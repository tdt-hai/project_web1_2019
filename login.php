<?php
require_once 'init.php';
if ($currentUser) {
    header('Location: index.php');
    exit();
}
?>
<?php include 'header.php'; ?>
<?php  
   global $noti_err;
?>
<div class="container-fluid">
    <div class="row">
        <div class="hold-transition register-page pull-left text-center col-sm-6">
            <div>
                <h3 class="text-primary">Lotus helps you connect and share with the people in your life.</h3>
                <img src="./css_files/imgs/globe.png" class="img-responsive" />
            </div>
        </div>
        <div class="hold-transition register-page  pull-right col-sm-6">
            <div class="login-box">
            <?php
                    $check  = false ;
                    if (isset($_POST['dangnhap'])) {
                        $email    = $username    = $_POST['username'];
                        $password = $_POST['password'];
                        $getUser = findUser($username);
                        if ($getUser['username'] != $username && $getUser['email'] != $email) {
                            ?>
                    <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Nguy hiểm!</h5>
                    Tài khoản không tồn tại
                    </div>
                    <?php
                        } else {
                            if (empty($email) || empty($password))
                            {   ?>
                    <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                    Không được bỏ trống
                    </div>
                    <?php
                            }
                            else if (!password_verify($password, $getUser['password'])) 
                            {
                                ?>
                    <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                    Mật khẩu không chính xác!
                    </div>
                    <?php
                            } 
                            else 
                            {
                                if ($getUser['confirmStatus'] != 1) 
                                {
                                    ?>
                    <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                    Tài khoản chưa được kích hoạt! Vui lòng kiểm tra lại email để kích hoạt tài khoản!
                    </div>
                    <?php
                                } 
                                else 
                                {
                                    $_SESSION['userID'] = $getUser['id'];
                                    header("Location: index.php");
                                }
                            }
                        }
                    }
            ?>
                <div class="login-logo">
                    <a >Lotus</a>
                </div>
                <!-- /.login-logo -->
                <div class="card">
                    <div class="card-body login-card-body">
                        <p class="login-box-msg">Đăng nhập</p>

                        <form action="login.php" method="POST">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Email hoặc tên đăng nhập" name="username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope "></span>
                                    </div>
                                    <div class="input-group-text">
                                        <span class="fas fa-user " ></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Mật khẩu" name="password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="remember">
                                        <label for="remember">
                                            Nhớ tài khoản
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <input type="submit" name="dangnhap" value="Đăng nhập" class="btn btn-primary btn-block" >
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                        <p class="mb-1">
                            <a href="findAccount.php">Tôi quên mật khẩu</a>
                        </p>
                        <p class="mb-0">
                            <a href="register.php" class="text-center">Tôi chưa có tài khoản</a>
                        </p>
                    </div>
                    <!-- /.login-card-body -->
                </div>
            </div>
            <!-- /.register-box -->
        </div>
    </div>
</div>
<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Success alert preview. This alert is dismissable.
                </div>
                <div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                  Warning alert preview. This alert is dismissable.
                </div>
<?php include 'footer.php'; ?>