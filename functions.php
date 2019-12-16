<?php

require_once './vendor/autoload.php';
require_once 'config.php';

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function detectPage()
{
        $uri      = $_SERVER['REQUEST_URI'];
        $part     = explode('/', $uri);
        $fileName = $part[2];
        $part     = explode('.', $fileName);
        $page     = $part[0];
        return $page;
}

function findUser($input)
{
        global $db;
        $stmt = $db->prepare("SELECT * FROM `user_accounts` WHERE `email` = ? OR `username` = ?");
        $stmt->execute(array($input, $input));
        return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findUserByID($userID)
{
        global $db;
        $stmt = $db->prepare("SELECT * FROM `user_accounts` WHERE `id` = ?");
        $stmt->execute(array($userID));
        return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Generate a random Activation Code
function generateCode($length = 10)
{
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
}

// Create a user's account
function createUser($f_name, $l_name, $email, $username, $passsword, $profilePicture, $birthday, $phonenumber)
{
        global $db, $BASE_URL;
        $command = "INSERT INTO `user_accounts`(`firstname`, `lastname`, `email`, `username`, `password`, `confirmStatus`, `activationCode`,profilePicture,Birthday,phoneNumber,Education,Location,Skill,Notes)
                VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?,null,null,null,null)";
        $hashPass       = password_hash($passsword, PASSWORD_DEFAULT);
        $activationCode = generateCode(16);
        $stmt           = $db->prepare($command);
        $stmt->execute(array($f_name, $l_name, $email, $username, $hashPass, 0, $activationCode, $profilePicture, $birthday, $phonenumber));
        $newAccount = $db->lastInsertId();
        // Send mail
        //die();
        sendMail(
                $email,
                $l_name,
                'Confirm email',
                "Your activation code is:
        <a href=\"$BASE_URL/confirmEmail.php?activationCode=$activationCode\">$BASE_URL/confirmEmail.php?activationCode=$activationCode</a>"
        );
        return $newAccount;
}

/// Change password
function updateUserPassword($email, $newPass)
{
        global $db;
        $hashPass = password_hash($newPass, PASSWORD_DEFAULT);
        $command  = "UPDATE `user_accounts` SET `password`= ? WHERE `email` = ?";
        $stmt     = $db->prepare($command);
        return $stmt->execute(array($hashPass, $email));
}

/// Update Profile
function updateUserProfile($id, $f_name, $l_name, $phonenumber, $birthday, $education,$location,$skill, $notes)
{
        global $db;
        $command = "UPDATE `user_accounts` SET `firstname`= ?, `lastname` = ?, `phoneNumber` = ?, `Birthday` = ? ,Education=? , Location = ?, Skill = ?, Notes = ? WHERE `id` = ?";
        $stmt    = $db->prepare($command);
        return $stmt->execute(array($f_name, $l_name, $phonenumber, $birthday ,$education,$location,$skill,$notes, $id));
}
/// Update Profile Picture
function updateUserProfilePicture($id, $image)
{
        global $db;
        $command = "UPDATE `user_accounts` SET `profilePicture` = ? WHERE `id` = ?";
        $stmt    = $db->prepare($command);
        return $stmt->execute(array($image, $id));
}

// Resize image
function resizeImage($filename, $max_width, $max_height)
{
        list($orig_width, $orig_height) = getimagesize($filename);

        $width  = $orig_width;
        $height = $orig_height;

        # taller
        if ($height > $max_height) {
                $width  = ($max_height / $height) * $width;
                $height = $max_height;
        }

        # wider
        if ($width > $max_width) {
                $height = ($max_width / $width) * $height;
                $width  = $max_width;
        }

        $image_p = imagecreatetruecolor($width, $height);

        $image = imagecreatefromjpeg($filename);

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

        return $image_p;
}

/// Get newsfeed
function getNewsFeed($start, $limit)
{
        global $db;
        $stmt = $db->prepare('SELECT p.*, u.firstname, u.lastname, u.username, u.profilePicture FROM `user_posts` AS p
                        JOIN `user_accounts` AS u ON p.id = u.id ORDER BY `post_time` DESC LIMIT ?,?');
        $stmt->bindParam(1, $start, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
}

/// Add new post
function createPost($userID, $content,$privacy,$image)
{
        global $db;
        $command = "INSERT INTO `user_posts` (image,privacy,content, id) VALUES (?, ? ,? ,?)";
        $stmt    = $db->prepare($command);
        $stmt->execute(array($image,$privacy,$content, $userID));
        return $db->lastInsertId();
}
function showPost($id)
{
        global $db;
        $stmt = $db->prepare("SELECT us.postID, uc.profilePicture,uc.firstname,uc.lastname,us.post_time,us.content,us.privacy,us.image FROM `user_posts` us, user_accounts uc Where us.id= ?
                                and us.id=uc.id order by `post_time` DESC");
        $stmt->execute(array($id));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/// Send Mail
function sendMail($to, $toName, $subject, $content)
{
        global $EMAIL_FROM, $EMAIL_NAME, $EMAIL_PASSWORD;
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        //Server settings
        $mail->isSMTP(); // Send using SMTP

        $mail->CharSet    = 'UTF-8';
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true; // Enable SMTP authentication
        $mail->Username   = $EMAIL_FROM; // SMTP username
        $mail->Password   = $EMAIL_PASSWORD; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587; // TCP port to connect to

        //Recipients
        $mail->setFrom($EMAIL_FROM, $EMAIL_NAME);
        $mail->addAddress($to, $toName); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
        //  $mail->AltBody = $content;

        $mail->send();
        return true;
}

/// Find user activation code and confirm status
function findUserCode($activationCode)
{
        global $db;
        $command = "SELECT * FROM `user_accounts` WHERE `activationCode` = ?";
        $stmt    = $db->prepare($command);
        $stmt->execute(array($activationCode));
        return $stmt->fetch(PDO::FETCH_ASSOC);
}

/// Confirm email
function confirmEmail($activationCode, $id)
{
        global $db;
        $command = "UPDATE `user_accounts` SET `activationCode` = ?, `confirmStatus` = ? WHERE `id` = ?";
        $stmt    = $db->prepare($command);
        $stmt->execute(array('', 1, $id));
        return true;
        return false;
}

/// Recover Code
function addRecoverCode($activationCode, $email, $username, $l_name)
{
        global $db, $BASE_URL;
        $command = "UPDATE `user_accounts` SET `activationCode` = ? WHERE `email` = ? OR `username` = ?";
        $stmt    = $db->prepare($command);
        $stmt->execute(array($activationCode, $email, $username));
        // Send mail
        sendMail( $email, $l_name,'Recover your account',"Click the link to change password:<a href=\"$BASE_URL/confirmEmail.php?activationCode=$activationCode\">$BASE_URL/confirmEmail.php?activationCode=$activationCode</a>");
}
//Xóa status
function DeleteContentbyID($id)
{
        global $db;
        $stmt = $db->prepare("DELETE FROM `user_posts` WHERE `postID` = ? ");
        $stmt->execute(array($id));
}
//PHÂN TRANG
//total page
function totalpage()
{
        global $db;
        $stmt = $db->prepare("SELECT COUNT(id) as 'total' FROM `user_posts`");
        $stmt->execute();
        $result =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
                return $row['total'];
        }
}
//
function findrelationship($userid1, $userid2)
{
        global $db;
        $stmt = $db->prepare("SELECT * FROM friends WHERE UserID_1 = ? AND UserID_2 = ? OR UserID_1 = ? AND UserID_2 = ?");
        $stmt->execute(array($userid1, $userid2, $userid2, $userid1));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function Send_Accept_FriendRequest($userid1, $userid2)
{
        global $db;
        $stmt = $db->prepare("INSERT INTO friends(UserID_1,UserID_2) VALUES(?, ?)");
        $newAccount = $stmt->execute(array($userid1, $userid2));
        return $newAccount;
}
function cancel_delete_FriendRequest($userid1, $userid2)
{
        global $db;
        $stmt = $db->prepare("DELETE FROM friends WHERE ( UserID_1 = ? AND UserID_2 = ?) OR ( UserID_1 = ? AND UserID_2 = ?) ");
        $stmt->execute(array($userid1, $userid2, $userid2, $userid1));
}
//Hàm lấy tất cả bạn bè của userID
function getFriends($userId)
{
        global $db;
        $stmt = $db->prepare("SELECT * FROM friends WHERE UserID_1 = ? OR UserID_2 = ?");
        $stmt->execute(array($userId, $userId));
        $followings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $friends = array();
        for ($i = 0; $i < count($followings); $i++) {
                $row1 = $followings[$i];
                if ($userId == $row1['UserID_1']) {
                        $userId2 = $row1['UserID_2'];
                        for ($j = 0; $j < count($followings); $j++) {
                                $row2 = $followings[$j];
                                if ($userId == $row2['UserID_2'] && $userId2 == $row2['UserID_1']) {
                                        $friends[] = findUserById($userId2);
                                }
                        }
                }
        }
        return $friends;
}
//Lấy danh sách tất cả accouts trong db
function getSuggestionFriend() {
        global $db;
        $stmt = $db->prepare("SELECT * FROM `user_accounts`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
//hàm newfeed khi đã là friend
function getNewFeedsForUserId($userId,$start,$limit) {
        global $db;
        $friends = getFriends($userId);
        $friendIds = array();
        foreach ($friends as $friend) {
          $friendIds[] = $friend['id'];
        }
        $friendIds[] = $userId;
        $stmt = $db->prepare("SELECT p.postID, p.id, u.firstname ,u.lastname, u.profilePicture, p.content, p.post_time,p.privacy,p.image FROM user_posts as p LEFT JOIN user_accounts as u ON u.id = p.id WHERE p.id IN (" . implode(',', $friendIds) .  ") ORDER BY post_time DESC LIMIT ?,?");
        $stmt->bindParam(1, $start, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
//Lấy tin nhắn gần đây nhất 
function getLatestConversations($userId) {
        global $db;
        $stmt = $db->prepare("SELECT UserID2 AS id, u.firstname,u.lastname, u.profilePicture FROM messages AS m LEFT JOIN user_accounts AS u ON u.id = m.UserID2 WHERE UserID1 = ? GROUP BY UserID2 ORDER BY CreateTime DESC");
        $stmt->execute(array($userId));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for ($i = 0; $i < count($result); $i++) {
          $stmt = $db->prepare("SELECT * FROM messages WHERE UserID1 = ? AND UserID2 = ? ORDER BY CreateTime DESC LIMIT 1");
          $stmt->execute(array($userId, $result[$i]['id']));
          $lastMessage = $stmt->fetch(PDO::FETCH_ASSOC);
          $result[$i]['lastMessage'] = $lastMessage;
        }
        return $result;
}
//Gửi tin nhắn
function sendMessage($userId1, $userId2, $content) {
        global $db;
        $stmt = $db->prepare("INSERT INTO messages (UserID1,UserID2,Content,Type, CreateTime) VALUE (?, ?, ?, ?, CURRENT_TIMESTAMP())");
        $stmt->execute(array($userId1, $userId2, $content, 0));
        $id = $db->lastInsertId();
        $stmt = $db->prepare("SELECT * FROM messages WHERE ID = ?");
        $stmt->execute(array($id));
        $newMessage = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $db->prepare("INSERT INTO messages (UserID2, UserID1,Content, Type, CreateTime) VALUE (?, ?, ?, ?, ?)");
        $stmt->execute(array($userId1, $userId2, $content, 1, $newMessage['CreateTime']));
}
//Lấy tin nhắn
function getMessagesWithUserId($userId1, $userId2) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM messages WHERE UserID1 = ? AND UserID2= ? ORDER BY CreateTime");
        $stmt->execute(array($userId1, $userId2));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function createComments($postID,$userID,$content)
{
        global $db;
        $command = "INSERT INTO `comments` (Content,UserID,PostID) VALUES (?, ?,?)";
        $stmt    = $db->prepare($command);
        $stmt->execute(array($content, $userID,$postID));
        return $db->lastInsertId();
}
function showComment($idPost)
{
        global $db;
        $stmt = $db->prepare("SELECT uc.firstname,uc.lastname,uc.profilePicture,cmt.PostID,cmt.Content,cmt.CreateTime FROM `user_posts` up, comments cmt, user_accounts uc Where cmt.PostID= ? and up.PostID=cmt.PostID and uc.id=cmt.UserID order by CreateTime DESC");
        $stmt->execute(array($idPost));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function sendEmailAddFriend($email, $l_name,$from,$idfrom)
{
        global $BASE_URL;
        sendMail(
                $email,
                $l_name,
                'Friend request',
                "$from sent you a friend request 
                <a href=\"$BASE_URL/information.php?id=$idfrom\">$BASE_URL/information.php?id=$idfrom</a>"
        );
} 
/////LIKE
function Likes($postID, $userID)
{
        global $db;
        $stmt = $db->prepare("INSERT INTO postlikes(PostID,UserID) VALUES(?, ?)");
        $newAccount = $stmt->execute(array($postID, $userID)); 
        return $newAccount;
}
function Unlikes($postID, $userID)
{
        global $db;
        $stmt = $db->prepare("DELETE FROM postlikes WHERE  PostID = ? AND UserID = ?");
        $stmt->execute(array($postID,$userID));
}
function findlikeforUserID($postID,$userID)
{
        global $db;
        $stmt = $db->prepare("SELECT * FROM postlikes WHERE PostID = ? and UserID = ?");
        $stmt->execute(array($postID,$userID));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function findlikePost($postID)
{
        global $db;
        $stmt = $db->prepare("SELECT * FROM postlikes WHERE PostID = ?");
        $stmt->execute(array($postID));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function updatePublic($postID){
        global $db;
        $command  = "UPDATE `user_posts` SET `privacy`= 0 WHERE `postID` = ?";
        $stmt     = $db->prepare($command);
        return $stmt->execute(array($postID));
}
function updateFriend($postID){
        global $db;
        $command  = "UPDATE `user_posts` SET `privacy`= 1 WHERE `postID` = ?";
        $stmt     = $db->prepare($command);
        return $stmt->execute(array($postID));
}
function updatePrivate($postID){
        global $db;
        $command  = "UPDATE `user_posts` SET `privacy`= 2 WHERE `postID` = ?";
        $stmt     = $db->prepare($command);
        return $stmt->execute(array($postID));
}
function DeleteMessageforUserID($userID1,$UserID2)
{
        global $db;
        $stmt = $db->prepare("DELETE FROM `messages` WHERE `UserID1` = ?  and UserID2 = ?" );
        $stmt->execute(array($userID1,$UserID2));
}



