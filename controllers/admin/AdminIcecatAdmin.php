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

class AdminIcecatAdminController extends ModuleAdminController
{
	public function __construct()
	{
		parent::__construct();
		// Do your stuff here
	}

	public function renderList()
	{
		$limit = 10;
		$results = Db::getInstance()->executeS('SELECT *
			FROM `'._DB_PREFIX_.'icecat_model` 
			LIMIT '.(int)$limit
		);
		$result = $results[0];
		$html = '<table class="table product mt-3">';
		foreach($results as $result){
			$html .= '<tr>'.'<td>'.$result['my_field_1'].'</td>'.'<td>'.$result['my_field_2'].'</td>'.'</tr>';
		}
		$html .= '</table>'.'<p>'.Configuration::get('ICECAT_UPDATE_URL').'</p>';
		$list = parent::renderList();
		
		// You can create your custom HTML with smarty or whatever then concatenate your list to it and serve it however you want !
		return $html . $list;
	}
}
