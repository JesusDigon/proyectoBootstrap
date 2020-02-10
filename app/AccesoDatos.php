<?php
include_once 'plantilla/Usuario.php';
include_once 'config.php';
class AccesoDatos {
    
    private static $modelo = null;
    private $dbh = null;
    
    public static function initModelo(){
        if (self::$modelo == null){
            self::$modelo = new AccesoDatos();
        }
        return self::$modelo;
    }

    private function __construct(){
        
        try {
            $dsn =  "mysql:host=".DBSERVER.";dbname=".DBNAME.";charset=utf8";
            $this->dbh = new PDO($dsn,DBUSER,DBPASSWORD);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Error de conexión ".$e->getMessage();
            exit();
        }
        // Construyo las consultas
        $this->consultaUsuarios = $this->dbh->prepare("SELECT * FROM usuarios");
        $this->consultaUser = $this->dbh->prepare("SELECT user FROM usuarios");
        $this->consultaClave = $this->dbh->prepare("SELECT clave FROM usuarios WHERE user=?;");
        $this->consultaTipo = $this->dbh->prepare("SELECT tipo FROM usuarios WHERE user=?;");
        $this->consultaEstado = $this->dbh->prepare("SELECT estado FROM usuarios WHERE user=?;");
        $this->consultaCorreo = $this->dbh->prepare("SELECT correo FROM usuarios");
        $this->eliminarUsuario = $this->dbh->prepare("DELETE FROM usuarios WHERE user=?;");
        $this->nuevoUsuario = $this->dbh->prepare("INSERT INTO usuarios VALUES (?,?,?,?,?,?);");
        $this->actualizarUsuario = $this->dbh->prepare("UPDATE usuarios SET clave=?, nombre=?, correo=?, tipo=?, estado=? WHERE user=?;");
        $this->contarRegistros = $this->dbh->prepare("SELECT count(*) FROM usuarios");
    }
    
    public function obtenerUsuarios ():array {
        $usuarios = [];       
        // Devuelvo una tabla asociativa
        $this->consultaUsuarios->setFetchMode(PDO::FETCH_CLASS,'usuarios');
        if ( $this->consultaUsuarios->execute() ){
            while ( $fila = $this->consultaUsuarios->fetch()){
               $usuario = new Usuario($fila['user'],$fila['clave'],$fila['nombre'],$fila['correo'],$fila['tipo'],$fila['estado']);
               $usuarios[]= $usuario;
            }
        }
        return $usuarios;
    }

    public function obtenerUsers ():array {
        $users = [];
        $this->consultaUser->setFetchMode(PDO::FETCH_ASSOC);
        if ( $this->consultaUser->execute() ){
            while ( $fila = $this->consultaUser->fetch()){
                $users[]= $fila['user'];
            }
        return $users;
        }
    }

    public function obtenerCorreos ():array {
        $correos = [];
        $this->consultaCorreo->setFetchMode(PDO::FETCH_ASSOC);
        if ( $this->consultaCorreo->execute() ){
            while ( $fila = $this->consultaCorreo->fetch()){
                $correos[] = $fila['correo'];
            }
        return $correos;
        }
    }

    public function obtenerClave ($user):string {
        $this->consultaClave->setFetchMode(PDO::FETCH_ASSOC);
        $this->consultaClave->bindValue(1,$user);
        if($this->consultaClave->execute()){
            if( $fila=$this->consultaClave->fetch()){
                return $fila['clave'];                
            }else{
                return "a";
            }
        }
    }

    public function obtenerTipo ($user):string {
        $this->consultaTipo->setFetchMode(PDO::FETCH_ASSOC);
        $this->consultaTipo->bindValue(1,$user);
        if($this->consultaTipo->execute()){
            if( $fila=$this->consultaTipo->fetch(PDO::FETCH_ASSOC)){
                return $fila['tipo'];                
            }
        }
    }

    public function obtenerEstado ($user):string {
        $this->consultaEstado->setFetchMode(PDO::FETCH_ASSOC);
        $this->consultaEstado->bindValue(1,$user);
        if($this->consultaEstado->execute()){
            if( $fila=$this->consultaEstado->fetch(PDO::FETCH_ASSOC)){
                return $fila['estado'];                
            }
        }
    }

    public function eliminarUsuario ($user):bool {
        $this->eliminarUsuario->setFetchMode(PDO::FETCH_ASSOC);
        $this->eliminarUsuario->bindValue(1,$user);

        return $this->eliminarUsuario->execute();
    }

    public function nuevoUsuario($idusuario, $datosuser):bool{
        $this->nuevoUsuario->setFetchMode(PDO::FETCH_ASSOC);
        $this->nuevoUsuario->bindValue(1,$idusuario);
        $this->nuevoUsuario->bindValue(2,$datosuser[0]);
        $this->nuevoUsuario->bindValue(3,$datosuser[1]);
        $this->nuevoUsuario->bindValue(4,$datosuser[2]);
        $this->nuevoUsuario->bindValue(5,$datosuser[3]);
        $this->nuevoUsuario->bindValue(6,$datosuser[4]);
        if($this->nuevoUsuario->execute()){
            return true;
        }
        return false;
    }

    public function actualizaUsuario($id, $datosuser):bool{
        $this->actualizarUsuario->setFetchMode(PDO::FETCH_ASSOC);
        $this->actualizarUsuario->bindValue(1,$datosuser[0]);
        $this->actualizarUsuario->bindValue(2,$datosuser[1]);
        $this->actualizarUsuario->bindValue(3,$datosuser[2]);
        $this->actualizarUsuario->bindValue(4,$datosuser[3]);
        $this->actualizarUsuario->bindValue(5,$datosuser[4]);
        $this->actualizarUsuario->bindValue(6,$id);
        if($this->actualizarUsuario->execute()){
            return true;
        }
        return false;
    }

     // Evito que se pueda clonar el objeto.
    public function __clone()
    { 
        trigger_error('La clonación no permitida', E_USER_ERROR); 
    }
}

?>