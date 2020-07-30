<?php
# /modules/icecat/controllers/admin/AdminIcecatAdmin.php

/**
 * Icecat integration - A Prestashop Module
 * 
 * Icecat integration
 * 
 * @author Hendry Raudsepp <hraudsepp10@gmail.com>
 * @version 0.0.1
 */

if ( !defined('_PS_VERSION_') ) exit;

// You can now access this controller from /your_admin_directory/index.php?controller=AdminIcecatAdmin

class SupplierFeedAdminController extends ModuleAdminController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function postProcess()
	{
		if (Tools::isSubmit('submit'))
		{
			$supplier_meta_title = Tools::getValue('supplier_meta_title');
			$cron_url = Tools::getValue('cron_url');
			$extension = Tools::getValue('extension');
			$username = Tools::getValue('username');
			$password = Tools::getValue('password');
			$cron_interval = Tools::getValue('cron_interval');
			$header_exists = Tools::getValue('header_exists');
			$delimiter = Tools::getValue('delimiter');
			$defined_fields = Tools::getValue('defined_fields');
			$table_created = Tools::getValue('table_created');
			$header_exists = ($header_exists == "true")?"1":"0";
			$table_created = ($table_created == "true")?"1":"0";
			$fields = implode(',', $defined_fields);
			if($table_created == "1" && $supplier_meta_title != "") {
				if(!$this->createTable($supplier_meta_title, $defined_fields)) die('Error create table.');
			}
			// die(var_dump($defined_fields));
			$sql = " INSERT INTO `"._DB_PREFIX_."supplier_feeds` (`meta_title`, `url`, `extension`,`username`,`password`,`interval`, `header_exists`, `delimiter`, `table_created`, `fields`)  VALUES('{$supplier_meta_title}' ,'{$cron_url}', '{$extension}','{$username}','{$password}','{$cron_interval}', '{$header_exists}', '{$delimiter}', '{$table_created}', '{$fields}');";

			if (!Db::getInstance()->execute($sql)) die('Error supplier add.');
		}
		if (Tools::getValue('delete'))
		{
			Db::getInstance()->delete('supplier_feeds', 'id_supplier='.Tools::getValue('delete'));
		}
		
		if (Tools::isSubmit('check'))
		{
			$field_list = Db::getInstance()->executeS('SELECT field_name
				FROM `'._DB_PREFIX_.'feed_fields`'
			);
			$username = Tools::getValue('username');
			$password = Tools::getValue('password');
			$this->context->smarty->assign([
				'feed_fields' => $this->readTheFile(Tools::getValue('cron_url'), $username, $password),
				'cron_url' => Tools::getValue('cron_url'),
				'username' => $username,
				'password' => $password,
				'cron_interval' => Tools::getValue('cron_interval'),
				'header_exists' => Tools::getValue('header_exists'),
				'table_created' => Tools::getValue('table_created'),
				'defined_fields' => Tools::getValue('defined_fields'),
				'field_list' => $field_list,
			]);
		}
	}

	protected function readTheFile($path, $username, $password) 
	{
		$extension = pathinfo($path, PATHINFO_EXTENSION);
		$supplier_meta_title = Tools::getValue('supplier_meta_title');
		if(!$supplier_meta_title) $supplier_meta_title = pathinfo($path, PATHINFO_FILENAME);
		// $url = parse_url($path, PHP_URL_SCHEME)."://".parse_url($path, PHP_URL_HOST).parse_url($path, PHP_URL_PATH);
		$ch = curl_init($path);
		// curl_setopt($ch, CURLOPT_VERBOSE, true);
		if(parse_url($path, PHP_URL_PORT)>0)
			curl_setopt($ch, CURLOPT_PORT, parse_url($path, PHP_URL_PORT));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

		if($username!="" && $password!="")
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
		
		$data = curl_exec($ch);
		// $size = curl_getinfo($ch, CURLINFO_HTTP_CODE );
		$tempfile = "temp";
		$fh = fopen($tempfile, "w");
		fwrite($fh, $data, 4096);
		fclose($fh);
		curl_close($ch);
		if(strtolower($extension) == "zip"){
			$zip = new ZipArchive();
			$zip->open($tempfile);
			$data = $zip->getFromIndex(0);
			$tempfile1 = "temp1";
			$fh = fopen($tempfile1, "w");
			fwrite($fh, $data);
			fclose($fh);
			$zip->close();
			$tempfile = $tempfile1;
		} 
		$feed_fields = [];
		$handle = fopen($tempfile, "r");

		if($line = trim(fgets($handle))){
			fseek($handle, 0);
			$delimiter = Tools::getValue('delimiter');
			if($delimiter == "\\t") $delimiter = "\t";
			if(!$delimiter){
				// $fields_space = explode(" ", $line);
				$fields_tab = explode("\t", $line);
				$fields_semicolon = explode(";", $line);
				$fields_comma = explode(",", $line);
				$delimiter = "\t";
				$fields_count = count($fields_tab);
				// if($fields_count<count($fields_space)){
				// 	$delimiter = " ";
				// 	$fields_count = count($fields_space);
				// } 
				if($fields_count<count($fields_semicolon)){
					$delimiter = ";";
					$fields_count = count($fields_semicolon);
				}
				if($fields_count<count($fields_comma)){
					$delimiter = ",";
					$fields_count = count($fields_comma);
				}
			}
			$count = 0;
			while (($feed_field = fgetcsv($handle, 0, $delimiter)) !== false) {
				foreach($feed_field as $key => $field)
					if(strlen($field)>0 && substr($field,0,1) == '"' && substr($field, strlen($field)-1, 1) == '"') 
						$feed_field[$key] = substr($field, 1, strlen($field)-2);
				$feed_fields[] = $feed_field;
				$count++;
				if($count>2)break;
			}
			if($delimiter == "\t") $delimiter = "\\t";
			$this->context->smarty->assign([
				'delimiter' => $delimiter,
				'extension' => $extension,
				'supplier_meta_title' => $supplier_meta_title,
			]);
		}
		fclose($handle);
		return $feed_fields;
	}

	public static function createTable($supplier_meta_title, $defined_fields)
	{
		$tableName = _DB_PREFIX_ . 'supplier_feed_' . $supplier_meta_title;
		$primaryField = 'id_feed_product';

		$table_fields = "";
		foreach($defined_fields as $defined_field){
			$table_fields .= "`{$defined_field}` varchar(256), ";
		}
		$table_fields .= "`added` varchar(1), ";
		$table_fields .= "`edited` varchar(1), ";

		$sql = "
			CREATE TABLE IF NOT EXISTS `{$tableName}` (
				`{$primaryField}` int(10) unsigned NOT NULL AUTO_INCREMENT,
				{$table_fields}
				PRIMARY KEY (`{$primaryField}`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		";

		return Db::getInstance()->execute($sql);
	}

	public function initContent()
	{
		parent::initContent();
		$results = Db::getInstance()->executeS('SELECT *
			FROM `'._DB_PREFIX_.'supplier_feeds`'
		);
		$this->context->smarty->assign([
			'cronjobs' => $results,
			'self_link' => Context::getContext()->link->getAdminLink('SupplierFeedAdmin'),
		]);
		$this->setTemplate('supplier_feed.tpl');
	}
}