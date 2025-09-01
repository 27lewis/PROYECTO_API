<?php
//require('fpdf/fpdf.php');
require('tabla_pdf.php');
define('FPDF_FONTPATH','fpdf/font/');
$mat = array();

class PDF extends PDF_Tabla
{
    //Cabecera de pagina
    function Header()
    {
       $this->SetTitulo('Listado de clientes');
       $this->cabecera();
       //Salto de linea
       $this->Ln(3);
       $this->Tabla();
    }

    //Pie de pagina
    function Footer()
    {
       $this->piepagina();
    }

    function Tabla()
    {
       global $mat; // matriz para guardar los datos de la consulta
       $curl = curl_init();

       curl_setopt_array($curl, array(
          CURLOPT_URL => "http://localhost/APIeventos/clientes/get_clientes.php",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
       ));

       $response = curl_exec($curl);
       $err = curl_error($curl);
       curl_close($curl);

       if ($err) {
          echo "cURL Error #:" . $err;
          exit;
       }

       $objeto = json_decode($response);

       if (json_last_error() !== JSON_ERROR_NONE) {
          echo "Error al decodificar JSON: " . json_last_error_msg();
          var_dump($response);
          exit;
       }

       if (!is_array($objeto) && !is_object($objeto)) {
          echo "La API no devolvió un formato válido.";
          exit;
       }

       $i = 0;
       foreach ($objeto as $reg) {
          $mat[$i]["nombres"] = $reg->nombres;
          $mat[$i]["apellidos"] = $reg->apellidos;
          $mat[$i]["direccion"] = $reg->direccion;
          $mat[$i]["telefono"] = $reg->telefono;
          $mat[$i]["correo"] = $reg->correo;
          $i++;
       }

       // Configuración para la tabla
       $this->SetFillColor(255, 255, 255);
       $this->SetTextColor(0);
       $this->SetDrawColor(0, 0, 0);
       $this->SetLineWidth(.2);
       $this->SetFont('Arial', 'B', 10);

       $cabecera = ['No', 'Nombres', 'Apellidos', 'Direccion', 'Telefono', 'Correo'];
       $this->SetWidths([10, 30, 30, 40, 30, 50]); // define el ancho de la columnas
       for ($i = 0; $i < count($cabecera); $i++)
          $this->Cell($this->widths[$i], 5, $cabecera[$i], 1, 0, 'J', 1);
       $this->Ln();
    }
} // FIN DE LA CLASE

// --- Código fuera de la clase ---

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

global $mat;
for ($i = 0; $i < count($mat); $i++) {
    $pdf->Row(array(
        $i + 1,
        $mat[$i]["nombres"],
        $mat[$i]["apellidos"],
        $mat[$i]["direccion"],
        $mat[$i]["telefono"],
        $mat[$i]["correo"]
    ));
}

$pdf->Output();
?>
