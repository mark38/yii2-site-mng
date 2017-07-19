<?php
$config = require(__DIR__ . '/../../../../common/config/main-local.php');

$request = $_POST;

if (!$request) return;

try {
    $dbh = new PDO($config['components']['db']['dsn'], $config['components']['db']['username'], $config['components']['db']['password']);
    $dbh->query("set names utf8;");

    if ($request['parent']) {
        $request = "select id, url, name, anchor, child_exist, level from links where parent = '{$request['parent']}' order by seq asc;";
    } else {
        $request = "select id, url, name, anchor, child_exist, level from links where categories_id = '{$request['categories_id']}' and parent is null order by seq asc;";
    }

    $items = array();
    foreach ($dbh->query($request) as $row) {
        $items[] = [
            'id' => $row['id'],
            'url' => $row['url'],
            'name' => $row['name'],
            'anchor' => $row['anchor'],
            'child_exist' => $row['child_exist'],
            'level' => $row['level']
        ];
    }

    $sth = null;
    $dsn = null;

    echo json_encode($items, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    die();
}