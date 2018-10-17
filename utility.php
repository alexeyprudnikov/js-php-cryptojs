/**
* author: alexeyprudnikov
**/
class Utility {
  /**
   * CryptoJS aes-256-cbc decryptor
   * @param $base64string
   * @param $secretkey
   * @return string
   */
   public static function cryptoJsAesDecrypt($base64string, $secretkey) {

   	// _ durch + zurück ersetzen wegen GET Übergabe Problem aus JS (+ wird als Leerzeichen interpretiert)
	$base64string = str_replace('_', '+', $base64string);

	$data = base64_decode($base64string);

	$salt = substr($data, 8, 8);
	$main = substr($data, 16);

	$rounds = 3;
	$sksalt = $secretkey.$salt;

	$md5_hash = array();
	$md5_hash[0] = md5($sksalt, true);

	$result = $md5_hash[0];
	for ($i = 1; $i < $rounds; $i++) {
		$md5_hash[$i] = md5($md5_hash[$i - 1].$sksalt, true);
		$result .= $md5_hash[$i];
	}

	$key = substr($result, 0, 32);
	$iv = substr($result, 32, 16);

	$decrypted = openssl_decrypt($main, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

	return $decrypted;
   }
}
