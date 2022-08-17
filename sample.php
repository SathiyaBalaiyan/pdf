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
    public function Header()
    {
        $this->SetFillColor(28,86,168);
        $this->SetDrawColor(0,0,0);
        $this->Rect(0,0,75,300,"DF");
        $this->Image('apple.jpg',20,20,30);
        
        $this->Cell(20,30,'',0,1); 
        $this->SetFont('Times','',10);
        $this->SetTextColor(0,0,0);  
		$this->Ln(10);
    }

    function LeftColumn($db)
    {   
        $this->SetFont('Times','',10);
        $this->SetTextColor(0,0,0); 

        $x = $this->GetX();
        $y = $this->GetY();

        // $test = json_decode(file_get_contents("php://input", true));
        // $userId = $test->userId;

        $stmt = $db->query("SELECT u.username, u.email, u.mobile, u.address, a.programming, a.server_database, a.operating_system, a.certification, a.basic_knowledge FROM users u INNER JOIN academic a ON u.id = a.user_id WHERE u.id = '1' ");

        while($data = $stmt->fetch(PDO::FETCH_OBJ))
        {
            $this->SetXY($x -7, $y -25);
            $this->Cell(18,6,'NAME ',0,0,'L');
            $this->SetXY($x +11, $y -25); 
            $this->MultiCell(53,6,': ' . $data->username,0,1); 

            $this->SetXY($x -7, $y -18);              
            $this->Cell(18,6,'EMAIL',0,0,'L');	
            $this->SetXY($x +11, $y -18);
            $this->MultiCell(53,6,': ' . $data->email,0,1);

            $this->SetXY($x -7, $y -11);	
            $this->Cell(18,6,'MOBILE',0,0,'L');
            $this->SetXY($x +11, $y -11);
            $this->Cell(53,6,': ' . $data->mobile,0,1);
               
            $this->SetXY($x -7, $y -4);
            $this->Cell(18,6,'ADDRESS',0,0,'L');
            $this->SetXY($x +11, $y -4);
            $this->MultiCell(53,6,': ' . $data->address,0,1); 

            //$this->Line(9,115,65,115); //To draw horizontal line

            $this->SetXY($x +20, $y +30);
            $this->SetFont('Times','B',12);
            $this->Cell(18,6,'SKILLS',0,0);
            // $this->SetXY($x +11, $y +3);
            // $this->MultiCell(53,6,)
            $this->Ln();
        }
    }
}

/* Instanciation of inherited class */
$pdf = new myPDF();
$pdf->AddPage();

$pdf->Header();
$pdf->LeftColumn($db);
$pdf->Output();

?>
