<?php

$db = new mysqli('127.0.0.1', 'root', '');
if ($db->connect_error) {
    echo $db->connect_error . PHP_EOL;
    exit(1);
}

$result = $db->query("SHOW DATABASES LIKE 'jcses_pta_system'");
$row = $result ? $result->fetch_assoc() : null;
var_export($row);

echo PHP_EOL;
