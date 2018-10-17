/**
* author: alexeyprudnikov
**/
class Utility {
  /**
	 * CryptoJS aes-256-cbc decryptor
	 * @param $base64
	 * @param $secret
	 * @return string
	 */
	public static function cryptoJsAesDecrypt($base64, $secret) {

		// _ durch + zurück ersetzen wegen GET Übergabe Problem aus JS (+ wird als Leerzeichen interpretiert)
		$base64 = str_replace('_', '+', $base64);

		$data = base64_decode($base64);

		$salt = substr($data, 8, 8);
		$main = substr($data, 16);

		$rounds = 3;
		$data00 = $secret.$salt;

		$md5_hash = array();
		$md5_hash[0] = md5($data00, true);

		$result = $md5_hash[0];
		for ($i = 1; $i < $rounds; $i++) {
			$md5_hash[$i] = md5($md5_hash[$i - 1].$data00, true);
			$result .= $md5_hash[$i];
		}

		$key = substr($result, 0, 32);
		$iv = substr($result, 32, 16);

		$decrypted = openssl_decrypt($main, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

		return $decrypted;
	}
}
