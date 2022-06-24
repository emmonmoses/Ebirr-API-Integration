<?php
/** * Author : Emmon Moses */

require_once './connection/config.php';
require_once './connection/database.php';
require_once './connection/pdo.php';
require_once './class/payment.php';
require_once 'jwt_utils.php';

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new Database();
$db = $database->getConnection();
$item = new Payment($db);

$request = json_decode(file_get_contents("php://input"));
$item->invoiceid = $request->invoice_number;
$item->fs_number = $request->fs_number;

$sql_INV = "SELECT * FROM tblreceipts WHERE invoiceid='$item->invoiceid'";
$result_INV = $db->prepare($sql_INV);
$result_INV->execute();
$invoiceExist = $result_INV->fetchColumn();

$sql_FS = "SELECT * FROM tblreceipts WHERE fs_number='$item->fs_number'";
$result_FS = $db->prepare($sql_FS);
$result_FS->execute();
$fsNumberExist = $result_FS->fetchColumn();

$getReceipt = mysqli_query($conn, "SELECT CONCAT_WS('', 'RC', Year(NOW()), MAX(SUBSTRING(ReceiptId, 7, 9800) + 1)) ReceiptId FROM getreceiptno;");
@$receiptId = "";

foreach ($getReceipt as $row):
  $receiptId = $row['ReceiptId'];
endforeach;

$bearer_token = get_bearer_token();
$is_jwt_valid = is_jwt_valid($bearer_token);

if ($is_jwt_valid) {

  // if ($invoiceExist > 0) {
  //   echo json_encode(array(
  //     'success' => false,
  //     'message' => "Payment with invoice number $item->invoiceid ALREADY EXISTS!",
  //     'error_code' => 403,
  //     'data' => null
  //   ));
  // }
  if ($invoiceExist < 0) {
    echo json_encode(array(
      'success' => false,
      'message' => "Invoice number $item->invoiceid DOES NOT EXIST",
      'error_code' => 404,
      'data' => null
    ));
  }
  else if ($fsNumberExist > 0) {
    echo json_encode(array(
      'success' => false,
      'message' => "Payment with fs number $item->fs_number ALREADY EXISTS!",
      'error_code' => 403,
      'data' => null
    ));
  }
  else {
    $response;
    $sql = "SELECT * FROM tblbills WHERE invoiceid = '$item->invoiceid'";
    $result = dbQuery($sql);

    if (dbNumRows($result) > 0) {
      while ($row = dbFetchAssoc($result)) {
        $response['studentid'] = $row['student_id'];
        $response['full_name'] = $row['full_name'];
        $response['receiptnumber'] = $receiptId;
        $response['invoiceid'] = $row['invoiceid'];
        $response['fs_number'] = $item->fs_number;
        $response['departmentid'] = $row['departmentid'];
        $response['payment_type'] = $row['payment_type'];
        $response['amount'] = $row['amount'];
        $response['discount_percent'] = $row['discount_percent'];
        $response['billdate'] = date('Y-m-d');
        $response['status'] = 'paid';
      }

      $columns = implode(",\n", array_keys($response));
      $values = implode("',\n'", $response);

      $sqlQuery = "INSERT INTO tblreceipts ($columns) VALUES ('$values')";

      if ($conn->query($sqlQuery) === TRUE) {

        mysqli_query($conn, "DELETE FROM getreceiptno");

        $updateReceiptTable = mysqli_query($conn, "INSERT into getreceiptno (ReceiptId) 
            values('$receiptId')") or die(mysqli_error($conn));

        $sql = "SELECT * FROM tblreceipts WHERE invoiceid = '$item->invoiceid'";
        $result = dbQuery($sql);

        while ($row = dbFetchAssoc($result)) {
          $paymentConfirmation = [
            'student_id' => $row['studentid'],
            'full_name' => $row['full_name'],
            'payment_type' => $row['payment_type'],
            'invoice_no' => $row['invoiceid'],
            'amount_payed' => $row['amount'],
            'fs_number' => $row['fs_number'],
            'payment_month' => $row['billdate'],
          ];
        }
        echo json_encode(array(
          'success' => true,
          'message' => "Payment was successfully made with invoice number $item->invoiceid",
          'data' => array($paymentConfirmation) // 200 ok
        ));
      }

    }
    else {
      echo json_encode(array(
        'success' => false,
        'message' => "Invoice number $item->invoiceid not found",
        'data' => null // not found
      ));
    }
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