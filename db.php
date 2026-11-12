
<?php

$conn = mysqli_connect(
    "127.0.0.1",
    "root",
    "",
    "college_feedback",
    3307
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>