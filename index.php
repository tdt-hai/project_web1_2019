<?php
require_once 'init.php';

?>
<?php include 'header.php'; ?>
<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
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
if($start < 0)
{
    $start = 0;
}
?>
<?php $posts = getNewsFeed($start, $limit); ?>
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
                        <?php ob_start(); ?>
                        <h5 class="card-title">
                            <img style="width:80px;" src="<?php echo 'data:image/jpeg;base64,' . base64_encode($post['profilePicture']); ?>" class="card-img-top" alt="<?php echo $post['firstname'] . ' ' . $post['lastname']; ?>">
                            <?php echo $post['firstname'] . ' ' . $post['lastname']; ?>
                        </h5>
                        <p class="card-text">
                            <small>Đăng lúc: <?php echo $post['post_time'] ?> </small>
                            <p>
                                <h6><strong><?php echo $post['content'] ?></strong></h6>
                            </p>
                        </p>
                        <?php if ($post['id'] == $currentUser['id']) : ?>
                            <?php
                                        if (isset($_POST['delete'])) {
                                            $value = $_POST['delete'];
                                            DeleteContentbyID($value);
                                            header('Location: index.php');
                                        }
                                        ?>
                            <button type="submit" name="delete" value="<?php echo $post['postID'] ?>" class="btn btn-danger">Xóa</button>
                            <?php ob_end_flush(); ?>
                        <?php else : ?>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        <?php endforeach; ?>
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
    <?php else : ?>
        <h1>Chào mừng bạn đã đến với trang web.</h1>
    <?php endif; ?>
    <?php include 'footer.php'; ?>