<?php
if(!class_exists("FDPF")){
	require_once("fpdf/fpdf.php");
}
define('FPDF_FONTPATH',SENELIB.'fpdf/font/');


class Seme_FPDF extends FPDF {
	var $UHC_widths;
	public function __construct(){
		//parent::__construct($orientation='P', $unit='mm', $size='A4');
		parent::__construct();
		$this->UHC_widths = array(' '=>333,'!'=>416,'"'=>416,'#'=>833,'$'=>625,'%'=>916,'&'=>833,'\''=>250,
		'('=>500,')'=>500,'*'=>500,'+'=>833,','=>291,'-'=>833,'.'=>291,'/'=>375,'0'=>625,'1'=>625,
		'2'=>625,'3'=>625,'4'=>625,'5'=>625,'6'=>625,'7'=>625,'8'=>625,'9'=>625,':'=>333,';'=>333,
		'<'=>833,'='=>833,'>'=>916,'?'=>500,'@'=>1000,'A'=>791,'B'=>708,'C'=>708,'D'=>750,'E'=>708,
		'F'=>666,'G'=>750,'H'=>791,'I'=>375,'J'=>500,'K'=>791,'L'=>666,'M'=>916,'N'=>791,'O'=>750,
		'P'=>666,'Q'=>750,'R'=>708,'S'=>666,'T'=>791,'U'=>791,'V'=>750,'W'=>1000,'X'=>708,'Y'=>708,
		'Z'=>666,'['=>500,'\\'=>375,']'=>500,'^'=>500,'_'=>500,'`'=>333,'a'=>541,'b'=>583,'c'=>541,
		'd'=>583,'e'=>583,'f'=>375,'g'=>583,'h'=>583,'i'=>291,'j'=>333,'k'=>583,'l'=>291,'m'=>875,
		'n'=>583,'o'=>583,'p'=>583,'q'=>583,'r'=>458,'s'=>541,'t'=>375,'u'=>583,'v'=>583,'w'=>833,
		'x'=>625,'y'=>625,'z'=>500,'{'=>583,'|'=>583,'}'=>583,'~'=>750);
	}

	//begin korean text
	function AddCIDFont($family, $style, $name, $cw, $CMap, $registry){
		$fontkey=strtolower($family).strtoupper($style);
		if(isset($this->fonts[$fontkey]))
		$this->Error("Font already added: $family $style");
		$i=count($this->fonts)+1;
		$name=str_replace(' ','',$name);
		$this->fonts[$fontkey]=array('i'=>$i,'type'=>'Type0','name'=>$name,'up'=>-130,'ut'=>40,'cw'=>$cw,'CMap'=>$CMap,'registry'=>$registry);
	}

	function AddCIDFonts($family, $name, $cw, $CMap, $registry){
		$this->AddCIDFont($family,'',$name,$cw,$CMap,$registry);
		$this->AddCIDFont($family,'B',$name.',Bold',$cw,$CMap,$registry);
		$this->AddCIDFont($family,'I',$name.',Italic',$cw,$CMap,$registry);
		$this->AddCIDFont($family,'BI',$name.',BoldItalic',$cw,$CMap,$registry);
	}

	function AddUHCFont($family='UHC', $name='HYSMyeongJoStd-Medium-Acro') {
		// Add UHC font with proportional Latin
		$cw=$this->UHC_widths;
		$CMap='KSCms-UHC-H';
		$registry=array('ordering'=>'Korea1','supplement'=>1);
		$this->AddCIDFonts($family,$name,$cw,$CMap,$registry);
	}

	function AddUHChwFont($family='UHC-hw', $name='HYSMyeongJoStd-Medium-Acro'){
		// Add UHC font with half-witdh Latin
		for($i=32;$i<=126;$i++)
		$cw[chr($i)]=500;
		$CMap='KSCms-UHC-HW-H';
		$registry=array('ordering'=>'Korea1','supplement'=>1);
		$this->AddCIDFonts($family,$name,$cw,$CMap,$registry);
	}

	function GetStringWidth($s){
		if($this->CurrentFont['type']=='Type0')
		return $this->GetMBStringWidth($s);
		else
		return parent::GetStringWidth($s);
	}

	function GetMBStringWidth($s){
		// Multi-byte version of GetStringWidth()
		$l=0;
		$cw=&$this->CurrentFont['cw'];
		$nb=strlen($s);
		$i=0;
		while($i<$nb){
			$c=$s[$i];
			if(ord($c)<128){
				$l+=$cw[$c];
				$i++;
			} else {
				$l+=1000;
				$i+=2;
			}
		}
		return $l*$this->FontSize/1000;
	}

	function MultiCell($w, $h, $txt, $border=0, $align='L', $fill=false) {
		if($this->CurrentFont['type']=='Type0')
		$this->MBMultiCell($w,$h,$txt,$border,$align,$fill);
		else
		parent::MultiCell($w,$h,$txt,$border,$align,$fill);
	}

	function MBMultiCell($w, $h, $txt, $border=0, $align='L', $fill=false) {
		// Multi-byte version of MultiCell()
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 && $s[$nb-1]=="\n")
		$nb--;
		$b=0;
		if($border) {
			if($border==1) {
				$border='LTRB';
				$b='LRT';
				$b2='LR';
			} else {
				$b2='';
				if(is_int(strpos($border,'L')))
				$b2.='L';
				if(is_int(strpos($border,'R')))
				$b2.='R';
				$b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
			}
		}
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb) {
			// Get next character
			$c=$s[$i];
			// Check if ASCII or MB
			$ascii=(ord($c)<128);
			if($c=="\n") {
				// Explicit line break
				$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				if($border && $nl==2)
				$b=$b2;
				continue;
			}
			if(!$ascii) {
				$sep=$i;
				$ls=$l;
			} elseif($c==' ') {
				$sep=$i;
				$ls=$l;
			}
			$l+=$ascii ? $cw[$c] : 1000;
			if($l>$wmax) {
				// Automatic line break
				if($sep==-1 || $i==$j) {
					if($i==$j)
					$i+=$ascii ? 1 : 2;
					$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
				} else {
					$this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
					$i=($s[$sep]==' ') ? $sep+1 : $sep;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				if($border && $nl==2) $b=$b2;
			} else $i+=$ascii ? 1 : 2;
		}
		// Last chunk
		if($border && is_int(strpos($border,'B')))
		$b.='B';
		$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
		$this->x=$this->lMargin;
	}

	function Write($h, $txt, $link='') {
		if($this->CurrentFont['type']=='Type0')
		$this->MBWrite($h,$txt,$link);
		else
		parent::Write($h,$txt,$link);
	}

	function MBWrite($h, $txt, $link) {
		// Multi-byte version of Write()
		$cw=&$this->CurrentFont['cw'];
		$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb) {
			// Get next character
			$c=$s[$i];
			// Check if ASCII or MB
			$ascii=(ord($c)<128);
			if($c=="\n") {
				// Explicit line break
				$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				if($nl==1) {
					$this->x=$this->lMargin;
					$w=$this->w-$this->rMargin-$this->x;
					$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
				}
				$nl++;
				continue;
			}
			if(!$ascii || $c==' ')
			$sep=$i;
			$l+=$ascii ? $cw[$c] : 1000;
			if($l>$wmax) {
				// Automatic line break
				if($sep==-1 || $i==$j) {
					if($this->x>$this->lMargin) {
						// Move to next line
						$this->x=$this->lMargin;
						$this->y+=$h;
						$w=$this->w-$this->rMargin-$this->x;
						$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
						$i++;
						$nl++;
						continue;
					}
					if($i==$j)
					$i+=$ascii ? 1 : 2;
					$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
				} else {
					$this->Cell($w,$h,substr($s,$j,$sep-$j),0,2,'',0,$link);
					$i=($s[$sep]==' ') ? $sep+1 : $sep;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				if($nl==1) {
					$this->x=$this->lMargin;
					$w=$this->w-$this->rMargin-$this->x;
					$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
				}
				$nl++;
			} else
			$i+=$ascii ? 1 : 2;
		}
		// Last chunk
		if($i!=$j)
		$this->Cell($l/1000*$this->FontSize,$h,substr($s,$j,$i-$j),0,0,'',0,$link);
	}

	function _putType0($font) {
		// Type0
		$this->_newobj();
		$this->_out('<</Type /Font');
		$this->_out('/Subtype /Type0');
		$this->_out('/BaseFont /'.$font['name'].'-'.$font['CMap']);
		$this->_out('/Encoding /'.$font['CMap']);
		$this->_out('/DescendantFonts ['.($this->n+1).' 0 R]');
		$this->_out('>>');
		$this->_out('endobj');
		// CIDFont
		$this->_newobj();
		$this->_out('<</Type /Font');
		$this->_out('/Subtype /CIDFontType0');
		$this->_out('/BaseFont /'.$font['name']);
		$this->_out('/CIDSystemInfo <</Registry (Adobe) /Ordering ('.$font['registry']['ordering'].') /Supplement '.$font['registry']['supplement'].'>>');
		$this->_out('/FontDescriptor '.($this->n+1).' 0 R');
		if($font['CMap']=='KSCms-UHC-HW-H')
		$W='8094 8190 500';
		else
		$W='1 ['.implode(' ',$font['cw']).']';
		$this->_out('/W ['.$W.']>>');
		$this->_out('endobj');
		// Font descriptor
		$this->_newobj();
		$this->_out('<</Type /FontDescriptor');
		$this->_out('/FontName /'.$font['name']);
		$this->_out('/Flags 6');
		$this->_out('/FontBBox [0 -200 1000 900]');
		$this->_out('/ItalicAngle 0');
		$this->_out('/Ascent 800');
		$this->_out('/Descent -200');
		$this->_out('/CapHeight 800');
		$this->_out('/StemV 50');
		$this->_out('>>');
		$this->_out('endobj');
	}
	//end korean text

	public function Code39($xpos, $ypos, $code, $baseline=0.5, $height=5){

		$wide = $baseline;
		$narrow = $baseline / 3 ;
		$gap = $narrow;

		$barChar['0'] = 'nnnwwnwnn';
		$barChar['1'] = 'wnnwnnnnw';
		$barChar['2'] = 'nnwwnnnnw';
		$barChar['3'] = 'wnwwnnnnn';
		$barChar['4'] = 'nnnwwnnnw';
		$barChar['5'] = 'wnnwwnnnn';
		$barChar['6'] = 'nnwwwnnnn';
		$barChar['7'] = 'nnnwnnwnw';
		$barChar['8'] = 'wnnwnnwnn';
		$barChar['9'] = 'nnwwnnwnn';
		$barChar['A'] = 'wnnnnwnnw';
		$barChar['B'] = 'nnwnnwnnw';
		$barChar['C'] = 'wnwnnwnnn';
		$barChar['D'] = 'nnnnwwnnw';
		$barChar['E'] = 'wnnnwwnnn';
		$barChar['F'] = 'nnwnwwnnn';
		$barChar['G'] = 'nnnnnwwnw';
		$barChar['H'] = 'wnnnnwwnn';
		$barChar['I'] = 'nnwnnwwnn';
		$barChar['J'] = 'nnnnwwwnn';
		$barChar['K'] = 'wnnnnnnww';
		$barChar['L'] = 'nnwnnnnww';
		$barChar['M'] = 'wnwnnnnwn';
		$barChar['N'] = 'nnnnwnnww';
		$barChar['O'] = 'wnnnwnnwn';
		$barChar['P'] = 'nnwnwnnwn';
		$barChar['Q'] = 'nnnnnnwww';
		$barChar['R'] = 'wnnnnnwwn';
		$barChar['S'] = 'nnwnnnwwn';
		$barChar['T'] = 'nnnnwnwwn';
		$barChar['U'] = 'wwnnnnnnw';
		$barChar['V'] = 'nwwnnnnnw';
		$barChar['W'] = 'wwwnnnnnn';
		$barChar['X'] = 'nwnnwnnnw';
		$barChar['Y'] = 'wwnnwnnnn';
		$barChar['Z'] = 'nwwnwnnnn';
		$barChar['-'] = 'nwnnnnwnw';
		$barChar['.'] = 'wwnnnnwnn';
		$barChar[' '] = 'nwwnnnwnn';
		$barChar['*'] = 'nwnnwnwnn';
		$barChar['$'] = 'nwnwnwnnn';
		$barChar['/'] = 'nwnwnnnwn';
		$barChar['+'] = 'nwnnnwnwn';
		$barChar['%'] = 'nnnwnwnwn';

		$this->SetFont('Arial','',10);
		//$this->Text($xpos, $ypos + $height + 4, $code);
		//$this->Cell($xpos, $ypos + $height + 4,$code,0,"C");
		$this->SetFillColor(0);

		$code = '*'.strtoupper($code).'*';
		for($i=0; $i<strlen($code); $i++){
			$char = $code[$i];
			if(!isset($barChar[$char])){
				$this->Error('Invalid character in barcode: '.$char);
			}
			$seq = $barChar[$char];
			for($bar=0; $bar<9; $bar++){
				if($seq[$bar] == 'n'){
					$lineWidth = $narrow;
				}else{
					$lineWidth = $wide;
				}
				if($bar % 2 == 0){
					$this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
				}
				$xpos += $lineWidth;
			}
			$xpos += $gap;
		}
	}
}
