<?php

require "db.php";
require "auth.php";

admin_required();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: reports.php");
    exit;
}

$feedback_id = intval($_POST["feedback_id"]);

if ($feedback_id <= 0) {
    header("Location: reports.php");
    exit;
}

$conn->begin_transaction();

try {

    // Delete answers first
    $stmt1 = $conn->prepare("
        DELETE FROM answers
        WHERE feedback_id = ?
    ");

    $stmt1->bind_param("i", $feedback_id);
    $stmt1->execute();
    $stmt1->close();


    // Delete feedback
    $stmt2 = $conn->prepare("
        DELETE FROM feedback
        WHERE id = ?
    ");

    $stmt2->bind_param("i", $feedback_id);
    $stmt2->execute();
    $stmt2->close();


    $conn->commit();

} catch (Exception $e) {

    $conn->rollback();
}

header("Location: reports.php");
exit;

?>