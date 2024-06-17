
$(document).ready(function(){
    ivoyOrder.init();
});

var ivoyOrder = (function(){
    
    var module_info, base_url, module_url, start_button;
    
    var init = function(){
        $('body').on('click', '#start_ivoy_process', startIvoyProcess);
        loadModuleInfo();
    }
    
    var loadModuleInfo = function(){
        module_info = $('#module-info');
        base_url = module_info.data('baseUrl');
        module_url = module_info.data('moduleUrl');
        start_button = $('#start_ivoy_process');
    }
    
    var startIvoyProcess = function(){
        
        var id_request = start_button.data('idRequest');
        var start = confirm("Enviar producto(s) por iVoy?");
        
        if (start == true) {
            createIvoyOrder(id_request);
        } else {
            return false;
        }
    }
    
    var createIvoyOrder = function(id_request){
        
        var url = base_url+'/administration/'+module_url;
        
        please_wait();
        
        $.ajax({
            url: url,
            type:"POST",
            async: true,
            dataType: "json",            
            data : {
                ajax: "1",
                tab: "IvoyDelivery",
                action: "CreateIvoyOrder",
                ajax_request: true,
                id_request: id_request,
            },            
            success: function(res){
                
                if(res.error){
                    handle_error(res.error);
                }
                
                if(res.ivoyorder){
                    
                    $('#ivoy_shipping_actions').html();
                    $('#ivoy_shipping_actions').html('Orden generada');
                    $('#ivoy_shipping_form').html();
                    $('#ivoy_shipping_form').html('Ivoy Order: '+res.ivoyorder.id_ivoy_order);
                }
            }
        });
    }
    
    var handle_error = function(error){
        
        console.log('handle_error = function - error: '+error);
        
        switch(error){
            
            case 'loginerror':
                alert('No se pudo tener conexión con Ivoy. Intente nuevamente.');
                break;
                
            case 'ordererror':
                alert('Ivoy no generó la orden. Intente nuevamente.');
                break;

            case 'dbordererror':
                alert('Error al guardar la orden. Intente nuevamente.');
                break;            
        }
    }
    
    var please_wait = function(){
        
        var processing = '<div id="loading_div"><img src="../themes/vidafull/img/ajax-loader.gif">&nbsp;Procesando...</div></div>';
        $('#ivoy_shipping_form').html();
        $('#ivoy_shipping_form').html(processing);
    }
    
    return {
        init: init,
    }
    
})();