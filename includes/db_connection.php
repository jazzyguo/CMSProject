<?php
//create mysql connection
$mysqli = new mysqli('localhost', 'root', '', 'guo');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
        . $mysqli->connect_error);
}
