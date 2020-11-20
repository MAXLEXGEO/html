<?php
	//INVOCAR EL ARCHIVO DE CONEXION
	include 'assets/scripts/php/connection.php';

	//RECIBE LA CUPONERA
	$cuponera = $_REQUEST['cuponera'];//REQUIRED

	//EVALUA RECIBIR EL ID DE LA CUPONERA
	if(empty($cuponera) || empty($cuponera)){ unset($cuponera);  echo "<script>window.close();</script>"; die();}

	//REALIZA LAS CONSULTA PARA EL R

	#COMPOSITE
	$sql_composite = "SELECT c.hole_id,c.from,c.to,c.interval,lv.description,c.avg_au_ppm,c.avg_ag_ppm,c.avg_ageq,c.avg_cu_pct,c.avg_pb_pct,c.avg_zn_pct FROM vw_dh_composite AS c INNER JOIN Library_vein AS lv ON lv.vein = c.vein_id  WHERE c.hole_id = '$hole'";
	$records_composite = mysqli_query(conn(), $sql_composite);
	$i 		 = 0;
	$array_composite = null;

	//GENERA ARREGLO DE DATOS
	while($data = mysqli_fetch_array($records_composite)){
	    $array_composite[$i] = $data;
	    $i += 1;
	}


	#DRILLING VEIN
	$sql_vein = "SELECT * FROM Drilling_vein WHERE hole_id='$hole'";
	$records_vein =  mysqli_query(conn(), $sql_vein);
	$i 		 = 0;
	$array_vein = null;

	//GENERA ARREGLO DE DATOS
	while($data = mysqli_fetch_array($records_vein)){
	    $array_vein[$i] = $data;
	    $i += 1;
	}

	//INCLUDE PDF TEMPLATE HEADER AND FOOTER
	include 'download-assay-vein-template.php';

	//INICIALIZACION DE LA LIBRERIA
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();

	//CONFIGURACION DE CABECERAS DE TABLAS
	$pdf->SetFillColor(137, 137, 137);
	$pdf->SetFont('Helvetica','B',9);
	$pdf->SetTextColor(95,95,95);
	$pdf->Cell(190,10,'Composite',0,1,'C',0);

	$pdf->SetTextColor(255,255,255);
	$pdf->SetFont('Helvetica','B',8);
	$pdf->Cell(15,5,'From',0,0,'C',1);
	$pdf->Cell(15,5,'To',0,0,'C',1);
	$pdf->Cell(15,5,'Int:',0,0,'C',1);
	$pdf->Cell(30,5,'Vein lib',0,0,'C',1);
	$pdf->Cell(15,5,'Au ppm',0,0,'C',1);
	$pdf->Cell(15,5,'Ag ppm',0,0,'C',1);
	$pdf->Cell(31,5,'AgEq',0,0,'C',1);
	$pdf->Cell(18,5,'Cu pct',0,0,'C',1);
	$pdf->Cell(18,5,'Pb pct',0,0,'C',1);
	$pdf->Cell(18,5,'Zn pct',0,1,'C',1);

	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(95,95,95);

	foreach($array_composite AS $dat)
	{	
		$pdf->Cell(15,6,$dat['from'],'LRB',0,'C');
		$pdf->Cell(15,6,$dat['to'],'RB',0,'C');
		$pdf->Cell(15,6,number_format($dat['interval'],2),'RB',0,'C');
		$pdf->Cell(30,6,$dat['description'],'RB',0,'C');
		$pdf->Cell(15,6,number_format($dat['avg_au_ppm'],3),'RB',0,'C');
		$pdf->Cell(15,6,number_format($dat['avg_ag_ppm'],2),'RB',0,'C');
		$pdf->Cell(31,6,$dat['avg_ageq'],'RB',0,'C');
		$pdf->Cell(18,6,number_format($dat['avg_cu_pct'],3),'RB',0,'C');
		$pdf->Cell(18,6,number_format($dat['avg_pb_pct'],3),'RB',0,'C');
		$pdf->Cell(18,6,number_format($dat['avg_zn_pct'],3),'RB',1,'C');
	}

	$pdf->Ln(5);
	$pdf->SetFillColor(137, 137, 137);
	$pdf->SetTextColor(255,255,255);
	$pdf->SetFont('Helvetica','B',8);
	$pdf->Cell(35,5,'From',0,0,'C',1);
	$pdf->Cell(35,5,'To',0,0,'C',1);
	$pdf->Cell(35,5,'Int:',0,0,'C',1);
	$pdf->Cell(35,5,'Geologist ID',0,0,'C',1);
	$pdf->Cell(50,5,'Comments',0,1,'C',1);

	$pdf->SetFont('Arial','',8);
	$pdf->SetTextColor(95,95,95);

	foreach($array_vein AS $dat){

		$pdf->Cell(35,6,$dat['from'],'R',0,'C',0);
		$pdf->Cell(35,6,$dat['to'],'R',0,'C',0);
		$pdf->Cell(35,6,$dat['interval'],'R',0,'C',0);
		$pdf->Cell(35,6,$dat['geologist_id'],'R',0,'C',0);
		$pdf->Cell(50,6,utf8_decode($dat['comments']),0,1,'C',0);

	}
	//RENDERIZADO DEL PDF
	$pdf->Output();