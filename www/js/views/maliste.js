$(document).ready(function() {
	$('#formatList .btn').click(function() {
		$('#formatList .active').removeClass('active');
		$(this).addClass('active');
		var type = $(this).data('type');
		console.log(type);
		$('#list > ul').prop('class', type);
	});

});