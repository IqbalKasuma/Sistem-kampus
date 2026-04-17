<?php
//do-while hal 29,38
$ulangi =10;

do {
    echo "<p>Ini adalah perulangan ke-$ulangi</p>";
    $ulangi--;
}while ($ulangi > 0);

echo "<br>";
$i = 10;
do {
	echo $i;
	$i = $i - 2;
}while ($i > 0);
?>