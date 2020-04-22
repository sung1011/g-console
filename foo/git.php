<?php
// include ('versionauth.php');
ini_set('display_errors', 1);
error_reporting(15);
date_default_timezone_set('PRC');
include('functions.php');

function getVersions($hostname) {
    $configs = require('configs.php');
    if(!isset($configs['versions'][$hostname])) {
        return array();
    }
    return $configs['versions'][$hostname];
}

$action = 'showPage';
$v = '';
$t = 'game';
if(isset($_REQUEST['a'])) $action = $_REQUEST['a'];
if(isset($_REQUEST['v'])) $v = $_REQUEST['v']; // dev, ha-h5, happya-release-test
if(isset($_REQUEST['t'])) $t = $_REQUEST['t']; // game, tools, game-config

$hostname = $_SERVER['SERVER_NAME'];
$versions = getVersions($hostname);
if(!$versions) die('error!');
if(!isset($versions[$v])) $v = '1';
$version = $versions[$v];
if(!isset($version[$t])) $t = 'game';

$className = 'gitClass';
if(is_callable(array($className, $action), false)) {
    $refClass = new ReflectionClass($className);
    $class = $refClass->newInstance($version, $v, $t, $hostname);
    $result = $refClass->getMethod($action)->invokeArgs($class, array());

    echo $result;
}

class gitClass {

    private $dir = ''; // 目录
    private $pageDir = ''; // 当前目录
    private $logFile = '';
