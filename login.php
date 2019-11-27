<?php
require_once 'init.php';
if ($currentUser) {
    header('Location: index.php');
    exit();
}
?>
<?php include 'header.php'; ?>
<link rel="stylesheet" href="./css_files/css/style.css" />

<?php if (isset($_POST['username']) && isset($_POST['password'])) : ?>
    <?php
        $email    = $username    = $_POST['username'];
        $password = $_POST['password'];
        $check    = false;

        $getUser = findUser($username);

        if ($getUser['username'] != $username && $getUser['email'] != $email) {
            $noti_err = 'Tài khoản không tồn tại! <a href="register.php">Đăng ký tài khoản ngay tại đây.</a>';
        } else {
            if (!password_verify($password, $getUser['password'])) {
                $noti_err = 'Mật khẩu không chính xác!';
            } else {
                if ($getUser['confirmStatus'] != 1) {
                    $noti_err = 'Tài khoản chưa được kích hoạt! Vui lòng kiểm tra lại email để kích hoạt tài khoản!</a>';
                } else {
                    $check              = true;
                    $noti_succ          = 'Đăng nhập thành công! <a href="index.php">Trở về trang chủ.</a>';
                    $_SESSION['userID'] = $getUser['id'];
                }
            }
        }
        ?>
    <?php if ($check) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $noti_succ; ?>
        </div>
    <?php else : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $noti_err; ?>
        </div>
    <?php endif; ?>
<?php else : ?>
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
                                <input type="submit" value="Đăng nhập" class="btn btn-success input-lg" />
                                <p><br>Bạn chưa có tài khoản? <a href="register.php">Đăng kí</a></p>
                                <a href="findAccount.php">Quên mật khẩu?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php //else : 
        ?>

<?php endif; ?>
<?php include 'footer.php'; ?>