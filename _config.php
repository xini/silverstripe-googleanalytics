<?php

define('GOOGLEANALYTICS_PATH', dirname(__FILE__));
$dir = explode(DIRECTORY_SEPARATOR, GOOGLEANALYTICS_PATH);
define('GOOGLEANALYTICS_DIR', array_pop($dir));