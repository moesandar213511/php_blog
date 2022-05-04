<?php 
  session_start();
  require '../config/config.php';
  require '../config/common.php';

  // print_r($_SESSION);
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
  if($_SESSION['role'] != 1){
    header('Location: login.php');
  }

  if(!empty($_POST)){
    // backend validation
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 6){
      // || strlen($_POST['password']) < 6
      if(empty($_POST['name'])){
        $nameError = "Name can't be empty";
      }
      if(empty($_POST['email'])){
        $emailError = "Email can't be empty";
      }
      if(empty($_POST['password'])){
        $passwordError = "Password can't be empty";
      }
      if(strlen($_POST['password']) < 6){
        $passwordError = "Password must be 6 characters at least";
      }
    }else{
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

      if(empty($_POST['role'])){ // if check is not on & is not admin,
          $role = 0;
      }else{ // if is admin,
          $role = 1;
      }

      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
      $stmt->bindValue(':email',$email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if($user){ 
        echo "<script>alert('Email duplicated.'); window.location.href = 'user_add.php'</script>";
      }else{
        $stmt = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES(:name,:email,:password,:role)");
        $result = $stmt->execute(
            array(':name' => $name,':email' => $email, ':password' => $password, ':role' => $role)
        );
        if($result){
            echo "<script>alert('Successfully User Added.');window.location.href='user_list.php';</script>";
        }
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
                  <form class="" action="user_add.php" method="post" enctype="multipart/form-data">
                      <!-- config/common.php -->
                      <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                      <div class="form-group">
                          <label for="">Name</label><br>
                          <p style="color: red;"><?php echo empty($nameError) ? '' : "*".$nameError; ?></p>
                          <input type="text"  class="form-control" name="name" value="">
                      </div>
                      <div class="form-group">
                          <label for="">Email</label><br>
                          <p style="color: red;"><?php echo empty($emailError) ? '' : "*".$emailError; ?></p>
                          <input type="email"  class="form-control" name="email" value="">
                      </div>
                      <div class="form-group">
                          <label for="">Password</label><br>
                          <p style="color: red;"><?php echo empty($passwordError) ? '' : "*".$passwordError; ?></p>
                          <input type="password" class="form-control" name="password" value="">
                      </div>
                      <div class="form-group">
                          <label for="">Role</label><br>
                          <input type="checkbox" name="role" value="1">
                      </div>
                      <div class="form-group">
                          <input type="submit"  class="btn btn-success
                          " value="SUBMIT">
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
