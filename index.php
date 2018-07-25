<?php
    $con=new PDO("sqlsrv:Server = serwer,1433; Database = komunikacjamiejska", "", "");//Połączenie z bazą danych
    define('BOT_USERNAME', ''); // place username of your bot here
    function getTelegramUserData() {
        if (isset($_COOKIE['tg_user'])) {
            $auth_data_json = urldecode($_COOKIE['tg_user']);
            $auth_data = json_decode($auth_data_json, true);
            return $auth_data;
        }
        return false;
    }
    if ($_GET['logout']) {
    setcookie('tg_user', '');
    header('Location: index.php');
    }
    $tg_user = getTelegramUserData();
?>
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
            function czas(i) {
                var t=document.getElementById("czasT"+i).innerHTML;
                var d = new Date();
                var date = new Date,
                    time = t.split(/\:|\-/g);
                date.setHours(time[0]);
                date.setMinutes(time[1]);
                date.setSeconds(time[2]);
                var diff = (date - d);
                s = Math.floor(diff / 1000); //Sekundy
                s=s%60;
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
                if (diff == 60 || diff <= 0) {
                    diff = "<blink>>>>></blink>";
                } else {
                    diff = diff + "min" + s + "s";
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
                        $("#rozklad").html(data).slideDown(1000,function() {
                            document.getElementById("rozklad").innerHTML = data;
                            if (document.getElementById("busy") != null) {
                                var busy = document.getElementById("busy").innerHTML;
                                for (i = 0; i < busy; i++){
                                    czas(i);
                                }
                            }
                        });
                });
            }
            function myChange(){
                var x = document.getElementById("mySelect").value;
                if(x!=""){
                    $("#rozklad").slideUp(1000,change(x));
                }
            }
            $( "#refr" ).click(function() {
                myChange();
            });
            function nazi(){
                if(document.getElementById("refr").innerHTML=="卐"){
                    document.getElementById("refr").innerHTML="&#8635";
                }else{
                    document.getElementById("refr").innerHTML="卐";
                }
            };
        </script>
        <center>
        <header>
            <h1><a href="https://vanco.azurewebsites.net/komunikacja" />PRZYSTANECZKI ZKM</a></h1>
        </header>
        <div id=telegram>
            <?php
                if ($tg_user !== false) {
                    $first_name = htmlspecialchars($tg_user['first_name']);
                    if (isset($tg_user['username'])) {
                        $username = htmlspecialchars($tg_user['username']);
                        echo "<h1 onclick=nazi() style=\"color:black\">Witaj, <a style=\"color:black\" href=\"https://t.me/{$username}\">{$first_name}</a>!</h1>";
                    } else {
                        echo "<h1 onclick=nazi() style=\"color:black\">Witaj, {$first_name}!</h1>";
                    }
                    echo "<p><a style=\"color:white\" href=\"?logout=1\">Wyloguj</a>    <a style=\"color:white\" href=\"moje.php\">Moje przystanki</a></p>";
                } 
                else {
                    $bot_username = BOT_USERNAME;
                    echo "<h1 style=\"color:red\">Nie jesteś zalogowany</h1>";
                    echo "<a style=\"color:white\" href=WszystkiePrzystanki.php>Wszystkie przystanki</a><br>";
                    echo "<script async src=\"https://telegram.org/js/telegram-widget.js?2\" data-telegram-login=\"{$bot_username}\" data-size=\"medium\" data-auth-url=\"check_authorization.php\"></script>";
                }
            ?>
        </div>
        <br>
        <?php
        if($tg_user==false)echo '
        <select id="mySelect" onchange="myChange()">
            <option disabled selected value> -- WYBIERZ PRZYSTANEK -- </option>
            <option disabled value> -- Vanco -- </option>
            <option value=37200>Cisowa SKM 06 --> Centrum</option>
            <option value=37540>Jęczmienna 02 --> Centrum</option>
            <option value=37350>Owsiana 01 --> Centrum</option>
            <option value=37700>Chylonia Dworzec PKP --> Cisowa</option>
            <option value=37380>Chylonia Centrum --> Centrum</option>
            <option value=37320>Owsiana --> Rumia</option>
            <option value=38460>Handlowa 02 (NŻ) --> Rumia</option>
            <option value=31329>Janowo SKM - Sobieskiego --> Cisowa</option>
            <option disabled value> -- Brzoza -- </option>
            <option value=39380>Stocznia Marynarki Woj. 01 --> Estakada</option>
            <option value=39070>Stocznia Marynarki Woj. 01 --> AMW</option>
            <option value=39040>Obłuże centrum --> Brzozen</option>
            <option value=36140>Gdynia Dw. Główny PKP - Hala --> Brzozen</option>
            <option value=39320>Alzacka --> Brzozen</option>
            <option disabled value> -- Diana -- </option>
            <option value=38100>Wiklinowa --> Centrum</option>
            <option disabled value> -- Centrum -- </option>
            <option value=35113>Wzg św Maks SKM 03 - Trajtki --> Cisowa</option>
            <option value=35111>Wzg św Maks SKM 01 - Busy --> Oksywie</option>
            <option value=36050>Armii Krajowej --> Domki</option>
            <option value=37070>Mireckiego 02 --> Cisowa</option>
            <option value=39370> -- AMW --</option>
            <option value=39360>Oksywie dolne</option>
        </select>';
        else{
            echo '<select id="mySelect" onchange="myChange()">';
            echo '<option disabled selected value> -- WYBIERZ PRZYSTANEK -- </option>';
                $telegramID=$tg_user['id'];
                $query= "SELECT * FROM [dbo].[VancoFavs] WHERE [user_id]=$telegramID ORDER BY [stop_name]";
                $result=$con->query($query); //Wyślij zapytanie do bazy danych o listę przystanków
                while ($row = $result->fetch(PDO::FETCH_BOTH)) {
                    echo "<option value=" . $row['stop_id'] . ">" . $row['stop_name'] . "</option>"; //Wypisz opcje dla każdego przystanku w bazie danych
                }
            echo '</select>';
        }
        ?>
        <div id=rozklad></div>
        </center>
        <div id=lel></div>
        <div id=refr onclick=myChange()>&#8635</div>
    </body>
</html>
