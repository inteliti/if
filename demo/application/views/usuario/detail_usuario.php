<?php

	//PARAMETROS  DE ENTRADA DE LA VISTA
	//campos
    $id = isset($id) ? $id : NULL;
    $usuario = isset($usuario) ? $usuario : NULL;
	$clave = isset($clave) ? $clave : NULL;
	$rol_id = isset($rol_id) ? $rol_id : NULL;
	
	//errores de campos
    $usuario_error = isset($usuario_error) ? $usuario_error : NULL; 
	$clave_error = isset($clave_error) ? $clave_error : NULL; 
	$rol_id_error = isset($rol_id_error) ? $rol_id_error : NULL;
	
?>

<div class="content">
	
    <div class="page-header">
		<h2>Usuario <span class="ico-strongone-usuario pull-right"></span></h2> 
    </div>
	
	<form id="det-usuario-form" class="form-horizontal" onsubmit="return(false);">
		
		<input type="hidden" name="id" value="<?= $id; ?>" size="50" />
		
		<div class="form-group">
			<label for="usuario" class="col-md-3 control-label">
				Usuario
			</label>
			<div class="col-md-9">
				<input class="form-control" 
					   type="text" 
					   name="usuario" 
					   value="<?= $usuario; ?>"
					   size="50" required/>
				<label class="error">
					<?= $usuario_error ?>
				</label>
			</div>

		</div>
		
		<div id="compos-clave" class="<?= $id>0 ? 'hide' : NULL ?>">
			<div class="form-group">
				<label for="clave" class="col-md-3 control-label">
					Clave
				</label>
				<div class="col-md-9">
					<input class="form-control" 
						   type="password" 
						   name="clave" 
						   id="clave"
						   value=""
						   size="50" <?= $id==-1 ? 'required' : NULL ?>/>
					<label class="error">
						<?= $clave_error ?>
					</label>
				</div>
			
			</div>

			<div class="form-group">
				<label for="rclave" class="col-md-3 control-label">
					Repita Clave
				</label>
				<div class="col-md-9">
					<input class="form-control" 
						   type="password" 
						   name="rclave" 
						   value="" 
						   size="50" <?= $id==-1 ? 'required' : NULL ?>/>
				</div>

			</div>
		</div>
		
		<div class="form-group">
			<label for="clave" class="col-md-3 control-label">
				Rol
			</label>
			<div class="col-md-9">
				<select class="form-control"
						name="rol_id">
					<option value="1" <?= $rol_id==1 ? 'selected' : NULL ?>>SUPER</option>
					<option value="2" <?= $rol_id==2 ? 'selected' : NULL ?>>EDITOR</option>
				</select>
				<label class="error">
					<?= $rol_id_error ?>
				</label>
			</div>
	
		</div>
		
		<div class="form-group">
			<div class="col-md-offset-3 col-md-9">
				<button class="btn btn-strongone-blue  margin-left-right-xs" 
						onclick="USUARIO.save('#det-usuario-form');">
					<span class="icon-save"></span>
					Guardar
				</button>

				<?php if($id>0): ?>
				<button class="btn btn-strongone-white margin-left-right-xs" 
						onclick="USUARIO.elim(<?= $id; ?>)">
					<span class="icon-trash"></span>
					Eliminar
				</button>
				<button class="btn btn-strongone-white margin-left-right-xs" 
						onclick="USUARIO.cambiarClave('#det-usuario-form')">
					<span class="icon-lock"></span>
					Cambiar Clave
				</button>
				<?php endif; ?>
				<button class="btn btn-strongone-white margin-left-right-xs" 
						onclick="USUARIO.close()">
					<span class="icon-signout"></span>
					Cerrar
				</button>
			</div>
		</div>
		
	</form>
	
</div>