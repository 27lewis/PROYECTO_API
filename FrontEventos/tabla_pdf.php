<?php
require('fpdf/fpdf.php');

class PDF_Tabla extends FPDF
{
var $widths;
var $aligns;
var $titulo;
//Funcion Que imprime el Encabezado  de p�gina
function cabecera()
{
   
   
    $w=array(20,40,40,40,20,20);
    $this->SetFont('Arial','B',15);
    //-----------------Imprime el Logo--------------------------
    $logo="logo.jpg";
    $this->Image($logo,8,5,20);
    //Movernos a la derecha
    $this->Cell(30);
    //-------Nombre de la Empresa--------------------------
    $this->MultiCell(0,5,'SISTEMAS DE GESTION DE EVENTOS',0,'C');
     //Movernos a la derecha
    $this->Cell(30);
    //--------------Nit--------------------
     $this->SetFont('Arial','I',9);
     $this->MultiCell(0,5,'Nit: 8606060-1',0,'C');
     //Movernos a la derecha
    $this->Cell(30);

     //--------------------Nombre de la Ciudad-------------------
     $this->SetFont('Arial','B',10);
     $this->MultiCell(0,5,'Tel: 01800911911',0,'C');
     $this->Ln();
     $this->Ln();
      //-------- adiciona el Titulo del Reporte----------------------
     $this->Text(10,33,$this->titulo);
    //----------- Dibuja la linea superior------------------------
    $this->SetLineWidth(.5);
    $this->line(10,35,200,35);
    $this->SetLineWidth(.2);
    
  
}// end cabecera
//------------- Funcion para el pie de pagina--------------------------
function piepagina()
{
    //Posicion: a 1,5 cm del final
    $this->SetY(-15);
     $y=$this->GetY();
    //Arial italic 8
    $this->SetFont('Arial','I',7);
     //----------- Dibuja la linea superior-----------------------------
    $this->SetLineWidth(.5);
    $this->line(10,$y,200,$y);
    $this->SetLineWidth(.2);
    //--------------Enumera las paginas parte ineferior-----------------
       $this->Text(15,$y+3,"Gestion de eventos en linea    Fecha:  ".date("d/m/Y"));
       $this->Text(170,$y+3,"Pagina: ".$this->PageNo()." de {nb}");
    //-----------------------------------------------------------------
    
}
function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}
function SetTitulo($t)
{
	//Set the array of column widths
	$this->titulo=$t;
}
function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function Row($data,$border=1,$alig='J',$fill=0)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
		
		$this->Rect($x,$y,$w,$h);
		//-------------------Print the text-------------------
		$this->MultiCell($w,5,$data[$i],0,$a);
		
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}
}
?>
