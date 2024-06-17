<?php

class IvoyLogin{
    
    private $_service_url;
    private $_ivoy_user;
    private $_ivoy_pass;
    private $_curl_post_data;
    private $_http_headers = array();
    
    
    public function __construct() {
        
        $this->_ivoy_user = Configuration::get('ivoy_user');
        $this->_ivoy_pass = Configuration::get('ivoy_password');
        $this->_service_url = Configuration::get('ivoy_login');
        
        $this->buildHeaders();
        $this->buildStructure();
    }
    
    private function buildHeaders(){

        /* Agregar la información a las cabeceras */
        $this->_http_headers[] = "Content-Type: application/json";
        $this->_http_headers[] = "Authorization: Basic VEVTVFRFU1Q6UEFTU1BBU1M=";
    }
    
    private function buildStructure(){
        
        $curl_data = array("data" => array(
                "systemRequest" => array(
                        "user" => $this->_ivoy_user, 
                        "password" => $this->_ivoy_pass
                )));

        $this->_curl_post_data = json_encode($curl_data);
    }
    
    public function callApi(){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->_service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_http_headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->_curl_post_data);

        $curl_response = curl_exec($curl);
        $ivoy_response = json_decode($curl_response, true);
        curl_close($curl);
        
        if(!empty($ivoy_response['data']['idClient']) && !empty($ivoy_response['token']['access_token'])){
            return $ivoy_response;
        }else{
            return false;
        }

    }
    
}