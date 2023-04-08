<?php
require '../assets/scripts/db_connect.php';
require '../assets/scripts/sql.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$teacher_id = $_SESSION['user_id'];

$user_type = submissUserType($teacher_id);

$result = resultSubmissions();

?>
<?php if ($user_type['type'] === 'teacher'):?>
<!DOCTYPE html>
<html>
<head>
  <title>Ingeleverde opdrachten</title>
  <link rel="icon" type="image/png" href="../assets/img/logo.png">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="#">Inleveringen</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
        <li>
          <a class="nav-link" href="../student/dashboard.php">Terug</a>
        </li>
			</ul>
		</div>
	</nav>
  <div class="container">
    <h1>Ingeleverde opdrachten</h1>
    <table class="table">
      <thead>
        <tr>
          <th>Student naam</th>
          <th>Opdracht</th>
          <th>Datum van inlevering</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($result as $row) { ?>
          <tr>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['submission_date']; ?></td>
            <td><?php 
              switch ($row['status']){
                case 'graded':
                    echo "nagekeken";
                    break;
                case 'submitted':
                    echo "ingeleverd";
                    break;
            }?></td>
            <td><a href="grade.php?id=<?php echo $row['id']; ?>">Opdracht nakijken</a></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>
<?php else: echo "You're not a teacher";?>
<?php endif; ?>