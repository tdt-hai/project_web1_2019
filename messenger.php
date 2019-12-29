<?php
require_once 'init.php';
$conversations = getLatestConversations($currentUser['id']);

?>
<?php include 'header.php' ?>
<div class="container">
<h1>Danh sách tin nhắn</h1>
<a href="new-message.php" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Thêm cuộc trò
    chuyện</a>
<?php foreach ($conversations as $conversation) : ?>

<div class="card" style="margin-bottom: 10px;">
    <div class="card-body">
        <h4 class="card-title">
            <div class="row">
                <div class="col-sm-0  ">
                    <?php if ($conversation['profilePicture']) : ?>
                    <img class="direct-chat-img"
                        src="<?php echo 'data:image/jpeg;base64,' . base64_encode($conversation['profilePicture']); ?>"
                        alt="Message User Image">
                    <?php else : ?>
                    <img class="avatar" src="no-avatar.jpg">
                    <?php endif; ?>
                </div>
                <div class="col-md-7">
                    <a
                        href="conversation.php?id=<?php echo $conversation['id'] ?>"><?php echo $conversation['firstname'].''.$conversation['lastname'] ?></a>
                </div>
            </div>
        </h4>
        <p class="card-text">
            <small>Tin nhắn cuối: <?php echo $conversation['lastMessage']['CreateTime'] ?></small>
            <p><?php echo $conversation['lastMessage']['Content'] ?></p>
        </p>
    </div>
</div>

<?php endforeach; ?>
</div>
<?php include 'footer.php' ?>