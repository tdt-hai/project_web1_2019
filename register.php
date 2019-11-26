<?php
require_once 'init.php';

if ($currentUser) {
 header('Location: index.php');
 exit();
}
?>
<?php include 'header.php'; ?>

<h1>Đăng ký</h1>
<?php if ("POST" == $_SERVER["REQUEST_METHOD"]): ?>
<?php
$f_name   = $_POST['f-name'];
$l_name   = $_POST['l-name'];
$birthday = $_POST['b-date'];
$phonenumber = $_POST['phoneNumber'];
$email    = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$repass   = $_POST['re-password'];

$check    = false;

// Get username/email
$getUserEmail    = findUser($email);
$getUserUsername = findUser($username);

if (empty(trim($username)) || empty(trim($f_name)) || empty(trim($l_name)) || empty(trim($email)) || empty(trim($password)) || empty(trim($repass)) || empty(trim($birthday)) || empty(trim($phonenumber))) {
    $noti_fail = 'Vui lòng nhập đầy đủ thông tin';
    $check     = false;
} else {
// Validate pass
 if (strlen(trim($password)) < 6 || strlen(trim($password)) > 15) {
        $noti_fail = 'Vui lòng nhập mật khẩu từ 6 đến 15 ký tự';
        $check     = false;
 } else {
  // Validate rePass
  if (empty($noti_err_pass) && ($password != $repass)) {
        $noti_fail = 'Mật khẩu xác nhận không khớp!';
        $check     = false;
  } else {
   if ($getUserEmail || $getUserUsername) {
            if ($getUserEmail) {
            $noti_fail = 'Email đã được đăng ký. <a href="login.php">Đăng nhập?</a>';
            $check     = false;
            } elseif ($getUserUsername) {
            $noti_fail = 'Username đã tồn tại. <a href="login.php">Đăng nhập?</a>';
            $check     = false;
            }
   } else {
       if(strlen(trim($phonenumber)) > 10){
        $noti_fail = 'Số điện thoại không hợp lệ!';
        $check     = false;
       }
       else{
    $newUserID = createUser($f_name, $l_name, $email, $username, $password,'',$birthday,$phonenumber);
    $noti_succ = 'Đăng kí thành công. Kiểm tra email để kích hoạt tài khoản! <a href="index.php">Trở về trang chủ?</a>';
    $check     = true;
       }
   }
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
    <?php echo $noti_fail; ?>
</div>
<?php endif; ?>

<?php else: ?>
<div>
    <form action="register.php" method="POST">

        <div class="form-group">
            <div class="row">
                <div class="col">
                    <label for="f-name">Họ</label>
                    <input type="text" class="form-control" id="f-name" name="f-name"
                           placeholder="Họ">
                </div>
                <div class="col">
                    <label for="l-name">Tên</label>
                    <input type="text" class="form-control" id="l-name" name="l-name"
                           placeholder="Tên">
                </div>
                <div class="col">
                    <label for="b-date">Ngày sinh</label>
                    <input type="date" class="form-control" id="b-date" name="b-date"
                           placeholder="">
                </div>
                <div class="col">
                    <label for="phoneNumber">Số điện thoại</label>
                    <input type="text" class="form-control" id="phoneNumber" name="phoneNumber"
                           placeholder="">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                   placeholder="name@example.com">
        </div>

        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">@</span>
                </div>
                <input type="text" class="form-control" placeholder="Username" id="username"
                       name="username" aria-label="Username" aria-describedby="basic-addon1">
            </div>
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password"
                   placeholder="Mật khẩu">
        </div>

        <div class="form-group">
            <label for="re-password">Nhập lại mật khẩu</label>
            <input type="password" class="form-control" id="re-password" name="re-password"
                   placeholder="Xác nhận lại mật khẩu">
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Đăng kí</button>
            <p><br>Bạn đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
        </div>
    </form>
</div>

<?php endif; ?>

<?php include 'footer.php'; ?>