<?php
$host = "gateway01.ap-northeast-1.prod.aws.tidbcloud.com";
$dbname = "tourcycle_vigan";
$username = "2pSkLTKVKsBo7db.root";
$password = "AaiDjnq1E7NoOMKa";
$port = 4000;

$conn = mysqli_init();

mysqli_ssl_set($conn, NULL, NULL, __DIR__ . "/isrgrootx1.pem", NULL, NULL);

if (!mysqli_real_connect(
    $conn,
    $host,
    $username,
    $password,
    $dbname,
    $port,
    NULL,
    MYSQLI_CLIENT_SSL
)) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>