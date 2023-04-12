<?php
require '../assets/scripts/db_connect.php';
require '../assets/scripts/sql.php';

// start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

checkIfTeacher();

// ERROR REPORTING TURNED OFF!!! FIXME
error_reporting(E_ERROR);
// ERROR REPORTING TURNED OFF!!! FIXME

// assign id's to variables
$id = $_GET['id'];
$teacher_id = $_SESSION['user_id'];

$user_type = usertypeGrade($teacher_id);

$row = submissionsGrade($id);

$assignment_id = $row['assignment_id'];
$assignmentAnswer = assignmentAnswer($assignment_id);

if($row["status"] == "graded"){
  $gradeSubmission = getGrade($id);
  
  $teach_id = $gradeSubmission["teacher_id"];
  $teacherName = getTeacherName($teach_id);
} else {

}

if (isset($_POST['submit'])) {
  $score = $_POST['score'];
  $feedback = $_POST['feedback'];

  setGrade($id, $teacher_id, $score, $feedback);
  header('Location: submissions.php');
}
?>

<?php if ($user_type['type'] === 'teacher'):?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Nakijken</title>
  <link rel="icon" type="image/png" href="../assets/img/logo.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="#">Inzendingen</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
        <li>
          <a class="nav-link" href="./submissions.php">Terug</a>
        </li>
			</ul>
		</div>
	</nav>
  <div class="container">
    <h1 class="mt-4 mb-5">Inlevering:</h1>
    <div class="row">
        <div class="col-md-8">
          <h3>Ingeleverd:</h3>
          <p><?php echo nl2br(htmlspecialchars($row['text_submission'])); ?></p>
        </div>
        <?php if($row["status"] == "graded"): ?>
        <div class="col-md-4 border-left pt-3">
            <h3>Beoordeling:</h3>
            <p>Punten: <?php echo $gradeSubmission["score"]; ?></p>
            <p>Feedback: <?php echo $gradeSubmission["feedback"]; ?></p>
            <p>Nagekeken door: <?php echo $teacherName[0]; ?></p>
        </div>
        <?php endif; ?>
    </div>
    <?php if ($row['status'] == 'submitted'): ?>


    <h2>Grade Submission</h2>
    <form method="post" action="">
      <div class="form-group">
        <label for="score">Score:</label>
        <input type="number" class="form-control" id="score" name="score" required>
      </div>
      <div class="form-group">
        <label for="feedback">Feedback:</label>
        <textarea class="form-control" id="feedback" name="feedback" rows="5" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary" name="submit">Submit</button>
    </form>
    <?php endif; ?>
    <?php if($assignmentAnswer['good_answer'] === NULL): ?>
      <center style="font-size:larger; margin-top:40px;" class="alert alert-danger">Er is geen antwoordmodel voor deze opdracht!</center>
    <?php else:?>
      <?php if($row['status'] == 'submitted'): ?>
        <h3 style="padding-top:50px;">Goed antwoord:</h3>
        <p><?php echo nl2br(htmlspecialchars($assignmentAnswer['good_answer'])); ?></p>
        <?php endif?>
    <?php endif?>
  </div>
</body>
</html>
<?php else: echo"You're not a teacher";?>
<?php endif; ?>
