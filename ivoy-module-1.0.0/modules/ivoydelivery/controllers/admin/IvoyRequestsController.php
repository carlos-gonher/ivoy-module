<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(_PS_MODULE_DIR_.'ivoydelivery/classes/ivoyrequests.php');

class IvoyRequestsController extends ModuleAdminController
{
    
    private $_request_data;
    private $ivoyRequestsModel;
    
    public function __construct() {
        
            $this->ivoyRequestsModel = new IvoyRequestModel();
                
            $this->module = 'ivoydelivery';
            $this->lang = false;
            $this->explicitSelect = false;
            $this->context = Context::getContext();
            $this->bootstrap = true;

            $this->table = 'ivoy_requests';
            $this->className = 'IvoyRequestModel';
            $this->identifier = 'id_request';
            $this->orderBy = 'id_request';
            $this->_orderWay = 'DESC';
            $this->actions = ['edit', 'delete'];
            
            $this->fields_list = array(
                'id_request' => array(
                    'title' => $this->l('ID'),
                    'callback' => 'setId',
                    'align' => 'center',
                    'class' => 'fixed-width-xs',
                ),
                'id_order' => array(
                    'title' => $this->l('ID Order'),
                    'search' => true,
                    'orderby' => false,
                    'align' => 'center',
                ),
                'id_customer' => array(
                    'title' => $this->l('Cliente'),
                    'callback' => 'callClientName',
                    'search' => true,
                    'orderby' => false,
                    'align' => 'center',
                ),
                'distance' => array(
                    'title' => $this->l('Distancia'),
                    'search' => false,
                    'orderby' => false,
                    'align' => 'center',
                ),
                'discount' => array(
                    'title' => $this->l('Descuento'),
                    'search' => false,
                    'width' => 'auto'
                ),
                'is_bike' => array(
                    'title' => $this->l('is_bike'),
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

    public function setId($id_request){
        $this->ivoyRequestsModel->setId($id_request);
        $this->_request_data = $this->ivoyRequestsModel->loadData();
        return $this->ivoyRequestsModel->getId();
    }
    
    public function callClientName(){
        return $this->_request_data['addr_person_approved'];
    }
    
    public function callDestination(){
        $int = ($this->_request_data['addr_int_num'] != 0) ? 'int '.$this->_request_data['addr_int_num'].' ' : ' ';
        $addr = $this->_request_data['addr_street'].' '.$this->_request_data['addr_ext_num'].' '.$int.$this->_request_data['addr_neighborhood'].' CP: '.$this->_request_data['addr_zip_code'];
        return $addr;
    }
    
    public function initContent() {
        parent::initContent();
    }
    
    public function display() {
        parent::display();
    }    
}