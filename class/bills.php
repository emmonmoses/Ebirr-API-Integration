<?php
class OutstandingBill
{
    // Connection
    private $conn_db;
    private $db_table = "tblbills";
    public $studentid;
    public $full_name;
    public $payment_type;
    public $amount;
    public $discount_percent;
    public $status;
    public $invoice_number;
    public $due_year_month;
    public $department;

    public function __construct($db)
    {
        $this->conn_db = $db;
    }

    public function getOutstandingBill()
    {
        $sqlQuery = "SELECT * FROM " . $this->db_table . "WHERE studentid = ? LIMIT 0,1";
        $stmt = $this->conn_db->prepare($sqlQuery);
        $stmt->bindParam(1, $this->studentid);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->student_id = $dataRow['studentid'];
        $this->full_name = $dataRow['full_name'];
        $this->payment_type = $dataRow['payment_type'];
        $this->amount = $dataRow['amount'];
        $this->discount_percent = $dataRow['discount_percent'];
        $this->status = $dataRow['status'];
        $this->invoice_number = $dataRow['invoiceid'];
        $this->due_year_month = $dataRow['billdate'];
        $this->department = $dataRow['departmentid'];


    }


}

?>