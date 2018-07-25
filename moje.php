<html>
    <head>
        <style>
            html{
                background-color: black;
                color:white;
            }
            input[type="text"]{
                width:60vw;
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
            <h1>Moje przystanki</h1>
            <a href="dodaj.php">Dodaj przystanek</a>
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
    if(isset($_POST)){
        $stop=$_POST['stopID'];
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $stop)){
            echo "<script>alert('Ty śmieciu jebany')</script>";
        }else{
            if($_POST['del']){
                $query= "DELETE FROM [dbo].[VancoFavs] WHERE [user_id]=$telegramID AND [stop_id]=$stop";
                $con->query($query);
            }else if($_POST['dodaj']){
                $nazwa=$_POST['stopName'];
                if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $nazwa)){
                    echo "<script>alert('Nie wprowadzono zmiany, nazwa zawiera niedozwolone znaki!')</script>";
                }
                else{
                    $query="INSERT INTO [dbo].[VancoFavs](user_id,stop_id,stop_name) VALUES('$telegramID','$stop','$nazwa')";
                    //echo $query;
                    $con->query($query);
                }
            }else{
                $nazwa=$_POST['stopName'];
                if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $nazwa)){
                    echo "<script>alert('Nie wprowadzono zmiany, nazwa zawiera niedozwolone znaki!')</script>";
                }
                else{
                    $query="UPDATE [dbo].[VancoFavs] SET [stop_name]='$nazwa' WHERE [user_id]=$telegramID AND [stop_id]=$stop";
                    //echo $query;
                    $con->query($query);
                }
            }
        }
        
    }
    $query= "SELECT * FROM [dbo].[VancoFavs] WHERE [user_id]=$telegramID ORDER BY [stop_name]";
    $result=$con->query($query); //Wyślij zapytanie do bazy danych o listę przystanków
    while ($row = $result->fetch(PDO::FETCH_BOTH)) {
        echo "<form style='margin-bottom: 0px' action=moje.php method=POST >";
        echo "<input name='stopID' type=hidden value=" . $row['stop_id'] . "></input>";
        echo "<input name='stopName' type=text value=\"" . $row['stop_name'] . "\"></input>";
        echo "Usuń<input name=del type=checkbox>";
        echo "<input type=submit value=Wyślij></input>";
        echo "</form>";
    }
?>
        </center>
    </body>
</html>