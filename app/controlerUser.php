<?php
// ------------------------------------------------
// Controlador que realiza la gestión de usuarios
// ------------------------------------------------
include_once 'config.php';
include_once 'modeloUser.php';
include_once 'controlerFile.php';
include_once 'plantilla/Usuario.php';
include_once 'AccesoDatos.php';

function  ctlUserInicio(){
    $msg   = "";
    $user  = "";
    $clave = "";
    if ( $_SERVER['REQUEST_METHOD'] == "POST"){
        if (isset($_POST['user']) && isset($_POST['clave'])){
            $user  = $_POST['user'];
            $clave = $_POST['clave'];
            if ( modeloOkUser($user,$clave)){
                $_SESSION['user'] = $user;
                $_SESSION['tipouser'] = modeloObtenerTipo($user);
                if ( $_SESSION['tipouser'] == 3){
                    $_SESSION['modo'] = GESTIONUSUARIOS;
                    header('Location:index.php?orden=VerUsuarios');
                }
                else {
                    if(modeloUserObtenerEstado($user)=="A"){
                   $_SESSION['modo'] = GESTIONFICHEROS;
                   header('Location:index.php?orden=Mis Archivos');
                    }
                    else{
                        $msg="Usuario momentaneamente inactivo, le enviaremos un email cuando su cuenta este activada.";
                        session_destroy();
                        include_once 'plantilla/facceso.php';
                    }
                }
            }
            else {$msg="Error: usuario y contraseña no válidos.";}  
        }
    }    
    include_once 'plantilla/facceso.php';
}

// Cierra la sesión y vuelva los datos
function ctlUserCerrar(){
    session_destroy();
    header('Location:index.php');
}


// Muestro la tabla con los usuario 
function ctlUserVerUsuarios (){
    // Obtengo los datos del modelo
    $usuarios = modeloUserGetAll(); 
    include_once 'plantilla/verusuariosp.php';
}


//Borra un usuario y llama a ver la tabla actualizada
function ctlUserBorrar(){
    $user=$_GET['id'];
    if(modeloUserDel($user)){
        $msg="La operación se realizó correctamente.";
    }else{
        $msg="No se pudo relaizar la operación.";
    }
    ctlFileBorrarDir($user);    //al borrar el usuario se borrar también su carpeta de archivos.
        
    ctlUserVerUsuarios();
    
}

//Comprueba si hay envio de formulario, de no ser así muestra el formulario nuevo, 
//y sino trata los datos enviados desde este para crear el nuevo usuario.
function ctlUserAlta(){
    //si no hay id enviado por post, muestro formulario
    if(!isset($_POST['id'])){
        include_once 'plantilla/nuevo.php';
        }else{
            //si hay datos enviados por post, y no es el boton de vuelta a atras, doy de alta al usuario
          if(!isset($_POST['atras'])){
            $msg            =  "";
            $idDiv          = "";
            $usuarioid      =  htmlspecialchars($_POST['id']);
            $clave          =  htmlspecialchars($_POST['password']); 
            $passrepetida   =  htmlspecialchars($_POST['password2']);
            $valoresUsuario =  [$clave ,htmlspecialchars($_POST['nombre']),htmlspecialchars($_POST['mail']), htmlspecialchars($_POST['plan']), htmlspecialchars($_POST['estado'])];
            
            if(modeloUserComprobacionesNuevo($usuarioid, $valoresUsuario, $passrepetida, $msg, $idDiv)) {//comprueba valores introducidos
                $valoresUsuario[0]=modeloUserCifrar($clave);
                if(modeloUserNuevo($usuarioid, $valoresUsuario)){
                    $msg="Usuario dado de alta correctamente";
                    ctlUserVerUsuarios();
                    modeloUserCrearDir($usuarioid);
                    }else{
                        $msg="No se pudo relaizar la operación.";
                     }
              }else{//si los valores no son correctos se muestra el formulario otra vez
                 include_once 'plantilla/nuevo.php';
              }
          }else{//si se le da a atras se vuelve a la pantalla de ver usuarios
            ctlUserVerUsuarios();
        }
    }
}

//Comprobamos si hay Post, de ser asi modificamos el usuario, y sino mostramos el formulario de modificación
function ctlUserModificar(){
    $msg="";
    $idDiv="";
    //si no hay post, se accede a la plantilla
    if(!isset($_POST['nombre'])){
        $usuarioid = $_GET['id'];
        $usuarios  = modeloUserGetAll();
        include_once 'plantilla/Modificar.php';
        }else{   
            //si no hay orden atras, se modifica el usuario    
            if(!isset($_POST['Atrás'])){
                if($_SESSION['modo']!== GESTIONUSUARIOS){
                $usuarioid       = $_SESSION['user'];
                }else{
                    $usuarioid       = $_POST['id'];
                }
                $usuarios        = modeloUserGetAll();
                foreach($usuarios as $usuario){
                    if($usuario->user==$usuarioid){
                        $user=$usuario;
                    }
                }
                $clave           = htmlspecialchars($_POST['clave']);
                $valoresUsuario  = [$clave,htmlspecialchars($_POST['nombre']),htmlspecialchars($_POST['email']), htmlspecialchars($_POST['plan']), htmlspecialchars($_POST['estado'])];
                
                if(modeloUserComprobacionesModificar($valoresUsuario, $msg, $user, $idDiv)){
                    if($user->clave!=$valoresUsuario[0]){
                    $valoresUsuario[0]=modeloUserCifrar($clave);//si se modificó la contraseña se cifra, sino ya está cifrada
                    }
                    //si no es administrador compruebo si ha cambiado el plan y de ser asi, le dejo incativo para que sea el administrador quien de el visto bueno al cambio;
                    if($_SESSION['modo']!=GESTIONUSUARIOS){
                        if($valoresUsuario[3]!=$user->plan){
                            $valoresUsuario[4]="B";
                        }
                        if($valoresUsuario[4]!=$user->estado){
                            $valoresUsuario[4]=$user->estado;
                        }
                    }
                    //si es administrador, después de modificar se muestra ver usuarios
                    
                    if($_SESSION['modo']==GESTIONUSUARIOS){
                        modeloUserUpdate($usuarioid, $valoresUsuario);
                        ctlUserVerUsuarios();
                    }else{//si es un usuario normal se muestra ver ficheros
                        modeloUserUpdate($usuarioid,$valoresUsuario);
                        if($valoresUsuario[4]=="B"){
                            session_destroy();
                            header('Location:index.php');//si ha cambiado el plan se le muestra la pantalla de inicio
                        }
                        ctlFileVerFicheros();
                    }
                }else{include_once 'plantilla/Modificar.php';}
            }else{ 
                //lo mismo, si es admin o usuario normal, en este caso al darle al botón atrás.
                if ( $_SESSION['modo'] == GESTIONUSUARIOS){
                    ctlUserVerUsuarios();
                }else{
                    ctlFileVerFicheros();
            }
        }
    }
}

//Muestra detalles del usuario en cuestión
function ctlUserdetalles(){
    $usuarios = modeloUserGetAll();
    $msg="Gestión de usuarios";
    include_once 'plantilla/detalles.php';
}

function ctlUserNuevo() {
    if(!isset($_POST['id'])){
        include_once 'plantilla/registro.php';
    }else{
        $idDiv="";
        $msg            = "";
        $usuarioid      = htmlspecialchars($_POST['id']);
        $passrepetida   = htmlspecialchars($_POST['password2']);
        $clave          = htmlspecialchars($_POST['password']); 
        $valoresUsuario = [$clave,htmlspecialchars($_POST['nombre']),htmlspecialchars($_POST['mail']), htmlspecialchars($_POST['plan']), htmlspecialchars($_POST['estado'])];
        
        if(modeloUserComprobacionesNuevo($usuarioid, $valoresUsuario, $passrepetida, $msg, $idDiv)) {//comprueba valores introducidos
            $valoresUsuario[0]=modeloUserCifrar($clave);
            //Si no es un administrador el que crea el usuario el estado es Inactivo
            if($_SESSION['modo']!=GESTIONUSUARIOS){
                $valoresUsuario[4]="B";
            }
            if(modeloUserNuevo($usuarioid, $valoresUsuario)){
                $msg="Usuario dado de alta correctamente";
                modeloUserCrearDir($usuarioid); 
                session_destroy();                
                header('Location:index.php');
            }else{
                $msg="No se pudo realizar la operación.";
            }
        }else{//si los valores no son correctos se muestra el formulario otra vez
            include_once 'plantilla/registro.php';
        }
    }
}






