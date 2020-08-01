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

class PriceMappingAdminController extends ModuleAdminController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function postProcess()
	{
		if(Tools::isSubmit('submit'))
		{
			$username = Tools::getValue('username');
			$password = Tools::getValue('password');
			// $index_file = str_replace("\\","/",Tools::getValue('index_file'));
			// Db::getInstance()->execute("LOAD DATA LOCAL INFILE '$index_file' INTO TABLE ps_icecat_brands;");
			Configuration::updateValue('ICECAT_LOGIN',$username);
			Configuration::updateValue('ICECAT_PASSWORD',$password);
		}

		return parent::postProcess();
	}

	public function ajaxProcessGetAllSupplierData()
	{

		$suppliers = Db::getInstance()->executeS("SELECT * FROM `" . _DB_PREFIX_ . "supplier_feeds`");
		$supplier_data = [];

        foreach ($suppliers as $supplier) {
            $tableName = _DB_PREFIX_ . 'supplier_feed_' . $supplier['meta_title'];
            $supplier_data = array_merge($supplier_data, Db::getInstance()->executeS("SELECT `manufacturer`, `model`, `category` FROM `$tableName`"));
        }

        $supplier_models = array_unique(array_column($supplier_data, 'model'));
        $supplier_brands = array_unique(array_column($supplier_data, 'manufacturer'));
        $supplier_categories = array_unique(array_column($supplier_data, 'category'));

		$response = array(
			'status' => true,
			'supplier_brands' => $supplier_brands,
			'supplier_categories' => $supplier_categories,
			'supplier_models' => $supplier_models,
		);
		
		die(json_encode($response));
	}

	public function initContent()
	{
		parent::initContent();
		
		$suppliers = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'supplier_feeds`');
//		$get_brands =Db::getInstance()->executeS('SELECT data
//			FROM `'._DB_PREFIX_.'icecat` WHERE list_name=\'supplier\''
//		);
//		$brands = json_decode($get_brands[0]["data"], true);
//		$categories =Db::getInstance()->executeS('SELECT data
//			FROM `'._DB_PREFIX_.'icecat` WHERE list_name=\'category\''
//		);
//		$categories = json_decode($categories[0]["data"]);
		// var_dump($get_brands[0]["data"]);
		$link = new LinkCore;
		$_path = str_replace("/module/", "/modules/", Context::getContext()->link->getModuleLink('icecat', 'views'));
		$_path = str_replace("/en/", "/", $_path);
		$this->context->controller->addCSS('https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css');
        $this->context->controller->addCSS('/admin023pexgcs/themes/new-theme/public/theme.css');
		$this->context->controller->addCSS($_path . '/css/style.css', 'all');
		$this->context->controller->addJS($_path . '/js/priceMapping.js');
		$this->context->controller->addJS($_path . '/js/clusterize.min.js');
		$this->context->smarty->assign([
			'mapping_ajax_link' => $this->context->link->getAdminLink('PriceMappingAdmin'),
			'suppliers' => $suppliers,
//			'brands' => $brands,
//			'categories' => json_encode($categories->subs),
		]);
		$this->setTemplate('price_mapping.tpl');

	}
}
