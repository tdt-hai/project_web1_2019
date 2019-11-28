<!doctype html>
<html lang="vi">

    <head>
        <!-- Required meta tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet"
              href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
              crossorigin="anonymous">
        <style type="text/css">
        </style>
         <link rel="stylesheet" type="text/css" href="./css_files/main.css">
        <title>BTN</title>
    </head>

    <body>
        <div class="container-fluid polaroid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">BTN</a>
                    </li>
                    <li class="nav-item <?php echo $page == 'index' ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php">Trang chủ</span></a>
                    </li>
                </ul>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav nav-pills navbar-nav">
                        <?php if (!$currentUser): ?>
                        <li class="nav-item  <?php echo $page == 'login' ? 'active' : ''; ?>">
                            <a class="nav-link" href="login.php">Đăng nhập</a>
                        </li>
                        <li class="nav-item  <?php echo $page == 'register' ? 'active' : ''; ?>">
                            <a class="nav-link" href="register.php">Đăng ký</a>
                        </li>

                        <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"
                               role="button" aria-haspopup="true" aria-expanded="false">Tùy
                                chọn</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="updateProfile.php">Đổi thông tin cá
                                    nhân</a>
                                <a class="dropdown-item" href="changePassword.php">Đổi mật
                                    khẩu</a>
                                    <a class="dropdown-item" href="information.php">Trang cá
                                      nhân</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Đăng xuất
                                    <?php echo $currentUser ? '(' . $currentUser['firstname'] . ' ' . $currentUser['lastname'] . ')' : ''; ?></a>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="container-fluid-bd">