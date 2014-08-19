<?php
	class processcsv extends common {
		var $imagelc;
		var $mp3lc;
		var $dbcon  = null;
		var $table  = '';
		var $smarty = null;
		var $lang   = null;
		
		public function __construct(){
			$this->dbcon  = &$GLOBALS['dbcon'];
			$this->table  = &$GLOBALS['table'];
			$this->smarty = &$GLOBALS['smarty'];
			$this->lang   = &$GLOBALS['_LANG'];
		}
		public function __destruct(){
			unset( $this->dbcon, $this->table, $this->smarty, $this -> lang , $this->imagelc, $this->mp3lc);
		}
		/**
		 * check the per online store wishlist
		 * param @StoreID
		 */
		
		public function uploadSaveCsv($StoreID,$csvfile,$type){
			$emailfiles  = $this->exploadCSVfile($csvfile);
			if($type == 'gallery'){
				$arykey = array('Email');
			}else{
				$arykey = array(0=>'Email',1=>'Name');
			}
			if(count($emailfiles)>1){
				if($emailfiles[0]!=$arykey){
					return array('num'=>0,'msg'=>"The titles in the csv don&#039;t match the standardized titles completely.");
				}
				$query = "insert into {$this->table}upload_email (`emailAddress`,`emailName`,`type`,`StoreID`)VALUES";
				$values = "";
				for ($i=1,$j=0;$i<count($emailfiles);$i++){
					$values .= ($values=="")?"":",";
					if(eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$emailfiles[$i][0])){
						$values .= "('".addslashes($emailfiles[$i][0])."','".addslashes($emailfiles[$i][1])."','$type','$StoreID')";
						$j++;
					}
				}
				if($values){
					if($this->dbcon->execute_query($query.$values)){
						$result = $this->showCSVEmail($StoreID,$type);
						if($result){
							return array('num'=>count($result),'msg'=>count($result).' items uploaded successfully.');
						}
						return array('num'=>0,'msg'=>'uploaded unsuccessfully.');
					}else{
						return array('num'=>0,'msg'=>'uploaded unsuccessfully.');
					}
				}else{
					return array('num'=>0,'msg'=>'uploaded unsuccessfully.');
				}
			}else{
				return array('num'=>0,'msg'=>'uploaded unsuccessfully.');//Empty in email list.
			}
		}
		
		public function showCSVEmail($StoreID,$type){
			$query = "SELECT * FROM {$this->table}upload_email where type='$type' and StoreID='$StoreID' group by emailAddress";
			$this->dbcon->execute_query($query);
			return $this->dbcon->fetch_records(true);
		}
		
		public function deleteCSVEmailbyStoreType($StoreID,$type){
			$query = "delete from {$this->table}upload_email where type='$type' and StoreID='$StoreID'";
			return $this->dbcon->execute_query($query);
		}
		
		public function exploadCSVfile($csvfile){
			$file = fopen($csvfile['tmp_name'],'r');
			$emails = array();
			while(($row = fgetcsv($file,0,',','"'))!==false){
				$emails[] = $row;
			}
			fclose($file);
			return $emails;
		}
	}
?>