<?php
$config = require(__DIR__ . '/../../../../common/config/main-local.php');

$request = $_POST;

if (!$request && $request['links_id']) return false;

try {
    $dbh = new PDO($config['components']['db']['dsn'], $config['components']['db']['username'], $config['components']['db']['password']);
    $dbh->query("set names utf8;");

    $sth = $dbh->query("select id, anchor, parent, start from links where id = {$request['links_id']};");
    $link = $sth->fetch();

    if ($link) {
        $url = '/'.$link['anchor'];

        if ($link['parent'] !== null) {
            $parent = $link['parent'];

            do {
                $sth = $dbh->query("select id, anchor, parent, start from links where id = $parent;");
                $link = $sth->fetch();
                $parent = $link['parent'];

                $url = '/'.$link['anchor'].$url;
            } while($parent !== null);
        }

        $response = [
            'url' => $url
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }  else {
        return false;
    }

    $sth = null;
    $dsn = null;
} catch (PDOException $e) {
    die();
}