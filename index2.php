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
		//To insert logo in pdf
        $this->Image('apple.jpg',27,20,30);
		$this->Ln(10);
    }

    function LeftColumn()
    {
        $this->SetDrawColor(28,86,168);
        $this->SetLineWidth(100);
        $this->Line(35,0,35,300);
        $this->Ln();
    }

    function User()
    {
        $this->SetFont('Times','',10);
        $this->SetTextColor(255,255,255); 

       
            $this->Cell(0,30,'',0,1);  //to leave empty space 

            $this->Cell(21,6,'NAME',0,1,'L'); 
            $this->Cell(21,6,'EMAIL',0,1,'L');	
            $this->Cell(21,6,'MOBILE',0,1,'L');
            $this->Cell(21,6,'ADDRESS',0,1,'L');
                 

            
    }
    function Data($db)
    {
        $this->SetFont('Times','',10);
        $this->SetTextColor(255,255,255); 

        $test = json_decode(file_get_contents("php://input", true));
        $userId = $test->userId;

        $stmt = $db->query("SELECT username, email, mobile, address FROM users WHERE id = '".$userId."' ");

        while($data = $stmt->fetch(PDO::FETCH_OBJ))
        { 
             //to leave empty space 

            // $this->Cell(21,6,'NAME',0,0,'L'); 
            $this->Cell(21,6,': ' . $data->username,0,1,'L');               
            // $this->Cell(21,6,'EMAIL',0,0,'L');	
            $this->Cell(21,6,': ' . $data->email,0,1,'L');		
            // $this->Cell(21,6,'MOBILE',0,0,'L');
            $this->Cell(21,6,': ' . $data->mobile,0,1,'L');     
            // $this->Cell(21,6,'ADDRESS',0,0,'L');
            $this->Cell(3,6,':',0,0,'L');
            $this->Cell(21,6,$this->TextWrapping($data->address),0,1,'L');     

            $this->Ln();
        }
    }

    function TextWrapping($wrapping)
    {
        if ($wrapping)
        {	
            $cellWidth=50;
            $cellHeight=6;
            
            //check whether the text is overflowing
            if($this->GetStringWidth($wrapping) < $cellWidth)
            {
                //if not, then do nothing
                $line=1;
            }
            else
            {
                $textLength=strlen($wrapping);	//total text length
                $errMargin=10;		//cell width error margin, just in case
                $startChar=0;		//character start position for each line
                $maxChar=0;			//maximum character in a line, to be incremented later
                $textArray=array();	//to hold the strings for each line
                $tmpString="";		//to hold the string for a line (temporary)
                
                while($startChar < $textLength)
                {   
                    while( 
                    $this->GetStringWidth( $tmpString ) < ($cellWidth-$errMargin) &&
                    ($startChar+$maxChar) < $textLength ) 
                    {
                        $maxChar++;
                        $tmpString=substr($wrapping,$startChar,$maxChar);
                    }
                    //move startChar to next line
                    $startChar=$startChar+$maxChar;
                    //then add it into the array so we know how many line are needed
                    array_push($textArray,$tmpString);
                    //reset maxChar and tmpString
                    $maxChar=0;
                    $tmpString='';	
                }
                //get number of line
                $line=count($textArray);
            }
            $xPos=$this->GetX();
            $yPos=$this->GetY();
            $this->MultiCell(50,$cellHeight,$wrapping,0);
            
            //return the position for next cell next to the multicell
            //and offset the x with multicell width
            $this->SetXY($xPos + $cellWidth, $yPos);
        }
    }
}

/* Instanciation of inherited class */
$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage('L', 'A4', 0);
$pdf->SetMargins(3, 44, 11.7);
$pdf->LeftColumn();
$pdf->Header();

$pdf->User();
$pdf->Data($db);
$pdf->Output();

// $out = 'upload/inspection-' . microtime(true) . '.pdf';
// $gen = $pdf->Output($out, 'F');

// ///$link = "http://demo.azonix.in:10557/audit/inspection/";
// $link = "http://localhost/pdf/fpdf/";

// $test = json_decode(file_get_contents("php://input", true));
// $userId = $test->userId;

// $stmt = $db->query("UPDATE users SET pdf_file = '".$link.$out."' WHERE id = '".$userId."'");
// echo json_encode(['message' => 'Link found', 'status' => 0, 'link' => $link.$out]);

?>
