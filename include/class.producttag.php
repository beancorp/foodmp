<?php
	class producttag extends common {
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
			unset( $this->dbcon, $this->table, $this->smarty, $this -> lang );
		}
		/**
		 * check the per online store wishlist
		 * param @StoreID
		 */
		public function save_tags($aryResult){
			if(isset($aryResult['id'])&&$aryResult['id']){
				return $this->dbcon->update_record($this->table."product_tags",$aryResult," where id={$aryResult['id']} ");
			}else{
				$this->dbcon->insert_record($this->table."product_tags", $aryResult);
				return $this->dbcon->insert_id();
			}
		}

		public function save_pro_tags($str_tags,$pid=0,$attribute=0){
			$this->del_pro_tag_ByPID($pid,$attribute);
			if($str_tags){
				$tags_ary = split(',',$str_tags);
				if($tags_ary):
					$i=0;
					foreach ($tags_ary as $pass):
						if(!get_magic_quotes_gpc()){
							$pass = addslashes($pass);
						}
						$pass = trim($pass);
						if($pass!=$this->clean_tag_name($pass)||$pass=="")continue;
						if($i>4)break;
						$i++;
						$pinfo = $this->get_pro_tag_ByTag($pass,$pid,$attribute);
						if($pinfo['newtag']){
							$data = array('pids'=>"\'".$pid."\'",'pro_tags'=>$pass,'attribute'=>$attribute,'createtime'=>time(),'lastupdate'=>time());
							$this->save_tags($data);
						}elseif ($pinfo['update']){
							$data = array('id'=>$pinfo['id'],'pids'=>trim(addslashes($pinfo['pids'].($pinfo['pids']==""?"":",")."'".$pid."'"),","),'attribute'=>$attribute,'lastupdate'=>time());
							$this->save_tags($data);
						}
					endforeach;
				endif;
			}
		}

		public function del_pro_tag_ByPID($pid,$attribute=0){
			$query = "SELECT * FROM {$this->table}product_tags where pids like '%\'$pid\'%'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			if($result){
				foreach ($result as $pass):
					$pidtmp = str_ireplace("'$pid',",'',$pass['pids']);
					$pidtmp = str_ireplace(",'$pid'",'',$pass['pids']);
					$pidtmp = str_ireplace("'$pid'",'',$pidtmp);
					$query = "update {$this->table}product_tags set pids='".addslashes($pidtmp)."' where id='{$pass['id']}' ";
					$this->dbcon->execute_query($query);
				endforeach;
			}
		}

		public function get_pro_tag_ByTag($str,$pid,$attribute=0){
			$query = "SELECT * FROM {$this->table}product_tags where pro_tags='".$str."' and attribute='$attribute'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			if($result){
				$result[0]['update'] = true;
				$result[0]['newtag'] = false;
				if($result[0]['pids']){
					$pids = split(',',$result[0]['pids']);
					if(is_array($pids)&&in_array($pid,$pids)){
						$result[0]['update']=false;
					}else if($pid == $pids){
						$result[0]['update']=false;
					}
				}
				return $result[0];
			}else{
				return array('update'=>false,'newtag'=>true);
			}
		}

		public function get_pro_tags_ByPorID($pid,$attribute=0){
			$query = "SELECT * FROM {$this->table}product_tags where pids like '%\'$pid\'%'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			$str_tags = "";
			if($result){
				foreach ($result as $pass):
					$str_tags .= $str_tags==""?$pass['pro_tags']:",".$pass['pro_tags'];
				endforeach;
			}
			return $str_tags;
		}

		public function get_pro_tags_ByTagKeyword($keyword,$attribute=0){
			if(!get_magic_quotes_gpc()){
				$keyword = addslashes($keyword);
			}
			$query = "SELECT * FROM  {$this->table}product_tags where pro_tags like '%$keyword%'";
			$this->dbcon->execute_query($query);
			$result = $this->dbcon->fetch_records(true);
			$aryStr = "";
			if($result){
				foreach ($result as $pstr):
                    if(!empty($pstr['pids'])){
                        $aryStr .= ($aryStr=="")?$pstr['pids']:",".$pstr['pids'];
                    }
				endforeach;
			}
			if($aryStr){
				$aryStr = str_ireplace('\'',"",$aryStr);
				$resRay = explode(',',$aryStr);
				$resRay = array_unique($resRay);
				$aryStr = implode(',',$resRay);
				$aryStr = trim($aryStr,',');
			}
			return $aryStr;
		}

		public function clean_tag_name($name){
			$pattern = array("/!/","/~/","/`/","/@/","/#/","/\\$/","/%/","/\^/","/&/","/\*/","/\(/","/\)/","/\+/","/\=/","/\{/","/\}/","/\[/","/\]/","/\|/","/;/","/:/","/\"/","/</","/>/","/,/","/\./","/\?/","/\//","/\\\/","/'/");
			$replace = array("","",'');

			$name = preg_replace($pattern,$replace,$name);
			if (strlen($name)>60){
				$name = substr($name,0,60);
			}
			return $name;
		}

	}
?>