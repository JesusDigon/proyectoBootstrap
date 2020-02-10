<?php
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
?>

	<?php  
	if($msg!==""){ ?> 
	<script>
	    $(document).ready(function(){
		    $("#subirArchivo").addClass("is-invalid");
		    $(".invalid-tooltip").text("<?=$msg?>");
		    $(".invalid-tooltip").css("position", "relative");
		    });
	    </script>
	<?php }?>
<div class="container">
  <h2>Subir fichero</h2>
  
  <form name="f1" enctype="multipart/form-data" action="index.php?orden=Subir Fichero" method="post" class="needs-validation">
	  <div class="row">
      <div class="col">
        <input type="hidden" name="MAX_FILE_SIZE" value="199999990" />
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="custom-file">
        <label class="custom-file-label" for="subirArchivo">Choose file...</label>
          <input name="archivo" class="custom-file-input" type="file" id="subirArchivo" value="Examinar" required>  
          <div class="invalid-tooltip">a</div>    
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group">
          <input type="submit" class="btn btn-primary"  value="Subir fichero" />
        </div>
      </div>
      <div class="col">
        <div class="form-group">
        <input type="button" name="atras" class="btn btn-success"  id="atras" onclick="javascript:document.location='index.php?orden=Mis Archivos'" value="Atrás">
        </div>
      </div>
    </div>



    
  </form>
</div>

<?php 

$contenido = ob_get_clean();
include_once "principal.php";
?>