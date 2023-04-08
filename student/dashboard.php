<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("location: ../index.php");
    exit();
}

require_once "../assets/scripts/db_connect.php";
require '../assets/scripts/sql.php';

$newsmessage = getNewsMessage();

$user_id = $_SESSION["user_id"];
$user_type = usertype($user_id);

$exercises = exercises2($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard</title>
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="#">Dashboard</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
                <?php if ($user_type['type'] === 'teacher'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../docent/create.php">Opdracht aanmaken</a>
                    </li>
                    <li>
                        <a class="nav-link" href="../docent/submissions.php">Nakijken</a>
                    </li>
                <?php endif; ?>
                <li>
                    <a class="nav-link" href="../logout.php">Uitloggen</a>
                </li>
                <?php if ($user_type['type'] === 'teacher'): ?>
                <li class="nav-item">
					<a class="nav-link" href="../docent/control.php"><?php echo $user_type['full_name'] ?></a>
				</li>
                <?php else:?>
				<li class="nav-item">
					<a class="nav-link" href="#"><?php echo $user_type['full_name'] ?></a>
				</li>
                <?php endif; ?>

			</ul>
		</div>
	</nav>
    <div class="container mt-5">
        <?php if($user_type['type'] === 'teacher'): ?>
		<?php if(!$newsmessage): ?>
		<?php else: ?>
        <div class="card text-bg-primary mb-3" style="max-width: 18rem;">
            <div class="card-header">Nieuws (<?php echo $newsmessage[0]["created_at"]; ?>)</div>
            <div class="card-body">
                <p class="card-text"><?php echo $newsmessage[0]["message"]; ?></p>
            </div>
        </div>
		<?php endif ?>
        <?php endif ?>
        <h1>Welkom</h1>
        <p>Beste <?php echo $user_type['full_name'] ?>, leuk dat je meedoet aan de meeloop dag! Wij hebben een aantal opdrachten voorbereid zodat jij echt kan beleven hoe het is om hier student te zijn. Wij wensen je veel succes en als je vragen hebt kan je die altijd stellen aan een andere student. Je kan starten met de eerste opdracht "Voorbereidingen", daar vind je de verdere instructies<br><br><b>Veel succes!</b><br><br>Als je nog tijd over hebt/feedback wilt geven dan kan dat <a href="https://forms.gle/9cnvB1uBZugynniH7">HIER</a></p>
    </div>
	<div class="container mt-5" style="margin-bottom: 50px;">
		<h1>Opdrachten</h1>
<?php
    if (count($exercises) > 0): ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Punten</th>
                    <th>Status</th>
                    <th>Datum ingeleverd</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exercises as $exercise) : ?>
                    <tr>
                        <td><?php echo $exercise['title']; ?></td>
                        <?php 
                            switch($exercise['score']) {
                                case NULL:
                                    echo "<td title='Maximale punten: {$exercise['points']}'>";
                                    echo $exercise['points'];
                                    break;
                                default:
                                    echo "<td title='behaalde/maximale punten: {$exercise['score']}/{$exercise['points']}'>";
                                    echo $exercise['score']. '/' . $exercise['points']; 
                                    break;
                            }
                        ?></td>
                        <td><?php
                            switch ($exercise['status']){
                                case 'graded':
                                    echo "nagekeken";
                                    break;
                                case 'submitted':
                                    echo "ingeleverd";
                                    break;
                                default:
                                    echo "nog niet gemaakt";
                                    break;
                            }
                        ?></td>
                        <td><?php  
                            switch ($exercise['submission_date']){
                                case NULL:
                                    echo "<p title='Nog geen opdracht ingeleverd'>-</p>";
                                    break;
                                default:
                                    echo "<p title='Opdracht ingeleverd op: {$exercise['submission_date']}'>".$exercise['submission_date']."</p>";
                                    break;
                            }
                        ?></td>
                        <td>
                            <?php
                                switch ($exercise['status']){
                                    case 'graded':
                                        echo "<button type='button' class='btn btn-success'><a style='color:white;' href='opdracht.php?id={$exercise['id']}'>Naar opdracht</a></button>";
                                        break;
                                    case 'submitted':
                                        echo "<button type='button' class='btn btn-warning'><a style='color:white;' href='opdracht.php?id={$exercise['id']}'>Naar opdracht</a></button>";
                                        break;
                                    default:
                                        echo "<button type='button' class='btn btn-danger'><a style='color:white;' href='opdracht.php?id={$exercise['id']}'>Naar opdracht</a></button>";
                                        break;
                                }
                            ?>    
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No exercises found.</p>
    <?php endif;
?>
</div>
<footer class="bg-light fixed-bottom">
  <div class="container text-center">
    <p>Gemaakt door <a href="https://luciousdev.nl" target="_blank">Lucy Puyenbroek</a> | <a href="https://github.com/Luciousdev/Meeloopdag-website-ROC-Amstelland" target="_blank">source code</a></p>
  </div>
</footer>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
