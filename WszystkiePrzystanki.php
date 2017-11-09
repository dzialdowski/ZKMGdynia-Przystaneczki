<?php 
$con=mysqli_connect("localhost", "root", "", "komunikacjamiejska"); //Połączenie z bazą danych
if (mysqli_connect_errno()) {
    echo "<script>console.log('Połączenie z bazą danych nieudane: '" . mysqli_connect_error() . ");</script>";
}//rezygnuję z informacji o udanym połączeniu, informacja zbędna
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
        
    </head>    
    <body>
        <script>         
            function change(x){
                $.post("ajaxReturn.php",
                {
                    PrzystanekID: x //Wyślij request o rozkład jazdy dla przystanku x
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
        </script>
        <center>
        <header>
            <h1>PRZYSTANECZKI ZKM</h1>
        </header>
        
        <select id="mySelect" onchange="myChange()">

        <?php 
            $result = mysqli_query($con, "SELECT * FROM Przystanki ORDER BY nazwaPrzystanku"); //Wyślij zapytanie do bazy danych o listę przystanków
            while ($row = mysqli_fetch_array($result)) { //tabelę można wygenerowac korzystając z pliku Linie.php
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