<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include "config.php";
// $conn = new mysqli("localhost", "root", "", "dataionic");

$data = json_decode(file_get_contents("php://input"), true);
// var_dump($data);

$email = trim($data['email'] ?? '');
$password = trim($data['password']?? '');
$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = mysqli_query($conn, $sql);
$method = $_SERVER['REQUEST_METHOD'];

$sandi_rahasia = "rahasia09";
$headers = getallheaders();
$kunci_pengguna = $headers['X-API_KUNCI'] ?? "rahasia09";

if ($kunci_pengguna !== $sandi_rahasia) {
        echo json_encode(["Error" => "Anda tidak berhak masuk"]);
        exit();
} else {
    if(empty($email) || empty($password)){
        echo json_encode(["success" => false, "message" => "Email atau password kosong"]);
    } else {
        if(mysqli_num_rows($result) > 0){
            echo json_encode(["success" => true, "message" => "Login berhasil"]);
        } else{
            echo json_encode(["success" => false, "message" => "Email atau password salah"]);
        }
    }
}
?>