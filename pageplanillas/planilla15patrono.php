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
	    $this->Cell(50,10,utf8_decode('Planilla patronal'),0,0,'C');
	    // Salto de línea
	    $this->Ln(10);

	    // Arial bold 15
	    $this->SetFont('Arial','B',11);
	    $this->Cell(65);//Alinearlo
	    $this->Cell(12, 6, 'Cod.', 1,0, 'C', 0);
	    $this->Cell(50, 6, 'Nombre', 1,0, 'C', 0);
	    $this->Cell(18, 6, 'Salario', 1,0, 'C', 0);
	    $this->Cell(15, 6, 'ISSS', 1,0, 'C', 0);
	    $this->Cell(15, 6, 'AFP', 1,0, 'C', 0);
		$this->Cell(25, 6, 'INSAFORP', 1,0, 'C', 0);
		$this->Cell(25, 6, 'TOTALES', 1,1, 'C', 0);

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

	$ttlisss = 0;
	$ttlafp = 0;
	$ttlinsa = 0;
	$ttlrecib = 0;

foreach ($empl as $empls) {
		$salario = $empls[2];// completo

		$isss = number_format((float)($salario * $valores[1][2]), 2, '.', '');//gravado*0.075
		$afp = number_format((float)($salario * $valores[3][2]), 2, '.', '');//gravado * 0.0775
		
		$isss = ($isss>30) ? 30 : $isss; // verifica si el isss es mayor a 30

		$insa = number_format((float)($salario * $valores[6][2]), 2, '.', '');//gravado * 0.01
		
		$ttlsum = $isss + $afp + $insa;
		
		$ttlisss += $isss;
		$ttlafp += $afp;
		$ttlinsa += $insa;
		$ttlrecib += $ttlsum;
		
		
		$pdf->Cell(65);
		$pdf->Cell(12, 6, $empls[0], 1,0, 'C', 0);
		$pdf->Cell(50, 6, utf8_decode($empls[1]), 1,0, 'C', 0);
		$pdf->Cell(18, 6, $salario, 1,0, 'C', 0);
		$pdf->Cell(15, 6, $isss, 1,0, 'C', 0);
		$pdf->Cell(15, 6, $afp, 1,0, 'C', 0);
		$pdf->Cell(25, 6, $insa, 1,0, 'C', 0);
		$pdf->Cell(25, 6, $ttlsum, 1,1, 'C', 0);
		
}
//Totales
$pdf->Cell(127);//Alinearlo
$pdf->Cell(18, 6, 'Totales', 1,0, 'C', 0);
$pdf->Cell(15, 6, $ttlisss, 1,0, 'C', 0);
$pdf->Cell(15, 6, $ttlafp, 1,0, 'C', 0);
$pdf->Cell(25, 6, $ttlinsa, 1,0, 'C', 0);
$pdf->Cell(25, 6, $ttlrecib, 1,1, 'C', 0);

$pdf->Output();


try{
    $db = new Database();
    $query = $db->connect()->query('SELECT codTrans FROM transferencias order by codTrans desc limit 1');
	$codnueva = $query->fetchAll();
	$codnueva = str_split($codnueva[0][0], 3);
	$codnueva[1] += 1;
  	$codnueva = 'PLA'.$codnueva[1];

	$valor = $ttlinsa + $ttlisss; 
    $consulta = $db->connect()->prepare("insert into transferencias (codTrans, montoTrans, detalles, fechatrans) 
        VALUES (:codT, :montoT, :detalles, :fechatrans)");

    $result = $consulta->execute(
        array(':codT' => $codnueva,
        	':montoT' => $ttlrecib,
            ':detalles'=> 'Pago de planilla patrono',
            ':fechatrans' => date("Y-m-d"))
    );
	//ISSS + INSAFORP
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
            	':codCuenta' => 2131,
              	':codTipo'=> 2,
              	':montou' => $valor)
        );

        //HABER
        $consulta = null; // obligado para cerrar la conexión
        $db = null;
        $db = new Database();

        $consulta = $db->connect()->prepare("insert into detallesTransferencias (codTrans, codCuenta, codTipo, montoU) VALUES (:codT, :codCuenta, :codTipo, :montou)");

        $result = $consulta->execute(
        	array(':codT' => $codnueva,
            	':codCuenta' => 622,
              	':codTipo'=> 1,
              	':montou' => $valor)
        );
	}
	
	//AFP
	if (!$result){
        $fmsg = "Transacción no pudo ser ingresada.";
        return;
    }else{
    	//HABER
        $consulta = null; // obligado para cerrar la conexión
        $db = null;
        $db = new Database();

        $consulta = $db->connect()->prepare("insert into detallesTransferencias (codTrans, codCuenta, codTipo, montoU) VALUES (:codT, :codCuenta, :codTipo, :montou)");

        $result = $consulta->execute(
        	array(':codT' => $codnueva,
            	':codCuenta' => 2132,
              	':codTipo'=> 2,
              	':montou' => $ttlafp)
        );

        //DEBE
        $consulta = null; // obligado para cerrar la conexión
        $db = null;
        $db = new Database();

        $consulta = $db->connect()->prepare("insert into detallesTransferencias (codTrans, codCuenta, codTipo, montoU) VALUES (:codT, :codCuenta, :codTipo, :montou)");

        $result = $consulta->execute(
        	array(':codT' => $codnueva,
            	':codCuenta' => 623,
              	':codTipo'=> 1,
              	':montou' => $ttlafp)
        );
    }
}catch(Exception $e){
	echo $e;
}
?>