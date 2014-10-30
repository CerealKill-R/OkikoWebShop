<?php
/**
 * Le fichier post.php test les champs du formulaire de façon séquentiel
 * 1.	SI LE CHAMP EST VIDE on utilise la fonction msg()
 * 		pour renvoyer une réponse sous forme d'objet JSON au fichier ajaxPost.js
 * 		On interrompt le script
 * 2.	SI LE CHAMP N'EST PAS VIDE on sanitize son contenu et on le stock dans une variable
 * 		Dans le cas de l'email on test également si il est valide
 * 3.	Si tous les champs sont valides on crée une query que l'on envois à la DB (il manque le script de connexion)
 * 		
 */


/**
 * Envois un message de réponse, sous forme d'objet JSON,
 * qui est récupéré par la méthode AJAX de jQuery (ajaxPost.js)
 * 
 * @param  [string] $txt [le message]
 * @param  [string] $class [la classe CSS utilisée pour afficher le message]
 * @return [none]
 */
function msg($txt, $class) {
	$response_array['msg'] = $txt;
	$response_array['class'] = $class;
	echo json_encode($response_array);
}

//Check du type de serveur pour passage à la ligne

if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
{
	$passage_ligne = "\r\n";
}
else
{
	$passage_ligne = "\n";
}


// ------------------------------------------------
// Nom
// ------------------------------------------------
if( empty($_POST['name']) ){

	// 1. le champ est vide, on fait un echo d'un message...
	msg('Le champ "Nom" est requis', 'required');

	// ... et on arrête le script
	return;

}else{

	// 2. le champ n'est pas vide, on prépare la chaîne de caractères pour la DB
	$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);

}



// ------------------------------------------------
// Prénom
// ------------------------------------------------
if( empty($_POST['surname']) ){

	msg('Le champ "Prénom" est requis', 'required');
	return;

}else{

	$surname = filter_var($_POST['surname'], FILTER_SANITIZE_STRING);

}

$fullName = $name." ".$surname;

// ------------------------------------------------
// EMAIL
// ------------------------------------------------
if( empty($_POST['email']) ){

	msg('Le champ "Email" est requis', 'required');
	return;

}else{

	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

	// 2b. dans le cas de l'email on vérifie que c'est bien une adresse valide
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		msg("L'adress email n'est pas valide", 'required');
		return;
	}

}

// ------------------------------------------------
// Sujet
// ------------------------------------------------
if( empty($_POST['subject']) ){

	msg('Le champ "Sujet" est requis', 'required');
	return;

}else{

	$subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);

}


// ------------------------------------------------
// MESSAGE
// ------------------------------------------------
if( !empty($_POST['about']) ){

	$about = filter_var($_POST['about'], FILTER_SANITIZE_STRING);

}else{

	$about = "";
}


// ------------------------------------------------
// PREPARATION DU MAIL
// ------------------------------------------------

$message = "Le message suivant vous a été envoyé sur www.horlogerie-lakaye.fr\n"
        . "DE : ".$fullName." (".$email.")\n"
        . "CONCERNE : ".$subject."\n"
        . "_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-\n"
        . "".$about; 
require 'PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer();
$mail->CharSet = 'UTF-8';
// Set PHPMailer to use the sendmail transport
//$mail->isSendmail();
//Set who the message is to be sent from
$mail->setFrom($email, $fullName);
//Set an alternative reply-to address
$mail->addReplyTo($email, $fullName);
//Set who the message is to be sent to
$mail->addAddress('contact.me@julienremy.be', 'Lakaye Muriel');
//Set the subject line
$mail->Subject = "Message via votre site web HORLOGERIE-LAKAYE.FR";
$mail->Body     = $message;
$mail->WordWrap = 50;


//send the message, check for errors
if (!$mail->send()) {
    msg('Votre message n\'a pu nous être transmis. '.'<br/>'.'Veuillez réessayer.', 'required');
} else {
    msg('Votre message nous a bien été transmis. '.'<br/>'.'Nous y donnerons suite dans les meilleurs délais.'.'<br/>'
        . 'Merci pour votre intêret.', 'valid');
}

?>