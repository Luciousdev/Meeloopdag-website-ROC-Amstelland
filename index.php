<?php
session_start();


// include('./assets/scripts/db_connect.php');


if(isset($_SESSION['user_id'])) {
  header('Location: ./student/dashboard.php');
  exit;
}

if(isset($_POST['full_name']) && isset($_POST['password'])) {
  $full_name = $_POST['full_name'];
  $password = $_POST['password'];

  require './assets/scripts/sql.php';
  $user = user($full_name);

  if($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_type'] = $user['type'];
    // echo "test";
    header('Location: ./student/dashboard.php');
    exit;
  } else {
    $error = 'Invalid email or password';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="#">Login pagina</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav ml-auto">
        <li>
          <a class="nav-link" href="./signup.php">Account aanmaken</a>
        </li>
			</ul>
		</div>
	</nav>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4>Login</h4>
          </div>
          <div class="card-body">
            <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form action="index.php" method="post">
              <div class="form-group">
                <label for="email">Volledige naam</label>
                <input type="" class="form-control" id="full_name" name="full_name" required>
              </div>
              <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <button type="submit" class="btn btn-primary">Inloggen</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
