<?php

/**
 * $Id class.db.php 2 jacky.zhou Thu May 22 10:37:12 CST 2008 10:37:12 $
 * 
 * @package buyblitz/
 * @subpackage  include/
 */

class DatabaseConnection
{
	var $_host;
	var $_user;
	var $_pass;
	var $_database;
	var $_link;

	var $_query;
	var $_result;
	var $_errorstr;
	var $_success;

	var $_row = 0;

	function DatabaseConnection($host="",$user="",$pass="",$database="")
	{
		$this->_host = $host ;
		$this->_user  = $user ;
		$this->_pass = $pass ;
		$this->_database = $database ;

		if(!DatabaseConnection::connectDB())
		{
			DatabaseConnection::showerror() ;
			exit() ;
		}
	} //end databasse connection

	/**
	 * connect datebase
	 *
	 * @return boolean
	 */
	function connectDB(){
		$ret = true;
		$this->_link = mysql_connect($this->_host,$this->_user,$this->_pass) ;
		if(!$this->_link)
		{
			$this->_errorstr = "could not connect to Server " . mysql_error($this->_link) ;
			$ret = false ;
		}
		else
		{
			if(!empty($this->_database)) {
				if(!mysql_select_db($this->_database,$this->_link)) {
					$this->_errorstr = "could not connect to database " . mysql_error($this->_link) ;
					$ret = false ;
				}else {
					$this->execute_query("set names utf8");
				}
			} //if
		} //else
		return $ret ;
	}

	/**
	 * execute query
	 *
	 * @param string $query
	 * @return boolean
	 */
	function execute_query($query) {

		$ret	=	false;
		if(!empty($this->_database)) {
			if(!mysql_select_db($this->_database,$this->_link)) {
				$this->_errorstr = "could not connect to database " . mysql_error($this->_link) ;
			}
		}

		$this->_errorstr = "" ;
		$this->_query = $query ;
		$this->_result = mysql_query($this->_query) or mysql_error() ;

		if(!$this->_result) {
			$this->_errorstr = "Could not connect to database " .mysql_error($this->_link) ;
		}
		else
		{
			$ret = true ;
		}

		return $ret ;
	} // end execute query


	/**
	 * insert record
	 *
	 * @param string $tblname
	 * @param array $queryarr
	 * @return boolean
	 */
	function insert_query($tblname,$queryarr){
		$fields = array_keys($queryarr) ;
		$values = array_values($queryarr) ;

		$values2 = "" ;
		for($i=0;$i<count($values);$i++)
		{
			$values2 .= '"' . htmlentities(trim($values[$i])) . '",' ;
		}

		$fields = implode(",",$fields) ;
		$values2 = substr($values2,0,-1) ;
		$query = "insert into $tblname($fields) values($values2)  " ;
		$this->_query = $query;

		$this->_result = mysql_query( $this->_query, $this->_link ) ;
		if ( ! $this->_result )
		{
			$this->_errorstr = "Error : ".mysql_error( $this->_link );
			$this->showerror() ;
			$reti = false;
		}
		else
		{
			$reti = true;
		}
		return $reti ;
	}


	/**
	 * insert record
	 * insert at 20080215
	 * @author ping.hu <enquiries@infinitytechnologies.com.au>
	 * @param string $tblname
	 * @param array $queryarr
	 * @param string $condition
	 * @return boolean
	 */
	function insert_record($tblname,$arrQuery,$condition='')
	{
		$boolResult	=	false;
		if (is_array($arrQuery)) {
			$fields = '';
			$values = '';
			foreach ($arrQuery as $key => $var)
			{
				$fields .= ",`$key`";
				$values .= ',\'' . trim($var) . '\'' ;
				//$values .= ',\'' . htmlentities(trim($var)) . '\'' ;
			}
			$fields	=	substr($fields,1);
			$values	=	substr($values,1);

			$this->_query = "insert into $tblname ( $fields ) values ( $values ) $condition" ;

			$this->_result = mysql_query( $this->_query, $this->_link ) ;
			if ( ! $this->_result )
			{
				$this->_errorstr = "Error : ". mysql_error( $this->_link ) . "<br><br>" .$this->_query;
				$this->showerror() ;
			}
			else
			{
				$boolResult = true;
			}
		}
		else
		{
			$this->_errorstr = "Error : Parameter isn't full." ;
			$this->showerror() ;
		}
		return $boolResult;
	}
	/**
	 * return the autoincrement id of insert
	 * insert at 20080523
	 * @author Jessee Wang <enquiries@infinitytechnologies.com.au>
	 * @return int
	 */
	function insert_id(){
		return mysql_insert_id();
	}
	
	/**
	 * is exist for check record from datetable 
	 *
	 * @param string $tblName
	 * @param string $condition
	 * @return boolean
	 */
	function checkRecordExist($tblName,$condition)
	{
		$boolResuml	=	false;
		$this->_query = "select count(*) from $tblName $condition";
		$this -> execute_query($this->_query);
		$temp =  $this -> fetch_records();
		if (is_array($temp) && $temp[0][0]>0) {
			$boolResuml = true;
		}
		return $boolResuml;
	}

	/**
	 * update records
	 * 
	 * @author ping.hu <enquiries@infinitytechnologies.com.au> at 20080215
	 * @param string $tblname
	 * @param array $arrQuery
	 * @param string $condition
	 * @return boolean
	 */
	function update_record($tblname,$arrQuery,$condition='')
	{
		$boolResult	=	false;
		if (is_array($arrQuery)) {
			$fields = '';
			$values = '';
			foreach ($arrQuery as $key => $var)
			{
				$values .= ",`$key`='" . trim($var) . "'" ;
			}
			$values	=	substr($values,1);

			$this->_query = "update $tblname set $values $condition" ;
			//			exit;
			$this->_result = mysql_query( $this->_query, $this->_link ) ;
			if ( ! $this->_result ){
				$this->_errorstr = "Error : ". mysql_error( $this->_link ) . "<br><br>" .$this->_query;
				$this->showerror() ;
			}else{
				$boolResult = true;
			}

		}else{
			$this->_errorstr = "Error : Parameter isn't full." ;
			$this->showerror() ;
		}
		return $boolResult;
	}

	/**
	 * update records
	 *
	 * @param string $tblName
	 * @param array $queryArr
	 * @param string $condition
	 * @return boolean
	 */
	function update_query($tblName,$queryArr,$condition='')
	{
		if(!empty($this->_database)){
			if(!mysql_select_db($this->_database, $this->_link) ) {
				$this->_errorstr = "Couldn't change database: ".$this->_database." My-SQL Error ".mysql_error($this->_link);
			}
		}

		$fields=array_keys($queryArr);
		$values=array_values($queryArr);
		$update="";

		for($i=0;$i<count($values);$i++)
		{
			$update.="$fields[$i]=\"".htmlentities(trim($values[$i]))."\",";
		}

		$update=substr($update,0,-1);

		$query="update $tblName set $update $condition";
		$this->_errorstr = "";

		$this->_query = $query;
		$this->_result = mysql_query( $this->_query, $this->_link );

		if ( ! $this->_result )
		{
			$this->_errorstr = "Error : ".mysql_error( $this->_link );
			$this->_success = false;
		}
		else
		{
			$this->_success = true;
		}

		return $this->_success;

	}


	/**
	 * get records
	 *
	 * @author ping.hu <enquiries@infinitytechnologies.com.au> at 20080217
	 * @param boolean $MYSQL_ASSOC
	 * @return array or false
	 */
	function fetch_records($MYSQL_ASSOC=false) {
		$records = false;
		if($this->count_records() != 0 ){
			if ($MYSQL_ASSOC) {
				while($rec =  mysql_fetch_array($this->_result , MYSQL_ASSOC)){
					$records[] = $rec ;
				}
			}else{
				while($rec =  mysql_fetch_array($this->_result)){
					$records[] = $rec ;
				}
			}
		}
		else
		{
			$this->_errorstr = "Error: Empty Result Set ";
			unset( $rec );
		}


		return $records ;
	}


	/**
	 * count records
	 *
	 * @return int
	 */
	function count_records() {
		if($this->_result)
		{
			$this -> _row =  mysql_num_rows($this->_result) ;
		}
		else
		{
			$this -> _row = 0 ;
		}
		return  $this -> _row;
	}



	function showpaging($table,$file)
	{
		$this->_table = $table ;
		$this->_file = $file ;
		$result =  mysql_query("select count(*) from $this->_table") ;

		if(!$this->_result){
			$this->_errorstr = "Some errors in query " . mysql_error($this->_link) ;
			$this->showerror() ;
			exit() ;
		}

		$this->_row = mysql_fetch_array($result) ;
		$rows = $this->_row[0] ;
		$pages = ceil($rows / $this->_limit) ;

		echo "<table  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">" ;
		echo "<tr>" ;

		$i = 1 ;

		while($i <= $pages)
		{
			echo "<td><font face=\"Verdana\" size=\"1\"><a href=\"$this->_file?pno=$i\">$i</a></font>&nbsp;</td>" ;
			$i++ ;
		}

		echo "</tr>" ;
		echo "</table>" ;
	}

	/**
	 * create sql for list's page
	 * 
	 * changed it by ping.hu at Wed Jan 30 10:22:26 CST 2008
	 * 
	 * @param string $table
	 * @param string $query
	 * @param string $limit
	 * @param string $pno
	 * @return string
	 */
	function pagequery($table,$query,$limit,$pno)
	{
		$this->_pno = $pno ;
		$this->_limit = $limit ;
		$this->_table = $table ;
		$this->_result =  mysql_query("select count(*) from $this->_table") ;
		$this->_row = mysql_fetch_array($this->_result) ;

		//		$rows = $this->_row[0] ;
		//		$pages = ceil($rows / $this->_limit) ;
		$this->_start = (($this->_limit*$this->_pno) - $this->_limit) ;
		$sql = $query . " limit $this->_start , $this->_limit" ;

		return $sql ;
	}



	/**
	 * show list and pages
	 *
	 * changed it by ping.hu at Wed Jan 30 10:22:26 CST 2008
	 * 
	 * @param string $table
	 * @param string $query
	 * @param string $file
	 * @param int $pno
	 * @return void
	 */
	function showpaging_query($table,$query,$file,$pno)
	{
		//		$strResult	=	"";
		$this->_table = $table ;
		$this->_file = $file ;
		$this->_pno = $pno ;

		$result =  mysql_query($query) ;
		$rows = mysql_num_rows($result) ;
		$pages = ceil($rows / $this->_limit) ;

		$strResult = "<table  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">" ;
		$strResult .=  "<tr>" ;

		$i = $this->_pno ;
		$next = $this->_pno + 1 ;
		$previous = $this->_pno - 1 ;
		$noOfTImes = 10 ;
		$noOfTImes = $noOfTImes + $this->_pno ;

		if($noOfTImes>$pages)
		$noOfTImes = $pages ;

		$im = $pages - 10 ;

		if($i>$im)  {   $i = $im ;  }

		if($i>1)
		$strResult .=  "<td><font face=\"Verdana\" size=\"1\"><a href=\"$this->_file?pno=$previous\"><u>Previous</u></a></font>&nbsp;</td>" ;

		while($i <= $noOfTImes)
		{
			if($pno==$i){
				$strResult .=  "<td><font face=\"Verdana\" size=\"1\">$i</font>&nbsp;</td>" ;
			}else{
				$strResult .=  "<td><font face=\"Verdana\" size=\"1\"><a href=\"$this->_file?pno=$i\"><u>$i</u></a></font>&nbsp;</td>" ;
			}
			$i++ ;
		}

		if($next<=$pages)
		$strResult .=  "<td><font face=\"Verdana\" size=\"1\"><a href=\"$this->_file?pno=$next\"><u>Next</u></a></font>&nbsp;</td>" ;
		$strResult .=  "</tr>" ;
		$strResult .=  "</table>" ;

		echo $strResult;
	}

	/////////////////////////////////////////////////////Pankaj/////////////////////////////////////////////////

	/**
	 * show paging
	 *
	 * @param string $table
	 * @param string $file
	 * @param string $cond
	 * @return void
	 */
	function showpaging_con($table,$file,$cond)
	{
		$this->_table = $table ;
		$this->_file = $file ;
		$this->_condition = $cond ;

		$result =  mysql_query("select count(*) from $this->_table this->_condition") ;

		if(!$this->_result){
			$this->_errorstr = "Some errors in query " . mysql_error($this->_link) ;
			$this->showerror() ;
			exit() ;
		}

		$this->_row = mysql_fetch_array($result) ;
		$rows = $this->_row[0] ;
		$pages = ceil($rows / $this->_limit) ;

		echo "<table  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">" ;
		echo "<tr>" ;

		$i = 1 ;
		while($i <= $pages)
		{
			echo "<td><font face=\"Verdana\" size=\"1\"><a href=\"$this->_file?pno=$i\">$i</a></font>&nbsp;</td>" ;
			$i++ ;
		}

		echo "</tr>" ;
		echo "</table>" ;
	}


	/**
	 * create sql of page query
	 *
	 * @param string $table
	 * @param int $limit
	 * @param int $pno
	 * @param string $cond
	 * @return string
	 */
	function pagequery_con($table,$limit,$pno,$cond)
	{
		$this->_pno = $pno ;
		$this->_limit = $limit ;
		$this->_table = $table ;
		$this->_condition = $cond ;

		$this->_result =  mysql_query("select count(*) from $this->_table") ;
		$this->_row = mysql_fetch_array($this->_result) ;

		//		$rows = $this->_row[0] ;
		//		$pages = ceil($rows / $this->_limit) ;
		$this->_start = (($this->_limit*$this->_pno) - $this->_limit) ;
		$sql = "select * from  $this->_table $this->_condition limit $this->_start , $this->_limit " ;

		return $sql ;
	}

	/////////////////////////////////////////Pankaj//////////////////////////////////////////////////////////////

	/**
	 * generate page wise serial no
	 *
	 * @return int
	 */
	function srno() {
		$no = (($this->_limit * ($this->_pno - 1)) + 1) ;
		return $no ;
	}

	/**
	 * disconnect database
	 * @return void
	 */
	function disconnectDB() {
		if($this->_link)
		@mysql_close( $this->_link );
	}

	/**
	 * count page numbers
	 *
	 * @param string $table
	 * @param int $limit
	 * @return int
	 */
	function pno($table,$limit, $condition='')
	{
		$this->_table = $table ;
		$this->_limit = $limit ;
		$result =  mysql_query("select count(*) from $this->_table " . $condition) ;

		if(!$this->_result){
			$this->_errorstr = "Some errors in query " . mysql_error($this->_link) ;
			$this->showerror() ;
			exit() ;
		}

		$this->_row = mysql_fetch_array($result) ;

		$rows = $this->_row[0] ;
		$pages = ceil($rows / $this->_limit) ;

		return $pages ;
	}
	
	function getOne($sql) {
		$this->execute_query($sql);
		$res = $this->fetch_records(true);
		if ($res) {
			return $res[0];
		}
		
		return ;
	}

	/**
	 * show error
	 * @return void
	 */
	function showerror(){
		echo $this->_errorstr;
	}


	/**
	 * show last query.
	 * 
	 * @return void
	 */
	function lastquery() {
		echo $this->_query ;
	}

	function lastInsertId()
	{
		$intResuml	=	0;

		$intResuml = mysql_insert_id($this->_link);

		return $intResuml;
	}

	/**
	 * get response rows
	 * 
	 * @author ping.hu <2007-12-29>
	 * @return int
	 */
	function getResponseRows()
	{
		$intResult = @mysql_affected_rows($this->_link);
		return $intResult;
	}


	/**
	 * destory oneself
	 * 
	 * @return void
	 */
	function destroy() {
		unset($this->_hostname);
		unset($this->_username);
		unset($this->_password);
		unset($this->_database);
		unset($this->_link);
		unset($this->_resultType);
		unset($this->_query);
		unset($this->_result);
		unset($this->_row);
		unset($this->_errorstr);
		unset($this->_success);
	}
	//---------------------------
	
	/**
	 * begin an transaction
	 * @access public
	 */
	function beginTrans() {
		mysql_query('SET AUTOCOMMIT=0');
		mysql_query('START TRANSACTION');
	}
	
	/**
	 * commit the transaction
	 * @access public
	 */
	function commitTrans() {
		mysql_query('COMMIT');
	}
	
	/**
	 * transaction rollback
	 * @access public
	 */
	function rollbackTrans() {
		mysql_query('ROLLBACK');
	}
	
	/**
	 * close the transaction
	 * @access public
	 */
	function endTrans() {
		mysql_query("END");
		mysql_query('SET AUTOCOMMIT=1');
	}
}//class

?>