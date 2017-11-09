<?php
$con=mysqli_connect("localhost", "root", "", "KomunikacjaMiejska");
if (mysqli_connect_errno()) {
    echo "<script>console.log('Połączenie z bazą danych nieudane: '" . mysqli_connect_error() . ");</script>";
}
$str = file_get_contents("./routes.json",true);
$json = json_decode($str, true);
foreach ($json['2017-11-08']['routes'] as $row) { // datę zmienic tak żeby było dobrze, zgodnie z http://91.244.248.19/dataset/c24aa637-3619-4dc2-a171-a23eec8f2172/resource/4128329f-5adb-4082-b326-6e1aea7caddf/download/routes.json
    $linia='"'. $row['routeId']. '"';
    $nazwa='"'. $row['routeShortName']. '"';
    $query="INSERT INTO `linie` (`routeID`, `nrBusa`) VALUES ($linia, $nazwa)";
    echo $query;
    mysqli_query($con,$query);
}