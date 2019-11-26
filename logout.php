<?php
require_once 'init.php';

unset($_SESSION['userID']);
unset($db);
header('Location: index.php');
exit();