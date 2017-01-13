	<?php include_once 'lib/authentication/lang/'.$_SESSION['lang'].'.lang.php'; ?>

	<div id="connexion">
		<h3><br/><?php echo CONNEXION_TITLE; ?><br/></h3>
		<form action="<?php echo COMMON_URL; ?>/lib/authentication/php/connexion.php" method="post" onsubmit="return verifConnexionForm(this)">
			<?php	if(isset($_SESSION['connexion_form_error_message'])){
						 echo $_SESSION['connexion_form_error_message'];
					if(empty($_SESSION['connexion_form_error_message'])){
						 echo "Le message d'erreur de connexion existe mais est vide.";
					}
				}
			?>
			<input type="email" name="adresse_e-mail" placeholder="<?php echo FIELD_EMAIL; ?> *" autocomplete="off" required autofocus /><br/>
			<input type="password" name="password" placeholder="<?php echo FIELD_PASSWORD; ?> *" autocomplete="off" required/><div id="password_forgotten" class="tooltip"><a href="#">?<span><?php echo CONNEXION_PASSWORD_FORGOTTEN; ?></span></a></div><br/>
			<input type="submit" name="check-login" class="form_bouton" value="<?php echo CONNEXION_BUTTON; ?>"/>
		</form>
	</div>