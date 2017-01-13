<?php
require_once "conexiones/ConexionSGA.php";

function dbInsert($tabla, $data, &$msg = ''){	
	
	if (empty($tabla)){
		$msg = "No se ha proporcionado el nombre de la tabla en la que se desea insertar.";
		return false;
	}
	
	if (empty($data)){
		$msg = "No se han proporcionado los datos a insertar.";
		return false;
	}
	
	try{
		$mysqli = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);
		
		if ($mysqli->connect_errno) {
			$msg = "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			return false;
		}else{				
			$campos = "";
			$valores = "";
			
			foreach ($data as $clave => $valor) {
				if (empty($campos)){
					$campos = $clave;
					$valores = $valor;
				}else{
					$campos = "$campos, $clave";
					$valores = "$valores, $valor";
				}		
			}
					
			$consulta = "insert into $tabla( $campos ) values( $valores )";
			$resultado = $mysqli->query($consulta);
			
			if (!$resultado){
				$msg = "Falló la inserción: (" . $mysqli->errno . ") " . $mysqli->error . "consulta: " . $consulta;				
			}else{
				$msg = $mysqli->affected_rows;				
			}
			mysqli_close($mysqli);
			return $resultado;
		}
	}catch(Exception $ex){
		$msg = "Falló la inserción: (" .$ex->getMessage();
		return false;
	}
}

function dbUpdate($tabla, $data, $condiciones, &$msg = '', $checkEmptyWhere = true){	
	
	if (empty($tabla)){
		$msg = "No se ha proporcionado el nombre de la tabla que se desea actualizar.";
		return false;
	}
	
	if (empty($data)){
		$msg = "No se han proporcionado los datos para la actualización";
		return false;
	}
	
	if (empty($condiciones) && $checkEmptyWhere){
		$msg = "No se han proporcionado condiciones para las filas que se van a actualizar.";
		return false;
	}
	
	try{
		$mysqli = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);
		
		if ($mysqli->connect_errno) {
			$msg = "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			return false;
		}else{							
			$campos = "";			
			foreach ($data as $clave => $valor) {
				if (empty($campos)){
					$campos = "$campos $clave = $valor";					
				}else{
					$campos = "$campos, $clave = $valor";
				}		
			}
			
			$conds = "1 = 1";
			if (!empty($condiciones))
				foreach ($condiciones as $clave => $valor) {
					$conds = "$conds and $clave = $valor";	
				}
					
			$consulta = "update $tabla set $campos where $conds";
			$resultado = $mysqli->query($consulta);
			
			if (!$resultado){
				$msg = "Falló la actualización: (" . $mysqli->errno . ") " . $mysqli->error . "consulta: " . $consulta;				
			}else{
				$msg = $mysqli->affected_rows;;				
			}
			mysqli_close($mysqli);
			return $resultado;
		}
	}catch(Exception $ex){
		$msg = "Falló la actualización: " .$ex->getMessage();
		return false;
	}
}

function dbDelete($tabla, $condiciones, &$msg = '', $checkEmptyWhere = true){	
	
	if (empty($tabla)){
		$msg = "No se ha proporcionado el nombre de la tabla de la que se desea eliminar filas.";
		return false;
	}
		
	if (empty($condiciones) && $checkEmptyWhere){
		$msg = "No se han proporcionado una condiciones para las filas que se van a eliminar.";
		return false;
	}
	
	try{
		$mysqli = new mysqli(DBHOST, DBUSER, DBPWD, DBNAME);
		
		if ($mysqli->connect_errno) {
			$msg = "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			return false;
		}else{										
			
			$conds = "1 = 1";
			if (!empty($condiciones))
				foreach ($condiciones as $clave => $valor) {
					$conds = "$conds and $clave = $valor";	
				}
					
			$consulta = "delete from $tabla where $conds";
			$resultado = $mysqli->query($consulta);
			
			if (!$resultado){
				$msg = "Falló la eliminación: (" . $mysqli->errno . ") " . $mysqli->error . "consulta: " . $consulta;				
			}else{
				$msg = $mysqli->affected_rows;				
			}
			mysqli_close($mysqli);
			return $resultado;
		}
	}catch(Exception $ex){
		$msg = "Falló la eliminación: " .$ex->getMessage();
		return false;
	}
}

?>
