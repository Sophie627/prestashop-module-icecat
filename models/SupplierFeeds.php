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

class SupplierFeedsModel extends ObjectModel
{
	/** Your fields names, adapt to your needs */
	public $id;
	public $meta_title;
	public $url;
	public $extension;
	public $username;
	public $password;
	public $interval;
	public $last_execution;
	public $header_exists;
	public $table_created;
	public $delimiter;
	public $fields;

	/** Your table definition, adapt to your needs */
	public static $definition = [
		'table' => 'supplier_feeds',
		'primary' => 'id_supplier',
		'fields' => [
			'meta_title' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 256,
			],
			'url' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 256,
				'required' => true,
			],
			'extension' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 3,
			],
			'username' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 256,
			],
			'password' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 256,
			],
			'interval' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 32,
				'required' => true,
			],
			'last_execution' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 32,
			],
			'header_exists' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 1,
			],
			'table_created' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 1,
			],
			'delimiter' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 5,
			],
			'fields' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 1024,
			],
			'brand_map' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 1024,
			],
			'category_map' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 1024,
			],
			'price_map' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 1024,
			],
			'model_map' => [
				'type' => self::TYPE_STRING,
				'validate' => 'isString',
				'size' => 1024,
			],
		],
	];

	/** Create your table into database, adapt to your needs */
	public static function installSql()
	{
		$tableName = _DB_PREFIX_ . self::$definition['table'];
		$primaryField = self::$definition['primary'];

		$sql = "
			CREATE TABLE IF NOT EXISTS `{$tableName}` (
				`{$primaryField}` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`meta_title` varchar(256),
				`url` varchar(256) NOT NULL,
				`extension` varchar(3),
				`username` varchar(256),
				`password` varchar(256),
				`interval` varchar(32),
				`last_execution` int(11),
				`header_exists` varchar(1),
				`delimiter` varchar(5),
				`fields` varchar(1024),
				`table_created` varchar(1),
				PRIMARY KEY (`{$primaryField}`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";

		return Db::getInstance()->execute($sql);
	}
}
