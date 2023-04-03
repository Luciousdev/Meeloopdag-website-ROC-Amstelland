<?php
require '../assets/scripts/db_connect.php';
require '../assets/scripts/sql.php';

// start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// assign id's to variables
$id = $_GET['id'];
$teacher_id = $_SESSION['user_id'];

$user_type = usertypeGrade($teacher_id);

$row = submissionsGrade($id);



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
  <title>Grade Submission</title>
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
    <h1>Submission Text</h1>
    <p><?php echo nl2br(htmlspecialchars($row['text_submission'])); ?></p>
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
  </div>
</body>
</html>
<?php else: echo"You're not a teacher";?>
<?php endif; ?>
