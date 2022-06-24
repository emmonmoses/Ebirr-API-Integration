<?php
/** * Author : Emmon Moses */

require_once '../connection/config.php';
require_once 'jwt_utils.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input", true));
    $sql = "SELECT * FROM tblusers WHERE username = '" . mysqli_real_escape_string($conn, $data->email) . "' AND userpassword = '" . mysqli_real_escape_string($conn, $data->password) . "' LIMIT 1";
    $result = dbQuery($sql);

    if (dbNumRows($result) < 1) {
        echo json_encode(array(
            'success' => false,
            'message' => "Authentication failed",
            'error_code' => 403
        ));
    }
    else {
        $sql2 = "SELECT * FROM tblusers WHERE username = '" . mysqli_real_escape_string($conn, $data->email) . "' AND userpassword = '" . mysqli_real_escape_string($conn, $data->password) . "' LIMIT 1";
        $result2 = dbQuery($sql);
        $row = dbFetchAssoc($result);

        $email = $row['username'];
        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        // jwt valid for 30 days (60 seconds * 60 minutes * 24 hours * 30 days)
        $issuedAt = time();
        $payload = array('username' => $email, 'exp' => ($issuedAt + 60 * 60 * 24 * 30));

        $rows;
        $jwt = generate_jwt($headers, $payload);

        while ($row = dbFetchAssoc($result2)) {
            $rows = [
                "id" => $row['Id'],
                "user_id" => $row['Id'],
                "name" => $row['fullname'],
                "email" => $row['username'],
                "email_verified_at" => $row['actiondate'],
                "created_at" => $row['actiondate'],
                "updated_at" => $row['actiondate']
            ];
        }
        echo json_encode(array('user' => $rows, 'token' => $jwt));
    }
}