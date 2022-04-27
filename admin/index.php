<?php 
  session_start();
  require '../config/config.php';
  // print_r($_SESSION);
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
?>

  <?php include 'header.php'; ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Blogs Listings</h3>
              </div>

              <?php 
                  if(!empty($_GET['pageno'])){
                    $pageno = $_GET['pageno'];
                  }else{
                    $pageno = 1;
                  }
                  $numOfRecords = 10;
                  $offset = ($pageno-1)*$numOfRecords;

                  if(empty($_POST['search'])){
                    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
                    $stmt->execute();
                    $rawResult = $stmt->fetchAll();
                    $total_pages = ceil(count($rawResult)/$numOfRecords);

                    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfRecords");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                  }else{
                    $search = $_POST['search'];
                    $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$search%' ORDER BY id DESC");
                    $stmt->execute();
                    $rawResult = $stmt->fetchAll();
                    $total_pages = ceil(count($rawResult)/$numOfRecords);

                    $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfRecords");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                  }
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="add.php" type="button" class="btn btn-success">Create Blog Post</a>
                </div>
                <br>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $i = 1;
                      if($result){
                        foreach ($result as $value){
                      
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['title']; ?></td>
                      <td><?php echo substr($value['content'],0,80) ?></td>
                      <td style="width:10%;">
                        <!-- <div class="row">
                          <a href="#" type="button" class="btn btn-warning"><i class="fas fa-edit"></i><a>&nbsp;&nbsp;
                          <a href="#" type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i><a>
                        </div> -->
                        <div class="btn-group">
                          <div class="container">
                            <a href="edit.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-warning"><i class="fas fa-edit"></i><a>
                          </div>
                          <div class="container">
                            <a href="delete.php?id=<?php echo $value['id'] ?>" onclick="return confirm('Are you sure you want to delete this item')" type="button" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php 
                      $i++;
                      }
                    }else{
                    ?>
                        <tr>
                          <td rowspan="4" style="text-align:center;">No Data</td>
                        </tr>
                    <?php
                    }
                    ?>
                    
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <!-- <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
              </div> -->
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                  <li class="page-item <?php if($pageno <= 1){ echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno <= 1){ echo '#';}else{ echo "?pageno=".($pageno-1); } ?>">Previous</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                  <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#';}else{ echo "?pageno=".($pageno+1); } ?>">Next</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="<?php echo "?pageno=".$total_pages ?>">Last</a></li>
                </ul>
              </div>
            </div>
  
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <?php include 'footer.php'; ?>


  <!-- 
  $numOfRecords = 10;
  page no 1 => 1 to 10
          2 => 11 to 20
          3 => 21 to 30
  $offset = ($pageno-1)*$numOfRecords;
   
  -->