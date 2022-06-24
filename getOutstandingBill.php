<?php
/** * Author : Emmon Moses */

require_once './connection/config.php';
require_once './connection/database.php';
require_once './class/bills.php';
require_once 'jwt_utils.php';

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new Database();
$db = $database->getConnection();
$item = new OutstandingBill($db);

$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url_path = parse_url($url, PHP_URL_PATH);
$item->studentid = pathinfo($url_path, PATHINFO_BASENAME);

$bearer_token = get_bearer_token();
//echo $bearer_token;
$is_jwt_valid = is_jwt_valid($bearer_token);

if ($is_jwt_valid) {
    $query = "SELECT * FROM tblbills WHERE student_id = '$item->studentid'";
    $studentExist = dbQuery($query);

    if (dbNumRows($studentExist) > 0) {

        $sql = "SELECT * FROM tblbills WHERE student_id='$item->studentid'";
        $result = dbQuery($sql);

        while ($row = dbFetchAssoc($result)) {
            $outstandingBill = [
                'student_id' => $row['student_id'],
                'full_name' => $row['full_name'],
                // 'department' => $row['departmentid']
                'payment_type' => $row['payment_type'],
                'amount' => $row['amount'],
                'discount_percent' => $row['discount_percent'],
                'status' => $row['status'],
                'invoice_number' => $row['invoiceid'],
                'due_year_month' => $row['billdate'],
            ];
        }

        echo json_encode(array(
            'success' => true,
            'message' => "The outstanding payment for student $item->studentid",
            'data' => array($outstandingBill) // 200 ok
        ));
    }
    else {
        echo json_encode(array(
            'success' => false,
            'message' => "There is NO student with id number $item->studentid",
            'error_code' => 404 // not found
        ));
    }
}
else {
    echo json_encode(array(
        'success' => false,
        'message' => "Please provide a valid token",
        'error_code' => 403 // unauthorised
    ));
}

?>