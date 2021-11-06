<?php

/**
 * @author Jasman <jasman@ihsana.com>
 * @copyright Ihsana IT Solution 2011
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @package String Class
 * 
 * 
 * $String = new String;
 * echo $String->Filter($content, "number");
 * 
 * echo $string->Convert($string, $parameter, $WhiteList);
 * 
 * Parameter:
 * - alphabet
 * - username
 * - email
 * - number
 * - url
 * - paragraph
 * 
 * WhiteList:
 * - null;
 * 
 * Return:
 * - false
 * - string
 * 
 */
defined('JSM_EXEC') or die('Not Here');
class jsmString
{
    protected $Alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    protected $Number = '0123456789';
    protected $Username = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-.';
    protected $Filesystem = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-[]./\\';
    protected $Email = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-.@';
    protected $Url = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-[]./\\:';
    protected $Paragraph = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789|+_) (*&^%$#@!_+|?><,./;}{][';
    public function Convert($string, $type = "alphabet", $WhiteList = null)
    {
        $this->Type = strtolower(trim($type));
        switch ($this->Type)
        {
            case 'email':
                $this->String = strtolower($string);
                break;
            case 'username':
                $this->String = strtolower($string);
                break;
            case 'id':
                $this->String = str_replace(' ', '_', strtolower($string));
                break;
            case 'number':
                $this->String = $string;
                break;
            case 'paragraph':
                $this->String = $string;
                break;
            case 'alphabet':
                $this->String = $string;
                break;
            case 'url':
                $this->String = str_replace(' ', '_', strtolower($string));
                break;
        }
        $this->WhiteList = $WhiteList;
        return $this->FilterString();
    }
    public function Filter($string, $type = "email")
    {
        $this->Type = strtolower(trim($type));
        $is_valid = false;
        switch ($this->Type)
        {
            case "ip":
                $is_valid = $this->FilterIp($string);
                break;
            case "email":
                $is_valid = $this->FilterEmail($string);
                break;
            case "number":
                $is_valid = $this->FilterNumber($string);
                break;
            case "url":
                $is_valid = $this->FilterUrl($string);
                break;
        }
        return $is_valid;
    }
    protected function FilterUrl($str)
    {
        $str = filter_var($str, FILTER_VALIDATE_URL);
        return $str;
    }
    protected function FilterEmail($str)
    {
        $str = filter_var($str, FILTER_VALIDATE_EMAIL);
        return $str;
    }
    protected function FilterIp($str)
    {
        $str = filter_var($str, FILTER_VALIDATE_IP);
        return $str;
    }
    protected function FilterNumber($str)
    {
        if (is_numeric($str))
        {
            return $str;
        } else
        {
            return false;
        }
    }
    protected function Character()
    {
        $Charset = null;
        switch ($this->Type)
        {
            case "alphabet":
                $Charset = $this->Alphabet;
                break;
            case "id":
                $Charset = strtolower($this->Username);
                break;
            case "username":
                $Charset = strtolower($this->Username);
                break;
            case "email":
                $Charset = strtolower($this->Email);
                break;
            case "number":
                $Charset = $this->Number;
                break;
            case "url":
                $Charset = $this->Url;
                break;
            case "paragraph":
                $Charset = $this->Paragraph;
                break;
        }
        if ($this->WhiteList != null)
        {
            $Charset .= $this->WhiteList;
        }
        return $Charset;
    }
    protected function FilterString()
    {
        $Allow = null;
        $char = $this->Character();
        $string = $this->String;
        for ($i = 0; $i < strlen($string); $i++)
        {
            $valid = false;
            for ($x = 0; $x < strlen($char); $x++)
            {
                if (ord($string[$i]) == ord($char[$x]))
                {
                    $valid = true;
                }
            }
            if ($valid == true)
            {
                $Allow .= $string[$i];
            }
        }
        return $Allow;
    }
    function Check($string, $type = "username", $WhiteList = null)
    {
        $text = $this->Convert($string, $type, $WhiteList);
        //echo md5($string) .":".md5($text);
        if (md5($string) == md5($text))
        {
            return false;
        } else
        {
            return true;
        }
    }

    function foreignChar($str)
    {
        $foreignChar[] = "&ccedil;";
        $foreignChar[] = "&euml;";
        $foreignChar[] = "&#263;";
        $foreignChar[] = "&#269;";
        $foreignChar[] = "&#273;";
        $foreignChar[] = "&#353;";
        $foreignChar[] = "&#382;";
        $foreignChar[] = "&agrave;";
        $foreignChar[] = "&ccedil;";
        $foreignChar[] = "&egrave;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&iacute;";
        $foreignChar[] = "&iuml;";
        $foreignChar[] = "&ograve;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&uacute;";
        $foreignChar[] = "&uuml;";
        $foreignChar[] = "&#263;";
        $foreignChar[] = "&#269;";
        $foreignChar[] = "&#273;";
        $foreignChar[] = "&#353;";
        $foreignChar[] = "&#382;";
        $foreignChar[] = "&aacute;";
        $foreignChar[] = "&#269;";
        $foreignChar[] = "&#271;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&#283;";
        $foreignChar[] = "&iacute;";
        $foreignChar[] = "&#328;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&#345;";
        $foreignChar[] = "&#353;";
        $foreignChar[] = "&#357;";
        $foreignChar[] = "&uacute;";
        $foreignChar[] = "&#367;";
        $foreignChar[] = "&yacute;";
        $foreignChar[] = "&#382;";
        $foreignChar[] = "&aelig;";
        $foreignChar[] = "&oslash;";
        $foreignChar[] = "&aring;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&euml;";
        $foreignChar[] = "&iuml;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&#265;";
        $foreignChar[] = "&#285;";
        $foreignChar[] = "&#293;";
        $foreignChar[] = "&#309;";
        $foreignChar[] = "&#349;";
        $foreignChar[] = "&#365;";
        $foreignChar[] = "&auml;";
        $foreignChar[] = "&ouml;";
        $foreignChar[] = "&otilde;";
        $foreignChar[] = "&uuml;";
        $foreignChar[] = "&aacute;";
        $foreignChar[] = "&eth;";
        $foreignChar[] = "&iacute;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&uacute;";
        $foreignChar[] = "&yacute;";
        $foreignChar[] = "&aelig;";
        $foreignChar[] = "&oslash;";
        $foreignChar[] = "&auml;";
        $foreignChar[] = "&ouml;";
        $foreignChar[] = "&agrave;";
        $foreignChar[] = "&acirc;";
        $foreignChar[] = "&ccedil;";
        $foreignChar[] = "&egrave;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&ecirc;";
        $foreignChar[] = "&euml;";
        $foreignChar[] = "&icirc;";
        $foreignChar[] = "&iuml;";
        $foreignChar[] = "&ocirc;";
        $foreignChar[] = "&oelig;";
        $foreignChar[] = "&ugrave;";
        $foreignChar[] = "&ucirc;";
        $foreignChar[] = "&uuml;";
        $foreignChar[] = "&yuml;";
        $foreignChar[] = "&auml;";
        $foreignChar[] = "&ouml;";
        $foreignChar[] = "&uuml;";
        $foreignChar[] = "&szlig;";
        $foreignChar[] = "&aacute;";
        $foreignChar[] = "&acirc;";
        $foreignChar[] = "&atilde;";
        $foreignChar[] = "&iacute;";
        $foreignChar[] = "&icirc;";
        $foreignChar[] = "&#297;";
        $foreignChar[] = "&ugrave;";
        $foreignChar[] = "&ucirc;";
        $foreignChar[] = "&#361;";
        $foreignChar[] = "&#312;";
        $foreignChar[] = "&aacute;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&iacute;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&ouml;";
        $foreignChar[] = "&#337;";
        $foreignChar[] = "&uacute;";
        $foreignChar[] = "&uuml;";
        $foreignChar[] = "&#369;";
        $foreignChar[] = "&aacute;";
        $foreignChar[] = "&eth;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&iacute;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&uacute;";
        $foreignChar[] = "&yacute;";
        $foreignChar[] = "&thorn;";
        $foreignChar[] = "&aelig;";
        $foreignChar[] = "&uml;";
        $foreignChar[] = "&aacute;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&iacute;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&uacute;";
        $foreignChar[] = "&agrave;";
        $foreignChar[] = "&acirc;";
        $foreignChar[] = "&egrave;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&ecirc;";
        $foreignChar[] = "&igrave;";
        $foreignChar[] = "&iacute;";
        $foreignChar[] = "&icirc;";
        $foreignChar[] = "&iuml;";
        $foreignChar[] = "&ograve;";
        $foreignChar[] = "&ocirc;";
        $foreignChar[] = "&ugrave;";
        $foreignChar[] = "&ucirc;";
        $foreignChar[] = "&#257;";
        $foreignChar[] = "&#269;";
        $foreignChar[] = "&#275;";
        $foreignChar[] = "&#291;";
        $foreignChar[] = "&#299;";
        $foreignChar[] = "&#311;";
        $foreignChar[] = "&#316;";
        $foreignChar[] = "&#326;";
        $foreignChar[] = "&#343;";
        $foreignChar[] = "&#353;";
        $foreignChar[] = "&#363;";
        $foreignChar[] = "&#382;";
        $foreignChar[] = "&aelig;";
        $foreignChar[] = "&oslash;";
        $foreignChar[] = "&aring;";
        $foreignChar[] = "&#261;";
        $foreignChar[] = "&#263;";
        $foreignChar[] = "&#281;";
        $foreignChar[] = "&#322;";
        $foreignChar[] = "&#324;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&#347;";
        $foreignChar[] = "&#378;";
        $foreignChar[] = "&#380;";
        $foreignChar[] = "&agrave;";
        $foreignChar[] = "&aacute;";
        $foreignChar[] = "&acirc;";
        $foreignChar[] = "&atilde;";
        $foreignChar[] = "&ccedil;";
        $foreignChar[] = "&egrave;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&ecirc;";
        $foreignChar[] = "&igrave;";
        $foreignChar[] = "&iacute;";
        $foreignChar[] = "&iuml;";
        $foreignChar[] = "&ograve;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&otilde;";
        $foreignChar[] = "&ugrave;";
        $foreignChar[] = "&uacute;";
        $foreignChar[] = "&uuml;";
        $foreignChar[] = "&ordf;";
        $foreignChar[] = "&ordm;";
        $foreignChar[] = "&#259;";
        $foreignChar[] = "&acirc;";
        $foreignChar[] = "&icirc;";
        $foreignChar[] = "&#351;";
        $foreignChar[] = "&#355;";
        $foreignChar[] = "&aacute;";
        $foreignChar[] = "&#269;";
        $foreignChar[] = "&#273;";
        $foreignChar[] = "&#331;";
        $foreignChar[] = "&#353;";
        $foreignChar[] = "&#359;";
        $foreignChar[] = "&#382;";
        $foreignChar[] = "&agrave;";
        $foreignChar[] = "&egrave;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&igrave;";
        $foreignChar[] = "&ograve;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&ugrave;";
        $foreignChar[] = "&aacute;";
        $foreignChar[] = "&auml;";
        $foreignChar[] = "&#269;";
        $foreignChar[] = "&#271;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&#314;";
        $foreignChar[] = "&#318;";
        $foreignChar[] = "&#328;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&ocirc;";
        $foreignChar[] = "&#341;";
        $foreignChar[] = "&#353;";
        $foreignChar[] = "&#357;";
        $foreignChar[] = "&uacute;";
        $foreignChar[] = "&yacute;";
        $foreignChar[] = "&#382;";
        $foreignChar[] = "&#269;";
        $foreignChar[] = "&#353;";
        $foreignChar[] = "&#382;";
        $foreignChar[] = "&aacute;";
        $foreignChar[] = "&eacute;";
        $foreignChar[] = "&iacute;";
        $foreignChar[] = "&oacute;";
        $foreignChar[] = "&ntilde;";
        $foreignChar[] = "&uacute;";
        $foreignChar[] = "&uuml;";
        $foreignChar[] = "&ordf;";
        $foreignChar[] = "&ordm;";
        $foreignChar[] = "&aring;";
        $foreignChar[] = "&auml;";
        $foreignChar[] = "&ouml;";
        $foreignChar[] = "&ccedil;";
        $foreignChar[] = "&#287;";
        $foreignChar[] = "&#305;";
        $foreignChar[] = "&ouml;";
        $foreignChar[] = "&#351;";
        $foreignChar[] = "&uuml;";
        $output = str_replace($foreignChar,"",$str);
        return $output;
    }

}

?>