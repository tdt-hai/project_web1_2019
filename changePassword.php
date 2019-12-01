<?php
require_once 'init.php';

if (!$currentUser) {
 header('Location: index.php');
 exit();
}
?>
<?php include 'header.php'; ?>
<div class="hold-transition register-page">
    <div class="login-box">
    <?php
        if ("POST" == $_SERVER["REQUEST_METHOD"]){
                $currentPass = $_POST['currentPass'];
                $newPass     = $_POST['newPass'];
                $repass      = $_POST['re-password'];
                $check = false;
                // Check
                if (!empty(trim($currentPass)) && !empty(trim($newPass)) && !empty(trim($repass))) {
                if (password_verify($currentPass, $currentUser['password'])) {
                if (strlen(trim($newPass)) >= 6 && strlen(trim($newPass)) <= 15) {
                if ($currentPass != $newPass) {
                    if ($newPass == $repass) {
                        updateUserPassword($currentUser['email'], $newPass);
                        ?>
        <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Thành công!</h5>
                        Thay đổi mật khẩu thành công!
        </div>
        <?php
                    } else {
                        ?>
        <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                        Mật khẩu xác nhận không chính xác!
        </div>
        <?php
                    }
                } else {
                    ?>
         <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                        Mật khẩu mới phải khác mật khẩu cũ!
        </div>
        <?php
                }
                } else {
                    ?>
        <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                        Vui lòng nhập mật khẩu từ 6 đến 15 ký tự!
        </div>
        <?php
                }
                } else {
                    ?>
         <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                        Mật khẩu hiện tại không đúng!
        </div>
        <?php
                }
                } else {
                    ?>
        <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                        Vui lòng nhập đầy đủ thông tin!
        </div>
        <?php
                }
        }
    ?>

        <div class="login-logo">
            <a><b>Lotus</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Đổi mật khẩu tại đây :))
                </p>

                <form  action="changePassword.php" method="POST">
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Mật khẩu hiện tại" id="currentPass" name="currentPass">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Mật khẩu mới" id="newPass" name="newPass">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" id="re-password" name="re-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Thay đổi</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="login.php">Đăng nhập</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>