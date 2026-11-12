<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "db.php";


/* ==============================
   ADD NEW FORM
================================ */

if (isset($_POST['create_form'])) {

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title != "") {

        $stmt = $conn->prepare(
            "INSERT INTO feedback_forms (title, description, active)
             VALUES (?, ?, 1)"
        );

        $stmt->bind_param("ss", $title, $description);
        $stmt->execute();

        $form_id = $conn->insert_id;

        header("Location: form_builder.php?form_id=" . $form_id);
        exit();
    }
}


/* ==============================
   ADD QUESTION
================================ */

if (isset($_POST['add_question'])) {

    $form_id = intval($_POST['form_id'] ?? 0);
    $question = trim($_POST['question'] ?? '');
    $category = trim($_POST['category'] ?? '');

    if ($question != "" && $form_id > 0) {

        $stmt = $conn->prepare(
            "INSERT INTO questions (form_id, question, category)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "iss",
            $form_id,
            $question,
            $category
        );

        $stmt->execute();
    }

    header("Location: form_builder.php?form_id=" . $form_id);
    exit();
}


/* ==============================
   DELETE QUESTION
================================ */

if (isset($_GET['delete_question'])) {

    $question_id = intval($_GET['delete_question']);
    $form_id = intval($_GET['form_id'] ?? 0);

    $stmt = $conn->prepare(
        "DELETE FROM questions WHERE id = ?"
    );

    $stmt->bind_param("i", $question_id);
    $stmt->execute();

    header("Location: form_builder.php?form_id=" . $form_id);
    exit();
}


/* ==============================
   ACTIVATE / DEACTIVATE FORM
================================ */

if (isset($_GET['toggle_form'])) {

    $form_id = intval($_GET['toggle_form']);

    $stmt = $conn->prepare(
        "UPDATE feedback_forms
         SET active = IF(active = 1, 0, 1)
         WHERE id = ?"
    );

    $stmt->bind_param("i", $form_id);
    $stmt->execute();

    header("Location: form_builder.php?form_id=" . $form_id);
    exit();
}


/* ==============================
   GET ALL FORMS
================================ */

$forms = $conn->query(
    "SELECT * FROM feedback_forms ORDER BY id DESC"
);


/* ==============================
   SELECT FORM
================================ */

$selected_form = null;
$questions = null;

if (isset($_GET['form_id'])) {

    $form_id = intval($_GET['form_id']);

    $stmt = $conn->prepare(
        "SELECT * FROM feedback_forms WHERE id = ?"
    );

    $stmt->bind_param("i", $form_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $selected_form = $result->fetch_assoc();


    if ($selected_form) {

        $stmt2 = $conn->prepare(
            "SELECT * FROM questions
             WHERE form_id = ?
             ORDER BY id ASC"
        );

        $stmt2->bind_param("i", $form_id);
        $stmt2->execute();

        $questions = $stmt2->get_result();
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Feedback Form Builder</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: #f4f6f9;

        }


        .container {

            width: 90%;

            max-width: 1100px;

            margin: 30px auto;

        }


        h1 {

            color: #222;

            margin-bottom: 25px;

        }


        .card {

            background: white;

            padding: 25px;

            border-radius: 10px;

            margin-bottom: 25px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.08);

        }


        .card h2 {

            margin-top: 0;

            color: #333;

        }


        input,
        textarea,
        select {

            width: 100%;

            padding: 11px;

            margin-top: 7px;

            margin-bottom: 15px;

            border: 1px solid #ccc;

            border-radius: 6px;

            font-size: 15px;

        }


        textarea {

            height: 90px;

            resize: vertical;

        }


        button {

            border: none;

            padding: 11px 18px;

            border-radius: 6px;

            cursor: pointer;

            font-size: 14px;

        }


        .btn-primary {

            background: #2563eb;

            color: white;

        }


        .btn-primary:hover {

            background: #1d4ed8;

        }


        .btn-danger {

            background: #dc2626;

            color: white;

        }


        .btn-danger:hover {

            background: #b91c1c;

        }


        .btn-secondary {

            background: #555;

            color: white;

        }


        .btn-secondary:hover {

            background: #333;

        }


        .forms {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 15px;

        }


        .form-box {

            border: 1px solid #ddd;

            padding: 15px;

            border-radius: 8px;

        }


        .form-box h3 {

            margin-top: 0;

        }


        .status-active {

            color: green;

            font-weight: bold;

        }


        .status-inactive {

            color: red;

            font-weight: bold;

        }


        .question {

            border: 1px solid #ddd;

            padding: 15px;

            margin-bottom: 12px;

            border-radius: 7px;

            background: #fafafa;

        }


        .question-number {

            font-weight: bold;

            color: #2563eb;

        }


        .category {

            display: inline-block;

            background: #e5e7eb;

            padding: 4px 8px;

            border-radius: 4px;

            font-size: 12px;

            margin-top: 8px;

        }


        .actions {

            margin-top: 12px;

            display: flex;

            gap: 8px;

            flex-wrap: wrap;

        }


        .actions a {

            text-decoration: none;

        }


        @media(max-width: 700px) {

            .forms {

                grid-template-columns: 1fr;

            }


            .container {

                width: 95%;

            }

        }

    </style>

</head>


<body>


<!-- ==============================
     NAVBAR
================================ -->

<?php include "navbar.php"; ?>


<div class="container">


    <h1>

        Feedback Form Builder

    </h1>


    <!-- ==========================
         CREATE FORM
    =========================== -->

    <div class="card">

        <h2>

            Create New Feedback Form

        </h2>


        <form method="POST">


            <label>

                Form Title

            </label>


            <input
                type="text"
                name="title"
                placeholder="Enter form title"
                required
            >


            <label>

                Description

            </label>


            <textarea
                name="description"
                placeholder="Enter form description"
            ></textarea>


            <button
                type="submit"
                name="create_form"
                class="btn-primary"
            >

                Create Form

            </button>


        </form>

    </div>


    <!-- ==========================
         EXISTING FORMS
    =========================== -->

    <div class="card">

        <h2>

            Existing Feedback Forms

        </h2>


        <div class="forms">


            <?php while ($form = $forms->fetch_assoc()) { ?>


                <div class="form-box">


                    <h3>

                        <?= htmlspecialchars($form['title']) ?>

                    </h3>


                    <p>

                        <?= htmlspecialchars($form['description']) ?>

                    </p>


                    <?php if ($form['active'] == 1) { ?>


                        <span class="status-active">

                            Active

                        </span>


                    <?php } else { ?>


                        <span class="status-inactive">

                            Inactive

                        </span>


                    <?php } ?>


                    <div class="actions">


                        <a
                            href="form_builder.php?form_id=<?= $form['id'] ?>"
                        >

                            <button
                                type="button"
                                class="btn-primary"
                            >

                                Manage Questions

                            </button>

                        </a>


                        <a
                            href="form_builder.php?toggle_form=<?= $form['id'] ?>"
                        >

                            <button
                                type="button"
                                class="btn-secondary"
                            >

                                Toggle

                            </button>

                        </a>


                    </div>


                </div>


            <?php } ?>


        </div>

    </div>


    <?php if ($selected_form) { ?>


        <!-- ==========================
             SELECTED FORM
        =========================== -->

        <div class="card">


            <h2>

                <?= htmlspecialchars($selected_form['title']) ?>

            </h2>


            <p>

                <?= htmlspecialchars($selected_form['description']) ?>

            </p>


        </div>


        <!-- ==========================
             ADD QUESTION
        =========================== -->

        <div class="card">


            <h2>

                Add Question

            </h2>


            <form method="POST">


                <input
                    type="hidden"
                    name="form_id"
                    value="<?= $selected_form['id'] ?>"
                >


                <label>

                    Question

                </label>


                <textarea
                    name="question"
                    placeholder="Enter feedback question"
                    required
                ></textarea>


                <label>

                    Category

                </label>


                <select
                    name="category"
                    required
                >


                    <option value="">

                        Select Category

                    </option>


                    <option value="Course">

                        Course

                    </option>


                    <option value="Teaching">

                        Teaching

                    </option>


                    <option value="Communication">

                        Communication

                    </option>


                    <option value="Punctuality">

                        Punctuality

                    </option>


                    <option value="General">

                        General

                    </option>


                </select>


                <button
                    type="submit"
                    name="add_question"
                    class="btn-primary"
                >

                    Add Question

                </button>


            </form>

        </div>


        <!-- ==========================
             QUESTIONS
        =========================== -->

        <div class="card">


            <h2>

                Questions

            </h2>


            <?php

            $count = 1;


            if ($questions && $questions->num_rows > 0) {


                while ($q = $questions->fetch_assoc()) {

            ?>


                    <div class="question">


                        <div class="question-number">

                            Question <?= $count ?>

                        </div>


                        <p>

                            <?= htmlspecialchars($q['question']) ?>

                        </p>


                        <span class="category">

                            <?= htmlspecialchars($q['category']) ?>

                        </span>


                        <div class="actions">


                            <a
                                href="form_builder.php?form_id=<?= $selected_form['id'] ?>&delete_question=<?= $q['id'] ?>"
                                onclick="return confirm('Delete this question?');"
                            >

                                <button
                                    type="button"
                                    class="btn-danger"
                                >

                                    Delete

                                </button>

                            </a>


                        </div>


                    </div>


            <?php

                    $count++;

                }


            } else {


                echo "<p>No questions added yet.</p>";


            }

            ?>


        </div>


    <?php } ?>


</div>


</body>

</html>