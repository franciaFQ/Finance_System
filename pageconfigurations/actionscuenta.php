<?php 
	include_once '../conexion.php';

	$accion=$_GET['acc'];
	$db = new Database();
	try{
		switch ($accion) {
			case 'edit':
				$id=$_GET['id'];
				$consulta = $db->connect()->prepare("delete FROM detallestransferencias where codTrans = '".$id ."'");

				$result = $consulta->execute();
				 
				$consulta = null;
				$result = null;

				$consulta = $db->connect()->prepare("delete FROM transferencias where codTrans = '".$id ."'");

     			$result = $consulta->execute();
        		header('location:nuevacontador.php');
				break;	
			default:
				header('location:nuevacontador.php');
				break;
		}		
	}catch (Exception $e){
		echo "Error: ". $e;
	}
?>