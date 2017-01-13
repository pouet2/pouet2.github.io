<?php

	// TODO - Créer l'adresse contact@e-lico.net
	// TODO - Récupérer la lang du visiteur pour adapter le message de confirmation de traitement + la langue utilisée en réponse.
	// TODO - Mettre tous les textes dans des fichiers lang !

	include_once '../config/config.php';

	//Envoi d'un mail pour E-liCo
	$subject= EMAIL_CONTACT_TITLE.$_POST['title'];
	
	$message  = "Prénom NOM du visiteur : ".$_POST['firstname']." ".$_POST['name']."<br/><br/><br/>";
	$message .= "<p>".$_POST['message']."</p>";

	$headers =  "From: ".$_POST['adresse_e-mail']."\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	
	mail(EMAIL_CONTACT_ADRESS_RECEPTION, $subject, $message, $headers);

	
	//Envoi d'une confirmation au client
	$title_automatique = "E-liCo - Traitement de votre demande d'information";
	$message_automatique = file_get_contents("./reponse_automatique.html");

	$headers =  "From: ".EMAIL_CONTACT_ADRESS_RECEPTION."\r\n";
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

	mail($_POST['adresse_e-mail'], $title_automatique, $message_automatique, $headers);

	// TODO - Traiter les cas d'e-mails non reçus => Annuler la demande.

	// Redirection vers la page du formulaire de prise de contact
	header('Location: '.EMAIL_CONTACT_URL_REDIRECTION );
	exit;
	
?>
