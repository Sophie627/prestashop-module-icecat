<?php
# /modules/icecat/models/IcecatModel.php

/**
 * Icecat integration - A Prestashop Module
 * 
 * Icecat integration
 * 
 * @author Hendry Raudsepp <hraudsepp10@gmail.com>
 * @version 0.0.1
 */

if ( !defined('_PS_VERSION_') ) exit;

class IcecatModel extends ObjectModel
{
	/** Your fields names, adapt to your needs */
	public $list_name;
	public $data;

	/** Your table definition, adapt to your needs */
	public static $definition = [
		'table' => 'icecat',
		'fields' => [
			'list_name' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 16,
				'required' => true,
			],
			'data' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 16*1024*1024,
			],
		],
	];

	/** Create your table into database, adapt to your needs */
	public static function installSql()
	{
		$tableName = _DB_PREFIX_ . self::$definition['table'];

		$sql = "
			CREATE TABLE IF NOT EXISTS `{$tableName}` (
				`list_name` varchar(16) NOT NULL,
				`data` longtext
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";

		if(Db::getInstance()->execute($sql)){
			return Db::getInstance()->execute("INSERT INTO `{$tableName}` (list_name) VALUES ('category'), ('supplier'), ('index');");
		}

		return false;
	}
}
