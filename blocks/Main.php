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
			if(URLParser::v("how") == "preukaz"){
				$phpWord = $this->vytvorZiadostHlasovaciPreukaz($obec,$n2k);
			}elseif(URLParser::v("how") == "preukazsplnomocnene"){
				$phpWord = $this->vytvorZiadostHlasovaciPreukazSplnomocnene($obec,$n2k);
			}elseif(URLParser::v("how") == "postousr"){
				$phpWord = $this->vytvorZiadostHlasovaniePostou($obec,$n2k);
			}
			if($phpWord){
				$this->posliSubor($phpWord);		
			}
			
		}
		
		$ret = '<h1>Vytvorte si žiadosť pre získanie volebného preukazu</h1>';
		
		
		$ret.=$this->vytvorFormular($obce);
		
		$ret.='<p>Touto aplikáciou si môžete vygenerovať upravený vzor, ktorý po doplnení osobných údajov môžete odoslať na úrad vašej obce, a oni Vám odošlú hlasovací preukaz alebo hlasovacie lístky pre hlasovanie poštou.</p>';
		$ret.='<p>Vo vygenerovanom súbore v hlavičke je k dispozícii email na ktorý máte žiadosť odoslať. Postupujte nasledovne:</p>
		<ol>
		<li>Vyberte si formát súboru: .docx, .odt, alebo .rtf</li>
		<li>Vyberte si miesto Vášho trvalého bydliska</li>
		<li>Stiahnite si vzor</li>
		<li>Na vyznačené miesta doplňte osobné údaje</li>
		<li>Uložte do formátu PDF, alebo si nainštalujte <a href="http://www.cutepdf.com/Products/CutePDF/writer.asp">CutePDF</a> a vytlačte dokument do PDF.</li>
		<li>Podpíšte PDF súbor elektronickým podpisom napríklad s Občianskym preukazom cez aplikáciu <a href="https://www.slovensko.sk/sk/na-stiahnutie/">XZep Signer</a></li>
		<li>Odošlite na email ktorý je uvedený vo vygenerovanom súbore. (Ak je z emailu rozpoznateľný starosta, odošlite to na druhý email).</li>
		</ol>
		
		';
		$ret.='<p>Ďalšie zdroje informácií:</p>
		<ul>
		<li><a target="_blank" href="https://platforma.slovensko.digital/t/registracia-na-volby-postou-zo-zahranicia-alebo-volicsky-preukaz/893/34">Diskusia o registrácii na hlasovanie</a></li>
		<li><a target="_blank" href="http://www.minv.sk/?nr16-preukaz">Oficiálne informácie o hlasovaní hlasovacím preukazom</a></li>
		<li><a target="_blank" href="http://www.minv.sk/?nr16-preukaz&subor=230515">Oficiálna žiadosť o vydanie hlasovacieho preukazu (62,2 kB)</a></li>
		<li><a target="_blank" href="http://www.minv.sk/?nr16-preukaz&subor=230517">Oficiálna žiadosť o vydanie hlasovacieho preukazu a splnomocnenie na jeho prevzatie (61,0 kB)</a></li>
		<li><a target="_blank" href="http://www.minv.sk/?nr16-preukaz&subor=230518">Oficiálne splnomocnenie (58,6 kB)</a></li>
		<li><a target="_blank" href="http://www.minv.sk/?nr16-posta2">Oficiálne informácie o hlasovaní hlasovaním poštou pre osoby s trvalým bydliskom na Slovensku</a></li>
		<li><a target="_blank" href="http://www.minv.sk/?nr16-posta2&subor=220769">Oficiálna žiadosť o voľbu poštou pre voľby do Národnej rady Slovenskej republiky v roku 2016 (volič, ktorý má trvalý pobyt na území Slovenskej republiky a v čase volieb sa zdržiava mimo jej územia) (78,0 kB)</a></li>
		<li><a target="_blank" href="http://www.minv.sk/?nr16-posta1">Oficiálne informácie o hlasovaní hlasovaním poštou pre osoby bez trvalého bydliska na Slovensku</a></li>
		<li><a target="_blank" href="http://www.minv.sk/?nr16-posta1&subor=220768">Oficiálna žiadosť o voľbu poštou pre voľby do Národnej rady Slovenskej republiky v roku 2016 (volič, ktorý nemá trvalý pobyt na území Slovenskej republiky) (70,2 kB)</a></li>
		<li><a target="_blank" href="http://www.minv.sk/?nr16-posta1&subor=230519">Čestné vyhlásenie (volič, ktorý nemá trvalý pobyt na území Slovenskej republiky) (36,2 kB)</a></li>		
		</ul>
		
		';
		
		
		$this->template = $ret;
		
	}
	public function vytvorFormular($obce){
		$ret.= '<form class="form-horizontal" role="form" action="'.Path::make(array("get"=>"1")).'" method="post">';
		$ret.='
		
      <div class="form-group">
        <label for="how" class="col-sm-2 control-label">Ako chcete voliť?</label>
        <div class="col-sm-10">
          <select id="how" name="how" class="form-control">
            <option value="preukaz">Chcem voliť osobne na Slovensku hlasovacím preukazom ktorý dostanem poštou do vlastných rúk</option>
            <option value="preukazsplnomocnene">Chcem voliť osobne na Slovensku hlasovacím preukazom ktorý dostane splnomecnená osoba</option>
            <option value="postousr">Chcem voliť poštou zo zahraničia</option>
          </select>
        </div>
      </div>      <div class="form-group">
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
      <div class="form-group">
        <label for="type" class="col-sm-2 control-label">Aký formát vzoru si môžete upraviť?</label>
        <div class="col-sm-10">
          <select id="type" name="type" class="form-control">
            <option value="1">Vzor pre moju obec vo formáte .xdoc</option>
            <option value="2">Vzor pre moju obec vo formáte .odt</option>
            <option value="3">Vzor pre moju obec vo formáte .rtf</option>
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
		return $ret;
	}
	public function vytvorZiadostHlasovaciPreukaz($obec,$n2k){
			$phpWord = new \PhpOffice\PhpWord\PhpWord();

			$phpWord->addParagraphStyle('pStyle', array('spacing' => 5));
			$phpWord->addParagraphStyle('pStyle2', array('spacing' => 5,'align' => 'center'));
			$phpWord->addParagraphStyle('pStyler', array('spacing' => 5,'align' => 'right'));

			$section = $phpWord->addSection();

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
			
			$textrun->addText(htmlspecialchars("Vec: Žiadosť o vydanie hlasovacieho preukazu"),array('bold' => true));
			$textrun->addTextBreak();			
			$textrun->addTextBreak();				
			$textrun->addText(htmlspecialchars("Meno:\t\t"));
			$textrun->addText(htmlspecialchars("MENO"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));

			$textrun->addText(htmlspecialchars("\t\t\t"));
			
			$textrun->addText(htmlspecialchars("Priezvisko:\t\t"));
			$textrun->addText(htmlspecialchars("PRIEZVISKO"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("R.č.:\t\t"));
			$textrun->addText(htmlspecialchars("RODNÉ ČÍSLO"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addText(htmlspecialchars("\t\tŠtátna príslušnosť:\tSlovenská republika"));
			
			$textrun->addTextBreak();

			$textrun->addText(htmlspecialchars("Adresa trvalého pobytu:\t\t"));
			$textrun->addText(htmlspecialchars("ADRESA TRVALÉHO BYDLISKA"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("(názov obce, názov ulice, ak sa obec člení na ulice, súpisné a orientačné číslo)"),array('size' => '6'));
			
			$textrun->addTextBreak();
			$textrun->addTextBreak();			
			
			$textrun = $section->addTextRun('pStyle2');
			$textrun->addText(htmlspecialchars("žiadam"),array('bold' => true,"align"=>"center"));
			
			$textrun = $section->addTextRun('pStyle');
			$textrun->addTextBreak();
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("podľa § 46 zákona č. 180/2014 Z. z. o podmienkach výkonu volebného práva a o zmene a doplnení niektorých zákonov "));
			$textrun->addText(htmlspecialchars("o vydanie hlasovacieho preukazu"),array('bold' => true));
			$textrun->addText(htmlspecialchars(" pre voľby do Národnej rady Slovenskej republiky v roku 2016."));
			
			$textrun->addTextBreak();

			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("Hlasovací preukaz žiadam zaslať na adresu:"),array('bold' => true));
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("Meno:\t\t"));
			$textrun->addText(htmlspecialchars("MENO"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addText(htmlspecialchars("\tPriezvisko:\t\t"));
			$textrun->addText(htmlspecialchars("PRIEZVISKO"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addTextBreak();
			
			$textrun->addText(htmlspecialchars("Adresa:\t\t"));
			$textrun->addText(htmlspecialchars("ADRESA KDE ZASLAŤ HLASOVACÍ PREUKAZ"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("(názov obce, názov ulice, ak sa obec člení na ulice, súpisné a orientačné číslo, poštové smerovacie číslo)"),array('size' => '6'));
			$textrun->addTextBreak();
			$textrun->addTextBreak();
			$textrun->addTextBreak();
			$textrun->addText(htmlspecialchars("V "));
			$textrun->addText(htmlspecialchars("MIESTO KDE SA NACHÁDZATE"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			$textrun->addText(htmlspecialchars(", dňa "));
			$textrun->addText(htmlspecialchars("DNEŠNÝ DÁTUM"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			
			$textrun->addTextBreak();
			$textrun = $section->addTextRun('pStyler');
			$textrun->addText(htmlspecialchars("MENO ALEBO PODPIS"),array('bgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
			
			return $phpWord;
	}
	public function vytvorZiadostHlasovaciPreukazSplnomocnene($obec,$n2k){
		
	}
	public function vytvorZiadostHlasovaniePostou($obec,$n2k){
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
			return $phpWord;

	}
	public function posliSubor($phpWord){

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
}