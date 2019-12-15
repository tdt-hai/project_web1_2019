<?php 
require_once 'init.php';
if (isset($_POST['send'])) {
    sendMessage($currentUser['id'], $_GET['id'], $_POST['message']); 
  }
  $messages = getMessagesWithUserId($currentUser['id'], $_GET['id']);
  $user = findUserById($_GET['id']);
?>
<?php include 'header.php' ?>
<div class="container">
<div class="col-sm-4">
    <!-- DIRECT CHAT PRIMARY -->
    <div class="card card-prirary cardutline direct-chat direct-chat-primary">
        <div class="card-header">
           <h3 class="card-title">&ensp;<?php echo $user['firstname'].''.$user['lastname']  ?></h3>
            <div class="card-tools">
                <!-- <span data-toggle="tooltip" title="3 New Messages" class="badge bg-primary">3</span>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts"
                          data-widget="chat-pane-toggle">
                    <i class="fas fa-comments"></i></button> -->
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <!-- Conversations are loaded here -->
            <div class="direct-chat-messages">
                <?php foreach ($messages as $message): ?>
                <!-- Message. Default to the left -->
                <?php if ($message['Type'] == 1):?>  <!--Hiển thị tin nhắn của bạn bè gửi lại -->
                <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                        <span
                            class="direct-chat-name float-left"><?php echo $user['firstname'].''.$user['lastname']  ?></span>
                        <span class="direct-chat-timestamp float-right"><?php echo $message['CreateTime']?></span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img"
                        src="<?php echo 'data:image/jpeg;base64,' . base64_encode($user['profilePicture']); ?>"
                        alt="Message User Image">

                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                        <?php echo $message['Content'] ?>
                    </div>
                    <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->

                <?php else :?> <!-- Hiển thị tin nhắn của mình -->

                <!-- Message to the right -->
                <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                        <span
                            class="direct-chat-name float-right"><?php echo $currentUser['firstname'].''.$currentUser['lastname'] ?></span>
                        <span class="direct-chat-timestamp float-left"><?php echo $message['CreateTime'] ?></span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img"
                        src="<?php echo 'data:image/jpeg;base64,' . base64_encode($currentUser['profilePicture']); ?>"
                        alt="Message User Image">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                        <?php echo $message['Content']; ?>
                    </div>
                    <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->
                <?php endif;?>
                <?php endforeach;?>
            </div>
            <!--/.direct-chat-messages-->
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <form method="POST">
                <div class="input-group">
                    <input type="text" name="message" placeholder="Aa" class="form-control">
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-primary" name="send">Gửi</button>
                    </span>
                </div>
            </form>
        </div>
        <!-- /.card-footer-->
    </div>
    <!--/.direct-chat -->
</div>
</div>
<?php include 'footer.php' ?>