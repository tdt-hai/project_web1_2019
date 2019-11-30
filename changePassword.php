<?php
require_once 'init.php';

if (!$currentUser) {
 header('Location: index.php');
 exit();
}
?>
<?php include 'header.php'; ?>
<h1>Đổi mật khẩu</h1>
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
                    <div class=" pull-left col-sm-7"></div>
                    <div class="alert alert-primary pull-right col-sm-5" role="alert" >
                        Thay đổi mật khẩu thành công!
                    </div>
                <?php
            } else {
                ?>
                    <div class=" pull-left col-sm-7"></div>
                    <div class="alert alert-warning pull-right col-sm-5" role="alert" >
                     Mật khẩu xác nhận không chính xác!
                    </div>
                <?php
            }
        } else {
            ?>
                <div class=" pull-left col-sm-7"></div>
                <div class="alert alert-warning pull-right col-sm-5" role="alert" >
                    Mật khẩu mới phải khác mật khẩu cũ!
                </div>
            <?php
        }
        } else {
            ?>
                <div class=" pull-left col-sm-7"></div>
                <div class="alert alert-warning pull-right col-sm-5" role="alert" >
                    Vui lòng nhập mật khẩu từ 6 đến 15 ký tự!
                </div>
           <?php
        }
        } else {
            ?>
                <div class=" pull-left col-sm-7"></div>
                <div class="alert alert-warning pull-right col-sm-5" role="alert" >
                        Mật khẩu hiện tại không đúng!
                </div>
           <?php
        }
        } else {
            ?>
                <div class=" pull-left col-sm-7"></div>
                <div class="alert alert-warning pull-right col-sm-5" role="alert" >
                Vui lòng nhập đầy đủ thông tin!
                </div>
            <?php
        }
}
?>
<div>
    <form action="changePassword.php" method="POST">
        <div class="form-group">
            <label for="currentPass">Mật khẩu hiện tại</label>
            <input type="password" class="form-control" id="currentPass" name="currentPass"
                   placeholder="Mật khẩu hiện tại">
        </div>
        <div class="form-group">
            <label for="newPass">Mật khẩu mới</label>
            <input type="password" class="form-control" id="newPass" name="newPass"
                   placeholder="Mật khẩu mới">
        </div>
        <div class="form-group">
            <label for="re-password">Nhập lại mật khẩu mới</label>
            <input type="password" class="form-control" id="re-password" name="re-password"
                   placeholder="Xác nhận lại mật khẩu">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Xác nhận</button>
        </div>
    </form>
</div>
<?php include 'footer.php'; ?>