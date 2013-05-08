<?php
$_dir = dirname(__FILE__);
require_once("{$_dir}/SplClassLoader.php");
$splClassLoader = new SplClassLoader("Peach", $_dir);
$splClassLoader->setNamespaceSeparator("_");
$splClassLoader->register();
?>