<?php
require_once 'init.php';

$posts = showPost($currentUser['id']);
//$posts2 = showPost2($currentUser['id']);
?>
<?php
$success = true;
if (isset($_POST['Posts'])) {
    $content = $_POST['content'];
    $lengh = strlen($content);
    if ($lengh == 0 || $lengh > 1024) {
        $success = false;
    } else {
        createPost($currentUser['id'], $content);
        header("Location: information.php");
    }
}

?>
<?php include 'header.php'; ?>
<main class="container">
    <div class="row">
        <div class="col-md-3">
            <!-- edit profile -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Trạng thái</h4>
                    <form method="post" action="">
                        <div class="form-group">
                            <textarea class="form-control" id="content" name="content" placeholder="Bạn đang nghĩ gì..."></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" name="Posts" value="Đăng">
                        </div>
                    </form>
                </div>
            </div>
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
                    <img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($currentUser['profilePicture']); ?>" class="media-object" style="width: 128px; height: 128px;">
                </div>
                <div style="text-indent: 10px;">
                    <h2 class="media-heading"><?php echo $currentUser['firstname'] . ' ' . $currentUser['lastname']; ?></h2>
                    <h6> <strong>Ngày sinh:</strong> <?php echo $currentUser['Birthday']; ?> </h6>
                    <h6><strong>Số điện thoại :</strong> <?php echo $currentUser['phoneNumber']; ?> </h6>
                    <h6> <strong>Email:</strong> <a href="mailto:<?php echo $currentUser['email']; ?>" target="_blank"><?php echo $currentUser['email']; ?></a>
                </div>

            </div>

            <hr>

            <!-- timeline -->
            <div>
                <!-- post -->
                <!-- ./post -->
                <?php foreach ($posts as $post) : ?>
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
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-3">
            <!-- friends -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Friends</h4>
                    <ul>
                        <li>
                            <a href="#">HaiMit</a>
                            <a class="text-danger" href="#">unfriend</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- ./friends -->
        </div>
        <div>
</main>

<?php include 'footer.php'; ?>