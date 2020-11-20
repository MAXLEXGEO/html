<?php
//LIBRERIA PARA GENERAR PDF
require('fpdf/fpdf.php');
//ZONA HORARIA
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'spanish');

class PDF extends FPDF
{
	function Header()
	{	
		//FECHA DEL REPORTE EN ESPAÑOL
		$fecha_pdf = utf8_encode(strftime("%A, %d de %B de %Y"));

		//CONSULTA EL CERTIFICADO EN CASO DE HABERLO FILTRADO
		$sql_cert = "SELECT Certificate FROM vw_dh_assay WHERE year='".$_REQUEST['year']."' AND project_id='".$_REQUEST['project_id']."' AND hole_id='".$_REQUEST['hole_id']."' LIMIT 1";
		$record_cert = mysqli_query(conn(), $sql_cert);
		$data_cert	 = mysqli_fetch_array($record_cert);

		//VARIABLES PARA PINTAR EN CABECERA HOLE_ID Y CERTIFICATE
		if($data_cert == 0){$certificate = '';}else{$certificate = 'Certificate: '.$data_cert[0];}
		if($_REQUEST['hole_id'] == '0'){$hole_id = '';}else{$hole_id = 'Hole_ID: '.$_REQUEST['hole_id'];}

		//CABECERA PDF
		$this->SetFont('Helvetica','B',14);
		$this->SetFillColor(137, 137, 137);
		$this->SetTextColor(255,255,255);
		$this->Cell(110,10, 'Composite Report',0,0,'R',1);
		$this->SetFont('Helvetica','B',9);
		$this->Cell(80,10, utf8_decode($fecha_pdf),0,1,'C',1);
		
		$this->SetFont('Arial','B',9);
		$this->Cell(110,10, 'Hole_ID: '.$_REQUEST['hole_id'],0,0,'R',1);
		$this->Cell(80,10, $certificate,0,1,'C',1);
		$this->Image('../../assets/images/logo_report.png',12,13,15);
		$this->Ln(5);
	}
	
	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I', 8);
		$this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
	}		
}