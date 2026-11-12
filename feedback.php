
<?php

require "db.php";
require "auth.php";

login_required();

if ($_SESSION["user"]["role"] != "student") {
    die("Student access only.");
}

$message = "";
$error = "";


/* =========================
   ACTIVE FEEDBACK CYCLE
========================= */

$activeCycle = $conn->query("
    SELECT *
    FROM feedback_cycles
    WHERE status = 'Active'
    AND CURDATE() BETWEEN start_date AND end_date
    LIMIT 1
")->fetch_assoc();


/* =========================
   SENTIMENT FUNCTION
========================= */

function sentiment($text)
{
    $positive = [
        "good",
        "great",
        "excellent",
        "helpful",
        "clear",
        "best",
        "nice"
    ];

    $negative = [
        "bad",
        "poor",
        "worst",
        "boring",
        "slow",
        "late",
        "difficult"
    ];

    $score = 0;

    $text = strtolower($text);

    foreach ($positive as $word) {

        if (strpos($text, $word) !== false) {
            $score++;
        }
    }

    foreach ($negative as $word) {

        if (strpos($text, $word) !== false) {
            $score--;
        }
    }

    if ($score > 0) {
        return "Positive";
    }

    if ($score < 0) {
        return "Negative";
    }

    return "Neutral";
}


/* =========================
   SUBMIT FEEDBACK
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Check active cycle */

    $cycleCheck = $conn->query("
        SELECT id
        FROM feedback_cycles
        WHERE status = 'Active'
        AND CURDATE() BETWEEN start_date AND end_date
        LIMIT 1
    ");

    if ($cycleCheck->num_rows == 0) {

        $error = "Feedback is currently closed.";

    } else {

        $form = (int)($_POST["form_id"] ?? 0);

        $course = (int)($_POST["course_id"] ?? 0);

        $comments = trim(
            $_POST["comments"] ?? ""
        );

        $anonymous = isset($_POST["anonymous"]) ? 1 : 0;


        /* =========================
           BASIC VALIDATION
        ========================= */

        if ($form <= 0 || $course <= 0) {

            $error =
                "Please select feedback form and course.";

        } else {

            /* =========================
               DUPLICATE CHECK
            ========================= */

            $student = $_SESSION["user"]["id"];

            $duplicateStmt = $conn->prepare("
                SELECT id
                FROM feedback
                WHERE student_id = ?
                AND course_id = ?
                AND form_id = ?
                LIMIT 1
            ");

            $duplicateStmt->bind_param(
                "iii",
                $student,
                $course,
                $form
            );

            $duplicateStmt->execute();

            $duplicateResult =
                $duplicateStmt->get_result();


            if ($duplicateResult->num_rows > 0) {

                $error =
                    "You have already submitted feedback for this course.";

            } else {


                /* =========================
                   GET FACULTY
                ========================= */

                $courseStmt = $conn->prepare(
                    "SELECT faculty_id
                     FROM courses
                     WHERE id = ?"
                );

                $courseStmt->bind_param(
                    "i",
                    $course
                );

                $courseStmt->execute();

                $courseResult =
                    $courseStmt->get_result();

                $courseData =
                    $courseResult->fetch_assoc();


                if (!$courseData) {

                    $error =
                        "Invalid course.";

                } else {

                    $faculty =
                        $courseData["faculty_id"];


                    /* =========================
                       SENTIMENT
                    ========================= */

                    $sentiment =
                        sentiment($comments);


                    /* =========================
                       INSERT FEEDBACK
                    ========================= */

                    $stmt = $conn->prepare(

                        "INSERT INTO feedback
                        (
                            form_id,
                            student_id,
                            course_id,
                            faculty_id,
                            anonymous,
                            comments,
                            sentiment
                        )
                        VALUES
                        (?, ?, ?, ?, ?, ?, ?)"

                    );


                    $stmt->bind_param(
                        "iiiisss",
                        $form,
                        $student,
                        $course,
                        $faculty,
                        $anonymous,
                        $comments,
                        $sentiment
                    );


                    if ($stmt->execute()) {

                        $feedback_id =
                            $conn->insert_id;


                        /* =========================
                           GET QUESTIONS
                        ========================= */

                        $questionStmt =
                            $conn->prepare(

                                "SELECT id
                                 FROM questions
                                 WHERE form_id = ?"

                            );

                        $questionStmt->bind_param(
                            "i",
                            $form
                        );

                        $questionStmt->execute();

                        $questions =
                            $questionStmt->get_result();


                        /* =========================
                           INSERT ANSWERS
                        ========================= */

                        while (
                            $q =
                            $questions->fetch_assoc()
                        ) {

                            $qid =
                                $q["id"];

                            $rating =
                                (int)(
                                    $_POST["q$qid"] ?? 0
                                );


                            if (
                                $rating >= 1 &&
                                $rating <= 5
                            ) {

                                $answerStmt =
                                    $conn->prepare(

                                        "INSERT INTO answers
                                        (
                                            feedback_id,
                                            question_id,
                                            rating
                                        )
                                        VALUES
                                        (?, ?, ?)"

                                    );


                                $answerStmt->bind_param(
                                    "iii",
                                    $feedback_id,
                                    $qid,
                                    $rating
                                );

                                $answerStmt->execute();
                            }
                        }


                        $message =
                            "Feedback submitted successfully.";

                    } else {

                        $error =
                            "Unable to submit feedback.";

                    }
                }
            }
        }
    }
}

?>


<?php include "header.php"; ?>


<style>

/* =========================
   FEEDBACK PAGE
========================= */

.feedback-container {
    width: 90%;
    max-width: 950px;
    margin: 40px auto;
}


/* =========================
   PAGE TITLE
========================= */

.page-title {
    margin-bottom: 25px;
}

.page-title h1 {
    margin: 0;
    font-size: 32px;
    color: #222;
}

.page-title p {
    margin-top: 8px;
    color: #777;
    font-size: 15px;
}


/* =========================
   MESSAGE
========================= */

.success-box {
    background: #e8f8ed;
    color: #16852d;
    padding: 13px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
}

.error-box {
    background: #ffe8e8;
    color: #d00000;
    padding: 13px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
}


/* =========================
   MAIN CARD
========================= */

.feedback-card {
    background: white;
    padding: 30px;
    border-radius: 12px;

    box-shadow:
        0 4px 15px rgba(0, 0, 0, 0.08);
}


/* =========================
   FORM
========================= */

.form-group {
    margin-bottom: 22px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #444;
}

.input {
    width: 100%;
    padding: 12px;

    border: 1px solid #ddd;
    border-radius: 7px;

    font-size: 15px;

    outline: none;

    background: white;
}

.input:focus {
    border-color: #667eea;

    box-shadow:
        0 0 5px rgba(102, 126, 234, 0.2);
}

textarea.input {
    resize: vertical;
    min-height: 110px;
}


/* =========================
   QUESTIONS
========================= */

.questions-title {
    margin-top: 30px;
    margin-bottom: 18px;

    color: #333;
    font-size: 20px;
}

.question-card {
    background: #f8f9fc;

    border: 1px solid #e5e7ef;

    padding: 20px;

    border-radius: 10px;

    margin-bottom: 15px;
}

.question-text {
    font-weight: bold;

    color: #333;

    line-height: 1.5;
}

.category {
    margin-top: 8px;

    color: #777;

    font-size: 14px;
}


/* =========================
   RATING
========================= */

.rating {
    display: flex;

    gap: 12px;

    margin-top: 18px;

    flex-wrap: wrap;
}

.rating label {
    display: flex;

    align-items: center;

    justify-content: center;

    gap: 5px;

    min-width: 48px;

    padding: 9px 12px;

    background: white;

    border: 1px solid #ddd;

    border-radius: 7px;

    cursor: pointer;

    font-weight: bold;

    color: #555;
}

.rating label:hover {
    border-color: #667eea;
    color: #667eea;
}

.rating input {
    cursor: pointer;
}


/* =========================
   ANONYMOUS
========================= */

.anonymous {
    display: flex;

    align-items: center;

    gap: 8px;

    margin-top: 20px;

    color: #555;

    cursor: pointer;
}

.anonymous input {
    width: 16px;
    height: 16px;

    cursor: pointer;
}


/* =========================
   SUBMIT BUTTON
========================= */

.submit-btn {
    width: 100%;

    padding: 13px;

    margin-top: 25px;

    background: #667eea;

    color: white;

    border: none;

    border-radius: 8px;

    font-size: 16px;

    font-weight: bold;

    cursor: pointer;
}

.submit-btn:hover {
    background: #5568d8;
}


/* =========================
   CLOSED CARD
========================= */

.closed-card {
    text-align: center;
    padding: 45px 30px;
}

.closed-icon {
    font-size: 45px;
    margin-bottom: 10px;
}

.closed-card h2 {
    margin: 0 0 10px;
    color: #d00000;
}

.closed-card p {
    margin: 0;
    color: #777;
    font-size: 15px;
}


/* =========================
   MOBILE
========================= */

@media (max-width: 600px) {

    .feedback-container {
        width: 92%;
        margin: 25px auto;
    }

    .feedback-card {
        padding: 20px;
    }

    .page-title h1 {
        font-size: 27px;
    }

    .rating {
        gap: 8px;
    }

    .rating label {
        min-width: 42px;
        padding: 8px;
    }

}

</style>


<div class="feedback-container">


    <!-- PAGE TITLE -->

    <div class="page-title">

        <h1>
            Give Feedback
        </h1>

        <p>
            Share your valuable feedback about your course and faculty.
        </p>

    </div>


    <!-- SUCCESS MESSAGE -->

    <?php if ($message != ""): ?>

        <div class="success-box">
            <?= h($message) ?>
        </div>

    <?php endif; ?>


    <!-- ERROR MESSAGE -->

    <?php if ($error != ""): ?>

        <div class="error-box">
            <?= h($error) ?>
        </div>

    <?php endif; ?>


    <?php if ($activeCycle): ?>


        <!-- FEEDBACK FORM -->

        <div class="feedback-card">

            <form method="POST">


                <!-- FEEDBACK FORM -->

                <div class="form-group">

                    <label>
                        Feedback Form
                    </label>

                    <select
                        class="input"
                        name="form_id"
                        required
                    >

                        <option value="">
                            Select Feedback Form
                        </option>


                        <?php

                        $result = $conn->query(

                            "SELECT *
                             FROM feedback_forms
                             WHERE active = 1"

                        );


                        while (
                            $f =
                            $result->fetch_assoc()
                        ):

                        ?>

                            <option
                                value="<?= $f["id"] ?>"
                            >

                                <?= h(
                                    $f["title"]
                                ) ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <!-- COURSE -->

                <div class="form-group">

                    <label>
                        Course
                    </label>

                    <select
                        class="input"
                        name="course_id"
                        required
                    >

                        <option value="">
                            Select Course
                        </option>


                        <?php

                        $result = $conn->query(

                            "SELECT
                                c.*,
                                u.name AS faculty_name

                             FROM courses c

                             LEFT JOIN users u
                             ON c.faculty_id = u.id"

                        );


                        while (
                            $c =
                            $result->fetch_assoc()
                        ):

                        ?>

                            <option
                                value="<?= $c["id"] ?>"
                            >

                                <?= h(
                                    $c["course_name"]
                                    . " - "
                                    . $c["faculty_name"]
                                ) ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <!-- QUESTIONS -->

                <h3 class="questions-title">
                    Rating Questions
                </h3>


                <?php

                $result = $conn->query(

                    "SELECT *
                     FROM questions
                     WHERE form_id = 1"

                );


                while (
                    $q =
                    $result->fetch_assoc()
                ):

                ?>

                    <div class="question-card">


                        <div class="question-text">

                            <?= h(
                                $q["question"]
                            ) ?>

                        </div>


                        <div class="category">

                            Category:
                            <?= h(
                                $q["category"]
                            ) ?>

                        </div>


                        <div class="rating">


                            <?php for (
                                $i = 1;
                                $i <= 5;
                                $i++
                            ): ?>

                                <label>

                                    <input
                                        type="radio"
                                        name="q<?= $q["id"] ?>"
                                        value="<?= $i ?>"
                                        required
                                    >

                                    <?= $i ?>

                                </label>

                            <?php endfor; ?>


                        </div>

                    </div>

                <?php endwhile; ?>


                <!-- COMMENTS -->

                <div class="form-group">

                    <label>
                        Comments
                    </label>

                    <textarea
                        class="input"
                        name="comments"
                        rows="5"
                        placeholder="Write your feedback..."
                    ></textarea>

                </div>


                <!-- ANONYMOUS -->

                <label class="anonymous">

                    <input
                        type="checkbox"
                        name="anonymous"
                    >

                    Submit Anonymously

                </label>


                <!-- SUBMIT -->

                <button
                    type="submit"
                    class="submit-btn"
                >

                    Submit Feedback

                </button>


            </form>

        </div>


    <?php else: ?>


        <!-- CLOSED MESSAGE -->

        <div class="feedback-card closed-card">

            <div class="closed-icon">
                🔒
            </div>

            <h2>
                Feedback is Currently Closed
            </h2>

            <p>
                There is no active feedback cycle at the moment.
                Please check again later.
            </p>

        </div>


    <?php endif; ?>


</div>


<?php include "footer.php"; ?>

