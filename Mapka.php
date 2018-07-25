<?php 

//PHP & MSSQL init
$con=new PDO("sqlsrv:Server = serwer,1433; Database = komunikacjamiejska", "", ""); //Połączenie z bazą danych
?>
<html>
    <head>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
    integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
   integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
   crossorigin=""></script>
   <script src='https://api.mapbox.com/mapbox-gl-js/v0.44.2/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v0.44.2/mapbox-gl.css' rel='stylesheet' />
    <style>
        #mapid { height: 100vw; }
    </style>
    </head>
    <body>
        <div id="mapid">
            <script>
            //mapboxgl.accessToken = 'pk.eyJ1IjoiMHZhbmNvIiwiYSI6ImNqZzhiM2w5MzJ5MGQyd3BoZjZwdHo3MjkifQ.tVMjmZqxC9zIRGh833KwFw';
            //var map = new mapboxgl.Map({
            //container: 'map',
            //style: 'mapbox://styles/mapbox/streets-v10'
            //});
            </script>

        </div>
        <script>
            var mymap = L.map('mapid').setView([54.52179,18.53014], 13);
            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                maxZoom: 18,
                id: 'mapbox.streets',
                accessToken: 'pk.eyJ1IjoiMHZhbmNvIiwiYSI6ImNqZzhiM2w5MzJ5MGQyd3BoZjZwdHo3MjkifQ.tVMjmZqxC9zIRGh833KwFw'
            }).addTo(mymap);
            <?php
                $query= "SELECT * FROM [dbo].[przystanki]";
                $result=$con->query($query); //Wyślij zapytanie do bazy danych o listę przystanków
                while ($row = $result->fetch(PDO::FETCH_BOTH)) {
                    echo " var marker = L.marker([" . $row['Latitude'] . ","
                    . $row['Longitude'] . ']).addTo(mymap);
                    '; //Wypisz opcje dla każdego przystanku w bazie danych
                    echo "marker.bindPopup(\"<a href=przystanek.php?stopID=" . $row['idPrzystanku'] . ">" . $row['nazwaPrzystanku'] . "</a>\");";
                    //Wypisz opcje dla każdego przystanku na trasie
                }
            ?>
        </script>
    </body>
</html>