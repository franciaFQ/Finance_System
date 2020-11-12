<?php 
	include_once '../conexion.php';

	$accion=$_GET['acc'];
	$db = new Database();
	try{
		switch ($accion) {
			case 'del':
				$id=$_GET['id'];

				$delete=$db->connect()->prepare("delete from empleados where codEmpleado=$id");
				$resultD = $delete->execute();
				header('location:listarempleados.php');
				break;
			case 'edit':
				$consulta = $db->connect()->prepare("update empleados set nombreEmpleado =:nombreC, salarioBase =:salarioB, salarioxHora =:salarioxH, horasextra =:horase, horasnocturnas =:horasn, patrono =:patrono,fechaingreso =:ingreso where codEmpleado =:codEmpleado");
				extract($_POST);

     			$result = $consulta->execute(
        		array(':nombreC' => $nombreCompletoE,
        		':salarioB' => $salarioBaseE,
         		':salarioxH'=> $salarioHoraE,
           		':horase' => $horasExtraE,
            	':horasn' => $horasNocturnasE,
             	':patrono' => $patronoE,
             	':ingreso' => $fechacontraE,
             	':codEmpleado' => $codEmpleadoE)
        		);
        		header('location:listarempleados.php');
				break;	
			default:
				header('location:listarempleados.php');
				break;
		}		
	}catch (Exception $e){
		echo "Error: ". $e;
	}
?>