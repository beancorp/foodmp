<?php
/**
    * 错误处理
    * @param     Int         $aErrorNo      出错类型
    * @param     String      $aErrorStr     错误提示
    * @return    Void
    */
function myErrorHandler($aErrorNo,$aErrorStr,$aFile,$aLine,$aContext)
{
	switch ($aErrorNo)
	{
		case E_ERROR:
			$aErrorType =   "E_ERROR";
			break;
		case E_WARNING:
			$aErrorType =   "E_WARNING";
			break;
		case E_PARSE:
			$aErrorType =   "E_PARSE";
			break;
		case E_NOTICE:
			$aErrorType =   "E_NOTICE";
			break;
		case E_CORE_ERROR:
			$aErrorType =   "E_CORE_ERROR";
			break;
		case E_CORE_WARNING:
			$aErrorType =   "E_CODE_WARNING";
			break;
		case E_COMPILE_ERROR:
			$aErrorType =   "E_COMPILE_ERROR";
			break;
		case E_COMPILE_WARNING:
			$aErrorType =   "E_COMPILE_WARNING";
			break;
		case E_USER_ERROR:
			$aErrorType =   "E_USER_ERROR";
			break;
		case E_USER_WARNING:
			$aErrorType =   "E_USER_WARNING";
			break;
		case E_USER_NOTICE:
			$aErrorType =   "E_USER_NOTICE";
			break;
		default:
			$aErrorType =   "UNKNOWN ERROR TYPE";
			break;
	}
	global $errorTotalNum;
	
	if(ERROR_DISPOSE == "DIS")
	{
		if (ERROR_TYPE == "") {
			$errorTotalNum ++;
			print(
			"<table boder=\"1\" width=\"90%\" align=center><tr><td>
            第 $errorTotalNum 处 <b>$aErrorType</b>: <font color=red> $aErrorStr </font></br>
            在文件 <font color=green>$aFile</font> 的第 <b>$aLine</b> 行<br>
            </td></tr></table>"
            );
		}
		elseif (ERROR_TYPE == $aErrorNo)
		{
			$errorTotalNum ++;
			print(
			"<table boder=\"1\" width=\"90%\" align=center><tr><td>
            第 $errorTotalNum 处 <b>$aErrorType</b>: <font color=red> $aErrorStr </font></br>
            在文件 <font color=green>$aFile</font> 的第 <b>$aLine</b> 行<br>
            </td></tr></table>"
            );
		}
	}
	elseif(ERROR_DISPOSE == "LOG")
	{
		$errorTotalNum ++;
		$aDestinationStr ="第 $errorTotalNum 处 $aErrorType: $aErrorStr \n 在文件 $aFile 的第 $aLine 行 \n";
		error_log($aErrorStr,$aErrorNo,$aDestinationStr);
	}
}

//    function error_reporting($errType)
//    {
//    	foreach ($GB_ERRARR as $temp)
//    	{
//
//    	}
//    }
?>
