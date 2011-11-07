<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/
/**
 * Настройки для локального сервера.
 * Для использования - переименовать файл в config.local.php
 */

/**
 * Настройка базы данных
 */
$config['db']['params']['host'] = 'localhost';
$config['db']['params']['port'] = '3306';
$config['db']['params']['user'] = 'root';
$config['db']['params']['pass'] = '';
$config['db']['params']['type']   = 'mysql';
$config['db']['params']['dbname'] = 'livestreet';
$config['db']['table']['prefix'] = 'prefix_';

$config['path']['root']['web'] = 'http://192.168.18.41/mlivestreet';
$config['path']['root']['server'] = '/var/www/mlivestreet';
$config['path']['offset_request_url'] = '1';
$config['db']['tables']['engine'] = 'InnoDB';
$config['view']['name'] = 'LiveStreet - бесплатный движок социальной сети';
$config['view']['description'] = 'LiveStreet - официальный сайт бесплатного движка социальной сети';
$config['view']['keywords'] = 'движок, livestreet, блоги, социальная сеть, бесплатный, php';
$config['view']['skin'] = 'developer';
$config['sys']['mail']['from_email'] = 'admin@admin.adm';
$config['sys']['mail']['from_name'] = 'Почтовик LiveStreet';
$config['general']['close'] = true;
$config['general']['reg']['activation'] = false;
$config['general']['reg']['invite'] = false;
$config['lang']['current'] = 'russian';
$config['lang']['default'] = 'russian';
return $config;
?>