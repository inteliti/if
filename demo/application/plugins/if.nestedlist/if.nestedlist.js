/*****************************************************
 * Clase JavaScript para una lista en forma de arbol de datos
 * v1.0.0
 * Dependencias: jquery.nestable
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES C.A.
 * Para su uso sólo con autorización.
 *****************************************************/
/*****************************************************
 * Constructor
 *****************************************************/
var IF_NL = function() {};

/*****************************************************
 * Prototipo de clase IF_MD
 *****************************************************/
IF_NL.prototype = {
	
	LIST : null,
	CONTROLLER : null,
	SELECTOR_LIST : null,
	SELECTOR_DETAIL : null,
	ITEM_LABEL : null,
	ITEM_ID : null,
	onChange : null,
	onDelete : null,
	onEdit : null,
	deleteLabel : null,
	editLabel : null,
	
	init : function(cnf)
	{
		if (!cnf.selectorList)
		{
			cnf.selectorList = "#md-list";
		}
		if (!cnf.selectorDetail)
		{
			cnf.selectorDetail = "#md-detail";
		}
		if (!cnf.itemLabel)
		{
			cnf.itemLabel = "name";
		}
		if (!cnf.itemId)
		{
			cnf.itemId = "id";
		}
		if (typeof(cnf.onChange)!=="function")
		{
			cnf.onChange = function(){};
		}
		if (typeof(cnf.onDelete)!=="function")
		{
			cnf.onDelete = null;
		}
		if (typeof(cnf.onEdit)!=="function")
		{
			cnf.onEdit = null;
		}
		if (!cnf.deleteLabel)
		{
			cnf.deleteLabel = "Borrar";
		}
		if (!cnf.editLabel)
		{
			cnf.editLabel = "Editar";
		}
		this.CONTROLLER = cnf.controller;
		this.SELECTOR_LIST = cnf.selectorList;
		this.SELECTOR_DETAIL = cnf.selectorDetail;
		this.ITEM_LABEL = cnf.itemLabel;
		this.ITEM_ID = cnf.itemId;
		this.onChange = cnf.onChange;
		this.onDelete = cnf.onDelete;
		this.onEdit = cnf.onEdit;
		this.deleteLabel = cnf.deleteLabel;
		this.editLabel = cnf.editLabel;
		
		this._loadData();
	}
	,
	getData : function()
	{
		return $(this.SELECTOR_LIST).nestable('serialize');
	}
	,
	loadDetail : function(cnf)
	{
		
		cnf.target = this.SELECTOR_DETAIL;
		
		IF_MAIN.loadCompos(cnf);
	}
	,
	reloadList : function()
	{
            var parent = $(this.SELECTOR_LIST).parent();
		$(this.SELECTOR_LIST).remove();
                parent.html('<div id="'+this.SELECTOR_LIST.substr(1) +'"></div>');
		this._loadData();
	}
	,
	_initNestable : function()
	{
            var cnf = { };
            if(this.onEdit!==null) cnf.onEdit = this.onEdit;
            if(this.onDelete!==null) cnf.onDelete = this.onDelete;
			if(this.onDelete!==null) cnf.deleteLabel = this.deleteLabel;
			if(this.onDelete!==null) cnf.editLabel = this.editLabel;
            
            $(this.SELECTOR_LIST).nestable(cnf);
	}
	,
	_loadData : function()
	{
		var that = this;
		var cnf = {
			controller : this.CONTROLLER,
			callback : function(d)
			{
				var html =  that._getHTML(d) ;
				$(that.SELECTOR_LIST).addClass('dd');
				$(that.SELECTOR_LIST).html(html);
				that._initNestable();
				$(that.SELECTOR_LIST).on('change', function() {
					that.onChange();
				});
			}
		};
		IF_MAIN.ajax(cnf);
	}

	,
	_getHTML : function(data)
	{
		var html = '';
		
		html += '<ol class="dd-list">';
		for(item in data){
			html += '<li class="dd-item" data-id="'+ data[item][this.ITEM_ID] +'">';
			if(typeof(data[item].children) === 'object')
			{
				html += '<div class="dd-handle">' + data[item][this.ITEM_LABEL]+ '</div>';
				if(data[item].children.length>0)
				{
					html += this._getHTML(data[item].children, true);
				}
			}
			else 
			{
				html += '<div>' + data[item][this.ITEM_LABEL] + '</div>';
			}
			html += '</li>';
		}
		html += '</ol>';
		
		return html;
	}
};


