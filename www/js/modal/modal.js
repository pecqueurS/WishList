$.fn.modal = function(parameters) {
	var that = $(this);
	var modal, popup;
	
  	that.parameters = {
		title: false, // false or text
		htmlContent: '',
		hideable: true,
		validName: 'Valider',
		cancelName: 'Annuler',
		accept: function(){}, 
		cancel: function(){}
  	};

  	that.init = function() {
		modal = $('<div></div>');
		modal.addClass('modalElement');
		popup = $('<div></div>');
  	}

  	that.create = function() {
  		that.init();
  		$.extend(that.parameters, parameters);
  		that.createBackground();
  		that.createPopup();
		$('body').append(modal);
  	};

  	that.createBackground = function() {
		var background = $('<div></div>');
		background.addClass('background')
			.css({
				position: 'fixed',
				top: '0',
				left: '0',
				width: '100%',
				height: '100%',
				background: 'rgba(0,0,0,0.5)'
			});
		modal.append(background);
  	}

  	that.createPopup = function() {
		popup.addClass('popup')
			.css({
				position: 'fixed',
				top: '15%',
				left: '20%',
				width: '60%',
				height: '70%',
				background: 'white',
				borderRadius: '4px',
				boxShadow: '0px 2px 3px 0px black'
			});

		that.createHeader();
		that.createFooter();
		that.createContent();

		modal.append(popup);
  	}

  	that.createHeader = function() {
		if (that.parameters.title !== false) {
			var header = $('<header></header>');
			header.css({
				position: 'absolute',
				top: '0',
				left: '0',
				background: 'white',
				margin: '5px',
				padding: '10px',
				width: 'calc(100% - 30px)',
				height: '45px',
				overflow: 'hidden',
				borderBottom: '1px solid #78a9c0'
			});
			var title = $('<h2></h2>');
			title.html(that.parameters.title);

			header.append(title);
			popup.append(header);
		}
  	}

  	that.createFooter = function () {
		var footer = $('<footer></footer>');
		footer.css({
			position: 'absolute',
			bottom: '0',
			left: '0',
			background: 'white',
			margin: '5px',
			padding: '10px',
			width: 'calc(100% - 30px)',
			height: '40px',
			overflow: 'hidden',
			borderTop: '1px solid #78a9c0'
		});
		
		footer.append(that.createBtn(that.parameters.validName, 'fa-check', 'valid'));
		footer.find('.valid').css('float', 'right');
		
		if (that.parameters.cancel !== false) {
			footer.append(that.createBtn(that.parameters.cancelName, 'fa-close', 'delete'));
			footer.find('.delete').css('float', 'left');
		}
		
		popup.append(footer);
  	}

  	that.createContent = function() {
		var content = $('<article></article>');
		content.html(that.parameters.htmlContent)
			.css({
				position: 'absolute',
				top: that.parameters.title === false ? '0' : '70px',
				left: '0',
				margin: '5px',
				padding: '10px',
				width: 'calc(100% - 30px)',
				height: that.parameters.title === false ? 'calc(100% - 95px)' : 'calc(100% - 165px)',
				overflow: 'auto'
			});
		popup.append(content);
  	}

  	that.createBtn = function(name, icon, classes) {
  		var button = $('<button></button');
  		button.addClass('btn ' + classes)
  			.attr({
  				type: 'button',
  				name: classes
  			})
  			.html('<i class="fa ' + icon + ' fa-fw"></i> ' + name);

  		return button;
  	}

  	that.destroy = function() {
  		modal.remove();
  		that.init();
  	}
  	
  	that.on('click',function() {
  		that.create();
  	});

  	$('body').on('click', '.modalElement .background', function() {
  		if (that.parameters.hideable === true) {
  			that.destroy();
  		}
  	});

  	$('body').on('click', '.modalElement .btn.delete', function() {
  		that.parameters.cancel();
  		that.destroy();
  	});

  	$('body').on('click', '.modalElement .btn.valid', function() {
  		if (that.parameters.accept() !== false) {
  			that.destroy();
  		}
  	});

 	return $(this);
}