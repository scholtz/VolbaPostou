<?php
use AsyncWeb\System\Path;
use AsyncWeb\Frontend\URLParser;
use AsyncWeb\Text\Texts;
use \BT\Base;$load = new \BT\Base; 

class Main extends \AsyncWeb\Frontend\Block{
	
	public function initTemplate(){
		
		// nacitaj obce z csv
		$i = 0;
		$n2k=array();
		$obce = array();
		$db = array();
	
		if (($handle = fopen("../obce.csv", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {$i++;
				if($i==1){
					foreach($data as $k=>$v){
						$n2k[Texts::clear($v)] = $k;	
					}
				}else{
					$db[$data[$n2k["ico"]]] = $data;
					$obce[$data[$n2k["ico"]]] = $data[$n2k["nazov"]];
				}
			}
		}

		
		
		if(URLParser::v("get") && isset($db[URLParser::v("obec")]) && $obec = $db[URLParser::v("obec")]){
			$phpWord = new \PhpOffice\PhpWord\PhpWord();
			/* Note: any element you append to a document must reside inside of a Section. */
			$phpWord->addParagraphStyle('pStyle', array('spacing' => 5));
			// Adding an empty Section to the document...
			$section = $phpWord->addSection();
			// Adding Text element to the Section having font styled by default...
			$textrun = $section->addTextRun('pStyle');
			$textrun->addTextBreak();
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("\t\t\t\t\t\t".$obec[$n2k["urad"]]),array('bold' => true));
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("\t\t\t\t\t\t".$obec[$n2k["ulica"]]." ".$obec[$n2k["cislo"]]));
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("\t\t\t\t\t\t".$obec[$n2k["psc"]]." ".$obec[$n2k["posta"]]));
			$emails = explode(";",$obec[$n2k["email"]]);
			foreach($emails as $email){
				$textrun->addTextBreak();
				$textrun->addText(htmlspecialchars("\t\t\t\t\t\t".$email));
			}
			$textrun->addTextBreak();
			$textrun->addTextBreak();
			$textrun->addTextBreak();
			$textrun->addTextBreak();
			
			$textrun->addText(htmlspecialchars("Vec: Žiadosť o zaradenie do zoznamu voličov mimo obce"),array('bold' => true));
			$textrun->addTextBreak();			
			$textrun->addTextBreak();

			$textrun->addText(htmlspecialchars("Žiadam o možnosť voľby v parlamentných voľbách v roku 2016 formou pošty."));
			$textrun->addTextBreak();
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("Žiadateľ:"));
			$textrun->addTextBreak();
			$textrun->addTextBreak();				
			$textrun->addText(htmlspecialchars("Meno:\t\t\t\t"));
			$textrun->addText(htmlspecialchars("MENO"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("Priezvisko:\t\t\t"));
			$textrun->addText(htmlspecialchars("PRIEZVISKO"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("R.č.:\t\t\t\t"));
			$textrun->addText(htmlspecialchars("RODNÉ ČÍSLO"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("Adresa:\t\t\t\t"));
			$textrun->addText(htmlspecialchars("ADRESA TRVALÉHO BYDLISKA"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("Adresa kde zaslať hárky:\t"));
			$textrun->addText(htmlspecialchars("ADRESA Z KADE CHCETE VOLIŤ"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addTextBreak();

			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("S pozdravom,"),array('bold' => true));
			$textrun->addTextBreak();
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("\t\t\t\t"));
			$textrun->addText(htmlspecialchars("CELÉ MENO"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			
			if(URLParser::v("type") == "1"){
				$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
				$objWriter->save($name = '../docs/'.md5(uniqid()).'.docx');
				$pripona = ".docx";
			}elseif(URLParser::v("type") == "2"){
				$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
				$objWriter->save($name = '../docs/'.md5(uniqid()).'.odt');
				$pripona = ".odt";
			}elseif(URLParser::v("type") == "3"){
				$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'RTF');
				$objWriter->save($name = '../docs/'.md5(uniqid()).'.rtf');
				$pripona = ".rtf";
			}
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=NaPodpis'.$pripona);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($name));
			flush();
			readfile($name);
			unlink($name);
			exit; 			
		}
		
		$ret = '<h1>Vytvorte si žiadosť pre získanie volebného preukazu</h1>';
		
		
		$ret.= '<form class="form-horizontal" role="form" action="'.Path::make(array("get"=>"1")).'" method="post">';
		$ret.='
		
      <div class="form-group">
        <label for="type" class="col-sm-2 control-label">Čo chete urobiť?</label>
        <div class="col-sm-10">
          <select id="type" name="type" class="form-control">
            <option value="1">Vytvoriť upravený vzor .xdoc &gt; Doplniť citlivé údaje &gt; Vytvoriť PDF &gt; Podpísať súbor  &gt; Odoslať samostatne  na úrad </option>
            <option value="2">Vytvoriť upravený vzor .odt &gt; Doplniť citlivé údaje &gt; Vytvoriť PDF &gt; Podpísať súbor  &gt; Odoslať samostatne  na úrad </option>
            <option value="3">Vytvoriť upravený vzor .rtf &gt; Doplniť citlivé údaje &gt; Vytvoriť PDF &gt; Podpísať súbor  &gt; Odoslať samostatne  na úrad </option>
            <option value="4">Vytvoriť dokument .xdoc &gt; Vytvoriť PDF &gt; Podpísať súbor  &gt; Odoslať samostatne  na úrad </option>
            <option value="5">Vytvoriť dokument .odt &gt; Vytvoriť PDF &gt; Podpísať súbor  &gt; Odoslať samostatne  na úrad </option>
            <option value="6">Vytvoriť dokument .pdf &gt; Samostatne podpísať súbor  &gt; Odoslať samostatne na úrad </option>
            <option value="7">Vytvoriť podpísaný dokument .pdf &gt; Odoslať na úrad systémom </option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="obec" class="col-sm-2 control-label">Vaše trvalé bydlisko je vedené v obci:</label>
        <div class="col-sm-10">
          <select id="obec" name="obec" class="form-control">';
		  foreach($obce as $k=>$v){
            $ret.='<option value="'.htmlspecialchars($k).'">'.htmlspecialchars($v).'</option>';
		  }
		  $ret.='
          </select>
        </div>
      </div>
    <div class="form-group has-error has-feedback">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-10">
        <input class="btn btn-primary" type="submit" value="Stiahnuť vzor">
      </div>
    </div>';
			$ret.= '';
		$ret.= '</form>';
		
		$ret.='<p>Zatiaľ je podporovaná iba funkcia vytvorenia vzoru. Zadávanie citlivých údajov a automatická tvorba PDF, prípadne automatické odoslanie na úrad nie je momentálne k dispozícii. </p>';
		$ret.='<p>Vo vygenerovanom súbore v hlavičke je k dispozícii email na ktorý máte žiadosť odoslať. Postupujte nasledovne:</p>
		<ol>
		<li>Vyberte si formát súboru: .docx, .odt, alebo .rtf</li>
		<li>Vyberte si miesto Vášho trvalého bydliska</li>
		<li>Stiahnite si vzor</li>
		<li>Na vyznačené miesta doplňte osobné údaje</li>
		<li>Uložte do formátu PDF, alebo si nainštalujte <a href="http://www.cutepdf.com/Products/CutePDF/writer.asp">CutePDF</a> a vytlačte dokument do PDF.</li>
		<li>Podpíšte súbor certifikátom. Ideálne z občianskeho preukazu</li>
		<li>Odošlite na email ktorý je uvedený vo vygenerovanom súbore. (Ak je z emailu rozpoznateľný starosta, odošlite to na druhý email).</li>
		</ol>
		
		';
		
		
		$this->template = $ret;
		
	}
	
}