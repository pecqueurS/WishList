$(document).ready(function() {
	$('#formatList .btn').click(function() {
		$('#formatList .active').removeClass('active');
		$(this).addClass('active');
		var type = $(this).data('type');
		$('#list > ul').prop('class', type);
	});

	var htmlContent = '<div id="cost-form" class="container-form">\
							<label id="cost-label" class="label-form" for="cost">Prix : </label>\
							<input type="text" name="cost" id="cost" class="input" placeholder="9.99" value="">\
							<span id="cost-information" class="information-form"> €</span>\
						</div>';
	var params = {
		title: 'Modification du prix',
		htmlContent: htmlContent,
		accept: function(){
			var cost = parseFloat($('#cost').val());
			if (cost == '' || cost == 0) {
				return false;
			} else {
				var post = {
					id: $('#cost').data('id'),
					cost: cost
				}
				console.log(post);
			}
		}
	};
	$('.action .cost').modal(params).click(function() {
		var id = $(this).parents('.action').parents('li').data('id');
		var oldCost = $(this).parents('.action').parents('li').find('.informations').data('cost');
		$('#cost').attr('data-id', id).val(oldCost);
	});

});