<?php
require_once 'init.php';
$user = findUserByID($_POST['id']);
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
if($_POST['action'] === 'Gửi yêu cầu kết bạn')
{
    Send_Accept_FriendRequest($currentUser['id'],$user['id']);
}
if($_POST['action'] === 'Hủy yêu cầu kết bạn' || $_POST['action'] === 'Xóa bạn bè')
{
    cancel_delete_FriendRequest($currentUser['id'],$user['id']);
}
if($_POST['action'] === 'Đồng ý yêu cầu kết bạn')
{
    Send_Accept_FriendRequest($currentUser['id'],$user['id']);
}
header('Location:information.php?id='.$user['id']);
?>