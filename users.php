
<?php

include "auth.php";

admin_required();

include "db.php";

$message = "";
$error = "";


/* =========================
   ADD FACULTY
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $department = trim($_POST["department"] ?? "");


    /* Validation */

    if (
        $name == "" ||
        $email == "" ||
        $password == "" ||
        $department == ""
    ) {

        $error = "Please fill all fields.";

    } else {

        /* Check email already exists */

        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);

        $check->execute();

        $result = $check->get_result();


        if ($result->num_rows > 0) {

            $error = "Email already exists.";

        } else {

            /* Password hashing */

            $hashedPassword = hash(
                "sha256",
                $password
            );


            /* Insert Faculty */

            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, email, password, role, department, semester)
                VALUES (?, ?, ?, 'faculty', ?, '')"
            );

            $stmt->bind_param(
                "ssss",
                $name,
                $email,
                $hashedPassword,
                $department
            );


            if ($stmt->execute()) {

                $message = "Faculty added successfully.";

            } else {

                $error = "Failed to add faculty.";

            }

            $stmt->close();

        }

        $check->close();

    }

}


/* =========================
   GET ALL USERS
========================= */

$result = $conn->query(
    "SELECT id, name, email, role, department, semester
     FROM users
     ORDER BY id ASC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SCFMS - Manage Users</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: #f5f7fb;

            color: #333;

        }


        /* =========================
           PAGE
        ========================= */

        .page-container {

            width: 92%;

            max-width: 1200px;

            margin: 35px auto 60px;

        }


        /* =========================
           TITLE
        ========================= */

        .page-title {

            margin-bottom: 25px;

        }


        .page-title h1 {

            margin: 0;

            color: #252b48;

            font-size: 30px;

        }


        .page-title p {

            margin-top: 8px;

            color: #777f93;

            font-size: 14px;

        }


        /* =========================
           CARD
        ========================= */

        .card {

            background: white;

            border-radius: 14px;

            padding: 25px;

            margin-bottom: 30px;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.06);

        }


        .card h2 {

            margin-top: 0;

            margin-bottom: 20px;

            color: #292e45;

            font-size: 21px;

        }


        /* =========================
           FORM
        ========================= */

        .form-grid {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 18px;

        }


        .form-group {

            display: flex;

            flex-direction: column;

        }


        .form-group label {

            margin-bottom: 7px;

            font-size: 14px;

            font-weight: bold;

            color: #444;

        }


        .form-group input,
        .form-group select {

            width: 100%;

            padding: 11px 12px;

            border: 1px solid #d9dce7;

            border-radius: 7px;

            font-size: 14px;

            outline: none;

            background: white;

        }


        .form-group input:focus,
        .form-group select:focus {

            border-color: #667eea;

            box-shadow:
                0 0 0 3px
                rgba(102, 126, 234, 0.1);

        }


        .full-width {

            grid-column: 1 / -1;

        }


        /* =========================
           BUTTON
        ========================= */

        .add-btn {

            margin-top: 20px;

            padding: 11px 22px;

            border: none;

            border-radius: 8px;

            background:
                linear-gradient(
                    135deg,
                    #667eea,
                    #764ba2
                );

            color: white;

            font-size: 14px;

            font-weight: bold;

            cursor: pointer;

        }


        .add-btn:hover {

            opacity: 0.9;

        }


        /* =========================
           MESSAGE
        ========================= */

        .success {

            background: #eaf8ef;

            color: #16803c;

            border: 1px solid #bce8ca;

            padding: 12px 15px;

            border-radius: 7px;

            margin-bottom: 20px;

        }


        .error {

            background: #fff0f0;

            color: #c62828;

            border: 1px solid #f3c0c0;

            padding: 12px 15px;

            border-radius: 7px;

            margin-bottom: 20px;

        }


        /* =========================
           TABLE
        ========================= */

        .table-wrapper {

            overflow-x: auto;

        }


        table {

            width: 100%;

            border-collapse: collapse;

            min-width: 750px;

        }


        th {

            background: #f1f3ff;

            color: #3d4460;

            padding: 13px;

            text-align: left;

            font-size: 13px;

        }


        td {

            padding: 13px;

            border-bottom: 1px solid #eeeeee;

            font-size: 13px;

            color: #555;

        }


        tr:hover td {

            background: #fafaff;

        }


        /* =========================
           ROLE BADGES
        ========================= */

        .role {

            display: inline-block;

            padding: 5px 10px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: bold;

        }


        .admin {

            background: #eeeaff;

            color: #6b46c1;

        }


        .faculty {

            background: #e8f1ff;

            color: #2563eb;

        }


        .student {

            background: #e8f8ef;

            color: #16803c;

        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 700px) {

            .form-grid {

                grid-template-columns: 1fr;

            }


            .full-width {

                grid-column: auto;

            }


            .page-title h1 {

                font-size: 25px;

            }

        }

    </style>

</head>


<body>


<!-- =========================
     HEADER
========================= -->

<?php include "header.php"; ?>


<!-- =========================
     PAGE CONTENT
========================= -->

<div class="page-container">


    <div class="page-title">

        <h1>
            Manage Users
        </h1>

        <p>
            Add faculty members and manage college users.
        </p>

    </div>


    <!-- =========================
         MESSAGES
    ========================= -->

    <?php if ($message != ""): ?>

        <div class="success">

            <?php echo h($message); ?>

        </div>

    <?php endif; ?>


    <?php if ($error != ""): ?>

        <div class="error">

            <?php echo h($error); ?>

        </div>

    <?php endif; ?>


    <!-- =========================
         ADD FACULTY
    ========================= -->

    <div class="card">

        <h2>
            Add Faculty
        </h2>


        <form method="POST">


            <div class="form-grid">


                <!-- NAME -->

                <div class="form-group">

                    <label>
                        Faculty Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter faculty name"
                        required
                    >

                </div>


                <!-- EMAIL -->

                <div class="form-group">

                    <label>
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter faculty email"
                        required
                    >

                </div>


                <!-- PASSWORD -->

                <div class="form-group">

                    <label>
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter password"
                        required
                    >

                </div>


                <!-- DEPARTMENT -->

                <div class="form-group">

                    <label>
                        Department
                    </label>

                    <select
                        name="department"
                        required
                    >

                        <option value="">
                            Select Department
                        </option>

                        <option value="Computer Science">
                            CS - Computer Science
                        </option>

                        <option value="Information Technology">
                            IT - Information Technology
                        </option>

                        <option value="Commerce">
                            COM - Commerce
                        </option>

                        <option value="Physics">
                            PHY - Physics
                        </option>

                        <option value="Chemistry">
                            CHE - Chemistry
                        </option>

                    </select>

                </div>


            </div>


            <button
                type="submit"
                class="add-btn"
            >

                + Add Faculty

            </button>


        </form>

    </div>


    <!-- =========================
         USERS LIST
    ========================= -->

    <div class="card">

        <h2>
            All Users
        </h2>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Role
                        </th>

                        <th>
                            Department
                        </th>

                        <th>
                            Semester
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php if ($result && $result->num_rows > 0): ?>


                        <?php while ($user = $result->fetch_assoc()): ?>


                            <tr>


                                <td>
                                    <?php echo h($user["id"]); ?>
                                </td>


                                <td>
                                    <?php echo h($user["name"]); ?>
                                </td>


                                <td>
                                    <?php echo h($user["email"]); ?>
                                </td>


                                <td>

                                    <?php

                                    $roleClass =
                                        strtolower(
                                            $user["role"]
                                        );

                                    ?>

                                    <span
                                        class="role <?php echo h($roleClass); ?>"
                                    >

                                        <?php
                                        echo ucfirst(
                                            h($user["role"])
                                        );
                                        ?>

                                    </span>

                                </td>


                                <td>

                                    <?php
                                    echo h(
                                        $user["department"]
                                    );
                                    ?>

                                </td>


                                <td>

                                    <?php

                                    echo
                                        $user["semester"] != ""
                                        ? h($user["semester"])
                                        : "-";

                                    ?>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="6"
                                style="text-align:center;"
                            >

                                No users found.

                            </td>

                        </tr>


                    <?php endif; ?>


                </tbody>

            </table>

        </div>

    </div>


</div>


</body>

</html>

