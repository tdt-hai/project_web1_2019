<?php
require_once 'init.php';

$posts = showPost($currentUser['id']);
$posts2 = showPost2($currentUser['id']);
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
<?php ob_start(); ?>
<link rel="stylesheet" type="text/css" href="./css_files/ust.css">
<?php include 'header.php'; ?>
<!-- ---------------------------------- -->
<div class="coverpadx"><img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($posts2['profilePicture']); ?>" width="850px" height="310px" />
</div>



<div class="profilepic">
</div>
<div class="profilepicx"><img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($posts2['profilePicture']); ?>" alt="A girl" height="140px">
</div>
<div class="username"><?php echo $currentUser['firstname'] . ' ' . $currentUser['lastname']; ?>
</div>
<div class="box11">Timeline
</div>
<div class="box12">About
</div>
<div class="box13">Friends
</div>
<div class="box14">Photos
</div>
<select>
    <option selected>More</option>
    <option value="saab">Videos</option>
    <option value="opel">Places</option>
    <option value="audi">Pages</option>
</select>
<div class="container">
    <div class="col-md-3 col-sm- col-xs-3  row-container1">

        <strong>Ngày sinh: </strong><?php echo $currentUser['Birthday']; ?> <br>
        <strong>Số điện thoại : </strong><?php echo $currentUser['phoneNumber']; ?> <br>
        <strong> Email: </strong><a href="mailto:<?php echo $currentUser['email']; ?>" target="_blank"><?php echo $currentUser['email']; ?></a>

    </div>
    <div class=" col-md-8 col-sm-3 col-xs-8 row-container">
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
        <div class="row-">
            <?php foreach ($posts as $post) : ?>
                <form method="POST">
                    <div class="col-sm-12" style="margin-bottom: 10px;">
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
                                        $val = $_POST['delete'];
                                        DeleteContentbyID($val);
                                        header('Location: information.php');
                                    }
                                    ?>
                                <button type="submit" name="delete" value="<?php echo $post['postID'] ?>" class="btn btn-danger">Xóa</button>
                                <?php ob_end_flush(); ?>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</div>
<?php include 'footer.php'; ?>