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

class CategoryMappingAdminController extends ModuleAdminController
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

    public function ajaxProcessGetCategory()
    {
        $categories =Db::getInstance()->executeS('SELECT data
			FROM `'._DB_PREFIX_.'icecat` WHERE list_name=\'category\''
        );
        $categories = json_decode($categories[0]["data"]);

        $response = array(
            'status' => true,
            'categories' => $categories,
        );

        die(json_encode($response));
    }

    public function ajaxProcessGetSupplierCategory()
    {
        $id_supplier = Tools::getValue('id_supplier');

        $suppliers = Db::getInstance()->executeS("SELECT *
			FROM `"._DB_PREFIX_."supplier_feeds` WHERE `id_supplier`='$id_supplier'"
        );
        $supplier = $suppliers[0];

        $tableName = _DB_PREFIX_ . 'supplier_feed_' . $supplier['meta_title'];
        $supplier_categories = Db::getInstance()->executeS("SELECT category FROM `$tableName` GROUP BY category");

        $response = array(
            'status' => true,
            'headers' => $supplier['fields'],
            'supplier_categories' => $supplier_categories
        );

        die(json_encode($response));
    }

    public function initContent()
    {
        parent::initContent();

        $suppliers = Db::getInstance()->executeS('SELECT *
			FROM `'._DB_PREFIX_.'supplier_feeds`'
        );
        $get_brands =Db::getInstance()->executeS('SELECT data
			FROM `'._DB_PREFIX_.'icecat` WHERE list_name=\'supplier\''
        );
        $brands = json_decode($get_brands[0]["data"], true);
        $categories =Db::getInstance()->executeS('SELECT data
			FROM `'._DB_PREFIX_.'icecat` WHERE list_name=\'category\''
        );
        $categories = json_decode($categories[0]["data"]);
        // var_dump($get_brands[0]["data"]);
        $link = new LinkCore;
        $_path = str_replace("/module/", "/modules/", Context::getContext()->link->getModuleLink('icecat', 'views'));
        $_path = str_replace("/en/", "/", $_path);
        $this->context->controller->addCSS('https://cdn.materialdesignicons.com/5.0.45/css/materialdesignicons.min.css');
        $this->context->controller->addCSS('/admin023pexgcs/themes/new-theme/public/theme.css');
        $this->context->controller->addCSS($_path . '/css/style.css', 'all');
        $this->context->controller->addJS($_path . '/js/comboTreePlugin.js');
        $this->context->controller->addJS($_path . '/js/categoryMapping.js');
        $this->context->controller->addJS($_path . '/js/clusterize.min.js');
        $this->context->smarty->assign([
            'mapping_ajax_link' => $this->context->link->getAdminLink('CategoryMappingAdmin'),
            'suppliers' => $suppliers,
            'brands' => $brands,
            'categories' => json_encode($categories->subs),
        ]);
        $this->setTemplate('category_mapping.tpl');

    }
}
