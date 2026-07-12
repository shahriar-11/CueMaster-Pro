<?php
/**
 * CueMaster Pro — Global Constants
 * BASE_URL is auto-detected so the project runs correctly no matter
 * what folder name it's placed under in htdocs (e.g. htdocs/cuemaster-pro/).
 */

if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host     = $_SERVER['HTTP_HOST'];

    // Directory of the currently running script
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    // If the script lives one level down (auth/, dashboard/, api/, modules/...), strip that
    // segment so we always land on the project root — regardless of script depth.
    $projectRoot = preg_replace('#/(auth|dashboard|api|modules)$#', '', $scriptDir);

    define('BASE_URL', $protocol . $host . $projectRoot . '/');
}

define('APP_NAME', 'CueMaster Pro');
