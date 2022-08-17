<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');

require ('fpdf.php');

//Server details
$dsn = "mysql:host=localhost;dbname=resume";

//MSSQL connection
$db = new PDO($dsn, 'root', '');
$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

//define('FPDF_FONTPATH','/xampp/htdocs/resume_api/fpdf/font');
define('FPDF_FONTPATH', 'font/');

class myPDF extends FPDF
{
    // Page header
    function Header()
    {
        $this->Cell(50,30,'',1); 
        // //$this->Line(89,35,290,35); //To draw horizontal line
		// //To insert logo in pdf
        // $this->Image('apple.jpg',27,20,30);

        // $this->SetFont('Times','',10);
        // $this->SetTextColor(0,0,0); 
        // //$this->Cell(0,10,'',0,1);  //to leave empty space     
		$this->Ln(10);
    }
}

/* Instanciation of inherited class */
$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage('L', 'A4', 0);
//$pdf->SetMargins(3, 44, 11.7);
$pdf->Header();

//$pdf->LeftColumn($db);

// $pdf->User($db);
// $pdf->AcademicAndProject($db);
$pdf->Output();

// $out = 'upload/inspection-' . microtime(true) . '.pdf';
// $gen = $pdf->Output($out, 'F');

// $link = "http://localhost/pdf/fpdf/";

// $test = json_decode(file_get_contents("php://input", true));
// $userId = $test->userId;

// // $query = $db->query("SELECT username FROM users WHERE id = '".$userId."'");

// //$file_name = str_replace($link, $query, $link.$out);

// $stmt = $db->query("UPDATE users SET pdf_file = '". $link.$out ."' WHERE id = '".$userId."'");
// echo json_encode(['message' => 'Link found', 'status' => 0, 'link' => $link.$out]);

?>
