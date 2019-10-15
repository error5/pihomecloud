<?php

include 'config.php';

copy ( 'config.php', 'config.php'.date("c"));

// Config for NON SSL, APC and MEMCACHE
$CACHECFG = array ('csrf.disabled' => true,
  'memcache.local' => '\\OC\\Memcache\\APCu',
  'memcache.locking' => '\\OC\\Memcache\\Redis',
  'redis' => 
  array (
    'host' => 'redis',
    'port' => 6379,
  ),
  'filelocking.enabled' => true,
);

$NEWCONFIG = array_merge($CONFIG, $CACHECFG);

$TOFILE = var_export($NEWCONFIG, TRUE);

$fh = fopen('config.php', 'w') or die("can't open file");

fwrite($fh,"<?php\n\$CONFIG = ".trim($TOFILE).";\n?>");

fclose($fh);

?>