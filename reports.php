<?php

require "db.php";
require "auth.php";

admin_required();

include "header.php";


// Overall Average Rating
$average = $conn->query(
    "SELECT ROUND(AVG(rating), 2) AS average
     FROM answers"
)->fetch_assoc();


// Total Feedback
$totalFeedback = $conn->query(
    "SELECT COUNT(*) AS total
     FROM feedback"
)->fetch_assoc();


// Total Students
$totalStudents = $conn->query(
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'student'"
)->fetch_assoc();


// Total Courses
$totalCourses = $conn->query(
    "SELECT COUNT(*) AS total
     FROM courses"
)->fetch_assoc();


// Category-wise Report
$category = $conn->query(
    "SELECT
        q.category,
        ROUND(AVG(a.rating), 2) AS average,
        COUNT(*) AS total
     FROM answers a
     JOIN questions q
     ON a.question_id = q.id
     GROUP BY q.category
     ORDER BY average DESC"
);


// Sentiment Analysis
$sentiment = $conn->query(
    "SELECT
        sentiment,
        COUNT(*) AS total
     FROM feedback
     GROUP BY sentiment"
);


// Submitted Feedback
$feedbackList = $conn->query(
    "SELECT
        f.id,
        f.student_id,
        f.anonymous,
        f.comments,
        f.sentiment,
        c.course_name,
        u.name AS student_name
     FROM feedback f
     LEFT JOIN courses c
     ON f.course_id = c.id
     LEFT JOIN users u
     ON f.student_id = u.id
     ORDER BY f.id DESC"
);

?>

<style>

    .reports-page {
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
        margin-top: 8px;
        color: #777;
        font-size: 15px;
    }

    /* Statistics */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .stat-icon {
        font-size: 30px;
        margin-bottom: 10px;
    }

    .stat-card h3 {
        margin: 0;
        color: #666;
        font-size: 14px;
        font-weight: normal;
    }

    .stat-card h2 {
        margin: 10px 0 0;
        color: #667eea;
        font-size: 28px;
    }


    /* Report Cards */

    .report-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 25px;
    }

    .report-card h2 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
        font-size: 20px;
    }


    /* Table */

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    th {
        background: #f5f7fb;
        color: #444;
        padding: 14px;
        text-align: left;
        font-size: 14px;
    }

    td {
        padding: 14px;
        border-bottom: 1px solid #eee;
        color: #555;
        font-size: 14px;
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: none;
    }


    /* Rating */

    .rating {
        font-weight: bold;
        color: #667eea;
    }


    /* Sentiment */

    .sentiment-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        background: #f0f0f0;
        font-size: 13px;
        font-weight: bold;
    }


    /* Delete Button */

    .delete-btn {
        border: none;
        background: #e74c3c;
        color: white;
        padding: 8px 13px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: bold;
        cursor: pointer;
    }

    .delete-btn:hover {
        background: #c0392b;
    }


    /* Export */

    .export-section {
        text-align: right;
        margin-top: 10px;
    }

    .export-btn {
        display: inline-block;
        padding: 12px 22px;
        background: #667eea;
        color: white;
        text-decoration: none;
        border-radius: 7px;
        font-weight: bold;
    }

    .export-btn:hover {
        background: #5568d8;
    }


    /* Empty */

    .empty {
        text-align: center;
        color: #777;
        padding: 25px;
    }


    /* Mobile */

    @media (max-width: 900px) {

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

    }

    @media (max-width: 600px) {

        .reports-page {
            width: 92%;
            margin: 30px auto;
        }

        .page-header h1 {
            font-size: 27px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .report-card {
            padding: 18px;
        }

    }

</style>


<div class="reports-page">


    <!-- Page Header -->

    <div class="page-header">

        <h1>
            Analytics & Reports
        </h1>

        <p>
            Monitor feedback performance and analyze student responses.
        </p>

    </div>


    <!-- Statistics -->

    <div class="stats-grid">


        <div class="stat-card">

            <div class="stat-icon">
                ⭐
            </div>

            <h3>
                Overall Rating
            </h3>

            <h2>
                <?= $average["average"] ?? 0 ?> / 5
            </h2>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                📝
            </div>

            <h3>
                Total Feedback
            </h3>

            <h2>
                <?= $totalFeedback["total"] ?>
            </h2>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                👨‍🎓
            </div>

            <h3>
                Students
            </h3>

            <h2>
                <?= $totalStudents["total"] ?>
            </h2>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                📚
            </div>

            <h3>
                Courses
            </h3>

            <h2>
                <?= $totalCourses["total"] ?>
            </h2>

        </div>


    </div>


    <!-- Category Report -->

    <div class="report-card">

        <h2>
            📊 Category-wise Report
        </h2>

        <div class="table-container">

            <table>

                <tr>

                    <th>
                        Category
                    </th>

                    <th>
                        Average Rating
                    </th>

                    <th>
                        Responses
                    </th>

                </tr>


                <?php if ($category->num_rows > 0): ?>

                    <?php while ($r = $category->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?= h($r["category"]) ?>
                            </td>

                            <td class="rating">
                                <?= h($r["average"]) ?> / 5
                            </td>

                            <td>
                                <?= h($r["total"]) ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="3">
                            No category data available.
                        </td>

                    </tr>

                <?php endif; ?>

            </table>

        </div>

    </div>


    <!-- Sentiment Analysis -->

    <div class="report-card">

        <h2>
            😊 Sentiment Analysis
        </h2>

        <div class="table-container">

            <table>

                <tr>

                    <th>
                        Sentiment
                    </th>

                    <th>
                        Feedback Count
                    </th>

                </tr>


                <?php if ($sentiment->num_rows > 0): ?>

                    <?php while ($r = $sentiment->fetch_assoc()): ?>

                        <tr>

                            <td>

                                <span class="sentiment-badge">
                                    <?= h($r["sentiment"]) ?>
                                </span>

                            </td>

                            <td>
                                <?= h($r["total"]) ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="2">
                            No sentiment data available.
                        </td>

                    </tr>

                <?php endif; ?>

            </table>

        </div>

        <p style="color:#777; font-size:14px; margin-bottom:0;">

            Positive, Negative and Neutral comments are
            analyzed using the basic sentiment module.

        </p>

    </div>


    <!-- Submitted Feedback -->

    <div class="report-card">

        <h2>
            📝 Submitted Feedback
        </h2>


        <div class="table-container">

            <?php if ($feedbackList->num_rows > 0): ?>

                <table>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Course
                        </th>

                        <th>
                            Student
                        </th>

                        <th>
                            Sentiment
                        </th>

                        <th>
                            Comments
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>


                    <?php while ($f = $feedbackList->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?= h($f["id"]) ?>
                            </td>


                            <td>
                                <?= h($f["course_name"] ?? "-") ?>
                            </td>


                            <td>

                                <?php if ($f["anonymous"] == 1): ?>

                                    Anonymous

                                <?php else: ?>

                                    <?= h($f["student_name"] ?? "-") ?>

                                <?php endif; ?>

                            </td>


                            <td>

                                <span class="sentiment-badge">
                                    <?= h($f["sentiment"] ?? "Neutral") ?>
                                </span>

                            </td>


                            <td>

                                <?= h(
                                    $f["comments"] ?? "-"
                                ) ?>

                            </td>


                            <td>

                                <form
                                    method="POST"
                                    action="delete_feedback.php"
                                    onsubmit="return confirm('Are you sure you want to delete this feedback?');"
                                >

                                    <input
                                        type="hidden"
                                        name="feedback_id"
                                        value="<?= h($f["id"]) ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="delete-btn"
                                    >
                                        🗑️ Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </table>

            <?php else: ?>

                <div class="empty">

                    No feedback submitted yet.

                </div>

            <?php endif; ?>

        </div>

    </div>


    <!-- Export -->

    <div class="export-section">

        <a
            class="export-btn"
            href="export.php"
        >
            📥 Export Report
        </a>

    </div>


</div>


<?php

include "footer.php";

?>