<?php 
  session_start();
  require 'config/config.php';
  // print_r($_SESSION);
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User | Home</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Main Sidebar Container -->
  

  <!-- Content Wrapper. Contains page content -->
  <div class="">
    <!-- Content Header (Page header) -->
    <section class="">
      <div class="container-fluid">
        <h1 style="margin: 15px 0;text-align:center;">Blog Site</h1>
      </div><!-- /.container-fluid -->
    </section>
    <?php
      if(!empty($_GET['pageno'])){
        $pageno = $_GET['pageno'];
      }else{
        $pageno = 1;
      }
      $numOfRecords = 9;
      $offset = ($pageno-1)*$numOfRecords;

      $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
      $stmt->execute();
      $rawResult = $stmt->fetchAll();
      $total_pages = ceil(count($rawResult)/$numOfRecords);

      $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfRecords");
      $stmt->execute();
      $result = $stmt->fetchAll();

      // $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
      // $stmt->execute();
      // $result = $stmt->fetchAll();
    ?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- /.row -->
        <div class="row">
          <?php
            if($result){
              foreach ($result as $value){
            
          ?>
                    <div class="col-md-4">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="card-title" style="text-align:center;float:none;">
                  <h4><?php echo escape($value['title']) ?></h4>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a href="blog_detail.php?id=<?php echo $value['id']; ?>">
                  <img class="img-fluid pad" src="images/<?php echo $value['image'] ?>" style="heigth:300px !important;" id="changeImg" alt="">
                </a>
                <!-- <p>I took this photo this morning. What do you guys think?</p> -->
              </div>
              
            </div>
            <!-- /.card -->
          </div>

          <?php 
            }
          }
          ?>

          <!-- /.col -->
        </div>
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
        <!-- /.row -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="margin-left:0 !important">
    <div class="float-right d-none d-sm-block">
      <b><a href="logout.php">Logout</a></b>
    </div>
    <strong>Copyright &copy; 2022 <a href="https://adminlte.io">Moe Sandar</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
