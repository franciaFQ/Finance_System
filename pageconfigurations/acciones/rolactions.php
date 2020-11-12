<?php 
include_once '../conexion.php';

$accion=$_GET['acc'];
$db = new Database();
try{
	switch ($accion) {
		case 'del':
		$id=$_GET['id'];

		$delete=$db->connect()->prepare("delete from roles where codRol=$id");
		$resultD = $delete->execute();
		echo ' <script language="javascript">alert("Rol Eliminado con éxito");</script> ';
		echo "<script>location.href='../roles.php'</script>";
		break;
		case 'edit':
		$consulta = $db->connect()->prepare("update roles set nombreRol =:nombreR where codRol =:codRol");
		extract($_POST);
/*
		var_dump($_POST);
		exit();
		*/
		$result = $consulta->execute(
			array(':nombreR' => $nombrerolE,
				':codRol' => $codRolE)
		);
		echo ' <script language="javascript">alert("Rol actualizado con éxito");</script> ';
		echo "<script>location.href='../roles.php'</script>";
		break;	
		default:
		echo ' <script language="javascript">alert("Rol actualizado con éxito");</script> ';
		echo "<script>location.href='../roles.php'</script>";
		break;
	}		
}catch (Exception $e){
	echo "Error: ". $e;
}
?>