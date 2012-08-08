<?php
/**
 * DT パッケージの全ファイルをロードします.
 * @package DT
 */
if (!function_exists("loadDT")) {
    function loadDT() {
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
    loadDT();
}
?>