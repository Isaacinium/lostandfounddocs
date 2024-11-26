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

// Handle search query
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $searchType = $_GET['searchType'];
    $searchDocNumber = $_GET['searchDocNumber'];
    $searchName = $_GET['searchName'];

    $sql = "SELECT * FROM documents WHERE doc_type LIKE ? AND doc_number LIKE ? AND owner_name LIKE ?";
    $stmt = $conn->prepare($sql);

    $searchType = '%' . $searchType . '%';
    $searchDocNumber = '%' . $searchDocNumber . '%';
    $searchName = '%' . $searchName . '%';

    $stmt->bind_param("sss", $searchType, $searchDocNumber, $searchName);
    $stmt->execute();
    $result = $stmt->get_result();

    $documents = [];
    while ($row = $result->fetch_assoc()) {
        $documents[] = $row;
    }

    echo json_encode($documents);

    $stmt->close();
}

$conn->close();
?>
