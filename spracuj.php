<?php


$ret="obce['mvsr']=['Ministerstvo vnútra Slovenskej republiky','odbor volieb, referenda a politických strán','Drieňová','22','826 86','Bratislava 29','volby@minv.sk'];";
$i = 0;
if (($handle = fopen("../obce.csv", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {$i++;
				if($i==1){
					foreach($data as $k=>$v){
								$n2k[$v] = $k;	
					}
				}else{
					$email = $data[$n2k["email"]];
					$emaily = explode(";",$email);
					
					if(count($emaily) != 1){
						foreach($emaily as $k=>$em){
							if(!$em){unset($emaily[$k]);}
							$em = trim($em);
							if(strpos($em,"podatelna") !== false){
								$emaily = array($em);break;
							}
							if(strpos($em,"sekretariat") !== false){
								$emaily = array($em);break;
							}
							if(strpos($em,"obecnyurad") !== false){
								$emaily = array($em);break;
							}
							if(strpos($em,"ocu") === 0){
								
								$emaily = array($em);break;
							}
							if(strpos($em,"ou") === 0){
								$emaily = array($em);break;
							}
							if(strpos($em,"obec.") === 0){
								$emaily = array($em);break;
							}
							if(strpos($em,"miestnyurad") === 0){
								$emaily = array($em);break;
							}
							
							if($em == "starostka@karlovaves.sk") continue;
							
							if(strpos($em,"starosta") !== false){
								unset($emaily[$k]);
							}
							if(strpos($em,"hovorca") !== false){
								unset($emaily[$k]);
							}
							if(strpos($em,"starostka") !== false){
								unset($emaily[$k]);
							}
							if(strpos($em,"matrika") !== false){
								unset($emaily[$k]);
							}
							if(strpos($em,"primator") !== false){
								unset($emaily[$k]);
							}
							if(strpos($em,"prednosta") !== false){
								unset($emaily[$k]);
							}
							if(strpos($em,"prednostka") !== false){
								unset($emaily[$k]);
							}
							if($em == "peter.nemecek@obecbab.sk"){
								unset($emaily[$k]);
							}
							if($em == "babindol@babindol.sk"){ // alt obec@babindol.sk
								unset($emaily[$k]);
							}
							if($em == "obec@babina.sk" || $em =="spravca@babina.sk" || $em=="ekonom@babina.sk"){ // alt podatelna@babina.sk
								unset($emaily[$k]);
							}
							if($em == "babinec2010@gmail.com"){ // alt ocu@babinec.info"
								unset($emaily[$k]);
							}
							if($em == "oubajtava@mail.t-com.sk"){ // alt oubajtava@stonline.sk
								unset($emaily[$k]);
							}
							if($em == "nadezda.babiakova@banskastiavnica.sk" || $em=="ivana.ondrejmiskova@banskastiavnica.sk"){ // alt msu@banskastiavnica.sk
								unset($emaily[$k]);
							}
							if($em == "obec.bartosovce@wi-net.sk"){ // alt obec.bartosovce@isomi.sk
								unset($emaily[$k]);
							}
							if($em == "oubenice@gaya.sk"){ // alt benice@benice.sk
								unset($emaily[$k]);
							}
							if($em == "betlanovce@stonline.sk"){ // alt obecbetlanovce@stonline.sk
								unset($emaily[$k]);
							}
							if($em == "oub.kostol@apo.sk"){ // alt oub.kostol@stonline.sk
								unset($emaily[$k]);
							}
							if($em == "oub.kostol@apo.sk"){ // alt oub.kostol@stonline.sk
								unset($emaily[$k]);
							}
							if($em == "terezia.foldvaryova@blatnanaostrove.sk"){ // obec@blatnanaostrove.sk
								unset($emaily[$k]);
							}
							if($em == "laco@svslm.sk"){ // alt obec@bobrovcek.sk
								unset($emaily[$k]);
							}
							if($em == "dusan.zeliznak@gmail.com"){ // alt ZeliP@zoznam.sk
								unset($emaily[$k]);
							}
							if($em == "obecboliarov@netkosice.sk"){ // alt obec@boliarov.sk
								unset($emaily[$k]);
							}
							if($em == "daniel.juracek@bosaca.eu"){ // alt "bosaca@bosaca.eu"
								unset($emaily[$k]);
							}
							
						}
					}
					if(count($emaily) != 1){
						var_dump($i);
						var_dump($emaily);
						var_dump($data[$n2k["email"]]);
						exit;
					}
					$ret.="obce['".$data[$n2k["ico"]]."']=['".$data[$n2k["urad"]]."','','".$data[$n2k["ulica"]]."','".$data[$n2k["cislo"]]."','".$data[$n2k["psc"]]."','".$data[$n2k["posta"]]."','".$data[$n2k["email"]]."'];";
				}
			}
		}
		
		
file_put_contents("out.html",$ret);