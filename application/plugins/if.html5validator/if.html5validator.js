/****************************************
 * V1.0
 * Por Gregorio Bolivar para Intelliti Framework
 * 
 * Validador de formularios que hace uso de los parametros 
 * y funciones nativas de HTML5 Constraints, ej. required, pattern, email, etc
 * Ideal para usar con formularios que se deben enviar via AJAX.
 * Obviamente este plugin no es compatible con navegadores que no
 * soporten validación nativa HTML5 (p.e. < IE7)
 * 
 * Debe llamarse sobre el formulario completo, p.e.:
 * $("#forma").ifValidate();
 * Retorna TRUE o FALSE si el formulario es valido o no.
 * Comportamiento automatico adicional:
 *  - Marca los campos con errores con la clase de Bootstrap 'has-error'
 *  - Añade el mensaje de error nativo del navegador
 * 
 * DEPENDENCIAS:
 * El formulario debe tener markup de Bootstrap. P.e.
 * <div class="form-group">
 *		<label class="col-sm-2 control-label">
 *			E-mail
 *		</label>
 *		<div class="col-sm-10">
 *			<input type="email"
 *			class="form-control"
 *			name="email"
 *			required="required">
 *		</div>
 *	</div>
 ****************************************/
$.fn.ifValidate = function ()
{
	if (!this[0].checkValidity())
	{
		this.find('input,select,textarea,checkbox,radio').each(function (i)
		{
			var $this = $(this)
				,$parent = $this.parent()
				, validityObj = this.validity
				;
				
			$parent
				.removeClass('has-error')
				.find('small.help-block.error')
				.remove()
			;

			if (!validityObj.valid)
			{
				var errMsg = '<small class="help-block error">'
					+ this.validationMessage
					+ '</small>'
					;
					
				$parent.addClass('has-error');
				
				if($parent.is('.input-group'))
				{
					$parent.after(errMsg);
				}
				else
				{
					$parent.append(errMsg);
				}

			}
		});

		return false;
	}
	return true;
};