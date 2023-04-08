<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit();
}
// ERROR REPORTING TURNED OFF!!! FIXME
error_reporting(E_ERROR);
// ERROR REPORTING TURNED OFF!!! FIXME

require_once "../assets/scripts/db_connect.php";
require_once "../assets/scripts/parsedown.php";
require '../assets/scripts/sql.php'; 

$userid = $_SESSION['user_id'];
$exercise_id = $_GET["id"];

$user_type = user_type($userid);
$exercise = exercises($exercise_id);
$submission = submissions($userid, $exercise_id);

$submission_id = $submission[0]['id'];
$feedback = feedback($submission_id);

$teacher_id = $feedback[0]['teacher_id'];
$teachername = teachername($teacher_id);

$Parsedown = new Parsedown();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $exercise['title']; ?></title>
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="./dashboard.php">Terug</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo $user_type["full_name"]; ?></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5" style="margin-bottom: 15px;">
    <h3>Feedback</h3>
    <hr>
        <?php if (empty($feedback)): ?>
            <div class="alert alert-info">
                De opdracht is nog niet nagekeken.
            </div>
        <?php else: ?>
            <div class="grade">
                <?php if (empty($feedback[0]['score'])): ?>
                    <div class="alert alert-info">
                        Your teacher has not yet provided a grade.
                    </div>
                <?php else: ?>
                    <p>Score: <?php echo $feedback[0]['score']. "/" . $exercise['points']; ?></p>
                    <p>Nagekeken door: <?php echo $teachername[0]['full_name']; ?></p>
                <?php endif; ?>
            </div>
            <div class="feedback-content">
                <h5>Jouw feedback:</h5>
                <?php echo $feedback[0]['feedback']; ?>
            </div>
        <?php endif; ?>
    <h1><?php echo $exercise['title']; ?></h1>
    <hr>
    <div class="exercise-content">
        <?php 
            $html =  $Parsedown->text($exercise['description']); 
            $plain = htmlspecialchars_decode($html);
            echo $plain;
                    
        ?>
    </div>
    <hr>
    <?php if (empty($submission)): ?>
        <?php if ($exercise['inleverbaar'] == 'TRUE'):?>
            <h2>Inleveren</h2>
            <form action="opdracht_submit.php" method="POST">
                <input type="hidden" name="exercise_id" value="<?php echo $exercise_id; ?>">
                <div class="form-group">
                    <label for="submission"></label>
                    <textarea class="form-control" id="submission" name="submission" rows="10"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Inleveren</button>
            </form>
        <?php else:?>
            <h2>Bij deze opdracht hoef je niets in te leveren!</h2>
        <?php endif ?>
    <?php else: ?>
        <div class="alert alert-info">
            Je hebt de opdracht al ingeleverd. Bovenaan de pagina kan je de feedback zien wanneer het is nagekeken.
        </div>
        <h3>Jouw antwoord:</h3><br><br>
        <div class="submission-content" style="border-style: solid">
            <?php 
                echo nl2br(htmlspecialchars($submission[0]['text_submission']));
            ?>
        </div>
        <hr>
    <?php endif; ?>
    
</div>
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script>
    var simplemde = new SimpleMDE();
</script>
</body>
</html>