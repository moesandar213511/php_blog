<?php 
  session_start();
  require '../config/config.php';
  // print_r($_SESSION);
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }

  if(!empty($_POST)){
    $file = "../images/".$_FILES['image']['name'];
    $imageType = pathinfo($file,PATHINFO_EXTENSION);

    if($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png'){
        echo "<script>alert('Image must be jpg,jpeg,png.');</script>";
    }else{
        $title = $_POST['title'];
        $content = $_POST['content'];
        $image = $_FILES['image']['name'];

        move_uploaded_file($_FILES['image']['tmp_name'],$file);

        $stmt = $pdo->prepare("INSERT INTO posts(title,content,author_id,image) VALUES(:title,:content,:author_id,:image)");
        $result = $stmt->execute(
            array(':title' => $title,':content' => $content, ':author_id' => $_SESSION['user_id'], ':image' => $image)
        );
        if($result){
            echo "<script>alert('Successfully Added.');</script>";
        }
    }
  }

?>

  <?php include 'header.php'; ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                  <form class="" action="add.php" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                          <label for="">Title</label>
                          <input type="text"  class="form-control" name="title" value="" required>
                      </div>
                      <div class="form-group">
                          <label for="">Content</label>
                          <textarea class="form-control" name="content" rows="8" cols="80" required></textarea>
                      </div>
                      <div class="form-group">
                          <label for="">Image</label><br>
                          <input type="file" name="image" value="" required>
                      </div>
                      <div class="form-group">
                          <input type="submit"  class="btn btn-success
                          " value="SUBMIT">
                          <a href="index.php" class="btn btn-secondary">Back</a>
                      </div>
                  </form>
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