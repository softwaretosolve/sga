<?php
require_once "conexiones/ConexionSGA.php";

function dbSelect($tabla, $campos = '*', $condiciones = '', &$msg = '', $tipoResultado = MYSQLI_BOTH){	
	
	if (empty($tabla)){
		$msg = "No se ha proporcionado el nombre de la tabla de la que se va a seleccionar.";
		return false;
	}		
	$resultado = [];
	
	try{
		$conexion = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);
		
		if ($conexion->connect_errno) {
			$msg = "Falló la conexión a MySQL: (" . $conexion->connect_errno . ") " . $conexion->connect_error;
			return false;
		}else{			
			$conds = "1 = 1";
			if (!empty($condiciones))
			foreach ($condiciones as $clave => $valor) {
				$conds = "$conds and $clave = $valor";	
			}
					
			$consulta = "select $campos from $tabla where $conds";			
			$resultado = mysqli_query($conexion , $consulta);
			
			$datos = mysqli_fetch_all($resultado, $tipoResultado);
			
			$msg = $conexion->affected_rows;
			mysqli_free_result($resultado);			
			mysqli_close($conexion);					
			return $datos;
		}
	}catch(Exception $ex){
		$msg = "Falló la consulta: " .$ex->getMessage();
		return false;
	}
}

function selectByID($tabla, $id, $campos = '*', &$msg = '', $tipoResultado = MYSQLI_BOTH){
	$cond = ["ID" => $id];
	$res = dbSelect($tabla, $campos, $cond, $msg, $tipoResultado);
	if ($res)
		return $res[0];
	else
		return $res;
}
?>
