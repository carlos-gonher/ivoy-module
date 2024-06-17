<!DOCTYPE html>
<html lang="es-mx">
    <head>
        <title>Address Search Template</title>
        <meta charset="UTF-8">
        <meta name="description" content="Contacta con PrimeTech Nutrition, una marca de suplementos con la mejor calidad al mejor precio" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script> 
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyBfJH-r9Y1KHUwNwi_6fP7fVqSnz_uQ_eg&libraries=places"></script>
    </head>
    <style>
        #map{
            width: 600px;
            height: 600px;
        }
        
        #ivoy-select-address{
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 6px 13px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
        }

        #pac-input {
          background-color: #fff;
          font-family: Roboto;
          font-size: 15px;
          font-weight: 300;
          margin-left: 8px;
          padding: 0 11px 0 13px;
          text-overflow: ellipsis;
          width: 500px;
        }
        
        #loading_div{
            display: none;
            position: absolute;
        }
        
        #loading_div img{
            position: absolute;
            margin-left: 7px;
            width: 25px;
            height: 25px;
            margin-top: 5px;
        }

    </style>
    <body>
        <div id="map-header">
            <div id="map-welcome">Mapa para buscar la dirección de destino: </div>
            <div id="map-storename"></div>
        </div>
        <div id="maps_address"></div>
        <input id="pac-input" class="controls" type="text" placeholder="Search Box">
        <div id="map"></div>

        <script>

        var ivoyDelivery;
        
        $(document).ready(function() {
            
            ivoyDelivery = window.parent.ivoyDelivery;
            
            ivoyDelivery.iframeDimensions();
            var location = ivoyDelivery.getCoordinates();
            initMap(location['lat'], location['lng']);
            storeName();

        });
        
        function captureDigit(value){
            ivoyDelivery.setIframeData('numint', value);
        }
        
        function storeName(){
            $('#map-storename').html('Su tienda de origen es VidaFull '+ivoyDelivery.storeName());
        }
        
        function initMap(lat, lng) {

            var coordinate = {lat: parseFloat(lat), lng: parseFloat(lng)};
            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 18,
              center: coordinate
            });

            var marker = new google.maps.Marker({
              position: coordinate,
              map: map
            });
    
            google.maps.event.addListener(map, 'click', function(event){
                placeMarker(event.latLng);
                ivoyDelivery.requestAddress(event.latLng);
            });

            var input = document.getElementById('pac-input');
            var autocomplete = new google.maps.places.Autocomplete(input);

            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();
                autocomplete.setBounds(map.getBounds());
                placeMarker(place.geometry.location);
                ivoyDelivery.requestAddress(place.geometry.location);
            });

            function placeMarker(geometry_location) {

                var lt = geometry_location.lat();
                var ln = geometry_location.lng();

                var location = {lat: parseFloat(lt), lng: parseFloat(ln)};
                if (marker == undefined){
                    marker = new google.maps.Marker({
                        position: location,
                        map: map, 
                        animation: google.maps.Animation.DROP,
                    });
                } else {
                    marker.setPosition(location);
                }
                map.setCenter(location);
            }

        } // End initMap 
        
        </script>
        
    </body>
</html>