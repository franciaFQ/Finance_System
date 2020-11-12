<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
	function header()
	{
		// Logo
    	$this->Image('../images/logo.png',10,0,33);
		// Arial bold 15
	    $this->SetFont('times','B',18);
	    // Movernos a la derecha
	    $this->Cell(110);
	    // Título
	    $this->Cell(50,10,utf8_decode('Planilla salarial aguinaldo'),0,0,'C');
	    // Salto de línea
	    $this->Ln(10);

	    // Arial bold 15
	    $this->SetFont('Arial','B',11);
	    $this->Cell(30);//Alinearlo
	    $this->Cell(12, 6, 'Cod.', 1,0, 'C', 0);
	    $this->Cell(50, 6, 'Nombre', 1,0, 'C', 0);
	    $this->Cell(18, 6, 'Salario', 1,0, 'C', 0);
	    $this->Cell(30, 6, 'Fecha ingreso', 1,0, 'C', 0);
	    $this->Cell(40, 6, 'Al 12 Diciembre '. date("Y"), 1,0, 'C', 0);
	    $this->Cell(27, 6, 'Total a recibir', 1,0, 'C', 0);
	    $this->Cell(25, 6, 'Firma', 1,1, 'C', 0);
	}

	// Pie de página
	function Footer()
	{
	    // Posición: a 1,5 cm del final
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
	}
}

include '../conexion.php';

$db = new Database();
$query = $db->connect()->query('SELECT * FROM empleados');
$empl = $query->fetchAll();

$queryv = $db->connect()->query('SELECT * FROM valoresbase');
$valores = $queryv->fetchAll();

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('l');
$pdf->SetFont('times','',10);

	$ttlrecib = 0;

foreach ($empl as $empls) {
		$salario = $empls[2];// entre 2 por ser mensual
		
		$data_inicio = new DateTime($empls[7]);
    	$data_fin = new DateTime("12-12-".date("Y"));
		$dateInterval = $data_inicio->diff($data_fin);
    	$dias = $dateInterval->days;

    	if ($dias<365) {
    		$recibir = (($salario/365)*$dias);
    	}elseif ($dias>=365 && $dias<1095) {
    		$recibir = ($salario/2);
    	}elseif ($dias>=1095 && $dias<3650) {
    		$recibir = (($salario/30)*19);
    	}else{
    		$recibir = (($salario/30)*21);
    	}

		$ttlrecib += number_format((float)($recibir), 2, '.', ''); 

		$pdf->Cell(30);//Alinearlo
		$pdf->Cell(12, 6, $empls[0], 1,0, 'C', 0);
	    $pdf->Cell(50, 6, utf8_decode($empls[1]), 1,0, 'C', 0);
	    $pdf->Cell(18, 6, $salario, 1,0, 'C', 0);
	    $pdf->Cell(30, 6, $empls[7], 1,0, 'C', 0);
	    $pdf->Cell(40, 6, $dias, 1,0, 'C', 0);
	    $pdf->Cell(27, 6, number_format((float)($recibir), 2, '.', ''), 1,0, 'C', 0);
	    $pdf->Cell(25, 6, '', 1,1, 'C', 0);//espacio para firma
}

//Totales
$pdf->Cell(140);//Alinearlo
$pdf->Cell(40, 6, 'Total', 1,0, 'C', 0);
$pdf->Cell(27, 6, $ttlrecib, 1,0, 'C', 0);

$pdf->Output();

try{
    $db = new Database();
    $query = $db->connect()->query('SELECT codTrans FROM transferencias where codTrans like \'PLA%\'
	 order by codTrans desc limit 1');
	$codnueva = $query->fetchAll();
	$codnueva = str_split($codnueva[0][0], 3);
	$codnueva[1] += 1;
  	$codnueva = 'PLA'.$codnueva[1];
    $consulta = $db->connect()->prepare("insert into transferencias (codTrans, montoTrans, detalles, fechatrans) 
        VALUES (:codT, :montoT, :detalles, :fechatrans)");

    $result = $consulta->execute(
        array(':codT' => $codnueva,
        	':montoT' => $ttlrecib,
            ':detalles'=> 'Pago de planilla 15 Aguinaldo',
            ':fechatrans' => date("Y-m-d"))
    );

    if (!$result){
        $fmsg = "Transacción no pudo ser ingresada.";
        return;
    }else{
    	//DEBE
        $consulta = null; // obligado para cerrar la conexión
        $db = null;
        $db = new Database();

        $consulta = $db->connect()->prepare("insert into detallesTransferencias (codTrans, codCuenta, codTipo, montoU) VALUES (:codT, :codCuenta, :codTipo, :montou)");

        $result = $consulta->execute(
        	array(':codT' => $codnueva,
            	':codCuenta' => 6217,
              	':codTipo'=> 1,
              	':montou' => $ttlrecib)
        );

        //HABER
        $consulta = null; // obligado para cerrar la conexión
        $db = null;
        $db = new Database();

        $consulta = $db->connect()->prepare("insert into detallesTransferencias (codTrans, codCuenta, codTipo, montoU) VALUES (:codT, :codCuenta, :codTipo, :montou)");

        $result = $consulta->execute(
        	array(':codT' => $codnueva,
            	':codCuenta' => 211,
              	':codTipo'=> 2,
              	':montou' => $ttlrecib)
        );
    }
}catch(Exception $e){
	echo $e;
}

?>