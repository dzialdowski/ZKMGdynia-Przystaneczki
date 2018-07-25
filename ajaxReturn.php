<?php 
$con=new PDO("sqlsrv:Server = serwer,1433; Database = komunikacjamiejska", "", "");
error_reporting(0);
function getNormalnaNazwa($routeID){
    $query="SELECT [NrBusa] FROM [dbo].[linie] WHERE [routeID]=$routeID";
    $result=$con->query($query);
    $row=$result->fetchAll(PDO::FETCH_ASSOC);
    if($row[0]['NrBusa']==NULL){
        return ltrim(substr($routeID, 1), '0');
    }else{
        return $row[0]['NrBusa'];
    }
    
}
function getPojazd($BusId){
    $class="bus ";
    $query="SELECT * FROM [dbo].[busy] WHERE [Bus]=$BusId";
    $result=$con->query($query);
    $row=$result->fetchAll(PDO::FETCH_ASSOC);
    if($row[0]['USB']==1){
        $class .="USB ";
    }else{
        $class .="nullu ";
    }
    if($row[0]['KLIMA']==1){
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
        $str = file_get_contents("http://87.98.237.99:88/delays?stopId={$StopId}");
        $json = json_decode($str, true);
        if(empty($json['delay'])) {
            echo "<h2><marquee>W najbliższym czasie na danym przystanku nic nie będzie jechać</marquee></h2>";
            echo "<div class='XD'>";
            echo $str;
            echo "</div>";
    		echo "<div class='sign'>Data aktualizacji danych: ". $json['lastUpdate'] ."</div>";
        }
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
            $czas=$row['delayInSeconds'];
            settype($czas, "integer");
            $sekundy = $czas%60;
            $minuty = $czas/60;
            settype($delay, "string");
            settype($minuty, "integer");
            if($minuty>0){
                $delay="<td class='min'>".$minuty ."min ". $sekundy;
            }else if($czas<0){
                $delay="<td class='min green'>". -$czas;
            }
            else{
                $delay="<td class='min'>".$sekundy;
            }
            $godzina=explode(":",$row['theoreticalTime']);
            $czasEstymowany= mktime($godzina[0],$godzina[1], 0, date("m"), date("d"), date("Y"));
            $czasEstymowany= date("Y-m-d H:i:s",$czasEstymowany);
            $czasEstymowany= new DateTime($czasEstymowany, new DateTimeZone("Europe/Warsaw"));
            $czasEstymowany->modify("+".$row['delayInSeconds']." seconds");
            $bus=$row['vehicleCode'];
            echo "<tr>";
            echo "<td class='sign qwe'><a href=http://www2.zkmgdynia.pl/index_rozklady.php?linia=". getNormalnaNazwa($row['routeId']) . ">". getNormalnaNazwa($row['routeId']) ."</a></td>"; 
            echo "<td class='sign dd'><a href=Trasa.php?routeID=". $row['routeId'] . "&tripID=" . $row['tripId'] . "&trip=" . $row['trip'] .">". $row['headsign'] ."</a></td>";
            echo '<td id="czas'. $iloscBusow .'" class="sign qwe min"></td>';
            echo '<td class="min" id="czasT'. $iloscBusow .'">'. $czasEstymowany->format("H:i:s") .'</td>';
            echo "<td class='min'>". $row['theoreticalTime'] ."</td>";
            echo  $delay ."s</td>";
            echo getPojazd($bus);
            echo "</tr>"; 
            $iloscBusow++;
        }
        echo "</table>";
		echo "<br/><div class='sign'>Data aktualizacji danych:<br/>". $json['lastUpdate'] ."</div>";
        echo "<div id=busy>". $iloscBusow ."</div></div>";
    }
}
if (!isset($_POST) || empty($_POST)){
	if (!isset($_GET) || empty($_GET)){
		echo "Brak danych do wyświetlenia -> Nie podano informacji o przystanku";
	}
	else{
		$przystanek=$_GET['PrzystanekID'];
		getBus($przystanek);
	} 
}
else{
    $przystanek=$_POST['PrzystanekID'];
    getBus($przystanek);
}

?>