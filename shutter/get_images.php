<?php
$db = mysqli_connect("localhost", "root", "", "gallery");

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM image";
$result = mysqli_query($db, $query);

if (!$result) {
    echo json_encode(['error' => 'Failed to retrieve images from the database']);
} else {
    $images = [];
    while ($data = mysqli_fetch_assoc($result)) {
        $images[] = ['filename' => $data['filename']];
    }
    echo json_encode($images);
}

mysqli_close($db);
?>
