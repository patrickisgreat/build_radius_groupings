<?php
class Cluster {
	//public and private props
		public $offset;
	    public $radius;
	    protected $link;
	    protected $getData;

	    public function __construct($offset, $radius, DbConnect $link, GetData $getData) {
	    	$this->offset = $offset;
	    	$this->radius = $radius;
	    	$this->getData = $getData;
	    }
	   

		public function lonToX($lon) {
 			return round(OFFSET + RADIUS * $lon * pi() / 180);  
		}

		public function latToY($lat) {
    			return round(OFFSET - RADIUS * 
                log((1 + sin($lat * pi() / 180)) / 
                (1 - sin($lat * pi() / 180))) / 2);
		}

		public function haversineDistance($lat1, $lon1, $lat2, $lon2, $earthRadius = 3959)
		{
			  // convert from degrees to radians
			  $latFrom = deg2rad($lat1);
			  $lonFrom = deg2rad($lon1);
			  $latTo = deg2rad($lat2);
			  $lonTo = deg2rad($lon2);

			  $latDelta = $latTo - $latFrom;
			  $lonDelta = $lonTo - $lonFrom;

			  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
			    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
			  return $angle * $earthRadius;
		}

		public function cluster($markers, $distance) {
		    $clustered = array();
		    /* Loop until all markers have been compared. */
		    while (count($markers)) {
		        $marker  = array_pop($markers);
		        $cluster = array();
		        /* Compare against all markers which are left. */
		        foreach ($markers as $key => $target) {
		            $haverSine = $this->haversineDistance($marker['lat'], $marker['lng'], $target['lat'], $target['lng']);
		            /* If two markers are closer than given distance remove */
		            /* target marker from array and add it to cluster.      */
		            if ($distance > $haverSine) {
		                unset($markers[$key]);
		                $cluster[] = $target;
		            }
		        }
		        /* If a marker has been added to cluster, add also the one  */
		        /* we were comparing to and remove the original from array. */
		        if (count($cluster) > 0) {
		            $cluster[] = $marker;
		            $clustered[] = $cluster;
		        } else {
		            $clustered[] = $marker;
		        }
		    }
		    return $clustered;
		}

		public function updateDb($clusters, $zoomLevel) {
			$clustered = $clusters;
			$zoom = $zoomLevel;
				foreach ($clustered as $key => &$val) {					
					$count = count($val);
					$key = key($val);
					//if this is a child array give it a unique value if the first key has never been set
					if ($key !== "userid") {
						foreach ($val as $k => &$v) {
							 if ($k == 0 && $v[$zoom] == null) {
								$groupVal = uniqid();
							//or add it to an existing group if the first key was set before
							} else if ($k == 0 && $v[$zoom] !== null) {
								$groupVal = $v[$zoom];
							} 
							$update = sprintf(	
				          			"UPDATE userfield SET %s = '%s' WHERE userid = '%s'",
				          			mysql_real_escape_string($zoom),
				          			mysql_real_escape_string($groupVal),
				          			mysql_real_escape_string($v['userid'])
				          		);
							echo "is array";
							echo "<br />";
							echo $update;
							echo "<br />";
							mysql_query($update);
						}
					//if it didn't belong to any cluster -- give it a unique value
					} else {
						$groupVal = uniqid();
						$update = sprintf(	
				          			"UPDATE userfield SET %s = '%s' WHERE userid = '%s'",
				          			mysql_real_escape_string($zoom),
				          			mysql_real_escape_string($groupVal),
				          			mysql_real_escape_string($val['userid'])
				          		);
							echo "is NOT array";
							echo "<br />";
							echo $update;
							echo "<br />";
							mysql_query($update);
					}
					
				}
			return true;
		}
		
		public function build() {
				$markers = $this->getData->buildMarkers();
				for ($i=0; $i<7; $i++) {
					if ($i == 0) {
						//$clustered = $this->cluster($markers, 300);
						//$this->updateDb($clustered, 'zoomLevel1');
					} else if ($i == 1) {
						//$clustered = $this->cluster($markers, 200);
						//$this->updateDb($clustered, 'zoomLevel2');
					} else if ($i == 2) {
						//$clustered = $this->cluster($markers, 150);
						//$this->updateDb($clustered, 'zoomLevel3');
					} else if ($i == 3) {
						//$clustered = $this->cluster($markers, 100);
						//$this->updateDb($clustered, 'zoomLevel4');
					} else if ($i == 4) {
						//$clustered = $this->cluster($markers, 50);
						//$this->updateDb($clustered, 'zoomLevel5');
					} else if ($i == 5) {
						$clustered = $this->cluster($markers, 25);					
						$this->updateDb($clustered, 'zoomLevel6');
					} 
				}
			return true;
		}
}
?>