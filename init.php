<?php

// Load Composer's autoloader
require_once ('vendor/autoload.php');

require_once ('config.php');
// Load functions
require_once ('functions.php');

// Error detector
ini_set('display_error', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Launch session
session_start();

// Detect page
$page = detectPage();

// Connect to Database
$servername = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8";
$db_user = $DB_USER;
$db_pass = $DB_PASS;

try {
 $db = new PDO($servername, $db_user, $db_pass);
 $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
 die("Connection failed: " . $e->getMessage());
}

// Detect user
$currentUser = null;

if (isset($_SESSION['userID'])) {
 $currentUser = findUserByID($_SESSION['userID']);
}