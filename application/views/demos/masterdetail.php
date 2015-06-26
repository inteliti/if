<?php include PLUGINS_PATH . "if.masterdetail/_loader.php";  ?>

<div id=md-mt>
	<div id="mtTbar" class=cwfTbar></div>
	<table id="mt" class='cwfTable'></table>
	<div id="mtPager"></div>
</div>
<div id=md-detail>
	<div class="wrap"></div>
</div>

<script>
	MD = new IF_MD();

	MD.init({
		url: '<?=APP_URL?>views/demos/data.json',
		colNames: [
			'Inv No', 'Date', 'Client', 'Amount',
			'Tax', 'Total', 'Notes'
		],
		colModel: [
			{name: 'id', index: 'id', width: 55},
			{name: 'invdate', index: 'invdate', width: 90},
			{name: 'name', index: 'name asc, invdate', width: 100},
			{name: 'amount', index: 'amount', width: 80, align: "right"},
			{name: 'tax', index: 'tax', width: 80, align: "right"},
			{name: 'total', index: 'total', width: 80, align: "right"},
			{name: 'note', index: 'note', width: 150, sortable: false}
		],
		sortname: 'id',
		viewrecords: true,
		sortorder: "desc",
		detail: {
			url: '<?=APP_URL?>views/demos/detail.php'
		},
		onSelectRow: function(id)
		{
			MD.loadDetail({
				url: '<?=APP_URL?>views/demos/detail.php?id=' + id
			})
		}
	})

</script>