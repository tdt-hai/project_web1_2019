<?php
require_once 'init.php';
ob_start();
$posts = showPost($_GET['id']);
////////////////////////////
$users = findUserByID($_GET['id']);
//Nếu đăng nhập mới thấy file profile.php
    // if (!$currentUser) {
    //     header("Location:login.php");
    // }
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
// $imagetmp ; 
//CreatePost
if(isset($_FILES['UploadPicture'])){
    $fileName = $_FILES['UploadPicture']['name'];
    $filetmp  = $_FILES['UploadPicture']['tmp_name'];
   $imagetmp = file_get_contents($filetmp);
}
if (isset($_POST['Posts'])) {
    $content = $_POST['content'];
    $privacy = $_POST['w_privacy'];
    $lengh = strlen($content);
    if ($lengh == 0 || $lengh > 1024) {
        $success = false;
    } else {
        createPost($users['id'], $content,$privacy,$imagetmp);
        header('Location:information.php?id='.$currentUser['id']);
    }
}
//}

$friends = getFriends($users['id']);
if(isset($_POST['commentts'])){
    $comments = $_POST['comment'];
    $post_ID = $_POST['commentts'];
    createComments($post_ID,$currentUser['id'],$comments);
    header('Location:information.php?id='.$users['id']);
}
if (isset($_POST['like'])) {
    $postid = $_POST['like'];
    try
    {
        Likes($postid,$currentUser['id']);
    }
    catch(Exception $e)
    {
        
    }
   
 }
 if(isset($_POST['unlike'])){
    $postid = $_POST['unlike'];
    Unlikes($postid,$currentUser['id']);
 }
 if(isset($_POST['public'])){
     $public = $_POST['public'];
     updatePublic($public);
     header('Location:information.php?id='.$users['id']);
 }
 if(isset($_POST['friend'])){
     $friend = $_POST['friend'];
     updateFriend($friend);
     header('Location:information.php?id='.$users['id']);
 }
 if(isset($_POST['private']))
 {
     $private = $_POST['private'];
     updatePrivate($private);
     header('Location:information.php?id='.$users['id']);
 }
?>
<?php include 'header.php'; ?>
<link rel="stylesheet" href="./css_files/style_image.css">
<!-- --------------------------------------- -->

<!-- Content Wrapper. Contains page content -->
<div class="">
    <!-- Main content -->
    <!-- <section class="content"> -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <?php if($users['profilePicture']==null):  ?>
                            <img class="profile-user-img img-fluid img-circle" src="./images/profile_default.jpg"
                                class="img-circle" alt="Avatar" width="25" height="25">
                            <?php else:?>
                            <img class="profile-user-img img-fluid img-circle"
                                src="<?php echo 'data:image/jpeg;base64,' . base64_encode($users['profilePicture']); ?>"
                                alt="User profile picture">
                            <?php endif;?>
                        </div>

                        <h3 class="profile-username text-center">
                            <?php echo $users['firstname'].' '.$users['lastname'];?></h3>
                        <h6 class="text-muted"> <strong>Ngày sinh:</strong> <?php echo $users['Birthday']; ?> </h6>
                        <h6 class="text-muted"><strong>Số điện thoại :</strong> <?php echo $users['phoneNumber']; ?>
                        </h6>
                        <h6 class="text-muted"> <strong>Email:</strong> <a href="mailto:<?php echo $users['email']; ?>"
                                target="_blank"><?php echo $users['email']; ?></a>
                            <ul class="list-group list-group-unbordered mb-3">
                                <?php if($friends  || ($users['id'] == $currentUser['id'])){?>
                                <li class="list-group-item">
                                    <b>Bạn bè</b> <a class="float-right"><?php echo count($friends); ?></a>
                                </li>
                                <?php }?>
                            </ul>
                            <?php if ($users['id'] != $currentUser['id']) : ?>
                            <form action="friend.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $users['id']; ?>">
                                <?php if ($isFriend) : ?>
                                <input type="submit" class="btn btn-danger btn-block" name="action" value="Xóa bạn bè">
                                <?php elseif ($norelationship) : ?>
                                <input type="submit" class="btn btn-success btn-block" name="action"
                                    value="Gửi yêu cầu kết bạn">
                                <?php else : ?>
                                <?php if (!$isrequesting) : ?>
                                <input type="submit" class="btn btn-primary btn-block" name="action"
                                    value="Đồng ý yêu cầu kết bạn">
                                <?php endif; ?>
                                <input type="submit" class="btn btn-warning btn-block" name="action"
                                    value="Hủy yêu cầu kết bạn">
                            </form>
                            <?php endif; ?>
                            <?php endif; ?>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Giới thiệu</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fas fa-university"></i>Sinh viên</strong>

                        <p class="text-muted">
                            <?php echo $users['Education'] ?>
                        </p>

                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i>Sống tại</strong>

                        <p class="text-muted"><?php echo $users['Location']?></p>

                        <hr>

                        <strong><i class="fas fa-pencil-alt mr-1"></i>Kĩ năng</strong>
                        <p class="text-muted">
                            <span class="tag tag-danger"><?php echo $users['Skill']?></span>
                        </p>

                        <hr>

                        <strong><i class="far fa-file-alt mr-1"></i>Tiểu sử</strong>

                        <p class="text-muted"><?php echo $users['Notes'] ?></p>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="post">
                                <?php if($currentUser['id'] == $users['id'] ) { ?>
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
                                                <select
                                                    style="margin: 5px; padding: 0px 10px; max-width: 110px; height: 35px;"
                                                    name="w_privacy">
                                                    <option selected value="0">Public</option>
                                                    <option value="1">Friends</option>
                                                    <option value="2"></span> Only me</option>
                                                </select>
                                            </div>
                                            <input name="UploadPicture" id="UploadPicture" type="file"
                                                accept="image/jpeg" onchange="readURL(this);" />
                                            <img id="blah" class="imgPreview" src="#" alt="" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                            <?php if (!$success) : ?>
                            <div class="alert alert-danger" role="alert">
                                Nội dung không được rỗng và dài quá 1024 ký tự!
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php foreach ($posts as $post) : ?>
                        <?php if($currentUser['id'] == $users['id']){  
                                        if($post['privacy']==0 || $post['privacy']==1  || $post['privacy']==2){  
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
                                                        data-toggle="dropdown"><?php echo $post['firstname'] . ' ' . $post['lastname'];?></a>
                                                    <?php if($currentUser['id'] == $users['id']){ ?>
                                                    <ul class="dropdown-menu">
                                                        <form method="POST">
                                                            <li><button type="submit" name="public"
                                                                    value="<?php echo $post['postID'] ?>"><i
                                                                        class="fas fa-globe-americas"></i>
                                                                    <span>Public </span></button></li>
                                                            <li><button type="submit" name="friend"
                                                                    value="<?php echo $post['postID'] ?>"><i
                                                                        class="fas fa-user-friends"></i>
                                                                    <span>Friend</span></button></li>
                                                            <li><button type="submit" name="private"
                                                                    value="<?php echo $post['postID'] ?>"><i
                                                                        class="fas fa-lock"></i>
                                                                    <span>Private</span></button></li>
                                                        </form>
                                                    </ul>
                                                    <?php }?>
                                                    <?php
                                                                    if (isset($_POST['delete'])) {
                                                                                $value = $_POST['delete'];
                                                                                DeleteContentbyID($value);
                                                                                header('Location:information.php?id='.$currentUser['id']);
                                                                    }
                                                            ?>
                                                    <?php if ($currentUser['id'] == $users['id']){?>
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
                                                <?php echo $post['content']; ?>

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
                                        <p>
                                            <?php if ($Nolike): ?>
                                            <button class="far fa-thumbs-up mr-1  " type="submit" name="like"
                                                value="<?php echo $post['postID'] ?>"></button>(<?php echo count($findlikePost)?>)
                                            <?php else:?>
                                            <button class="far fa-thumbs-up mr-1  btn-primary " type="submit"
                                                name="unlike" value="<?php echo $post['postID'] ?>">
                                            </button> (<?php echo count($findlikePost)?>)
                                            <?php endif;?>
                                            <span class="float-right">
                                                <a href="#" class="link-black text-sm">
                                                    <i class="far fa-comments mr-1"></i> Bình luận
                                                    (<?php $showComments = showComment($post['postID']); echo count($showComments);?>)
                                                </a>
                                            </span>
                                        </p>
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
                                            <b
                                                class="profile-username text-center"><?php echo $showComment['firstname'] .' '. $showComment['lastname']; ?></b><br>
                                            &ensp;&emsp;<?php echo $showComment['Content']; ?></a>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                            <!-- /.post -->
                        </div>
                    </div>

                    <?php 
                                 }}
                                 elseif($isFriend){
                                    if($post['privacy']==1 || $post['privacy']==0){    
                                      
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
                                                    data-toggle="dropdown"><?php echo $post['firstname'] . ' ' . $post['lastname'];?></a>
                                                <?php if($currentUser['id'] == $users['id']){ ?>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#"><i class="fas fa-globe-americas"></i>
                                                            <span>public</span></a></li>
                                                    <li><a href="#"><i class="fas fa-user-friends"></i>
                                                            <span>Friend</span></a></li>
                                                    <li><a href="#"><i class="fas fa-lock"></i>
                                                            <span>private</span></a></li>
                                                </ul>
                                                <?php }?>
                                                <?php
                                                                    if (isset($_POST['delete'])) {
                                                                                $value = $_POST['delete'];
                                                                                DeleteContentbyID($value);
                                                                                header('Location:information.php?id='.$currentUser['id']);
                                                                    }
                                                            ?>
                                                <?php if ($currentUser['id'] == $users['id']){?>
                                                <button type="submit" name="delete"
                                                    value="<?php echo $post['postID'] ?>"
                                                    class="float-right btn-tool"><i class="fas fa-times"></i></button>
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
                                            <?php echo $post['content']; ?>
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
                                    <p>
                                        <?php if($Nolike): ?>
                                        <button class="far fa-thumbs-up mr-1  " type="submit" name="like"
                                            value="<?php echo $post['postID'] ?>"></button>(<?php echo count($findlikePost)?>)
                                        <?php else: ?>
                                        <button class="far fa-thumbs-up mr-1  btn-primary " type="submit" name="unlike"
                                            value="<?php echo $post['postID'] ?>">
                                        </button>(<?php echo count($findlikePost)?>)
                                        <?php endif ;?>
                                        <span class="float-right">
                                            <a href="#" class="link-black text-sm">
                                                <i class="far fa-comments mr-1"></i> Bình luận
                                                (<?php $showComments = showComment($post['postID']); echo count($showComments);?>)
                                            </a>
                                        </span>
                                    </p>
                                </form>
                                <form method="POST" class="form-horizontal">
                                    <div class="input-group input-group-sm mb-0">
                                        <textarea class="form-control form-control-sm" name="comment" cols="30" rows="2"
                                            placeholder="Viết bình luận..."></textarea>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit" name="commentts"
                                                value="<?php echo $post['postID'] ?>">Bình luận</button>
                                        </div>
                                    </div>
                                </form>
                                <?php  foreach($showComments as $showComment):  ?>
                                <a class="nav-link" data-toggle="dropdown"><img
                                        src="<?php echo 'data:image/jpeg;base64,' . base64_encode($showComment['profilePicture']); ?>"
                                        class="img-circle" alt="Avatar" width="25" height="25">
                                    <?php echo $showComment['firstname'] .' '. $showComment['lastname']; ?></a>
                                <?php echo $showComment['Content']; ?>
                                <?php endforeach; ?>
                            </div>

                        </div>
                        <!-- /.post -->
                    </div>
                </div>
            </div>
            <?php 
                     } }
                            elseif( $norelationship || $isrequesting){
                            if($post['privacy']==0){ 
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
                                            data-toggle="dropdown"><?php echo $post['firstname'] . ' ' . $post['lastname'];?></a>
                                        <?php if($currentUser['id'] == $users['id']){ ?>
                                        <ul class="dropdown-menu">
                                            <li><a href="#"><i class="fas fa-globe-americas"></i>
                                                    <span>public</span></a></li>
                                            <li><a href="#"><i class="fas fa-user-friends"></i>
                                                    <span>Friend</span></a></li>
                                            <li><a href="#"><i class="fas fa-lock"></i>
                                                    <span>private</span></a></li>
                                        </ul>
                                        <?php }?>
                                        <?php
                                                                    if (isset($_POST['delete'])) {
                                                                                $value = $_POST['delete'];
                                                                                DeleteContentbyID($value);
                                                                                header('Location:information.php?id='.$currentUser['id']);
                                                                    }
                                                            ?>
                                        <?php if ($currentUser['id'] == $users['id']){?>
                                        <button type="submit" name="delete" value="<?php echo $post['postID'] ?>"
                                            class="float-right btn-tool"><i class="fas fa-times"></i></button>
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
                                    <?php echo $post['content']; ?>
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
                            <p>
                                <?php if($Nolike): ?>
                                <button class="far fa-thumbs-up mr-1  " type="submit" name="like"
                                    value="<?php echo $post['postID'] ?>"></button>(<?php echo count($findlikePost)?>)
                                <?php else: ?>
                                <button class="far fa-thumbs-up mr-1  btn-primary " type="submit" name="unlike"
                                    value="<?php echo $post['postID'] ?>">
                                </button> (<?php echo count($findlikePost)?>)
                                <?php endif ;?>
                                <span class="float-right">
                                    <a href="#" class="link-black text-sm">
                                        <i class="far fa-comments mr-1"></i> Bình luận
                                        (<?php $showComments = showComment($post['postID']); echo count($showComments);?>)
                                    </a>
                                </span>
                            </p>
                        </form>
                        <form method="POST" class="form-horizontal">
                            <div class="input-group input-group-sm mb-0">
                                <textarea class="form-control form-control-sm" name="comment" cols="30" rows="2"
                                    placeholder="Viết bình luận..."></textarea>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" name="commentts"
                                        value="<?php echo $post['postID'] ?>">Bình luận</button>
                                </div>
                            </div>
                        </form>
                        <?php  foreach($showComments as $showComment):  ?>
                        <a class="nav-link" data-toggle="dropdown"><img
                                src="<?php echo 'data:image/jpeg;base64,' . base64_encode($showComment['profilePicture']); ?>"
                                class="img-circle" alt="Avatar" width="25" height="25">
                            <?php echo $showComment['firstname'] .' '. $showComment['lastname']; ?></a>
                        <?php echo $showComment['Content']; ?>
                        <?php endforeach; ?>
                    </div>

                </div>
                <!-- /.post -->
            </div>
        </div>
    </div>
    <?php } }?>
    <?php endforeach; ?>

    <!-- /.tab-pane -->

    <!-- /.tab-content -->
</div><!-- /.card-body -->
</div>
<!-- /.nav-tabs-custom -->
</div>
<!-- /.col -->
<div class="col-md-3">
    <div class="card">
        <div class="card-body">
            <div class="tab-content">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>Danh sách bạn bè</h4>
                        <ul>
                            <?php foreach ($friends as $friend) : ?>
                            <?php ?>
                            <li>
                                <img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($friend['profilePicture']); ?>"
                                    class="img-circle" alt="Avatar" width="25" height="25">
                                <a
                                    href="information.php?id=<?php echo $friend['id']; ?>"><?php echo $friend['firstname'].' '.$friend['lastname']; ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./friends -->
</div>
</div>
<!-- /.row -->
</div><!-- /.container-fluid -->
<!-- </section> -->
<!-- /.content -->
</div>
<!-- -------------------------------------- -->
<?php ob_end_flush(); ?>
<?php include 'footer.php'; ?>