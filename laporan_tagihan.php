<?php
if( empty( $_SESSION['iduser'] ) ){
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else { ?>

   <h2>Tagihan Pembayaran</h2><hr>
   <a class="noprint pull-right btn btn-default" onclick="fnCetak()">
   <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Cetak</a>

   <?php
   $sql = mysql_query("SELECT s.nis,s.nama,k.kelas,p.bulan,p.jumlah FROM (siswa s INNER JOIN kelas k ON s.nis = k.nis) LEFT JOIN pembayaran p ON s.nis = p.nis ORDER BY k.kelas, s.nis");
   ?>
   <div class="row">
   <div class="col-md-7">
   <table id="example" class="table table-bordered">
   <thead>
   <tr class="info"><th width="50">#</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Bulan</th><th>Jumlah</th></tr>
   </thead>
   <tbody>
   <?php
   $no=1;
   while(list($nis,$nama,$kls,$bln,$jml)=mysql_fetch_array($sql)){
      echo '<tr><td>'.$no.'</td><td>'.$nis.'</td><td>'.$nama.'</td><td>'.$kls.'</td>';
      if(empty($bln) AND empty($jml)){
         echo '<td>--</td><td>BL</td></tr>';
      } else {
         echo '<td>'.$bln.'</td><td>LUNAS</td></tr>';
      }
      $no++;
   }
   ?>
   </tbody>
   </table>
   </div>
   </div>
<?php }
?>