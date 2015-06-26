/*****************************************************
 * Clase JavaScript para utilizar ventanas modales
 * v1.0.0
 * Dependencias: jquery.nestable
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES C.A.
 * Basado en utilidades de Bootstrap
 * Para su uso sólo con autorización.
 *****************************************************/
var IF_MODAL =
{
	_setSm: function()
	{
		$('#myModal > div').removeClass('modal-lg');
		$('#myModal > div').addClass('modal-sm');
	}
	,
	_setLg: function()
	{
		$('#myModal > div').removeClass('modal-sm');
		$('#myModal > div').addClass('modal-lg');
	}
	,
	alert: function(m)
	{
		IF_MODAL._setSm();
		
		var msg		=	'<p>' + m + '<p>';
		var header	=	'<div class="modal-header">'+
							'<a class="close" data-dismiss="modal"></a>'+
						'</div>';
		var body	=	'<div id="ifModal" class="modal-body">' + msg + '</div>';
		var footer	=	'<div class="modal-footer">'+
							'<a data-dismiss="modal" class="btn">Cerrar</a>'+
						'</div>';
		
		
		$('#ifModal-content').html( header + body + footer );
		
		$('#myModal').modal('show');
	}

	,confirm: function(m,callback)
	{
		IF_MODAL._setSm();
		var msg		=	'<p>' + m + '<p>';
		var body	=	'<div id="ifModal" class="modal-body">' + msg + '</div>';
		var footer	=	'<div class="modal-footer">'+
						'</div>';
					
		$('#ifModal-content').html( body + footer );
		
		$('<button class="btn">Aceptar</button>')
					.on('click',function(){
						$('#myModal').modal('hide');
						callback(true);
					}).appendTo(".modal-footer");
	
		$('<button class="btn">Cancelar</button>')
					.on('click',function(){
						$('#myModal').modal('hide');
						callback(false);
					}).appendTo(".modal-footer");

		$('#myModal').modal('show');
		
	}
	
	,close: function()
	{
		
		$('#myModal').modal('hide');
	}
	
	/*
	 * type: success, info, warning, danger
	 */
	,osd: function (msg, type, timeout)
	{
		type = type||'info';
		
		$('#alerts').html('<div class="alert alert-'+type+'">'  + 
			'<button type="button" class="close" data-dismiss="alert"></button>' + msg + '</div>');
		
		timeout = timeout||2000;
		
		window.setTimeout(function ()
		{
			IF_MODAL._close_osd();
		}, timeout);
	}
	,_close_osd : function()
	{
		$('#alerts').html('');
	}
	
	,ajaxCntrllr: function (cntrllr)
	{
		IF_MODAL._setLg();

		var header	=	'<div class="modal-header">'+
							'<a class="close" data-dismiss="modal"></a>'+
						'</div>';
		var body	=	'<div id="ifModal" class="modal-body"></div>';

		$('#ifModal-content').html( header + body );
		
		var cnf = {
			controller : cntrllr,
			target : '#ifModal'
		}

		IF_MAIN.loadCompos(cnf);

		$('#myModal').modal('show');
	}
};