<?php
session_start();
include "koneksi.php";
if( empty( $_SESSION['iduser'] ) ){
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
   
      //echo $printTestText;
      // $handle = printer_open('PDFcreator');	             //nama printer
      // printer_set_option($handle, PRINTER_MODE, "TEXT");  //mode printer: RAW, TEXT
      // printer_write($handle, $printTestText);
      //printer_close($handle);
      
      //tutup jendela setelah cetak
      //echo '<script>window.close();</script>';
   
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SPP Online</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

	<style type="text/css">
	body {
	  min-height: 200px;
	  padding-top: 50px;
	}
   @media print {
      .noprint {
         display: none;
      }
      .noprint2 {
         display: none;
      }
   }
	</style>
  </head>

  <body>
<?php

    $nis = $_REQUEST['nis'];
   if(isset($_REQUEST['submit'])){
      //cetak nota pembayaran sesuai NIS dan BULAN
      $submit = $_REQUEST['submit'];
      $kls = $_REQUEST['kls'];
      $bln = $_REQUEST['bln'];
      
      //print: $nis, $nama, $kls, $bln, $tgl_bayar, $jml
      $sql = mysql_query("SELECT s.nis,s.nama,p.kelas,t.tapel,p.bulan,p.tgl_bayar,p.jumlah FROM siswa s INNER JOIN pembayaran p ON s.nis = p.nis AND p.nis='$nis' 
      left join tapel t on t.id = s.idthajaran");
      list($nis,$nama,$kelas,$thajar,$bulan,$tgl_bayar,$jml) = mysql_fetch_array($sql);
      
      echo '<div class="container">';
      echo '<h3>Bukti Pembayaran SPP</h3>';
      echo '<div class="row">';
      echo '<div class="col-sm-6"><table class="table table-bordered">';
      echo '<tr><td colspan="2">NIS</td><td colspan="3">'.$nis.'</td></tr>';
      echo '<tr><td colspan="2">Nama Siswa</td><td colspan="3">'.$nama.'</td></tr>';
      echo '<tr><td colspan="2">Kelas</td><td colspan="3">'.$kelas.'</td></tr>';
      echo '<tr><td colspan="2">TH. Ajaran</td><td colspan="3">'.$thajar.'</td></tr>';
      echo '<tr><td colspan="2">Bulan</td><td colspan="3">'.$bulan.'</td></tr>';
      echo '<tr><td colspan="2">Tanggal</td><td colspan="3">'.$tgl_bayar.'</td></tr>';
      echo '<tr><td colspan="2">Jumlah</td><td colspan="3">'.$jml.'</td></tr>';
      echo '</table></div></div>';
      echo '<a class="noprint2 btn btn-default" onclick="fnCetak2()">Cetak</a>';
      echo '</div>';
?>
      <script src="js/jquery.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script type="text/javascript">
        $(".force-logout").alert().delay(3000).slideUp('slow', function(){
          window.location = "./logout.php";
        });
          function fnCetak2() {
            window.print();
          }
      </script>
  <?php
} else {
      //cetak seluruh pembayaran sesuai NIS
   $qsiswa = mysql_query("SELECT * FROM siswa WHERE nis='$nis'");
   list($nis,$nama,$idprodi) = mysql_fetch_array($qsiswa);

   echo '<div class="container">';
   echo '<h3>Bukti Pembayaran SPP</h3>';
   echo '<div class="row">';
   echo '<div class="col-sm-6"><table class="table table-bordered">';
   echo '<tr><td colspan="2">Nomor Induk</td><td colspan="3">'.$nis.'</td></tr>';
   echo '<tr><td colspan="2">Nama Siswa</td><td colspan="3">'.$nama.'</td></tr>';
   echo '<tr class="info"><th width="50">#</th><th width="100">Kelas</th><th>Bulan</th><th>Tanggal Bayar</th><th>Jumlah</th>';
   echo '</tr>';
   
   //tampilkan histori pembayaran, jika ada
   $qbayar = mysql_query("SELECT kelas,bulan,tgl_bayar,jumlah FROM pembayaran WHERE nis='$nis' ORDER BY tgl_bayar DESC");
   if(mysql_num_rows($qbayar) > 0){
      $no = 1;
      while(list($kelas,$bulan,$tgl,$jumlah) = mysql_fetch_array($qbayar)){
         echo '<tr><td>'.$no.'</td>';
         echo '<td>'.$kelas.'</td>';
         echo '<td>'.$bulan.'</td>';
         echo '<td>'.$tgl.'</td>';
         echo '<td>'.$jumlah.'</td>';
         echo '</tr>';
         
         $no++;
      }
   } else {
      echo '<tr><td colspan="6"><em>Belum ada data!</em></td></tr>';
   }
   echo '</table></div></div>';
   echo '<a class="noprint btn btn-default" onclick="fnCetak()">Cetak</a>';
   echo '</div>'; 
?>

    <!-- Bootstrap core JavaScript, Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
	 <script type="text/javascript">
		$(".force-logout").alert().delay(3000).slideUp('slow', function(){
			window.location = "./logout.php";
		});
      function fnCetak() {
         window.print();
      }
	 </script>
  </body>
</html>
<?php
   }
}
?>