<?php 
  session_start();
  require "config/config.php";
  require 'config/common.php';

  if(!empty($_POST)){
    // print'<pre>';
    // print_r($_POST);
    // exit();

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

      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
      $stmt->bindValue(':email',$email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      if($user){
          echo "<script>alert('Email have already exists.'); window.location.href = 'register.php'</script>";
      }else{
          $stmt = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES(:name,:email,:password,:role)");
          $result = $stmt->execute(
              array(':name' => $name,':email' => $email, ':password' => $password, 
              ':role' => 0)
          );
          if($result){
              echo "<script>alert('Successfully Registered. You can login now!');window.location.href = 'login.php'</script>";
          }
      }
    }
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User | Register</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Blog</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Register New Account</p>

      <form action="register.php" method="post">
        <!-- config/common.php -->
        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
        <p style="color: red;"><?php echo empty($nameError) ? '' : "*".$nameError; ?></p>
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>

        <p style="color: red;"><?php echo empty($emailError) ? '' : "*".$emailError; ?></p>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <p style="color: red;"><?php echo empty($passwordError) ? '' : "*".$passwordError; ?></p>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="container">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <a href="login.php" type="button" class="btn btn-secondary btn-block">Login</a>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- /.social-auth-links -->
<!-- <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
