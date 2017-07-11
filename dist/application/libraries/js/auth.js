/******************************************************************.
 * 
 * auth.js
 * 
 * functions for user authorization
 * 
 * Dependences: libraries/app/sys.js, 
 *				plugins/if.hotkeys
 * 
 * Copyright (c) 2016 INTELITI SOLUCIONES, C.A.
 * For use only with permission
 * 
 *****************************************************************/

var AUTH = {
	
	init: function()
	{
		var action = $('body').data('action');
		//console.log(action);
		
		if(action === null)
			location.href = IF_MAIN.CI_INDEX;

		if(action === '' || action === 'login')		//form login
		{
			AUTH.login.init();
			SYS.button.event('#btn', AUTH.login.submit, 'enter');
		}

		$(":input:first").focus(); 
	},
	
	//--------------------------------------------------
	// Login functions 
	//--------------------------------------------------
	login: {
		
		init: function()
		{
			IF_MAIN._form_validate(
			'#login_form', 
			{
				rules:{
					usuario:	'required',
					pass:		'required',
					captcha:	'required'
				}
			});
		}
		
		,
		submit: function()
		{
			//validation
			if(!$('#login_form').valid())
			{
				return false;
			}
			
			if($('#password').val())
			{
				var md5 = hex_md5($('#password').val());
				$('#password, #repassword').val('').attr('disabled', true);
				$('#md5').val(md5);
			}
			
			//console.log($('#login_form').serializeArray());

			IF_MAIN.loadCompos({
				target:		'#wrapper-login',
				controller:	'auth/login',
				data:		$('#login_form').serializeArray(),
				callback: function()
				{
					AUTH.init();
				}
			});
		}	
	},
	
	logout: function()
	{
		window.open(IF_MAIN.CI_INDEX + 'auth/logout', "_self");
	}
};

$(document).ready(function() {
    AUTH.init();
});

