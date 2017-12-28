<?php
$rawData = file_get_contents('php://input');

if (empty($rawData)) {
    exit;
}


$time = date('His', time());
$filename = dirname(__FILE__) . '/../runtime/ali_' . $time . '.txt';
file_put_contents($filename, $rawData);


