<?php
header('Content-type: text/html; charset=utf-8');
include 'config.php';
require 'vendor/autoload.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = true;
$config['determineRouteBeforeAppMiddleware'] = true;

use Slim\Views\PhpRenderer;

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();
$container['renderer'] = new PhpRenderer("./views");

//$app->get('/ip[/{ip1}[/{ip2}[/{status}]]]', function ($request, $response, $args) {
$app->get('/ip[/{ip1}[/{ip2}]]', function ($request, $response, $args) {
    // IP block:
    $ip1 = (isset($args['ip1']) ? $args['ip1'] : null);
    // filter status:
    $ip2 = (isset($args['ip2']) ? $args['ip2'] : null);

    $args['baseUri'] = $request->getUri()->getBasePath();
    $args['title'] = TITLE;
    echo 0;
    if ($ip1 != null) {
        $db = new ezSQL_mysqli(USER, PASS, DB, HOST);
        echo 1;
        if (isset($ip1) and isset($ip2)) {
            echo 2;
            if ($ip2 == "up" || $ip2 == "down") {
                echo 3;
                $like = '160.75.' . $ip1 . '.%';
                $sql = "SELECT ip, host, mac, vendor, status, timestr FROM netmon";
                $sql .= " WHERE ip LIKE '$like' AND status='$ip2' ORDER BY INET_ATON(ip)";
            } else {
                echo 4;
                $like = '160.75.' . $ip1 . '.' . $ip2;
                $sql = "SELECT netmon.ip, netmon.host, iplog.mac, iplog.vendor, iplog.status, iplog.timestr FROM netmon
                LEFT JOIN iplog ON netmon.id = iplog.netmon_id
                WHERE netmon.ip = '$like'
                ORDER BY iplog.timestr DESC";
            }
        } elseif (isset($ip1)) {
            $like = '160.75.' . $ip1 . '.%';
            $sql = "SELECT ip, host, mac, vendor, status, timestr FROM netmon";
            $sql .= " WHERE ip LIKE '$like' ORDER BY INET_ATON(ip)";
        }
        $args['result'] = $db->get_results($sql);
    }
    return $this->renderer->render($response, "/index.phtml", $args);
});

$app->run();

function url($path = null, $absolute = false)
{
    $url = pathinfo($_SERVER["PHP_SELF"], PATHINFO_DIRNAME) . "/" . $path;
    $url = preg_replace("/\/\/+/", "/", $url);
    if ($absolute) {
        $url = "http://" . $_SERVER["SERVER_NAME"] . $url;
    }
    return $url;
};
