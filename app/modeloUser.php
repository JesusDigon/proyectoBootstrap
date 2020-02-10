<?php 
include_once 'plantilla/Cifrador.php';
include_once 'plantilla/Usuario.php';
include_once 'AccesoDatos.php';
/* DATOS DE USUARIO
• Identificador ( 5 a 10 caracteres, no debe existir previamente, solo letras y números)
• Contraseña ( 8 a 15 caracteres, debe ser segura)
• Nombre ( Nombre y apellidos del usuario
• Correo electrónico ( Valor válido de dirección correo, no debe existir previamente)
• Tipo de Plan (0-Básico |1-Profesional |2- Premium| 3- Máster)
• Estado: (A-Activo | B-Bloqueado |I-Inactivo )
*/
// Inicializo el modelo 
// Cargo los datos del fichero a la session
function modeloUserInit(){
     
}

function abrirBD(){
    $conex = AccesoDatos::initModelo();
    return $conex;
}

// Comprueba usuario y contraseña (boolean)
function modeloOkUser($user,$password){

    $bd=abrirBD();
    $consultaClave=$bd->obtenerClave($user);
   
    if(password_verify($password,$consultaClave)){echo "true";}else{echo "false";}
    
    if(Cifrador::verificar($password,$consultaClave)){return true;}return false;  
}

function modeloObtenerTipo($user){
    $bd=abrirBD();
    return $bd->obtenerTipo($user);
}

function modeloUserObtenerEstado($user){
    $bd=abrirBD();
    return $bd->obtenerEstado($user);
}

function modeloUserDel($user){
    $bd=abrirBD();
    return $bd->eliminarUsuario($user);
}

// Tabla de todos los usuarios para visualizar
function modeloUserGetAll (){
    // Genero lo datos para la vista que no muestra la contraseña ni los códigos de estado o plan
    // sino su traducción a texto
    $bd=abrirBD();
    return $bd->obtenerUsuarios();
}

//Vuelca nuevo usuario en la session
function modeloUserNuevo($idusuario, $datosuser){
   
    $bd=abrirBD();
    return $bd->nuevoUsuario($idusuario, $datosuser);

}
    
//Actualiza un usuario en la session
function modeloUserUpdate($id, $datosuser){
    $bd=abrirBD();
    return $bd->actualizaUsuario($id, $datosuser);
}

//Funcion que comprueba todas las entradas del formulario Nuevo
function modeloUserComprobacionesNuevo($usuarioid,$valoresusuario, $passrepetida ,&$msg, &$idDiv){
    if(modeloUserComprobarId($usuarioid, $msg, $idDiv)){
        if(comprobarContraseñas($valoresusuario[0],$passrepetida, $msg, $idDiv)){
            if(modeloUserComprobarNombre($valoresusuario[1], $msg, $idDiv)){
                if(modeloUserComprobarMail($valoresusuario[2], $msg, "", $idDiv)){
                    return true;
                }
            }
        }
    }
    return false;
}

function modeloUserCifrar($clave){ 
    return Cifrador::cifrar($clave);   
}

//Funcion que comprueba las entradas del formulario modificar
function modeloUserComprobacionesModificar($valoresusuario, &$msg, $user, &$idDiv){
    if(modeloUserComprobarNombre($valoresusuario[1], $msg, $idDiv)){
        if(modeloUserComprobarMail($valoresusuario[2], $msg, $user->correo, $idDiv)){
            if($user->clave!=$valoresusuario[0]){//condicion para ver si se cambio la contraseña y de ser asi se comprueba la nueva
                if(comprobarContraseñas($valoresusuario[0],$valoresusuario[0], $msg, $idDiv)){   
                    return true;}
                }else{
                    return true;
                }   }    }return false;
}

function modeloUserComprobarId($id, &$msg, &$idDiv){
    $idDiv="ident";
    if(strlen($id)<5 || strlen($id)>10){
        $msg="El id de usuario debe tener entre 5 y 10 caracteres.";
        return false;
    }
    $bd=abrirBD();
    $listaUsers=$bd->obtenerUsers();
        foreach($listaUsers as $user){
            if($user==$id){
                $msg="Ya existe un usuario con ese id.";
                return false;
            }
        }
    
    if(!ctype_alnum($id)){
        $msg="El id solo debe contener letras y números.";
        return false;
    }
    return true;
}

function modeloUserComprobarNombre($nombre, &$msg, &$idDiv){
    $idDiv="nombre";
    if(strlen($nombre)>20 || strlen($nombre)<1){
        $msg="El nombre debe estar comprendido entre 1 y 20 caracteres.";
        return false;
    }
    return true;
}

function modeloUserComprobarMail($mail, &$msg, $mailuser, &$idDiv){
    $idDiv="mail";
    if($mail!=$mailuser){
        $bd=abrirBD();
        $listaCorreos=$bd->obtenerCorreos();
        foreach($listaCorreos as $correo){
            if($correo==$mail){
                $msg="Ya existe un usuario con ese mail";
                return false;
            }
        }
        
    }
    if(strpos($mail, "@") && strpos($mail, ".")){
        return true;
    }
    $msg="El email no es correcto.";
    return false;
    
}

function comprobarContraseñas($contraseña1,$contraseña2, &$msg, &$idDiv){
    $idDiv="password";
    if($contraseña1==$contraseña2){
        if(strlen($contraseña1)>=8 && strlen($contraseña1)<=15){
            if(ctype_upper($contraseña1)||ctype_lower($contraseña1)){
                    $msg="La contraseña debe contener al menos una minúscula y una mayúscula.";
                    return false;
            }elseif(ctype_alpha($contraseña1)){
                    $msg="La contraseña debe contener algún carácter numérico";
                    return false;
            }elseif(ctype_alnum($contraseña1)){
                    $msg="La contraseña debe contener algún carácter no alfanumérico";
                    return false;
                }else{
                    return true;
                }  
        }else{
            $msg="La contraseña debe contener al menos 8 carácteres.";
            return false;
            }
    }else{
        $msg="Las contraseñas no coinciden";
        $idDiv="password2";
        return false;
        }
}

function modeloUSerCrearDir($usuarioid){
    $carpeta="app\\dat\\".$usuarioid;
    if(!file_exists($carpeta)){
        mkdir($carpeta, 0777, true);
        chmod($carpeta, 0777);
    }
}
