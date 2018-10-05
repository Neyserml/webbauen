<?php

require_once 'config.php';
Conectarse();
function Conectarse()
	{

		 $conn=mysqli_connect(SERVER,USER,PASS);
             mysqli_select_db($conn,DB); 
         $conn->query("SET CHARACTER SET UTF8");

// $conn = new mysqli(SERVER, USER, PASS, DB);

// 		$con->query("SET CHARACTER SET UTF8");

// return $conn;
  if( $conn) {
     echo "Conexión establecida con exito.<br />";
}else{
     echo "Conexión no se pudo establecer.<br />";
     
}

}


function CerrarConexion($conn){

 mysqli_close($conn);

}


?>
