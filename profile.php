<?php
require_once 'init.php';
$user = findUserByID($_GET['id']);
//Nếu đăng nhập mới thấy file profile.php
if(!$currentUser)
{
    header("Location:login.php");
}
$relationship = findrelationship($currentUser['id'],$user['id']);
$isFriend = count($relationship) === 2;
$norelationship = count($relationship) === 0;
if (count($relationship) === 1)
{
    $isrequesting = $relationship[0]['UserID_1'] === $currentUser['id'];
}
?>
<?php include 'header.php'; ?>
<h1> Trang cá nhân của <?php echo $user['firstname'];echo $user['lastname'];?></h1>
<img style="width:80px;" src="<?php echo 'data:image/jpeg;base64,' . base64_encode($user['profilePicture']); ?>" class="card-img-top">
<?php if ($user['id'] != $currentUser['id']):?>
    <form action="friend.php" method="POSt">
        <input type="hidden" name ="id" value="<?php echo $user['id']; ?>">
        <?php if($isFriend): ?>
        <input type="submit" class="btn btn-danger" name = "action" value="Xóa bạn bè">
        <?php elseif($norelationship): ?>
        <input type="submit" class="btn btn-success" name = "action" value="Gửi yêu cầu kết bạn">
        <?php else:?>
        <?php if(!$isrequesting): ?>
        <input type="submit" class="btn btn-primary" name = "action" value="Đồng ý yêu cầu kết bạn">
        <?php endif;?>
        <input type="submit" class="btn btn-warning" name = "action" value="Hủy yêu cầu kết bạn">
    </form>
<?php endif; ?>
<?php endif;?>
<?php include 'footer.php'; ?>