<?php
class Confirmation
{
    private $conn_db;
    private $db_table = "tblreceipts";
    public $studentid;
    public $full_name;
    public $payment_type;
    public $invoiceid;
    public $receipt_no;
    public $fs_number;
    public $amount;
    public $discount_percent;
    public $statuss;
    public $billdate;

    public function __construct($db)
    {
        $this->conn_db = $db;
    }
    public function confirmPayment()
    {
        $sqlQuery = "SELECT * FROM " . $this->db_table . " WHERE invoiceid = ? LIMIT 0,1";
        $stmt = $this->conn_db->prepare($sqlQuery);
        $stmt->bindParam(1, $this->invoiceid);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->student_id = $dataRow['studentid'];
        $this->full_name = $dataRow['full_name'];
        $this->payment_type = $dataRow['payment_type'];
        $this->invoice_no = $dataRow['invoiceid'];
        $this->amount = $dataRow['amount'];
        $this->receipt_no = $dataRow['receiptnumber'];
        $this->fs_number = $dataRow['fs_number'];
        $this->billdate = $dataRow['billdate'];

    }



}

?>