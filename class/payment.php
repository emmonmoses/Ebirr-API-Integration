<?php
class Payment
{
    private $conn_db;
    private $db_table = "tblreceipts";
    public $invoiceid;
    public $fs_number;

    public function __construct($db)
    {
        $this->conn_db = $db;
    }

    public function makePayment()
    {
        $sqlQuery = "INSERT INTO " . $this->db_table . "
        SET 
        invoiceid = :invoiceid, 
        fs_number = :fs_number,      
        WHERE 
        invoiceid = :invoiceid";

        $stmt = $this->conn_db->prepare($sqlQuery);

        $this->invoiceid = $this->invoiceid;
        $this->fs_number = $this->fs_number;

        $stmt->bindParam(":invoiceid", $this->invoiceid);
        $stmt->bindParam(":fs_number", $this->fs_number);


        if ($stmt->execute()) {
            return true;
        }
        return false;

    }


}

?>