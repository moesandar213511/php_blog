<?php 
  session_start();
  require '../config/config.php';
  // print_r($_SESSION);
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }

  if(!empty($_POST)){
      $id = $_POST['id'];
      $title = $_POST['title'];
      $content = $_POST['content'];
      $image = $_FILES['image']['name'];
      
      // if user upload img file,
      if($_FILES['image']['name'] != null){ 
        $file = "../images/".$_FILES['image']['name'];
        $imageType = pathinfo($file,PATHINFO_EXTENSION);

        if($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png'){
            echo "<script>alert('Image must be jpg,jpeg,png.');</script>";
        }else{
            move_uploaded_file($_FILES['image']['tmp_name'],$file);

            $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
            $result = $stmt->execute();
            if($result){
                echo "<script>alert('Successfully Updated.');window.location.href='index.php';</script>";
            }
        }
      // if user not upload img file,
      }else{ 
        $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id'");
        $result = $stmt->execute();
        if($result){
            echo "<script>alert('Successfully Updated.');window.location.href='index.php';</script>";
        }
      }
  }

  $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  
?>

  <?php include 'header.php'; ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                  <form class="" action="" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
                      <div class="form-group">
                          <label for="">Title</label>
                          <input type="text"  class="form-control" name="title" value="<?php echo $result['title'] ?>" required>
                      </div>
                      <div class="form-group">
                          <label for="">Content</label>
                          <textarea class="form-control" name="content" rows="8" cols="80" required><?php echo $result['content'] ?></textarea>
                      </div>
                      <div class="form-group">
                          <label for="">Image</label><br>
                          <img src="../images/<?php echo $result['image'] ?>" width="92px" height="80px" id="changeImg" alt=""><br>
                          <input type="file" id="img" name="image">
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
  
