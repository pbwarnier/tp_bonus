<?php
	$title = 'Cookie data user';
	include 'head.php';

	if (isset($_GET['logout'])) {
		// vide le tableau session
		$_SESSION['user'] = [];
		// vide la variable session
		unset($_SESSION['user']);
		// détruit la session
		session_destroy();
	}

	if (!empty($_POST['login']) && !empty($_POST['password'])) {
		$_SESSION['user'] = ['auth' => true, 'login' => $_POST['login']];
	}

	include 'navbar.php';
?>
	<div class="container">
		<div class="p-3 w-100">
			<form action="login.php" method="POST">
				<fieldset class="p-3 border border-dark">
					<legend>Me connecter</legend>
					<div class="form-group">
						<input class="form-control" type="text" name="login" placeholder="Votre adresse email">
					</div>
					<div class="form-group">
						<input class="form-control" type="password" name="password" placeholder="Votre mot de passe">
					</div>
					<div class="w-100 d-flex">
						<button class="mx-auto btn btn-outline-dark rounded-pill" type="submit">Me connecter</button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
<?php
	include 'foot.php';