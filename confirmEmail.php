<?php
require_once 'init.php';
if ($currentUser) {
 header('Location: index.php');
 exit();
}
?>
<?php include 'header.php'; ?>

<h1>Kích hoạt tài khoản</h1>
<?php if (isset($_GET['activationCode'])): ?>
<?php
$activationCode = $_GET['activationCode'];
$check          = false;

$getUser       = findUserCode($activationCode);
$confirmStatus = $getUser['confirmStatus'];
$_SESSION['userEmail'] = $getUser['email'];

if ($activationCode == $getUser['activationCode']) {
 $check = confirmEmail($activationCode, $getUser['id']);
 if ($confirmStatus == 0) {
  $status = 0;
  $noti   = 'Kích hoạt tài khoản thành công!  <a href="login.php">Đăng nhập!</a>';
 } else {
  $status = 1;
 }
} else {
 $status = 1;
 $noti   = 'Link đã hết hạn sử dụng! <a href="findAccount.php"> Lấy lại mật khẩu</a>';
}

?>

<?php if ($check): ?>
<div class="alert alert-success" role="alert">
    <?php
if ($status == 0) {
 echo $noti;
} else {
 header("Location: recoverAccount.php");
}
?>
</div>
<?php else: ?>
<div class="alert alert-danger" role="alert">
    <?php
if ($status == 0) {
 echo 'Kích hoạt tài khoản thất bại!  <a href="index.php">Trang chủ</a>';
} else {
 echo $noti;
}
?>
</div>
<?php endif; ?>

<?php else: ?>
<div>
    <form method="GET">
        <div class="form-group">
            <label for="activationCode">Mã kích hoạt</label>
            <input type="text" class="form-control" id="activationCode" name="activationCode"
                   placeholder="Nhập mã kích hoạt">
        </div>
        <button type="submit" class="btn btn-primary">Xác nhận</button>
    </form>
</div>

<?php endif; ?>

<?php include 'footer.php'; ?>