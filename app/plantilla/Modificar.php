<?php
include_once "Usuario.php";
// No se envia al navegador
ob_start();
?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
<?php  
$auto = $_SERVER['PHP_SELF'];
foreach($usuarios as $clave=>$valor){
	if($valor->user == $_GET['id']){
	  $user=$usuarios[$clave];
	}
  }
?>


<div class="container">
  <h2>Formulario de modificación</h2>
  
  <form action="index.php?orden=Modificar" method="POST" class="needs-validation" novalidate>
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="id">Identificador:</label>
				<input type="text" class="form-control" id="id" placeholder="Introduzca un identificador" name="id" required
				value="<?=$user->user?>" readonly>
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-feedback">Por favor, introduzca un identificador</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="nombre">Nombre:</label>
				<input type="text" class="form-control" id="nombre" placeholder="Introduzca un nombre" name="nombre" required
				value="<?=$user->nombre?>">
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-feedback">Por favor, introduzca un nombre</div>
			</div>
		</div>
		</div>
	
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="mail">Email:</label>
				<input type="mail" class="form-control" id="email" placeholder="Ejemplo@ejemplo.com" name="email" required
				value="<?=$user->correo?>">
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-feedback">Por favor, introduzca un email</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="clave">Contraseña:</label>
				<input type="password" class="form-control" id="clave" name="clave" required
				value="<?=$user->clave?>">
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-feedback">Por favor, introduzca una contraseña</div>
			</div>
		</div>

	</div>
	<div class="row">
		<div class="col">
			<div class="form-group">
			<label for="plan">Plan:</label>
			<select name="plan" class="form-control" >
    			<option value="0" <?=($user->plan=='0')?'selected':''?>>Básico</option>
    			<option value="1" <?=($user->plan=='1')?'selected':''?>>Profesional</option>
    			<option value="2" <?=($user->plan=='2')?'selected':''?>>Premium</option>
    			<option value="3" <?=($user->plan=='3')?'selected':''?>>Máster</option>
			</select>
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="estado">Estado:</label>
				<select <?=($_SESSION['modo']!='1')?'id="deshabilitado"':'' ?> name="estado" class="form-control">
					<option value="A"<?=($user->estado=='A')?'selected':''?>>Activo</option>
    				<option value="B"<?=($user->estado=='B')?'selected':''?>>Bloqueado</option>
    				<option value="I"<?=($user->estado=='I')?'selected':''?>>Inactivo</option>
				</select>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col">
    		<button type="submit" class="btn btn-primary" name="Modificar" id="Modificar" value="Modificar"
			onclick="confirmarModificar('<?=$user->nombre."','".$user->user."'"?>)">Modificar</button>
		</div>	
		<div class="col">
			<input type="cancel"<?php 
			if($_SESSION['modo']==GESTIONUSUARIOS){?>
			onclick="javascript:document.location='index.php'" <?php
			}else{?>
			onclick="VerArchivos()" <?php }  ?>
			name="orden" class="btn btn-success"  id="atras" value="Atrás">
		</div>
	</div>
  </form>
</div>

<?php 
$contenido = ob_get_clean();
include_once "principal.php";
?>
