<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classdbdbbase
 *
 * @author Yangball
 */
class dbbase extends common {
    //put your code here
    var $dbcon 	= 	null;
    var $table	=	'';
    var $smarty = 	null;
    var $lang	=	null;

    /**
     * @return void
     */
    public function __construct(){
            $this -> dbcon  = &$GLOBALS['dbcon'];
            $this -> table	= &$GLOBALS['table'];
            $this -> smarty = &$GLOBALS['smarty'];
            $this -> lang	= &$GLOBALS['_LANG'];
    }
}
?>
