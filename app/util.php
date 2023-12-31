<?php

namespace app\util;
require_once $_SERVER["DOCUMENT_ROOT"] . "/app/exception/http.php";

use app\exception\HttpException;

/**
 * @throws HttpException
 */
function getConfig(): array
{
    $defaultConfig = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . "/resource/default.ini", true);
    if (!$defaultConfig) {
        throw new HttpException("기본 설정 파일을 찾을 수 없습니다.");
    }
    $stage = $defaultConfig["meta"]["stage"];
    $config = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . "/resource/$stage.ini", true);
    if (!$config) {
        throw new HttpException("설정 파일을 찾을 수 없습니다: $stage");
    }
    return $config;
}

/**
 * @throws HttpException
 */
function getDbConn(): \mysqli
{
    $config = getConfig();
    if (!array_key_exists("database", $config)) {
        throw new HttpException("Database 설정을 찾을 수 없습니다.");
    }
    $database = $config["database"];
    $conn = mysqli_connect(hostname: $database["host"], username: $database["user"],
        password: $database["password"], port: $database["port"], database: $database["db"]);
    if (!$conn) {
        throw new HttpException("DB에 연결할 수 없습니다.");
    } else {
        return $conn;
    }
}

/**
 * @throws HttpException
 */
function safeMysqliQuery(\mysqli $conn, string $query): \mysqli_result|bool
{
    $result = mysqli_query($conn, $query);
    if (!$result) {
        $conn->close();
        throw new HttpException("Database에서 Query를 실행할 수 없습니다." . "Query: " . $query);
    }
    return $result;
}
