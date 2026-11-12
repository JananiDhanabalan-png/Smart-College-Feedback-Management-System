
<?php

require "db.php";
require "auth.php";

admin_required();


// CSV download
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=feedback_report.csv");

$output = fopen("php://output", "w");


// CSV Header
fputcsv($output, [
    "Feedback ID",
    "Course",
    "Student",
    "Sentiment",
    "Comments"
]);


// Feedback data
$query = "
    SELECT
        f.id,
        c.course_name,
        u.name AS student_name,
        f.sentiment,
        f.comments,
        f.anonymous
    FROM feedback f

    JOIN courses c
    ON f.course_id = c.id

    JOIN users u
    ON f.student_id = u.id

    ORDER BY f.id DESC
";

$result = $conn->query($query);


// Write data
while ($row = $result->fetch_assoc()) {

    if ($row["anonymous"] == 1) {
        $student = "Anonymous";
    } else {
        $student = $row["student_name"];
    }

    fputcsv($output, [
        $row["id"],
        $row["course_name"],
        $student,
        $row["sentiment"],
        $row["comments"]
    ]);
}


fclose($output);

exit;

?>

