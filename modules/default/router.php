<?php
/**
 * router.php Маршрутизатор модуля default
 *
 * Created by Konstantin Khachaturyan
 * User: AGA-C4
 * Date: 09.04.15
 * Time: 16:53
 */

//if (!empty(Glob::$vars['request']['module'])) { Glob::$vars['module'] = Glob::$vars['request']['module'];}
if (!empty(Glob::$vars['request']['controller'])) Glob::$vars['controller'] = Glob::$vars['request']['controller']; else Glob::$vars['controller'] = SysBF::trueName(Glob::$vars['module'],'title');
if (!empty(Glob::$vars['request']['action'])) Glob::$vars['action'] = Glob::$vars['request']['action'];

$controller = Glob::$vars['controller'];
$action = Glob::$vars['action'];

SysLogs::addLog('Default router: Module=['.Glob::$vars['module'].'] Controller=['.$controller.'] Action=['.$action.']');