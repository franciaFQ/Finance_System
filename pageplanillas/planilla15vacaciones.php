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
	    $this->Cell(50,10,utf8_decode('Planilla salarial 15 días laborados vacaciones'),0,0,'C');
	    // Salto de línea
	    $this->Ln(10);

	    // Arial bold 15
	    $this->SetFont('Arial','B',11);
	    $this->Cell(12, 6, 'Cod.', 1,0, 'C', 0);
	    $this->Cell(50, 6, 'Nombre', 1,0, 'C', 0);
	    $this->Cell(11, 6, utf8_decode('Días'), 1,0, 'C', 0);
	    $this->Cell(18, 6, 'Salario', 1,0, 'C', 0);
	    $this->Cell(22, 6, 'Vacaciones', 1,0, 'C', 0);
	    $this->Cell(18, 6, 'Gravado', 1,0, 'C', 0);
	    $this->Cell(15, 6, 'ISSS', 1,0, 'C', 0);
	    $this->Cell(15, 6, 'AFP', 1,0, 'C', 0);
	    $this->Cell(15, 6, 'Renta', 1,0, 'C', 0);
	    $this->Cell(22, 6, 'Total desc.', 1,0, 'C', 0);
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

$queryr = $db->connect()->query('SELECT * FROM rentas');
$rentab = $queryr->fetchAll();

function CalcularRenta($desctmp)
{
	if ($desctmp<236.00) {
		return 0;
	}

	global $rentab;
	for ($i = 0; $i <= sizeof($rentab); $i++){
		if (isset($rentab[$i]['hasta'])) {
			if ($rentab[$i]['desde']<$desctmp && $rentab[$i]['hasta']>$desctmp) {
				return ($desctmp - $rentab[$i]['excesoDe']) * $rentab[$i]['porcentaje'] + $rentab[$i]['cuotaFija']."desde ". $rentab[$i]['desde'] . " hasta " . $rentab[$i]['hasta'];
			}
		}else{
			return ($desctmp - $rentab[$i]['excesoDe']) * $rentab[$i]['porcentaje'] + $rentab[$i]['cuotaFija']
			."desde ". $rentab[$i]['desde'] . " hasta " . $rentab[$i]['hasta'];
		}
	}
}


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('l');
$pdf->SetFont('times','',10);

	$ttlisss = 0;
	$ttlafp = 0;
	$ttlrenta = 0;
	$ttldesc = 0;
	$ttlrecib = 0; 

foreach ($empl as $empls) {
		$salario = $empls[2] / 2;// entre 2 por ser 15 dias
		$vacaciones = number_format((float)($salario * $valores[5][2]), 2, '.', ''); //salario * 0.3
		$gravado = number_format((float)($salario + $vacaciones), 2, '.', ''); //salario+vacaciones

		$isss = number_format((float)($gravado * $valores[0][2]), 2, '.', '');//gravado*0.03
		$afp = number_format((float)($gravado * $valores[2][2]), 2, '.', '');//gravado * 0.0725
		
		$isss = ($isss>30) ? 30 : $isss; // verifica si el isss es mayor a 30

		$desctmp = $gravado - $isss - $afp;//temporal descuentos
		$renta = number_format((float)(CalcularRenta($desctmp)), 2, '.', '');//depende de la tabla

		$totaldes = $isss + $afp + $renta;//suma de isss,afp,renta
		$recibir = $gravado - $totaldes;//gravado-descuentos

		$ttlisss += $isss;
		$ttlafp += $afp;
		$ttlrenta += $renta;
		$ttldesc += $totaldes;
		$ttlrecib += $recibir; 

		$pdf->Cell(12, 6, $empls[0], 1,0, 'C', 0);
	    $pdf->Cell(50, 6, utf8_decode($empls[1]), 1,0, 'C', 0);
	    $pdf->Cell(11, 6, '15', 1,0, 'C', 0);
	    $pdf->Cell(18, 6, $salario, 1,0, 'C', 0);
	    $pdf->Cell(22, 6, $vacaciones, 1,0, 'C', 0);//vacaciones
	    $pdf->Cell(18, 6, $gravado, 1,0, 'C', 0);
	    $pdf->Cell(15, 6, $isss, 1,0, 'C', 0);
	    $pdf->Cell(15, 6, $afp, 1,0, 'C', 0);
	    $pdf->Cell(15, 6, $renta, 1,0, 'C', 0);
	    $pdf->Cell(22, 6, $totaldes, 1,0, 'C', 0);
	    $pdf->Cell(27, 6, $recibir, 1,0, 'C', 0);
	    $pdf->Cell(25, 6, '', 1,1, 'C', 0);//espacio para firma
}
//Totales
$pdf->Cell(113);//Alinearlo
$pdf->Cell(18, 6, 'Totales', 1,0, 'C', 0);
$pdf->Cell(15, 6, $ttlisss, 1,0, 'C', 0);
$pdf->Cell(15, 6, $ttlafp, 1,0, 'C', 0);
$pdf->Cell(15, 6, $ttlrenta, 1,0, 'C', 0);
$pdf->Cell(22, 6, $ttldesc, 1,0, 'C', 0);
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
            ':detalles'=> 'Pago de planilla 15 vacaciones',
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
            	':codCuenta' => 627,
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