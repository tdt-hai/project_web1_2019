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
    } if($success && !$imagetmp ) {
        createPost($users['id'], $content);
        header('Location:information.php?id='.$currentUser['id']);
        
    }
    if($success && $imagetmp)
    {
        uploadImageOnTimeline($user['id'],$content,$imagetmp);
        header('Location:information.php?id='.$currentUser['id']);
    }
}
$friends = getFriends($currentUser['id']); 
?>
<?php include 'header.php'; ?>
<!-- --------------------------------------- -->

<!-- Content Wrapper. Contains page content -->
<div class="">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="<?php echo 'data:image/jpeg;base64,' . base64_encode($users['profilePicture']); ?>"
                                    alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">
                                <?php echo $users['firstname'].' '.$users['lastname'];?></h3>
                            <h6 class="text-muted"> <strong>Ngày sinh:</strong> <?php echo $users['Birthday']; ?> </h6>
                            <h6 class="text-muted"><strong>Số điện thoại :</strong> <?php echo $users['phoneNumber']; ?>
                            </h6>
                            <h6 class="text-muted"> <strong>Email:</strong> <a
                                    href="mailto:<?php echo $users['email']; ?>"
                                    target="_blank"><?php echo $users['email']; ?></a>
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <?php if (($currentUser['id'] == $users['id']) ){?>
                                        <b>Bạn bè</b> <a class="float-right"><?php echo count($friends); ?></a>
                                        <?php }?>
                                    </li>
                                </ul>
                                <?php if ($users['id'] != $currentUser['id']) : ?>
                                <form action="friend.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $users['id']; ?>">
                                    <?php if ($isFriend) : ?>
                                    <input type="submit" class="btn btn-danger btn-block" name="action"
                                        value="Xóa bạn bè">
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
                            <h3 class="card-title">About Me</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i> Education</strong>

                            <p class="text-muted">
                                B.S. in Computer Science from the University of Tennessee at Knoxville
                            </p>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                            <p class="text-muted">Malibu, California</p>

                            <hr>

                            <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                            <p class="text-muted">
                                <span class="tag tag-danger">UI Design</span>
                                <span class="tag tag-success">Coding</span>
                                <span class="tag tag-info">Javascript</span>
                                <span class="tag tag-warning">PHP</span>
                                <span class="tag tag-primary">Node.js</span>
                            </p>

                            <hr>

                            <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                                fermentum enim
                                neque.</p>
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
                                            <form method="POST"
                                                action="information.php?id=<?php echo $users['id']; ?> ">
                                                <div class="form-group">
                                                    
                                                    <style>
                                                        .imgPreview{
                                                            border-radius: 8px;
                                                            display: block;
                                                            margin-left: auto;
                                                            margin-right: auto;
                                                         
                                                            object-fit: cover;
                                                        }
                                                        .choose_file{
                                                                    position:relative;
                                                                    display:inline-block;    
                                                                    border-radius:8px;
                                                                    border:#ebebeb solid 1px;
                                                                    width:75px; 
                                                                    padding: 4px 6px 4px 8px;
                                                                    font: normal 14px Myriad Pro, Verdana, Geneva, sans-serif;
                                                                    color: #7f7f7f;
                                                                    margin-top: 2px;
                                                                    background:white
                                                                }
                                                                .choose_file input[type="file"]{
                                                                    -webkit-appearance:none; 
                                                                    position:absolute;
                                                                    top:0; left:0;
                                                                    opacity:0; 
                                                                }
                                                    </style>
                                                    <textarea class="form-control" id="content" name="content"
                                                        placeholder="Bạn đang nghĩ gì..."></textarea>

                                                    <!-- <button type = "buttton" name = "insertPicture"  > <i class="fas fa-images"></i>
                                                    </button>    -->
                                                    <style>
                                                     
                                                    </style>
                                                    <script>
                                                        function readURL(input) {
                                                            if (input.files && input.files[0]) {
                                                                var reader = new FileReader();

                                                                reader.onload = function (e) {
                                                                    $('#blah')
                                                                        .attr('src', e.target.result)
                                                                        .width(150)
                                                                        .height(200);
                                                                };


                                                                reader.readAsDataURL(input.files[0]);
                                                            }
                                                        }
                                                    </script>
                                                    <div class="choose_file">
                                                        <span>Ảnh  <i class="fas fa-images"></i>
                                                                        </span>
                                                        <input name="UploadImage" id = "UploadImage"  type="file" accept = "image/jpeg" onchange ="readURL(this);" />
                                                    </div>
                                                   
                                                <?php
                                                    if(isset($_FILES['UploadImage']))
                                                    {
                                                      $img = $_FILES['UploadImage']['name'];
                                                      $imagetmp = file_get_contents($fileTmp);
                                                    }
                                                ?>
                                                 <img id="blah"  class = "imgPreview" src="#" alt="" />
                                                </div>
                                                <?php 
                                                    var_dump("test");
                                                    if (isset($_FILES['UploadImage'])) {
                                                        $fileName = $_FILES['UploadImage']['name'];
                                                        $fileTmp  = $_FILES['UploadImage']['tmp_name'];
                                                        var_dump("test");
                                                        $imagetmp = file_get_contents($fileTmp);
                                                    }
                                                ?>
                                                <div class="form-group">
                                                    <input class="btn btn-primary" type="submit" name="Posts"
                                                        value="Đăng">
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
                                </div>
                                <?php if($isFriend || ($users['id'] == $currentUser['id'])){?>
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
                                                            <?php echo $post['post_time']; ?></span>
                                                    </div>
                                                    <!-- /.user-block -->
                                                    <p>
                                                        <?php echo $post['content']; ?>
                                                    </p>

                                                    <p>
                                                        <a href="#" class="link-black text-sm mr-2"><i
                                                                class="far fa-thumbs-up mr-1"></i>Thích</a>
                                                        <a href="#" class="link-black text-sm"><i
                                                                class="fas fa-share mr-1"></i> Chia sẻ</a>
                                                        <span class="float-right">
                                                            <a href="#" class="link-black text-sm">
                                                                <i class="far fa-comments mr-1"></i> Comments (5)
                                                            </a>
                                                        </span>
                                                    </p>

                                                    <form class="form-horizontal">
                                                        <div class="input-group input-group-sm mb-0">
                                                            <input class="form-control form-control-sm"
                                                                placeholder="comment">
                                                            <div class="input-group-append">
                                                                <button type="submit"
                                                                    class="btn btn-danger">Gửi</button>
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
                                <?php }?>
                                <!-- /.tab-pane -->
                            </div>
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
                                        <?php if (($currentUser['id'] == $users['id']) ){?>
                                        <h4>Danh sách bạn bè</h4>
                                        <ul>
                                            <?php foreach ($friends as $friend) : ?>
                                            <?php ?>
                                            <li>
                                                <a
                                                    href="information.php?id=<?php echo $friend['id']; ?>"><?php echo $friend['firstname'];echo $friend['lastname']; ?></a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php } ?>
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
    </section>
    <!-- /.content -->
</div>
<!-- -------------------------------------- -->
<?php ob_end_flush(); ?>
<?php include 'footer.php'; ?>