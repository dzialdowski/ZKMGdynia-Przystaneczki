<?php 
error_reporting(0);
function getNormalnaNazwa($routeID){
    $con=mysqli_connect("localhost", "root", "", "komunikacjamiejska");
    if (mysqli_connect_errno()) {
        echo "<script>console.log('Połączenie z bazą danych nieudane: '" . mysqli_connect_error() . ");</script>";
    }
    $query="SELECT NrBusa FROM Linie WHERE routeID=$routeID";//tabelę można wygenerowac korzystając z pliku Linie.php
    $result = mysqli_query($con, $query);
    $row=mysqli_fetch_assoc($result);
    if($row['NrBusa']==NULL){
        return ltrim(substr($routeID, 1), '0');
    }else{
        return $row['NrBusa'];
    }
    mysqli_close($con);
    
}
function getPojazd($BusId){
    $con=mysqli_connect("localhost", "root", "", "komunikacjamiejska");
    if (mysqli_connect_errno()) {
        echo "<script>console.log('Połączenie z bazą danych nieudane: '" . mysqli_connect_error() . ");</script>";
    }
    $class="bus ";
    $query="SELECT * FROM busy WHERE Bus=$BusId"; // tabelę można wygenerowac korzystając z plikow w folderze busy
    $result = mysqli_query($con, $query);
    $row=mysqli_fetch_assoc($result);
    if($row['USB']==1){
        $class .="USB ";
    }else{
        $class .="nullu ";
    }
    if($row['KLIMA']==1){
        $class .="Klima";
    }else{
        $class .="nullk";
    }
    return "<td align=center class=\"$class\">$BusId</td>";

}
function getBus($StopId){
    $str = file_get_contents("http://87.98.237.99:88/delays?stopId={$StopId}");
    $json = json_decode($str, true);
    if(empty($json['delay'])) {
        echo "<h2><marquee>W najbliższym czasie na danym przystanku nic nie będzie jechać</marquee></h2>";
    }
    else {
        echo "
        <div id=ajaxContainer>
        <table>
            
            <tr>
                <th width='50px'>Linia</th>
                <th width='200px'>Kierunek</th>
                <th width='70px'>Odjazd</th>
                <th>Rzeczywisty odjazd</th>
                <th>Rozkładowy odjazd</th>
                <th>Opóźnienie</th>
                <th>Nr pojazdu</th>
            </tr>
        
        ";
        echo "<br>";
        $iloscBusow=0;
        foreach ($json['delay'] as $row) {
            $bus=$row['vehicleCode'];
            echo "<tr>";
            echo "<td class='sign qwe'>". getNormalnaNazwa($row['routeId']) ."</td>"; 
            echo "<td class='sign dd'>". $row['headsign'] ."</td>";
            echo '<td id="czas'. $iloscBusow .'" class="sign qwe"></td>';
            echo '<td id="czasT'. $iloscBusow .'">'. $row['estimatedTime'] .'</td>';
            echo "<td>". $row['theoreticalTime'] ."</td>";
            echo "<td>". $row['delayInSeconds'] ."s</td>";
            echo getPojazd($bus);
            echo "</tr>"; 
            $iloscBusow++;
        }
        echo "</table>";
        echo "<div id=busy>". $iloscBusow ."</div></div>";
    }
}



if ((!isset($_POST) || empty($_POST))){
    echo "Brak danych do wyświetlenia -> Nie podano informacji o przystanku";
}
else{
    $przystanek=$_POST['PrzystanekID'];
    getBus($przystanek);
    
}

?>