<?php
	class GeoCode {
		
		/*@__PROPS__instantiated@*/
		protected $link;

		//construct

		public function __construct(DbConnect $link) {
			
		}

		public function checkData($data) {
			$checked = array();
			while ($row = mysql_fetch_assoc($data)) {
				if ($row['field61'] !="" && $row['field22'] !="" && $row['field19'] !="" && $row['field23'] !="" && $row['field61'] = $this->guessCountry($row['field61'])) 
      				{  
						$checked[] = $row;
					}
			}
			return $checked;
		}

		public function geocode($postalCode, $countryCode=false, $street, $city) {
			$key = '&key=AIzaSyB_NJ2bKWoKzNSX3swKMbGQy5byod_bojg';
			$serviceUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=';
			//$street = urlencode($street);
			$city = urlencode($city);
			$postalCode = urlencode($postalCode);
			if ($countryCode) {
				$countryCode = urlencode($countryCode);
				$add = /*$street.*/"+".$city."+".$postalCode."+".$countryCode;
			} else {
				$add = /*$street.*/"+".$city."+".$postalCode."+";
			}
			$url = $serviceUrl.$add.$key;
			//echo $url;
			//echo "<br />";

			$ch = curl_init();
		 	curl_setopt($ch, CURLOPT_URL, $url);
		 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, false); //Change this to a 1 to return headers
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
			$data = curl_exec($ch);
			echo curl_getinfo($ch) . '<br/>';
			echo curl_errno($ch) . '<br/>';
			echo curl_error($ch) . '<br/>';
			curl_close($ch);
			
			$data = json_decode($data, true);
			$coordinates = $data['results'][0]['geometry']['location'];
			//var_dump($coordinates);
			//echo "<br />";
			Sleep(1);
			//print_r($coordinates);
			return $coordinates;
		}

		public function updateDb($coords, $userid) {
			$query1 = sprintf("UPDATE userfield SET lat = '%s', lng = '%s' WHERE userid = '%s'",
                  mysql_real_escape_string($coords['lat']),
                  mysql_real_escape_string($coords['lng']),
                  mysql_real_escape_string($userid)
               );
			echo $query1;
			echo "<br />";
			$results = mysql_query($query1);
            if (!$results) {
               die("Invalidish Query : ".mysql_error());
            }
		}

		public function validateZip($country, $zip) {
		if ($zip == "00000" || $zip == "00000-000") {
			return false;
		}
			switch ($country) {
				case "AD":
					$pattern = '/AD\\d{3}/';
					preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
					break;
				case "AM":
					$pattern = '/(37)?\\d{4}/';
					preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "AR":
				$pattern = '/([A-HJ-NP-Z])?\\d{4}([A-Z]{3})?/';	
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "AS":
				$pattern = '/96799/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "AT":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "AU":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "AX":
				$pattern = '/22\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "AZ":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "BA":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "BB":
				$pattern = '/(BB\\d{5})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "BD":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "BE":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "BG":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "BH":
				$pattern = '/((1[0-2]|[2-9])\\d{2})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "BM":
				$pattern = '/[A-Z]{2}[ ]?[A-Z0-9]{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "BN":
				$pattern = '/[A-Z]{2}[ ]?\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "BR":
				$pattern = '/\\d{5}[\\-]?\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "BY":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CA":
				$pattern = '/[ABCEGHJKLMNPRSTVXY]\\d[ABCEGHJ-NPRSTV-Z][ ]?\\d[ABCEGHJ-NPRSTV-Z]\\d/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CC":
				$pattern = '/6799/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CH":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CK":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CL":
				$pattern = '/\\d{7}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CN":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CR":
				$pattern = '/\\d{4,5}|\\d{3}-\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CS":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CV":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CX":
				$pattern = '/6798/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CY":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "CZ":
				$pattern = '/\\d{3}[ ]?\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "DE":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "DK":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "DO":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "DZ":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "EC":
				$pattern = '/([A-Z]\\d{4}[A-Z]|(?:[A-Z]{2})?\\d{6})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "EE":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "EG":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "ES":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "ET":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "FI":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "FK":
				$pattern = '/FIQQ 1ZZ/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "FM":
				$pattern = '/(9694[1-4])([ \\-]\\d{4})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "FO":
				$pattern = '/\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "FR":
				$pattern = '/\\d{2}[ ]?\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GB":
				$pattern = '/GIR[ ]?0AA|((AB|AL|B|BA|BB|BD|BH|BL|BN|BR|BS|BT|CA|CB|CF|CH|CM|CO|CR|CT|CV|CW|DA|DD|DE|DG|DH|DL|DN|DT|DY|E|EC|EH|EN|EX|FK|FY|G|GL|GY|GU|HA|HD|HG|HP|HR|HS|HU|HX|IG|IM|IP|IV|JE|KA|KT|KW|KY|L|LA|LD|LE|LL|LN|LS|LU|M|ME|MK|ML|N|NE|NG|NN|NP|NR|NW|OL|OX|PA|PE|PH|PL|PO|PR|RG|RH|RM|S|SA|SE|SG|SK|SL|SM|SN|SO|SP|SR|SS|ST|SW|SY|TA|TD|TF|TN|TQ|TR|TS|TW|UB|W|WA|WC|WD|WF|WN|WR|WS|WV|YO|ZE)(\\d[\\dA-Z]?[ ]?\\d[ABD-HJLN-UW-Z]{2}))|BFPO[ ]?\\d{1,4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GE":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GF":
				$pattern = '/9[78]3\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GG":
				$pattern = '/GY\\d[\\dA-Z]?[ ]?\\d[ABD-HJLN-UW-Z]{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GL":
				$pattern = '/39\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GN":
				$pattern = '/\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GP":
				$pattern = '/9[78][01]\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GR":
				$pattern = '/\\d{3}[ ]?\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GS":
				$pattern = '/SIQQ 1ZZ/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GT":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GU":
				$pattern = '/969[123]\\d([ \\-]\\d{4})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "GW":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "HM":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "HN":
				$pattern = '/(?:\\d{5})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "HR":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "HT":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "HU":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "ID":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "IL":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "IM":
				$pattern = '/IM\\d[\\dA-Z]?[ ]?\\d[ABD-HJLN-UW-Z]{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "IN":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "IO":
				$pattern = '/BBND 1ZZ/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "IQ":
				$pattern = '\\d{5}';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "IS":
				$pattern = '/\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "IT":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "JE":
				$pattern = '/JE\\d[\\dA-Z]?[ ]?\\d[ABD-HJLN-UW-Z]{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "JO":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "JP":
				$pattern = '/\\d{3}-\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "KE":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "KG":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "KH":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "KR":
				$pattern = '/\\d{3}[\\-]\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "KW":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "KZ":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "LA":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "LB":
				$pattern = '/(\\d{4}([ ]?\\d{4})?)?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "LI":
				$pattern = '/(948[5-9])|(949[0-7])/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "LK":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "LR":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "LS":
				$pattern = '/\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "LT":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "LU":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "LV":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MA":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MC":
				$pattern = '/980\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MD":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "ME":
				$pattern = '/8\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MG":
				$pattern = '/\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MH":
				$pattern = '/969[67]\\d([ \\-]\\d{4})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MK":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MN":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MP":
				$pattern = '/9695[012]([ \\-]\\d{4})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MQ":
				$pattern = '/9[78]2\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MT":
				$pattern = '/[A-Z]{3}[ ]?\\d{2,4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MU":
				$pattern = '/(\\d{3}[A-Z]{2}\\d{3})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MV":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MX":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "MY":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "NC":
				$pattern = '/988\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "NE":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "NF":
				$pattern = '/2899/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "NG":
				$pattern = '/(\\d{6})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "NI":
				$pattern = '/((\\d{4}-)?\\d{3}-\\d{3}(-\\d{1})?)?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "NL":
				$pattern = '/\\d{4}[ ]?[A-Z]{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "NO":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "NP":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "NZ":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "OM":
				$pattern = '/(PC )?\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "PF":
				$pattern = '/987\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "PG":
				$pattern = '/\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "PH":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "PK":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "PL":
				$pattern = '/\\d{2}-\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "PM":
				$pattern = '/9[78]5\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "PN":
				$pattern = 'PCRN 1ZZ';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "PR":
				$pattern = '/00[679]\\d{2}([ \\-]\\d{4})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "PT":
				$pattern = '/\\d{4}([\\-]\\d{3})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "PW":
				$pattern = '/96940/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "PY":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "RE":
				$pattern = '/9[78]4\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "RO":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "RS":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "RU":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "SA":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "SE":
				$pattern = '/\\d{3}[ ]?\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "SG":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "SH":
				$pattern = '/(ASCN|STHL) 1ZZ/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "SI":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "SJ":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "SK":
				$pattern = '/\\d{3}[ ]?\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "SM":
				$pattern = '/4789\\d/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "SN":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "SO":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "SZ":
				$pattern = '/[HLMS]\\d{3}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "TC":
				$pattern = '/TKCA 1ZZ/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "TH":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "TJ":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "TM":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "TN":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "TR":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "TW":
				$pattern = '/\\d{3}(\\d{2})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "UA":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "US":
				$pattern = '/\\d{5}([ \\-]\\d{4})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "UY":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "UZ":
				$pattern = '/\\d{6}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "VA":
				$pattern = '/00120/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "VE":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "VI":
				$pattern = '/008(([0-4]\\d)|(5[01]))([ \\-]\\d{4})?/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "WF":
				$pattern = '/986\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "XK":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "YT":
				$pattern = '/976\\d{2}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "YU":
				$pattern = '/\\d{5}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "ZA":
				$pattern = '/\\d{4}/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				case "ZM":
				$pattern = '/\\d{5/';
				preg_match($pattern, $zip, $match);
					if (!$match) {
						return false;
					} else if ($match) {
						return $match;
					}
				default: 
					
					return false; 

			}
		}

		public function guessCountry($country) {
			switch ($country) {
				case "United States":
					$country = 'US';
					return $country;
					break;
				case "India":
					$country = 'IN';
					return $country;
					break;
				case "United Kingdom":
					$country = 'UK';
					return $country;
					break;
				case "Puerto Rico":
					$country = 'PR';
					return $country;
					break;
				case "Canada":
					$country = 'CA';
					return $country;
					break;
				case "France":
					$country = 'FR';
					return $country;
					break;
				case "China":
					$country = 'CN';
					return $country;
					break;
				case "Brazil":
					$country = 'BR';
					return $country;
					break;
				case "South Africa":
					$country = 'ZA';
					return $country;
					break;
				case "Belgium":
					$country = 'BE';
					return $country;
					break;
				case "Germany":
					$country = 'DE';
					return $country;
					break;
				case "Greece":
					$country = 'GR';
					return $country;
					break;
				case "Ukraine":
					$country = 'UA';
					return $country;
					break;
				case "Denmark":
					$country = 'DK';
					return $country;
					break;
				case "Kazakhstan":
					$country = 'KZ';
					return $country;
					break;
				case "Switzerland":
					$country = 'CH';
					return $country;
					break;
				case "Austria":
					$country = 'AT';
					return $country;
					break;
				case "Australia":
					$country = 'AU';
					return $country;
					break;
				case "Spain":
					$country = 'ES';
					return $country;
					break;
				case "Luxembourg":
					$country = 'LU';
					return $country;
					break;
				case "Afghanistan":
					$country = 'AF';
					return $country;
					break;
				case "Singapore":
					$country = 'SG';
					return $country;
					break;
				case "Hong Kong":
					$country = 'HK';
					return $country;
					break;
				case "Czech Republic":
					$country = 'CZ';
					return $country;
					break;
				case "Taiwan, Province Of China ":
					$country = 'TW';
					return $country;
					break;
				case "Sweden":
					$country = 'SE';
					return $country;
					break;
				case "Bosnia and Herzegovina":
					$country = 'BA';
					return $country;
					break;
				case "Russian Federation":
					$country = 'RU';
					return $country;
					break;
				case "Hungary":
					$country = 'HU';
					return $country;
					break;
				case "Montenegro":
					$country = 'ME';
					return $country;
					break;
				case "Netherlands":
					$country = 'NL';
					return $country;
					break;
				case "Turkey":
					$country = 'TR';
					return $country;
					break;
				case "Bahrain":
					$country = 'BH';
					return $country;
					break;
				case "Saudi Arabia":
					$country = 'SA';
					return $country;
					break;
				case "Saudi Arabia":
					$country = 'SA';
					return $country;
					break;
				case "New Zealand":
					$country = 'NZ';
					return $country;
					break;
				case "Lebanon":
					$country = 'LB';
					return $country;
					break;
				case "Poland":
					$country = 'PL';
					return $country;
					break;
				case "Bulgaria":
					$country = 'BG';
					return $country;
					break;
				case "Estonia":
					$country = 'EE';
					return $country;
					break;
				case "Finland":
					$country = 'FI';
					return $country;
					break;
				case "Chile":
					$country = 'CL';
					return $country;
					break;
				case "Philippines":
					$country = 'PH';
					return $country;
					break;
				case "Kuwait":
					$country = 'KW';
					return $country;
					break;
				case "Romania":
					$country = 'RO';
					return $country;
					break;
				case "Japan":
					$country = 'JP';
					return $country;
					break;
				case "Andorra":
					$country = 'AD';
					return $country;
					break;
				case "Peru":
					$country = 'PE';
					return $country;
					break;
				case "Croatia":
					$country = 'HR';
					return $country;
					break;
				case "Norway":
					$country = 'NO';
					return $country;
					break;
				case "Korea, Republic of":
					$country = 'KR';
					return $country;
					break;
				case "Uganda":
					$country = 'UG';
					return $country;
					break;
				case "Latvia":
					$country = 'LV';
					return $country;
					break;
				case "Thailand":
					$country = 'TH';
					return $country;
					break;
				case "United Arab Emirates":
					$country = 'AE';
					return $country;
					break;
				case "Ireland":
					$country = 'IE';
					return $country;
					break;
				case "Gibraltar":
					$country = 'GI';
					return $country;
					break;
				case "Ecuador":
					$country = 'EC';
					return $country;
					break;
				case "Albania":
					$country = 'AL';
					return $country;
					break;
				case "Mexico":
					$country = 'MX';
					return $country;
					break;
				case "Slovenia":
					$country = 'SI';
					return $country;
					break;
				case "Monaco":
					$country = 'MC';
					return $country;
					break;
				case "Belarus":
					$country = 'BY';
					return $country;
					break;
				case "Slovakia":
					$country = 'SK';
					return $country;
					break;
				case "Argentina":
					$country = 'AR';
					return $country;
					break;
				case "Azerbaijan":
					$country = 'AZ';
					return $country;
					break;
				case "Egypt":
					$country = 'EG';
					return $country;
					break;
				case "Guernsey":
					$country = 'GG';
					return $country;
					break;
				case "Brunei Darussalam":
					$country = 'BN';
					return $country;
					break;
				case "Malaysia":
					$country = 'MY';
					return $country;
					break;
				case "Indonesia":
					$country = 'ID';
					return $country;
					break;
				case "Oman":
					$country = 'OM';
					return $country;
					break;
				case "Armenia":
					$country = 'AM';
					return $country;
					break;
				case "Liechtenstein":
					$country = 'LI';
					return $country;
					break;
				case "Colombia":
					$country = 'CA';
					return $country;
					break;
				case "Namibia":
					$country = 'NA';
					return $country;
					break;
				case "Qatar":
					$country = 'QA';
					return $country;
					break;
				case "Italy":
					$country = 'IT';
					return $country;
					break;
				case "Angola":
					$country = 'AO';
					return $country;
					break;
				case "Algeria":
					$country = 'DZ';
					return $country;
					break;
				case "Israel":
					$country = 'IL';
					return $country;
					break;
				case "Dominican Republic":
					$country = 'DO';
					return $country;
					break;
				case "Botswana":
					$country = 'BW';
					return $country;
					break;
				case "Portugal":
					$country = 'PT';
					return $country;
					break;
				case "Cyprus":
					$country = 'CY';
					return $country;
					break;
				case "Iceland":
					$country = 'IS';
					return $country;
					break;
				case "Kenya":
					$country = 'KE';
					return $country;
					break;
				case "Serbia":
					$country = 'RS';
					return $country;
					break;
				case "Tunisia":
					$country = 'TN';
					return $country;
					break;
				case "Suriname":
					$country = 'SR';
					return $country;
					break;
				case "Libyan Arab Jamahiriya":
					$country = 'LY';
					return $country;
					break;
				case "United States Minor Outlying Islands":
					$country = 'UM';
					return $country;
					break;
				case "Georgia":
					$country = 'GE';
					return $country;
					break;
				case "Rwanda":
					$country = 'RW';
					return $country;
					break;
				case "Nigeria":
					$country = 'NG';
					return $country;
					break;
				case "Mozambique":
					$country = 'MZ';
					return $country;
					break;
				case "Mongolia":
					$country = 'MN';
					return $country;
					break;
				case "Trinidad and Tobago":
					$country = 'TT';
					return $country;
					break;
				case "Macao":
					$country = 'MO';
					return $country;
					break;
				case "Lithuania":
					$country = 'LT';
					return $country;
					break;
				case "Kyrgyzstan":
					$country = 'KG';
					return $country;
					break;
				case "Tajikistan":
					$country = 'TJ';
					return $country;
					break;
				case "Viet Nam":
					$country = 'VN';
					return $country;
					break;
				case "Moldova, Republic of":
					$country = 'MD';
					return $country;
					break;
				case "Malta":
					$country = 'MT';
					return $country;
					break;
				case "New Caledonia":
					$country = 'NC';
					return $country;
					break;
				case "Malawi":
					$country = 'MW';
					return $country;
					break;
				case "Pakistan":
					$country = 'PK';
					return $country;
					break;
				case "Aruba":
					$country = 'AW';
					return $country;
					break;
			}
		}
}
?> 