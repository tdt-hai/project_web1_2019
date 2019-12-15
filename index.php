<?php
require_once 'init.php';
 ob_start();
?>
<?php include 'header.php'; ?>
<!-- <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'> -->
<link rel="stylesheet" type="text/css" href="./css_files/style_page.css">
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
//Xử lí phân trang
// BƯỚC 2: TÌM TỔNG SỐ RECORDS
$totalrecord =  totalpage();
// BƯỚC 3: TÌM LIMIT VÀ CURRENT_PAGE
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 2;
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
                        <img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($friend['profilePicture']); ?>" class="img-circle" alt="Avatar" width="25" height="25">
                            <a href="information.php?id=<?php echo $friend['id']; ?>"><?php echo $friend['firstname'].' '.$friend['lastname']; ?></a>
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
                                    <form method="POST">
                                        <div class="form-group">
                                            <textarea class="form-control" id="content" name="content"
                                                placeholder="Bạn đang nghĩ gì..."></textarea>
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
                        </div>
                        <?php foreach ($posts as $post) : ?>
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
                                                    <ul class="dropdown-menu">
                                                        <li><a href="#"><i class="fas fa-globe-americas"></i>
                                                                <span>public</span></a></li>
                                                        <li><a href="#"><i class="fas fa-user-friends"></i>
                                                                <span>Friend</span></a></li>
                                                        <li><a href="#"><i class="fas fa-lock"></i>
                                                                <span>private</span></a></li>
                                                    </ul>
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
                                                    <?php echo $post['post_time']; ?></span>
                                            </div>
                                            <!-- /.user-block -->
                                            <p>
                                                <?php echo $post['content']; ?>
                                            </p>

                                            <p>
                                                <a href="#" class="link-black text-sm mr-2"><i
                                                        class="far fa-thumbs-up mr-1"></i>Thích</a>
                                                <a href="#" class="link-black text-sm"><i class="fas fa-share mr-1"></i>
                                                    Chia sẻ</a>
                                                <span class="float-right">
                                                    <a href="#" class="link-black text-sm">
                                                        <i class="far fa-comments mr-1"></i> Bình luận (5)
                                                    </a>
                                                </span>
                                            </p>

                                            <form class="form-horizontal">
                                                <div class="input-group input-group-sm mb-0">
                                                    <input class="form-control form-control-sm" placeholder="comment">
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-danger">Gửi</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </form>
                                </div>
                                <!-- /.post -->
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
            <!-- Hiển thị phân trang -->
            <ul class="pagination modal-1">
                <?php if ($current_page > 1 && $total_page > 1) {
                                ?>
                <li><a href="index.php?page=<?php echo ($current_page - 1); ?>" class="prev">&laquo</a></li>
                <?php } ?>

                <?php for ($i = 1; $i <= $total_page; $i++) {
                                if ($i == $current_page) {
                                    ?>
                <li><a href="index.php?page= <?php echo $i; ?>" class="active"><?php echo $i ?></a></li>
                <?php
                                } else {
                                    echo '<a href="index.php?page=' . $i . '">' . $i . '</a>';
                                }
                            }
                            ?>
                <?php if ($current_page < $total_page && $total_page > 1) {
                                ?>
                <li><a href="index.php?page=<?php echo ($current_page + 1); ?>" class="next">&raquo;</a></li>
                <?php } ?>
            </ul>
    </div>
    <?php else : ?>

    <?php header("Location: login.php")?>
</div>
</div>
<?php ob_end_flush(); ?>
<?php include 'footer.php'; ?>