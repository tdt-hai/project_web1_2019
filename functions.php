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
function createUser($f_name, $l_name, $email, $username, $passsword,$profilePicture,$birthday,$phonenumber)
{
        global $db, $BASE_URL;
        $command = "INSERT INTO `user_accounts`(`firstname`, `lastname`, `email`, `username`, `password`, `confirmStatus`, `activationCode`,profilePicture,Birthday,phoneNumber)
                VALUES (?, ?, ?, ?, ?, ?, ?,?,?,?)";
        $hashPass       = password_hash($passsword, PASSWORD_DEFAULT);
        $activationCode = generateCode(16);
        $stmt           = $db->prepare($command);
        $stmt->execute(array($f_name, $l_name, $email, $username, $hashPass, 0, $activationCode,$profilePicture,$birthday,$phonenumber));
        $newAccount = $db->lastInsertId();
        // Send mail
        die();
        sendMail($email, $l_name, 'Confirm email',
        "Your activation code is:
        <a href=\"$BASE_URL/confirmEmail.php?activationCode=$activationCode\">$BASE_URL/confirmEmail.php?activationCode=$activationCode</a>");
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
function updateUserProfile($id, $f_name, $l_name, $phonenumber, $birthday)
{
        global $db;
        $command = "UPDATE `user_accounts` SET `firstname`= ?, `lastname` = ?, `phoneNumber` = ?, `Birthday` = ? WHERE `id` = ?";
        $stmt    = $db->prepare($command);
        return $stmt->execute(array($f_name, $l_name, $phonenumber, $birthday, $id));
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
function getNewsFeed()
{
        global $db;
        $command = "SELECT p.*, u.firstname, u.lastname, u.username, u.profilePicture FROM `user_posts` AS p
                        JOIN `user_accounts` AS u ON p.id = u.id ORDER BY `post_time` DESC";
        $stmt = $db->query($command);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/// Add new post
function createPost($userID, $content)
{
        global $db;
        $command = "INSERT INTO `user_posts` (content, id) VALUES (?, ?)";
        $stmt    = $db->prepare($command);
        $stmt->execute(array($content, $userID));
        return $db->lastInsertId();
}
function showPost($id)
{
        global $db;
        $stmt = $db->prepare("SELECT us.postID, uc.profilePicture,uc.firstname,uc.lastname,us.post_time,us.content FROM `user_posts` us, user_accounts uc Where us.id= ? and us.id=uc.id");
        $stmt->execute(array($id));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function showPost2($id)
{
        global $db;
        $stmt = $db->prepare("SELECT uc.profilePicture,uc.firstname,uc.lastname,us.post_time,us.content FROM `user_posts` us, user_accounts uc Where us.id= ? and us.id=uc.id");
        $stmt->execute(array($id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
 sendMail($email, $l_name, 'Recover your account',
  "Click the link to change password:
  <a href=\"$BASE_URL/confirmEmail.php?activationCode=$activationCode\">$BASE_URL/confirmEmail.php?activationCode=$activationCode</a>");
}

function DeleteContentbyID($id){
        global $db ;
        $stmt = $db->prepare("DELETE FROM `user_posts` WHERE `postID` = ? " );
        $stmt->execute(array($id));
        //return $stmt->fetch(PDO::FETCH_ASSOC);
}
