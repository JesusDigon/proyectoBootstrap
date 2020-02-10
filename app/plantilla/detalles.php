<?php 
include_once "Usuario.php";
ob_start();

$auto = $_SERVER['PHP_SELF'];
foreach($usuarios as $clave=>$valor){
  if($valor->user == $_GET['id']){
    $user=$usuarios[$clave];
  }
}
$numeroArchivos = 0;
$espacioOcupado = 0;
$directorio = "app/dat/".$_GET['id'];

if(is_dir($directorio)){
  $gestor=opendir($directorio);
  while(($archivo=readdir($gestor))!==false){
    if( $archivo=="." || $archivo==".."){
      continue;
    }
    $numeroArchivos++;
    $espacioOcupado +=round((filesize($directorio."/".$archivo)/1024),2);
  }
    $espacioOcupado = round(($espacioOcupado/LIMITE_TOTAL)*100);
}
?>

<div class="container">
  <h2>Detalles de <?=$_GET['id']?></h2>
  <table class="table table-hover">
    <tbody>
      <tr>
        <th scope="row">Nombre</th>
        <td><?=$user->nombre?></td>
      </tr>
      <tr> 
        <th scope="row">Email</th>
        <td><?=$user->correo?></td>
      </tr>
      <tr>
        <th scope="row">Plan</th>
        <td><?=PLANES[$user->plan]?></td>
      </tr>
      <tr>
        <th scope="row">Número de ficheros</th>
        <td><?=$numeroArchivos?></td>
      </tr>
      <tr>
        <th scope="row">Espacio libre</th>
        <td><?=100 - $espacioOcupado?> %</td>  
      </tr>
      <tr> 
        <th>0 <meter min="0" max=<?=100?> low="50" high="10" optimum="0" value="<?=$espacioOcupado?>"></meter> <?=100?> %</th>
        <td>
          <form action="index.php" method="POST" id="formularioDetalles">
            <input type="submit" name="VerUsuarios" value="Volver">
          </form>
        </td>
      </tr>
    </tbody>
  </table>
</div>

<?php 
$contenido = ob_get_clean();
include_once "principal.php";
?>
