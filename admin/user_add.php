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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

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
                      <div class="form-group">
                          <label for="">Name</label>
                          <input type="text"  class="form-control" name="name" value="" required>
                      </div>
                      <div class="form-group">
                          <label for="">Email</label>
                          <input type="email"  class="form-control" name="email" value="" required>
                      </div>
                      <div class="form-group">
                          <label for="">Password</label><br>
                          <input type="password" class="form-control" name="password" value="" required>
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
