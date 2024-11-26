<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lostandfound";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $docType = $_POST['docType'];
    $docNumber = $_POST['docNumber'];
    $ownerName = $_POST['name'];
    $dateFound = $_POST['dateFound'];
    $location = $_POST['location'];
    $contactEmail = $_POST['contact'];
    $contactPhone = $_POST['phone'];

    $today = new DateTime();

    $submittedDate = DateTime::createFromFormat('Y-m-d', $dateFound);

    if (!$submittedDate) {
        echo "Invalid date format!";
        exit;
    }

    $errorMessage =  "";

    if ($submittedDate > $today) {
        $errorMessage = "The date found cannot be in the future!";
        exit;
    }

    $sql = "INSERT INTO documents (doc_type, doc_number, owner_name, date_found, location_found, contact_email, contact_phone)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $docType, $docNumber, $ownerName, $dateFound, $location, $contactEmail, $contactPhone);

    if ($stmt->execute()) {
        echo "Document successfully uploaded.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

