<?php

class IvoyOrdersModel extends ObjectModel {
    
    public $id_ivoy_order;
    public $device;
    public $discount;
    public $distance;
    public $extra_charge;
    public $order_child_count;
    public $package_value;
    public $price;
    public $eta;
    public $rating;
    public $total;
    public $messenger_fee;
    public $ivoy_cost;
    public $is_bike;
    public $id_client;
    public $client_email;
    public $client_name;
    public $client_firstName;
    public $client_lastName;
    public $id_zone;
    public $zone_name;
    public $zone_description;
    public $id_order_status;
    public $order_status_name;
    public $order_status_description;
    public $id_order_type;
    public $order_type_name;
    public $order_type_description;
    public $order_type_zones;
    public $id_package_type;
    public $package_type_name;
    public $package_type_description;
    public $package_type_zones;
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
    public $promo_code_balance;
    public $referral_balance;
    public $prepaid_balance;
    public $credit_balance;
    public $id_vehicle;
    public $vehicle_name;
    public $vehicle_description;
    public $revenue_hot_zone;

    public static $definition = array(
        'table' => 'ivoy_orders',
        'primary' => 'id_order',
        'fields' => array(
            'code' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'distance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'discount' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'extra_charge' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'package_value' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'price' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'eta' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'rating' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'total' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'messenger_fee' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'ivoy_cost' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'is_bike' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'message' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_zone' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'zone_description' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'zone_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_order_type' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_package_type' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'id_payment_status' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
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
            'id_vehicle' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'vehicle_description' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'vehicle_name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'vehicle_zones' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'promo_code_balance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'referral_balance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'prepaid_balance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'credit_balance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'others_balance' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'revenue_hot_zone' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'token' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
            'errors' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 70, 'required' => false),
        ),
    );
    
    public function setId($id){
        $this->id_order = $id;
    }

    public function getId(){
        return $this->id_order;
    }

    public function loadData(){

        $sql = 'SELECT * FROM '._DB_PREFIX_.'ivoy_orders WHERE id_order = '.$this->id_order;
        return DB::getInstance()->getRow($sql);
    }

        public function createOrderFromRequest($newivoyorder, $ivoyrequest){
        
        try {
            
            Db::getInstance()->insert(_DB_PREFIX_.'ivoy_orders', array(
                
                'id_ivoy_order' => $newivoyorder['idOrder'], 
                'id_store_order' => $ivoyrequest['id_order'], 
                'id_cart' => $ivoyrequest['id_cart'], 
                'id_customer' => $ivoyrequest['id_customer'], 
                'id_carrier' => $ivoyrequest['id_carrier'], 
                'device' => $newivoyorder['device'], 
                'discount' => $newivoyorder['discount'], 
                'distance' => $newivoyorder['distance'], 
                'extra_charge' => $newivoyorder['extraCharge'], 
                'order_child_count' => $newivoyorder['orderChildCount'], 
                'package_value' => $newivoyorder['packageValue'], 
                'price' => $newivoyorder['price'], 
                'eta' => $newivoyorder['eta'], 
                'rating' => $newivoyorder['rating'], 
                'total' => $newivoyorder['total'], 
                'messenger_fee' => $newivoyorder['messengerFee'], 
                'ivoy_cost' => $newivoyorder['ivoyCost'], 
                'is_bike' => $newivoyorder['isBike'], 
                'id_client' => $newivoyorder['client']['idClient'], 
                'client_email' => $newivoyorder['client']['email'], 
                'client_name' => $newivoyorder['client']['nickname'], 
                'client_firstName' => $newivoyorder['client']['firstName'], 
                'client_lastName' => $newivoyorder['client']['lastName'], 
                'id_zone' => $newivoyorder['zone']['idZone'], 
                'zone_name' => '', 
                'zone_description' => '', 
                'id_order_status' => $newivoyorder['orderStatus']['idOrderStatus'], 
                'order_status_name' => '', 
                'order_status_description' => $newivoyorder['orderStatus']['description'], 
                'id_order_type' => $newivoyorder['orderType']['idOrderType'], 
                'order_type_name' => $newivoyorder['orderType']['name'], 
                'order_type_description' => $newivoyorder['orderType']['description'], 
                'order_type_zones' => $newivoyorder['orderType']['zones'], 
                'id_package_type' => $newivoyorder['packageType']['idPackageType'], 
                'id_payment_method' => $newivoyorder['paymentMethod']['idPaymentMethod'], 
                'payment_name' => $newivoyorder['paymentMethod']['name'], 
                'payment_description' => $newivoyorder['paymentMethod']['description'], 
                'store_id' => $ivoyrequest['store_id'], 
                'store_name' => $ivoyrequest['store_name'], 
                'addr_is_pickup' => $ivoyrequest['addr_is_pickup'], 
                'addr_is_source' => $ivoyrequest['addr_is_source'], 
                'addr_person_approved' => $ivoyrequest['addr_person_approved'], 
                'addr_comment' => $ivoyrequest['addr_comment'], 
                'addr_phone' => $ivoyrequest['addr_phone'], 
                'addr_price_business' => $ivoyrequest['addr_price_business'], 
                'addr_tip' => $ivoyrequest['addr_tip'], 
                'addr_distance' => $ivoyrequest['addr_distance'], 
                'addr_eta' => $ivoyrequest['addr_eta'], 
                'addr_price' => $ivoyrequest['addr_price'], 
                'addr_ext_num' => $ivoyrequest['addr_ext_num'], 
                'addr_int_num' => $ivoyrequest['addr_int_num'], 
                'addr_lat' => $ivoyrequest['addr_lat'], 
                'addr_lng' => $ivoyrequest['addr_lng'], 
                'addr_neighborhood' => $ivoyrequest['addr_neighborhood'], 
                'addr_street' => $ivoyrequest['addr_street'], 
                'addr_zip_code' => $ivoyrequest['addr_zip_code'], 
                'promo_code_balance' => $newivoyorder['promoCodeBalance'], 
                'referral_balance' => $newivoyorder['referralBalance'], 
                'prepaid_balance' => $newivoyorder['prepaidBalance'], 
                'credit_balance' => $newivoyorder['creditBalance'], 
                'id_vehicle' => $newivoyorder['vehicle']['idVehicle'], 
                'vehicle_name' => $newivoyorder['vehicle']['name'], 
                'vehicle_description' => $newivoyorder['vehicle']['description'], 
                'revenue_hot_zone' => $newivoyorder['revenueHotZone'], 
                'date_add' => date("Y-m-d H:i:s"), 
                'date_upd' => date("Y-m-d H:i:s")
            ));

            $lastId = Db::getInstance()->Insert_ID();
            
            if(!empty($lastId)){
                $this->deleteIvoyRequests($ivoyrequest);
            }
            
            return $lastId;

        } catch (Exception $e) {
            //echo 'Excepción qd: '.$e->getMessage()."\n";
        }
    }
    
    private function deleteIvoyRequests($ivoyrequest){
        
        $sql = 'DELETE FROM '._DB_PREFIX_.'ivoy_requests WHERE id_cart = '.$ivoyrequest['id_cart'].' AND id_order = '.$ivoyrequest['id_order'];
        Db::getInstance()->execute($sql);
    }
    
    public function confirmIvoyOrder($id_order, $id_cart){

        $sql = 'SELECT id_order, id_ivoy_order, id_customer, distance, total, store_id, store_name, date_add, '
                . 'addr_person_approved, addr_street, addr_neighborhood, addr_ext_num, addr_int_num, addr_zip_code '
                . 'FROM '._DB_PREFIX_.'ivoy_orders WHERE id_store_order = '.$id_order.' AND id_cart = '.$id_cart;
        return Db::getInstance()->getRow($sql);
    }

    public function displayIvoyOrder($id_order){
        
        $sql = 'SELECT id_order, id_ivoy_order, id_customer, distance, total, store_id, store_name, '
                . 'addr_person_approved, addr_street, addr_neighborhood, addr_ext_num, addr_int_num, addr_zip_code '
                . 'FROM '._DB_PREFIX_.'ivoy_orders WHERE id_order = '.$id_order;
        return Db::getInstance()->getRow($sql);
    }

}