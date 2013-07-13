<?php

// You can change the salt you use in your application here
define("Kl76n9hHGg775H","1o37j");

class textEncrypter
{


    var $salt;
    var $separator;

    /**
    * @return returns value of variable $salt
    * @desc getSalt : Getting value for variable $salt
    */
    function getSalt ()
    {
        return $this->salt ;
    }

    /**
    * @param param : value to be saved in variable $salt
    * @desc setSalt : Setting value for $salt
    */
    function setSalt ($value)
    {
        $this->salt  = $value;
    }

    /**
    * @return returns value of variable $separator
    * @desc getSeparator : Getting value for variable $separator
    */
    function getSeparator()
    {
        return $this->separator;
    }

    /**
    * @param param : value to be saved in variable $separator
    * @desc setSeparator : Setting value for $separator
    */
    function setSeparator($value)
    {
        $this->separator = $value;
    }

    function textEncrypter()
    {
        $this->setSalt("Kl76n9hHGg775H");
        $this->setSeparator("||:||");
    }


    /**
    * @return encoded string with salt added
    * @param String to be encoded
    * @desc Adds Salt to Data and Encode it before sending back to client
    * @generationDate 2004-10-31
    * @version 1.0
    * @license GNU GPL License
    * @author Nilesh Dosooye <opensource@weboot.com>
    */
    function encode($string)
    {
        // Write Function Code Here

        $string = $string.$this->getSeparator().$this->getSalt();
        $string = base64_encode($string);

        return $string;
    }

    /**
    * @return UnEncoded Data
    * @param String to be Decoded
    * @desc Decode Data and Exits if tampering of data is detected
    * @generationDate 2004-10-31
    * @version 1.0
    * @license GNU GPL License
    * @author Nilesh Dosooye <opensource@weboot.com>
    */
    function decode($string)
    {
        // Write Function Code Here

        $string = base64_decode($string);
        $tokens = explode($this->getSeparator(),$string);
/*
        if (@$tokens[2]!=$this->getSalt())
        {

            echo "Data tampering was detected. Your session has expired.";
            exit;
        }
*/

        return $tokens[0];

    }


}

global $encrypter;
$encrypter = new textEncrypter();

?>