<?php 
  session_start();
  require '../config/config.php';
  // print_r($_SESSION);
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
  if($_SESSION['role'] != 1){
    header('Location: login.php');
  }

  if(!empty($_POST)){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // print'<pre>';
    // print_r($_POST);
    
    // if(($_POST['role'] == '')){ 
    //     $role = 0;
    // }else{
    //     $role = 1;
    // }
    
    if(isset($_POST['role'])){ 
        $role = 1;
    }else{
        $role = 0;
    }

    // print_r($name);
    // print_r($email);
    // print_r($role);
    // die();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
    $stmt->bindValue(':email',$email);
    $stmt->bindValue(':id',$id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){ 
        echo "<script>alert('Email duplicated.');</script>";
    }else{ 
        // print_r($name);
        // print_r($email);
        // print_r($role);
        // die();
        
        $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
        $result = $stmt->execute();
        if($result){
            echo "<script>alert('Successfully User Updated.');window.location.href='user_list.php';</script>";
        }
    }

  }

  $stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
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
                          <label for="">Name</label>
                          <input type="text"  class="form-control" name="name" value="<?php echo $result['name'] ?>" required>
                      </div>
                      <div class="form-group">
                          <label for="">Email</label>
                          <input type="email"  class="form-control" name="email" value="<?php echo $result['email'] ?>" required>
                      </div>
                      <div class="form-group">
                          <label for="">Role</label><br>
                          <input type="checkbox" name="role" <?php echo ($result['role'] == 1) ? 'checked' : ''; ?> value="<?php echo $result['role'] ?>">
                      </div>
                      <!--  echo ($result['role'] == 1) ? 'checked' : ''; -->

                      <div class="form-group">
                          <input type="submit"  class="btn btn-success
                          " value="UPDATE">
                          <a href="user_list.php" class="btn btn-secondary">Back</a>
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
  