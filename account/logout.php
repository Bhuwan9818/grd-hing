<?php
require_once __DIR__ . '/../includes/auth.php';
grd_logout_customer();
header('Location: ../index.php');
exit;
