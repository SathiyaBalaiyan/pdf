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
        //$this->SetFillColor(28,86,168);
        //$this->SetDrawColor(0,0,0);
        //$this->Rect(0,0,70,300,"F");

        $this->Image('apple.jpg',20,20,30);
        
        $this->Cell(20,30,'',0,1); 
        $this->SetFont('Times','',10);
        $this->SetTextColor(0,0,0);  
		$this->Ln(10);
    }

    function LeftColumn($db)
    {   
        $this->SetFont('Times','',10);
        $this->SetTextColor(255,255,255); 

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
            $this->SetFont('Times','',9);
            $this->MultiCell(45,6,': ' . $data->username,0,1); 

            $this->SetXY($x -7, $y -15);              
            $this->Cell(18,6,'EMAIL',0,0,'L');	
            $this->SetXY($x +11, $y -15);
            $this->SetFont('Times','',9);
            $this->MultiCell(45,6,': ' . $data->email,0,1);

            $this->SetXY($x -7, $y -5);	
            $this->Cell(18,6,'MOBILE',0,0,'L');
            $this->SetXY($x +11, $y -5);
            $this->SetFont('Times','',9);
            $this->Cell(45,6,': ' . $data->mobile,0,1);
               
            $this->SetXY($x -7, $y +5);
            $this->Cell(18,6,'ADDRESS',0,0,'L');
            $this->SetXY($x +11, $y +5);
            $this->SetFont('Times','',9);
            $this->MultiCell(45,5,': ' . $data->address,0,'J'); 

            //$this->Line(9,115,65,115); //To draw horizontal line

            $this->SetXY($x +20, $y +50);
            $this->SetFont('Times','B',12);
            $this->Cell(18,6,'SKILLS',0,0);
            
            $this->SetXY($x -7, $y +60);
            $this->SetFont('Times','',12);
            $this->Cell(18,6,'Programming Languages :',0,1,'L');
            $this->SetXY($x -3, $y +66);
            $this->SetFont('Times','',9);
            $this->MultiCell(50,5,$data->programming,0,'J'); 

            $this->SetXY($x -7, $y +86);
            $this->SetFont('Times','',12);
            $this->Cell(18,6,'Database :',0,1,'L');
            $this->SetXY($x -3, $y +92);
            $this->SetFont('Times','',9);
            $this->MultiCell(50,5,$data->server_database,0,'J'); 

            $this->SetXY($x -7, $y +112);
            $this->SetFont('Times','',12);
            $this->Cell(18,6,'Operating Systems :',0,1,'L');
            $this->SetXY($x -3, $y +118);
            $this->SetFont('Times','',9);
            $this->MultiCell(50,5,$data->operating_system,0,'J'); 

            $this->SetXY($x -7, $y +138);
            $this->SetFont('Times','',12);
            $this->Cell(18,6,'Certifications :',0,1,'L');
            $this->SetXY($x -3, $y +144);
            $this->SetFont('Times','',9);
            $this->MultiCell(50,5,$data->certification,0,'J'); 
            
            $this->SetXY($x -7, $y +164);
            $this->SetFont('Times','',12);
            $this->Cell(18,6,'Other Skills :',0,1,'L');
            $this->SetXY($x -3, $y +170);
            $this->SetFont('Times','',9);
            $this->MultiCell(50,5,$data->basic_knowledge,0,'J'); 
            
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

        $query = $db->query("SELECT red, green, blue FROM users WHERE id = '1' ");
        while($data1 = $query->fetch(PDO::FETCH_OBJ))
        {
            $this->SetXY($x -145, $y -260);  
            $this->SetFillColor($data1->red,$data1->green,$data1->blue);
            $this->Cell(130,7,'CAREER OBJECTIVE',0,1,'L', true);
        }
        $stmt = $db->query("SELECT career_objective FROM users WHERE id = '1' ");

        while($data = $stmt->fetch(PDO::FETCH_OBJ))
        { 
            // $this->SetXY($x -145, $y -260);
            // $this->SetFillColor(224,224,224);
            // $this->Cell(130,7,'CAREER OBJECTIVE',0,1,'L', true);
            
            $this->SetXY($x -145, $y -250);
            $this->SetFont('Times','',9);
            $this->MultiCell(125,5,$data->career_objective, 0,'J');    
            $this->Ln();
        }
    }

    function Academics($db)
    {
        $this->SetFont('Times','B',12);
        $this->SetTextColor(0,0,0);

        $x = $this->GetX();
        $y = $this->GetY();

        // $stmt = $db->query("SELECT a.college, a.course, a.year_of_passing, a.percentage, p.project_title, p.frontend, p.backend, p.role, p.achievements, pe.date_of_birth, pe.father_name, pe.gender, pe.marital_status, pe.nationality FROM academic a INNER JOIN projects p ON a.user_id = p.user_id INNER JOIN personal_details pe ON p.user_id = pe.user_id AND a.user_id = pe.user_id WHERE a.user_id = '1' ");
        $query = $db->query("SELECT red, green, blue FROM users WHERE id = '1' ");
        while($data1 = $query->fetch(PDO::FETCH_OBJ))
        {
            $this->SetXY($x -145, $y -295);  
            $this->SetFillColor($data1->red,$data1->green,$data1->blue);
            $this->Cell(130,7,'EDUCATION DETAILS',0,1,'L',true);
        }
        $stmt = $db->query("SELECT college, course, year_of_passing, percentage, x_school, x_course, x_yop, x_percentage, xii_school, xii_course, xii_yop, xii_percentage, pg_college, pg_course, pg_yop, pg_percentage FROM academic WHERE user_id = '1' ");

        while($data = $stmt->fetch(PDO::FETCH_OBJ))
        { 
            // $this->SetXY($x -145, $y -295);  
            // $this->SetFillColor(224,224,224);
            // $this->Cell(130,7,'EDUCATION DETAILS',0,1,'L',true);

            //Education details table heading
            $this->SetXY($x -145, $y -285);
            $this->SetFillColor(255,255,255);
            $this->SetFont('Times','B',11);
            $this->MultiCell(40,10,'College',1,'C');
            $this->SetXY($x -105, $y -285);
            $this->MultiCell(40,10,'Course',1,'C');
            $this->SetXY($x -65, $y -285);
            $this->MultiCell(25,5,'Year of Completion',1,'C');
            $this->SetXY($x -40, $y -285);
            $this->MultiCell(24,10,'Grade',1,'C');    

            //10th marks
            $this->Line(75,62,75,122); // To draw 1st vertical line
            $this->SetFont('Times','',9);
            $this->SetXY($x -145, $y -275);
            $this->MultiCell(40,5,$data->x_school,0);
            $this->SetXY($x -105, $y -275);
            $this->MultiCell(40,5,$data->x_course,0);
            $this->SetXY($x -65, $y -275);
            $this->MultiCell(25,15,$data->x_yop,1,'C');
            $this->SetXY($x -40, $y -275);
            $this->MultiCell(24,15,$data->x_percentage,1,'C');
            $this->Line(75,77,204,77); //To draw 1st horizontal line

            //12th or diploma marks
            $this->Line(115,62,115,122); // To draw 1st vertical line
            $this->SetXY($x -145, $y -260);
            $this->MultiCell(40,5,$data->xii_school,0);
            $this->SetXY($x -105, $y -260);
            $this->MultiCell(40,5,$data->xii_course,0);
            $this->SetXY($x -65, $y -260);
            $this->MultiCell(25,15,$data->xii_yop,1,'C');
            $this->SetXY($x -40, $y -260);
            $this->MultiCell(24,15,$data->xii_percentage,1,'C');
            $this->Line(75,92,204,92); //To draw 2nd horizontal line

            //UG marks
            $this->SetXY($x -145, $y -245);
            $this->MultiCell(40,5,$data->college,0);
            $this->SetXY($x -105, $y -245);
            $this->MultiCell(40,5,$data->course,0);
            $this->SetXY($x -65, $y -245);
            $this->MultiCell(25,15,$data->year_of_passing,1,'C');
            $this->SetXY($x -40, $y -245);
            $this->MultiCell(24,15,$data->percentage,1,'C');
            $this->Line(75,107,204,107); //To draw 3rd horizontal line

            //PG marks
            if ($data->pg_college && $data->pg_course && $data->pg_yop && $data->pg_percentage == '-')
            {
                $this->SetXY($x -145, $y -230);
                $this->MultiCell(40,15,$data->pg_college,0,'C');
                $this->SetXY($x -105, $y -230);
                $this->MultiCell(40,15,$data->pg_course,0,'C');
                $this->SetXY($x -65, $y -230);
                $this->MultiCell(25,15,$data->pg_yop,1,'C');
                $this->SetXY($x -40, $y -230);
                $this->MultiCell(24,15,$data->pg_percentage,1,'C');
                $this->Line(75,122,204,122); //To draw 4th horizontal line
            }
            else
            {
                $this->SetXY($x -145, $y -230);
                $this->MultiCell(40,5,$data->pg_college,0,);
                $this->SetXY($x -105, $y -230);
                $this->MultiCell(40,5,$data->pg_course,0);
                $this->SetXY($x -65, $y -230);
                $this->MultiCell(25,15,$data->pg_yop,1,'C');
                $this->SetXY($x -40, $y -230);
                $this->MultiCell(24,15,$data->pg_percentage,1,'C');
                $this->Line(75,122,204,122); //To draw 4th horizontal line
            }
        }
    }

    function Projects($db)
    {
        $this->SetFont('Times','B',12);
        $this->SetTextColor(0,0,0);

        $x = $this->GetX();
        $y = $this->GetY();

        $query = $db->query("SELECT red, green, blue FROM users WHERE id = '1' ");
        while($data1 = $query->fetch(PDO::FETCH_OBJ))
        {
            $this->SetXY($x -145, $y -290);  
            $this->SetFillColor($data1->red,$data1->green,$data1->blue);
            $this->Cell(130,7,'PROJECTS',0,1,'L',true);
        }

        $stmt = $db->query("SELECT project_title, frontend, backend, role, achievements FROM projects WHERE user_id = '1' ");

        while($data = $stmt->fetch(PDO::FETCH_OBJ))
        { 
            // $this->SetXY($x -145, $y -290);  
            // $this->SetFillColor(224,224,224);
            // $this->Cell(130,7,'PROJECTS',0,1,'L',true);

            //Project details table heading
            $this->SetXY($x -145, $y -280);
            $this->SetFillColor(255,255,255);
            $this->SetFont('Times','B',11);
            $this->MultiCell(40,10,'Title',1,'C');
            $this->SetXY($x -105, $y -280);
            $this->MultiCell(30,10,'Frontend',1,'C');
            $this->SetXY($x -75, $y -280);
            $this->MultiCell(30,10,'Backend',1,'C');
            $this->SetXY($x -45, $y -280);
            $this->MultiCell(29,10,'Role',1,'C');    

            $this->Line(75,149,75,179); // To draw 1st vertical line
            $this->SetFont('Times','',9);
            $this->SetXY($x -145, $y -270);
            $this->MultiCell(40,5,$data->project_title,0);

            $this->Line(115,149,115,179); // To draw 2nd vertical line
            $this->SetXY($x -105, $y -270);
            $this->MultiCell(30,5,$data->frontend,0);

            $this->Line(145,149,145,179); // To draw 3rd vertical line
            $this->SetXY($x -75, $y -270);
            $this->MultiCell(30,5,$data->backend,0);

            $this->Line(175,149,175,179); // To draw 4th vertical line
            $this->SetXY($x -45, $y -270);
            $this->MultiCell(24,5,$data->role,0);
            $this->Line(204,149,204,179); // To draw 5th vertical line
            $this->Line(75,179,204,179); //To draw horizontal line

            // $this->SetXY($x -145, $y -233); 
            // $this->SetFont('Times','B',12); 
            // $this->SetFillColor(224,224,224);
            // $this->Cell(130,7,'ACHIEVEMENTS',0,1,'L',true);

            // $this->SetXY($x -145, $y -223);
            // $this->SetFont('Times','',9);
            // $this->MultiCell(129,25,$data->achievements,1,'J');    
            $this->Ln();
        }
    }

    function PersonalDetails($db)
    {
        $this->SetFont('Times','',10);
        $this->SetTextColor(0,0,0); 

        $x = $this->GetX();
        $y = $this->GetY();

        // $test = json_decode(file_get_contents("php://input", true));
        // $userId = $test->userId;

        $query = $db->query("SELECT red, green, blue FROM users WHERE id = '1' ");
        while($data1 = $query->fetch(PDO::FETCH_OBJ))
        {
            $this->SetXY($x -145, $y -276);  
            $this->SetFont('Times','B',12); 
            $this->SetFillColor($data1->red,$data1->green,$data1->blue);
            $this->Cell(130,7,'PERSONAL DETAILS',0,1,'L',true);
        }
        $stmt = $db->query("SELECT father_name, date_of_birth, gender, marital_status, nationality,place, date, signature FROM personal_details WHERE user_id = '1' ");

        while($data = $stmt->fetch(PDO::FETCH_OBJ))
        {
            // $this->SetXY($x -145, $y -276); 
            // $this->SetFont('Times','B',12); 
            // $this->SetFillColor(224,224,224);
            // $this->Cell(130,7,'PERSONAL DETAILS',0,1,'L',true);

            $this->SetXY($x -145, $y -266);
            $this->SetFont('Times','',9);
            $this->Cell(30,6,'Father Name',0,0,'L');
            $this->SetXY($x -115, $y -266); 
            $this->Cell(45,6,': ' . $data->father_name,0,1); 

            $this->SetXY($x -145, $y -261);
            $this->Cell(30,6,'DOB',0,0,'L');
            $this->SetXY($x -115, $y -261); 
            $this->Cell(45,6,': ' . $data->date_of_birth,0,1);

            $this->SetXY($x -145, $y -256);
            $this->Cell(30,6,'Gender',0,0,'L');
            $this->SetXY($x -115, $y -256); 
            $this->Cell(45,6,': ' . $data->gender,0,1);

            $this->SetXY($x -145, $y -251);
            $this->Cell(30,6,'Marital Status',0,0,'L');
            $this->SetXY($x -115, $y -251); 
            $this->Cell(45,6,': ' . $data->marital_status,0,1);

            $this->SetXY($x -145, $y -246);
            $this->Cell(30,6,'Nationality',0,0,'L');
            $this->SetXY($x -115, $y -246); 
            $this->Cell(45,6,': ' . $data->nationality,0,1);

            $this->SetXY($x -145, $y -200);
            $this->Cell(10,6,'Place',0,0,'L');
            $this->SetXY($x -135, $y -200); 
            $this->Cell(20,6,': ' . $data->place,0,1);

            $this->SetXY($x -145, $y -195);
            $this->Cell(10,6,'Date',0,0,'L');
            $this->SetXY($x -135, $y -195); 
            $this->Cell(20,6,': ' . $data->date,0,0);

            $this->SetXY($x -55, $y -210); 
            $this->Cell(20,6,'',0,1);
            //$this->Image('/signature'. $data->signature,20,20,30);
            $this->SetXY($x -55, $y -195);
            $this->Cell(20,6,'Signature :',0,0,'L');
            
            $this->Ln();
        }
    }
}

/* Instanciation of inherited class */
$pdf = new myPDF();
$pdf->AddPage();

$stmt = $db->query("SELECT red, green, blue FROM users WHERE id = '1' ");
while($data = $stmt->fetch(PDO::FETCH_OBJ))
{
    $pdf->SetFillColor($data->red,$data->green,$data->blue);
    $pdf->Rect(0,0,70,300,"F");
}

$pdf->Header();
$pdf->LeftColumn($db);
$pdf->User($db);
$pdf->Academics($db);
$pdf->Projects($db);
$pdf->PersonalDetails($db);
$pdf->Output();

?>
