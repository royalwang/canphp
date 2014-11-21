<?php
class AESHelper{
	private static $ciphername = MCRYPT_RIJNDAEL_128;
	private static $modename   = MCRYPT_MODE_ECB;
	
	public static function encrypt($content, $password) {
        // ...
        $key = md5($password, true);
        $cipertext = mcrypt_encrypt(self::$ciphername, $key, $content, self::$modename);
        $cipertext_arr = unpack("H*0", $cipertext);
        return $cipertext_arr[0];
    }
	public static function decrypt($content, $password) {
        // ...
        $key = md5($password, true);
        $ciphertext = pack("H*",$content);
        $plaintext = mcrypt_decrypt(self::$ciphername, $key, $ciphertext, self::$modename);
        return rtrim($plaintext,"\0");
    }
}

function test_AESHelper(){
	$plaintext = "abcdefghijklmnopqrstuvwxyz";
	$password  = "0123456789";
	
	$cipertext = AESHelper::encrypt($plaintext, $password);
	
	$plaintext_2 = AESHelper::decrypt($cipertext, $password);
	if($plaintext == $plaintext_2){
		return true;
	}
	return false;
}
?>
