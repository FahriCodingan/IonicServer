<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require_once "config.php";

function getInput() {
    return json_decode(file_get_contents("php://input"), true);
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // =======================
    // 🔹 LOGIN USER
    // =======================
    case 'POST':
        // Jika ada parameter ?action=login di URL
        if (isset($_GET['action']) && $_GET['action'] === 'login') {
            $input = getInput();
            $email = $conn->real_escape_string($input['email'] ?? '');
            $password = $conn->real_escape_string($input['password'] ?? '');

            if (!$email || !$password) {
                echo json_encode(["error" => "Email dan password wajib diisi"]);
                exit;
            }

            $result = $conn->query("SELECT * FROM users WHERE email = '$email' AND password = '$password' LIMIT 1");

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                // Buat token sederhana
                $token = base64_encode(json_encode([
                    "id" => $user['id'],
                    "email" => $user['email'],
                    "time" => time()
                ]));

                echo json_encode([
                    "message" => "Login berhasil",
                    "token" => $token,
                    "user" => [
                        "id" => $user['id'],
                        "nama" => $user['nama'],
                        "email" => $user['email']
                    ]
                ]);
            } else {
                echo json_encode(["error" => "Email atau password salah"]);
            }
            break;
        }

        // =======================
        // 🔹 TAMBAH DATA USER
        // =======================
        $input = getInput();
        $name = $conn->real_escape_string($input['nama'] ?? '');
        $email = $conn->real_escape_string($input['email'] ?? '');
        $password = $conn->real_escape_string($input['password'] ?? '');

        if ($name && $email && $password) {
            $conn->query("INSERT INTO users (nama, email, password) VALUES ('$name', '$email', '$password')");
            echo json_encode(["message" => "Data berhasil ditambahkan"]);
        } else {
            echo json_encode(["error" => "Nama, Email, dan Password wajib diisi"]);
        }
        break;

    // =======================
    // 🔹 TAMPILKAN DATA USER
    // =======================
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $result = $conn->query("SELECT * FROM users WHERE id = $id");
            $data = $result->fetch_assoc();
            echo json_encode($data);
        } else {
            $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        break;

    // =======================
    // 🔹 UPDATE DATA USER
    // =======================
    case 'PUT':
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "ID wajib disertakan"]);
            break;
        }

        $id = intval($_GET['id']);
        $input = getInput();
        $name = $conn->real_escape_string($input['nama'] ?? '');
        $email = $conn->real_escape_string($input['email'] ?? '');
        $password = $conn->real_escape_string($input['password'] ?? '');

        $conn->query("UPDATE users SET nama='$name', email='$email', password='$password' WHERE id=$id");
        echo json_encode(["message" => "Data berhasil diupdate"]);
        break;

    // =======================
    // 🔹 HAPUS DATA USER
    // =======================
    case 'DELETE':
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "ID wajib disertakan"]);
            break;
        }

        $id = intval($_GET['id']);
        $conn->query("DELETE FROM users WHERE id=$id");
        echo json_encode(["message" => "Data berhasil dihapus"]);
        break;

    // =======================
    // 🔹 DEFAULT
    // =======================
    default:
        echo json_encode(["error" => "Metode tidak didukung"]);
        break;
}
?>