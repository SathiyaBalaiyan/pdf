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
        $this->Cell(20,30,'',1,1); 
        //$this->Line(89,35,290,35); //To draw horizontal line
		//To insert logo in pdf
        $this->Image('apple.jpg',27,20,30);

        $this->SetFont('Times','',10);
        $this->SetTextColor(0,0,0); 
        //$this->Cell(0,10,'',0,1);  //to leave empty space     
		$this->Ln(10);
    }

    function LeftColumn($db)
    {
        $this->Header();
        //$this->Cell(0,30,'',0,1);  //to leave empty space  
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
            $this->SetXY($x + 85, $y -130);
            $this->Cell(205,7,'CAREER OBJECTIVE',1,1,'L');
            $this->SetFillColor(255,255,255);
           
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

        $this->SetXY($x + 85, $y -200);
        //$this->Cell(0,5,'',0,1);  
        $this->Cell(205,7,'EDUCATION DETAILS',1,1,'L');

        $this->SetFont('Times','B',11);
        $this->SetXY($x + 90, $y -190);
        $this->Cell(70,10,'College',1,0,'C');
        $this->Cell(50,10,'Course',1,0,'C');
        $this->Cell(40,10,'Year of Completion',1,0,'C');
        $this->Cell(35,10,'Percentage',1,1,'C');

        // $stmt = $db->query("SELECT a.college, a.course, a.year_of_passing, a.percentage, p.project_title, p.frontend, p.backend, p.role, p.achievements, pe.date_of_birth, pe.father_name, pe.gender, pe.marital_status, pe.nationality FROM academic a INNER JOIN projects p ON a.user_id = p.user_id INNER JOIN personal_details pe ON p.user_id = pe.user_id AND a.user_id = pe.user_id WHERE a.user_id = '1' ");
        $stmt = $db->query("SELECT college, course, year_of_passing, percentage, x_school, x_course, x_yop, x_percentage, xii_school, xii_course, xii_yop, xii_percentage, pg_college, pg_course, pg_yop, pg_percentage FROM academic WHERE user_id = '1' ");

        while($data = $stmt->fetch(PDO::FETCH_OBJ))
        { 
            $this->SetFont('Times','',8);
            $this->SetXY($x + 90, $y -180);
            $this->MultiCell(70,6,$data->x_school,1);
            $this->SetXY($x + 160, $y -180);
            $this->MultiCell(50,6,$data->x_course,0);
            $this->SetXY($x + 210, $y -180);
            $this->Cell(40,6,$data->x_yop,1,0,'C');
            $this->Cell(35,6,$data->x_percentage,1,0,'C');

            $this->SetXY($x + 90, $y -168);
            $this->MultiCell(70,6,$data->xii_school,1);
            $this->SetXY($x + 160, $y -168);
            $this->MultiCell(50,6,$data->xii_course,1);
            $this->SetXY($x + 210, $y -168);
            $this->Cell(40,6,$data->xii_yop,1,0,'C');
            $this->Cell(35,6,$data->xii_percentage,1,0,'C');

            $this->SetXY($x + 90, $y -156);
            $this->MultiCell(70,6,$data->college,1);
            $this->SetXY($x + 160, $y -156);
            $this->MultiCell(50,6,$data->course,1);
            $this->SetXY($x + 210, $y -156);
            $this->Cell(40,6,$data->year_of_passing,1,0,'C');
            $this->Cell(35,6,$data->percentage,1,0,'C');

            if ($data->pg_college && $data->pg_course && $data->pg_yop && $data->pg_percentage == '-')
            {
                $this->SetXY($x + 90, $y -144);
                $this->MultiCell(70,6,$data->pg_college,1);
                $this->SetXY($x + 160, $y -144);
                $this->MultiCell(50,6,$data->pg_course,1);
                $this->SetXY($x + 210, $y -144);
                $this->Cell(40,6,$data->pg_yop,1,0,'C');
                $this->Cell(35,6,$data->pg_percentage,1,0,'C');
            }
            else
            {
                $this->SetXY($x + 90, $y -144);
                $this->MultiCell(70,6,$data->pg_college,1);
                $this->SetXY($x + 160, $y -144);
                $this->MultiCell(50,6,$data->pg_course,1);
                $this->SetXY($x + 210, $y -144);
                $this->MultiCell(40,6,$data->pg_yop,1);
                $this->SetXY($x + 250, $y -144);
                $this->MultiCell(35,6,$data->pg_percentage,1);
            }
           

//-------------------------------------------------------------------
//             $this->SetXY($x + 85, $y -155);
//             $this->SetFont('Times','B',12);
//             $this->Cell(205,7,'PROJECTS',1,1,'L');
//             $this->SetXY($x + 90, $y -145);
//             $this->SetFont('Times','B',11);
//             $this->Cell(100,10,$this->RightSideTextWrapping($data->project_title), 0, 1, 'L'); 

//             $this->SetFont('Times','',11);
//             $this->SetXY($x + 90, $y -140);
//             $this->Cell(50,10,'Frontend :',0,0,'L');
//             $this->SetXY($x + 107, $y -138);
//             $this->SetFont('Times','',11);
//             $this->Cell(100,10,$this->RightInSideTextWrapping($data->frontend), 0, 1, 'L'); 

//             $this->SetXY($x + 90, $y -133);
//             $this->Cell(50,10,'Backend :',0,1,'L');
//             $this->SetXY($x + 107, $y -131);
//             $this->SetFont('Times','',11);
//             $this->Cell(100,10,$this->RightInSideTextWrapping($data->backend), 0, 1, 'L'); 

//             $this->SetXY($x + 90, $y -126);
//             $this->Cell(50,10,'Role :',0,1,'L');
//             $this->SetXY($x + 101, $y -124);
//             $this->SetFont('Times','',11);
//             $this->Cell(100,10,$this->RightInSideTextWrapping($data->role), 0, 1, 'L'); 

//             $this->SetXY($x + 90, $y -119);
//             $this->Cell(50,10,'Achievements :',0,1,'L');
//             $this->SetXY($x + 116, $y -117);
//             $this->SetFont('Times','',11);
//             $this->Cell(255,10,$this->RightInSideTextWrapping($data->achievements), 0, 1, 'L'); 
// //-------------------------------------------------------------------
//             $this->SetXY($x + 85, $y -101);
//             $this->SetFont('Times','B',12);
//             $this->Cell(205,7,'PERSONAL DETAILS',1,1,'L');
            $this->Ln();
        }
    }

    function RightInSideTextWrapping($wrapping)
    {
        if ($wrapping)
        {	
            $cellWidth=45;
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
$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage('L', 'A4', 0);
$pdf->SetMargins(3, 44, 11.7);
$pdf->Header();
$pdf->LeftColumn($db);

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
