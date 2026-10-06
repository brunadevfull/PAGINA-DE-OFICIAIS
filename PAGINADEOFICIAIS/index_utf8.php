<?php
session_start();
include 'config/config.php';
include 'controllers/OficialController.php';

$controller = new OficialController();
$controller->index();
?>

