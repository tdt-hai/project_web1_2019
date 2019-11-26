<?php
require_once 'init.php';

$posts = showPost($currentUser['id']);
$posts2 = showPost2($currentUser['id']);
?>
<?php
$success = true;
if(isset($_POST['Posts']))
{
  $content = $_POST['content'];
  $lengh = strlen($content);
  if($lengh == 0 || $lengh > 1024)
  {
      $success = false;
  }
  else
  {
    createPost($currentUser['id'],$content);
    header("Location: information.php");
  }

}

?>
<?php include 'header.php'; ?>
<h1> Trang cá nhân </h1>
<div class="form-check-label">
                    <li > <strong>Họ và tên: </strong><?php echo $currentUser['firstname'] ; echo $currentUser['lastname'] ;?></li>
                    <li> <strong>Ngày  sinh: </strong><?php echo $currentUser['Birthday'] ; ?></li>
                    <li> <strong>Số điện thoại : </strong><?php echo $currentUser['phoneNumber'] ; ?></li>
                    <li> <strong> Email: </strong><a href="mailto:<?php echo $currentUser['email'] ;?>" target="_blank"><?php echo $currentUser['email'] ; ?></a></li>
                    <img style="width: 250px;" src="<?php echo 'data:image/jpeg;base64,'.base64_encode( $posts2['profilePicture'] ); ?>">
</div>
    <div class="col-md-6 offset-md-3">
        <div class="row-">
            <form method="POST">
                <div class="col-sm-12" style="margin-bottom: 10px;">
                    <div class="input-group mb-3">
                        <textarea class="form-control" id="content" name="content"
                                  placeholder="Bạn đang nghĩ gì..."></textarea>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" name = "Posts">Đăng</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
<?php if (!  $success) : ?>
<div class="alert alert-danger" role="alert">
  Nội dung không được rỗng và dài quá 1024 ký tự!
</div>
<?php endif ;?>

        <div class="row-">
            <?php foreach ($posts as $post): ?>
            <form method="POST">
            <div class="col-sm-12" style="margin-bottom: 10px;">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <img style="width: 80px;"
                                 src="<?php echo 'data:image/jpeg;base64,'.base64_encode( $post['profilePicture'] ); ?>"
                                 class="card-img-top"
                                 alt="<?php echo $post['firstname'] . ' ' . $post['lastname']; ?>">
                            <?php echo $post['firstname'] . ' ' . $post['lastname']; ?>
                            <h6 class="card-subtitle mb-2 text-muted">
                                Đăng lúc:  <?php  echo $post['post_time']; ?>
                            </h6>
                        </h5>
                        <p class="card-text">
                            <?php echo $post['content']; ?>
                        </p>
                        <button type="submit" name="delete" value="<?php echo $post['postID']; ?>" class="btn btn-danger">Xóa</button>
                            <?php 
                                if (isset($_POST['delete']))
                                {
                                    //$value = $_POST['delete'];
                                    DeleteContentbyID($_POST['delete']);
                                    header('Location: infomation.php');
                                }   
                            ?>
                    </div>
                </div>
            </div>
            </form>
            <?php endforeach; ?>
        </div>
    </div>

<?php include 'footer.php'; ?>