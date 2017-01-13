	<?php include_once 'lib/authentication/lang/'.$_SESSION['lang'].'.lang.php'; ?>

	<div id="inscription">
		<h3><?php echo INSCRIPTION_TITLE; ?></h3>
		<form action="<?php echo COMMON_URL; ?>/lib/authentication/php/inscription.php" method="post" onsubmit="return verifInscriptionForm(this)">
			<?php	if(isset($_SESSION['inscription_form_error_message'])){
						 echo $_SESSION['inscription_form_error_message'];
					if(empty($_SESSION['inscription_form_error_message'])){
						 echo "Le message d'erreur d'inscription existe mais est vide.";
					}
				}
			?>
			<input type="text" name="firstname" placeholder="<?php echo FIELD_FIRSTNAME; ?> *" required/><input type="text" name="name" placeholder="<?php echo FIELD_NAME; ?>" required/><br/>
			<input type="email" name="adresse_e-mail" placeholder="<?php echo FIELD_EMAIL; ?> *" autocomplete="off" required/><br/>
			<input type="password" name="password" placeholder="<?php echo FIELD_PASSWORD; ?> *" autocomplete="off" required/><br/>
			<input type="submit" name="submit" class="form_bouton" value="<?php echo INSCRIPTION_BUTTON; ?>" />
		</form>
	</div>