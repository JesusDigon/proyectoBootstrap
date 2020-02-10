<?php
// DATOS DE CONEXION
define ('DBSERVER','192.168.105.85');
define ('DBNAME','usuario' );
define ('DBUSER','root');
define ('DBPASSWORD','root');


define ('GESTIONUSUARIOS','1');
define ('GESTIONFICHEROS','2');


// CONSTANTES PARA FICHEROS
define('FILEUSER','app/dat/usuarios.json');// Ruta donde se guardan los archivos de los usuarios
define('RUTA_FICHEROS','/home/alumno/dirpruebas');
define ('LIMITE_TOTAL', 2*1024);//Espacio de memoria para guardar archivos
define ('LIMITE_FICHERO', 1*1024);


// (0-Básico |1-Profesional |2- Premium| 3- Máster)
const  PLANES = ['Básico','Profesional','Premium','Máster'];
//  Estado: (A-Activo | B-Bloqueado |I-Inactivo )
const  ESTADOS = ['A' => 'Activo','B' =>'Bloqueado', 'I' => 'Inactivo']; 

 