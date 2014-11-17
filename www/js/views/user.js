$(document).ready(function() {
	$('#menuList a').on('click', function() {
		var element = ($(this).attr("href"));
		$('#menuList .active').removeClass('active');
		$(this).addClass('active');

		$('#menuContent > div').hide();
		$(element).fadeIn('slow');
	});

	$('#date').datepicker({ 
		altField: "#date",
		closeText: 'Fermer',
		prevText: 'Précédent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
		monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
		dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
		dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
		dayNamesMin: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
		weekHeader: 'Sem.',
		dateFormat: 'dd-mm-yy',
		firstDay: 1

	}).mask("99-99-9999");

	var hash = window.location.hash;
	if (hash != '') {
		$('#menuList .active').removeClass('active');
		$('#menuList a[href=' + hash + ']').addClass('active');
		$('#menuContent > div').hide();
		$(hash).show();
	}
	
	//$('ul'+hash+':first').show();

});