<?php
namespace App\Lib\Traits;

trait Sanitize
{
    public function sanitize_string($var) 
    {
        if (is_string($var) && !empty($var)) {
            // remove HTML tags
            $var = strip_tags($var);
            // remove non-breaking spaces
            $var = preg_replace("#\x{00a0}#siu", ' ', $var);
            // remove illegal file system characters
            $var = str_replace(array_map('chr', range(0, 31)), '', $var);
            // remove dangerous characters for file names
            $chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "’", "%20",
                        "+", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", "%", "+", "^", chr(0));
            $var = str_replace($chars, '_', $var);
            // remove break/tabs/return carriage
            $var = preg_replace('/[\r\n\t -]+/', '_', $var);
            // convert some special letters
            $convert = array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss',
                            'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u');
            $var = strtr($var, $convert);
            // remove foreign accents by converting to HTML entities, and then remove the code
            $var = html_entity_decode( $var, ENT_QUOTES, "utf-8" );
            $var = htmlentities($var, ENT_QUOTES, "utf-8");
            $var = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $var);
            // clean up, and remove repetitions
            $var = preg_replace('/_+/', '_', $var);
            $var = preg_replace(array('/ +/', '/-+/'), '_', $var);
            $var = preg_replace(array('/-*\.-*/', '/\.{2,}/'), '.', $var);
            // cut to 255 characters
            $var = substr($var, 0, 255);
            // remove bad characters at start and end
            $var = trim($var, '.-_');
            return $var;
        } else {
            return false;
        }
    }
}
?>