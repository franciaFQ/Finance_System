<?php
require('fpdf/fpdf.php');


class PDF extends FPDF
{
	function header()
	{
		
		 setlocale(LC_TIME, 'spanish');  
		 $mes=strftime("%B",mktime(0, 0, 0, date("m"), 1, 2000)); 
		 
		// Logo
    	$this->Image('../images/logo.png',10,0,33);
		// Times bold 18
	    $this->SetFont('times','B',18);
	    // Movernos a la derecha
	    $this->Cell(50);
	    // Título
	    $this->Cell(100,8,utf8_decode('Balance de comprobación'),1,1,'C');
	    // Movernos a la derecha
	    // Times bold 18
	    $this->SetFont('times','B',14);
	    $this->Cell(50);
	    $this->Cell(100,8,utf8_decode('Asociación de Ganaderos San Luis de la Reina'),1,1,'C');
	    // Movernos a la derecha
	    $this->Cell(50);
	    $this->Cell(100,7,utf8_decode('Balance de comprobación'),1,1,'C');
	    // Movernos a la derecha
	    $this->Cell(50);
	    $this->Cell(100,7,utf8_decode('Periodo contable mes '. $mes),1,1,'C');

	    // Arial bold 15
	    $this->SetFont('Arial','B',11);
	    // Movernos a la derecha
	    $this->Cell(20);
	    $this->Cell(14, 6, 'Cod.', 1,0, 'C', 0);
	    $this->Cell(100, 6, 'Nombre', 1,0, 'C', 0);
	    $this->Cell(18, 6, 'DEBE', 1,0, 'C', 0);
	    $this->Cell(18, 6, 'HABER', 1,1, 'C', 0);
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

$param = date("yy-m");
$param .= '%';
$db = new Database();
$query = $db->connect()->query("select g.codGrupo, g.nombreGrupo from gruposcuentas g");
$grupos = $query->fetchAll();


$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

	$ttldebe = 0;
	$ttlhaber = 0;

foreach ($grupos as $grupo) {
		
		//Movernos
		$pdf->Cell(20);
		$pdf->SetFont('times','B',10);
		$pdf->Cell(14, 6, "", 1,0, 'C', 0);
		$pdf->Cell(100, 6, $grupo[1], 1,0, '', 0);
		$pdf->Cell(18, 6, '', 1,0, 'C', 0);
		$pdf->Cell(18, 6, '', 1,1, 'C', 0);
		$pdf->SetFont('times','',10);

		$query = null; // obligado para cerrar la conexión
        $db = null;
        $db = new Database();

		$query = $db->connect()->query("select d.codCuenta, c.nombreCuenta, tt.nombreTrans,
case
when ((select sum(d2.montoU) monto from detallestransferencias d2 where d2.codTipo in (1,4) and d2.codCuenta = d.codCuenta group by d2.codCuenta, d2.codTipo) - 
(select sum(d1.montoU) monto from detallestransferencias d1 where d1.codTipo in (2,3) and d1.codCuenta = d.codCuenta group by d1.codCuenta, d1.codTipo) is null)
then
	d.montoU
else 
	ABS((select sum(d2.montoU) monto from detallestransferencias d2 where d2.codTipo in (1,4) and d2.codCuenta = d.codCuenta group by d2.codCuenta, d2.codTipo) - 
(select sum(d1.montoU) monto from detallestransferencias d1 where d1.codTipo in (2,3) and d1.codCuenta = d.codCuenta group by d1.codCuenta, d1.codTipo))
end as monto 
from detallestransferencias d 
inner join cuentas c on c.codCuenta = d.codCuenta
inner join tipotrans tt on tt.codTipo = d.codTipo
inner join transferencias t on t.codTrans = d.codTrans
where t.fechatrans like '$param' and c.codGrupo = $grupo[0]
group by d.codCuenta");

		$cuentas = $query->fetchAll();

		foreach ($cuentas as $cuenta) {
			// Movernos a la derecha
			if($cuenta[3] != ''){
			    $pdf->Cell(20);
				$pdf->Cell(14, 6, $cuenta[0], 1,0, 'C', 0);
			    $pdf->Cell(100, 6, utf8_decode($cuenta[1]), 1,0, '', 0);
			    if ($cuenta[2] == 'DEBE' or $cuenta[2] == 'COMPRA') {
			    	$pdf->Cell(18, 6, number_format((float)($cuenta[3]), 2, '.', ''), 1,0, 'C', 0);
			    	$pdf->Cell(18, 6, '', 1,1, 'C', 0);
			    	$ttldebe += number_format((float)($cuenta[3]), 2, '.', '');
			    }else{
			    	$pdf->Cell(18, 6, '', 1,0, 'C', 0);
			    	$pdf->Cell(18, 6, number_format((float)($cuenta[3]), 2, '.', ''), 1,1, 'C', 0);
			    	$ttlhaber += number_format((float)($cuenta[3]), 2, '.', '');
			    }
			}
		}
	    
}
//Totales
$pdf->Cell(34);//Alinearlo
$pdf->Cell(100, 6, 'Totales', 1,0, 'C', 0);
$pdf->Cell(18, 6, $ttldebe, 1,0, 'C', 0);
$pdf->Cell(18, 6, $ttlhaber, 1,0, 'C', 0);

$pdf->Output();
