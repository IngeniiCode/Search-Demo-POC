<?php

if(!$x_auth = @$_COOKIE['x-auth']){
	printf('<h3>Fatal Error -- Unable to generate PDF</h3>');
	exit;
}

require_once($_SERVER['NLIBS'].'/Search/form.class.php');
require_once($_SERVER['NLIBS'].'/tcpdf/examples/tcpdf_include.php');
$SQL = new SearchSQL(array('x_a'=>$x_auth));
$SQL->log_download(@$_COOKIE['last_search']);

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('Outspoken.Ninja');
$pdf->SetAuthor('David DeMartini @ Outspoken Ninja');
$pdf->SetTitle('Outspoken Ninja Search Report');
$pdf->SetSubject('Outspoken Ninja Search Report');
$pdf->SetKeywords('outspoken.ninja, search report');

// set default header data
$pdf->SetHeaderData('report.logo.png', 30, 'Outspoken Ninja -- Search Report (beta)', '');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Incluce Ninja Data //
$REPORT      = json_decode(@$_POST['MMHD'],true);
$OUTFILE     = (trim(@$_POST['downloadFileName']))?trim($_POST['downloadFileName']).'.pdf':'OutspokenNinjaVehicleReport.pdf';
$ReportTitle = sprintf('Outspoken Ninja Report - %s',(@$REPORT['info']['name'])?:'Web Search');

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

/*
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
*/
// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 16);

// add a page
$pdf->AddPage();

$pdf->Write(0,$ReportTitle, '', 0, 'L', true, 0, false, false, 0);
$pdf->SetFont('helvetica', '', 8);

// CONSTRUCT THE HTML
$TEMP = array();
if(isset($REPORT['ads'])){
        foreach($REPORT['ads'] as $id => $ad){
                $region = @$ad['regionkey'];
                $state = $REPORT['regions'][$region]['state'];
                $name  = $REPORT['regions'][$region]['name'];
                $TEMP[$state][$name][$id] = $ad;
        }
}

// Create List
foreach($TEMP as $state => $regions){
	$HTML = '<TABLE cellspacing="0" cellpadding="2" border="1" BORDERCOLOR="#BBB">';
        $HTML .= '<tr><td style="background-color:#44a; color:#ddd; font-size: 200%; text-align: left;">'.$state.'</td></tr>'.PHP_EOL;
        foreach($regions as $region => $items){
                $HTML .= '<tr><td colspan="2" style="background-color:#ddd; font-size: 150%; text-align: left; padding-left: 10px;">'.$region.'</td></tr><tr><td><ul style="font-size: 100%;">'.PHP_EOL;
                foreach($items as $item){
                        $HTML .= '<li><span style="font-size:85%;">[ <em>'.$item['type'].'</em> ]</span>&nbsp;&nbsp;<a href="'.$item['url'].'" style="text-decoration: none; color:#444; font-weight: bold;">'.$item['title'].'</a></li>'.PHP_EOL;
                }
		$HTML .= '</ul></td></tr>';
        }
	$HTML .= '</TABLE>';
	$pdf->writeHTML($HTML, true, false, false, false, '');
}

//echo $HTML;


// reset pointer to the last page
$pdf->lastPage();

//Close and output PDF document
$pdf->Output($OUTFILE, 'I');

//============================================================+
// END OF FILE
//============================================================+

?>
