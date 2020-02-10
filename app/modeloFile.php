<?php
include_once 'config.php';
function modeloFileUpFile($archivo,$userId, &$msg,$tamanioFichero){
    $resu = true;
    $codigosErrorSubida= [
        0 => 'Subida correcta',
        1 => 'El tamaño del archivo excede el admitido por el servidor',  // directiva upload_max_filesize en php.ini
        2 => 'El tamaño del archivo excede el admitido por el cliente',  // directiva MAX_FILE_SIZE en el formulario HTML
        3 => 'El archivo no se pudo subir completamente',
        4 => 'No se seleccionó ningún archivo para ser subido',
        6 => 'No existe un directorio temporal donde subir el archivo',
        7 => 'No se pudo guardar el archivo en disco',  // permisos
        8 => 'Una extensión PHP evito la subida del archivo'  // extensión PHP
    ];
    $msg = '';
    print_r($tamanioFichero);

        $directorioSubida = "app/dat/".$userId; 
        $nombreFichero    = $archivo['name'];
        $temporalFichero  = $archivo['tmp_name'];
        $errorFichero     = $archivo['error'];

        if($tamanioFichero > LIMITE_FICHERO){
            $msg = "El tamaño del archivo es mayor de ".round(LIMITE_FICHERO/1024)." Mbs";
            return false;
        }else{
        if(!modeloComprobarEspacio($userId,$tamanioFichero)){
            $msg = "El tamaño del archivo es superior al espacio disponible";
            return false;
        }
        }
        

        // Obtengo el código de error de la operación, 0 si todo ha ido bien
        if ($errorFichero > 0) {
            $msg .= "Se a producido el error: $errorFichero: "
            . $codigosErrorSubida[$errorFichero] ;
            $resu = false;
        } else { // subida correcta del temporal
            // si es un directorio y tengo permisos
            if ( is_dir($directorioSubida) && is_writable ($directorioSubida)) {
                
                //Intento mover el archivo temporal al directorio indicado
                if (move_uploaded_file($temporalFichero,  $directorioSubida .'/'. $nombreFichero) == true) {
                    $msg .= 'Archivo guardado correctamente';
                    
                } else {
                    $msg .= 'ERROR: Archivo no guardado correctamente';
                    $resu = false;
                }
            } else {
                    $resu = FALSE;
            }
        }  
        return $resu;
}


function modeloFileBorrar($fichero) {
    if(unlink($fichero)){
        return true;
    }else{
        return false;
    }  
}

function modeloFileCambiarNombre($fichero, $NuevoNombre){
    if(is_file($fichero)){
        $arrayExtension=explode('.',$fichero);
        $extension=end($arrayExtension);
        rename($fichero, "app/dat/".$NuevoNombre.'.'.$extension);
        return true;
    }
    return false;
}

function modeloEspaciOcupado($userId){
    $espacioOcupado = 0;
    $directorio="app/dat/".$userId;
    if(is_dir($directorio)){
        $gestor=opendir($directorio);
        while(($archivo=readdir($gestor))!==false){
            if( $archivo=="." || $archivo==".."){
                continue;
            }
            $espacioFichero = round((filesize($directorio."/".$archivo)/1024),2);          
            $espacioOcupado += $espacioFichero;           
        }
    }
    return $espacioOcupado;
}

function modeloComprobarEspacio($userId,$espacioFichero){ 
    $espacioOcupado = modeloEspaciOcupado($userId)*1024;
    $espacioLibre = LIMITE_TOTAL - $espacioOcupado; 
    if($espacioLibre < $espacioFichero){       
        return false;
    }
    return true;
}