
<?php

require "db.php";
require "auth.php";

login_required();

if ($_SESSION["user"]["role"] != "faculty") {
    die("Access denied.");
}

$faculty_id = $_SESSION["user"]["id"];

$stmt = $conn->prepare("
    SELECT
        f.id,
        f.comments,
        f.sentiment,
        f.anonymous,
        c.course_name,
        u.name AS student_name
    FROM feedback f
    JOIN courses c
        ON f.course_id = c.id
    JOIN users u
        ON f.student_id = u.id
    WHERE f.faculty_id = ?
    ORDER BY f.id DESC
");

$stmt->bind_param("i", $faculty_id);
$stmt->execute();

$result = $stmt->get_result();

include "header.php";

?>

<style>

    .faculty-page {
        width: 90%;
        max-width: 1100px;
        margin: 45px auto;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 32px;
        color: #222;
    }

    .page-header p {
        color: #777;
        margin-top: 8px;
    }

    .feedback-card {
        background: white;
        padding: 25px;
        margin-bottom: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .feedback-card h2 {
        margin: 0 0 15px;
        color: #333;
        font-size: 20px;
    }

    .info {
        margin: 8px 0;
        color: #555;
    }

    .label {
        font-weight: bold;
        color: #333;
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        background: #eef0ff;
        color: #667eea;
        font-size: 13px;
        font-weight: bold;
    }

    .comments {
        margin-top: 18px;
        padding: 15px;
        background: #f5f7fb;
        border-radius: 8px;
        color: #555;
        line-height: 1.6;
    }

    .empty {
        background: white;
        padding: 45px;
        text-align: center;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        color: #777;
    }

    @media (max-width: 600px) {

        .faculty-page {
            width: 92%;
            margin: 30px auto;
        }

        .page-header h1 {
            font-size: 27px;
        }

        .feedback-card {
            padding: 18px;
        }

    }

</style>


<div class="faculty-page">
    

    <div class="page-header">

        <h1>
            Student Feedback
        </h1>

        <p>
            View feedback submitted for your courses.
        </p>

    </div>


    <?php if ($result->num_rows == 0): ?>

        <div class="empty">

            <h2>
                No Feedback Available
            </h2>

            <p>
                No students have submitted feedback for your courses yet.
            </p>

        </div>

    <?php else: ?>

        <?php while ($row = $result->fetch_assoc()): ?>

            <div class="feedback-card">

                <h2>
                    <?= h($row["course_name"]) ?>
                </h2>

                <p class="info">

                    <span class="label">
                        Student:
                    </span>

                    <?php

                    if ($row["anonymous"] == 1) {
                        echo "Anonymous";
                    } else {
                        echo h($row["student_name"]);
                    }

                    ?>

                </p>


                <p class="info">

                    <span class="label">
                        Sentiment:
                    </span>

                    <span class="badge">
                        <?= h($row["sentiment"]) ?>
                    </span>

                </p>


                <div class="comments">

                    <span class="label">
                        Comments:
                    </span>

                    <br><br>

                    <?php

                    if ($row["comments"] != "") {

                        echo nl2br(h($row["comments"]));

                    } else {

                        echo "No comments provided.";

                    }

                    ?>

                </div>

            </div>

        <?php endwhile; ?>

    <?php endif; ?>

</div>


<?php

include "footer.php";

?>

