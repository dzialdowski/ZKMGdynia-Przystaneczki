<?php
$con=mysqli_connect("localhost", "root", "", "komunikacjamiejska");
if (mysqli_connect_errno()) {
    echo "<script>console.log('Połączenie z bazą danych nieudane: '" . mysqli_connect_error() . ");</script>";
}
include('simple_html_dom.php');
$search[0]="USB";
$search[1]="klimatyzacja";
$dane="";
$isFirst=true;
$html = file_get_html("http://www.zkmgdynia.pl/?mod=bazapojazdow&lang=pl&nr=");
foreach($html->find('#bpSelNrInw option') as $e) { //dla każdego nr inwentarzowego na stronie
    if ($isFirst) { // ominięcie tytułu w menu wyboru
        $isFirst = false;
        continue;
    } 
    $line = $e->plaintext;
        $html = file_get_html("http://www.zkmgdynia.pl/?mod=bazapojazdow&lang=pl&nr=$line");
        foreach($html->find('.bpListaElem') as $e)  // dla każdej cechy pojazdu
            $dane .= $e->plaintext;
            if(strpos($dane, $search[0]) !== false) { 
                $USB = 1;
            }else {
                $USB = 0;
            }
            if(strpos($dane, $search[1]) !== false) { 
                $klima = 1;
            }else {
                $klima = 0;
            }
            echo $line . " " . $USB . " " . $klima . "<br>";
            $query="INSERT INTO `busy` (`Bus`, `USB`, `Klima`) VALUES ($line, $USB, $klima)";
            mysqli_query($con,$query);
            $dane="";

            
    
}
?>