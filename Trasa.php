<?php 

//PHP & MySQL init
$con=new PDO("sqlsrv:Server = serwer,1433; Database = komunikacjamiejska", "", ""); //Połączenie z bazą danych
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="theme-color" content="black">
        <title>Trasy</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="icon" sizes="192x192" href="nice-highres.png">
        <link rel="icon" sizes="512x512" href="nice-higherres.png">
        <script type="text/javascript">
		  var appInsights=window.appInsights||function(a){
			function b(a){c[a]=function(){var b=arguments;c.queue.push(function(){c[a].apply(c,b)})}}var c={config:a},d=document,e=window;setTimeout(function(){var b=d.createElement("script");b.src=a.url||"https://az416426.vo.msecnd.net/scripts/a/ai.0.js",d.getElementsByTagName("script")[0].parentNode.appendChild(b)});try{c.cookie=d.cookie}catch(a){}c.queue=[];for(var f=["Event","Exception","Metric","PageView","Trace","Dependency"];f.length;)b("track"+f.pop());if(b("setAuthenticatedUserContext"),b("clearAuthenticatedUserContext"),b("startTrackEvent"),b("stopTrackEvent"),b("startTrackPage"),b("stopTrackPage"),b("flush"),!a.disableExceptionTracking){f="onerror",b("_"+f);var g=e[f];e[f]=function(a,b,d,e,h){var i=g&&g(a,b,d,e,h);return!0!==i&&c["_"+f](a,b,d,e,h),i}}return c
			}({
				instrumentationKey:"83cdd0f3-b6a0-4dd7-9b0f-d6a4c6066f2c"
			});
    
		  window.appInsights=appInsights,appInsights.queue&&0===appInsights.queue.length&&appInsights.trackPageView();
		</script>
    </head>    
    <body>
        <center>
            <?php
                $routeID=$_GET['routeID'];
                $tripID=$_GET['tripID'];
                $trip=$_GET['trip'];
                if(is_numeric($routeID) && is_numeric($tripID)){
                    $query= "SELECT przystanki.nazwaPrzystanku, trasy.stopID FROM przystanki
                    JOIN trasy ON trasy.stopID=przystanki.idPrzystanku
                    WHERE trasy.routeID=$routeID AND trasy.tripID=$tripID ORDER BY trasy.stopSequence;";
                    $result=$con->query($query); //Wyślij zapytanie do bazy danych o listę przystanków
                    while ($row = $result->fetch(PDO::FETCH_BOTH)) {
                        $StopId=$row['stopID'];
                        $str = file_get_contents("http://87.98.237.99:88/delays?stopId={$StopId}");
                        $json = json_decode($str, true);
                        foreach ($json['delay'] as $rowInside) {
                            if($trip==$rowInside['trip']){
                                $czasNaPrzystanku=$rowInside['estimatedTime'];
                                break;
                            }else{
                                $czasNaPrzystanku="---";
                            }
                        }
                        echo "<a href=przystanek.php?stopID=" . $row['stopID'] . ">" . $row['nazwaPrzystanku'] . "</a>     ". $czasNaPrzystanku ."</br>"; //Wypisz opcje dla każdego przystanku na trasie
                    }
                }
                
            ?>
        </center>
    </body>
</html>