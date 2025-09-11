<?php
define('BASE_PATH', __DIR__);
define('BASE_URL', '/TESTECF');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once BASE_PATH . '/libs/bdd.php';
?>
