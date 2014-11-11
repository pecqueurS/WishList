$(document).ready(function() {
	$('#menuList a').on('click', function() {
		var element = ($(this).attr("href"));
		$('#menuList .active').removeClass('active');
		$(this).addClass('active');

		$('#menuContent > div').hide();
		console.log($(element));
		$(element).fadeIn('slow');
	});
});