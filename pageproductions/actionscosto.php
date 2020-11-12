<?php 
	include_once '../conexion.php';

	$accion=$_GET['acc'];
	$db = new Database();
	try{
		switch ($accion) {
			case 'edit':
				$id=$_GET['id'];
				$consulta = $db->connect()->prepare("update ordenproduccion set estado = 'FINALIZADO' where codOrden =".$id);

     			$result = $consulta->execute();
        		header('location:costos.php');
				break;	
			default:
				header('location:costos.php');
				break;
		}		
	}catch (Exception $e){
		echo "Error: ". $e;
	}
?>