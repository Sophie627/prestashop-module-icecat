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

class IcecatAdminController extends ModuleAdminController
{
	public function __construct()
	{
		parent::__construct();
		// Do your stuff here
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
	}

	public function initContent()
	{
		parent::initContent();
		
		$this->context->smarty->assign([
			'username' => Configuration::get('ICECAT_LOGIN'),
			'password' => Configuration::get('ICECAT_PASSWORD'),
		]);
		$this->setTemplate('icecat.tpl');
	}
}
