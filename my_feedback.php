
<?php

require "db.php";
require "auth.php";

login_required();

if ($_SESSION["user"]["role"] != "student") {
    die("Access denied.");
}

$student_id = $_SESSION["user"]["id"];

$query = "
    SELECT 
        f.id,
        f.comments,
        f.sentiment,
        f.anonymous,
        c.course_name,
        ff.title
    FROM feedback f
    JOIN courses c ON f.course_id = c.id
    JOIN feedback_forms ff ON f.form_id = ff.id
    WHERE f.student_id = ?
    ORDER BY f.id DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();

$result = $stmt->get_result();

include "header.php";

?>

<style>

    .feedback-page {
        width: 90%;
        max-width: 1000px;
        margin: 40px auto;
    }

    .page-title {
        margin-bottom: 30px;
    }

    .page-title h1 {
        margin: 0;
        color: #222;
    }

    .page-title p {
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
        margin-top: 0;
        color: #333;
    }

    .info {
        margin: 8px 0;
        color: #555;
    }

    .label {
        font-weight: bold;
        color: #333;
    }

    .sentiment {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        background: #eee;
        font-weight: bold;
        margin-top: 10px;
    }

    .comments {
        margin-top: 18px;
        padding: 15px;
        background: #f5f7fb;
        border-radius: 8px;
        line-height: 1.6;
    }

    .empty {
        background: white;
        padding: 40px;
        text-align: center;
        border-radius: 12px;
        color: #777;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

</style>


<div class="feedback-page">

    <div class="page-title">

        <h1>My Feedback</h1>

        <p>
            View the feedback you have submitted.
        </p>

    </div>

    <?php if ($result->num_rows == 0): ?>

        <div class="empty">

            <h2>No Feedback Found</h2>

            <p>
                You have not submitted any feedback yet.
            </p>

        </div>

    <?php else: ?>

        <?php while ($row = $result->fetch_assoc()): ?>

            <div class="feedback-card">

                <h2>
                    <?= h($row["course_name"]) ?>
                </h2>

                <p class="info">
                    <span class="label">Feedback Form:</span>
                    <?= h($row["title"]) ?>
                </p>

                <p class="info">

                    <span class="label">Submitted As:</span>

                    <?php if ($row["anonymous"] == 1): ?>

                        Anonymous

                    <?php else: ?>

                        Your Name

                    <?php endif; ?>

                </p>

                <p class="info">

                    <span class="label">Sentiment:</span>

                    <span class="sentiment">
                        <?= h($row["sentiment"]) ?>
                    </span>

                </p>

                <div class="comments">

                    <span class="label">Comments:</span>

                    <br>

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
