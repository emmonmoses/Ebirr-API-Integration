<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json');

require_once("../../db_config/config.php");

$sql = "SELECT studentid id, CONCAT_WS(' ', firstname, middlename, lastname) name, status isActive, md.name branch, up.Name customerGroup, up.Id orderNo, CONCAT_WS(' ', firstname, middlename, lastname) contactName, sphs.gender, 'Self' relationship, 'Gibson College' label, sphs.emailaddress email, 'Mobile' phoneLabel, '+251' dialingCode, sphs.phonenumber2 phone, sphs.emailaddress primaryEmail, sphs.phonenumber primaryPhone FROM students_preparatory_high_school_complete sphs
LEFT JOIN modality md on md.id = sphs.modalityid
LEFT JOIN undergraduateprogram up on up.Id = sphs.undergraduate_programid
where sphs.status = 1 and sphs.IsApproved = 1

UNION
SELECT studentid id, CONCAT_WS(' ', firstname, middlename, lastname) name, status isActive, md.name branch, up.Name customerGroup, up.Id orderNo, CONCAT_WS(' ', firstname, middlename, lastname) contactName, sphs.gender, 'Self' relationship, 'Gibson College' label, sphs.emailaddress email, 'Mobile' phoneLabel, '+251' dialingCode, sphs.phonenumber2 phone, sphs.emailaddress primaryEmail, sphs.phonenumber primaryPhone FROM student_tvet_complete sphs
LEFT JOIN modality md on md.id = sphs.modalityid
LEFT JOIN undergraduateprogram up on up.Id = sphs.undergraduate_programid
where sphs.status = 1 and sphs.IsApproved = 1

UNION
SELECT studentid id, CONCAT_WS(' ', firstname, middlename, lastname) name, status isActive, md.name branch, up.Name customerGroup, up.Id orderNo, CONCAT_WS(' ', firstname, middlename, lastname) contactName, sphs.gender, 'Self' relationship, 'Gibson College' label, sphs.emailaddress email, 'Mobile' phoneLabel, '+251' dialingCode, sphs.phonenumber2 phone, sphs.emailaddress primaryEmail, sphs.phonenumber primaryPhone FROM students_international_admission sphs
LEFT JOIN modality md on md.id = sphs.modalityid
LEFT JOIN undergraduateprogram up on up.Id = sphs.undergraduate_programid
where sphs.status = 1 and sphs.IsApproved = 1

UNION
SELECT studentid id, CONCAT_WS(' ', firstname, middlename, lastname) name, status isActive, md.name branch, up.Name customerGroup, up.Id orderNo, CONCAT_WS(' ', firstname, middlename, lastname) contactName, sphs.gender, 'Self' relationship, 'Gibson College' label, sphs.emailaddress email, 'Mobile' phoneLabel, '+251' dialingCode, sphs.phonenumber2 phone, sphs.emailaddress primaryEmail, sphs.phonenumber primaryPhone FROM students_bachelor_degree_holders sphs
LEFT JOIN modality md on md.id = sphs.modalityid
LEFT JOIN undergraduateprogram up on up.Id = sphs.undergraduate_programid
where sphs.status = 1 and sphs.IsApproved = 1";

$result = mysqli_query($conn, $sql);
$rows = mysqli_num_rows($result);

$pageSize = 10;
$pages = ceil($rows / $pageSize);

if (!isset($_GET['page']) || !isset($_GET['pageSize'])) {
  $page = 1;
  $pageSize;
}
else {
  $page = $_GET['page'];
  $pageSize = $_GET['pageSize'];
}

// LIMIT starting number for the records on the current page
$this_page_first_result = ($page - 1) * $pageSize;

// retrieve selected results from the db
$sql = "SELECT studentid id, CONCAT_WS(' ', firstname, middlename, lastname) name, status isActive, md.name branch, up.Name customerGroup, up.Id orderNo, CONCAT_WS(' ', firstname, middlename, lastname) contactName, sphs.gender, 'Self' relationship, 'Gibson College' label, sphs.emailaddress email, 'Mobile' phoneLabel, '+251' dialingCode, sphs.phonenumber2 phone, sphs.emailaddress primaryEmail, sphs.phonenumber primaryPhone FROM students_preparatory_high_school_complete sphs
LEFT JOIN modality md on md.id = sphs.modalityid
LEFT JOIN undergraduateprogram up on up.Id = sphs.undergraduate_programid
where sphs.status = 1 and sphs.IsApproved = 1

UNION
SELECT studentid id, CONCAT_WS(' ', firstname, middlename, lastname) name, status isActive, md.name branch, up.Name customerGroup, up.Id orderNo, CONCAT_WS(' ', firstname, middlename, lastname) contactName, sphs.gender, 'Self' relationship, 'Gibson College' label, sphs.emailaddress email, 'Mobile' phoneLabel, '+251' dialingCode, sphs.phonenumber2 phone, sphs.emailaddress primaryEmail, sphs.phonenumber primaryPhone FROM student_tvet_complete sphs
LEFT JOIN modality md on md.id = sphs.modalityid
LEFT JOIN undergraduateprogram up on up.Id = sphs.undergraduate_programid
where sphs.status = 1 and sphs.IsApproved = 1

UNION
SELECT studentid id, CONCAT_WS(' ', firstname, middlename, lastname) name, status isActive, md.name branch, up.Name customerGroup, up.Id orderNo, CONCAT_WS(' ', firstname, middlename, lastname) contactName, sphs.gender, 'Self' relationship, 'Gibson College' label, sphs.emailaddress email, 'Mobile' phoneLabel, '+251' dialingCode, sphs.phonenumber2 phone, sphs.emailaddress primaryEmail, sphs.phonenumber primaryPhone FROM students_international_admission sphs
LEFT JOIN modality md on md.id = sphs.modalityid
LEFT JOIN undergraduateprogram up on up.Id = sphs.undergraduate_programid
where sphs.status = 1 and sphs.IsApproved = 1

UNION
SELECT studentid id, CONCAT_WS(' ', firstname, middlename, lastname) name, status isActive, md.name branch, up.Name customerGroup, up.Id orderNo, CONCAT_WS(' ', firstname, middlename, lastname) contactName, sphs.gender, 'Self' relationship, 'Gibson College' label, sphs.emailaddress email, 'Mobile' phoneLabel, '+251' dialingCode, sphs.phonenumber2 phone, sphs.emailaddress primaryEmail, sphs.phonenumber primaryPhone FROM students_bachelor_degree_holders sphs
LEFT JOIN modality md on md.id = sphs.modalityid
LEFT JOIN undergraduateprogram up on up.Id = sphs.undergraduate_programid
where sphs.status = 1 and sphs.IsApproved = 1 LIMIT " . $this_page_first_result . "," . $pageSize;

$result = mysqli_query($conn, $sql);
$i = 0;
$response = array();
$status_code = "404";
$error_message = "Data not found";

if (mysqli_num_rows($result) > 0) {

  while ($row = mysqli_fetch_array($result)) {

    if ($row['isActive'] == "1") {
      $isActive = true;
    }
    else {
      $isActive = false;
    }

    $response['page'] = $page;
    $response['pages'] = $pages;
    $response['pageSize'] = $pageSize;
    $response['rows'] = $rows;
    $response['data'][$i]['id'] = $row['id'];
    $response['data'][$i]['name'] = $row['name'];
    $response['data'][$i]['isActive'] = $isActive;
    $response['data'][$i]['branch']['id'] = $row['branch'];
    $response['data'][$i]['branch']['name'] = $row['branch'];
    $response['data'][$i]['customerGroup']['id'] = $row['customerGroup'];
    $response['data'][$i]['customerGroup']['name'] = $row['customerGroup'];
    $response['data'][$i]['customerGroup']['orderNo'] = $row['orderNo'];
    $response['data'][$i]['contacts'][0]['id'] = $row['id'];
    $response['data'][$i]['contacts'][0]['name'] = $row['contactName'];
    $response['data'][$i]['contacts'][0]['gender'] = $row['gender'];
    $response['data'][$i]['contacts'][0]['relationship'] = $row['relationship'];
    $response['data'][$i]['contacts'][0]['emails'][0]['label'] = $row['label'];
    $response['data'][$i]['contacts'][0]['emails'][0]['email'] = $row['email'];
    $response['data'][$i]['contacts'][0]['phoneNumbers'][0]['label'] = $row['phoneLabel'];
    $response['data'][$i]['contacts'][0]['phoneNumbers'][0]['dialingCode'] = $row['dialingCode'];
    $response['data'][$i]['contacts'][0]['phoneNumbers'][0]['phone'] = $row['phone'];
    $response['data'][$i]['contacts'][0]['primaryEmail'] = $row['primaryEmail'];
    $response['data'][$i]['contacts'][0]['primaryPhone'] = $row['primaryPhone'];

    $i++;
  }

  echo json_encode($response, JSON_PRETTY_PRINT);
}
else {

  echo json_encode(array('status' => $status_code, 'message' => $error_message));
}
?>