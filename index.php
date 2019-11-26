<?php
require_once 'init.php';
?>
<?php include 'header.php'; ?>
<?php $posts = getNewsFeed(); ?>
<?php
$success = true;
if (isset($_POST['Posts'])) {
    $content = $_POST['content'];
    $lengh = strlen($content);
    if ($lengh == 0 || $lengh > 1024) {
        $success = false;
    } else {
        createPost($currentUser['id'], $content);
        header('Location: index.php');
    }
}
?>
<div class="col-md-6 offset-md-3">
    <?php if ($currentUser) : ?>
        <h3>Chào mừng <?php echo $currentUser['firstname'] . ' ' . $currentUser['lastname']; ?> đã trở lại!
        </h3>
        <div class="row-">
            <form method="POST">
                <div class="col-sm-12" style="margin-bottom: 10px;">
                    <div class="input-group mb-3">
                        <textarea class="form-control" id="content" name="content" placeholder="Bạn đang nghĩ gì..."></textarea>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" name="Posts">Đăng</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php if (!$success) : ?>
            <div class="alert alert-danger" role="alert">
                Nội dung không được rỗng và dài quá 1024 ký tự!
            </div>
        <?php endif; ?>

        <?php foreach ($posts as $post) : ?>
            <form method="POST">
                <div class="card" style="margin-bottom: 10px;">
                    <div class="card-body">
                        <h5 class="card-title">
                           <img style="width: 80px;" src="<?php echo 'data:image/jpeg;base64,' . base64_encode($post['profilePicture']); ?>" class="card-img-top" alt="<?php echo $post['firstname'] . ' ' . $post['lastname']; ?>">
                            <?php echo $post['firstname'] . ' ' . $post['lastname']; ?>
                        </h5>
                        <p class="card-text">
                            <small>Đăng lúc: <?php echo $post['post_time'] ?> </small>
                            <p>
                                <h6><strong><?php echo $post['content'] ?></strong></h6>
                            </p>
                        </p>
                        <?php if ($post['id'] == $currentUser['id']):?>
                            <button type="submit" name="delete" value="<?php echo $post['postID'] ?>" class="btn btn-danger">Xóa</button>
                            <?php 
                                if (isset($_POST['delete']))
                                {
                                    //$value = $_POST['delete'];
                                    DeleteContentbyID($_POST['delete']);
                                    exit(header("Location: index.php"));
                                    //header('Location: index.php');
                                }   
                            ?>
                        <?php else : ?>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        <?php endforeach; ?>


    <?php else : ?>
        <h1>Chào mừng bạn đã đến với trang web.</h1>
    <?php endif; ?>
<?php include 'footer.php'; ?>