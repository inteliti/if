
var LOGIN = {
	init : function(cnf)
	{
		$('input').keyup(function(){ 
			return false; 
		});

		//
		IF_HOTKEY.registerTemp( 'enter' , LOGIN.submit );

		//validacion de formulario
		IF_MAIN._form_validate(
			'#login_form', 
			{
				rules:{
					usuario: 'required'
				}
			}
		);
	}
	,
	submit : function()
	{


		if($('#login_form').valid())
		{
			if($('#pass').val())
			{
				var md5 = hex_md5($('#pass').val());
				$('#md5').val(md5);
				$('#pass').val('');
				$('#pass').attr('disabled', true);
			}
			
			IF_MAIN.loadCompos({
				target: '#wrapper-login',
				controller: 'IF_Sys/login',
				data: $('#login_form').serializeArray(),
				callback: function()
				{

				}
			});
		}

		//IF_MODAL.confirm('Seguro?', function(){alert('ok');}, {width:'100%'});
	}
}


