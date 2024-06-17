<?php

class IvoyRequestModel extends ObjectModel {
    
    public $code;
    public $distance;
    public $discount;
    public $extra_charge;
    public $package_value;
    public $price;
    public $eta;
    public $rating;
    public $total;
    public $messenger_fee;
    public $ivoy_cost;
    public $is_bike;
    public $message;
    public $id_zone;
    public $zone_description;
    public $zone_name;
    public $id_order_type;
    public $id_package_type;
    public $id_payment_status;
    public $store_id;
    public $store_name;
    public $addr_is_pickup;
    public $addr_is_source;
    public $addr_person_approved;
    public $addr_comment;
    public $addr_phone;
    public $addr_price_business;
    public $addr_tip;
    public $addr_distance;
    public $addr_eta;
    public $addr_price;
    public $addr_ext_num;
    public $addr_int_num;
    public $addr_lat;
    public $addr_lng;
    public $addr_neighborhood;
    public $addr_street;
    public $addr_zip_code;
    public $id_vehicle;
    public $vehicle_description;
    public $vehicle_name;
    public $vehicle_zones;
    public $promo_code_balance;
    public $referral_balance;
    public $prepaid_balance;
    public $credit_balance;
    public $others_balance;
    public $revenue_hot_zone;
    public $token;
    public $errors;
    
    public static $definition = array(
        'table' => 'ivoy_requests',
        'id_request' => 'id_special_price',
        'fields' => array(
            'id_ivoy_order' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'device' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'discount' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'distance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'extra_charge' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'order_child_count' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'package_value' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'price' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'eta' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'rating' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'total' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'messenger_fee' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'ivoy_cost' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'is_bike' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_client' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'client_email' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'client_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'client_firstName' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'client_lastName' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_zone' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'zone_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'zone_description' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_order_status' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'order_status_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'order_status_description' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_order_type' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'order_type_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'order_type_description' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'order_type_zones' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_package_type' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'package_type_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'package_type_description' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'package_type_zones' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'store_id' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'store_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_is_pickup' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_is_source' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_person_approved' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_comment' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_phone' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_price_business' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_tip' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_distance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_eta' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_price' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_ext_num' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_int_num' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_lat' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_lng' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_neighborhood' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_street' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'addr_zip_code' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'promo_code_balance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'referral_balance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'prepaid_balance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'credit_balance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_vehicle' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'vehicle_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'vehicle_description' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'revenue_hot_zone' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
        ),
    );
    
    public function __construct() {
        
    }
    
    public function setId($id){
        $this->id_request = $id;
    }
    
    public function getId(){
        return $this->id_request;
    }

    public function loadData(){

        $sql = 'SELECT * FROM '._DB_PREFIX_.'ivoy_requests WHERE id_request = '.$this->id_request;
        return DB::getInstance()->getRow($sql);
    }

    public function getLastRequest($id_cart, $id_customer){
        
        $sql = 'SELECT id_request, id_cart, id_carrier, price, total FROM '._DB_PREFIX_.'ivoy_requests WHERE id_cart = '.$id_cart.' AND id_customer = '.$id_customer.' ORDER BY id_request DESC LIMIT 1';
        $result = Db::getInstance()->ExecuteS($sql);
        
        if(!empty($result[0])){
            return $result[0];
        }else{
            return false;
        }
    }
    
    public function getRequestInOrder($id_order){

        $sql = 'SELECT * FROM '._DB_PREFIX_.'ivoy_requests WHERE id_order = '.$id_order.' ORDER BY id_request DESC LIMIT 1';
        $result = Db::getInstance()->ExecuteS($sql);
        
        if(!empty($result[0])){
            return $result[0];
        }else{
            return false;
        }
    }

    public function updateLastRequest($id_order, $id_request){
        
        $sql = 'UPDATE '._DB_PREFIX_.'ivoy_requests SET id_order = '.$id_order.' WHERE id_request = '.$id_request;
        Db::getInstance()->executeS($sql);
    }
    
    public function deleteTrashRequests($id_cart, $id_customer){
        
        $sql = 'DELETE FROM '._DB_PREFIX_.'ivoy_requests WHERE id_cart = '.$id_cart.' AND id_customer = '.$id_customer.' AND id_order = 0';
        Db::getInstance()->execute($sql);
    }
    
    public function getRequestById($id_request){
        
        $sql = 'SELECT * FROM '._DB_PREFIX_.'ivoy_requests WHERE id_request = '.$id_request;
        return Db::getInstance()->getRow($sql);
    }
    
    public function createOrderFromRequest($id_request){
        
        $ivoyrequest = $this->getRequestById($id_request);
        
    }

}
