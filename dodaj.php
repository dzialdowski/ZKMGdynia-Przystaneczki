<?php
$con=new PDO("sqlsrv:Server = serwer,1433; Database = komunikacjamiejska", "", ""); //Połączenie z bazą danych
define('BOT_USERNAME', ''); // place username of your bot here
function getTelegramUserData() {
    if (isset($_COOKIE['tg_user'])) {
        $auth_data_json = urldecode($_COOKIE['tg_user']);
        $auth_data = json_decode($auth_data_json, true);
        return $auth_data;
    }
    return false;
}
$tg_user = getTelegramUserData();
$telegramID=$tg_user['id'];
?>
<html>
    <head>
        <style>
            html{
                background-color: black;
                color:white;
            }
            input[type="text"]{
                width:40vw;
                height: 30px;
            }
            form:{
                color: white;
                margin-bottom: 0;
                margin-top:0;
            }
            form:nth-child(odd){
                background-color: rgb(29, 29, 29);
            }
            form:nth-child(even){
                background-color: rgb(60, 60, 60);
            }
        </style>
    </head>
    <body>
        <center>
            <?php
                $str = file_get_contents('http://91.244.248.19/dataset/c24aa637-3619-4dc2-a171-a23eec8f2172/resource/cd4c08b5-460e-40db-b920-ab9fc93c1a92/download/stops.json');
                $json = json_decode($str, true);
                $dzisiaj = date("Y-m-d"); 
                foreach ($json[$dzisiaj]['stops'] as $row) {
                    $przystanek="'". $row['stopId']. "'";
                    $nazwa="'". $row['stopDesc']. "'";
                    echo "<form style='margin-bottom: 0px' action=moje.php method=POST >";
                    echo "<input name='stopID' type=hidden value=" . $przystanek . "></input>";
                    echo "<input name='stopName' type=text placeholder=\"" . $nazwa . "\"></input>";
                    echo "<a target=\"_blank\" rel=\"noopener noreferrer\" href=przystanek.php?stopID=$przystanek >Podgląd przystanku</a>";
                    echo "<input name=dodaj type=hidden value=1>";
                    echo "<input type=submit value=Wyślij></input>";
                    echo "</form>";
                }
            ?>
        </center>
    </body>
</html>