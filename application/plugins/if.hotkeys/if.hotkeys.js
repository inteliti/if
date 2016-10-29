/******************************************************************
 * 
 * Clase JavaScript HOTKEYS basado en cwf.hotkeys
 * v1.0.0
 * 
 * Clase que define uso de hotkeys en el sistema.
 * 
 * Dependencias: Framework JQuery
 * 
 * Derechos Reservados (c) 2015 INTELITI SOLUCIONES, C.A.
 * Para su uso sólo con autorización.
 * 
 *****************************************************************/

/*
 * Hotkeys especiales
 */
var
	HOTKEY_ENTER	= 13,
	HOTKEY_ESC		= 27,
	HOTKEY_BACK		= 8
;
var IF_HOTKEY =
{
	GLOBAL	: {},
	TEMP	: {},
	ALL		: {}
	
	//-----------------------------------------------------------------
	
	/*
	 * 
	 */
	,SPECIAL_KEYS: (function()
	{
		var a = {};
		a[HOTKEY_ENTER]	= 'ENTER';
		a[HOTKEY_ESC]	= 'ESC';
		a[HOTKEY_BACK]	= 'BACK';
		return a;
	})()
	
	//-----------------------------------------------------------------
	
	/*
	 * 
	 */
	,registerGlobal: function (key, callback)
	{
		IF_HOTKEY._register(key, callback, IF_HOTKEY.GLOBAL);
	}
	
	//-----------------------------------------------------------------
	
	/*
	 * 
	 */
	,registerTemp: function (key, callback)
	{
		IF_HOTKEY._register(key, callback, IF_HOTKEY.TEMP);
	}
	
	//-----------------------------------------------------------------
	
	/*
	 * 
	 */
	,_register: function (key, cb, REPO)
	{
		key = (key+'').toUpperCase();
		var cur = IF_HOTKEY._processKey(key);
		cur.callback = cb;
		REPO[ key ] = cur;
		IF_HOTKEY._processAll();
	}
	
	//-----------------------------------------------------------------
	
	/*
	 * 
	 */
	,clearTempAll: function ()
	{
		delete(IF_HOTKEY.TEMP);
		IF_HOTKEY.TEMP = {};
		IF_HOTKEY._processAll();
	}
	
	//-----------------------------------------------------------------
	
	/*
	 * 
	 */
	,clearTemp: function (key)
	{
		IF_HOTKEY.TEMP[key] = null;
		delete(IF_HOTKEY.TEMP[key]);
		IF_HOTKEY._processAll();
	}
	
	//-----------------------------------------------------------------
	
	/*
	 * 
	 */
	,_processKey: function (key)
	{
		var index, o = {};
		
		if((index = key.indexOf('CTRL+')) >= 0)
		{
			o.ctrl = 1;
			key = key.replace('CTRL+','');
		}
		if((index = key.indexOf('SHIFT+')) >= 0)
		{
			o.shift = 1;
			key = key.replace('SHIFT+','');
		}
		if((index = key.indexOf('ALT+')) >= 0)
		{
			o.alt = 1;
			key = key.replace('ALT+','');
		}

		//special keys
		if(key=='ENTER') key=HOTKEY_ENTER;
		else if(key=='ESC') key=HOTKEY_ESC;
		else if(key=='BACK') key=HOTKEY_BACK;
		else{
			key = key.charCodeAt(0);
		}

		o.key = key;
		return o;
	}
	
	//-----------------------------------------------------------------
	
	/*
	 * 
	 */
	,_press: function (e)
	{
		var
			cur
			,k = e.which
			,type = e.target.type
			,input = (e.ctrlKey?'CTRL+':'')+(e.shiftKey?'SHIFT+':'')+
				(e.altKey?'ALT+':'')
		;
		input += IF_HOTKEY.SPECIAL_KEYS[k]||String.fromCharCode(e.which)
		
		if( cur=IF_HOTKEY.ALL[input] )
		{
			if(
				(	type==='text'
					|| type==='textarea'
					|| type==='password'
					|| type==='number'
					|| type==='email'
				)
				&& !IF_HOTKEY.SPECIAL_KEYS[k]
			) return true;
			
			if(cur.callback && typeof cur.callback === 'function')
				cur.callback();
			else
				$.noop();
			
			e.preventDefault();
			e.stopPropagation();
			e.cancelBubble = true;
			return false;
		}
		return true;
	}
	
	//-----------------------------------------------------------------
	
	/*
	 * 
	 */
	,_processAll: function()
	{
		delete(IF_HOTKEY.ALL);
		IF_HOTKEY.ALL = {};
		$.extend( IF_HOTKEY.ALL, IF_HOTKEY.GLOBAL, IF_HOTKEY.TEMP );
	}

};
$(function()
{
	$('body').keydown(IF_HOTKEY._press);
});