/*****************************************************
 * Clase JavaScript MAIN
 * v1.0.0
 * Dependencias: jquery
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES C.A.
 * Basado en cwf.main
 * Para su uso sólo con autorización.
 *****************************************************/

var IF_MAIN = {
	//customize
	//codeigniter index.php url
	CI_INDEX: '',
	DATEPICKER_FORMAT: 'dd/mm/yy',
	CANVAS_SELECTOR: '#canvas',
	INVALID_BROWSER_URL: '',
	SESSION_CHECKER_URL: '',
	//
	//---------------------------------------
	//dont touch anything below here!

	CANVAS_LOCK: 0,
	UNSAVED_DATA: 0,
	init: function(cnf)
	{
		if (!cnf) cnf = {};

		//test if the browser is compatible
		var b = IF_USER.browser, n = b.name, v = b.ver;
		if (
				!(n == 'chrome')
				&& !(n == 'firefox' && v >= 3.5)
				)
		{
			location.href = INVALID_BROWSER_URL + '/' + n + '/' + v;
			return;
		}
		IF_MAIN.startSessionChecker(cnf.sessionTimeout);

		//prepare canvas
		var bodyH = $("#body").height();
		$(IF_MAIN.CANVAS_SELECTOR).height(bodyH);
	}

	, ajax: function(cnf)
	{
		cnf.type = 'POST';
		if (!cnf.dataType)
			cnf.dataType = 'json';
		cnf.url = cnf.url || IF_MAIN.CI_INDEX + cnf.controller;
		cnf.success = cnf.callback;

		$.ajax(cnf);
	}

	//canvas monitor
	, canvasLock: function()
	{
		IF_MAIN.CANVAS_LOCK++;
	}
	, canvasUnlock: function()
	{
		IF_MAIN.CANVAS_LOCK--;
		if (IF_MAIN.CANVAS_LOCK < 0)
			IF_MAIN.CANVAS_LOCK = 0;
	}
	, canvasLocked: function()
	{
		return IF_MAIN.CANVAS_LOCK > 0;
	}
	, canvasShowLoading: function()
	{
		$(IF_MAIN.CANVAS_SELECTOR).empty()
				.append('<div class="cwfComposLoaderL"></div>')
	}

	, loadCompos: function(cnf)
	{
		var
				loaderCls = 'cwfComposLoader' + (cnf.loaderSize || 'L')
				, $target = $(cnf.target).empty()
				.html('<div class="' + loaderCls + '">&nbsp;</div>')
				.load(
				cnf.url || IF_MAIN.CI_INDEX + cnf.controller,
				cnf.data || null,
				function(resp)
				{
					if (IF_USER.browser.name != 'msie')
					{
						$target.css('display', 'none').fadeIn(175);
					}
					//$target.cwfForm();

					if (cnf.callback)
						cnf.callback(resp);
				}
		)
				;
		return 1;
	}
	
	//Revisar depende de GWF_DIALOG y GWF_HOTKEY
	, loadCanvas: function(cnf)
	{
		if (IF_MAIN.canvasLocked())
			return;
		IF_MAIN.canvasLock();

		cnf.target = IF_MAIN.CANVAS_SELECTOR;

		cnf._cb = cnf.callback;
		cnf.callback = function(r)
		{
			IF_MAIN.canvasUnlock();
			if (typeof cnf._cb == 'function')
				cnf._cb(r);
		};

		IF_MAIN.loadCompos(cnf);

		//clear past view hotkeys
		if (typeof GWF_HOTKEY != 'undefined')
			GWF_HOTKEY.clearTempAll();

		//close the dialog if opened
		if (typeof GWF_DIALOG != 'undefined')
			GWF_DIALOG.close();
	}

	, prepareFormForUnsavedData: function(sel)
	{
		$(sel + ' input,' + sel + ' textarea')
				.keypress(MAIN.setUnsavedData)
				;
		$(sel + ' select').change(MAIN.setUnsavedData);
	}

	//Revisar porque depende de CWF_DIALOG
	, confirmUnsavedData: function(callback, cbParams, scope)
	{
		if (IF_MAIN.UNSAVED_DATA)
		{
			CWF_DIALOG.confirm(
					'Se perderán los cambios no guardados. ¿Continuar?'
					, function(si)
			{
				if (si)
				{
					IF_MAIN.UNSAVED_DATA = 0;
					callback.call(scope || window, cbParams);
				}
			}
			, null
					, {
				modal: 1
			}
			);
		}
		else
			callback.call(scope || window, cbParams);
	}

	, setUnsavedData: function(status)
	{
		if (typeof status == 'object' && status.which && status.which == 27)
		{
			return;
		}

		if (typeof status == 'undefined')
			var status = true;
		IF_MAIN.UNSAVED_DATA = status;
	}

	//revisar depende de CWF_L10N
	, serialize: function(selector, returnAsStr, valueSeparator, pairSeparator)
	{
		//BUGFIX
		//takes out focus from any input to allow blur() execution
		//on inputs that need it like inputs with [xtype]
		$("<a href=#></a>")
				.appendTo('body').focus()
				.css({
			position: 'absolute',
			visibility: 'hidden',
			left: '0px'
		})
				.remove()
				;

		if (!valueSeparator)
			valueSeparator = '=';
		if (!pairSeparator)
			pairSeparator = '&';

		var obj = {}, str = [], arr;

		arr = $(selector + ' input,' + selector + ' select,' + selector + ' textarea')
				.toArray()
				;
		for (var i = 0, name, value, curr, $curr, l = arr.length; i < l; i++)
		{
			curr = arr[i];
			$curr = $(curr);

			if ($(curr).hasClass('exclude'))
			{
				continue;
			}

			//just ignore not checked radios and checkboxes
			if ((curr.type == 'radio' || curr.type == 'checkbox') && !curr.checked)
			{
				continue;
			}

			name = curr.name;

			//jquery ui datepicker?
			if ($curr.hasClass('hasDatepicker'))
			{
				var type = curr.getAttribute('type');
				if(type=="datetime")
				{
					value = $curr.datetimepicker("getDate");
					function pad(number) {
						if ( number < 10 )
						{
							return '0' + number;
						}
						return number;
					}
					value = value.getFullYear() +
					'-' + pad( value.getMonth() + 1 ) +
					'-' + pad( value.getDate() ) +
					' ' + pad( value.getHours() ) +
					':' + pad( value.getMinutes() ) +
					':' + pad( value.getSeconds() );
				}
				else
				{
					value = $curr.datepicker("getDate");
					value = value ? value.toISO8601() : '';
				}
			}
			else
			{
				value = $(curr).val();
				var xtype = curr.getAttribute('xtype');

				if (xtype == 'numeric')
				{
					value = CWF_L10N.number2Float(value);
				}
				if (xtype == 'currency')
				{
					value = CWF_L10N.currency2Float(value);
				}
			}
			obj[name] = value;
			str.push(name + valueSeparator + (value));
		}

		return returnAsStr ? str.join(pairSeparator) : obj;
	}
	/**
	 ,	showFormErrors: function(sel, obj, showAlert)
	 {
	 var $sel = $(sel);
	 
	 //process errors only once !
	 if ($sel.attr('errorsShowed'))
	 return;
	 
	 for (var i in obj)
	 {
	 var $input = $(sel + ' [name=' + i + ']');
	 
	 $input
	 .addClass('error')
	 .after('<div class=errMsg>' + obj[i] + '</div>')
	 ;
	 $input.prev('.tagLabel').addClass('error');
	 }
	 $sel.attr('errorsShowed', 1);
	 
	 if (typeof showAlert == 'undefined')
	 showAlert = 1;
	 if (showAlert)
	 {
	 CWF_DIALOG.alert(
	 'Se encontraron algunos errores, por favor verifique.'
	 , {
	 modal: 1
	 }
	 );
	 }
	 }
	 
	 ,	markError: function(sel, msg)
	 {
	 $(sel).addClass('error').after('<div class=errMsg>' + msg + '</div>')
	 }
	 
	 ,	clearFormErrors: function(sel)
	 {
	 $(sel)
	 .removeAttr('errorsShowed')
	 .find('.error')
	 .removeClass('error')
	 .next('.errMsg')
	 .remove()
	 ;
	 $(sel).prev('.tagLabel').removeClass('error');
	 }
	 
	 ,	disableForm: function(sel)
	 {
	 $(sel + ' input,' + sel + ' textarea, ' + sel + ' select')
	 .attr('disabled', 'disabled');
	 
	 }/**/

	, startSessionChecker: function(time)
	{
		if (!time)
			time = 305000;//5min 5sec

		IF_MAIN._sessChecker = window.setInterval(function()
		{
			IF_MAIN.ajax({
				controller: IF_MAIN.SESSION_CHECKER_URL
						,
				callback: function(r)
				{
					if (r != 1)
					{
						window.clearInterval(IF_MAIN._sessChecker);
						location.href = IF_MAIN.CI_INDEX + '?session_dead=1';
					}
				}
			});
		}, time);
	}
};

Date.prototype.toISO8601 = function()
{
	var day = this.getDate(), mon = this.getMonth() + 1;
	if (day < 10)
		day = '0' + day;
	if (mon < 10)
		mon = '0' + mon;
	return this.getFullYear() + '-' + mon + '-' + day;
};

var IF_USER = {};
(function()
{
	var NAV = navigator.userAgent

			//browser list - popular first
			//opmini first thatn opera: useragentstring.com/pages/Opera%20Mini/
			//version = safari, put last: useragentstring.com/pages/Safari/
			, USER_BROWSER = "msie,firefox,chrome,opera mini,opera,konqueror,epiphany,fennec,version"
			.split(",")
			, X1, X2
			;
	for (X1 = 0; X1 < USER_BROWSER.length; )
		if (
				X2 = (
				new RegExp(USER_BROWSER[X1++] + "[ /](\\d+\\.\\d+)", "i")
				).test(NAV)
				)
			break;

	X1--;

	IF_USER.browser = X2 ? {
		//safari fix
		name: USER_BROWSER[X1] == 'version' ? 'safari' : USER_BROWSER[X1]
				,
		ver: RegExp.$1
	} : {};
})();
