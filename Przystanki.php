<?php
$con=mysqli_connect("localhost", "root", "", "komunikacjamiejska");
if (mysqli_connect_errno()) {
    echo "<script>console.log('Połączenie z bazą danych nieudane: '" . mysqli_connect_error() . ");</script>";
}
$str = file_get_contents("stops.json");
$json = json_decode($str, true);
foreach ($json['2017-11-08']['stops'] as $row) {//datę zmienic tak żeby było dobrze, zgodnie z zawartością http://91.244.248.19/dataset/c24aa637-3619-4dc2-a171-a23eec8f2172/resource/cd4c08b5-460e-40db-b920-ab9fc93c1a92/download/stops.json
    $przystanek='"'. $row['stopId']. '"';
    $nazwa='"'. $row['stopDesc']. '"';
    $query="INSERT INTO `przystanki` (`idPrzystanku`, `nazwaPrzystanku`) VALUES ($przystanek, $nazwa)";
    echo $query;
    mysqli_query($con,$query);
}