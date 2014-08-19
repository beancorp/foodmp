<?php
/**
 * Thu Oct 16 17:27:32 GMT+08:00 2008 17:27:32
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * Adv function adn class of banner
 * ------------------------------------------------------------
 * \include\class.adminadvertising.php
 */

/*
 * All:0
 * Default:-1
 * State:1
 */

class adminAdv extends common {
	var $dbcon 	= 	null;
	var $table	=	'';
	var $smarty = 	null;
	var $lang	=	null;

	/**
	 * @return void 
	 */
	public function __construct()
	{
		$this -> dbcon  = &$GLOBALS['dbcon'];
		$this -> table	= &$GLOBALS['table'];
		$this -> smarty = &$GLOBALS['smarty'];
		$this -> lang	= &$GLOBALS['_LANG'];
	}

	/**
    * @return void 
    */
	public function __destruct(){
		unset($this->dbcon,$this -> table,$this->smarty,$this -> lang);
	}


	public function getBannerAllAndDefaultList($strParam='',$curpage=1){
                if(DATAFORMAT_DB=="%m/%d/%Y"){
			$datef = "m/d/Y";
		}else{
			$datef = "d/m/Y";
		}
		$arrResult	=	null;
		$pageno		=	$curpage >0 ? $curpage : 1;
		$perPage	=	20;
		$sql_where = "where 1 ";

		if ($strParam) {
			$strParam = str_replace('\\','',$strParam);
			$arrParam = unserialize($strParam);
			$arrResult['search'] = array('sid' => $arrParam['sid'],'markets'=>$arrParam['search_markets'],
			'start_date'=>$arrParam['start_date'],'end_date'=>$arrParam['end_date'],'sid2'=>$arrParam['state_id'],'searchparam'=>$strParam);
			if($arrParam['sid']>0){
				$arrResult['search']['sid'] = '1';
				$arrResult['search']['sid2'] = $arrParam['state_id'];
			}

			if (isset($arrParam['sid']) && $arrParam['sid'] != ''&&$arrParam['sid'] == 1) {
				if($arrParam['state_id'] == "-1") {
                                    $sql_where.= ' and state_id NOT IN ("0","-1")';
                                }
                                else {
                                    $sql_where.= ' and state_id LIKE "%,' . $arrParam['state_id'] . ',%" ';
                                }
			}elseif(isset($arrParam['sid']) && $arrParam['sid'] != ''){
				$sql_where.= ' and state_id = "' .$arrParam['sid'] . '"';
                        }
                        
			if($arrParam['start_date']!=0){
				$sql_where .= " and start_date>='".$arrParam['start_date']."' ";
			}
                        
			if($arrParam['end_date']!=0){
				$sql_where .= " and end_date<='".$arrParam['end_date']."' AND end_date>0  ";
			}
                        if('' != $arrParam['search_markets']) {
                            $sql_where .= ' AND `markets` = "' . $arrParam['search_markets'] . '" ';
                        }
		}

		$sql = "select count(*) from ".$this->table."banner_soc $sql_where";//                file_put_contents('aaa_sql.txt', $sql);
		$this->dbcon->execute_query($sql) ;
		$current_count = $this->dbcon->fetch_records();
		$current_count = $current_count[0][0];
		
		($pageno * $perPage > $current_count) ? $pageno = ceil($current_count/$perPage) : '';
		if(!$pageno){
			$pageno = 1;
		}
		$arrResult['pageno'] = $pageno;
		$start	= ($pageno-1) * $perPage;
                $strSqlOrderBy = ' ORDER BY `end_date` ASC,  `start_date` ASC ';
		$sql = "select * from ".$this -> table."banner_soc $sql_where $strSqlOrderBy" ;
		$this -> dbcon->execute_query($sql) ;
		$arrTemp = $this -> dbcon->fetch_records(true);
		if (is_array($arrTemp)) {
			$tmp	=	$arrTemp;
			$i = 0;
			foreach ($arrTemp as $temp){
				$tmp[$i]['banner_img'] = $this->__getImageSize($temp['banner_img'],$temp['replace_image'], $temp['description'], $i) ;
                                $tmp[$i]['start_format'] = $temp['start_date']>0 ? date($datef, $temp['start_date']) : '';
                                $tmp[$i]['end_format'] = $temp['end_date']>0 ? date($datef, $temp['end_date']) : '';
				$i++;
			}
                        $hasEnd = array();
                        $noEnd = array();
                        foreach($tmp  as $t) {
                            if($t['end_date'] > 0) {
                                $hasEnd[] = $t;
                            }else {
                                $noEnd[] = $t;
                            }
                        }
                        $arrList = array_merge($hasEnd, $noEnd);
                        $arrResult['list'] = array_slice($arrList, $start,$perPage);
			$objParams = new pagerClass();
			$arrResult['pager']	=	$objParams -> getLinkAjax($pageno,$current_count,$perPage,'xajax_getBannerAllAndDefaultList(xajax.getFormValues(\'mainForm\'),\'%d\');');
		}
		$arrResult['stateList'] = $this -> __getStateList();
		return $arrResult;
	}

	public function exportBannerAllAndDefaultList($strParam=''){
		$onlinetype = array('State'=>'Buy & Sell','Estate'=>'Real Estate','Auto'=>'Automotive','Job'=>'Job Market');
		if(DATAFORMAT_DB=="%m/%d/%Y"){
			$datef = "m/d/Y";
		}else{
			$datef = "d/m/Y";
		}
		$sql_where = "where 1 ";

		if ($strParam) {
			$strParam = str_replace('\\','',$strParam);
			$arrParam = unserialize($strParam);

			if (isset($arrParam['sid']) && $arrParam['sid'] != ''&&$arrParam['sid'] == 1) {
				if($arrParam['state_id'] == "-1") {
                                    $sql_where.= ' and state_id NOT IN ("0","-1")';
                                }
                                else {
                                    $sql_where.= ' and state_id LIKE "%,' . $arrParam['state_id'] . ',%" ';
                                }
			}elseif(isset($arrParam['sid']) && $arrParam['sid'] != ''){
				$sql_where.= ' and state_id = "' .$arrParam['sid'] . '"';
                        }
			if($arrParam['start_date']!=0){
				$sql_where .= " and start_date>='".$arrParam['start_date']."' ";
			}
			if($arrParam['end_date']!=0){
				$sql_where .= " and end_date<='".$arrParam['end_date']."' AND end_date>0  ";
			}
                        if('' != $arrParam['search_markets']) {
                            $sql_where .= ' AND `markets` = "' . $arrParam['search_markets'] . '" ';
                        }
		}

                $strSqlOrderBy = ' ORDER BY `end_date` ASC,  `start_date` ASC ';
		$sql = "select * from ".$this -> table."banner_soc $sql_where $strSqlOrderBy";
		$this -> dbcon->execute_query($sql) ;
		$arrTemp = $this -> dbcon->fetch_records(true);
		$exportlist[0][1] = "Banner Type" ;
		$exportlist[0][2] = "Description" ;
		$exportlist[0][3] = "Market Place" ;
		$exportlist[0][4] = "Link" ;
		$exportlist[0][5] = "Start Date" ;
		$exportlist[0][6] = "End Date" ;
		$exportlist[0][7] = "Views" ;
		$exportlist[0][8] = "Clicks" ;
		$now = mktime(0,0,0,date('m'),date('d'),date('Y'));
		if(is_array($arrTemp)){
			$i = 1;
                        $hasEnd = array();
                        $noEnd = array();
                        foreach($arrTemp  as $t) {
                            if($t['end_date'] > 0) {
                                $hasEnd[] = $t;
                            }else {
                                $noEnd[] = $t;
                            }
                        }
                        $arrTemp = array_merge($hasEnd, $noEnd);
			foreach ($arrTemp as $pass){
				if($pass['state_id']=="-1"){
					$exportlist[$i][1] = "Default banner";
				}elseif ($pass['state_id']=="0"){
					$exportlist[$i][1] = "All state banner";
				}else{
					$exportlist[$i][1] = "State banner";
				}
				$exportlist[$i][2] = $pass['description'];
				$exportlist[$i][3] = $onlinetype[$pass['markets']];
				$exportlist[$i][4] = $pass['banner_link'];
				$exportlist[$i][5] = $pass['start_date']>0?date($datef,$pass['start_date']):'';
				$exportlist[$i][6] = $pass['end_date']>0?date($datef,$pass['end_date']):'';
				$exportlist[$i][7] = $pass['view_times'];
				$exportlist[$i][8] = $pass['click'];
				$i++;
			}
		}

		return $exportlist;

	}

	
	private function __getImageSize($fileName, $replaceImage, $description, $i){
		$strResult = '';
		$filetype = explode('.',$fileName);
		$filetype = array_pop($filetype);
//		if ($postion == 'left1' || $postion == 'left2'){
//			$width=163;
//			$height=187;
//		}else{
			$width=150;
			$height=240;
//		}
		if ($filetype == 'swf'){

			$strResult ='
<div id="__Flash__' . $i . '__" replace_img="' . $replaceImage . '" title="' . $description . '" >
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="'.$width.'" height="'.$height.'">
			<param name="movie" value="/upload/new/'.$fileName.'" />
			<param name="quality" value="high" />
			<embed src="/upload/new/'.$fileName.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'"></embed>
			</object>
</div>';
		}else{
//			list($wd,$hg) = @getimagesize(ROOT_PATH."/upload/new/$fileName");
//			if($wd!=""&&$hg!=""){
//				$per = 270/170;
//				$tper = $hg/$wd;
//				if($tper>=$per){
//					$nhg = 270;
//					$nwd = intval(270/$tper);
//				}else{
//					$nwd = 170;
//					$nhg = intval(170*$tper);
//				}
//			}else{
//				$nwd = "270";
//				$nhg = "170";
//			}
			
			$strResult ='<img src="/upload/new/'.$fileName.'" width="'.$width.'" height="'.$height.'" border=0 title="' . $description . '">';

		}
		return $strResult;
	}

	public function addBannerAllAndDefault($objForm){
		$arrResult	=	null;

		if (is_array($objForm) && !empty($objForm['searchparam'])) {
			$arrParam = unserialize($objForm['searchparam']);
			if($arrParam['sid']!=0&&$arrParam['sid']!=100){
				$arrResult['sid'] 	= '-1';
				$arrResult['state_id'] 	= $arrParam['sid'];
			}else{
				$arrResult['sid']	=	$arrParam['sid'];
				$arrResult['state_id'] 	= '-1';
			}
			if($arrParam['po']==''){
				$arrParam['po']=1;
			}
			$search_position = explode(',',$arrParam['po']);
			$arrResult['positions'][1]=in_array(1,$search_position)?1:0;
			$arrResult['positions'][2]=in_array(2,$search_position)?1:0;
			$arrResult['positions'][3]=in_array(3,$search_position)?1:0;
			$arrResult['positions'][4]=in_array(4,$search_position)?1:0;
			$arrResult['positions'][5]=in_array(5,$search_position)?1:0;
			$arrResult['positions'][6]=in_array(6,$search_position)?1:0;
		}else {
			if($arrParam['sid']!=0&&$arrParam['sid']!=100){
				$arrResult['sid'] 	= '-1';
				$arrResult['state_id'] 	= $arrParam['sid'];
			}else{
				$arrResult['sid']	=	$arrParam['sid'];
				$arrResult['state_id'] 	= '-1';
			}
			$arrResult['positions'][1] = 1;
		}
		$arrResult['search']['searchparam'] = $objForm['searchparam'];
		$arrResult['stateList'] = $this -> __getStateList();
		$arrResult['pageno'] = $objForm['pageno'];
		return $arrResult;
	}

	public function editBannerAllAndDefault($id,$objForm){
		$arrResult	=	null;
		if (empty($id) or !is_numeric($id)){
			$strResult = "Invalid Banner ID.";
		}else {
			$sql = "select * from ".$this->table."banner_soc where banner_id=$id" ;
			$this->dbcon->execute_query($sql) ;
			$grid = $this->dbcon->fetch_records() ;
			$arrResult = $grid[0];
		}
		$arrResult['search']['searchparam'] = $objForm['searchparam'];
		$arrResult['stateList'] = $this -> __getStateList();
		$arrResult['pageno'] = $objForm['pageno'];
		return $arrResult;
	}

//	public function saveBannerAllAndDefault($arrParams){
//		$strResult	=	'';
//		$img ='left1';
//		$destination = UPLOADDIR ;
//		$base_dir = getcwd();
//		$destination =  $base_dir.'/../upload/new/';
//		$strResult = ($arrParams['searchparam']);
//		if (is_array($arrParams)){
//			$banner_id 	= $arrParams['banner_id'];
//			$state_id 	= $arrParams['stateId'];
//			$position 	= $arrParams['bannerPosition'];
//			$link 		= $arrParams['bannerLink'];
//			$desc 		= $arrParams['message'];
//			$displaypage= $arrParams['displaypage'];
//
//			// check file type
//			$ex = explode('.',$_FILES['bannerFile']['name']);
//			$ex = strtolower(array_pop($ex));
//
//			if ($ex != 'jpg' and $ex !='gif' and $ex != 'png'){
//				$strResult = "Banner file type is invalid.";
//			}elseif ($_FILES['bannerFile']['size'] > 0){
//				$banner_name = time();
//				while(file_exists($destination.$banner_name)){
//					$banner_name = time();
//				}
//				$banner_name.= '.'.$ex;
//
//				if (!move_uploaded_file($_FILES['bannerFile']['tmp_name'],$destination . $banner_name)){
//					$strResult = "Banner uploading failed.";
//
//				}elseif (!is_numeric($banner_id)){
//					$sql = "select count(*) from ".$this->table."banner_soc where position='$position' and state_id = $state_id and displaypage='$displaypage'";
//					$this->dbcon->execute_query($sql);
//					$count = $this->dbcon->fetch_records();
//					$count = $count[0][0];
//
//					$arr = array('state_id'=>$state_id,'position'=>$position,'banner_img'=>$banner_name,'banner_link'=>$link,'description'=>$desc,'displaypage'=>$displaypage);
//					if (($count == 0) or ($count < 3 and $position == 'right1')){
//						if($this->dbcon->insert_query($this->table."banner_soc",$arr)) {
//							$strResult =  "Record Inserted Successfully" ;
//						}
//					}else{
//						$strResult = "Banner exists, please select one to edit.";
//					}
//				}else{
//					$arr = array('banner_link'=>$link,'description'=>$desc);
//					if ($_FILES['bannerFile']['size'] > 0){
//						$arr['banner_img']=$banner_name;
//					}
//					if($this->dbcon->update_query($this->table."banner_soc",$arr,"where banner_id = $banner_id")) {
//						$strResult =  "Record Updated Successfully" ;
//					}
//				}
//			}
//		}
//		return $strResult;
//	}

	public function saveBannerAllAndDefault2($arrParams){
		$strResult	=	'';
		$img ='left1';
		$destination = UPLOADDIR ;
		$base_dir = getcwd();
		$destination =  $base_dir.'/../upload/new/';
		$strResult = ($arrParams['searchparam']);
		if (is_array($arrParams)){
			$banner_id 	= $arrParams['banner_id'];
			$state_id 	= $arrParams['stateId'];
			if($state_id=='1'){
				$state_id = ',' . implode(',',$arrParams['stateId2']) . ',';
			}
//			$position 	= $arrParams['bannerPosition'];
			$link 		= $arrParams['bannerLink'];
			$desc 		= $arrParams['message'];
			$displaypage= $arrParams['displaypage'];
			$start_date = 0;
			$end_date = 0;
			if(DATAFORMAT_DB=="%m/%d/%Y"){
				if($arrParams['start_date']!=""){
					list($month,$day,$year) = split('/',$arrParams['start_date']);
					$start_date = mktime(0,0,0,$month,$day,$year);
				}else{
					$start_date = mktime(0,0,0,date('m'),date('d'),date('Y'));
				}
				if($arrParams['end_date']!=""){
					list($month,$day,$year) = split('/',$arrParams['end_date']);
					$end_date 	= mktime(0,0,0,$month,$day,$year);
				}
			}else{
				if($arrParams['start_date']!=""){
					list($day,$month,$year) = split('/',$arrParams['start_date']);
					$start_date = mktime(0,0,0,$month,$day,$year);
				}else{
					$start_date = mktime(0,0,0,date('m'),date('d'),date('Y'));
				}
				if($arrParams['end_date']!=""){
					list($day,$month,$year) = split('/',$arrParams['end_date']);
					$end_date 	= mktime(0,0,0,$month,$day,$year);
				}
			}
			// check file type
			$ex = explode('.',$_FILES['bannerFile']['name']);
			$ex = strtolower(array_pop($ex));

			if (!is_numeric($banner_id)){
				if ($ex != 'jpg' and $ex !='gif' and $ex != 'png' and $ex != 'swf'){
					$strResult = "Banner file type is invalid.";
				}elseif ($_FILES['bannerFile']['size'] > 0){
					$banner_name = time();
					while(file_exists($destination.$banner_name)){
						$banner_name = time();
					}
					$fileName = $banner_name . '.'.$ex;
					if (!move_uploaded_file($_FILES['bannerFile']['tmp_name'],$destination . $fileName)){
						$strResult = "Banner uploading failed.";
					}
                                        $replaceImage = '';
                                        if($_FILES['bannerFile_img']['size'] > 0) {
                                            $ext = strtolower(end(explode('.', $_FILES['bannerFile_img']['name'])));
                                            if(!in_array($ext, array('jpg','gif','png'))) {
                                                $strResult = "Banner Image file type is invalid.";
                                            }
                                            else {

                                                if(!move_uploaded_file($_FILES['bannerFile_img']['tmp_name'],$destination . $banner_name . '_img.' . $ext)){
                                                    $strResult = "Banner Images uploading failed.";
                                                }
                                                else {
                                                    $replaceImage =  $banner_name . '_img.' . $ext;
                                                }
                                            }
                                        }
                                        

					$arr = array('state_id'=>$state_id,'banner_img'=>$fileName,'banner_link'=>$link,'description'=>$desc,'markets'=>$displaypage,'start_date'=>$start_date,'end_date'=>$end_date,'replace_image'=>$replaceImage);
					if($this->dbcon->insert_query($this->table."banner_soc",$arr)) {
						$strResult =  "Record Inserted Successfully" ;
					}
				}
			}else{
				$arr = array('state_id'=>$state_id,'banner_link'=>$link,'description'=>$desc,'markets'=>$displaypage,'start_date'=>$start_date,'end_date'=>$end_date);
				if ($_FILES['bannerFile']['size'] > 0){
					if ($ex != 'jpg' and $ex !='gif' and $ex != 'png'){
					$strResult = "Banner file type is invalid.";
					return $strResult;
					}
					$banner_name = time();
					while(file_exists($destination.$banner_name)){
						$banner_name = time();
					}
					$fileName = $banner_name . '.'.$ex;
					if (!move_uploaded_file($_FILES['bannerFile']['tmp_name'],$destination . $fileName)){
						$strResult = "Banner uploading failed.";
						return $strResult;
					}
                                        
					$arr['banner_img']=$fileName;
                                        
				}
                                if($_FILES['bannerFile_img']['size'] > 0) {

                                    $banner_name = time();
                                    while(file_exists($destination.$banner_name)){
                                            $banner_name = time();
                                    }
                                    $ext = strtolower(end(explode('.', $_FILES['bannerFile_img']['name'])));
                                    $replaceImage = '';
                                    if(!in_array($ext, array('jpg','gif','png'))) {
                                        $strResult = "Banner Image file type is invalid.";
                                    }
                                    else {
                                        if(!move_uploaded_file($_FILES['bannerFile_img']['tmp_name'],$destination . $banner_name . '_img.' . $ext)){
                                            $strResult = "Banner Images uploading failed.";
                                        }
                                        else {
                                            $replaceImage = $banner_name . '_img.' . $ext;
                                        }
                                    }
                                    $arr['replace_image'] = $replaceImage;
                                }
                                

				if($this->dbcon->update_query($this->table."banner_soc",$arr,"where banner_id = $banner_id")) {
					$strResult =  "Record Updated Successfully" ;
				}
			}
		}
		return $strResult;
	}
	
	
	public function deleteBannerAllAndDefault($id){
		$strResult	=	'';
		$destination = UPLOADDIR ;
		$base_dir = getcwd();
		$destination =  $base_dir.'/../upload/new/';
		if ($id){
			$sql = "select * from ".$this->table."banner_soc where banner_id=$id";
			$this->dbcon->execute_query($sql);
			$grid = $this->dbcon->fetch_records();
			if ($grid){
				@!unlink($destination.$grid[0]['banner_img']);
				
				if($this->dbcon->execute_query("delete from ".$this->table."banner_soc where banner_id=$id")) {
					$strResult =  "Banner deleted successfully" ;
				}else{
					$strResult = "Failed to delete Banner.";
				}
			}else{
				$strResult = "Invalid banner ID.".$sql;
			}
		}else{
			$strResult = "Invalid banner ID.";
		}

		return $strResult;
	}


	private function __getStateList(){
		$arrResult = null;

		$sql = "select * from ".$this->table."state order by description,stateName";
		$this -> dbcon -> execute_query($sql);
		$arrTemp = $this -> dbcon -> fetch_records();
		if (is_array($arrTemp)) {
			$arrResult = $arrTemp;
		}

		return  $arrResult;
	}

	public function getBannerStateList($pageno = 1, $strParam = ''){
		$arrResult	=	null;
		$pageno		=	$pageno >0 ? $pageno : 1;
		$perPage	=	7;

		$position = array('left1'=>'Left Banner One','left2'=>'Left Banner Two','left3'=>'Left Banner Three','right1'=>'Right Banner One','right2'=>'Right Banner Two','right3'=>'Right Banner Three');
		$search_position = array('left1','left2','left3','right1','right2','right3');

		$arrResult['position']	= $position;
		$arrResult['stateList'] = $this -> __getStateList();

		$sql_where = 'where '.$this->table.'banner_soc.state_id='.$this->table.'state.id and state_id > 0 and state_id < 100 ';
		if ($strParam) {
			$strParam = str_replace('\\','',$strParam);
			$arrParam = unserialize($strParam);
			$arrResult['search'] = array('sid' => $arrParam['sid'],
			'po' => $arrParam['po'],
			'searchparam'=>$strParam,
			'pageno'	=> $pageno
			);

			if (isset($arrParam['po']) && $arrParam['po'] != '') {
				$sql_where.= ' and position="'.$search_position[$arrParam['po']].'" ';
			}
			if (isset($arrParam['sid']) && $arrParam['sid'] != '') {
				$sql_where.= ' and state_id='.$arrParam['sid'];
			}
		}

		$sql = "select count(*) from ".$this->table."banner_soc,".$this->table."state $sql_where " ;
		$this->dbcon->execute_query($sql) ;
		$current_count = $this->dbcon->fetch_records();
		$current_count = $current_count[0][0];
		
		($pageno * $perPage > $current_count) ? $pageno = ceil($current_count/$perPage) : '';
		$start	= ($pageno-1) * $perPage;
		
		$sql = "select ".$this->table."banner_soc.*,".$this->table."state.stateName,".$this->table."state.description as fullStateName from  ".$this->table."banner_soc,".$this->table."state $sql_where limit $start,$perPage" ;
		$this->dbcon->execute_query($sql) ;
		$arrTemp = $this->dbcon->fetch_records(true);

		if (is_array($arrTemp)) {
			$arrResult['list']	=	$arrTemp;
			$i = 0;
			foreach ($arrTemp as $temp){
				$arrResult['list'][$i]['banner_img'] = $this->__getImageSize($temp['banner_img'],$temp['position']) ;
				$i ++;
			}

			//pager
			$objParams = new pagerClass();
			$arrResult['pager']	=	$objParams -> getLinkAjax($pageno,$current_count,$perPage,'xajax_getBannerStateList(\'%d\',xajax.getFormValues(\'mainForm\'));');
			unset($objParams);
		}

		return $arrResult;
	}

	public function addBannerState($objForm){
		$arrResult	=	null;
		$search_position = array('left1','left2','left3','right1','right2','right3');

		if (is_array($objForm) && !empty($objForm['searchparam'])) {
			$arrParam = unserialize($objForm['searchparam']);

			$arrResult['sid']	=	$arrParam['sid'] ? $arrParam['sid'] : 1 ;
			$arrResult['po'] = $search_position[$arrParam['po']?$arrParam['po']:0];
			$arrResult['position'] = $arrResult['po'];
		}else{
			$arrResult['sid']	= 1 ;
			$arrResult['po'] 	= $search_position[0];
			$arrResult['position'] = $arrResult['po'];
		}
		$arrResult['stateList'] = $this -> __getStateList();

		return $arrResult;
	}

	public function editBannerState($id,$objForm){
		$arrResult	=	null;
		$position = array('left1'=>'Down left 1','left2'=>'Down left 2','left3'=>'Down left 3','right1'=>'Right 1','right2'=>'Right 2','right3'=>'Right 3');

		if (empty($id) or !is_numeric($id)){
			$strResult = "Invalid Banner ID.";
		}else {
			$sql = "select t1.*,t2.* from ".$this->table."banner_soc as t1 left join ".$this->table."state as t2 on t1.state_id=t2.id  where banner_id=$id" ;
			$this->dbcon->execute_query($sql) ;
			$grid = $this->dbcon->fetch_records() ;
			$arrResult = $grid[0];
			$arrResult['poname']= $position[$arrResult['position']];
			$arrResult['sid']	=	$arrParam['state_id'];
		}

		return $arrResult;
	}

	public function saveBannerState($arrParams){
		$strResult	=	'';
		//$_FILES		= & $GLOBALS['_FILES'];
		$img ='left1';
		$destination = UPLOADDIR ;
		$base_dir = getcwd();
		$destination =  $base_dir.'/../upload/new/';
		$strResult = ($arrParams['searchparam']);
		if (is_array($arrParams)){
			$banner_id  = $arrParams['banner_id'];
			$state_id 	= $arrParams['stateId'];
			$position 	= $arrParams['bannerPosition'];
			$link 		= $arrParams['bannerLink'];
			$desc 		= $arrParams['message'];
			$displaypage= $arrParams['displaypage'];

			// check file type
			$ex = explode('.', $_FILES['bannerFile']['name']);
			$ex = strtolower(array_pop($ex));

			if ($ex != 'jpg' && $ex !='gif' && $ex != 'png' || ($position=='right1' && $ex=='swf')){
				$strResult = "Banner file type is invalid.";
			}elseif ($_FILES['bannerFile']['size'] > 0){
				$banner_name = time();
				while(file_exists($destination.$banner_name)){
					$banner_name = time();
				}
				$banner_name .= ".$ex";
				if (!move_uploaded_file($_FILES['bannerFile']['tmp_name'],$destination . $banner_name)){
					$strResult = "Banner uploading failed.";

				}elseif (!is_numeric($banner_id)){
					$sql = "select count(*) from ".$this->table."banner_soc where position='$position' and state_id = $state_id and displaypage='$displaypage'";
					$this->dbcon->execute_query($sql);
					$count = $this->dbcon->fetch_records();
					$count = $count[0][0];

					$arr = array('state_id'=>$state_id,'position'=>$position,'banner_img'=>$banner_name,'banner_link'=>$link,'description'=>$desc,'displaypage'=>$displaypage);
					if (($count == 0) or ($count < 3 and $position == 'right1')){
						if($this->dbcon->insert_query($this->table."banner_soc",$arr)) {
							$strResult =  "Record Inserted Successfully" ;
						}
					}else{
						$strResult = "Banner exists, please select one to edit.";
					}
				}else{
					$arr = array('banner_link'=>$link,'description'=>$desc);
					if ($_FILES['bannerFile']['size'] > 0){
						$arr['banner_img']=$banner_name;
					}
					if($this->dbcon->update_query($this->table."banner_soc",$arr,"where banner_id = $banner_id")) {
						$strResult =  "Record Updated Successfully" ;
					}
				}
			}
		}

		return $strResult;
	}

	public function deleteBannerState($id){
		$strResult	=	'';
		$destination = UPLOADDIR ;
		$base_dir = getcwd();
		$destination =  $base_dir.'/../upload/new/';
		if ($id){
			$sql = "select * from ".$this->table."banner_soc where banner_id=$id";
			$this->dbcon->execute_query($sql);
			$grid = $this->dbcon->fetch_records();
			if ($grid){
				@!unlink($destination.$grid[0]['banner_img']);
				
				if($this->dbcon->execute_query("delete from ".$this->table."banner_soc where banner_id=$id")) {
					$strResult =  "Banner deleted successfully" ;
				}else{
					$strResult = "Failed to delete Banner.";
				}
			}else{
				$strResult = "Invalid banner ID.".$sql;
			}
		}else{
			$strResult = "Invalid banner ID.";
		}

		return $strResult;
	}
}

/*********************
* xajax function
**********************/

/****************************
* all and default banner xajax function
*****************************/

function getBannerAllAndDefaultList($objForm,$page=1){
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];
	$req['list']	= $objAdminAdv -> getBannerAllAndDefaultList($objForm['searchparam'],$page);
	$req['nofull'] = true ;
	$objAdminAdv -> smarty -> assign('req',	$req);
	$content = $objAdminAdv -> smarty -> fetch('admin_banner_all.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
        $objResponse->script('replaceFlash();');
	return $objResponse;
}

function getBannerAllAndDefaultListSearch($sid,$po,$page=1){
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];

	$arrParam		= serialize (array('sid'=>"$sid",'po'=>"$po"));
	$req['list']	= $objAdminAdv -> getBannerAllAndDefaultList($arrParam,$page);
	$req['nofull'] = true ;
	$objAdminAdv -> smarty -> assign('req',	$req);
	$content = $objAdminAdv -> smarty -> fetch('admin_banner_all.tpl');
	$objResponse -> assign("searchparam",'value',$arrParam);
	$objResponse -> assign("tabledatalist",'innerHTML',$content);

	return $objResponse;
}

function getBannerAllAndDefaultListSearch2($objForm,$page=1){
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];
	$start_date = 0;
	$end_date = 0;
	
	if(DATAFORMAT_DB=="%m/%d/%Y"){
		if($objForm['s_start_date']!=""){
			list($month,$day,$year) = split('/',$objForm['s_start_date']);
			$start_date = mktime(0,0,0,$month,$day,$year);
		}
		if($objForm['s_end_date']!=""){
			list($month,$day,$year) = split('/',$objForm['s_end_date']);
			$end_date 	= mktime(0,0,0,$month,$day,$year);
		}
	}else{
		if($objForm['s_start_date']!=""){
			list($day,$month,$year) = split('/',$objForm['s_start_date']);
			$start_date = mktime(0,0,0,$month,$day,$year);
		}
		if($objForm['s_end_date']!=""){
			list($day,$month,$year) = split('/',$objForm['s_end_date']);
			$end_date 	= mktime(0,0,0,$month,$day,$year);
		}
	}
	
	$arrParam		= serialize (array('sid'=>"{$objForm['sid']}",'start_date'=>"{$start_date}",'end_date'=>"{$end_date}",'state_id'=>"{$objForm['state_id']}", 'search_markets'=>$objForm['search_markets']));
	$req['list']	= $objAdminAdv -> getBannerAllAndDefaultList($arrParam,$page);
	$req['nofull'] = true ;
	$objAdminAdv -> smarty -> assign('req',	$req);
	$content = $objAdminAdv -> smarty -> fetch('admin_banner_all.tpl');
	
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
        $objResponse->script('replaceFlash();');
	return $objResponse;
}


function addBannerAllAndDefault($objForm){
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];
	$req['list'] = $objAdminAdv -> addBannerAllAndDefault($objForm);
	$req['display'] = 'edit' ;
	$objAdminAdv -> smarty -> assign('req',	$req);
	$objAdminAdv -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$content = $objAdminAdv -> smarty -> fetch('admin_banner_all.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);

	return $objResponse;
}

function editBannerAllAndDefault($id, $objForm){
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];

	$req['list'] = $objAdminAdv -> editBannerAllAndDefault($id, $objForm);
	$req['display'] = 'edit' ;
	$objAdminAdv -> smarty -> assign('req',	$req);
	$objAdminAdv -> smarty -> assign('PBDateFormat',DATAFORMAT_DB);
	$content = $objAdminAdv -> smarty -> fetch('admin_banner_all.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);

	return $objResponse;
}

function saveBannerAllAndDefault($objForm){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];
	$messages = $objAdminAdv -> saveBannerAllAndDefault($objForm);
	$objResponse -> script("javascript: xajax_getBannerAllAndDefaultList(xajax.getFormValues('mainForm'));");
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	$objResponse -> alert($messages);

	return $objResponse;
}

function deleteBannerAllAndDefault($id){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];
	$messages = $objAdminAdv -> deleteBannerAllAndDefault($id);
	$objResponse -> script("javascript: xajax_getBannerAllAndDefaultList(xajax.getFormValues('mainForm'));");
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	$objResponse -> alert($messages);

	return $objResponse;
}

/****************************
* state banner xajax function
*****************************/

function getBannerStateList($pageno,$objForm){
	$objResponse 	= new xajaxResponse();

	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];
	$req['list']	= $objAdminAdv -> getBannerStateList($pageno,$objForm['searchparam']);
	$req['nofull'] = true ;
	$objAdminAdv -> smarty -> assign('req',	$req);
	$content = $objAdminAdv -> smarty -> fetch('admin_banner_state.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',$pageno);
	return $objResponse;
}

function getBannerStateSearch($sid,$po){
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];
	$arrParam		= serialize (array('sid'=>"$sid",'po'=>"$po"));

	$req['list']	= $objAdminAdv -> getBannerStateList(1,$arrParam);
	$req['nofull'] = true ;
	$objAdminAdv -> smarty -> assign('req',	$req);
	$content = $objAdminAdv -> smarty -> fetch('admin_banner_state.tpl');
	$objResponse -> assign("searchparam",'value',$arrParam);
	$objResponse -> assign("tabledatalist",'innerHTML',$content);
	$objResponse -> assign("pageno",'value',1);

	return $objResponse;
}

function addBannerState($objForm){
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];
	$req['list'] = $objAdminAdv -> addBannerState($objForm);
	$req['display'] = 'edit' ;

	$objAdminAdv -> smarty -> assign('req',	$req);
	$content = $objAdminAdv -> smarty -> fetch('admin_banner_state.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);

	return $objResponse;
}

function editBannerState($id,$objForm){
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];

	$req['list'] = $objAdminAdv -> editBannerState($id, $objForm);
	$req['display'] = 'edit' ;
	$objAdminAdv -> smarty -> assign('req',	$req);
	$content = $objAdminAdv -> smarty -> fetch('admin_banner_state.tpl');
	$objResponse -> assign("tabledatalist",'innerHTML',$content);

	return $objResponse;
}

function saveBannerState($objForm){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];
	$messages = $objAdminAdv -> saveBannerState($objForm);
	$objResponse -> script("javascript: xajax_getBannerStateList(xajax.$(pageno).value,xajax.getFormValues('mainForm'));");
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	$objResponse -> alert($messages);

	return $objResponse;
}

function deleteBannerState($id){
	$messages		= '';
	$objResponse 	= new xajaxResponse();
	$objAdminAdv 	= &$GLOBALS['objAdminAdv'];
	$messages = $objAdminAdv -> deleteBannerState($id);
	$objResponse -> script("javascript: xajax_getBannerStateList(xajax.$('pageno').value,xajax.getFormValues('mainForm'));");
	$objResponse -> assign('ajaxmessage','innerHTML',$messages);
	$objResponse -> alert($messages);

	return $objResponse;
}

?>