<?php
$host     = getenv('DB_HOST') ?: 'localhost';
$db       = getenv('DB_NAME') ?: 'ecoride';
$user     = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$charset  = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];


define('BASE_URL', getenv('APP_ENV') ?: 'http://localhost:8080');

//Refaire la même chose pour mongo (ok)

$mongoHost = getenv('MONGO_HOST') ?: 'localhost:27017';
$mongoDb   = getenv('MONGO_DB') ?: 'ecoride_nosql';
$mongoUser = getenv('MONGO_USER') ?: 'root';
$mongoPassword = getenv('MONGO_PASSWORD') ?: 'example';

$mongoDsn = "mongodb://$mongoUser:$mongoPassword@$mongoHost";

$mailServer = getenv('MAIL_SERVER') ?: "smtp.gmail.com";
$mailUser = getenv('MAIL_USER') ?: "";
$mailPassword = getenv('MAIL_PASSWORD') ?: "";

