$.fn.hmbrgrmenu = function (options)
{
	$(this)
		.addClass('hmbrgrmenu')
		.append('<span></span><span></span><span></span><span></span>')
		.click(function () {
			$(this).toggleClass('open');
		})
		;
	if (options.color)
	{
		$(this).find('span').css('background', options.color);
	}
};