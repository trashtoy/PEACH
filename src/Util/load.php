<?php
/**
 * Util パッケージの全ファイルをロードします.
 * @package Util
 */
if (!function_exists("loadUtil")) {
    /** @ignore */
    function loadUtil() {
        $base        = dirname(__FILE__);
        $dir         = dir($base);
        while (FALSE !== ($entry = $dir->read())) {
            switch ($entry) {
                case ".":
                case "..":
                case "load.php":
                    break;
                default:
                    /** @ignore */
                    require_once($base . "/" . $entry);
            }
        }
    }
    loadUtil();
}
?>