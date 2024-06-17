
$(document).ready(function(){
    ivoyDelivery.init();
});

var ivoyDelivery = (function(){

    var coordinates = [];
    var user_address = [];
    var ivoy_location = [];
    var iframe_data = [];
    var theme_info;
    
    var init = function(){
        listenEvents();
        setThemeInfo();
        initoptionprice();
    }
        
    var listenEvents = function(){
        
        $('body').on('click', '#zipcode_search', searchByPostalCode);
        $('body').on('click', '#address_search', searchByAddress);
        $('body').on('click', '.payment_module a', checkDeliverySelected);
    }
    
    var setThemeInfo = function(){
        theme_info = document.getElementById("theme-info");
    }
    
    var initoptionprice = function(){
        $('#ivoy_option_price').html('Si desea usar el envío iVoy, debe proporcionar la dirección de destino.');
    }
    
    var checkDeliverySelected = function(e){

        var addrselected = $('#ivoy_option_price').attr('data-addr-selected');
        
        $("input:radio.delivery_option_radio").each(function(i){
            
            if ($(this).is(':checked')){
                if($(this).data('ivoy')){
                    if(addrselected == 0){
                        alert('Eligió iVoy como paquetería, debe buscar su dirección de destino');
                        e.preventDefault();
                        return false;
                    }
                }
            }

        });
        
    }

    var searchByPostalCode = function(){

        var postcode = $('#address_delivery').find('.address_postcode').text();
        sendRequest(cleanPostalCode(postcode));
    }
    
    var searchByAddress = function(){

        coordinates.length = 0;
        coordinates['lat'] = theme_info.dataset.lat;
        coordinates['lng'] = theme_info.dataset.lng;
        
        var map_url = theme_info.dataset.base + theme_info.dataset.uri + 'modules/ivoy/addresssearch.tpl';
        launchWindow(map_url);
    }

    var sendRequest = function(postal_code){

            var url = 'https://maps.google.com/maps/api/geocode/json?components=country:MX|postal_code:'+postal_code+'&sensor=false';

            // Petición a Google Maps 
            $.ajax({
               url : url,
               method: "POST",
               success:function(data){
                   var latitude = data.results[0].geometry.location.lat;
                   var longitude= data.results[0].geometry.location.lng;
                   coordinates['lat'] = latitude;
                   coordinates['lng'] = longitude;
                   callMap(latitude, longitude);
               }
            });
    }
    
    var requestAddress = function(loc){
        
        ivoy_location['lat'] = loc.lat();
        ivoy_location['lng'] = loc.lng();
        var address_string = '';

        var url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+ivoy_location['lat']+','+ivoy_location['lng'];
        console.log('requestAddress = function - url: '+url);
        
        $.ajax({
            url: url, 
            method: 'POST',
            success: function(res){
                
                if(res){
                    
                    user_address.length = 0;
                    setUserAddress(res.results[0].address_components);
                    address_string = '<div>'+res.results[0].formatted_address+'</div>';
                    address_string += '<div>Número interior (si aplica): <input type="text" id="ivoy-num-int" placeholder="Num Int" onKeyUp="captureDigit(this.value)" value=""></div>';
                    address_string += '<div>Seleccionar esta dirección: <input type="button" id="ivoy-select-address" onclick="ivoyDelivery.validateLocation()" value="Enviar">';
                    address_string += '<div id="loading_div"><img src="views/img/loader.gif"></div></div>';
                    $('#maps_address', $('.fancybox-iframe').contents()).html(address_string);
                }else{
                    $('#maps_address', $('.fancybox-iframe').contents()).html('Error: inténtelo nuevamente');
                }
            }
        });
        
    }
    
    var validateLocation = function(){
        
        $('.fancybox-iframe').contents().find('#loading_div').css('display', 'inline-block');
        
        if(!user_address['street_number']){
            alert('No hay numero en la dirección. CLick en el mapa.');
        }
            
        $.ajax({
            url : theme_info.dataset.ivoyModule,
            action: 'validate',
            type: 'POST',
            cache: false,
            ajax: true,            
            dataType: 'json',
            data: {
                ajax_validate: true, 
                store_id: theme_info.dataset.storeId,
                customer_id: theme_info.dataset.customerId,
                cart_id: theme_info.dataset.cartId,
                carrier_id: theme_info.dataset.carrier,
                addr_lat: ivoy_location['lat'],
                addr_lng: ivoy_location['lng'],
                addr_street: user_address['street'],
                addr_street_number: user_address['street_number'],
                addr_number_int: iframe_data['numint'],
                addr_sublocality: user_address['sublocality'],
                addr_locality: user_address['locality'],
                addr_area_level_1: user_address['area_level_1'],
                addr_country: user_address['country'],
                addr_postal_code: user_address['postal_code'],
            },
            success: function(response){
                
                if(response.error=='dberror'){
                    console.log('Error en la base de datos: '+response.error);
                }
                
                var total_html = $('#total_price').html();
                var total_html_price = total_html.replace('$', '');
                var total_plus_ivoy = (parseFloat(total_html_price) + (parseFloat(response.data.price.format(2))));
                
                fillOptionPrice(response.data);
                
                $('#total_price').html('$ '+total_plus_ivoy);
                $('.fancybox-close').click();
            },
            error: function() {
                alert('Error - textStatus: '+textStatus);
                alert('Error - errorThrown: '+errorThrown);
            }
          });

    }

    var callMap = function(lat, long){

        var tpl_base = theme_info.dataset.base;
        var tpl_uri = theme_info.dataset.uri;
        var map_url = tpl_base + tpl_uri + 'modules/ivoy/zipcodesearch.tpl?lat='+lat+'&long='+long;
        
        launchWindow(map_url);
    }

    var launchWindow = function(href){

        $.fancybox({
                'padding'       : 0,
                'autoScale'     : false,
                'autoSize'      : false, 
                'fitToView'     : false,
                'transitionIn'  : 'none',
                'transitionOut' : 'none',
                'title'         : this.title,
                'width'         : 700,
                'height'        : 500,
                'href'          : href,
                'type'          : 'iframe',
            });

        return false;                  
    }
    

    
    var fillOptionPrice = function(ivoydata){
        
        var useraddr = ivoydata.orderAddresses[1].address;
        var price = '$ '+ivoydata.price.format(2)+' (con I.V.A.)';
        var int = (useraddr.internalNumber != 0) ? 'Int.: '+useraddr.internalNumber+' ' : '';
        var address = price+' <br> Destino: '+useraddr.street+' '+useraddr.externalNumber+' '+int+useraddr.neighborhood+' CP: '+useraddr.zipCode;
        user_address['price'] = ivoydata.price.format(2);
        user_address['num_int'] = int;

        $('#ivoy_option_price').html(address);
        $('#ivoy_option_price').attr('data-addr-selected', 1);
        $('.cart_total_delivery #total_shipping').html(price);
    }
    
    var setUserAddress = function(address_components){
        
            for (var i = 0; i < address_components.length; i++){
              var addr = address_components[i];
              
              if(addr.types[0] == 'street_number')
                user_address['street_number'] = addr.long_name;
            
              if(addr.types[0] == 'route')
                user_address['street'] = addr.long_name;
            
              if(addr.types[0] == 'political')
                user_address['sublocality'] = addr.long_name;
            
              if(addr.types[0] == 'locality')
                user_address['locality'] = addr.long_name;
            
              if(addr.types[0] == 'administrative_area_level_2')
                user_address['area_level_2'] = addr.long_name;
            
              if(addr.types[0] == 'administrative_area_level_1')
                user_address['area_level_1'] = addr.long_name;
            
              if(addr.types[0] == 'country')
                user_address['country'] = addr.long_name;
            
              if(addr.types[0] == 'postal_code')
                user_address['postal_code'] = addr.long_name;

            }
    }
    
    var cleanPostalCode = function(postal_code){
        elements = postal_code.split(" ");
        return elements[0];
    }

    var iframeDimensions = function(){
        $('.fancybox-iframe').css({height:'700px',width:'700px'});
    }
        
    var getCoordinates = function(){
        return coordinates;
    }
    
    var setIframeData = function(iframe_key, iframe_value){
        iframe_data[iframe_key] = iframe_value;
    }
    
    var storeName = function(){
        return theme_info.dataset.storeName;
    }
    
    return {
        init: init,
        getCoordinates: getCoordinates,
        iframeDimensions: iframeDimensions, 
        requestAddress: requestAddress, 
        validateLocation: validateLocation, 
        setIframeData: setIframeData,
        storeName: storeName,
    }
})();

Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};    

