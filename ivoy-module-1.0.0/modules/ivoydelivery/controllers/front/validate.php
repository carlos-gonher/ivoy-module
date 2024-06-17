<?php
/* 
 * VidaFull DevOps Jan, 2018 
 */

include_once(_PS_MODULE_DIR_.'ivoydelivery/classes/ivoyrequests.php');

class IvoyDeliveryValidateModuleFrontController extends ModuleFrontController
{
    public $display_header = false;
    public $display_footer = false;
    public $display_column_left = false;
    public $display_column_right = false;
    
    protected $_id_cart;
    protected $_id_customer;
    protected $_customer;
    protected $_id_carrier;
    protected $_store_data;
    protected $_curl_post_data = array();
    protected $_http_headers = array();
    protected $_service_url;

    public function initContent()
    {
        if($_POST['ajax_validate']){
            
            $this->_id_cart = $_POST['cart_id'];
            $this->_id_customer = $_POST['customer_id'];
            $this->_id_carrier = $_POST['carrier_id'];
            $this->_customer = new Customer($_POST['customer_id']);
            $this->_service_url = Configuration::get('ivoy_validate');
            $this->_store_data = Store::getData($_POST['store_id']);
            $this->buildRequestHeaders();
            $this->buildRequestStructure($_POST);
            $this->callApi();
        }

    }
    
    private function buildRequestHeaders(){

        /* Agregar la información a las cabeceras */
        $this->_http_headers[] = "Content-Type: application/json";
        $this->_http_headers[] = "Authorization: Basic VEVTVFRFU1Q6UEFTU1BBU1M=";
    }

    private function buildRequestStructure($data){

        /* Crear el arreglo con la estructura que requiere iVoy API */
        $curl_data = array("data" => array("bOrder" => array(
                        "zone" => array("idZone" => 1),
                        "orderType" => array("idOrderType" => 1),
                        "paymentStatus" => array("idPaymentStatus" => 1),
                        "vehicle" => array("idVehicle" => 3),
                        "orderAddresses" => array(
                            array(
                            "isPickup" => 1,
                            "isSource" => 1,
                            "personApproved" => 'VidaFull '.$this->_store_data['name'],
                            "phone" => $this->_store_data['phone'],
                            "address" => array(
                                "idAddress" => null,
                                "externalNumber" => $this->_store_data['num_ext'],
                                "internalNumber" => $this->_store_data['num_int'],
                                "latitude" => $this->_store_data['latitude'],
                                "longitude" => $this->_store_data['longitude'],
                                "neighborhood" => $this->_store_data['address2'],
                                "street" => $this->_store_data['address1'],
                                "zipCode" => $this->_store_data['postcode']
                                )
                            ),
                            array(
                                "isPickup" => 0,
                                "isSource" => 0,
                                "address" => array(
                                    "idAddress" => null,
                                    "externalNumber" => $data['addr_street_number'],
                                    "internalNumber" => !empty($data['addr_number_int']) ? $data['addr_number_int'] : 0,
                                    "latitude" => $data['addr_lat'],
                                    "longitude" => $data['addr_lng'],
                                    "neighborhood" => $data['addr_sublocality'],
                                    "street" => $data['addr_street'],
                                    "zipCode" => $data['addr_postal_code']
                                    )
                            )
                        )
                    )
                )
        );

        $this->_curl_post_data = json_encode($curl_data);
    }
    
    private function callApi(){
        
        $error = array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->_service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_http_headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_curl_post_data);

        $curl_response = curl_exec($curl);
        $newitem = $this->createIvoyRequest($curl_response);
        curl_close($curl);
        
        if(!empty($newitem)){
            die($curl_response);
        }else{
            die(json_encode(array('error'=>'dberror')));
        }

    }
    
    private function createIvoyRequest($curl_response){
        
        $ivoy_response = json_decode($curl_response, true);
        
        try {
            
            Db::getInstance()->insert(_DB_PREFIX_.'ivoy_requests', array(
                'id_cart' => $this->_id_cart, 
                'id_customer' => $this->_id_customer, 
                'id_order' => 0, 
                'id_carrier' => $this->_id_carrier, 
                'code' => $ivoy_response['code'], 
                'distance' => (float)$ivoy_response['data']['distance'], 
                'discount' => (float)$ivoy_response['data']['discount'], 
                'extra_charge' => (float)$ivoy_response['data']['extraCharge'], 
                'package_value' => (float)$ivoy_response['data']['packageValue'], 
                'price' => (float)$ivoy_response['data']['price'], 
                'eta' => (float)$ivoy_response['data']['eta'], 
                'rating' => (float)$ivoy_response['data']['rating'], 
                'total' => (float)$ivoy_response['data']['total'], 
                'messenger_fee' => (float)$ivoy_response['data']['messengerFee'], 
                'ivoy_cost' => (float)$ivoy_response['data']['ivoyCost'], 
                'is_bike' => (!empty($ivoy_response['data']['isBike'])) ? $ivoy_response['data']['isBike'] : 0, 
                'id_zone' => $ivoy_response['data']['zone']['idZone'], 
                'zone_description' => $ivoy_response['data']['zone']['description'], 
                'zone_name' => $ivoy_response['data']['zone']['name'], 
                'id_order_type' => $ivoy_response['data']['orderType']['idOrderType'], 
                'order_type_name' => $ivoy_response['data']['orderType']['name'], 
                'id_package_type' => $ivoy_response['data']['packageType']['idPackageType'], 
                'id_payment_status' => $ivoy_response['data']['paymentStatus']['idPaymentStatus'], 
                'payment_status_name' => $ivoy_response['data']['paymentStatus']['name'], 
                'store_id' => $this->_store_data['id_store'], 
                'store_name' => $this->_store_data['name'], 
                'addr_is_pickup' => $ivoy_response['data']['orderAddresses'][1]['isPickup'], 
                'addr_is_source' => $ivoy_response['data']['orderAddresses'][1]['isSource'], 
                'addr_person_approved' => $this->_customer->firstname.' '.$this->_customer->lastname, 
                'addr_comment' => '', 
                'addr_phone' => '', 
                'addr_price_business' => (float)$ivoy_response['data']['orderAddresses'][1]['priceBusiness'], 
                'addr_tip' => (float)$ivoy_response['data']['orderAddresses'][1]['tip'], 
                'addr_distance' => (float)$ivoy_response['data']['orderAddresses'][1]['distance'], 
                'addr_eta' => (float)$ivoy_response['data']['orderAddresses'][1]['eta'], 
                'addr_price' => (float)$ivoy_response['data']['orderAddresses'][1]['price'], 
                'addr_ext_num' => $ivoy_response['data']['orderAddresses'][1]['address']['externalNumber'], 
                'addr_int_num' => $ivoy_response['data']['orderAddresses'][1]['address']['internalNumber'], 
                'addr_lat' => $ivoy_response['data']['orderAddresses'][1]['address']['latitude'], 
                'addr_lng' => $ivoy_response['data']['orderAddresses'][1]['address']['longitude'], 
                'addr_neighborhood' => $ivoy_response['data']['orderAddresses'][1]['address']['neighborhood'], 
                'addr_street' => $ivoy_response['data']['orderAddresses'][1]['address']['street'], 
                'addr_zip_code' => $ivoy_response['data']['orderAddresses'][1]['address']['zipCode'], 
                'id_vehicle' => $ivoy_response['data']['vehicle']['idVehicle'], 
                'vehicle_name' => $ivoy_response['data']['vehicle']['name'], 
                'vehicle_description' => $ivoy_response['data']['vehicle']['description'], 
                'vehicle_zones' => '', 
                'promo_code_balance' => (float)$ivoy_response['data']['promoCodeBalance'], 
                'referral_balance' => (float)$ivoy_response['data']['referralBalance'], 
                'prepaid_balance' => (float)$ivoy_response['data']['prepaidBalance'], 
                'credit_balance' => (float)$ivoy_response['data']['creditBalance'], 
                'others_balance' => (float)$ivoy_response['data']['othersBalance'], 
                'revenue_hot_zone' => (float)$ivoy_response['data']['revenueHotZone'], 
                'date_add' => date("Y-m-d H:i:s"), 
                'date_upd' => date("Y-m-d H:i:s")
            ));

            $lastId = Db::getInstance()->Insert_ID();
            return $lastId;

        } catch (Exception $e) {
            //echo 'Excepción qd: '.$e->getMessage()."\n";
        }
        
    }

}