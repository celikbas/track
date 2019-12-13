<?php
header('Content-Type: text/html; charset=utf-8');

include 'config.php';
$auth = Auth::Login($valid_user, $valid_pass);
if ($auth) {
    echo "<h1>".TITLE." - Parsing NMAP Data</h1>";
}

require 'vendor/autoload.php';

$db = new ezSQL_mysqli(USER, PASS, DB, HOST);

$path = 'data/';
$allfiles = glob($path . "*.xml");

foreach ($allfiles as $file) {
    echo $file . " Dosyasının ayrışıma başlanıyor<br />";
    $xml = simplexml_load_file($file);

    $filedatetime = $xml->runstats->finished[0]['timestr'];
    $format = 'D F d G:i:s Y';
    $date = date_create_from_format($format, $filedatetime);
    $timestr = date_format($date, 'Y-m-d H:i:s');

    // print xml File stats:
    echo "
    File   : " . $file . "</br>
    Scanned: " . $xml->runstats->hosts[0]['total'] . "</br>
    Up/Down: " . $xml->runstats->hosts[0]['up'] . " / " . $xml->runstats->hosts[0]['down'] . "</br>
    Date   : " . $xml->runstats->finished[0]['timestr'] . "</br>
    Date   : " . $timestr . "</br>";

    $created = $updated = $logged = 0;

    $var = $db->get_var("SELECT timestr FROM iplog WHERE timestr = '$timestr'");

    if ($timestr != $var) {
        // Parse xml data and save it to database:
        foreach ($xml->host as $item) {
            $ip = $host = $mac = $status = $vendor = "";
            $ip = isset($item->address[0]['addr']) ? current($item->address[0]['addr']) : "";
            $host = isset($item->hostnames->hostname['name']) ? current($item->hostnames->hostname['name']) : null;
            $mac = isset($item->address[1]['addr']) ? current($item->address[1]['addr']) : null;
            $vendor = isset($item->address[1]['vendor']) ? current($item->address[1]['vendor']) : null;
            $status = current($item->status['state']);
            // Show Info:
            // echo 'IP: ' . $ip . " Host: " . $host . " MAC: " . $mac . " Vendor: " . $vendor . " Status: " . $status . "<br>";

            if ($comp = $db->get_row("SELECT id, ip, status FROM `netmon` WHERE ip='$ip'")) {
                if ($comp->ip == $ip) {
                    if ($status == "up") {
                        $sql = "UPDATE `netmon` SET mac = '$mac', vendor = '$vendor', status = 'up', timestr = '$timestr' WHERE id = '$comp->id'";
                        //echo $sql . "<br>";
                        $db->query($sql);
                        $updated++;
                        //echo "Updated " . $ip . "<br>"; // . "$sql<br>";
                    }
                    $sql = "INSERT INTO `iplog` (netmon_id, mac, vendor, status, timestr) VALUES ( '$comp->id', '$mac', '$vendor', '$status', '$timestr')";
                    $db->query($sql);
                    $logged++;
                    // echo "Update 2 - $sql<br>";
                }
            } else {
                $sql = "INSERT INTO `netmon` (ip, host, mac, vendor, status, timestr) VALUES ('$ip', '$host', '$mac', '$vendor', '$status', '$timestr')";
                $db->query($sql);
                $created++;
                echo "Created " . $ip . "<br>"; // . "$sql<br>";
                $sql = "INSERT INTO `iplog` (netmon_id, mac, vendor, status, timestr) VALUES ('$db->insert_id', '$mac', '$vendor', '$status', '$timestr')";
                $logged++;
                $db->query($sql);
                //echo "Inserted " . $ip . "<br>"; // . "$sql<br>";
            }
            // exit();
        }
        echo "
        Created  : $created <br>
        Updated  : $updated <br>
        Logged    : $logged <br>";
    } else {
        echo "Skipped<br>";
    }
}

class Auth {
    public static function Login($username, $password) {
        $inputUsername = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : false;
        $inputPassword = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : false;
        $validated = ($inputUsername == $username) && ($inputPassword == $password);
        if (!$validated) {
            header('WWW-Authenticate: Basic');
            header('HTTP/1.0 401 Unauthorized');
            die('Authentication needed!');
        }
        return true;
    }

    public static function Logout() {
        header('HTTP/1.1 401 Unauthorized');
    }
}
