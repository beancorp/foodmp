<?php
/**
 * Mon Nov 24 01:33:33 GMT 2008 01:33:33
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V1.0
 * ------------------------------------------------------------
 * extend xajax
 * ------------------------------------------------------------
 * include\class\ajax.php
 */


include_once ('xajax/xajax_core/xajax.inc.php');

class ajax extends xajax {

	/**
	 * regedit function extend
	 *
	 * @param string $strFunctionName
	 * @param array $params    array(functionname => array(strVarName,intVarType))
	 * @param boolean  $isFlag    add 'javascript' at head
	 * @return string  javascript string
	 */
	public function regFun($strFunctionName, $params=null, $isFlag=true,$debug=false){
		$strResult	=	'';
		$arrType = array(XAJAX_FORM_VALUES, XAJAX_INPUT_VALUE, XAJAX_CHECKED_VALUE, XAJAX_ELEMENT_INNERHTML, XAJAX_QUOTED_VALUE, XAJAX_JS_VALUE);
		xajax::configureMany(array('debug'=>$debug));
		$strRecipients		=	$this->registerFunction("$strFunctionName");
		if (is_array($params)) {
			foreach ($params as $key => $arrNameAndType){
				$strRecipients	-> setParameter($key, $arrType[$arrNameAndType[1]], "$arrNameAndType[0]");
			}
		}
		$strResult	=	$isFlag ? "javascript:" . str_replace("\"","'",$strRecipients -> getScript()) .";" : str_replace("\"","'",$strRecipients -> getScript() ) .";" ;

		return $strResult;
	}

	/**
	 * get javascript of init
	 *
	 * @param stirng $strInitPath   xajax path
	 * @return stirng  javascript string
	 */
	public function getJSInit($strInitPath='/include/xajax', $sJsFile=NULL)
	{
		return $this -> getJavascript($strInitPath, $sJsFile);
	}
}

?>