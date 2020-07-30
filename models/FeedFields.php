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

class FeedFieldsModel extends ObjectModel
{
	/** Your fields names, adapt to your needs */
	public $id;
	public $field_name;

	/** Your table definition, adapt to your needs */
	public static $definition = [
		'table' => 'feed_fields',
		'primary' => 'id_field',
		'fields' => [
			'field_name' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 32,
				'required' => true,
			]
		]
	];

	/** Create your table into database, adapt to your needs */
	public static function installSql()
	{
		$tableName = _DB_PREFIX_ . self::$definition['table'];
		$primaryField = self::$definition['primary'];

		$sql = "
			CREATE TABLE IF NOT EXISTS `{$tableName}` (
				`{$primaryField}` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`field_name` varchar(32) NOT NULL,
				PRIMARY KEY (`{$primaryField}`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";

		if(Db::getInstance()->execute($sql)){
			return Db::getInstance()->execute("INSERT INTO `{$tableName}` (field_name) VALUES ('manufacturer');") && Db::getInstance()->execute("INSERT INTO `{$tableName}` (field_name) VALUES ('model');") && Db::getInstance()->execute("INSERT INTO `{$tableName}` (field_name) VALUES ('price');") && Db::getInstance()->execute("INSERT INTO `{$tableName}` (field_name) VALUES ('stock_level');") && Db::getInstance()->execute("INSERT INTO `{$tableName}` (field_name) VALUES ('description');") && Db::getInstance()->execute("INSERT INTO `{$tableName}` (field_name) VALUES ('description_short');");
		}

		return false;
	}
}
