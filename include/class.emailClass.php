<?php

/**
 * Tue Nov 18 06:30:33 GMT 2008 06:30:33
 * @author  : Ping.Hu <support@infinitytesting.com.au>
 * @version : V2.0
 * ------------------------------------------------------------
 * mail class of system at smarty templates.
 * ------------------------------------------------------------
 * include\class.mailClass.php
 */
class emailClass {

    private $smarty = null;
    private $parts;
    public $msg = '';

    public function __construct() {
        $this->smarty = &$GLOBALS['smarty'];
        $this->parts = array();
    }

    public function __destruct() {
        unset($this->smarty, $this->msg, $this->parts);
    }

    /**
     * send mail
     *
     * @param array $arrParams
     * $arrParams = array('To'=>'','Subject'=>''[,'From'='','BCC'=>'',.... value])
     * @param string $strTempaleFile
     * @return boolean
     */
    public function send($arrParams, $strTempaleFile, $isDebug=false, $isBCC=true) {
        $boobleanResult = false;
        $message = '';
        if (empty($arrParams['To'])) {
            //$arrParams['To']	=	$isDebug ? 'xiong.wu@infinitytesting.com.au' : 'info@TheSOCExchange.com';
            $arrParams['To'] = 'dafiny.infinitytest@gmail.com';
        }
        if (empty($arrParams['BCC']) && $isBCC) {
            //$arrParams['BCC']    =   'kevin.liu@infinitytesting.com.au';
//                    $arrParams['BCC'] = 'albert.osmena@infinitytechnologies.com.hk';
        }

        if ($this->_checkToEmail($arrParams['To'])) {

            if ($this->_checkTemplate($strTempaleFile)) {

                $headers = $this->_headers($arrParams);

                $this->smarty->assign('req', $arrParams);
                $message = $this->smarty->fetch($strTempaleFile);
                // if (!empty($message)) {
                    // $this->_add_attachment((isset($arrParams['_no_tpl_']) and true == $arrParams['_no_tpl_']) ? $message : getEmailTemplate($message, $arrParams), "", "text/html; charset=" . PB_CHARSET, '7bit');
                // }

                if ($arrParams['attachment']) {
                    $attaContent = $this->_getFileContent($arrParams['attachment']);
                    $this->_add_attachment($attaContent, $arrParams['attachmentName'], $arrParams['attachmentType'], 'base64');
					$headers .= $this->_build_multipart();
                }

                
                $boobleanResult = @mail($arrParams['To'], $arrParams['Subject'], $message, FixEOL($headers));
                if ($boobleanResult) {
                    $this->msg = 'Email sent successfully.';
                } else {
                    $this->msg = 'Failed to send email.';
                }
                unset($message);
            }
        }

        return $boobleanResult;
    }

    private function _getFileContent($strFilePath) {
        $strResult = '';

        if ($strFilePath) {
            $strFileNamePath = ROOT_PATH . substr($strFilePath, 1);
            $handle = fopen($strFileNamePath, "r");
            $strResult = fread($handle, filesize($strFileNamePath));
            fclose($handle);
        }

        return $strResult;
    }

    /**
     * check email
     *
     * @param string $strToEmail
     * @return boolean
     */
    private function _checkToEmail($strToEmail) {
        $booleanResult = false;

        if (empty($strToEmail)) {
            $this->msg = 'Failed, Email address is required.';
        } elseif (!eregi("^([_a-zA-Z0-9]+([\._a-zA-Z0-9-]+)*)@([a-zA-Z0-9]{1,}(\.[a-zA-Z0-9-]{1,})*)$", $strToEmail)) {
            $this->msg = 'Failed, The target email address is not available.';
        } else {
            $booleanResult = true;
        }

        return $booleanResult;
    }

    /**
     * check template
     *
     * @param stirng $strTemplateFile
     * @return boolean
     */
    private function _checkTemplate($strTempaleFile) {
        $booleanResult = false;

        if (empty($strTempaleFile)) {
            $this->msg = "Failed, The template doesn't exist.";
        } else {
            $booleanResult = true;
        }

        return $booleanResult;
    }

    /**
     * create head of email
     *
     * @param string $FromEmail
     * @return string
     */
    private function _headers($arrParams) {
        $strResult = '';

        $FromEmail = empty($arrParams['From']) ? 'noreply@'.EMAIL_DOMAIN : $arrParams['From'];
        $Fromname = empty($arrParams['fromname']) ? '' : $arrParams['fromname'];

        $strResult = "Date: " . date("r") . "\r\n";
        $strResult .= "From: $Fromname <" . $FromEmail . ">\r\n";
        $strResult .= "Reply-To: <" . $FromEmail . ">\r\n";
        $strResult .= "Errors-To: <" . $FromEmail . ">\r\n";
        $strResult .= "Return-path: <" . $FromEmail . ">\r\n";
        if (!empty($arrParams['BCC'])) {
            $strResult .= "Bcc: <" . $arrParams['BCC'] . ">\r\n";
        }
        if (!empty($arrParams['Cc'])) {
            $strResult .= "Cc: " . $arrParams['Cc'] . "\r\n";
        }
        $strResult .= "MIME-Version: 1.0\r\n";
		if (empty($arrParams['attachment'])) {
			$strResult .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		}
        //$strResult .= "X-Mailer: SOC Networks HTML-Mailer v1.0\r\n";
        //$strResult .= "X-Sender: " . $FromEmail . "\n";


        return $strResult;
    }

    /**
     * Add an attachment to the mail object
     *
     * @param string $message
     * @param string $name
     * @param string $ctype
     * @param string $encode
     * @return void
     */
    function _add_attachment($message, $name = "", $ctype = "application/octet-stream", $encode='base64') {
        $this->parts[] = array(
            "ctype" => $ctype,
            "message" => $message,
            "encode" => $encode,
            "name" => $name
        );
    }

    /**
     * Build message parts of an multipart mail
     *
     * @param array $part
     * @param int $attaNum
     * @return string
     */
    function _build_message($part, &$attaNum) {
        $strResult = '';
        $message = $part["message"];
        if ($part['encode'] == 'base64') {
            $message = chunk_split(base64_encode($message));
            $encoding = "base64";
            $attachmentSet = "X-Attachment-Id: f_fpzemzna$attaNum\r\n";
            $attachmentSet .= "Content-Disposition: attachment; filename=\"" . ($part["name"]) . "\"\r\n";
            $attaNum++;
        } else {
            $attachmentSet = "";
        }

        $strResult = "Content-Type: " . $part["ctype"] . ($part["name"] ? "; name = \"" . $part["name"] . "\"" : "") .
                "\r\nContent-Transfer-Encoding: $part[encode]\r\n$attachmentSet\r\n$message\r\n";

        return $strResult;
    }

    /**
     * void build_multipart()
     * Build a multipart mail
     *
     * @return void
     */
    function _build_multipart() {
        $boundary = "b" . md5(uniqid(time()));
        $multipart = "Content-type: multipart/mixed; boundary=\"$boundary\"\n";

        if (sizeof($this->parts)) {
            $multipart .= "--$boundary";
            $attaNum = 0;
            //for($i = sizeof($this->parts)-1; $i >= 0; $i--){
            for ($i = 0; $i < sizeof($this->parts); $i++) {
                $multipart .= "\n" . $this->_build_message($this->parts[$i], $attaNum) . "--$boundary";
            }
        }
        return $multipart.= "--\n\n";
    }

}

?>