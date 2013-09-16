<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('_init_adodb_library')) {
    function _init_adodb_library(&$ci) {
        $db_var = false;
        $debug = false;
        
        // try to load config/adodb.php
        // extra parameter comes from patch at http://www.codeigniter.com/wiki/ConfigLoadPatch/
        // without this patch, if config/adodb.php doesn't exist, CI will display a fatal error.
        if ($ci->config->load('adodb',true)) {
            $cfg = $ci->config->item('adodb');
            if (isset($cfg['dsn'])) {
                $dsn = $cfg['dsn'];
            }
            
            // set db_var if it's set in the config file, or false otherwise
            $db_var = isset($cfg['db_var']) && $cfg['db_var'];
            
            $debug = isset($cfg['debug']) && $cfg['debug'];
        }
        
        if (!isset($dsn)) {
            // fallback to using the CI database file
            include(APPPATH.'config/database'.EXT);
            $group = 'default';
            $dsn = $db[$group]['dbdriver'].'://'.$db[$group]['username']
                   .':'.$db[$group]['password'].'@'.$db[$group]['hostname']
                   .'/'.$db[$group]['database'];
        }
        
        // $ci is by reference, refers back to global instance
        $ci->adodb =& ADONewConnection($dsn);
        
        if ($db_var) {
            // also set the normal CI db variable
            $ci->db =& $ci->adodb;
        }
        
        if ($debug) {
            $ci->adodb->debug = true;
        }
    }
}

if ( ! class_exists('ADONewConnection') )
{
     require_once(APPPATH.'libraries/adodb/adodb.inc'.EXT);
}

$obj =& get_instance();
_init_adodb_library($obj);
$obj->ci_is_loaded[] = 'adodb';

?>