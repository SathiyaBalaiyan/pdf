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
        $this->Cell(0,10,'',0,1);  //to leave empty space     
		$this->Ln(10);
    }

    function LeftColumn($db)
    {
        $this->Cell(0,30,'',0,1);  //to leave empty space       
        // $this->SetDrawColor(28,86,168);
        // $this->SetLineWidth(100);
        // $this->Line(35,0,35,300);
        
        $this->SetFont('Times','',10);
        $this->SetTextColor(0,0,0); 

        // $test = json_decode(file_get_contents("php://input", true));
        // $userId = $test->userId;

        $stmt = $db->query("SELECT username, email, mobile, address FROM users WHERE id = '1' ");

        while($data = $stmt->fetch(PDO::FETCH_OBJ))
        { 
            $this->Cell(21,6,'NAME',0,0,'L'); 
            $this->Cell(55,6,': ' . $data->username,0,1,'L');               
            $this->Cell(21,6,'EMAIL',0,0,'L');	
            $this->Cell(55,6,': ' . $data->email,0,1,'L');		
            $this->Cell(21,6,'MOBILE',0,0,'L');
            $this->Cell(55,6,': ' . $data->mobile,0,1,'L');     
            $this->Cell(21,6,'ADDRESS',0,0,'L');
            $this->Cell(3,6,':',0,0,'L');
            $this->Cell(55,6,$this->TextWrapping($data->address),0,1,'L'); 
            $this->Ln();
        }
    }

    function User($db)
    {
        $this->SetFont('Times','B',12);
        $this->SetTextColor(0,0,0);

        $x = $this->GetX();
        $y = $this->GetY();
        
        // $test = json_decode(file_get_contents("php://input", true));
        // $userId = $test->userId;

        $stmt = $db->query("SELECT career_objective FROM users WHERE id = '1' ");

        while($data = $stmt->fetch(PDO::FETCH_OBJ))
        { 
            $this->SetXY($x + 85, $y -100);
            $this->Cell(205,7,'CAREER OBJECTIVE',1,1,'L');
           
            $this->SetXY($x + 90, $y -90);
            $this->SetFont('Times','',11);
            $this->Cell(50,10,$this->RightSideTextWrapping($data->career_objective), 0, 0, 'L');    
            $this->Ln();
        }
    }

    function AcademicAndProject($db)
    {
        $this->SetFont('Times','B',12);
        $this->SetTextColor(0,0,0);
        // $this->SetDrawColor(28,68,224);
        // $this->SetLineWidth(100);

        $x = $this->GetX();
        $y = $this->GetY();

        $stmt = $db->query("SELECT a.college, a.course, a.year_of_passing, a.percentage, p.project_title, p.frontend, p.backend, p.role, p.achievements, pe.date_of_birth, pe.father_name, pe.gender, pe.marital_status, pe.nationality FROM academic a INNER JOIN projects p ON a.user_id = p.user_id INNER JOIN personal_details pe ON p.user_id = pe.user_id AND a.user_id = pe.user_id WHERE a.user_id = '1' ");

        while($data = $stmt->fetch(PDO::FETCH_OBJ))
        { 
            //$this->Line(89,35,290,35); //To draw horizontal line
            $this->SetXY($x + 85, $y -200);
            $this->Cell(205,7,'EDUCATION DETAILS',1,1,'L');
            $this->SetXY($x + 90, $y -190);
            $this->SetFont('Times','',11);
            $this->Cell(100,10,$this->RightSideTextWrapping($data->college), 1, 1, 'L'); 

            $this->SetXY($x + 90, $y -185);
            $this->Cell(50,10,'Course :',0,0,'L');
            $this->SetXY($x + 105, $y -183);
            $this->SetFont('Times','',11);
            $this->Cell(100,10,$this->RightInSideTextWrapping($data->course), 0, 1, 'L'); 

            $this->SetXY($x + 90, $y -178);
            $this->Cell(50,10,'Year of completion :',0,0,'L');
            $this->SetXY($x + 124, $y -176);
            $this->SetFont('Times','',11);
            $this->Cell(100,10,$this->RightInSideTextWrapping($data->year_of_passing), 0, 1, 'L'); 

            $this->SetXY($x + 90, $y -171);
            $this->Cell(50,10,'Percentage :',0,0,'L');
            $this->SetXY($x + 111, $y -169);
            $this->SetFont('Times','',11);
            $this->Cell(100,10,$this->RightInSideTextWrapping($data->percentage), 0, 1, 'L'); 
//-------------------------------------------------------------------
            $this->SetXY($x + 85, $y -155);
            $this->SetFont('Times','B',12);
            $this->Cell(205,7,'PROJECTS',1,1,'L');
            $this->SetXY($x + 90, $y -145);
            $this->SetFont('Times','B',11);
            $this->Cell(100,10,$this->RightSideTextWrapping($data->project_title), 0, 1, 'L'); 

            $this->SetFont('Times','',11);
            $this->SetXY($x + 90, $y -140);
            $this->Cell(50,10,'Frontend :',0,0,'L');
            $this->SetXY($x + 107, $y -138);
            $this->SetFont('Times','',11);
            $this->Cell(100,10,$this->RightInSideTextWrapping($data->frontend), 0, 1, 'L'); 

            $this->SetXY($x + 90, $y -133);
            $this->Cell(50,10,'Backend :',0,1,'L');
            $this->SetXY($x + 107, $y -131);
            $this->SetFont('Times','',11);
            $this->Cell(100,10,$this->RightInSideTextWrapping($data->backend), 0, 1, 'L'); 

            $this->SetXY($x + 90, $y -126);
            $this->Cell(50,10,'Role :',0,1,'L');
            $this->SetXY($x + 101, $y -124);
            $this->SetFont('Times','',11);
            $this->Cell(100,10,$this->RightInSideTextWrapping($data->role), 0, 1, 'L'); 

            $this->SetXY($x + 90, $y -119);
            $this->Cell(50,10,'Achievements :',0,1,'L');
            $this->SetXY($x + 116, $y -117);
            $this->SetFont('Times','',11);
            $this->Cell(255,10,$this->RightInSideTextWrapping($data->achievements), 0, 1, 'L'); 
//-------------------------------------------------------------------
            $this->SetXY($x + 85, $y -101);
            $this->SetFont('Times','B',12);
            $this->Cell(205,7,'PERSONAL DETAILS',1,1,'L');
            $this->Ln();
        }
    }

    function RightInSideTextWrapping($wrapping)
    {
        if ($wrapping)
        {	
            $cellWidth=175;
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
            $this->MultiCell($cellWidth,$cellHeight,$wrapping,0);
            
            //return the position for next cell next to the multicell
            //and offset the x with multicell width
            $this->SetXY($xPos + $cellWidth, $yPos);
        }
    }

    function RightSideTextWrapping($wrapping)
    {
        if ($wrapping)
        {	
            $cellWidth=200;
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
            $this->MultiCell($cellWidth,$cellHeight,$wrapping,0);
            
            //return the position for next cell next to the multicell
            //and offset the x with multicell width
            $this->SetXY($xPos + $cellWidth, $yPos);
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
$pdf = new myPDF("P","mm","A4");
$pdf->AliasNbPages();
$pdf->AddPage('L', 'A4', 0);
$pdf->SetMargins(3, 44, 11.7);
$pdf->LeftColumn($db);
$pdf->Header();

$pdf->User($db);
$pdf->AcademicAndProject($db);
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
