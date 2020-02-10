<?php
include_once "Usuario.php";
include_once "app/config.php";
// No se envia al navegador
ob_start();
?>
	<?php  
	if(isset($msg)){ ?> 
	<script>
	    $(document).ready(function(){
		    $("#<?=$idDiv?>").addClass("is-invalid");
		    $(".invalid-tooltip").text("<?=$msg?>");
		    $(".invalid-tooltip").css("position", "relative");
		    });
	    </script>
	<?php }?>
<?php  
$auto = $_SERVER['PHP_SELF'];
foreach($usuarios as $usuario){
	if($usuario->user == $usuarioid){
	  $user=$usuario;
	}
  }
?>


<div class="container">
  <h2>Formulario de modificación</h2>
  
  <form action="index.php?orden=Modificar" method="POST" class="needs-validation">
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="id">Identificador:</label>
				<input type="text" class="form-control" id="ident" placeholder="Introduzca un identificador" name="id" required
				value="<?=$user->user?>" readonly>
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-tooltip mt-0">a</div>
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="nombre">Nombre:</label>
				<input type="text" class="form-control" id="nombre" placeholder="Introduzca un nombre" name="nombre" required
				value="<?=$user->nombre?>">
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-tooltip">a</div>
			</div>
		</div>
		</div>
	
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label for="mail">Email:</label>
				<input type="email" class="form-control" id="mail" placeholder="Ejemplo@ejemplo.com" name="email" required
				value="<?=$user->correo?>">
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-tooltip">a</div>
			</div>
		</div>

		<div class="col">
			<div class="form-group">
				<label for="clave">Contraseña:</label>
				<input type="password" class="form-control" id="password" name="clave" required
				value="<?=$user->clave?>">
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-tooltip">a</div>
			</div>
		</div>

	</div>
	<div class="row">
		<div class="col">
			<div class="form-group">
			<label for="plan">Plan:</label>
			<select name="plan" class="form-control" >
    			<option value="0" <?=(PLANES[$user->plan]=='Básico')?'selected':''?>>Básico</option>
    			<option value="1" <?=(PLANES[$user->plan]=='Profesional')?'selected':''?>>Profesional</option>
    			<option value="2" <?=(PLANES[$user->plan]=='Premium')?'selected':''?>>Premium</option>
    			<option value="3" <?=(PLANES[$user->plan]=='Máster')?'selected':''?>>Máster</option>
			</select>
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label for="estado">Estado:</label>
				<select <?=($_SESSION['modo']!='1')?'id="deshabilitado"':'' ?> name="estado" class="form-control">
					<option value="A"<?=(ESTADOS[$user->estado]=='Activo')?'selected':''?>>Activo</option>
    				<option value="B"<?=(ESTADOS[$user->estado]=='Bloqueado')?'selected':''?>>Bloqueado</option>
    				<option value="I"<?=(ESTADOS[$user->estado]=='Inactivo')?'selected':''?>>Inactivo</option>
				</select>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col">
    		<button type="submit" class="btn btn-primary" name="Modificar" id="Modificar" value="Modificar"
			onclick="confirmarModificar('<?=$user->nombre?>','<?=$user->user?>')">Modificar</button>
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
