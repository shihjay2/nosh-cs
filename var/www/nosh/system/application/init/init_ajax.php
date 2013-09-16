<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if ( ! class_exists('xajax'))
{
    require_once(APPPATH.'libraries/xajax'.EXT);
}

$obj =& get_instance();
$obj->xajax = new xajax();
$obj->ci_is_loaded[] = 'xajax';

?> 