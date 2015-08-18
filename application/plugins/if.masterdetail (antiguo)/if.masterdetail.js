/*****************************************************
 * Clase JavaScript de Maestro Detalle
 * v1.0.0
 * Dependencias: jqGrid
 * Derechos Reservados (c) 2014 INTELITI SOLUCIONES C.A.
 * Basado en cwf.masterdetail
 * Para su uso sólo con autorización.
 *****************************************************/

var IF_MD = function() {};


IF_MD.prototype = {

	GRID: null,
	SELECTED_ID: null,
	
	init: function(cnf) {
		this.initMT(cnf);
		if (cnf.detail)
			this.loadDetail(cnf.detail);
		return this;
	}

	, initMT: function(cnf)
	{
		if (!cnf.tbar) {
			cnf.tbar = [];
		}
		
		if (!cnf.selectorTbar) {
			cnf.selectorTbar = "#md-mt #mtTbar";
		}
		
		if (!cnf.selectorPager) {
			cnf.selectorPager = "#mtPager";
		}
		
		if (!cnf.selectorMT) {
			cnf.selectorMT = "#md-mt #mt";
		}
		
		var
				jTbar = $(cnf.selectorTbar),
				i = 0, l = cnf.tbar.length
				;

		while(i < l)
		{
			jTbar.append(cnf.tbar[i++]);
		}

		cnf.datatype = 'json';
		cnf.mtype = 'POST';
		cnf.rowNum = 250;
		cnf.rowList = [100, 250, 350];
		cnf.viewrecords = true;
		//cnf.gridComplete = CWF_MD._autoSelect;
		cnf.pager = cnf.selectorPager;
		//cnf.autowidth = true;
		//cnf.height = '100%';

		if (cnf.controller)
			cnf.url = IF_MAIN.CI_INDEX + cnf.controller;

		if (!cnf.postData)
			cnf.postData = {};
		cnf.postData._ajax_auth = 1;
		
		this.GRID = $(cnf.selectorMT).jqGrid(cnf);
		this.GRID.jqGrid('bindKeys');

		return this.resizeMT();
	}
	
	, loadDetail: function(C)
	{
		C.target = "#md-detail .wrap";
		IF_MAIN.loadCompos(C);
	}
	
	, loadDetailIn: function(C, target)
	{
		C.target = target;
		IF_MAIN.loadCompos(C);
	}
	
	, reloadMt: function()
	{
		this.GRID.trigger('reloadGrid');
	}
	
	, resizeMT: function(ev)
	{
		if (this.GRID)
		{
			
			var wrapHeight = $('#md-mt').height();
			var tbarHeight = $('.ui-state-default').height();
			var pagerHeight = $('#mtPager').height();
		

			this.GRID.setGridHeight(wrapHeight - (tbarHeight) - (pagerHeight) - 26);
			
			this.GRID.setGridWidth($("#md-mt").width());
			
			var that = this;
			
			$(window).bind('resize', function() {
				
						var wrapHeight = $('#md-mt').height();
						var tbarHeight = $('.ui-state-default').height();
						var pagerHeight = $('#mtPager').height();
						that.GRID.setGridHeight(wrapHeight - (tbarHeight) - (pagerHeight) - 26);
						that.GRID.setGridWidth($('#md-mt').width());
						
					}).trigger('resize');

		}
		return this;
	}

	, setMTSelection: function(id, onselectrow)
	{
		if (!id)
		{
			this.resetMTSelection();
		}
		else
		{
			this.GRID.setSelection(id, onselectrow);
			this.SELECTED_ID = id;
		}
		return this;
	}

	, resetMTSelection: function()
	{
		this.SELECTED_ID = false;
		this.GRID.resetSelection();
		return this;
	}

	, cleanUp: function()
	{
		if (this.GRID)
		{
			this.GRID.empty().remove();
			this.GRID = null;
		}
		return this;
	}
	
	/** Metodo para ocultar/mostrar el filtro **/
	, toggleFilter: function()
	{
		$('#md-mt').toggleClass( 'filter-open' );
		$('#md-filter').toggleClass( 'active' );
		this.resizeMT();
		
	}

	, filter: function(forceData, _f)
	{
		if(!forceData)
		{
			forceData = IF_MAIN.serialize('#md-filter',1,'~LIKE~','||');
		}
		
		_f = $('#md-filter').data('callback');
		
		this.GRID.setGridParam({
			gridComplete: _f ? function(){
				_f(forceData)
			} : null
		});
		
		var postData = {
			filter: forceData
		};
		
		//agregar parametro de proteccion csfr
		if(IF_MAIN.CSFR_NAME.length>0)
		{
			postData[IF_MAIN.CSFR_NAME] = IF_MAIN.CSFR_TOKEN;
		}
		
		this.GRID.setGridParam({
			postData: postData
		}).trigger('reloadGrid');
	}
	
	, getFilterData: function()
	{
		return IF_MAIN.serialize('#md-filter',1,'~LIKE~','||');
	}
	
	, resetFilter: function(_f)
	{
		$('#md-filter input').val('');
		$('#md-filter select').val('');
		
		_f = $('#md-filter').data('callback')
		this.GRID.setGridParam({
			gridComplete: _f? function(){
				_f('')
			} : null
		})
		
		this.GRID.setGridParam({
			postData: {
				filter: ''
			}
		}).trigger('reloadGrid');
	}
	
	, setFilter: function(items, callback, keepHidden)
	{
		var filter_html = '<div id="filter-wrap"><div id="filter-toggle"><button id=btn-toggle-filtrar class="btn pull-right btn-xs btn-default"><span class="icon icon-filter"></span></button></div><div class="content"><div id="top-filter"><h4>Filtrado</h4><span>Llene solamente los campos por los que desea filtrar.</span></div><div id="filter-fields"><ul></ul></div><div id="bottom-filter"><button id=btn-filtrar class="btn btn-success pull-right btn-xs ">Filtrar</button><button id=btn-reset-filtrar class="btn pull-right btn-xs btn-info">Reset</button><br class=clear /></div></div></div>';
		
		var $filter	= $('#md-filter');
		$filter.html(filter_html);
		var $ul	= $filter.find('ul').empty();
		
		for(var i in items)
		{
			$ul.append('<li><label>'+i+'</label>'+this._buildFilterField(items[i])+'</li>');
		}
		$ul.find('input').keypress(function(ev)
		{
			if(ev.which==13) this.filter();
		});
		$ul.find('select').css('visibility','visible');
		
		if(callback)
		{
			$('#md-filter').data('callback',callback);
		}
		else
		{
			$('#md-filter').removeData('callback');
		}
		
		$('#md-mt').toggleClass( 'has-filter' );
		this.resizeMT();
		$('#md-filter').toggle();
		
		var that = this;
		
		$('#btn-toggle-filtrar').on('click',function(){
			that.toggleFilter();
		});
		
		$('#btn-filtrar').on('click',function(){
			that.filter();
		});
		
		$('#btn-reset-filtrar').on('click',function(){
			that.resetFilter();
		});
		
		
		$('#md-filter [xtype=date]')
		.datepicker({
			dateFormat: IF_MAIN.DATEPICKER_FORMAT,
			onClose: function (dateText, inst) {
				this.select();
			}
		});
		
		if(!keepHidden) this.toggleFilter();
	}
	
	, _buildFilterField: function(item)
	{
		var i=0;
		switch (item.type)
		{
			case 'text':
				return '<input type=text class="form-control input-sm" name="' + item.name + '" />';
				break;
			case 'date':
				return '<input type=text class="form-control input-sm" name="' + item.name + '" xtype=date />';
				break;
			case 'date_from':
				return '<input type=text class="form-control input-sm" name="' + item.name + '__from" xtype=date />';
				break;
			case 'date_to':
				return '<input type=text class="form-control input-sm" name="' + item.name + '__to" xtype=date />';
				break;
			case 'select':
				var select = '<select  class="form-control input-sm" name="' + item.name + '" >';
				for (i=0; i<item.options.length; i++)
				{
					select = select + '<option value="'+item.options[i].value+'">'+item.options[i].label+'</option>';
				}
				select = select + '</select>';
				return select;
				break;
			case 'checkbox':
				var checkbox = '';
				for (i=0; i<item.options.length; i++)
				{
					var name = item.options[i].extraname ? item.options[i].extraname : item.name;
					checkbox = checkbox + '<input type="checkbox" name="' + name + '" value="'+item.options[i].value+
						'"> <span>'+item.options[i].label+'</span><br>';
				}
				return checkbox;
				break;
			default:
				return '<label>error<label>';
		}
	}
	
	
};