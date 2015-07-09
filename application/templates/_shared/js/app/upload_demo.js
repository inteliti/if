var UPLOAD_DEMO = {
	detail: function(id)
	{
		IF_MAIN.loadCompos({
			target:'#upload-detail',
			controller: 'upload/detail/'+id,
			callback: function(){
				
			}
		});
	}
	,
	save: function(form)
	{
		
		$(form).validate();
		
		if( $(form).valid() )
		{
			var data = IF_MAIN.serialize(form);
			
			var cnf = {
				controller : 'upload/save',
				target:'#upload-detail',
				data : data,
				callback : function()
				{
					var success = $('input[name=success]').val();
					if(success)
					{
						SLIDER.nuevo();
					}
				}
			}
			
			IF_MAIN.loadCompos(cnf);
		}
		else
		{
			console.log('no es valido');
		}
	}
}

