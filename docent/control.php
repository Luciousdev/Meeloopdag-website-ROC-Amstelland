<?php
// require '../assets/scripts/db_connect.php';
require '../assets/scripts/sql.php';

checkIfTeacher();
$test = getAllSubmissionsWithTeachers();

if (isset($_POST['admin_status'])) {
    $users_id = $_POST['usersAdminID'];
    $adminSet = $_POST['status'];

    setAdminId($users_id, $adminSet);

    echo "<script>alert('Gebruikers status is veranderd!')</script>";
}

if (isset($_POST['text'])) {
    $text = $_POST['usersAdminID'];
    setNewsMessage($text);
    echo "<script>alert('Nieuws bericht aangepast!')</script>";
}

$count2 = array();
foreach ($test as $item) {
    $name = $item["full_name"];
    if (!empty($name)) {
        if (isset($count2[$name])) {
            $count2[$name]++;
        } else {
            $count2[$name] = 1;
        }
    }
}

arsort($count2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control panel</title>
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
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
    <div class="container mt-5">
        <h3>Gebruikers status veranderen:</h3>
        <form method='post' action=''>
            <div class="form-group">
                <label for="usersAdminID">Gebruikers ID:</label>
                <input type='number' class="form-control" id="usersAdminID" name='usersAdminID' required>
            </div>
            <div class='form-group'>
                <label for="status">Status:</label>
                <select class="form-control" id="status" name='status' required>
                    <option value='teacher'>Docent</option>
                    <option value='student'>Student</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name='admin_status'>Verander status</button>
            <button class="btn btn-secondary"><a style="color:white;" href="users.php">Bekijk gebruikers</a></button>
        </form>    
    </div>
    <div class="container mt-5">
        <h3>Nieuws bericht (alleen zichtbaar voor docenten):</h3>
        <form method='post' action=''>
            <div class='form-group'>
                <label for="usersAdminID">Bericht:</label>
                <input type='text' class="form-control" id="usersAdminID" name='usersAdminID'>
            </div>
            <button type="submit" class="btn btn-primary" name='text'>Verstuur</button>
            <button class="btn btn-secondary"><a style="color:white;" href="newsdelete.php">Verwijder bericht</a></button>
        </form>    
    </div>
    <div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <h1>Statistieken:</h1>
            <table class="table table-striped table-hover">
                <tbody>
                    <tr>
                        <th scope="row">Totaal ingeleverde opdrachten:</th>
                        <td><?php echo count($test) -1;?></td>
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
                        echo $count - 1;
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
    <div class="col-md-6">
        <div class="container mt-5">
            <h1 style="margin-top: 0;">Nagekeken door docenten:</h1>
            <div class="row">
                <div class="col-md-12">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

            <script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js'></script>
            <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                labels: [
        <?php 

            $teacherNames = array_keys($count2);
            for ($i = 0; $i < count($teacherNames); $i++) {
                echo "'" . $teacherNames[$i] . "'";
                if ($i < count($teacherNames) - 1) {
                    echo ",";
                }
            }
        ?>

            ],
            datasets: [{
            label: 'Opdrachten nagekeken',
            data: [
            <?php
            for ($i = 0; $i < count($teacherNames); $i++) {
                echo $count2[$teacherNames[$i]];
                if ($i < count($teacherNames) - 1) {
                    echo ",";
                }
            }
            ?>
            ],
            backgroundColor: [

            <?php
            for ($i = 0; $i < count($teacherNames); $i++) {
                echo "'rgba(" . rand(0, 255) . ", " . rand(0, 255) . ", " . rand(0, 255) . ", 0.2)'";
                if ($i < count($teacherNames) - 1) {
                    echo ",";
                }
            }
            ?>

            ],
            borderColor: [

            <?php
            for ($i = 0; $i < count($teacherNames); $i++) {
                echo "'rgba(" . rand(0, 255) . ", " . rand(0, 255) . ", " . rand(0, 255) . ", 1)'";
                echo ",";
            }
            ?>
            ],
            borderWidth: 1
            }]
            },
            options: {
            scales: {
            yAxes: [{
            ticks: {
            beginAtZero:true
            }
            }]
            }
            }
            });
            </script>                                  
    </body>
</html> 