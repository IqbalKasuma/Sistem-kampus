<?php
//switch hal 20
$nilai = 2;

switch ($nilai) {
    case 100:
        echo "Great!!";
        break;
    case 50:
        echo "Oh,No!!";
        break;
    default:
        echo "Nilai tidak diketahui";
}
echo"<br>";
//hal 21
$halaman ="berita";
switch($halaman){
	case "home";
	echo "Anda memilih home";
	break;
	case "berita";
	echo "Anda memilih berita";
	break;
	case "artikel";
	echo "Anda memilih artikel";
	break;
	default:
		echo "Halaman yang anda cari tidak tersedia";
}
?>