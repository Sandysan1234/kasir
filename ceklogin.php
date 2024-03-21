<?php
require 'function.php';


if (!isset($_SESSION['login'])) {
    header('location: login.php');
    exit; // Pastikan untuk keluar setelah melakukan pengalihan
}
?>


?>