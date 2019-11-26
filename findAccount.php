<?php
require_once 'init.php';
if ($currentUser) {
 header('Location: index.php');
 exit();
}
?>
<?php include 'header.php'; ?>

<h1>Quên mật khẩu</h1>
<?php if (isset($_POST['account'])): ?>
<?php
$account = $_POST['account'];
$check   = false;
$isConfirmed = false;

$getUser = findUser($account);

if ($getUser) {
  $activationCode = generateCode(16);

  addRecoverCode($activationCode, $getUser['email'], $getUser['username'], $getUser['lastname']);

  $noti = 'Mã xác thực đã được gửi về email của bạn. <a href=index.php">Trang chủ</a>';
  $check = true;
}
else {
  $noti = 'Tài khoản không tồn tại. <a href="findAccount.php"> Trở lại</a>';
}

?>

<?php if ($check): ?>
<div class="alert alert-success" role="alert">
    <?php echo $noti; ?>
</div>
<?php else: ?>
<div class="alert alert-danger" role="alert">
    <?php echo $noti; ?>
</div>
<?php endif; ?>

<?php else: ?>
<div>
    <form method="POST">
        <div class="form-group">
            <label for="account">Email của bạn</label>
            <input type="email" class="form-control" id="account" name="account"
                   placeholder="Email">
        </div>
        <button type="submit" class="btn btn-primary">Xác nhận</button>
    </form>
</div>

<?php endif; ?>

<?php include 'footer.php'; ?>