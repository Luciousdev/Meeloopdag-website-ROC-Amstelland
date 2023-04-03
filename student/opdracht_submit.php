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

    // Insert or update the submission in the database
    // $stmt = $pdo->prepare("INSERT INTO submissions (student_id, assignment_id, text_submission) VALUES (:student_id, :assignment_id, :text_submission) ON DUPLICATE KEY UPDATE text_submission = :text_submission");
    // $stmt->bindParam(":student_id", $user_id, PDO::PARAM_INT);
    // $stmt->bindParam(":assignment_id", $exercise_id, PDO::PARAM_INT);
    // $stmt->bindParam(":text_submission", $submission);
    // $stmt->execute();

    header("location: opdracht.php?id=$exercise_id");
    exit();
} else {
    header("location: ../index.php");
    exit();
}
