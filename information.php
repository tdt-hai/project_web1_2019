<?php
require_once 'init.php';
ob_start();
$posts = showPost($_GET['id']);
////////////////////////////
$users = findUserByID($_GET['id']);
//Nếu đăng nhập mới thấy file profile.php
if (!$currentUser) {
    header("Location:login.php");
}
$relationship = findrelationship($currentUser['id'], $users['id']);
$isFriend = (count($relationship) === 2);
$norelationship = count($relationship) === 0;
if (count($relationship) === 1) {
    $isrequesting = $relationship[0]['UserID_1'] === $currentUser['id'];
}
////////////////
?>
<?php
$success = true;
if (isset($_POST['Posts'])) {
    $content = $_POST['content'];
    $lengh = strlen($content);
    if ($lengh == 0 || $lengh > 1024) {
        $success = false;
    } else {
        createPost($users['id'], $content);
        header('Location:information.php?id='.$currentUser['id']);
    }
}

?>
<?php include 'header.php'; ?>
<main class="container">
    <div class="row">
        <div class="col-md-3">
            <!-- edit profile -->
            <?php if($currentUser['id'] == $users['id']) {?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Trạng thái</h4>
                    <form method="POST" action="information.php?id=<?php echo $users['id']; ?> ">
                        <div class="form-group">
                            <textarea class="form-control" id="content" name="content" placeholder="Bạn đang nghĩ gì..."></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" name="Posts" value="Đăng">
                        </div>
                    </form>
                </div>
            </div>
            <?php }?>
            <?php if (!$success) : ?>
                <div class="alert alert-danger" role="alert">
                    Nội dung không được rỗng và dài quá 1024 ký tự!
                </div>
            <?php endif; ?>
            <!-- ./edit profile -->
        </div>
        <div class="col-md-6">
            <!-- user profile -->
            <div class="media">
                <div class="media-left">
                    <img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($users['profilePicture']); ?>" class="media-object" style="width: 128px; height: 128px;">
                </div>
                <div style="text-indent: 10px;">
                    <h2 class="media-heading"><?php echo $users['firstname'] . ' ' . $users['lastname']; ?></h2>
                    <h6> <strong>Ngày sinh:</strong> <?php echo $users['Birthday']; ?> </h6>
                    <h6><strong>Số điện thoại :</strong> <?php echo $users['phoneNumber']; ?> </h6>
                    <h6> <strong>Email:</strong> <a href="mailto:<?php echo $users['email']; ?>" target="_blank"><?php echo $users['email']; ?></a>
                </div>

            </div>
            <?php if ($users['id'] != $currentUser['id']) : ?>
                <form action="friend.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $users['id']; ?>">
                    <?php if ($isFriend) : ?>
                        <input type="submit" class="btn btn-danger" name="action" value="Xóa bạn bè">
                    <?php elseif ($norelationship) : ?>
                        <input type="submit" class="btn btn-success" name="action" value="Gửi yêu cầu kết bạn">
                    <?php else : ?>
                        <?php if (!$isrequesting) : ?>
                            <input type="submit" class="btn btn-primary" name="action" value="Đồng ý yêu cầu kết bạn">
                        <?php endif; ?>
                        <input type="submit" class="btn btn-warning" name="action" value="Hủy yêu cầu kết bạn">
                </form>
            <?php endif; ?>
            <?php endif; ?>
        <hr>

        <!-- timeline -->
        <div>
            <!-- post -->
            <!-- ./post -->
            <?php if($isFriend || ($users['id'] == $currentUser['id'])){?>
            <?php foreach ($posts as $post) : ?>
                <form method="POST">
                    <div class="panel panel-default">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <img style="width: 80px;" src="<?php echo 'data:image/jpeg;base64,' . base64_encode($post['profilePicture']); ?>" class="card-img-top" alt="<?php echo $post['firstname'] . ' ' . $post['lastname']; ?>">
                                    <?php echo $post['firstname'] . ' ' . $post['lastname']; ?>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        Đăng lúc: <?php echo $post['post_time']; ?>
                                    </h6>
                                </h5>
                                <p class="card-text">
                                    <?php echo $post['content']; ?>
                                </p>
                                <?php
                                    if (isset($_POST['delete'])) {
                                        $value = $_POST['delete'];
                                        DeleteContentbyID($value);
                                        header('Location:information.php?id='.$currentUser['id']);
                                    }
                                    ?>
                                <?php if ($currentUser['id'] == $users['id']){?>
                                <button type="submit" name="delete" value="<?php echo $post['postID'] ?>" class="btn btn-danger">Xóa</button>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endforeach; ?>
                                <?php }?>
        </div>
        </div>
        <div class="col-md-3">
            <!-- friends -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php $friends = getFriends($currentUser['id']);?>
                    <?php if (($currentUser['id'] == $users['id']) ){?>
                    <h4>List Friends</h4>
                    <ul>
                            <?php foreach ($friends as $friend) : ?>
                                <li>
                                <a href="information.php?id=<?php echo $friend['id']; ?>"><?php echo $friend['firstname'];echo $friend['lastname']; ?></a>
                                </li>
                            <?php endforeach; ?>
                    </ul>
                            <?php } ?>
                </div>
            </div>
            <!-- ./friends -->
        </div>
        <div>
</main>
<?php ob_end_flush(); ?>
<?php include 'footer.php'; ?>