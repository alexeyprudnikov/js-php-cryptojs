/**
 * methods encrypt and decrypt string for transfer to php via GET
 * crypto-js is required al modul or path to library .js file
 *
 * author: alexeyprudnikov
 */
let CryptoJS = require("crypto-js")
class Utility {
  /**
   * encrypt with method AES-256-CBC, returns encrypted string
   * @param {String} text
   * @param {String} secretkey
   * @param {Boolean} raw - forces to output encrypted string as original, not for GET transfer
   * @returns {String}
  */
  static encryptString (text, secretkey, raw) {
        let iv = CryptoJS.SHA256(secretkey).toString().substring(0,16)
        iv = CryptoJS.enc.Utf8.parse(iv)
        let encrypted = CryptoJS.AES.encrypt(text, secretkey, {iv: iv, mode: CryptoJS.mode.CBC, padding: CryptoJS.pad.Pkcs7})
        let encryptedString = encrypted.toString()
        if(raw) { return encryptedString }
        // + durch _ ersetzen wegen GET Übergabe Problem (+ als Leerzeichen vom PHP angenommen und deswegen falsch enkodiert)
        return encryptedString.replace(/\+/g,'_')
    }
    /**
     * decrypt with method AES-256-CBC, returns null if failed
     * @param {String} string
     * @param {String} secretkey
     * @returns {String|null}
     */
    static decryptString (string, secretkey) {
        string = string.replace(/_/g,'+')
        let iv = CryptoJS.SHA256(secretkey).toString().substring(0,16)
        let decrypted = CryptoJS.AES.decrypt(string, secretkey, {iv: iv, mode: CryptoJS.mode.CBC, padding: CryptoJS.pad.Pkcs7})
        try {
            return decrypted.toString(CryptoJS.enc.Utf8)
        } catch(e) {
            return null
        }
    }
}
