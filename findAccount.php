<?php
require_once 'init.php';
if ($currentUser) {
 header('Location: index.php');
 exit();
}
?>
<?php include 'header.php'; ?>
<div class="hold-transition register-page">
    <div class="login-box">
    <?php
        if (isset($_POST['account'])){
            $account = $_POST['account'];
            $check   = false;
            $isConfirmed = false;
            $getUser = findUser($account);
            if ($getUser) {
                $activationCode = generateCode(16);
                addRecoverCode($activationCode, $getUser['email'], $getUser['username'], $getUser['lastname']);
                $check = true;
                ?>
                <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Thành công!</h5>
                        Mã xác thực đã được gửi về email của bạn.Vui lòng kiểm tra email
                </div>
                <?php
            } else {
                ?>
                 <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
                        Tài khoản không tồn tại
                </div>
                <?php
            }
        }
    ?>
        <div class="login-logo">
            <a><b>Lotus</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Bạn quên mật khẩu? Tại đây bạn có thể dễ dàng lấy lại mật khẩu mới</p>
                <form method="POST">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" id="account" name="account">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="login.php">Tôi đã có tài khoản</a>
                </p>
                <p class="mb-0">
                    <a href="register.php" class="text-center">Tôi chưa có tài khoản</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>