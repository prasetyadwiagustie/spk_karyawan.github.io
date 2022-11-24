<?php
    include 'db/db_config.php';
    session_start();
    // error_reporting(0);
    if(empty($_SESSION['id'])){
        header('location:login.php');
    }
    ob_start(); 

    require_once('tcpdf/tcpdf.php');

    function hitung_lama_bergabung($tgl_lahir)
    {
        $today = date('Y-m-d');
        $now = time();
        list($thn, $bln, $tgl) = explode('-',$tgl_lahir);
        $time_lahir = mktime(0,0,0,$bln, $tgl, $thn);

        $selisih = $now - $time_lahir;

        $tahun = floor($selisih/(60*60*24*365));
        $bulan = round(($selisih % (60*60*24*365) ) / (60*60*24*30));

        return $tahun.' tahun '.$bulan.' bulan';

    }

    class MYPDF extends TCPDF {
        var $top_margin = 20;

        //Page header
        public function Header() {
            
            if ($this->page == 1) {
                // Logo
                $image_file = K_PATH_IMAGES.'tcpdf_logo.jpg';
            $this->Image($image_file, 10, 2, 35, 45, 'JPG', '', 'T', false, 200, '', false, false, 0, false, false, false);
            $html = '<strong><br><br><font size="16" align="center" >Laporan Data Karyawan PT PRATESIS</font></strong></br></br>
            <BR/><br><font size="10" align="center">Ruko Bidex Blok H.21 Jl. Pahlawan Seribu No.8, Lengkong Gudang, Kec. Serpong, Kota Tangerang Selatan, Banten 15321
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
                <td align="center" style="font-size: 15px;">Laporan Data Karyawan</td>
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
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

$pdf->SetFont('times','',10);
// add a page
$pdf->AddPage('L');

$htmlTable =
'
<table border="1" cellpadding="4" >
<thead>
        <tr>
            <th><b>NIK</b></th>
            <th><b>Nama</b></th>
            <th><b>Jenis Kelamin</b></th>
            <th><b>Alamat</b></th>
            <th><b>Telepon</b></th>
            <!--<th>foto</th>-->
            <th><b>Tempat lahir</b></th>
            <th><b>Tanggal lahir</b></th>
            <th><b>Pendidikan</b></th>
            <th><b>Jabatan</b></th>
            <th><b>Tgl Bergabung</b></th>
            <th><b>Lama Bergabung</b></th>
        </tr>
    </thead>
    <tbody>';
        $no=1; 
        foreach($db->select('*','karyawan')->where('status=0')->get() as $data):
    $htmlTable .='<tr>
            <td nowrap>'.$data['NIK'].'</td>
            <td nowrap>'.$data['nama'].'</td>
            <td nowrap>'.$data['jeniskelamin'].'</td>
            <td nowrap>'.$data['alamat'].'</td>
            <td nowrap>'.$data['telepon'].'</td>';
            $htmlTable .='<!--<td>';
            if($data["foto"] != ""):
            $htmlTable .='<img src="assets/foto_calon_karyawan/'.$data["foto"].'" class="img-thumbnail" width="75px" height="70px">';
            else:
            $htmlTable .='<img src="assets/foto_calon_karyawan/image_not_available.jpg" class="img-thumbnail" width="75px" height="70px">';
            endif;
            $htmlTable .='</td>-->';


            $htmlTable .='<td>'.$data['TempatLahir'].'</td>
            <td>'.$data['ttl'].'</td>
            <td>'.$data['PendidikanTerakhir'].'</td>
            <td>'.$data['Jabatan'].'</td>
            <td>'.$data['TglBergabung'].'</td>
            <td>'.hitung_lama_bergabung($data['TglBergabung']).'</td>
        </tr>';
        $no++; endforeach;
        $htmlTable .= '</tbody>
    </table>';

$pdf->writeHTML($htmlTable, true, false, true, false, '');
// $pdf->writeHTML($htmlTable, true, 0, true, 0);
// ---------------------------------------------------------
ob_end_clean();
//Close and output PDF document
$pdf->Output('Lap_Karyawan.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

?>
