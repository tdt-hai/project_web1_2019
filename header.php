<!doctype html>
<html lang="vi">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style type="text/css">
    </style>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./css_files/lte/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="stylesheet" href="./css_files/lte/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./css_files/lte/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
    <!-- -------------------------- -->
    <title>BTN</title>
    <?php require_once 'init.php'; 
    ?>
</head>
<?php 
$conversations = getLatestConversations($currentUser['id']);
?>

<body>
    <div class="wrapper">
        <!-- Navbar main-header-->
        <nav class="navbar navbar-expand navbar-primary navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">

                <?php if (!$currentUser) : ?>
                <li class="nav-item d-none d-sm-inline-block <?php echo $page == 'login' ? 'active' : ''; ?>">
                    <a href="login.php" class=" nav-link fas fa-sign-in-alt"> Đăng nhập</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block <?php echo $page == 'register' ? 'active' : ''; ?>">
                    <a href="register.php" class="nav-link fas fa-registered"> Đăng kí</a>
                </li>
                <?php else : ?>
                <li class="nav-item d-none d-sm-inline-block <?php echo $page == 'index' ? 'active' : ''; ?>">
                    <a class="nav-link fa fa-home" href="index.php"> Trang chủ</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block <?php echo $page == 'information' ? 'active' : ''; ?>">
                    <a href="information.php?id=<?php echo $currentUser['id']; ?>" class=" nav-link fas fa-user"> Trang
                        cá nhân</a>
                </li>
            </ul>

            <!-- SEARCH FORM -->
            <form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->

                <li class="nav-item dropdown">

                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge"> <?php echo count($conversations) ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <?php foreach ($conversations as $conversation) : ?>

                        <a href="conversation.php?id=<?php echo $conversation['id'] ?>" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img class="direct-chat-img"
                                    src="<?php echo 'data:image/jpeg;base64,' . base64_encode($conversation['profilePicture']); ?>"
                                    alt="User Avatar" class="img-size-50 img-circle mr-3 ">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        &ensp; <?php echo $conversation['firstname'].''.$conversation['lastname']  ?>
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm"> &ensp; <?php echo $conversation['lastMessage']['Content'] ?></p>
                                    <p class="text-sm text-muted"> &ensp;&ensp;<i
                                            class="far fa-clock mr-1"></i><?php echo $conversation['lastMessage']['CreateTime'] ?>
                                    </p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <?php endforeach; ?>
                        <div class="dropdown-divider"></div>
                        <a href="messenger.php" class="dropdown-item dropdown-footer">Tất cả tin nhắn</a>
                    </div>
                </li>

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown">
                        <?php if($currentUser['profilePicture'] == null):?>
                        <img src="./images/profile_default.jpg" class="img-circle" alt="Avatar" width="25" height="25">
                        <?php else:?>
                        <img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($currentUser['profilePicture']); ?>"
                            class="img-circle" alt="Avatar" width="25" height="25">
                        <?php endif; ?>
                        <?php echo $currentUser['firstname'] .' '. $currentUser['lastname']; ?>
                        <span><?php $currentUser['firstname'].''.$currentUser['lastname'] ;?></span></a>
                    <ul class=" dropdown-menu  dropdown-menu-right">
                        <li><a href="information.php?id=<?php echo $currentUser['id']; ?>"><i
                                    class="fas fa-user-tie"></i> <span>Trang cá nhân</span></a></li>
                        <li><a href="changePassword.php"><i class="fas fa-exchange-alt"></i> <span>Đổi mật
                                    khẩu</span></a></li>
                        <li><a href="updateProfile.php"><i class="fas fa-user-edit"></i> <span>Đổi thông tin cá
                                    nhân</span></a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Đăng xuất</span></a></li>
                        <?php endif; ?>

                    </ul>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- ========================== -->
        <!-- <div class="container-fluid-bd"> -->