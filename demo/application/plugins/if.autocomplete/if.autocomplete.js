var IF_AUTOCOMPLETE =
{

	selector : null,
	onSelect : null,	
	controller : null,
	

	init: function(cnf)
	{

		if( typeof cnf.selector == "undefined" )
		{
			console.log('debe indicar un campo input para el autocompletar');
			return false;
		}
		else
		{
			IF_AUTOCOMPLETE.selector = cnf.selector;
		}
		
		if( typeof cnf.minLength == "undefined" )
		{
			cnf.minLength = 3;
		}
		
		if( typeof cnf.highlight == "undefined" )
		{
			cnf.highlight = true;
		}
		
		if( typeof cnf.hint == "undefined" )
		{
			cnf.hint = true;
		}
		
		if( typeof(cnf.controller) == "undefined" && typeof(cnf.source) != "function" )
		{
			console.log('debe indicar un controlador para hacer la consulta o definir una funcion source');
			return false;
		}
		else
		{
			IF_AUTOCOMPLETE.controller = cnf.controller;
		}
		
		if( typeof(cnf.source) != "function" )
		{
			cnf.source = IF_AUTOCOMPLETE._source;
		}
		
		if( typeof(cnf.templates) == "undefined" )
		{
			cnf.templates = {
				empty: [
					'<div class="empty-message">',
					'No se pudieron conseguir resultados que coincidan con su patron de búsqueda',
					'</div>'
				].join('\n'),
				suggestion: Handlebars.compile('<p>{{value}}</p>')
			}
		}
		else
		{
			if(typeof(cnf.templates.empty) != "undefined")
				cnf.templates.empty = Handlebars.compile(cnf.templates.empty);
				
			if(typeof(cnf.templates.footer) != "undefined")
				cnf.templates.footer = Handlebars.compile(cnf.templates.footer);
				
			if(typeof(cnf.templates.header) != "undefined")
				cnf.templates.header = Handlebars.compile(cnf.templates.header);
				
			if(typeof(cnf.templates.suggestion) != "undefined")
				cnf.templates.suggestion = Handlebars.compile(cnf.templates.suggestion);
			
		}
		
		$(cnf.selector).typeahead({
			minLength: cnf.minLength,
			highlight: cnf.highlight,
			hint: cnf.hint
		},
		{
			name: 'if-autocomplete',
			source: cnf.source,
			templates: cnf.templates
		});
		
		$(cnf.selector).parent('.twitter-typeahead').append('<div id="if-autocomplete-spinner"></div>');
		
		if( typeof(cnf.onSelect) == "function" )
		{
			IF_AUTOCOMPLETE.onSelect = cnf.onSelect;
			$(cnf.selector).on('typeahead:selected',IF_AUTOCOMPLETE._onSelect);
		}
		
		return true;
	}
	
	,
	
	_source : function(query, process)
	{
		$('#if-autocomplete-spinner').show();
		var cnf = {
			controller: IF_AUTOCOMPLETE.controller,
			data: {query: query},
			callback : function(r)
			{
				$('#if-autocomplete-spinner').hide();
				process(r);
			}
		}
		
		IF_MAIN.ajax(cnf);
	}
	
	
	,
	
	
	_onSelect : function(obj, data, name)
	{
		IF_AUTOCOMPLETE.onSelect(data);
	}
	
	
	
};