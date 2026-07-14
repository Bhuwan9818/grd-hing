<?php
require_once __DIR__ . '/includes/auth.php';
grd_admin_logout();
header('Location: login.php');
exit;
