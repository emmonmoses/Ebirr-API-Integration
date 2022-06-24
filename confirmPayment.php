<?php
/** * Author : Emmon Moses */

require_once './connection/config.php';
require_once './connection/database.php';
require_once './class/confirm.php';
require_once 'jwt_utils.php';

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new Database();
$db = $database->getConnection();
$item = new Confirmation($db);

$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$url_path = parse_url($url, PHP_URL_PATH);
$item->invoiceid = pathinfo($url_path, PATHINFO_BASENAME);

$bearer_token = get_bearer_token();
$is_jwt_valid = is_jwt_valid($bearer_token);

if ($is_jwt_valid) {
  $invoiceExist = "SELECT * FROM tblreceipts WHERE invoiceid='$item->invoiceid'";
  $result = dbQuery($invoiceExist);

  if (dbNumRows($result) > 0) {
    $item->confirmPayment();

    $paymentConfirmation = array(
      "student_id" => $item->student_id,
      "full_name" => $item->full_name,
      "payment_type" => $item->payment_type,
      "invoice_no" => $item->invoice_no,
      //"receipt_no" => $item->receipt_no,
      "amount_payed" => $item->amount,
      "fs_number" => $item->fs_number,
      "payment_month" => $item->billdate
    );

    echo json_encode(array(
      'success' => true,
      'message' => "Payment was successfully made with invoice number $item->invoiceid",
      'data' => array($paymentConfirmation) // 200 ok
    ));
  }

  else {
    echo json_encode(array(
      'success' => false,
      'message' => "Invoice number $item->invoiceid NOT FOUND",
      'error_code' => 404, // not found
      'data' => null
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