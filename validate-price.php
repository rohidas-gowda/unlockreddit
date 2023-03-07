<?php
require_once 'config.php';

$data = json_decode(trim(file_get_contents("php://input")));

$user_email = $data->email;
$user_free = $data->free;
$user_premium = $data->premium;
$user_dtsignup = $data->dtsignup;

$sql = "INSERT INTO validateuser(registerdate,email,freeregister,proregister) VALUES ('$user_dtsignup', '$user_email', '$user_free', '$user_premium')";
$result = mysqli_query($conn, $sql);

echo json_encode($data);

?>

