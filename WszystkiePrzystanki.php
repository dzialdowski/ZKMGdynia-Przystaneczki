<?php 

//PHP & MySQL init
$con=new PDO("sqlsrv:Server = serwer,1433; Database = komunikacjamiejska", "", ""); //Połączenie z bazą danych
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="theme-color" content="black">
        <title>Przystaneczki</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="icon" sizes="192x192" href="nice-highres.png">
        <link rel="icon" sizes="512x512" href="nice-higherres.png">
        <script>
            function czas(i) {
                var t=document.getElementById("czasT"+i).innerHTML; //Zdobądź obliczony czas przyjazdu
                var d = new Date(); // aktualna data, od której będzie liczona różnica 
                var date = new Date,
                    time = t.split(/\:|\-/g);
                date.setHours(time[0]);
                date.setMinutes(time[1]);
                var diff = (date - d);
                diff = Math.floor(diff / 1000); //Sekundy
                diff = Math.floor(diff / 60) //minuty
                if (diff < -200) {
                    diff = diff + 1440; //Fix na następny dzień
                }
                if (diff > 120) {
                    diff = diff - 120; //Fix na strefę czasową
                }
                if (diff > 60) {
                    diff = diff - 60; //Fix na strefę czasową 2
                }
                if (diff == 60 || diff == 0) {
                    diff = "<blink>>>>></blink>"; //Migacz przy odjeździe
                } else {
                    diff = diff + "min"; //Dodanie jednostki czasu
                }
                document.getElementById("czas"+i).innerHTML=diff; //zapis do pola w tabeli
            }
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="select2.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
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
        <script>         
            function change(x){
                $.post("ajaxReturn.php",
                {
                    PrzystanekID: x //Wyślij request o rozkład jazyd dla przystanku x
                },
                function(data, status){
                        $("#rozklad").html(data).slideDown(1000);
                        document.getElementById("rozklad").innerHTML = data; //Dodaj pobraną tabelkę do źródła strony
                        var busy=document.getElementById("busy").innerHTML;
                        for(i=0;i<busy;i++){
                            czas(i); //dla każdego wiersza w tabeli oblicz czas do przyjazdu
                        }
                });
            }
            function myChange(){
                var x = document.getElementById("mySelect").value;
                    $("#rozklad").stop(true, true).slideUp(1000,change(x));
            }
            $(document).ready(function(){
                $("#refresh").bind('click', function(){
                    myChange();
                });
            });

            $( "body" ).click(function() {
                myChange();
            });
        </script>
        <center>
        <header>
            <h1><a href="https://vanco.azurewebsites.net/komunikacja" />PRZYSTANECZKI ZKM</a></h1>
        </header>
        
        <select id="mySelect" onchange="myChange()">

        <?php
			$query= "SELECT * FROM [dbo].[przystanki] ORDER BY [nazwaPrzystanku]";
            $result=$con->query($query); //Wyślij zapytanie do bazy danych o listę przystanków
            while ($row = $result->fetch(PDO::FETCH_BOTH)) {
                echo "<option value=" . $row['idPrzystanku'] . ">" . $row['nazwaPrzystanku'] . "</option>"; //Wypisz opcje dla każdego przystanku w bazie danych
            }
        ?>
        </select>
        <script>
            $(document).ready(function() {
                $('#mySelect').select2();
            });
        </script>
        <div id=rozklad></div>
        </center>
    </body>
</html>
