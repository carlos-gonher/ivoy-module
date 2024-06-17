<?php
/* 
 * VidaFull DevOps Jan, 2018 
 */

class IvoyDeliveryValidatorModuleFrontController extends ModuleFrontController
{
    public $display_header = false;
    public $display_footer = false;
    public $display_column_left = false;
    public $display_column_right = false;
    
    public function initContent()
    {
        $id_cart = 15462;
        $id_customer = 4924;
        $order['id'] = 111; // 55
        $order['id_carrier'] = 66; // 55

        echo 'Class Validator Module... <br>';
        $lastRequest = $this->getLastRequest($id_cart, $id_customer);
        
        if(!empty($lastRequest)){
            echo 'Si se encontro cotizacion - Order Carrier: '.$order['id_carrier'].' <br>';
            echo 'Si se encontro cotizacion - id_request: '.$lastRequest['id_request'].' <br>';
            echo 'Si se encontro cotizacion - id_cart: '.$lastRequest['id_cart'].' <br>';
            echo 'Si se encontro cotizacion - id_carrier: '.$lastRequest['id_carrier'].' <br>';
            
            if($order['id_carrier'] == $lastRequest['id_carrier']){
                echo 'SI coinciden - Actualizar campo id_order en ivoy_requests <br>';
                $this->updateLastRequest($order['id'], $lastRequest['id_request']);
            }else{
                echo 'No coinciden - Borrar los registros encontrados para el cart y el carrier <br>';
                $this->deleteTrashRequests($id_cart, $id_customer);
            }
        }else{
            echo 'NO se encontro cotizacion:  <br>';
        }
        

    }
    
    private function getLastRequest($id_cart, $id_customer){
        
        $sql = 'SELECT id_request, id_cart, id_carrier FROM '._DB_PREFIX_.'ivoy_requests WHERE id_cart = '.$id_cart.' AND id_customer = '.$id_customer.' ORDER BY id_request DESC LIMIT 1';
        echo 'QUERY: '.$sql.'<br>';
        $result = Db::getInstance()->ExecuteS($sql);
        
        if(!empty($result[0])){
            return $result[0];
        }else{
            return false;
        }
        
    }
    
    private function updateLastRequest($id_order, $id_request){
        
        echo 'function updateLastRequest - $id_order: '.$id_order.'<br>';
        echo 'function updateLastRequest - $id_request: '.$id_request.'<br>';
        $sql = 'UPDATE '._DB_PREFIX_.'ivoy_requests SET id_order = '.$id_order.' WHERE id_request = '.$id_request;
        echo 'function updateLastRequest - SQL: '.$sql.'<br>';
        Db::getInstance()->executeS($sql);
    }
    
    private function deleteTrashRequests($id_cart, $id_customer){
        
        echo 'function deleteTrashRequests - $id_cart: '.$id_cart.'<br>';
        echo 'function deleteTrashRequests - $id_customer: '.$id_customer.'<br>';
        $sql = 'DELETE FROM '._DB_PREFIX_.'ivoy_requests WHERE id_cart = '.$id_cart.' AND id_customer = '.$id_customer;
        echo 'function deleteTrashRequests - SQL: '.$sql.'<br>';
        Db::getInstance()->execute($sql);
    }

}