jQuery(function($) {
	$('li.ui-state-default, a.ui-state-default, input.ui-state-default, div.ui-state-default, span.ui-state-default')
		.live('mouseenter', function(){
			$(this).addClass('ui-state-hover');
		})
		.live('mouseleave', function(){
			$(this).removeClass('ui-state-hover');
		});
	$('span.ui-button-link').live('click', function(e){
		if ($(e.target).is('span')) {
			$(this).find('a, input').click();
		}
	});
	$('input.datepicker').datepicker();

	$('.test-name').each(function() {
		var $this = $(this),
			content = $this.html(),
			suite = content.split('::')[0],
			test = content.split('::')[1],
			suitelink = $('<a>').attr('href', '/run/suite/' + suite).html(suite),
			testlink = $('<a>').attr('href', '/run/test/' + suite + '/' + test).html(test);

		$this.html('::').prepend(suitelink).append(testlink);
	});
});
