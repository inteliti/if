<?php
$demo = ASSETS_URL . 'gmd_demo/';
?>
<style>
	#logo{
		width: 150px;
	}
	.if_header{
		background-color: #fafafa;
	}
	#section-home{
		background-color: #f0f0f0;
		background: url(<?= $demo ?>intro_bg.jpg);
		height: 650px;
	}
	@media(max-width: 768px){
		#section-home{
			height: 350px;
		}
	}
	#section-features img{
		width: 150px;
		margin: 0 20px 20px 0;
		float: left;
	}
	#section-features .text-right img{
		margin: 0 0 20px 20px;
		float: right;
	}
	@media(max-width: 768px){
		#section-features img{
			width: 100px;
		}
	}
</style>

<div class="if_drawer_push">

	<div id="header" class='if_header fixed if_z_4 if_drawer_push'>
		<img src="<?= $demo ?>logo.png" 
			 alt="" border="0" id="logo" class="if_logo" />
		<a class="hmbrgrmenu if_fab if_z_noshadow"></a>
	</div>
	<div class="if_header_push"></div>



	<div class="bg_parallax if_z_inset"
		 id='section-home'
		 data-enllax-ratio="1.5">

		<div class="container if_height100">
			<div class="row if_centerv_wrap if_height100 "
				 >

				<div class="col-sm-12 if_centerv"
					 data-enllax-ratio=".3" data-enllax-type="foreground">

					<h1 class="lead">
						IF { Material Design }
					</h1>
					<p class="lead">
						Demo de el nuevo IF en Bootstrap 3.0,
						con implementaciones basadas en
						Google Material Designs.
					</p>
					<a href="#" class="btn btn-raised btn-primary"
					   onclick="IF_MODAL.alert('Demo de el nuevo IF en Bootstrap 3.0, con implementaciones basadas en Google Material Designs.')">
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




	<div class="if_section" id="section-features">
		<div class="container">
			<div class="row slide-left">
				<div class="col-md-12 ">
					
					<img src="<?= $demo ?>a1.jpg"
						 alt="" border="0" />
					
					<h1 class='lead'>
						Responsive
					</h1>
					<p class='lead'>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod quam a porta maximus. Donec efficitur, ipsum eget blandit vestibulum, diam felis porttitor est, scelerisque varius odio erat quis libero.
					</p>

				</div>
			</div>
			
			<div class="row slide-right">
				<div class="col-md-12 text-right">
					
					<img src="<?= $demo ?>a2.jpg"
						 alt="" border="0" />
					
					<h1 class='lead'>
						Lightweight
					</h1>
					<p class='lead'>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod quam a porta maximus. Donec efficitur, ipsum eget blandit vestibulum, diam felis porttitor est, scelerisque varius odio erat quis libero.
					</p>
					
				</div>
			</div>
			
			<div class="row slide-left">
				<div class="col-md-12">
					
					<img src="<?= $demo ?>a3.jpg"
						 alt="" border="0" />
					
					<h1 class='lead'>
						Cross-browser
					</h1>
					<p class='lead'>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec euismod quam a porta maximus. Donec efficitur, ipsum eget blandit vestibulum, diam felis porttitor est, scelerisque varius odio erat quis libero.
					</p>

				</div>
			</div>


		</div>
	</div>


	<hr />





	<div class="if_section" id='section-about'>
		<div class="container">
			<div class="row">

				<div class="col-sm-4 text-center">
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




	<hr />




	<div class="section3 if_section" id='section-components'>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-warning">
						<h4>
							Warning!
						</h4>
						<p>
							Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.
						</p>
					</div>
					<div class="alert alert-danger">
						<h4>
							Warning!
						</h4>
						<p>
							Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.
						</p>
					</div>
					<div class="alert alert-info">
						<h4>
							Warning!
						</h4>
						<p>
							Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.
						</p>
					</div>
					<div class="alert alert-success">
						<h4>
							Warning!
						</h4>
						<p>
							Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.
						</p>
					</div>
					<div class="alert alert-primary">
						<h4>
							Warning!
						</h4>
						<p>
							Best check yo self, you're not looking too good. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.
						</p>
					</div>


					<div class="navbar navbar-default">
						<div class="container-fluid">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="javascript:void(0)">Brand</a>
							</div>
							<div class="navbar-collapse collapse navbar-responsive-collapse">
								<ul class="nav navbar-nav">
									<li class="active"><a href="javascript:void(0)">Active</a></li>
									<li><a href="javascript:void(0)">Link</a></li>
									<li class="dropdown">
										<a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown
											<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="javascript:void(0)">Action</a></li>
											<li><a href="javascript:void(0)">Another action</a></li>
											<li><a href="javascript:void(0)">Something else here</a></li>
											<li class="divider"></li>
											<li class="dropdown-header">Dropdown header</li>
											<li><a href="javascript:void(0)">Separated link</a></li>
											<li><a href="javascript:void(0)">One more separated link</a></li>
										</ul>
									</li>
								</ul>
								<form class="navbar-form navbar-left">
									<div class="form-group is-empty">
										<input type="text" class="form-control col-md-8" placeholder="Search">
										<span class="material-input"></span></div>
								</form>
								<ul class="nav navbar-nav navbar-right">
									<li><a href="javascript:void(0)">Link</a></li>
									<li class="dropdown">
										<a href="bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown
											<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="javascript:void(0)">Action</a></li>
											<li><a href="javascript:void(0)">Another action</a></li>
											<li><a href="javascript:void(0)">Something else here</a></li>
											<li class="divider"></li>
											<li><a href="javascript:void(0)">Separated link</a></li>
										</ul>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<hr />

	</div>

</div>

<nav id="menu" class="if_drawer" role="navigation">
    <ul>
		<li>
			<h2>
				Demo Drawer
			</h2>
		</li>
        <li>
			<a href="javascript:goTo('section-home')" class="ripple">
				<i class="fa fa-check"></i>
				Inicio
			</a>
		</li>
		<li>
			<a href="javascript:goTo('section-features')" class="ripple">
				<i class="fa fa-check"></i>
				Features
			</a>
		</li>
        <li>
			<a href="javascript:goTo('section-about')" class="ripple">
				<i class="fa fa-check"></i>
				Acerca
			</a>
		</li>
		<li>
			<a href="javascript:goTo('section-components')" class="ripple">
				<i class="fa fa-check"></i>
				Componentes Bootstrap
			</a>
		</li>
	</ul>
</nav>
<script>

	function goTo(where)
	{
		$('.hmbrgrmenu').click();
		$(window).scrollTo('#' + where, 1000, {offset: -80});
	}

</script>