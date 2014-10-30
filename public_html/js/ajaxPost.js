$(function(){

	// On écoute l'event "submit" du formulaire
	$('#contactForm').on('submit',function(evt) {

		// Empêche le formulaire d'envoyer ses données par lui même
		evt.preventDefault();

		// Cache le message d'erreur
		$("#msg").hide();


		// Traite les données du formulaire via AJAX
		$.ajax({
			url:'post.php', // L'url du fichier PHP qui traite les données du formulaire
			data:$(this).serialize(), // On sérialise le data qu'on envois au PHP ex:name=leNom&surname=lePrénom&...
			type:'POST', // La méthode d'envois


			// La méthode .ajax() possède une série de listeners qui permettent de suivre la progression de la requête
			// cf : http://api.jquery.com/Ajax_Events/

			// Si le fichier PHP a répondu avec succès...
			success:function(data){

				if (data){ // Juste une petite précaution, si le php ne renvois rien on aura une erreur avec jQuery

					// ... on parse le JSON reçu du fichier 'post.php' en objet utilisable par jQuery
					var data = jQuery.parseJSON( data );

console.log(data.msg);

					// Affiche les messages d'erreur récupérés du fichier PHP
					$("#msg")
						.html(data.msg)
						.addClass(data.class)
						.fadeIn(1000);
				}
			},

			// Catch les erreurs lors de l’envoi
			// ex : modifier le nom du fichier 'post.php' en 'pos.php' retourne une erreur 4O4
			error:function(data){

console.log(data);

				$("#msg")
					.html("Error " + data.status)
					.addClass('error')
					.fadeIn(1000);
			}
		});
	});
});