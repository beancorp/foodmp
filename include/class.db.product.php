<?php
include_once('class.db.dbbase.php');
class Product extends dbbase
{
    /**
     *  Check Product Is Delete
     *
     *  @author YangBall
     *  @param  Int     $pid    Product Id
     *  @return Bool 
     */
    function CheckProduct($StoreID,$pid)
    {
        $pid=intval($pid);
        if($pid<=0) {
            return false;
        }
        $intResult	=	false;

        $_query	=	"select attribute from ".$this->table."bu_detail where StoreID='$StoreID'";
        $this->dbcon->execute_query($_query);
        $arrTemp = $this->dbcon->fetch_records(true);
        if (is_array($arrTemp)) {

                $_where	=	"where StoreID='$StoreID' and pid='".$pid."'";
                if ($arrTemp[0]['attribute']==1) {
                        $_query = "select pid from ".$this->table."product_realestate $_where and deleted = 0 ";
                }elseif ($arrTemp[0]['attribute']==2) {
                        $_query = "select pid from ".$this->table."product_automotive $_where and deleted = 0 ";
                }elseif ($arrTemp[0]['attribute']==3) {
                        $current_date = date("Y-m-d");
                        $_query = "select pid from ".$this->table."product_job $_where and deleted = 0 and (closingDate>='$current_date' or closingDate='0000-00-00')";
                }else{
                        $_query = "select pid from ".$this->table."product $_where and deleted!='YES'";
                }
                //exit($_query);
                $this->dbcon->execute_query($_query);
                $count = $this->dbcon->count_records();
                if ($count > 0){
                        $row = $this->dbcon->fetch_records();
                        $intResult = $row[0]['pid'];
                }
        }

        return $intResult;
    }
}
?>
