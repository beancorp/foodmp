<?php
/**
 * Description of class
 *
 *  This is Input Filter Class
 * @author YangBall
 */
class Input {

    /**
     *  @Author :   YangBall
     *  @Date   :   2010-08-25
     *  @Input  :   get(String) : User GET Data
     *  @Desc   :   Filter User GET Input Data
     */
    static public function Get($get) {
        return is_string($get) ? ($_GET["$get"] ? self :: _Filter($_GET["$get"]) : '') : $get;
    }

    /**
     *  @Author :   YangBall
     *  @Date   :   2010-08-25
     *  @Input  :   post(String) : User POST Data
     *  @Desc   :   Filter User POST Input Data
     */
    static public function Post($post) {
        return is_string($post) ? ($_POST["$post"] ? self :: _Filter($_POST["$post"]) : '') : $post;
    }

    /**
     *  @Author :   YangBall
     *  @Date   :   2010-08-25
     *  @Input  :   str(String) : User Input String
     *  @Desc   :   String
     */
     static public function String($str) {
         return is_string($str) ? self :: _Filter($str) : '';
     }


    /**
     *  @Author :   YangBall
     *  @Date   :   2010-08-25
     *  @Input  :   input(ANY) : User Input Data
     *  @Desc   :   Filter User Input , Include Array, Object, String
     */
    static public function Fliter($input) {
        if(is_string($input)) {
            return self :: _Filter($input);
        }
        elseif(is_array($input)) {
            foreach($input as $key=>$val) {
                $input[$key]=self :: Fliter($val);
            }
            return $input;
        }
        elseif(is_object($input)) {
            $value=get_object_vars($input);
            foreach($value as $key=>$val) {
                $input->$key=self :: Fliter($val);
            }
            return $input;
        }
        else {
            return $input;
        }
    }

    /**
     * @access public
     * @desc strip quotes of addslashes
     * @param <string> $string
     * @return <string>
     */
    public static function StripString($string)
    {
        return self::_Strip($string);
    }


    /**
     * @access private
     * @param <string> $string
     * @return <string>
     */
    private static function _Strip($string)
    {
        if(!get_magic_quotes_gpc()){
            return $string;
        }
        else {
            return stripslashes($string);
        }
    }

    /**
     *  @Author :   YangBall
     *  @Date   :   2010-08-25
     *  @Input  :   str(String) : Filter String
     *  @Return :   Process String
     *  @Desc   :   Private Function
     */
    private function _Filter($str) {
        if(is_string($str)) {
            if(!get_magic_quotes_gpc ()) {
                $str=addslashes($str);
            }
        }
        return $str;
    }

}