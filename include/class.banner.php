<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * All:0
 * Default:-1
 * State:1
 */


class  Banner
{
    private $_dbcon = null;
    private $_table = '';

    public function __construct()
    {
        $this ->_dbcon = &$GLOBALS['dbcon'];
        $this ->_table = &$GLOBALS['table'];
    }

    /**
     * @desc    state page random banner
     */
    public function StatePageRandomBanner($stateName = 'NSW', $markeyType='State', $bannerNumber=20)
    {
        $result = array();      //all result

        $stateInfo = getStateInfoByName($stateName);
        $stateID = $stateInfo['id'];

        $nowTimeStamp = time();
        $nowTimeStamp = mktime(0,0,0,date('m', $nowTimeStamp),date('d', $nowTimeStamp),date('Y', $nowTimeStamp));
        $sqlDate = ' `start_date` <= ' . $nowTimeStamp . ' AND (`end_date` >= ' . $nowTimeStamp .' OR `end_date` = 0 ) AND `markets` = "'. $markeyType . '" ORDER BY RAND() ';
        
        //all state
        $strSql = 'SELECT * FROM `' . $this->_table . 'banner_soc` WHERE `state_id` = "0" AND ' . $sqlDate . ' LIMIT ' . intval($bannerNumber);
        $this->_dbcon->execute_query($strSql);
        $allState = $this->_dbcon->fetch_records(true);
        $allState = is_array($allState) ? $allState : array();

        if(count($allState) >= $bannerNumber) {
            shuffle($allState);
            return $this->getBannerType($allState);
        }

        //state
        $strSql = 'SELECT * FROM `' . $this->_table . 'banner_soc` WHERE `state_id` LIKE "%,' . intval($stateID) . ',%" AND ' . $sqlDate . ' LIMIT ' . ($bannerNumber - count($allState));
        $this->_dbcon->execute_query($strSql);
        $state = $this->_dbcon->fetch_records(true);
        $state = is_array($state) ? $state : array();

        $result = array_merge($allState, $state);
        if(count($result) >= $bannerNumber) {
            shuffle($result);
            return $this->getBannerType($result);
        }

        //default
        $strSql = 'SELECT * FROM `' . $this->_table . 'banner_soc` WHERE `state_id` = "-1" AND ' . $sqlDate . ' LIMIT ' . ($bannerNumber - count($result));
        $this->_dbcon->execute_query($strSql);
        $default = $this->_dbcon->fetch_records(true);
        $default = is_array($default) ? $default : array();

        $result = array_merge($result, $default);
        shuffle($result);
        return $this->getBannerType($result);
        
    }



    public function addStatePageRandomBannerViews($id = array())
    {
        $strSql = 'UPDATE `' . $this->_table . 'banner_soc` SET `view_times` = `view_times` + 1 WHERE `banner_id` IN ( ' . implode(',' , $id) . ' ) ' ;
        $this->_dbcon->execute_query($strSql);
        return true;
    }


    private function getBannerType($banners=array())
    {
        foreach($banners as $k=>$banner) {
            if(preg_match('/swf$/i', $banner['banner_img'])) {
                $banners[$k]['file_type'] = 'flash';
            }
            else {
                $banners[$k]['file_type'] = 'image';
            }
        }
        return $banners;
    }
}
?>
