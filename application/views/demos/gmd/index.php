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
		background-color: #f0f0f0;
		height: 500px;
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
					
					<h1 class="lead">
						IF { Material Designs }
					</h1>
					<p class="lead">
						Demo de el nuevo IF en Bootstrap 3.0,
						con implementaciones basadas en
						Google Material Designs.
					</p>
					<a href="#" class="btn btn-raised btn-primary"
					   onclick="IF_MODAL.alert('Dialogo')">
						Abrir alert()
					</a>
					<a href="#" class="btn btn-raised"
					   onclick="IF_MODAL.confirm('¿Confirmas que te gusta el nuevo IF { Material Designs }?')">
						Abrir confirm()
					</a>
					
				</div>
				
			</div>
		</div>
	</div>
	<div class="section2 if_section">
		<div class="container">
			<div class="row">
				
				<div class="col-sm-4 text-center">
					<h1>
						<i class="fa fa-bolt"></i>
					</h1>
					<h3>
						
						Cool features
					</h3>		
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod quam a porta maximus. Donec efficitur, ipsum eget blandit vestibulum, diam felis porttitor est, scelerisque varius odio erat quis libero.
					</p>
					<a href="<null>" class="btn btn-primary">
						Conoce más aqui
					</a>
				</div>
				
				<div class="col-sm-4 text-center">
					<h1>
						<i class="fa fa-usd"></i>
					</h1>
					<h3>
						Superb prices
					</h3>		
					<p>
						Donec efficitur, ipsum eget blandit vestibulum, diam felis porttitor est, scelerisque varius odio erat quis libero. Curabitur feugiat tincidunt hendrerit.
					</p>
					<a href="<null>" class="btn btn-primary">
						Conoce más aqui
					</a>
				</div>
				
				<div class="col-sm-4 text-center">
					<h1>
						<i class="fa fa-cogs"></i>
					</h1>
					<h3>
						High-end tech
					</h3>		
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod quam a porta maximus. Donec efficitur, ipsum eget blandit vestibulum, diam felis porttitor est.
					</p>
					<a href="<null>" class="btn btn-primary">
						Conoce más aqui
					</a>
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