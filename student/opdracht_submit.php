<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exercise_id = $_POST["exercise_id"];
    $submission = $_POST["submission"];

    $user_id = $_SESSION["user_id"];

    require '../assets/scripts/sql.php';

    submission_submit($user_id, $exercise_id, $submission);

    header("location: opdracht.php?id=$exercise_id");
    exit();
} else {
    header("location: ../index.php");
    exit();
}
