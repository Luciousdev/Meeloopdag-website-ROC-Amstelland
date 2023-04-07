<?php
// require '../assets/scripts/db_connect.php';
require '../assets/scripts/sql.php';

$test = getAllSubmissionsWithTeachers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="#">Control panel</a>
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
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <table class="table table-striped table-hover">
                    <tbody>
                        <tr>
                            <th scope="row">Totaal ingeleverde opdrachten:</th>
                            <td><?php echo count($test);?></td>
                        </tr>
                        <tr>
                            <th scope="row">Nagekeken opdrachten:</th>
                            <td><?php 
                            $count = 0;
                            foreach ($test as $item) {
                                if ($item['status'] == 'graded') {
                                    $count++;
                                }
                            }
                            echo $count;
                            ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><a style="color:black;" href="submissions.php">Nog na te kijken opdrachten:</a></th>
                            <td><?php 
                            $count = 0;
                            foreach ($test as $item) {
                                if ($item['status'] == 'submitted') {
                                    $count++;
                                }
                            }
                            echo $count;
                            ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>