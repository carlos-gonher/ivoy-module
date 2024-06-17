<?php

/* 
 * VidaFull DevOps Dec, 2017 
 */

class Store extends StoreCore
{
    
    /*
     * VidaFull DevOps 
     * param: id_store
     * return: Database fields 
     */
    public static function getData($id){

        $sql = 'SELECT id_store, name, address1, address2, city, postcode, '
                . 'num_ext, num_int, latitude, longitude, phone, email '
                . 'FROM '._DB_PREFIX_.'store WHERE id_store = '.$id;

        return DB::getInstance()->getRow($sql);
    }
}