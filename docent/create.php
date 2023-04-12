<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "../assets/scripts/db_connect.php";
include '../assets/scripts/sql.php';
$userid = $_SESSION['user_id'];

$user_type = createUserType($userid);

if ($user_type[0]['type'] === 'teacher') {
    require_once "../assets/scripts/db_connect.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userid = $_SESSION['user_id'];
        $title = htmlspecialchars(trim($_POST["title"]));
        $description = htmlspecialchars(trim($_POST["instructions"]));
        $points = intval($_POST["points"]);
        $inleverbaar = $_POST['inleverbaar'];
        $antwoord = $_POST['antwoord'];

        insertCreateAss($title, $description, $points, $inleverbaar, $antwoord);

        header("location: ../student/dashboard.php");
        exit();
    }
} else {
    header("location: ../student/dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Exercise</title>
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Opdracht aanmaken</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../student/dashboard.php">Terug</a>
                </li>
            </ul>
        </div>
    </nav>
    <div style="margin-bottom:30px;" class="container mt-5">
        <h1>Opdracht aanmaken</h1>
        <form method="post" action="create.php">
            <div class="form-group">
                <label for="title">Titel:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="points">Totaal aantal haalbare punten:</label>
                <input type="number" class="form-control" id="points" name="points" required>
            </div>
            <div class="form-group">
                <label for="inleverbaar">Inleverbaar:</label>
                <select class="form-control" name="inleverbaar" id="lang">
                    <option value="TRUE">Ja</option>
                    <option value="FALSE">Nee</option>
                </select>
            </div>
            <div class="form-group">
                <label for="instructions">Opdracht:</label>
                <textarea class="form-control" id="instructions" name="instructions" rows="10"></textarea>
            </div>
            <div class="form-group">
                <label for="antwoord">Nakijk model/mogelijk antwoord:</label>
                <textarea class="form-control" id="antwoord" name="antwoord" rows="10"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Opdracht aanmaken</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script>
        let instructionsEditor = new SimpleMDE({
            element: document.getElementById("instructions")
        });

        let antwoordEditor = new SimpleMDE({
            element: document.getElementById("antwoord")
        });
    </script>
</body>
</html>
