<?php
class BuildRadians {
		//public and private props
		public $primaryCount = 0;
	    public $members = array();
	    

		//construct
		function __construct($dbhost, $un, $pw, $usedb) {
			$this->dbhost = $dbhost;
			$this->un = $un;
			$this->pw = $pw;
			$this->usedb = $usedb;

		}

		//database connect
		private function connect() {
			$link = mysql_connect($this->dbhost, $this->un, $this->pw);
			if (!$link) {
			    die('Not connected : ' . mysql_error());
			}

			$db_selected = mysql_select_db($this->usedb, $link);
			if (!$db_selected) {
			    die ('Can\'t use '.$this->usedb.' : ' . mysql_error());
			}

			return $link;
		}

		private function primaryQuery($whereClause) {
			//us.username
			//LEFT OUTER JOIN user as us ON uf.userid = us.userid
			$results = mysql_query('SELECT uf.userid, uf.field9, uf.field10, uf.field19, uf.field23, uf.field24, uf.field61, uf.field22, uf.field13, uf.field14, uf.lat, uf.lng, uf.zoomLevel1, uf.zoomLevel2, uf.zoomLevel3
									FROM userfield as uf
									WHERE ('.$whereClause.');
									');
			if (!$results) {
				echo 'Invalid query: ' . mysql_error();
				return 'Invalid query: ' . mysql_error();
			} else {
					return $results;	
			}
		}
		
		private function distanceQuery($radius, $center_lat, $center_lng, $whereClause = false) {
			//add back into query after
			//LEFT OUTER JOIN user as us ON uf.userid = us.userid
			//LEFT OUTER JOIN user as us ON uf.userid = us.userid  chi 
			//quick check
			if ($radius == '300') {
				$zoom = 'zoomLevel1';
			} else if ($radius == '150') {
				$zoom = 'zoomLevel2';
			} else if ($radius == '50') {
				$zoom = 'zoomLevel3';
			}

			if ($whereClause) {
				$format = "SELECT uf.userid, uf.field61, uf.".$zoom.", ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) 
	    	AS distance FROM userfield as uf ".$whereClause." HAVING distance < '%s' ORDER BY distance";
			} else {
				$format = "SELECT uf.userid, uf.field61, uf.".$zoom.", ( 3959 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) 
	    	AS distance FROM userfield as uf HAVING distance < '%s' ORDER BY distance";
			}
			$query = sprintf($format,
		    	mysql_real_escape_string($center_lat), 
		    	mysql_real_escape_string($center_lng),
		    	mysql_real_escape_string($center_lat), 
		    	mysql_real_escape_string($radius),
		    	mysql_real_escape_string($whereClause)
		    	);
			$results = mysql_query($query);
			if (!$results) {
				echo 'Invalid query: ' . mysql_error();
				return 'Invalid query: ' . mysql_error();
			} else {
				return $results;	
			}
	    	
		}

		private function updateQuery($zoomLevel, $id, $radius) {
			//
			if ($radius == '300') {
				$zoom = "zoomLevel1";
			} else if ($radius == '150') {
				$zoom = "zoomLevel2";
			} else if ($radius == '50') {
				$zoom = "zoomLevel3";
			}
			$query = sprintf("UPDATE userfield SET %s = '%s' WHERE userid='%s'",
		  		mysql_real_escape_string($zoom),
		  		mysql_real_escape_string($zoomLevel), 
		    	mysql_real_escape_string($id)
		 	);
				
				$results = mysql_query($query);
				echo $query." is the distance query! <br />";
	    	if (!$results) {
				echo 'Invalid query: ' . mysql_error();
				return 'Invalid query: ' . mysql_error();
			} else {
					return $results;	
			}
		}

		//look for the max group value already set for zoomLevel1 to indicate the next level if a non grouped geocode has no relatives
		private function checkGroupVals() {
			//echo "checkGroupVals running<br />";
			$groupValQuery1 = "SELECT max(zoomLevel1) as maxZ1 from userfield as uf";
			$groupValQuery2 = "SELECT max(zoomLevel2) as maxZ2 from userfield as uf";
			$groupValQuery3 = "SELECT max(zoomLevel3) as maxZ3 from userfield as uf";
			
			$checkGroupVal1 = mysql_query($groupValQuery1);
			$checkGroupVal2 = mysql_query($groupValQuery2);
			$checkGroupVal3 = mysql_query($groupValQuery3);
			
			if (!$checkGroupVal1) {
				die('Invalid query: ' . mysql_error());
			}

			if (!$checkGroupVal2) {
				die('Invalid query: ' . mysql_error());
			}

			if (!$checkGroupVal3) {
				die('Invalid query: ' . mysql_error());
			}
			
			$groupValResult1 = mysql_fetch_row($checkGroupVal1);
			$groupValResult2 = mysql_fetch_row($checkGroupVal2);
			$groupValResult3 = mysql_fetch_row($checkGroupVal3);
			
			$maxGroupVal = array($groupValResult1[0], $groupValResult2[0], $groupValResult3[0]);
			$groupVal = array();
			
			if ($maxGroupVal[0] <= 0 || $maxGroupVal[0] == "") {
				$groupVal[0] = 0;
			} else {
				$groupVal[0] = $maxGroupVal[0]+1;
			}

			if ($maxGroupVal[1] <= 0 || $maxGroupVal[1] == "") {
				$groupVal[1] = 0;
			} else {
				$groupVal[1] = $maxGroupVal[1]+1;
			}

			if ($maxGroupVal[2] <= 0 || $maxGroupVal[2] == "") {
				$groupVal[2] = 0;
			} else {
				$groupVal[2] = $maxGroupVal[2]+1;
			}
			//print_r($groupVal);
			return $groupVal;
		}

		private function radianLoop($radius, $zoomLevel, $row, $groupVal=false) {
			echo "radian loop is running<br />";
			//if the current row has no val for zoomLevel(n)
			if ($zoomLevel == "") {
				echo "zoomLevel1  for row ".$j." is not Set <br /><br />";
				print_r($row);
				echo "<br /><br />";
				//quick check
				if ($radius == '300') {
					$zoom = 'zoomLevel1';
				} else if ($radius == '150') {
					$zoom = 'zoomLevel2';
				} else if ($radius == '50') {
					$zoom = 'zoomLevel3';
				}

				$firstDistanceQuery = $this->distanceQuery($radius, $row['lat'], $row['lng'], 'WHERE ('.$zoom.' <> 0)');
				
				//var_dump($firstDistanceQuery);

				//any results from the distance with zoomLevel set ?
				$firstDistanceNumResults = mysql_num_rows($firstDistanceQuery);
				//   die();
				//set the primary row's zoom1Level value to the value of the first row in the set
				if ($firstDistanceNumResults > 0) {
					echo "first distance query had results <br />";
					//fetch the last row or greatest distance result from the current row
					mysql_data_seek($firstDistanceQuery, $num_results);
					$lastRow = mysql_fetch_assoc($firstDistanceQuery);
					//update the primary row
					$update = $this->updateQuery($lastRow[$zoom], $row['userid'], $radius);
						//lets run the distance query again with a different where clause to check if there's anything new that hasn't been set that belongs to this group
						$secondDistanceQuery = $this->distanceQuery('300', $row['lat'], $row['lng'], 'WHERE ('.$zoom.' = 0)');
						//any results from the distance with zoomLevel not set (suQuery)
						$secondDistanceNumResults = mysql_num_rows($secondDistanceQuery);

						if ($secondDistanceNumResults > 0) {
							//update all of them to have this groupVal
							while($newRow = mysql_fetch_assoc($secondDistanceQuery)) {
								
								$update = $this->updateQuery($lastRow[$zoom], $newRow['userid'], $radius);
							}
						}
				
				//if there was no results from the distance query
				} else if ($firstDistanceNumResults <= 0) {
					echo "first distance query yielded nothing </br>";

					//no results from above set this row's zoomLevel1 to the unique GroupVal that is the next highest value
					$update = $this->updateQuery($groupVal, $row['userid'], $radius);
					
					$thirdDistanceQuery = $this->distanceQuery('300', $row['lat'], $row['lng']);

					$thirDistanceNumResults = mysql_num_rows($thirdDistanceQuery);

					if ($thirDistanceNumResults > 0) {
						while($newRow = mysql_fetch_assoc($thirdDistanceQuery)) {
								
								$update = $this->updateQuery($groupVal, $newRow['userid'], $radius);
						}
					}
				}

			}
		}
		
		//loop to build radians
		public function buildRadians() {
			$link = $this->connect();
			if ($link) {
				//run primary query -- get results as return
				$result = $this->primaryQuery('uf.lat <> "0.000000"');
				$groupVals = $this->checkGroupVals();
				$groupVal1 = $groupVals[0];
				$groupVal2 = $groupVals[1];
				$groupVal3 = $groupVals[2];
				//run loop with results using switchable query functions
				while ($row = mysql_fetch_assoc($result)) {
					
					for ($i=0; $i < 2; $i++) {
						
						if ($i == 0) {
							//300 mile radius
							$this->radianLoop('300', $row['zoomLevel1'], $row, $groupVal1);
						} else if ($i == 1) {
							//150 mile radius
							$this->radianLoop('150', $row['zoomLevel2'], $row, $groupVal2);
						} else if ($i == 2) {
							//50 mile radius
							$this->radianLoop('50', $row['zoomLevel3'], $row, $groupVal3);
						}
					}//for
				$groupVal1++;
				$groupVal2++;
				$groupVal3++;
				}//while

			} else {
				return "something went wrong trying to connect";
			}
		}


}

?>