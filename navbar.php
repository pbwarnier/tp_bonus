<nav class="navbar navbar-dark bg-dark">
	<ul class="navbar-nav mr-auto flex-row">
		<li class="mr-3 nav-item">
      <a class="nav-link" href="index.php">M'inscrire</a>
    </li>
    <li class="mr-3 nav-item">
      <a class="nav-link" href="user.php">Infos utilisateurs</a>
    </li>
    <li class="mr-3 nav-item">
      <a class="nav-link" href="admin.php">Administrateur</a>
    </li>
<?php
  // affiche le lien de connexion si la session est absente
  if (!isset($_SESSION['user'])) {
?>
  <li class="mr-3 nav-item">
    <a class="nav-link" href="login.php">Me connecter</a>
  </li>
<?php
  }
  // sinon affiche le bouton de deconnexion
  else{
?>
  <li class="mr-3 nav-item">
    <!-- le $_GET logout sert à déclencher la deconnexion -->
    <a class="btn btn-outline-light" href="login.php?logout=true">Se déconnecter</a>
  </li>
<?php
  }
?>
    
	</ul>
<?php
  if (isset($_SESSION['user'])) {
?>
  <div class="ml-auto border border-light rounded-circle text-light d-flex">
    <span class="m-auto"><?= /* Première lettre en MAJ */ ucfirst($_SESSION['user']['login'])[0] ?></span>
  </div>
<?php
  }
?>
</nav>