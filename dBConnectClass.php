<?php
	class DbConnect {
		//construct db stuff
		public function __construct($dbhost, $un, $pw, $usedb) {
			$this->dbhost = $dbhost;
			$this->un = $un;
			$this->pw = $pw;
			$this->usedb = $usedb;
			$link = $this->connect();

		}

		private function connect() {
			$link = mysql_connect($this->dbhost, $this->un, $this->pw);
			if (!$link) {
			    die('Not connected : ' . mysql_error());
			}

			$db_selected = mysql_select_db($this->usedb, $link);
			if (!$db_selected) {
			    die ('Can\'t use '.$this->usedb.' : ' . mysql_error());
			}
			mysql_set_charset('utf8');
			return $link;
		}
	}
?>