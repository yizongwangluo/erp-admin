<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/


$hook['pre_system'] = function () {
    load_class('User_agent');
    $ua = new CI_User_agent();
    define('IS_WAP', $ua->is_mobile());
    define('IS_888_WAP', strpos ($_SERVER['HTTP_HOST'],'265g.com'));
    define('IS_6585_WAP', strpos ($_SERVER['HTTP_HOST'],'jyt.6585.com'));
};