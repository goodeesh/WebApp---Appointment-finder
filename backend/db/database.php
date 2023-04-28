<?php
require_once('db.php');
$db_obj = new mysqli($host, $user, $passwort, $database);
if ($db_obj->connect_error) {
    echo ("Connection failed: " . $db_obj->connect_error);
    exit();
} else {
}
?>