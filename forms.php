<?php

require "db.php";
require "auth.php";

admin_required();


$message = "";


if (
    isset($_POST["create_form"])
) {

    $title =
        trim($_POST["title"]);

    $description =
        trim($_POST["description"]);


    $stmt =
        $conn->prepare(

            "INSERT INTO
             feedback_forms
             (title,description)
             VALUES (?,?)"

        );


    $stmt->bind_param(
        "ss",
        $title,
        $description
    );


    $stmt->execute();

    $message =
        "Feedback form created.";

}


if (
    isset($_POST["add_question"])
) {

    $form =
        (int)$_POST["form_id"];

    $question =
        trim($_POST["question"]);

    $category =
        $_POST["category"];


    $stmt =
        $conn->prepare(

            "INSERT INTO questions
            (form_id,question,category)
            VALUES (?,?,?)"

        );


    $stmt->bind_param(
        "iss",
        $form,
        $question,
        $category
    );


    $stmt->execute();

    $message =
        "Question added.";

}


include "header.php";

?>


<h1>
Feedback Form Builder
</h1>


<?php if ($message): ?>

<div class="success">

<?= h($message) ?>

</div>

<?php endif; ?>


<div class="grid">


<div class="card">

<h3>
Create Feedback Form
</h3>


<form method="POST">

<input
class="input"
name="title"
placeholder="Form title"
required
>


<textarea
class="input"
name="description"
placeholder="Description"
></textarea>


<button
name="create_form">

Create Form

</button>

</form>

</div>


<div class="card">

<h3>
Add Question
</h3>


<form method="POST">


<select
class="input"
name="form_id"
>

<?php

$r =
$conn->query(
    "SELECT *
     FROM feedback_forms"
);


while (
    $f =
    $r->fetch_assoc()
):

?>

<option
value="<?= $f["id"] ?>">

<?= h($f["title"]) ?>

</option>

<?php endwhile; ?>

</select>


<input
class="input"
name="question"
placeholder="Enter question"
required
>


<select
class="input"
name="category"
>

<option>Course</option>

<option>Teaching</option>

<option>Communication</option>

<option>Punctuality</option>

<option>General</option>

</select>


<button
name="add_question">

Add Question

</button>


</form>

</div>

</div>


<?php include "footer.php"; ?>