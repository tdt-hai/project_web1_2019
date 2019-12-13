<?php
require_once 'init.php';
if (isset($_POST['send']) ){
  sendMessage($currentUser['id'], $_POST['UserID'], $_POST['content']);
  header('Location: conversation.php?id=' . $_POST['UserID']);
}
$friends = getFriends($currentUser['id']);
?>
<?php  include 'header.php'?>
<div class="container">
    <form method="POST">
        <div class="form-group">
            <label for="userId">Người nhận</label>
            <select class="form-control" name="UserID">
            <?php foreach($friends as $friend) : ?>
            <?php
                $user = findUserByID($friend['id']);
            ?>
            <option value="<?php echo $user['id'] ?>"><?php echo $user['firstname'].''.$user['lastname'] ?></option>
            <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="content">Tin nhắn:</label>
            <textarea class="form-control" id="content" name="content" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary" name="send">Gửi tin nhắn</button>
    </form>
    </div>
<?php include 'footer.php'?>