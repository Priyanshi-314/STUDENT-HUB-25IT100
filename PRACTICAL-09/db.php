<?php
declare(strict_types=1);

function studentHubMysqli(): mysqli
{
    static $connection = null;
    if ($connection instanceof mysqli) {
        return $connection;
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $host = getenv('STUDENTHUB_DB_HOST') ?: '127.0.0.1';
    $port = (int)(getenv('STUDENTHUB_DB_PORT') ?: '3306');
    $database = getenv('STUDENTHUB_DB_NAME') ?: 'studenthub';
    $username = getenv('STUDENTHUB_DB_USER') ?: 'root';
    $password = getenv('STUDENTHUB_DB_PASSWORD');
    $password = $password === false ? '' : $password;

    $connection = new mysqli($host, $username, $password, $database, $port);
    $connection->set_charset('utf8mb4');
    return $connection;
}
