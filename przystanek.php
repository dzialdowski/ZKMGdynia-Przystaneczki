<html>
    <head>
        <meta charset="UTF-8">
        <meta name="theme-color" content="black">
		<meta name="mobile-web-app-capable" content="yes">
		<link rel="manifest" href="manifest.json">
        <title>Przystaneczki</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="icon" sizes="192x192" href="nice-highres.png">
        <link rel="icon" sizes="512x512" href="nice-higherres.png">
        <script>
            <?php 
            $przystanek=$_GET['stopID'];
            echo "var x=$przystanek;";?>
            function czas(i) {
                var t=document.getElementById("czasT"+i).innerHTML;
                var d = new Date();
                var date = new Date,
                    time = t.split(/\:|\-/g);
                date.setHours(time[0]);
                date.setMinutes(time[1]);
                var diff = (date - d);
                diff = Math.floor(diff / 1000); //Sekundy
                diff = Math.floor(diff / 60) //minuty
                if (diff < -200) {
                    diff = diff + 1440;
                }
                if (diff > 120) {
                    diff = diff - 120;
                }
                if (diff > 60) {
                    diff = diff - 60;
                }
                if (diff == 60 || diff == 0) {
                    diff = "<blink>>>>></blink>";
                } else {
                    diff = diff + "min";
                }
                document.getElementById("czas"+i).innerHTML=diff;
            }
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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
                    PrzystanekID: x
                },
                function(data, status){
                        $("#rozklad").html(data).slideDown(1000);
                        document.getElementById("rozklad").innerHTML = data;
                        if (document.getElementById("busy") != null) {
                            var busy = document.getElementById("busy").innerHTML;
                            for (i = 0; i < busy; i++){
                                czas(i);
                            }
                        }
                });
            }
            function myChange(x){
                if(x!=""){
                    $("#rozklad").stop(true, true).slideUp(1000,change(x));
                }
            }
            $( "body" ).click(function() {
                <?php 
                $przystanek=$_GET['stopID'];
                echo "var x=$przystanek;";?>
                myChange(x);
            });
        </script>
        <center>
        <header>
            <h1><a href="https://vanco.azurewebsites.net/komunikacja" />PRZYSTANECZKI ZKM</a></h1>
        </header>
        <?php
            $przystanek=$_GET['stopID'];
            $con=new PDO("sqlsrv:Server = serwer,1433; Database = komunikacjamiejska", "", "");
            $query="SELECT [nazwaPrzystanku] FROM [dbo].[przystanki] WHERE [idPrzystanku]=$przystanek";
            $result=$con->query($query);
            $row=$result->fetchAll(PDO::FETCH_ASSOC);
            $nazwa=$row[0]['nazwaPrzystanku'];
            if($nazwa!=NULL){
                echo "<h1>$nazwa</h1>";
            }
            ?>
            <?php
                $przystanek=$_GET['stopID'];
                echo "<script>myChange($przystanek);</script>";
            ?>
        <div id=rozklad></div>
        </center>
    </body>
</html>
