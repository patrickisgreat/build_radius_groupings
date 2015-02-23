<?php
class Post {
		/*@__PROPS__instantiated@*/
		protected $dbConnect;

		//construct
		public function __construct(DbConnect $link, $filePath) {
			$this->filePath = $filePath;
		}

		public function post($data) {
			//update for srvr specific path
			if (file_exists($this->filePath)) {
				file_put_contents('/json_test/test.json', $data);
			} else {
				$fh = fopen($this->filePath, 'w') or die ("Can't create json file for MLA, it's probably permissions");
				file_put_contents('/json_test/test.json', $data);
			}
		}

}
?>