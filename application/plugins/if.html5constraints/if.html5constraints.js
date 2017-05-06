/****************************************
 * V2.0.2
 * Por Gregorio Bolivar para Intelliti Framework
 * 
 * Validador de formularios que hace uso de los parametros 
 * y funciones nativas de HTML5 Constraints, ej. required, pattern, email, etc
 * Obviamente este plugin no es compatible con navegadores que no
 * soporten validación nativa HTML5 (p.e. < IE8)
 * 
 * Debe llamarse sobre el formulario completo, p.e.:
 * $("#formulario").ifValidate();
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
		this.find('input,select,textarea,checkbox,radio')
			.not(':hidden')
			.each(function (i)
			{
				var $this = $(this)
					, $parent = $this.parent()
					, validityObj = this.validity
					;

				$parent.removeClass('has-error');
				
				if ($parent.is('.input-group'))
				{
					$parent.siblings('small.help-block.error').remove();
				} else
				{
					$parent.find('small.help-block.error').remove();
				}
				if(i===0) $this.focus();
				if (!validityObj.valid)
				{
					var errMsg = '<small class="help-block error">'
						+ this.validationMessage
						+ '</small>'
						;

					$parent.addClass('has-error');

					if ($parent.is('.input-group'))
					{
						$parent.after(errMsg);
					} else
					{
						$parent.append(errMsg);
					}

				}
			});

		return false;
	}
	return true;
};