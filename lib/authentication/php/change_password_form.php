		<?php include_once 'lib/authentication/lang/'.$_SESSION['lang'].'.lang.php'; ?>
		
		<div id="change_password_form">
			<form action="<?php echo COMMON_URL; ?>/lib/authentication/php/change_password.php" method="post">
				<input type="password" name="old_password" placeholder="Ancien mot de passe *" autocomplete="off" required /><br/>
				<input type="password" name="new_password_1" placeholder="Nouveau mot de passe *" autocomplete="off" required />
				<input type="password" name="new_password_2" placeholder="Confirmation du nouveau mot de passe *" autocomplete="off" required /><br/>
				<input type="submit" value="MODIFIER" />
			</form>
		</div>