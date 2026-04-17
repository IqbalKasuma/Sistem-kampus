<!DOCTYPE html>
<html>
<head>
	<title>INTRODUCTION</title>
</head>
<body> 
	<h1>LATIHAN PHP DASAR</h1>
	<?php
	//variabel
	$nama = "Iqbal Kasuma";
	$umur = 23;
	
	echo "<p>Nama saya =  $nama</p>";
	echo "<p>Umur saya = $umur tahun</p>";

	//percabangan
	if ($umur >= 18) {
		echo "<p>Status : Dewasa</p>";
	} else {
		echo "<p> Status : Belum dewasa</p>";
	}
	
	//Perulangan 
	echo "<h3> Daftar angka : </h3>";
	for ($i = 1; $i <= 5; $i++)
		echo "Angka ke-$i <br>";
?>
</body>
</html>
