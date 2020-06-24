<?php
	$title = 'Formulaire d\'inscription';
	$isSubmitted = false;
	$sexe = null;
	$lastname = null;
	$firstname = null;
	$birthdate = null;
	$email = null;
	$password = null;
	$confirmPassword = null;
	$cgu = null;
	$errors = [];

	// quand le formulaire est submit
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$isSubmitted = true;
		// vérifie que le sexe existe
		if (empty($_POST['sexe'])) {
			$errors['sexe'] = 'Veuillez choisir votre civilité';
		}
		elseif ($_POST['sexe'] != 1 && $_POST['sexe'] != 2) {
			$errors['sexe'] = 'La donnée saisie n\'est pas correcte';
		}

		// filtre le nom et prénom
		$firstname = trim(filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING));
    	if (empty($firstname)){
        	$errors['firstname'] = 'Veuillez renseigner votre prénom.';
    	}
    	elseif (!preg_match('/^[a-zéèîïêëç]+((?:\-|\s)[a-zéèéîïêëç]+)?$/i', $firstname)) {
        	$errors['firstname'] = 'Le format attendu n\'est pas respecté';
    	}

    	$lastname = trim(filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING));
    	if (empty($lastname)){
        	$errors['lastname'] = 'Veuillez renseigner votre nom.';
    	}
    	elseif (!preg_match('/^[a-zéèîïêëç]+((?:\-|\s)[a-zéèéîïêëç]+)?$/i', $lastname)) {
        	$errors['lastname'] = 'Le format attendu n\'est pas respecté';
    	}

    	// filtre la date de naissance
    	$birthdate = trim(filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_STRING));
    	if (!empty($birthdate)) {
    		// si input type date pas besoin DEBUT
    		// convertion de la date avant de vérifier les données
    		$birthdate = DateTime::createFromFormat('d/m/Y', $birthdate);
    		$birthdate = $birthdate -> format('Y-m-d');
    		// FIN

    		// créé le timestamp d'aujourd'hui
    		$today = strtotime("NOW");
    		// timestamp de mon input date
    		$convertBirthdate = strtotime($birthdate);
    		if (!preg_match('/^((?:19|20)[0-9]{2})-((?:0[1-9])|(?:1[0-2]))-((?:0[1-9])|(?:1[0-9])|(?:2[0-9])|(?:3[01]))$/', $birthdate)) {
    			$errors['birthdate'] = 'Veuillez renseigner une date correcte';
    		}
    		// vérifie que la date reste inférieur à NOW
    		elseif ($convertBirthdate > $today) {
    			$errors['birthdate'] = 'Votre date ne peut pas être supérieur à la date du jour';
    		}
    	}
    	else{
    		$errors['birthdate'] = 'Veuillez renseigner votre date de naissance';
    	}

    	// filtre l'adresse email
    	$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
    	if (empty ($email)){
        	$errors['email'] = 'Veuillez renseigner votre adresse email';
    	}
    	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        	$errors['email'] = 'Le format attendu n\'est pas respecté';
    	}

    	// supprime les espaces avant et après
    	$password = trim($_POST['password']);
    	$confirmPassword = trim($_POST['confirmPassword']);
    	if (!empty($password)) {
    		// vérifie que les deux passwords sont identiques
    		if ($password != $confirmPassword) {
    			$errors['comparePassword'] = 'Les mots de passe ne sont pas identiques';
    		}
    	}
    	else{
    		$errors['password'] = 'Veuillez renseigner un mot de passe';
    	}

    	// vérifie que la checkbox n'existe pas (pas coché)
    	if (!isset($_POST['cgu'])) {
    		$errors['cgu'] = 'Vous devez accepter nos CGU pour vous inscrire';
    	}

    	if (count($errors) == 0) {
    		// transforme le tableau POST en chaine de caractères
    		$user = serialize($_POST);
    		// créé le cookie avec la chaine des POST
    		setcookie('user', $user, time() + 3600, '/', '', false, false);
    	}
	}

	include 'head.php';
	include 'navbar.php';

	// affiche le message si le form est soumit et qu'il n'y a pas d'erreurs
	if($isSubmitted && count($errors) == 0){
?>
		<div class="alert alert-success" role="alert">
  			Votre compte a été crée avec succès
		</div>
<?php
	}
?>
	<div class="container">
		<div class="p-3 w-100">
			<form action="index.php" method="POST">
				<fieldset class="p-3 border">
					<legend>M'inscrire</legend>
					<div class="form-group d-flex">
						<div class="mr-3 custom-control custom-radio">
  							<input type="radio" id="madame" name="sexe" class="custom-control-input <?= isset($errors['sexe']) ? 'is-invalid' : '' ?>" value="1">
  							<label class="custom-control-label" for="madame">Madame</label>
						</div>
						<div class="ml-3 custom-control custom-radio">
  							<input type="radio" id="monsieur" name="sexe" class="custom-control-input <?= isset($errors['sexe']) ? 'is-invalid' : '' ?>" value="2">
  							<label class="custom-control-label" for="monsieur">Monsieur</label>
  							<?php if (isset($errors['sexe'])) { ?><div class="invalid-feedback"><?= $errors['sexe'] ?></div><?php } ?>
						</div>
					</div>
					<div class="w-100 d-flex">
						<div class="form-group mr-3 w-100">
							<input class="form-control w-100 <?= isset($errors['lastname']) ? 'is-invalid' : '' ?>" type="text" name="lastname" maxlength="50" onkeyup="upperCase(this.value, 'lastname')" placeholder="Votre nom">
							<?php if (isset($errors['lastname'])) { ?><div class="invalid-feedback"><?= $errors['lastname'] ?></div><?php } ?>
						</div>
						<div class="form-group ml-3 w-100">
							<input class="form-control w-100 <?= isset($errors['lastname']) ? 'is-invalid' : '' ?>" type="text" name="firstname" maxlength="50" onkeyup="upperCase(this.value, 'firstname')" placeholder="Votre prénom">
							<?php if (isset($errors['lastname'])) { ?><div class="invalid-feedback"><?= $errors['firstname'] ?></div><?php } ?>
						</div>
					</div>
					<div class="w-100 d-flex">
						<div class="form-group mr-3 w-100">
							<input id=birthdate class="form-control w-100 <?= isset($errors['birthdate']) ? 'is-invalid' : '' ?>" type="text" name="birthdate" placeholder="Votre date de naissance" autocomplete="off">
							<?php if (isset($errors['birthdate'])) { ?><div class="invalid-feedback"><?= $errors['birthdate'] ?></div><?php } ?>
						</div>
						<div class="form-group ml-3 w-100">
							<input class="form-control w-100 <?= isset($errors['email']) ? 'is-invalid' : '' ?>" type="email" name="email" placeholder="Votre email">
							<?php if (isset($errors['email'])) { ?><div class="invalid-feedback"><?= $errors['email'] ?></div><?php } ?>
						</div>
					</div>
					<div class="w-100 d-flex">
						<div class="form-group mr-3 w-100">
							<input class="form-control w-100 <?= isset($errors['password']) ? 'is-invalid' : '' ?>" type="password" name="password" placeholder="Votre mot de passe">
							<!-- check force password -->
							<div id="forcePassword" class="bg-white">
								<div class="force-progress w-100 rounded-pill">
  									<div id="progress" class="p-bar" role="progressbar" aria-valuemin="0" aria-valuemax="4"></div>
								</div>
								<div id="force" class="small text-secondary">Faible</div>
							</div>
							<?php if (isset($errors['password'])) { ?><div class="invalid-feedback"><?= $errors['password'] ?></div><?php } ?>
						</div>
						<div class="form-group ml-3 w-100">
							<input class="form-control w-100 <?= isset($errors['comparePassword']) ? 'is-invalid' : '' ?>" type="password" name="confirmPassword" placeholder="Confirmez le mot de passe">
							<!-- validation of the passwords values -->
			                <div id="check_passwords" class="bg-white">
			                	<span id="checked_info" class="invalid">Les mots de passe ne correspondent pas</span>
			                </div>
							<?php if (isset($errors['comparePassword'])) { ?><div class="invalid-feedback"><?= $errors['comparePassword'] ?></div><?php } ?>
						</div>
					</div>
					<div class="w-100 d-flex">
						<div class="mx-auto">
							<div class="custom-control custom-checkbox">
  								<input type="checkbox" class="custom-control-input <?= isset($errors['cgu']) ? 'is-invalid' : '' ?>" id="CGU" name="cgu">
  								<label class="custom-control-label" for="CGU">J'ai lu et j'accepte les conditions générale d'utilisation</label>
  								<?php if (isset($errors['cgu'])) { ?><div class="invalid-feedback"><?= $errors['cgu'] ?></div><?php } ?>
							</div>
						</div>
					</div>
					<div class="w-100 d-flex">
						<div class="mx-auto">
							<button type="submit" class="mt-3 btn btn-outline-dark rounded-pill">M'inscrire</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		function upperCase(string, name_input){
			// Met la première lettre en MAJ et le reste en minuscule
			string = string.substr(0, 1).toUpperCase() + string.substr(1, string.length).toLowerCase();
			document.querySelector(`input[name="${name_input}"]`).value = string;
			//$("input[name='"+name_input+"']").val(string);
		}

		// Fait apparaitre la progressbar quand on focus le champ password
		document.querySelector(`input[name="password"]`).addEventListener('focus', function(){ 
			let forcePassword = $("#forcePassword").slideDown();
		})

		/* $("input[name='password']").focus(function(){
			$("#forcePassword").slideDown();
		}) */

		// selectionne un élément et affique la fonction au keyup
		$("input[name='password']").keyup(function(){
			// prend la value du selecteur choisi précédement
			var password = $(this).val();
			var force = 0;

			// vérifie que la regex est true ou false
			// var regex = (/(?=.*[a-z])/).test(password);
			
			// vérifie que la value de l'input contient des lettres
			// Si c'est le cas, la force prend +1
			if (password.match(/(?=.*[a-z])/) || password.match(/(?=.*[A-Z])/)) {
				force ++;
			}

			// vérifie que la value de l'input contient des chiffres
			if (password.match(/(?=.*[0-9])/)) {
				force ++;
			}

			// vérifie que la value de l'input contient des caractères spéciaux
			if (password.match(/(?=.*\W)/)) {
				force ++;
			}


			// vérifie que le password contient au moins 8 caractères
			if (password.length >= 8) {
				force ++;
			}

			// couleur en fonction de la force
			if (force == 1) {
				var bgColor = '#dc3545';
			}
			else{
				if (force == 2) {
					var bgColor = '#ffc107';
				}
				else{
					if (force == 3) {
						var bgColor = '#28a745';
					}
					else{
						if (force == 4) {
							var bgColor = '#0d6e25';
						}
					}
				}
			}

			document.getElementById('progress').style.backgroundColor = bgColor;
			document.getElementById('progress').style.width = 25*force+'%';

			//document.getElementById('progress').setAttribute('style', 'width:'+25*force+'%; background-color: '+bgColor);

			// change le css de la progressbar
			/* $("#progress").css({
				'width': 25*force+'%',
				'background-color': bgColor
			}); */
		})

		// fait disparaitre la progressbar quand on quitte le champ password
		$("input[name='password']").blur(function(){
			$("#forcePassword").slideUp();
		})

		// fait appraitre la div qui vérifie la correspondance des passwords
		$("input[name='confirmPassword']").focus(function(){
			// animation vers le bas
			$("#check_passwords").slideDown();
			// selectionne l'input password
			var input = $("input[name='password']");
			// selectionne l'input choisi au .focus()
	  		var compare = $(this);
	  		// selectionne le span qui modifie le texte
	  		var info = $("#checked_info");
			if (compare.val() != "" && input.val() == compare.val()) {
				// retire une class css
				document.getElementById('checked_info').classList.remove('invalid');
				// info.removeClass("invalid");

				// ajoute une class css
				document.getElementById('checked_info').classList.add('valid');
				//info.addClass("valid");

				// ajoute du text dans le html
				document.getElementById('checked_info').textContent = 'Les mots de passe correspondent';
				// info.text("Les mots de passe correspondent");
			}
			else{
				info.removeClass("valid");
				info.addClass("invalid");
				info.text("Les mots de passe ne correspondent pas");
			}
		})

		// fait le traitement de correspondance quand on keyup dans le champ confirmer password
		$("input[name='confirmPassword']").keyup(function(){
			// selectionne l'input password
			var input = $("input[name='password']");
			// selectionne l'input choisi au .keyup()
	  		var compare = $(this);
	  		// selectionne le span qui modifie le texte
	  		var info = $("#checked_info");
	  		// vérifie que la valeur est différente de rien et que les passwords correspondent
			if (compare.val() != "" && input.val() == compare.val()) {
				info.removeClass("invalid");
				info.addClass("valid");
				info.text("Les mots de passe correspondent");
			}
			else{
				info.removeClass("valid");
				info.addClass("invalid");
				info.text("Les mots de passe ne correspondent pas");
			}
		})

		$("input[name='confirmPassword']").blur(function(){
			$("#check_passwords").slideUp();
		})

		// calendrier birthdate
		$("#birthdate").datepicker({
		    format: "dd/mm/yyyy",
		    maxViewMode: 3,
		    language: "fr",
		    daysOfWeekHighlighted: "0,6",
		    autoclose: true,
		    todayHighlight: true,
		    toggleActive: true,
		    orientation: "bottom auto",
		    todayHighlight: true,
		    endDate: "now"
		});
	</script>
<?php		
	include 'foot.php';