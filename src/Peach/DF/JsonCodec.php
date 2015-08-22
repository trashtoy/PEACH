<?php
/*
 * Copyright (c) 2015 @trashtoy
 * https://github.com/trashtoy/
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the
 * Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
/** @package DF */
/**
 * JSON 形式の文字列を扱う Codec です.
 * このクラスは {@link http://tools.ietf.org/html/rfc7159 RFC 7159}
 * の仕様に基いて JSON のデコード (JSON を値に変換) とエンコード (値を JSON に変換)
 * を行います.
 * 
 * RFC 7159 によると JSON 文字列のエンコーディングは UTF-8, UTF-16, UTF-32
 * のいずれかであると定義されていますが, この実装は UTF-8 でエンコーディングされていることを前提とします.
 * UTF-8 以外の文字列をデコードした場合はエラーとなります.
 * 
 * @package DF
 */
class Peach_DF_JsonCodec implements Peach_DF_Codec
{
    /**
     * 定数 JSON_HEX_TAG に相当するオプションです.
     * 文字 %x3c (LESS-THAN SIGN) および %x3e (GREATER-THAN SIGN)
     * をそれぞれ "\u003C" および "\u003E" にエンコードします
     * 
     * @var int
     */
    const HEX_TAG  = 1;
    
    /**
     * 定数 JSON_HEX_AMP に相当するオプションです.
     * 文字 "&" を "\u0026" にエンコードします.
     * 
     * @var int
     */
    const HEX_AMP  = 2;
    
    /**
     * 定数 JSON_HEX_APOS に相当するオプションです.
     * 文字 "'" を "\u0027" にエンコードします.
     * 
     * @var int
     */
    const HEX_APOS = 4;
    
    /**
     * 定数 JSON_HEX_QUOT に相当するオプションです.
     * 文字 '"' を "\u0022" にエンコードします.
     * 
     * @var int
     */
    const HEX_QUOT = 8;
    
    /**
     * 定数 JSON_FORCE_OBJECT に相当するオプションです.
     * 通常は array 形式でエンコードされる配列を object 形式でエンコードします.
     * 具体的には以下の配列の出力に影響します.
     * 
     * - 空の配列
     * - 添字が 0 から始まる整数の連続 (0, 1, 2, ...) となっている配列
     * 
     * @var int
     */
    const FORCE_OBJECT = 16;
    
    /**
     * 定数 JSON_NUMERIC_CHECK に相当するオプションです.
     * 数値表現の文字列を数値としてエンコードします.
     */
    const NUMERIC_CHECK = 32;
    
    /**
     * 定数 JSON_UNESCAPED_SLASHES に相当するオプションです.
     * エンコードの際に "/" をエスケープしないようにします.
     * 
     * @var int
     */
    const UNESCAPED_SLASHES = 64;
    
    /**
     * 定数 JSON_PRETTY_PRINT に相当するオプションです.
     * object, array 形式の書式でエンコードする際に,
     * 半角スペース 4 個でインデントして整形します.
     * 
     * @var int
     */
    const PRETTY_PRINT = 128;
    
    /**
     * 定数 JSON_UNESCAPED_UNICODE に相当するオプションです.
     * エンコードの際にマルチバイト文字を UTF-8 文字として表現します.
     * 
     * @var int
     */
    const UNESCAPED_UNICODE = 256;
    
    /**
     * 定数 JSON_PRESERVE_ZERO_FRACTION に相当するオプションです.
     * float 型の値を常に float 値としてエンコードします.
     * このオプションが OFF の場合, 小数部が 0 の数値 (2.0 など) は
     * 整数としてエンコードされます.
     * 
     * @var int
     */
    const PRESERVE_ZERO_FRACTION = 1024;
    
    /**
     * {@link http://php.net/manual/function.json-decode.php json_decode()}
     * の第 2 引数に相当する, このクラス独自のオプションです.
     * このオプションが ON の場合, object 形式の値をデコードする際に配列に変換します.
     * (デフォルトでは stdClass オブジェクトとなります)
     * 
     * @var int
     */
    const OBJECT_AS_ARRAY = 1;
    
    /**
     * 定数 JSON_BIGINT_AS_STRING に相当するオプションです.
     * 巨大整数をデコードする際に, int の範囲に収まらない値を文字列に変換します.
     * (デフォルトでは float 型となります)
     * 
     * @var int
     */
    const BIGINT_AS_STRING = 2;
    
    /**
     * encode() の出力内容をカスタマイズするオプションです.
     * 
     * @var Peach_Util_ArrayMap
     */
    private $encodeOptions;
    
    /**
     * decode() の出力内容をカスタマイズするオプションです.
     * 
     * @var Peach_Util_ArrayMap
     */
    private $decodeOptions;
    
    /**
     * 文字列をエンコードする際に使用する Utf8Codec です.
     * 
     * @var Peach_DF_Utf8Codec
     */
    private $utf8Codec;
    
    /**
     * 新しい JsonCodec を構築します.
     * 引数に encode() および decode() の出力のカスタマイズオプションを指定することが出来ます.
     * 引数は配列または整数を指定することが出来ます.
     * 
     * - 配列の場合: キーにオプション定数, 値に true または false を指定してください.
     * - 整数の場合: 各オプションのビットマスクを指定してください. 例えば Peach_DF_JsonCodec::HEX_TAG | Peach_DF_JsonCodec::HEX_AMP のような形式となります.
     * 
     * @param array|int $encodeOptions encode() のカスタマイズオプション
     * @param array|int $decodeOptions decode() のカスタマイズオプション
     */
    public function __construct($encodeOptions = null, $decodeOptions = null)
    {
        $this->encodeOptions = $this->initOptions($encodeOptions);
        $this->decodeOptions = $this->initOptions($decodeOptions);
        $this->utf8Codec     = new Peach_DF_Utf8Codec();
    }
    
    /**
     * コンストラクタに指定された $encodeOptions および $decodeOptions
     * を初期化します.
     * 
     * @param  array|int $options  コンストラクタに指定されたオプション
     * @return Peach_Util_ArrayMap 各オプションの ON/OFF をあらわす ArrayMap
     */
    private function initOptions($options)
    {
        $result = new Peach_Util_ArrayMap();
        if (is_scalar($options)) {
            return $this->initOptionsByBitMask(Peach_Util_Values::intValue($options, 0));
        }
        if (!is_array($options)) {
            return $result;
        }
        
        foreach ($options as $key => $value) {
            $result->put($key, Peach_Util_Values::boolValue($value));
        }
        return $result;
    }
    
    /**
     * ビットマスクを配列に変換します.
     * 
     * @param  int $options        オプションをあらわす整数
     * @return Peach_Util_ArrayMap 変換後のオプション
     */
    private function initOptionsByBitMask($options)
    {
        $opt    = 1;
        $result = new Peach_Util_ArrayMap();
        while ($options) {
            $result->put($opt, (bool) ($options % 2));
            $options >>= 1;
            $opt     <<= 1;
        }
        return $result;
    }
    
    /**
     * 指定されたエンコード用オプションが ON かどうかを調べます.
     * 
     * @param  int $code オプション (定義されている定数)
     * @return bool      指定されたオプションが ON の場合は true, それ以外は false
     */
    public function getEncodeOption($code)
    {
        return $this->encodeOptions->get($code, false);
    }
    
    /**
     * 指定されたデコード用オプションが ON かどうかを調べます.
     * 
     * @param  int $code オプション (定義されている定数)
     * @return bool      指定されたオプションが ON の場合は true, それ以外は false
     */
    public function getDecodeOption($code)
    {
        return $this->decodeOptions->get($code, false);
    }
    
    /**
     * 指定された JSON 文字列を値に変換します.
     * 
     * 引数が空白文字列 (または null, false) の場合は null を返します.
     * 
     * @param  string $text 変換対象の JSON 文字列
     * @return mixed        変換結果
     */
    public function decode($text)
    {
        if (Peach_Util_Strings::isWhitespace($text)) {
            return null;
        }
        
        try {
            $root = new Peach_DF_JsonCodec_Root();
            $root->handle(new Peach_DF_JsonCodec_Context($text, $this->decodeOptions));
            return $root->getResult();
        } catch (Peach_DF_JsonCodec_DecodeException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }
    
    /**
     * 指定された値を JSON 文字列に変換します.
     * 
     * @param  mixed  $var 変換対象の値
     * @return string      JSON 文字列
     */
    public function encode($var)
    {
        return $this->encodeValue($var);
    }
    
    /**
     * encode() の本体の処理です.
     * 指定された値がスカラー型 (null, 真偽値, 数値, 文字列), 配列, オブジェクトのいずれかにも該当しない場合,
     * 文字列にキャストした結果をエンコードします.
     * 
     * @param  mixed  $var 変換対象の値
     * @return string      JSON 文字列
     * @ignore
     */
    public function encodeValue($var)
    {
        if ($var === null) {
            return "null";
        }
        if ($var === true) {
            return "true";
        }
        if ($var === false) {
            return "false";
        }
        if (is_float($var)) {
            return $this->encodeFloat($var);
        }
        if (is_integer($var)) {
            return strval($var);
        }
        if (is_string($var)) {
            return is_numeric($var) ? $this->encodeNumeric($var) : $this->encodeString($var);
        }
        if (is_array($var)) {
            return $this->checkKeySequence($var) ? $this->encodeArray($var) : $this->encodeObject($var);
        }
        if (is_object($var)) {
            $arr = (array) $var;
            return $this->encodeValue($arr);
        }
        
        return $this->encodeValue(Peach_Util_Values::stringValue($var));
    }
    
    /**
     * 数値形式の文字列を数値としてエンコードします.
     * 
     * @param string $var
     */
    private function encodeNumeric($var)
    {
        if (!$this->getEncodeOption(self::NUMERIC_CHECK)) {
            return $this->encodeString($var);
        }
        
        $num = preg_match("/^-?[0-9]+$/", $var) ? intval($var) : floatval($var);
        return $this->encodeValue($num);
    }
    
    /**
     * float 値を文字列に変換します.
     * 
     * @param  float $var 変換対象の float 値
     * @return string     変換結果
     */
    private function encodeFloat($var)
    {
        $str = strval($var);
        if (!$this->getEncodeOption(self::PRESERVE_ZERO_FRACTION)) {
            return $str;
        }
        if (false !== strpos($str, "E")) {
            return $str;
        }
        return (floor($var) === $var) ? "{$str}.0" : $str;
    }
    
    /**
     * 配列のキーが 0, 1, 2, ……という具合に 0 から始まる整数の連続になっていた場合のみ true,
     * それ以外は false を返します.
     * ただし, オプション FORCE_OBJECT が ON の場合は常に false を返します.
     * 
     * @param  array $arr 変換対象の配列
     * @return bool       配列のキーが整数の連続になっていた場合のみ true
     */
    private function checkKeySequence(array $arr) {
        if ($this->getEncodeOption(self::FORCE_OBJECT)) {
            return false;
        }
        
        $i = 0;
        foreach (array_keys($arr) as $key) {
            if ($i !== $key) {
                return false;
            }
            $i++;
        }
        return true;
    }
    
    /**
     * 文字列を JSON 文字列に変換します.
     * 
     * @param  string $str 変換対象の文字列
     * @return string      JSON 文字列
     * @ignore
     */
    public function encodeString($str)
    {
        $unicodeList = $this->utf8Codec->decode($str);
        return '"' . implode("", array_map(array($this, "encodeCodepoint"), $unicodeList)) . '"';
    }
    
    /**
     * 指定された Unicode 符号点を JSON 文字に変換します.
     * 
     * @param  int $num Unicode 符号点
     * @return string   指定された Unicode 符号点に対応する文字列
     * @ignore
     */
    public function encodeCodePoint($num)
    {
        // @codeCoverageIgnoreStart
        static $hexList = array(
            0x3C => self::HEX_TAG,
            0x3E => self::HEX_TAG,
            0x26 => self::HEX_AMP,
            0x27 => self::HEX_APOS,
            0x22 => self::HEX_QUOT,
        );
        static $encodeList = array(
            0x22 => "\\\"",
            0x5C => "\\\\",
            0x08 => "\\b",
            0x0C => "\\f",
            0x0A => "\\n",
            0x0D => "\\r",
            0x09 => "\\t",
        );
        // @codeCoverageIgnoreEnd
        
        if (array_key_exists($num, $hexList) && $this->getEncodeOption($hexList[$num])) {
            return "\\u00" . strtoupper(dechex($num));
        }
        if (array_key_exists($num, $encodeList)) {
            return $encodeList[$num];
        }
        if ($num === 0x2F) {
            return $this->getEncodeOption(self::UNESCAPED_SLASHES) ? "/" : "\\/";
        }
        if (0x20 <= $num && $num < 0x80) {
            return chr($num);
        }
        if (0x80 <= $num && $this->getEncodeOption(self::UNESCAPED_UNICODE)) {
            return $this->utf8Codec->encode($num);
        }
        return "\\u" . str_pad(dechex($num), 4, "0", STR_PAD_LEFT);
    }
    
    /**
     * 指定された配列を JSON の array 表記に変換します.
     * オプション PRETTY_PRINT が有効化されている場合,
     * json_encode の JSON_PRETTY_PRINT と同様に半角スペース 4 個と改行文字で整形します.
     * 
     * @param  array  $arr 変換対象
     * @return string      JSON 文字列
     */
    private function encodeArray(array $arr)
    {
        $prettyPrintEnabled = $this->getEncodeOption(self::PRETTY_PRINT);
        
        $indent   = $prettyPrintEnabled ? PHP_EOL . "    " : "";
        $start    = "[" . $indent;
        $end      = $prettyPrintEnabled ? PHP_EOL . "]" : "]";
        $callback = array($this, "formatArrayValue");
        return $start . implode("," . $indent, array_map($callback, $arr)) . $end;
    }
    
    public function formatArrayValue($value)
    {
        $prettyPrintEnabled = $this->getEncodeOption(self::PRETTY_PRINT);
        $indent             = $prettyPrintEnabled ? PHP_EOL . "    " : "";
        $valueResult        = $this->encodeValue($value);
        return $prettyPrintEnabled ? str_replace(PHP_EOL, $indent, $valueResult) : $valueResult;
    }
    
    /**
     * 指定された配列を JSON の object 表記に変換します.
     * オプション PRETTY_PRINT が有効化されている場合,
     * json_encode の JSON_PRETTY_PRINT と同様に半角スペース 4 個と改行文字で整形します.
     * 
     * @param  array  $arr 変換対象
     * @return string      JSON 文字列
     */
    private function encodeObject(array $arr)
    {
        $prettyPrintEnabled = $this->getEncodeOption(self::PRETTY_PRINT);
        
        $indent   = $prettyPrintEnabled ? PHP_EOL . "    " : "";
        $start    = "{" . $indent;
        $end      = $prettyPrintEnabled ? PHP_EOL . "}" : "}";
        $callback = array($this, "formatObjectValue");
        return $start . implode("," . $indent, array_map($callback, array_keys($arr), array_values($arr))) . $end;
    }
    
    public function formatObjectValue($key, $value)
    {
        $prettyPrintEnabled = $this->getEncodeOption(self::PRETTY_PRINT);
        
        $indent      = $prettyPrintEnabled ? PHP_EOL . "    " : "";
        $coron       = $prettyPrintEnabled ? ": " : ":";
        $valueResult = $this->encodeValue($value);
        $valueJson   = $prettyPrintEnabled ? str_replace(PHP_EOL, $indent, $valueResult) : $valueResult;
        return $this->encodeString($key) . $coron . $valueJson;
    }
}
