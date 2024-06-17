<?php

if (!defined('_PS_VERSION_'))
  exit;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class IvoyDelivery extends Module
{
    
    public function __construct(){
        $this->name = 'ivoydelivery';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Vidafull DevTeam';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
        $this->bootstrap = true;
                    $this->error = false;
                    $this->valid = false;


        parent::__construct();

        $this->displayName = $this->l('Ivoy');
        $this->description = $this->l('Ivoy Delivery Module');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall iVoy?');
      
        if (!Configuration::get('IVOY_DELIVERY')){
            $this->warning = $this->l('Not ivoy module provided');
        }
        
    }
    
    public function install(){
        
        $createtable = $this->createTableQueries();
        
        $create_requests = $this->createTable($createtable['requests']);
        $create_orders = $this->createTable($createtable['orders']);
        $create_login = $this->createTable($createtable['login']);
        
        if (!parent::install() || 
            !$create_requests || 
            !$create_orders || 
            !$this->registerHook('orderConfirmation') ||
            !$this->registerHook('displayAdminOrderTabShip') ||
            !$this->installTab()){
            return false;
        }
        
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }        
        return true;
    }
    
    public function uninstall(){
        
        $deletetable = $this->deleteTableQueries();
        
        $delete_requests = $this->deleteTable($deletetable['requests']);
        $delete_orders = $this->deleteTable($deletetable['orders']);
        $delete_login = $this->deleteTable($deletetable['login']);
        
        if (!parent::uninstall() || !$delete_requests || !$delete_orders || !$delete_login){
            return false;
        }
        return true;
    }

    public function createTable($sql){

        if(!Db::getInstance()->execute($sql)){
            return false;
        }
        return true;
    }
    
    public function deleteTable($sql){

        if(!Db::getInstance()->execute($sql)){
            return false;
        }
        return true;
    }
    
    private function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'IvoyDelivery';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang){
          $tab->name[$lang['id_lang']] = 'Ivoy Delivery';
        }
        $tab->id_parent = 0;
        $tab->module = $this->name;
        $return = $tab->add();
        if(!$return){
          $this->_errors[] = Tools::displayError('Algo irregular ha sucedido con la colocación del IvoyDelivery.');
        }
        return $return;
    }
    
    public function hookOrderConfirmation($params){
 
        include_once(_PS_MODULE_DIR_.'ivoydelivery'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'ivoyrequests.php');
        
        $ivoyrequests = new IvoyRequestModel();
        
        $order = $params['objOrder'];
        $id_cart = $order->id_cart;
        $id_customer = $order->id_customer;
        $order_id = $order->id;
        $carrier_id = $order->id_carrier;
        $ivoy = array();
        
        $lastRequest = $ivoyrequests->getLastRequest($id_cart, $id_customer);
        
        if(!empty($lastRequest)){
            
            if($carrier_id == $lastRequest['id_carrier']){
                $ivoyrequests->updateLastRequest($order_id, $lastRequest['id_request']);
                $ivoyrequests->deleteTrashRequests($id_cart, $id_customer);
                $this->updateOrderShipping($order, $lastRequest);
            }else{
                $ivoyrequests->deleteTrashRequests($id_cart, $id_customer);
            }
        }

    }
    
    public function hookDisplayAdminOrderTabShip(array $params)
    {

        $order = isset($params['order']) ? $params['order'] : null;
        $customer = isset($params['customer']) ? $params['customer'] : null;
        
        include_once(_PS_MODULE_DIR_.'ivoydelivery'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'ivoyrequests.php');
        include_once(_PS_MODULE_DIR_.'ivoydelivery'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'ivoyorders.php');
        
        $ivoyrequest = new IvoyRequestModel();
        $ivoyorders = new IvoyOrdersModel();
        $request = $ivoyrequest->getRequestInOrder($order->id);
        $ivoyorder = $ivoyorders->confirmIvoyOrder($order->id, $order->id_cart);
        $module_url = $this->context->link->getAdminLink('IvoyDelivery');
        
        $this->smarty->assign(
            array(
            'ivoyrequests' => $request,
            'ivoyorder' => $ivoyorder,
            'ordercustomer' => $customer,
            'base_url' => _PS_BASE_URL_SSL_,
            'module_url' => $module_url,
            )
        );
        
        $this->context->controller->addJS($this->_path.'views/js/ivoy-in-order.js');
        return $this->display(__FILE__, 'views/templates/hook/admin-order-tab-ivoy.tpl');
    
    }
    
    private function updateOrderShipping($order, $ivoy_request){
        
        $totals = $this->addIvoyShippingl($order, $ivoy_request);
        
        $order->total_paid = $totals['total_paid'];
        $order->total_paid_real = $totals['total_paid_real'];
        $order->total_paid_tax_incl = $totals['total_paid_tax_incl'];
        $order->total_paid_tax_excl = $totals['total_paid_tax_excl'];
        $order->total_shipping = $totals['total_shipping'];
        $order->total_shipping_tax_incl = $totals['total_shipping_tax_incl'];
        $order->total_shipping_tax_excl = $totals['total_shipping_tax_excl'];
        $order->save();
    }
    
    private function addIvoyShippingl($order, $ivoy_request){
        
        $totals = array();
        $tax_rate = $order->getOrderTaxRate($order->id);
        
        $totals['total_paid'] = ($order->total_paid + $ivoy_request['price']);
        $totals['total_paid_real'] = ($order->total_paid + $ivoy_request['price']);
        $totals['total_paid_tax_incl'] = ($order->total_paid_tax_incl + $ivoy_request['price']);
        $totals['total_paid_tax_excl'] = ($this->subtractTax($order->total_paid, $tax_rate) + $this->subtractTax($ivoy_request['price'], $tax_rate));
        $totals['total_shipping'] = $ivoy_request['price'];
        $totals['total_shipping_tax_incl'] = $ivoy_request['price'];
        $totals['total_shipping_tax_excl'] = $this->subtractTax($ivoy_request['price'], $tax_rate);
        return $totals;
    }
    
    private function subtractTax($amount, $tax_rate){
        $tax =  (($amount * $tax_rate)/100);
        return ($amount - $tax);
    }
    
    public function deleteTableQueries(){
        $tables = array();
        $tables['requests'] = "DROP TABLE IF EXISTS `ivoy_requests`";
        $tables['orders'] = "DROP TABLE IF EXISTS `ivoy_orders`";
        $tables['login'] = "DROP TABLE IF EXISTS `ivoy_login`";
        return $tables;
    }
    
    public function createTableQueries(){
        
        $tables = array();
        
        $tables['requests'] = "CREATE TABLE `ivoy_requests` ( " 
	."`id_request` int(11) unsigned NOT NULL AUTO_INCREMENT, "
        ."`id_cart` int(11) unsigned NOT NULL DEFAULT '0', " 
        ."`id_customer` int(11) unsigned NOT NULL DEFAULT '0', " 
        ."`id_order` int(11) unsigned NOT NULL DEFAULT '0', "
        ."`id_carrier` int(11) unsigned NOT NULL DEFAULT '0', "
	."`code` int(11) unsigned NOT NULL DEFAULT '0', " 
	."`distance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`discount` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`extra_charge` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`package_value` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`price` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`eta` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`rating` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`total` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`messenger_fee` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`ivoy_cost` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`is_bike` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`id_zone` int(11) unsigned NOT NULL DEFAULT '0', " 
	."`zone_description` varchar(100) NOT NULL DEFAULT '', " 
	."`zone_name` varchar(50) NOT NULL DEFAULT '', " 
	."`id_order_type` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
        ."`order_type_name` varchar(50) NOT NULL DEFAULT '', "
	."`id_package_type` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`id_payment_status` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
        ."`payment_status_name` varchar(50) NOT NULL DEFAULT '', "
	."`store_id` int(11) unsigned NOT NULL DEFAULT '0', " 
	."`store_name` varchar(50) NOT NULL DEFAULT '', " 
	."`addr_is_pickup` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`addr_is_source` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`addr_person_approved` varchar(100) NOT NULL DEFAULT '', " 
	."`addr_comment` varchar(255) NOT NULL DEFAULT '', " 
	."`addr_phone` varchar(50) NOT NULL DEFAULT '', " 
	."`addr_price_business` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`addr_tip` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`addr_distance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`addr_eta` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`addr_price` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`addr_ext_num` varchar(50) NOT NULL DEFAULT '', " 
	."`addr_int_num` smallint(11) unsigned NOT NULL DEFAULT '0', " 
	."`addr_lat` decimal(15,12) SIGNED NOT NULL DEFAULT '0.000000', " 
	."`addr_lng` decimal(15,12) SIGNED NOT NULL DEFAULT '0.000000',  " 
	."`addr_neighborhood` varchar(255) NOT NULL DEFAULT '', " 
	."`addr_street` varchar(155) NOT NULL DEFAULT '', " 
	."`addr_zip_code` varchar(15) NOT NULL DEFAULT '', " 
	."`id_vehicle` int(11) unsigned NOT NULL DEFAULT '0', " 
	."`vehicle_description` varchar(255) NOT NULL DEFAULT '', " 
	."`vehicle_name` varchar(100) NOT NULL DEFAULT '', " 
	."`vehicle_zones` varchar(100) NOT NULL DEFAULT '', " 
	."`promo_code_balance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`referral_balance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`prepaid_balance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`credit_balance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`others_balance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`revenue_hot_zone` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
        ."`date_add` datetime NOT NULL, "
        ."`date_upd` datetime NOT NULL, "
	."PRIMARY KEY (`id_request`), " 
	."KEY `id_vehicle` (`id_vehicle`), "
        ."KEY `id_cart` (`id_cart`), "
        ."KEY `id_customer` (`id_customer`), "
        ."KEY `id_order` (`id_order`),"
	."KEY `id_carrier` (`id_carrier`) " 
        .") ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8";
        
        $tables['orders'] = "CREATE TABLE `ivoy_orders` ( " 
	."`id_order` int(11) unsigned NOT NULL AUTO_INCREMENT, " 
	."`id_ivoy_order` int(11) unsigned NOT NULL default '0', "
        ."`id_store_order` int(11) unsigned NOT NULL default '0', "
        ."`id_cart` int(11) unsigned NOT NULL DEFAULT '0', " 
        ."`id_customer` int(11) unsigned NOT NULL DEFAULT '0', "
        ."`id_carrier` int(11) unsigned NOT NULL DEFAULT '0', "
	."`device` varchar(50) NOT NULL DEFAULT '', " 
	."`discount` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`distance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`extra_charge` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`order_child_count` varchar(100) NOT NULL DEFAULT '', " 
	."`package_value` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`price` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`eta` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`rating` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`total` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`messenger_fee` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`ivoy_cost` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`is_bike` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`id_client` int(11) unsigned NOT NULL DEFAULT '0', " 
	."`client_email` varchar(100) NOT NULL DEFAULT '', " 
	."`client_name` varchar(100) NOT NULL DEFAULT '', " 
	."`client_firstName` varchar(100) NOT NULL DEFAULT '', " 
	."`client_lastName` varchar(100) NOT NULL DEFAULT '', " 
	."`id_zone` int(11) unsigned NOT NULL DEFAULT '0', " 
	."`zone_name` varchar(50) NOT NULL DEFAULT '', " 
	."`zone_description` varchar(100) NOT NULL DEFAULT '', " 
	."`id_order_status` int(11) unsigned NOT NULL DEFAULT '0', " 
	."`order_status_name` varchar(50) NOT NULL DEFAULT '', " 
	."`order_status_description` varchar(100) NOT NULL DEFAULT '', " 
	."`id_order_type` int(11) unsigned NOT NULL DEFAULT '0', " 
	."`order_type_name` varchar(50) NOT NULL DEFAULT '', " 
	."`order_type_description` varchar(100) NOT NULL DEFAULT '', " 
	."`order_type_zones` varchar(50) NOT NULL DEFAULT '', " 
	."`id_package_type` int(11) unsigned NOT NULL DEFAULT '0', "
	."`id_payment_method` int(11) unsigned NOT NULL DEFAULT '0', " 
	."`payment_name` varchar(50) NOT NULL DEFAULT '', " 
	."`payment_description` varchar(100) NOT NULL DEFAULT '', " 
	."`store_id` smallint(11) unsigned NOT NULL DEFAULT '0', " 
	."`store_name` varchar(70) NOT NULL DEFAULT '', " 
	."`addr_is_pickup` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`addr_is_source` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`addr_person_approved` varchar(100) NOT NULL DEFAULT '', " 
	."`addr_comment` varchar(255) NOT NULL DEFAULT '', " 
	."`addr_phone` varchar(50) NOT NULL DEFAULT '', " 
	."`addr_price_business` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`addr_tip` tinyint(1) unsigned NOT NULL DEFAULT '0', " 
	."`addr_distance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`addr_eta` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`addr_price` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`addr_ext_num` varchar(50) NOT NULL DEFAULT '', " 
	."`addr_int_num` smallint(11) unsigned NOT NULL DEFAULT '0', " 
	."`addr_lat` decimal(15,12) SIGNED NOT NULL DEFAULT '0.000000', " 
	."`addr_lng` decimal(15,12) SIGNED NOT NULL DEFAULT '0.000000',  " 
	."`addr_neighborhood` varchar(255) NOT NULL DEFAULT '', " 
	."`addr_street` varchar(155) NOT NULL DEFAULT '', " 
	."`addr_zip_code` varchar(15) NOT NULL DEFAULT '', " 
	."`promo_code_balance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`referral_balance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`prepaid_balance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`credit_balance` decimal(20,6) NOT NULL DEFAULT '0.000000', " 
	."`id_vehicle` int(11) unsigned NOT NULL DEFAULT '0', " 
	."`vehicle_name` varchar(50) NOT NULL DEFAULT '', " 
	."`vehicle_description` varchar(100) NOT NULL DEFAULT '', " 
	."`revenue_hot_zone` tinyint(1) unsigned NOT NULL DEFAULT '0', "
        ."`date_add` datetime NOT NULL, "
        ."`date_upd` datetime NOT NULL, "
	."PRIMARY KEY (`id_order`), " 
	."KEY `id_ivoy_order` (`id_ivoy_order`), " 
	."KEY `id_client` (`id_client`), "
        ."KEY `id_carrier` (`id_carrier`), " 
	."KEY `store_id` (`store_id`) " 
        .") ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ";
        
        $tables['login'] = "CREATE TABLE `ivoy_login` ( " 
	."`id_login` int(10) unsigned NOT NULL AUTO_INCREMENT, " 
	."`token` text, " 
	."`token_type` varchar(25) NOT NULL DEFAULT 'no', " 
	."`expires_in` DATETIME, " 
	."PRIMARY KEY (`id_login`) " 
        .")ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ";
        
        return $tables;
    }
    
    public function getContent()
    {
        $output = null;
        if(Tools::isSubmit('submit'.$this->name)){
            Configuration::updateValue('ivoy_user', Tools::getValue('ivoy_user'));
            Configuration::updateValue('ivoy_password', Tools::getValue('ivoy_password'));
            Configuration::updateValue('ivoy_validate', Tools::getValue('ivoy_validate'));
            Configuration::updateValue('ivoy_login', Tools::getValue('ivoy_login'));
            Configuration::updateValue('ivoy_new_order', Tools::getValue('ivoy_new_order'));
            Configuration::updateValue('ivoy_cancel_order', Tools::getValue('ivoy_cancel_order'));
            Configuration::updateValue('ivoy_by_id', Tools::getValue('ivoy_by_id'));
            
            $output .= $this->displayConfirmation($this->l('Datos actualizados'));
        }

        $output .= $this->displayForm();
        return $output;    
    }
    
    public function displayForm()
    {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('iVoy user'),
                    'name' => 'ivoy_user',
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('iVoy password'),
                    'name' => 'ivoy_password',
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Validate Service Url'),
                    'name' => 'ivoy_validate',
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Login Service Url'),
                    'name' => 'ivoy_login',
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('New Order Service Url'),
                    'name' => 'ivoy_new_order',
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Get order by ID'),
                    'name' => 'ivoy_by_id',
                    'size' => 20,
                    'required' => true
                ),                
                array(
                    'type' => 'text',
                    'label' => $this->l('Cancel Order Service Url'),
                    'name' => 'ivoy_cancel_order',
                    'size' => 20,
                    'required' => true
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['ivoy_user'] = Configuration::get('ivoy_user');
        $helper->fields_value['ivoy_password'] = Configuration::get('ivoy_password');
        $helper->fields_value['ivoy_validate'] = Configuration::get('ivoy_validate');
        $helper->fields_value['ivoy_login'] = Configuration::get('ivoy_login');
        $helper->fields_value['ivoy_new_order'] = Configuration::get('ivoy_new_order');
        $helper->fields_value['ivoy_cancel_order'] = Configuration::get('ivoy_cancel_order');
        $helper->fields_value['ivoy_by_id'] = Configuration::get('ivoy_by_id');

        return $helper->generateForm($fields_form);
    }
    
}