<?php
require_once 'init.php';

if ($currentUser) {
    header('Location: index.php');
    exit();
}
?>
<?php include 'header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="hold-transition register-page pull-left text-center col-sm-7">
            <div>
                <h3 class="text-primary">Lotus helps you connect and share with the people in your life.</h3>
                <img src="./css_files/imgs/globe.png" class="img-responsive" />
            </div>
        </div>
        <div class="hold-transition register-page  pull-right col-sm-5">
            <div class="register-box">
            <?php
                if (isset($_POST['registers'])) {
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
                        ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                    Vui lòng nhập đầy đủ thông tin
                </div>
                <?php
                            } else {
                                // Validate pass
                                if (strlen(trim($password)) < 6 || strlen(trim($password)) > 15) {
                                    ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                    Vui lòng nhập mật khẩu từ 6 - 15 kí tự
                </div>
                <?php
                                    } else {
                                        // Validate rePass
                                        if ($getUserEmail || $getUserUsername) {
                                            if ($getUserEmail) {
                                                ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                    Email đã được đăng ký
                </div>
                <?php
                                                } elseif ($getUserUsername) {

                                                    ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                    Username đã tồn tại
                </div>
                <?php
                                                }
                                            } else {
                                                if (strlen(trim($phonenumber)) > 10) {
                                                    ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                    Số điện thoại không hợp lệ!
                </div>
                <?php
                                                } else {
                                                    $newUserID = createUser($f_name, $l_name, $email, $username, $password, '', $birthday, $phonenumber);
                                ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Thành công!</h5>
                  Đăng kí thành công. Kiểm tra email để kích hoạt tài khoản!
                </div>
                <?php
                                }
                            }
                        }
                    }
                }
            ?>
                <div class="register-logo">
                    <a ><b>Đăng kí tài khoản mới</a>
                </div>

                <div class="card">
                    <div class="card-body register-card-body">
                        <p class="login-box-msg">Hãy đăng kí vì nó miễn phí</p>
                        <form action="register.php" method="POST">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Họ"  name="f-name">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Tên"  name="l-name">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="date" class="form-control"  name="b-date">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-birthday-cake"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Số điện thoại" name="phoneNumber">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-phone-alt"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Tên đăng nhập" name="username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Email"  name="email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Mật khẩu"  name="password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                        <label for="agreeTerms">
                                            Đồng ý với điều khoản
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <input type="submit" name="registers" value="Đăng kí" class="btn btn-primary btn-block" />
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                        <a href="login.php" class="text-center">Tôi đã có tài khoản</a>
                    </div>
                    <!-- /.form-box -->
                </div><!-- /.card -->
            </div>
            <!-- /.register-box -->
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>