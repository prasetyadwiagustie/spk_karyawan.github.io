<?php
    include 'db/db_config.php';
    session_start();
    // error_reporting(0);
    if(empty($_SESSION['id'])){
        header('location:login.php');
    }
    ob_start(); 
  
    require_once('tcpdf/tcpdf.php');

    class MYPDF extends TCPDF {

        public function Header() {
            if ($this->page == 1) {
            // Logo
            $image_file = K_PATH_IMAGES.'tcpdf_logo.jpg';
            $this->Image($image_file, 10, 2, 25, 35, 'JPG', '', 'T', false, 200, '', false, false, 0, false, false, false);
            $html = '<strong><br><br><font size="12" align="center" >Laporan Data Admin PT PRATESIS</font></strong></br></br>
            <BR/><br><font size="6" align="center">Ruko Bidex Blok H.21 Jl. Pahlawan Seribu No.8, Lengkong Gudang, Kec. Serpong, Kota Tangerang Selatan, Banten 15321
            </FONT></br>
            ';
            $this->writeHTMLCell(
                $w=0,
                $h=0,
                $x=0,
                $y=3,
                $html,
                $border=0,
                $ln=0,
                $fill=false,
                $reseth=true,
                $align='L'
                );


                $html = '
                <hr>
                <br>
                <table>
                <tr>
                <td align="center" style="font-size: 15px;">Laporan Data Admin</td>
                </tr>
                </table>
                <table>
                <tr>
                <td></td>
                </tr>
                </table>
                ';
                $this->writeHTMLCell(
                    $w=0,
                    $h=0,
                    $x=0,
                    $y=30,
                    $html,
                    $border=0,
                    $ln=0,
                    $fill=false,
                    $reseth=true,
                    $align=''
            );
        }
        }
    
        public function lastPage($resetmargins=false) {
            $this->setPage($this->getNumPages(), $resetmargins);
            $this->isLastPage = true;
        }
        
        // Page footer
        public function Footer() {

           if($this->isLastPage) { 
            $tgl = date("d F Y");
            // $this->SetY(-55);
            $html = '<font size="10">Depok, '.$tgl.' <br/><br/> <br/><br/>
            '.$_SESSION['nama'].'<font>
            <br/>';
            $this->writeHTMLCell(
                $w=0,
                $h=0,
                $x=0,
                $y=-40,
                $html,
                $border=0,
                $ln=0,
                $fill=false,
                $reseth=true,
                $align='R'
            ); 

            }
            // Position at 15 mm from bottom
            $this->SetY(-15);

            // Set font
            $this->SetFont('helvetica', 'I', 8);
            // Page number
            $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// data header
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
 

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

$pdf->SetFont('times','',10);
// add a page
$pdf->AddPage();

$htmlTable =
'
<table border="1" cellpadding="4" >
<thead>
        <tr >
            <th width="35px"><b>No</b></th>
            <th><b>Nama</b></th>
            <th width="110px"><b>Alamat</b></th>
            <th><b>Telepon</b></th>
            <th width="150px"><b>Email</b></th>
        </tr>
    </thead>
    <tbody>';
        $no=1; 
        foreach($db->select('*','admin')->get() as $data):
    $htmlTable .='<tr>
            <td width="35px">'.$no.'</td>
            <td>'.$data['nama'].'</td>
            <td width="110px">'.$data['alamat'].'</td>
            <td>'.$data['telepon'].'</td>
            <td width="150px">'.$data['email'].'</td>
        </tr>';
        $no++; endforeach;
        $htmlTable .= '</tbody>
    </table>';

$pdf->writeHTML($htmlTable, true, false, true, false, '');
// $pdf->writeHTML($htmlTable, true, 0, true, 0);
// ---------------------------------------------------------
// ob_end_clean();
//Close and output PDF document
$pdf->Output('Lap_Admin.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

?>
