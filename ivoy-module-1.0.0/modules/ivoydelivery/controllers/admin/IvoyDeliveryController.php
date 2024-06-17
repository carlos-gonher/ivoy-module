<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(_PS_MODULE_DIR_.'ivoydelivery/classes/ivoyorders.php');

class IvoyDeliveryController extends ModuleAdminController
{

    private $_order_data;
    private $ivoyOrdersModel;
    
    public function __construct() {
        
            $this->ivoyOrdersModel = new IvoyOrdersModel();
                
            $this->module = 'ivoydelivery';
            $this->lang = false;
            $this->explicitSelect = false;
            $this->context = Context::getContext();
            $this->bootstrap = true;

            $this->table = 'ivoy_orders';
            $this->className = 'IvoyOrdersModel';
            $this->identifier = "id_order";
            $this->orderBy = 'id_order';
            $this->_orderWay = 'DESC';
            $this->actions = ['edit', 'delete'];
                    
            $this->fields_list = array(
                'id_order' => array(
                    'title' => $this->l('ID'),
                    'callback' => 'setId',
                    'align' => 'center',
                    'class' => 'fixed-width-xs',
                ),
                'id_ivoy_order' => array(
                    'title' => $this->l('iVoy Order ID'),
                    'search' => false,
                    'orderby' => false,
                    'align' => 'center',
                ),
                'id_cart' => array(
                    'title' => $this->l('id_cart'),
                    'search' => true,
                    'orderby' => false,
                    'align' => 'center',
                ),
                'id_customer' => array(
                    'title' => $this->l('Cliente'),
                    'callback' => 'callClientName',
                    'orderby' => false,
                    'align' => 'center',
                ),
                'distance' => array(
                    'title' => $this->l('Distancia (Km)'),
                    'search' => false,
                    'orderby' => false,
                    'align' => 'center',
                    'filter_key' => 'id_special_price'
                ),
                'discount' => array(
                    'title' => $this->l('Descuento'),
                    'search' => false,
                    'width' => 'auto'
                ),
                'total' => array(
                    'title' => $this->l('Total'),
                    'search' => false,
                    'width' => 'auto'
                ),
                'store_name' => array(
                    'title' => $this->l('Tienda origen'),
                    'search' => false,
                    'width' => 'auto'
                ),
                'zone_name' => array(
                    'title' => $this->l('Destino'),
                    'callback' => 'callDestination',
                    'search' => false,
                    'width' => 'auto'
                ),
                'date_add' => array(
                    'title' => $this->l('date_add: '),
                    'search' => false,
                    'align' => 'center'
                ),
            );            

            parent::__construct();
    
    }
    
    public function setId($id_order){
        $this->ivoyOrdersModel->setId($id_order);
        $this->_order_data = $this->ivoyOrdersModel->loadData();
        return $this->ivoyOrdersModel->getId();
    }
    
    public function callClientName(){
        return $this->_order_data['addr_person_approved'];
    }
    
    public function callDestination(){
        $int = ($this->_order_data['addr_int_num'] != 0) ? 'int '.$this->_order_data['addr_int_num'].' ' : ' ';
        $addr = $this->_order_data['addr_street'].' '.$this->_order_data['addr_ext_num'].' '.$int.$this->_order_data['addr_neighborhood'].' CP: '.$this->_order_data['addr_zip_code'];
        return $addr;
    }
    
    public function initContent() {
        parent::initContent();
    }
    
    public function display() {
        parent::display();
    }

    public function ajaxProcessCreateIvoyOrder(){
        
        $data = array();
        
        include_once(_PS_MODULE_DIR_.'ivoydelivery/classes/ivoyrequests.php');
        include_once(_PS_MODULE_DIR_.'ivoydelivery/classes/ivoylogin.php');
        include_once(_PS_MODULE_DIR_.'ivoydelivery/classes/ivoyorder.php');
        include_once(_PS_MODULE_DIR_.'ivoydelivery/classes/ivoyorders.php');
        
        $ivoy = new IvoyRequestModel();
        $ivoyrequest = $ivoy->getRequestById(Tools::getValue('id_request'));
        
        $login = new IvoyLogin();
        $ivoylogin = $login->callApi();
        
        $data['ivoyrequest'] = $ivoyrequest;
        $data['ivoylogin'] = $ivoylogin;
        
        if(!empty($ivoylogin)){
            $ivoyorder = new IvoyOrder();
            $ivoyorder->initData($data);
            $newivoyorder = $ivoyorder->callApi();
            
            if(!empty($newivoyorder)){
                $ivoyorders = new IvoyOrdersModel();
                $id_order = $ivoyorders->createOrderFromRequest($newivoyorder, $ivoyrequest);
                if(!empty($id_order)){
                    $dborder = $ivoyorders->displayIvoyOrder($id_order);
                    die(json_encode(array('ivoyorder'=>$dborder)));
                }else{
                    die(json_encode(array('error'=>'dbordererror')));
                }
            }else{
                die(json_encode(array('error'=>'ordererror')));
            }
            
        }else{
            die(json_encode(array('error'=>'loginerror')));
        }
        
        //die('Hasta aqui...');

    }

}