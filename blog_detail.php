<?php 
  session_start();
  require 'config/config.php';
  // print_r($_SESSION);
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }

  // select post by id
  $stmt1 = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $stmt1->execute();
  $result = $stmt1->fetch(PDO::FETCH_ASSOC);

  // select comment
  $stmt2 = $pdo->prepare("SELECT * FROM comments WHERE post_id=".$_GET['id']);
  $stmt2->execute();
  $comResult = $stmt2->fetchAll();
  
  // print'<pre>';
  // print_r($comResult);
  // echo "<hr>";

  $authorResult = [];
  if($comResult){
    // select author name in comment
    foreach($comResult as $key => $value){
      $author_id = $comResult[$key]['author_id'];
      $stmt3 = $pdo->prepare("SELECT * FROM users WHERE id=".$author_id);
      $stmt3->execute();
      $authorResult[] = $stmt3->fetchAll();
    }
  }



  // insert comments in db
  if(!empty($_POST)){
    if(empty($_POST['comment'])){
      $cmtError = "Comment can't be empty";
    }else{
      $comment = $_POST['comment'];
      $post_id = $_GET['id'];

      $stmt = $pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES(:content,:author_id,:post_id)");
      $com = $stmt->execute(
          array(':content' => $comment,':author_id' => $_SESSION['user_id'], ':post_id' => $post_id)
      );
      if($com){
          header('Location:blog_detail.php?id='.$post_id);
      }
    }
  }
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User | Blog Detail</title>

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
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- /.row -->
        <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="card-title" style="text-align:center;float:none;">
                  <h4><?php echo $result['title'] ?></h4>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid pad" src="images/<?php echo $result['image'] ?>" alt="Photo"> <br><br>

                <p><?php echo $result['content']; ?></p>
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i> Share</button>
                <button type="button" class="btn btn-default btn-sm"><i class="far fa-thumbs-up"></i> Like</button>
                <span class="float-right text-muted">127 likes - 3 comments</span><br><br>
                <h3>Comments</h3><hr>
                <a href="index.php" class="btn btn-default" type="button">Back</a>
              </div>


              <div class="card-footer card-comments">
                <div class="card-comment">
                  <!-- User image -->
                  <!-- <img class="img-circle img-sm" src="dist/img/user3-128x128.jpg" alt="User Image"> -->

                  <div class="comment-text" style="margin-left: 0 !important">
                  <?php if($comResult){ ?>
                    <?php foreach ($comResult as $key => $value) { ?>
                        <span class="username">
                        <?php echo $authorResult[$key][0]['name'] ?>
                        <span class="text-muted float-right">
                        <?php echo $value['created_at'] ?>
                        </span>
                        </span><!-- /.username -->
                      <?php echo $value['content'] ?>
                    <?php } ?>
                    
                  <?php }else{ ?>
                  <h4>No comment</h4>
                  <?php } ?>
                  </div>
                  <!-- /.comment-text -->
                </div>
                <!-- /.card-comment -->
              </div>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="" method="post">
                  <!-- <img class="img-fluid img-circle img-sm" src="dist/img/user5-128x128.jpg" alt="Alt Text"> -->
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <div class="img-push">
                    <p style="color: red;"><?php echo empty($cmtError) ? '' : "*".$cmtError; ?></p>
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                </form>
              </div>
              <!-- card footer -->
            </div>
            <!-- /.card -->
          </div>

          <!-- /.col -->
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
