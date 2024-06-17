<?php
/*
*  @author Vidafull Dreamteam
*/

class Order extends OrderCore
{
    
    public function __construct($id = null, $id_lang = null){
        parent::__construct($id, $id_lang);
    }
    /**
     * Get id_cart_rule field
     * from order_cart_rule table 
     * by id_order
     * @param integer $id_order
     * @return string id_cart_rule
     */        
    public static function getIdCartRule($id_order){
            $sql = 'SELECT `id_cart_rule`
                            FROM `'._DB_PREFIX_.'order_cart_rule`
                            WHERE `id_order` = '.(int)($id_order);
            $result = Db::getInstance()->getRow($sql);
            return isset($result['id_cart_rule']) ? $result['id_cart_rule'] : false;
    }
    
    public function getAvailableCarriers(){

        /*
        $sql = "SELECT SQL_CALC_FOUND_ROWS a.id_carrier, a.name, a.active, b.id_shop, b.id_lang, b.delay
			FROM `carrier` a
			LEFT JOIN `carrier_lang` b ON a.id_carrier = b.id_carrier AND b.id_shop = 1  AND b.id_lang = 3 
                        LEFT JOIN `carrier_tax_rules_group_shop` ctrgs ON (a.`id_carrier` = ctrgs.`id_carrier` AND ctrgs.id_shop=1) 
			WHERE 1 AND a.`deleted` = 0 
			ORDER BY a.`position` ASC LIMIT 0,50";
         */
        
        $order_weight = $this->getWeightFromOrderCarrier($this->id);
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS a.id_carrier, a.name, a.active, b.id_shop, b.id_lang, b.delay, d.id_delivery, 
                        d.id_range_weight, d.id_zone, d.price as dprice, z.name as zname 
			FROM `carrier` a
			LEFT JOIN `carrier_lang` b ON a.id_carrier = b.id_carrier AND b.id_shop = 1  AND b.id_lang = 3 
                        LEFT JOIN `carrier_tax_rules_group_shop` ctrgs ON (a.`id_carrier` = ctrgs.`id_carrier` AND ctrgs.id_shop=1) 
                        LEFT JOIN `delivery` d ON a.id_carrier = d.id_carrier AND 
                        d.id_range_weight IN (select id_range_weight from range_weight where ".$order_weight." >= delimiter1 AND ".$order_weight." <= delimiter2) 
                        LEFT JOIN `zone` z ON z.id_zone = d.id_zone 
			WHERE 1 AND a.`deleted` = 0 AND a.active = 1  AND a.name != "."'".(string)'TPVTienda'."'"." 
                        HAVING a.name != "."'".(string)'POS'."'"." AND d.id_zone = 10 OR d.id_zone IS NULL 
			ORDER BY a.`position` ASC LIMIT 0,50";
        
        return Db::getInstance()->executeS($sql);
    }

    protected function getWeightFromOrderCarrier($order_id){
     
        $sql = 'SELECT weight FROM order_carrier WHERE id_order = '.$order_id;
        $res = Db::getInstance()->getRow($sql);
        return $res['weight'];
    }

    public function updateOrderCarrier($order, $delivery, $new_total, $has_invoice){
        
        $query = 'UPDATE '._DB_PREFIX_.'order_carrier  SET '
                .'id_carrier = '.$delivery->id_carrier.', '
                .'shipping_cost_tax_excl = '.$delivery->price.', '
                .'shipping_cost_tax_incl = '.$delivery->price.' '
                .'WHERE id_order = '.$order->id;
        
        return Db::getInstance()->executeS($query);
    }
    
    public function updateCarrierDataInOrder($order, $delivery, $new_total, $has_invoice){

        $id_carrier = $delivery->id_carrier;
        $delivery_price = $delivery->price;

        $query = 'UPDATE '._DB_PREFIX_.'orders  SET '
                .'id_carrier = '.$id_carrier.', '
                .'total_paid = '.$new_total.', '
                .'total_paid_tax_incl = '.$new_total.', '
                .'total_paid_tax_excl = '.$new_total.', '
                .'total_paid_real = '.$new_total.', '
                .'total_shipping = '.$delivery_price.', '
                .'total_shipping_tax_incl = '.$delivery_price.', '
                .'total_shipping_tax_excl = '.$delivery_price.' '
                .'WHERE id_order = '.$order->id;

        return Db::getInstance()->executeS($query);
    }
    
    public function updateCarrierDataInOrderInvoice($order, $delivery, $new_total){
        
        $query = 'UPDATE '._DB_PREFIX_.'order_invoice  SET '
        .'total_paid_tax_excl = '.$new_total.', '
        .'total_paid_tax_incl = '.$new_total.', '
        .'total_shipping_tax_excl = '.$delivery->price.', '
        .'total_shipping_tax_incl = '.$delivery->price.' '
        .'WHERE id_order = '.$order->id;
        
        return Db::getInstance()->executeS($query);
    }
    
    public function updateOrderPaymentAmount($order, $new_total){

        $query = 'UPDATE '._DB_PREFIX_.'order_payment  SET '
        .'amount = '.$new_total.' '
        .'WHERE order_reference = '."'".$order->reference."'";
        
        return Db::getInstance()->executeS($query);
    }
    
    public function getOrderTaxRate($id_order){

        $sql = 'SELECT od.id_order, odt.id_order_detail, odt.id_tax, t.rate 
                        FROM '._DB_PREFIX_.'order_detail as od 
                        INNER JOIN '._DB_PREFIX_.'order_detail_tax AS odt ON odt.id_order_detail = od.id_order_detail
                        INNER JOIN '._DB_PREFIX_.'tax AS t ON t.id_tax = odt.id_tax
                        WHERE od.id_order = '.$id_order.' AND id_tax_rules_group <> 0 
                        ORDER BY od.id_order_detail DESC LIMIT 1;';

        $result = Db::getInstance()->executeS($sql);
        
        if(!empty($result[0])){
            return $result[0]['rate'];
        }else{
            return false;
        }
        
    }
    

}
