<?php
session_start();

// Redirect to dashboard if the user is already logged in
if(isset($_SESSION['user_id'])) {
    header('Location: ./student/dashboard.php');
    exit;
}

// Process login form
if (isset($_POST['full_name']) && isset($_POST['password'])) {
    // Get form data
    $full_name = trim($_POST['full_name']);
    $password = $_POST['password'];

    // Validate form data
    if (empty($full_name) || empty($password)) {
        $error = "Please fill out all fields";
    } else {
        try {
            // Connect to database
            require './assets/scripts/db_connect.php';
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if user exists and verify password
            $stmt = $pdo->prepare("SELECT * FROM users WHERE full_name = :full_name");
            $stmt->bindParam(":full_name", $full_name);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $password = $_POST['password'];

            if ($user && password_verify($password, $user['password']) == TRUE) {
                // Login successful, set session variables and redirect to dashboard
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user['type'];
                header('Location: ./student/dashboard.php');
                exit;
            } else {
                // Invalid credentials
                $error = "Incorrect username or password";
            }
        } catch (PDOException $e) {
            $error = "Connection failed: " . $e->getMessage();
        }
        $pdo = null;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="icon" type="image/png" href="./assets/img/logo.png">
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
				<button class="btn btn-secondary"><a style="color:white;" href="signup.php">Account maken</a></button>
			</form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
