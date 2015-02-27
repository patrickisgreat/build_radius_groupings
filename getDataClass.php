<?php
class GetData {
		
		/* @__PROPS__instantiated@ */
		protected $link;
		protected $geo;
		//construct

		public function __construct(DbConnect $link, GeoCode $geo) {
			//GeoCode dependency injected
			$this->geo = $geo;
		
		}

		public function buildMarkers() {
			$results = mysql_query('SELECT uf.userid, uf.lat, uf.lng, uf.zoomLevel6
						FROM userfield as uf
						WHERE (uf.field22 <> "00000" AND uf.lat IS NOT NULL)
						ORDER BY uf.lat DESC
						');
			while ($row = mysql_fetch_assoc($results)) {
				$markers[] = $row;
			}
			return $markers;
		}

		public function getData() {
			$result = mysql_query('
				SELECT uf.userid, uf.field9, uf.field10, uf.field19, uf.field23, uf.field24, uf.field61, uf.field22, uf.field13, uf.field14, uf.lat, uf.lng, uf.zoomLevel6, us.username,
				us.usertitle, us.posts, us.joindate
				FROM userfield as uf
				LEFT OUTER JOIN user as us ON uf.userid = us.userid
				WHERE (uf.field22 <> "00000"  AND uf.lat is null)
			');

			if (!$result) {
    		die('Invalid query: ' . mysql_error());
			}
			return $result;
		}

		public function getPostData() {
			$result = mysql_query('
				SELECT uf.userid, uf.field9, uf.field10, uf.field19, uf.field23, uf.field24, uf.field61, uf.field22, uf.field13, uf.field14, uf.lat, uf.lng, uf.zoomLevel6, us.username,
				us.usertitle, us.joindate
				FROM userfield as uf
				LEFT OUTER JOIN user as us ON uf.userid = us.userid
				WHERE (uf.field22 <> "00000"  AND uf.lat is not null AND uf.lat <> 0)
			');

			if (!$result) {
    		die('Invalid query: ' . mysql_error());
			}
			$i=0;
			while ($row = mysql_fetch_assoc($result)) {
				if ($row['field61'] !="" && $row['field22'] !="" && $row['field19'] !="" && $row['field23'] !="" && $row['field61'] = $this->geo->guessCountry($row['field61'])) {
					$members[$i]['userid'] = $row['userid'];
					$members[$i]['username'] = $row['username'];
            		//$members[$i]['num_posts'] = $row['posts'];
            		$members[$i]['user_title'] = $row['usertitle'];
            		$dt = new DateTime();
		            $dt->setTimestamp($row['joindate']);
		            $members[$i]['join_date'] = $dt->format("Y");
		            $members[$i]['country'] = $row['field61'];
		            $members[$i]['postalCode'] = $row['field22'];
		            if ($row['field13'] == 0 || $row['field13'] == 'UNKNOWN') {
		               $members[$i]['modelYear'] = '';
		            } else {
		               $members[$i]['modelYear'] = $row['field13'];
		            }
		            if ($row['field14'] != "UNKNOWN") {
		               $members[$i]['model'] = $row['field14'];
		            }
		            $members[$i]['avatarPath'] = "https://www.mercedes-amg.com/privatelounge/images/theme/user_avatar/userAvatar.php?userid=".$row['userid']."";
		            if ( $row['zoomLevel6'] !=="" ) {
		               $members[$i]['zoomLevel6'] = $row['field61'].$row['zoomLevel6'];
		            } else {
		               $members[$i]['zoomLevel6'] = "";
		            }
		            $members[$i]['coords'] = array();
		            $members[$i]['coords']['lat'] = $row['lat'];
		            $members[$i]['coords']['lng'] = $row['lng'];
		            $i++;
				}
			
			}
			$json = json_encode($members, true);
			return $json;
			mysql_free_result($result);
			//return $result;
		}
}
?>