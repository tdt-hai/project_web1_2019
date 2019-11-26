<?php
require_once 'init.php';
if ($currentUser) {
 header('Location: index.php');
 exit();
}
?>
<?php include 'header.php'; ?>

  <h1>Đăng nhập</h1>
  <?php if (isset($_POST['username']) && isset($_POST['password'])): ?>
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

  <?php if ($check): ?>
    <div class="alert alert-success" role="alert">
    <?php echo $noti_succ; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-danger" role="alert">
    <?php echo $noti_err; ?>
    </div>
  <?php endif; ?>

  <?php else: ?>
  <div>
    <form action="login.php" method="POST">
      <div class="form-group">
        <label for="username">Tên đăng nhập/Email</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Nhập username hoặc email">
      </div>
      <div class="form-group">
        <label for="password">Mật khẩu</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu">
      </div>
      <a href="findAccount.php">Quên mật khẩu?</a>
      <p>Chưa có tài khoản?<a href="register.php"> Đăng ký</a> </p>
      <button type="submit" class="btn btn-primary">Đăng nhập</button>
    </form>
  </div>

  <?php endif; ?>

<?php include 'footer.php'; ?>