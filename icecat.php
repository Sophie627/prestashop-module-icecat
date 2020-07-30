<?php
# /modules/icecat/icecat.php

/**
 * Icecat integration - A Prestashop Module
 * 
 * Icecat integration
 * 
 * @author Hendry Raudsepp <hraudsepp10@gmail.com>
 * @version 0.0.1
 */

if ( !defined('_PS_VERSION_') ) exit;

// We look for our model since we want to install it's SQL from the module install
require_once(__DIR__ . '/models/SupplierFeeds.php');
require_once(__DIR__ . '/models/FeedFields.php');
require_once(__DIR__ . '/models/Icecat.php');

class Icecat extends Module
{
	const DEFAULT_CONFIGURATION = [
		'ICECAT_FULLINDEX_LOADED' => '',
		'ICECAT_SUPPLIERSLIST_LOADED' => '',
		'ICECAT_CATEGORIESLIST_LOADED' => '',
		'ICECAT_LOGIN' => '',
		'ICECAT_PASSWORD' => ''
	];

	protected $tabs = [
		['AdminIcecatAdmin', 'DEFAULT', 'Icecat & Feeds'],
		['IcecatAdmin', 'AdminIcecatAdmin', 'Icecat'],
		['SupplierFeedAdmin', 'AdminIcecatAdmin', 'Supplier feeds'],
		['FeedIcecatMappingAdmin', 'AdminIcecatAdmin', 'Mapping'],
		['BrandMappingAdmin', 'FeedIcecatMappingAdmin', 'Brands'],
		['CategoryMappingAdmin', 'FeedIcecatMappingAdmin', 'Categories'],
		['PriceMappingAdmin', 'FeedIcecatMappingAdmin', 'Price'],
		['CorrectMappingAdmin', 'FeedIcecatMappingAdmin', 'Correct'],
	];

	public function __construct()
	{
		$this->initializeModule();
	}

	public function install()
	{
		return
			parent::install()
			&& $this->installTabs()
			&& $this->initDefaultConfigurationValues()
			&& SupplierFeedsModel::installSql()
			&& FeedFieldsModel::installSql()
			&& IcecatModel::installSql()
		;
	}

	public function uninstall()
	{
		return
			parent::uninstall()
			&& $this->uninstallTabs()
		;
	}
	
	public function getContent()
	{
		return null;
	}

	/** Initialize the module declaration */
	private function initializeModule()
	{
		$this->name = 'icecat';
		$this->tab = 'administration';
		$this->version = '0.0.1';
		$this->author = 'Hendry Raudsepp';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = [
			'min' => '1.7',
			'max' => _PS_VERSION_,
		];
		$this->bootstrap = true;
		
		parent::__construct();

		$this->displayName = $this->l('Icecat integration');
		$this->description = $this->l('Icecat integration');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall this module ?');
	}

	private function initDefaultConfigurationValues()
	{
		foreach ( self::DEFAULT_CONFIGURATION as $key => $value )
		{
			if ( !Configuration::get($key) )
			{
				Configuration::updateValue($key, $value);
			}
		}

		return true;
	}

	private function installTabs()
	{
		foreach($this->tabs as $tab)
			if(!$this->installTab($tab[0], $tab[1], $tab[2])) return false;
		
		return true;
	}

	private function uninstallTabs()
	{
		foreach($this->tabs as $tab)
			if(!$this->uninstallTab($tab[0])) return false;
		
		return true;
	}
	
	private function installTab($class_name, $parent_class, $tab_name)
	{
		$languages = Language::getLanguages();
		
		$tab = new Tab();
		$tab->class_name = $class_name;
		$tab->module = $this->name;
		$tab->id_parent = (int) Tab::getIdFromClassName($parent_class);

		foreach ( $languages as $lang )
		{
			$tab->name[$lang['id_lang']] = $tab_name;
		}

		try
		{
			$tab->save();
		}
		catch ( Exception $e )
		{
			return false;
		}

		return true;
	}

	/** Uninstall module tab */
	private function uninstallTab($class_name)
	{
		$tab = (int) Tab::getIdFromClassName($class_name);

		if ( $tab )
		{
			$mainTab = new Tab($tab);
			
			try
			{
				$mainTab->delete();
			}
			catch ( Exception $e )
			{
				echo $e->getMessage();
				return false;
			}
		}

		return true;
	}

}
