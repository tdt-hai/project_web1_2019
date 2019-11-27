<?php
require_once 'init.php';

if ($currentUser) {
    header('Location: index.php');
    exit();
}
?>
<?php include 'header.php'; ?>
<link rel="stylesheet" href="./css_files/css/style.css" />

<?php if ("POST" == $_SERVER["REQUEST_METHOD"]) : ?>
    <?php
        $f_name   = $_POST['f-name'];
        $l_name   = $_POST['l-name'];
        $birthday = $_POST['b-date'];
        $phonenumber = $_POST['phoneNumber'];
        $email    = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $check    = false;

        // Get username/email
        $getUserEmail    = findUser($email);
        $getUserUsername = findUser($username);

        if (empty(trim($username)) || empty(trim($f_name)) || empty(trim($l_name)) || empty(trim($email)) || empty(trim($password)) || empty(trim($birthday)) || empty(trim($phonenumber))) {
            $noti_fail = 'Vui lòng nhập đầy đủ thông tin';
            $check     = false;
        } else {
            // Validate pass
            if (strlen(trim($password)) < 6 || strlen(trim($password)) > 15) {
                $noti_fail = 'Vui lòng nhập mật khẩu từ 6 đến 15 ký tự';
                $check     = false;
            } else {
                // Validate rePass
                if ($getUserEmail || $getUserUsername) {
                    if ($getUserEmail) {
                        $noti_fail = 'Email đã được đăng ký. <a href="login.php">Đăng nhập?</a>';
                        $check     = false;
                    } elseif ($getUserUsername) {
                        $noti_fail = 'Username đã tồn tại. <a href="login.php">Đăng nhập?</a>';
                        $check     = false;
                    }
                } else {
                    if (strlen(trim($phonenumber)) > 10) {
                        $noti_fail = 'Số điện thoại không hợp lệ!';
                        $check     = false;
                    } else {
                        $newUserID = createUser($f_name, $l_name, $email, $username, $password, '', $birthday, $phonenumber);
                        $noti_succ = 'Đăng kí thành công. Kiểm tra email để kích hoạt tài khoản! <a href="index.php">Trở về trang chủ?</a>';
                        $check     = true;
                    }
                }
            }
        }
        ?> 
        <?php if ($check) : ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $noti_succ; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $noti_fail; ?>
                            </div>
        <?php endif; ?>
        <?php else: ?> 
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
                        <h1>Đăng kí tài khoản mới</h1>
                        <p class="h3">Hãy đăng kí vì nó miễn phí.</p>
                        

                        <form action="register.php" method="POST">
                            <div class="form-group">
                                <input type="text" placeholder="Họ" name="f-name" class="input-lg col-sm-6" />
                                <input type="text" placeholder="Tên" name="l-name" class="input-lg col-sm-6" />
                            </div>
                            <div class="form-group">
                                <input class="input-lg col-sm-12" type="text" id="username" name="username" placeholder="Tên đăng nhập">
                            </div>
                            <div class="form-group">
                                <input type="email" placeholder="Email" name="email" class="input-lg col-sm-12" />
                            </div>
                            <div class="form-group">
                                <input type="password" placeholder="Mật khẩu" name="password" class="input-lg col-sm-12" />
                            </div>
                            <div class="form-group">
                                <input class="input-lg col-sm-12" type="date" id="b-date" name="b-date" placeholder="">
                            </div>
                            <div class="form-group">
                                <input class="input-lg col-sm-12" type="text" id="phoneNumber" name="phoneNumber" placeholder="Số điện thoại">
                            </div>

                            <div class="form-group">
                                <small class="text-mute">By clicking Create Account, you agree to our Terms and confirm that you have read our Data Policy, including our Cookie Use Policy. You may receive SMS message notifications from Facebook and can opt out at any time.</small>
                            </div>
                            
                            <div class="form-group">
                                <input type="submit" value="Đăng kí" class="btn btn-success input-lg" />
                                <p><br>Bạn đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
<?php //endif; ?>
<?php include 'footer.php'; ?>