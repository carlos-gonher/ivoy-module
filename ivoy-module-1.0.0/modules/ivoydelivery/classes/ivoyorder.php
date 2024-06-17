<?php

class IvoyOrder{
    
    private $_service_url;
    private $_curl_post_data;
    private $_http_headers = array();
    private $_store_data;
    private $_ivoy_request;
    private $_ivoy_client;

    public function __construct() {
        
        $this->_service_url = Configuration::get('ivoy_new_order');
    }
    
    public function initData($data){

        $this->_ivoy_request = $data['ivoyrequest'];
        $this->_ivoy_client = $data['ivoylogin'];
        $this->_store_data = Store::getData($data['ivoyrequest']['store_id']);
        
        $this->buildHeaders($data['ivoylogin']['token']['access_token']);
        $this->buildStructure();
    }

    private function buildHeaders($token){
        
        /* Agregar la información a las cabeceras */
        $this->_http_headers[] = "Content-Type: application/json";
        $this->_http_headers[] = "Token: ".$token;
    }
    
    private function buildStructure(){
        
        $curl_data = array("data" =>  array(
                            "bOrder" =>  array(
                                "device" =>  "web",
                                "client" =>  array("idClient" =>  $this->_ivoy_client['data']['idClient']),
                                "orderType" =>  array("idOrderType" =>  1),
                                "packageType" =>  array("idPackageType" =>  4),
                                "paymentMethod" =>  array("idPaymentMethod" =>  13),
                                "orderAddresses" =>  array(
                                    array(
                                        "isPickup" =>  1,
                                        "isSource" =>  1,
                                        "comment" =>  "Recoger paquete en tienda",
                                        "personApproved" =>  "VidaFull ".$this->_store_data['name'],
                                        "phone" =>  $this->_store_data['phone'],
                                        "address" =>  array(
                                            "externalNumber" =>  $this->_store_data['num_ext'],
                                            "internalNumber" =>  $this->_store_data['num_int'],
                                            "latitude" =>  $this->_store_data['latitude'],
                                            "longitude" =>  $this->_store_data['longitude'],
                                            "neighborhood" =>  $this->_store_data['address2'],
                                            "street" =>  $this->_store_data['address1'],
                                            "zipCode" =>  $this->_store_data['postcode']
                                            )
                                    ),
                                    array(
                                        "isPickup" =>  0,
                                        "isSource" =>  0,
                                        "comment" =>  "Ejemplo de comentario",
                                        "personApproved" =>  $this->_ivoy_request['addr_person_approved'],
                                        "phone" =>  $this->_ivoy_request['addr_phone'],
                                        "address" =>  array(
                                            "externalNumber" =>  $this->_ivoy_request['addr_ext_num'],
                                            "internalNumber" =>  $this->_ivoy_request['addr_int_num'],
                                            "latitude" =>  $this->_ivoy_request['addr_lat'],
                                            "longitude" =>  $this->_ivoy_request['addr_lng'],
                                            "neighborhood" =>  $this->_ivoy_request['addr_neighborhood'],
                                            "street" =>  $this->_ivoy_request['addr_street'],
                                            "zipCode" =>  $this->_ivoy_request['addr_zip_code']
                                            )
                                    )
                                )
                            )
                        )
            );

        $this->_curl_post_data = json_encode($curl_data);
    }
    
    public function callApi(){
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->_service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_http_headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_curl_post_data);


        $curl_response = curl_exec($curl);
        $ivoy_response = json_decode($curl_response, true);
        curl_close($curl);
        
        if(!empty($ivoy_response['data']['idOrder']) && !empty($ivoy_response['data']['vehicle']['idVehicle'])){
            return $ivoy_response['data'];
        }else{
            return false;
        }

    }

}