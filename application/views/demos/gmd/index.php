<?php
$demo = ASSETS_URL . 'gmd_demo/';
if_plugin('jquery.bigslide');
?>
<style>
	#logo{
		width: 150px;
	}
	.if_header{
		background-color: #fafafa;
	}
	.section1{
		background-color: #eee;
		height: 750px;
	}
</style>

<nav id="menu" class="if_drawer" role="navigation">
    <ul>
		<li>
			<h2>
				Demo Drawer
			</h2>
		</li>
        <li>
			<a href="#">
				<i class="fa fa-check"></i>
				Home
			</a>
		</li>
		<li>
			<a href="#">
				<i class="fa fa-check"></i>
				Seccion 1
			</a>
		</li>
        <li>
			<a href="#">
				<i class="fa fa-check"></i>
				Seccion 2
			</a>
		</li>
	</ul>
</nav>

<div class="if_drawer_push">

	<div id="header" class='if_header fixed if_z_4'>
		<img src="<?= $demo ?>logo.png" 
			 alt="" border="0" id="logo" class="if_logo" />
		<i class="if_fab if_z_noshadow">
			<i class="fa fa-bars"></i>
		</i>
	</div>
	<div class="if_header_push"></div>

	<div class="section1 if_z_2">
		<div class="container if_height100">
			<div class="row if_centerv_wrap if_height100">
				
				<div class="col-sm-12 if_centerv">
					
					<h1>
						<i class="fa fa-cogs"></i>
						Trabajando 24/7 para ud
					</h1>
					<h2>
						Con soporte a cualquier hora
					</h2>
					<h3>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod quam a porta maximus. Donec efficitur, ipsum eget blandit vestibulum, diam felis porttitor est, scelerisque varius odio erat quis libero. Curabitur feugiat tincidunt hendrerit. Cras accumsan orci vitae magna lacinia, at euismod turpis luctus.
					</h3>
					<a href="#" class="btn btn-default btn-lg"
					   onclick="IF_MODAL.alert('Dialogo')">
						Abrir diálogo
					</a>
					
				</div>
				
			</div>
		</div>
	</div>
	<div class="section2 if_z_2 if_section">
		<div class="container">
			<div class="row">
				
				<div class="col-sm-4">
					<h3>
						Cool features
					</h3>		
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod quam a porta maximus. Donec efficitur, ipsum eget blandit vestibulum, diam felis porttitor est, scelerisque varius odio erat quis libero.
					</p>
				</div>
				
				<div class="col-sm-4">
					<h3>
						Superb prices
					</h3>		
					<p>
						Donec efficitur, ipsum eget blandit vestibulum, diam felis porttitor est, scelerisque varius odio erat quis libero. Curabitur feugiat tincidunt hendrerit.
					</p>
				</div>
				
				<div class="col-sm-4">
					<h3>
						High-end tech
					</h3>		
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod quam a porta maximus. Donec efficitur, ipsum eget blandit vestibulum, diam felis porttitor est.
					</p>
				</div>
				
			</div>
		</div>
	</div>

</div>
<script>
	$(".if_fab").bigSlide({
		side: 'right',
		easyClose: true,
		menuWidth: '18em',
		push: '.if_drawer_push'
	});
</script>