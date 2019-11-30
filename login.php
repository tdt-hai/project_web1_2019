<?php
require_once 'init.php';
if ($currentUser) {
    header('Location: index.php');
    exit();
}
?>

<?php include 'header.php'; ?>
<link rel="stylesheet" href="./css_files/css/style.css" />
<?php  
//  global $check;
   global $noti_err;
//         global $noti_succ;
       
?>

<?php
    $check  = false ;
if (isset($_POST['dangnhap'])) {
    $email    = $username    = $_POST['username'];
    $password = $_POST['password'];
    $getUser = findUser($username);
    if ($getUser['username'] != $username && $getUser['email'] != $email) {
        ?>
            <div class=" pull-left col-sm-7"></div>
            <div class="alert alert-warning pull-right col-sm-5" role="alert" >
                Tài khoản không tồn tại
            </div>
        <?php
    } else {
        if (empty($email) || empty($password))
        {   ?>
            <div class=" pull-left col-sm-7"></div>
            <div class="alert alert-warning pull-right col-sm-5" role="alert">
                Không được bỏ trống
            </div>
            <?php
        }
        else if (!password_verify($password, $getUser['password'])) 
        {
            ?>
                <div class=" pull-left col-sm-7 pull-right col-sm-5"></div>
                <div class="alert alert-warning" role="alert">
                Mật khẩu không chính xác!
                </div>
            <?php
        } 
        else 
        {
            if ($getUser['confirmStatus'] != 1) 
            {
                ?>
                <div class=" pull-left col-sm-7 pull-right col-sm-5"></div>
                <div class="alert alert-warning" role="alert">
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

<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="pull-left text-center col-sm-7">
                <div>
                    <h3 class="text-primary">Lotus helps you connect and share with the people in your life.</h3>
                    <img src="./css_files/imgs/x.png" class="img-responsive" />
                </div>
            </div>
            <div class="pull-right col-sm-5">
                <div class="signup-form">
                    <h1>Đăng nhập</h1>
                    <form action="login.php" method="POST">
                        <div class="form-group">
                            <input class="input-lg col-sm-12" type="text" id="username" name="username" placeholder="Nhập username hoặc email">
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Mật khẩu" name="password" class="input-lg col-sm-12" />
                        </div>
                        <div class="form-group">
                            <small class="text-mute">By clicking Create Account, you agree to our Terms and confirm that you have read our Data Policy, including our Cookie Use Policy. You may receive SMS message notifications from Facebook and can opt out at any time.</small>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="dangnhap" value="Đăng nhập" class="btn btn-success input-lg" data-toggle="modal" data-target="#exampleModal">
                            <p><br>Bạn chưa có tài khoản? <a href="register.php">Đăng kí</a></p>
                            <a href="findAccount.php">Quên mật khẩu?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>