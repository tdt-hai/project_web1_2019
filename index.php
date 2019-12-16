<?php
require_once 'init.php';
 ob_start();
?>
<?php include 'header.php'; ?>
<link rel="stylesheet" type="text/css" href="./css_files/style_page.css">
<link rel="stylesheet" href="./css_files/style_image.css">
<?php
$success = true;
if(isset($_FILES['UploadPicture'])){
    $fileName = $_FILES['UploadPicture']['name'];
    $filetmp  = $_FILES['UploadPicture']['tmp_name'];
   $imagetmp = file_get_contents($filetmp);
}
if (isset($_POST['Posts'])) {
    $content = $_POST['content'];
    $lengh = strlen($content);
    $privacy = $_POST['w_privacy'];
    if ($lengh == 0 || $lengh > 1024) {
        $success = false;
    } else {
        createPost($currentUser['id'], $content,$privacy,$imagetmp);
        header('Location: index.php');
    }
}
if(isset($_POST['commentts'])){
    $comments = $_POST['comment'];
    $post_ID = $_POST['commentts'];
    createComments($post_ID,$currentUser['id'],$comments);
    header('Location: index.php');
}
if (isset($_POST['like'])) {
    $postid = $_POST['like'];
    Likes($postid,$currentUser['id']);
 }
 if(isset($_POST['unlike'])){
    $postid = $_POST['unlike'];
    Unlikes($postid,$currentUser['id']);
 }
//Xử lí phân trang
// BƯỚC 2: TÌM TỔNG SỐ RECORDS
$totalrecord =  totalpage();
// BƯỚC 3: TÌM LIMIT VÀ CURRENT_PAGE
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;
// BƯỚC 4: TÍNH TOÁN TOTAL_PAGE VÀ START
// tổng số trang
$total_page = ceil($totalrecord / $limit);
// Giới hạn current_page trong khoảng 1 đến total_page
if ($current_page > $total_page) {
    $current_page = $total_page;
} else if ($current_page < 1) {
    $current_page = 1;
}
// Tìm Start
$start = ($current_page - 1) * $limit;
if ($start < 0) {
    $start = 0;
}
?>
<?php 
    try
    {
        $posts = getNewFeedsForUserId($currentUser['id'],$start, $limit); 
    }
       
    catch(Exception $e)
    {
    }
        
?>
<?php if ($currentUser) : ?>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Gợi ý kết bạn</h4>
                    <ul>
                        <?php $friends= getSuggestionFriend() ?>

                        <?php foreach ($friends as $friend) : ?>
                        <?php if($currentUser['id'] != $friend['id']) { ?>
                        <li>
                            <?php if($friend['profilePicture'] == null): ?>
                            <img src="./images/profile_default.jpg" class="img-circle" alt="Avatar" width="25"
                                height="25">
                            <?php else: ?>
                            <img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($friend['profilePicture']); ?>"
                                class="img-circle" alt="Avatar" width="25" height="25">
                            <?php endif;?>
                            <a
                                href="information.php?id=<?php echo $friend['id']; ?>"><?php echo $friend['firstname'].' '.$friend['lastname']; ?></a>
                        </li>
                        <?php }?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <h3>Chào mừng <?php echo $currentUser['firstname'] . ' ' . $currentUser['lastname']; ?> đã trở lại!
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="post">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                <form method="POST" enctype="multipart/form-data">
                                            <div class="input-group input-group-sm mb-0">
                                                <textarea class="form-control" id="content" name="content"
                                                    placeholder="Bạn đang nghĩ gì..."></textarea>
                                                <div class="input-group-append">
                                                    <input class="btn btn-primary" type="submit" name="Posts"
                                                        value="Đăng">
                                                </div>
                                                <h6><select
                                                    style="margin: 5px; padding: 0px 10px; max-width: 110px; height: 35px;"
                                                    name="w_privacy">
                                                    <option selected value="0">Public</option>
                                                    <option value="1">Friends</option>
                                                    <option value="2"></span> Only me</option>
                                                </select></h6> 

                                            </div>
                                            <h5><input name="UploadPicture" id="UploadPicture" type="file"
                                                accept="image/jpeg" onchange="readURL(this);" /></h5> 
                                            <img id="blah" class="imgPreview" src="#" alt="" /> 
                                        </form>
                                </div>
                                <ul class="pagination modal-1">
                                    <?php if ($current_page > 1 && $total_page > 1) {
                                ?>
                                    <li><a href="index.php?page=<?php echo ($current_page - 1); ?>"
                                            class="prev">&laquo</a></li>
                                    <?php } ?>

                                    <?php for ($i = 1; $i <= $total_page; $i++) {
                                if ($i == $current_page) {
                                    ?>
                                    <li><a href="index.php?page= <?php echo $i; ?>" class="active"><?php echo $i ?></a>
                                    </li>
                                    <?php
                                } else {
                                    echo '<a href="index.php?page=' . $i . '">' . $i . '</a>';
                                }
                            }
                            ?>
                                    <?php if ($current_page < $total_page && $total_page > 1) {
                                ?>
                                    <li><a href="index.php?page=<?php echo ($current_page + 1); ?>"
                                            class="next">&raquo;</a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php if (!$success) : ?>
                            <div class="alert alert-danger" role="alert">
                                Nội dung không được rỗng và dài quá 1024 ký tự!
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php foreach ($posts as $post) : ?>
                        <?php if($posts){
                                if($post['privacy'] == 0 || $post['privacy'] == 1){
                         ?>
                        <div class="active tab-pane" id="activity">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Post -->
                                    <form method="POST">
                                        <div class="post">
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                    src="<?php echo 'data:image/jpeg;base64,' . base64_encode($post['profilePicture']); ?>"
                                                    alt="user image">
                                                <span class="username">
                                                    <a href="#" class="dropdown-toggle"
                                                        data-toggle="dropdown"><?php echo $post['firstname'] . ' ' . $post['lastname']; ?></a>
                                                    <?php
                                                                            if (isset($_POST['delete'])) {
                                                                                $value = $_POST['delete'];
                                                                                DeleteContentbyID($value);
                                                                                header('Location:index.php');
                                                                            }
                                                    ?>
                                                    <?php if ($post['id'] == $currentUser['id']){?>
                                                    <button type="submit" name="delete"
                                                        value="<?php echo $post['postID'] ?>"
                                                        class="float-right btn-tool"><i
                                                            class="fas fa-times"></i></button>
                                                    <?php }?>
                                                </span>
                                                <span class="description">Đăng lúc:
                                                    <?php echo  $post['post_time'];  ?>
                                                    <?php if($post['privacy'] == 0):?>
                                                    <a class="fas fa-globe-europe"></a>
                                                    <?php elseif($post['privacy'] == 1):?>
                                                    <a class="fas fa-user-friends"></a>
                                                    <?php else: ?>
                                                    <a class="fas fa-lock"></a>
                                                    <?php endif;?>
                                                </span>
                                            </div>
                                            <!-- /.user-block -->
                                            <p>
                                                <h5> <?php echo $post['content']; ?></h5>
                                            </p>
                                            <p>
                                                <?php if(!empty($post['image'])){?>
                                                <img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($post['image']); ?>"
                                                    alt="user image" class="imageShow">
                                                <?php } ?>
                                            </p>
                                    </form>
                                    <?php $findlikeforUserID = findlikeforUserID($post['postID'],$currentUser['id']); 
                                                  $Nolike = (count($findlikeforUserID) === 0);
                                                  $findlikePost = findlikePost($post['postID']);
                                        ?>
                                    <form method="POST">
                                       <h5> <p>
                                       <?php if ($Nolike): ?>
                                            <button class="far fa-thumbs-up mr-1  " type="submit" name="like"
                                                value="<?php echo $post['postID'] ?>"></button>(<?php echo count($findlikePost)?>)
                                            <?php else: ?>
                                            <button class="far fa-thumbs-up mr-1  btn-primary " type="submit"
                                                name="unlike" value="<?php echo $post['postID'] ?>">
                                               </button> (<?php echo count($findlikePost)?>)
                                            <?php endif ;?>
                                            <span class="float-right">
                                                <a href="#" class="link-black text-sm">
                                                    <i class="far fa-comments mr-1"></i> Bình luận
                                                    (<?php $showComments = showComment($post['postID']); echo count($showComments);?>)
                                                </a>
                                            </span>
                                        </p>
                                        </h5>
                                    </form>

                                    <form method="POST" class="form-horizontal">
                                        <div class="input-group input-group-sm mb-0">
                                            <textarea class="form-control form-control-sm" name="comment" cols="30"
                                                rows="2" placeholder="Viết bình luận..."></textarea>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit" name="commentts"
                                                    value="<?php echo $post['postID'] ?>">Bình luận</button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php  foreach($showComments as $showComment):  ?>
                                    <div>
                                        <a class="nav-link" data-toggle="dropdown"><img
                                                src="<?php echo 'data:image/jpeg;base64,' . base64_encode($showComment['profilePicture']); ?>"
                                                class="img-circle" alt="Avatar" width="25" height="25">
                                            <b class="profile-username text-center"><?php echo $showComment['firstname'] .' '. $showComment['lastname']; ?></b><br>
                                            &ensp;&emsp;<?php echo $showComment['Content']; ?></a>
                                    </div>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                            <!-- /.post -->
                        </div>
                    </div>
                    <?php }}?>
                    <?php endforeach; ?>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
    </div>
    <!-- /.nav-tabs-custom -->
    <!-- Hiển thị phân trang -->

</div>
<?php else : ?>
<?php header("Location: login.php")?>
<?php endif; ?>
</div>
</div>
<?php ob_end_flush(); ?>
<?php include 'footer.php'; ?>