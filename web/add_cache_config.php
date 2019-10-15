<?php

include 'config.php';

$CACHECFG = array("color" => "green");

$NEWCONFIG = array_merge($CONFIG, $CACHECFG);

$TOFILE = var_export($NEWCONFIG, TRUE);

$fh = fopen('config_new.php', 'w') or die("can't open file");

fwrite($fh,"<?php\n\$CONFIG = ".trim($TOFILE).";\n?>");

fclose($fh);

?>