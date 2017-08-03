<?php
if( empty( $_SESSION['iduser'] ) ){
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	/* tahapan pembayaran SPP
		1. masukkan nis
		2. tampilkan histori pembayaran (jika ada) dan form pembayaran
		3. proses pembayaran, kembali ke nomor 2
	*/
	echo '<h2>Pembayaran SPP</h2><hr>';
	
	if(isset($_REQUEST['submit'])){
		//proses pembayaran secara bertahap
		$submit = $_REQUEST['submit'];
		$nis = $_REQUEST['nis'];
		
		//proses simpan pembayaran
		if($submit=='bayar'){
			$kls = $_REQUEST['kls'];
			$idthn = $_REQUEST['thajaran'];
			$bln = $_REQUEST['bln'];
			$tgl = $_REQUEST['tgl'];
			$jml = $_REQUEST['jml'];
			
			if( $_SESSION['admin'] == 1 ){
			$qbayar = mysql_query("INSERT INTO pembayaran VALUES('$kls','$idthn','$nis','$bln','$tgl','$jml','admin','1')");
			}
			else{
				$resi = $_REQUEST['resi'];
			$qbayar = mysql_query("INSERT INTO pembayaran VALUES('$kls','$idthn','$nis','$bln','$tgl','$jml','$resi','0')");
			}

			if($qbayar > 0){	
				if( $_SESSION['admin'] != 1 ){
				echo '<div class="alert alert-info">Data Tersimpan, Proses Ferivikasi maksimal 2 hari, sukses atau gagal cek info status';
				echo '<br><a href="./admin.php?hlm=bayar&submit=v&nis='.$nis.'" class="btn btn-sm btn-success">Tutup</a> ';
				echo '</div>';
					}
				// header('Location: ./admin.php?hlm=bayar&submit=v&nis='.$nis);
				// die();
			} else {
				echo 'ada ERROR dg query '.$kls.'';
			}
		}
		
		//proses hapus pembayaran, hanya ADMIN
		if($submit=='hapus'){
			$kls = $_REQUEST['kls'];
			$bln = $_REQUEST['bln'];
			$tgl = $_REQUEST['tgl'];
			$jml = $_REQUEST['jml'];
			
			$qbayar = mysql_query("DELETE FROM pembayaran WHERE kelas='$kls' AND nis='$nis' AND bulan='$bln'");
			
			if($qbayar > 0){
				header('Location: ./admin.php?hlm=bayar&submit=v&nis='.$nis);
				die();
			} else {
				echo 'ada ERROR dg query';
			}
		}
		if( $_SESSION['admin'] == 1 ){
		//tampilkan data siswa
		$qsiswa = mysql_query("SELECT * FROM siswa WHERE nis='$nis'");
		} else {
		//tampilkan data siswa
		$id = $_SESSION['iduser'] ;
		$qsiswa = mysql_query("SELECT * FROM siswa LEFT JOIN user on user.username = siswa.nis 
		WHERE iduser='$id'");
		}
		list($nis,$nama,$idprodi,$idthajaran) = mysql_fetch_array($qsiswa);

		//tampilkan data biaya X
		$qbiayaX = mysql_query("SELECT tingkat,jumlah FROM jenis_bayar WHERE idthajaran='$idthajaran' and tingkat='X'");
		list($tingkatX,$jumlahX) = mysql_fetch_array($qbiayaX);

		//tampilkan data biaya XI
		$qbiayaXI = mysql_query("SELECT tingkat,jumlah FROM jenis_bayar WHERE idthajaran='$idthajaran' and tingkat='XI'");
		list($tingkatXI,$jumlahXI) = mysql_fetch_array($qbiayaXI);

		//tampilkan data biaya XII
		$qbiayaXII = mysql_query("SELECT tingkat,jumlah FROM jenis_bayar WHERE idthajaran='$idthajaran' and tingkat='XII'");
		list($tingkatXII,$jumlahXII) = mysql_fetch_array($qbiayaXII);

    echo '<div class="row">';
		echo '<div class="col-lg-12"><table class="table table-bordered">';
		echo '<tr><td colspan="4">Nomor Induk</td><td colspan="3">'.$nis.'</td>';
    echo '<td><a href="./cetak.php?nis='.$nis.'" target="_blank" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> cetak semua</a></td></tr>';
		echo '<tr><td colspan="4">Nama Siswa</td><td colspan="4">'.$nama.'</td></tr>';
		echo '<tr><td colspan="4">Pembayaran</td><td colspan="4">';
?>
<form class="form-inline" role="form" method="post" action="./admin.php?hlm=bayar">
  <input type="hidden" name="nis" value="<?php echo $nis; ?>">
	<input type="hidden" name="tgl" value="<?php echo date("Y-m-d"); ?>">
	
	<div class="form-group">
      <label class="sr-only" for="thajaran">Tahun Ajaran</label>
	  <select name="thajaran" class="form-control" id="thajaran">
	  <?php
		$qthajaran = mysql_query("SELECT idthajaran, tapel FROM siswa left join tapel on id = idthajaran WHERE  nis='$nis'");
		while(list($idthn,$thajaran)=mysql_fetch_array($qthajaran)){
			echo '<option value="'.$idthn.'">'.$thajaran.'</option>';
		}
	  ?>
	  </select>
	</div>
	<div class="form-group">
      <label class="sr-only" for="kls">Kelas</label>
	  <select name="kls" class="form-control" id="kls" required="" oninvalid="this.setCustomValidity('Pilih kelas untuk menentukan biaya')">
			<option value="">:: PILIH KELAS ::</option>
			<option value='X'>X</option>
			<option value='XI'>XI</option>
			<option value='XII'>XII</option>
	  </select>
	</div>
  <div class="form-group">
    <label class="sr-only" for="bln">Bulan</label>
	<select name="bln" id="bln" class="form-control">
	<?php
		for($i=1;$i<=12;$i++){
			$b = date('F',mktime(0,0,0,$i,10));
			echo '<option value="'.$b.'">'.$b.'</option>';
		}
	?>
	</select>
  </div>
  <div class="form-group">
	<label class="sr-only" for="jml">Jumlah</label>
	<div class="input-group">
		<div class="input-group-addon">Rp.</div>
		<input type="text" class="form-control" id="jml" name="jml" placeholder="Jumlah">
		<input type="hidden" class="form-control" id="jmlX" name="jmlX" value="<?php echo $jumlahX; ?>">
		<input type="hidden" class="form-control" id="jmlXI" name="jmlXI" value="<?php echo $jumlahXI; ?>">
		<input type="hidden" class="form-control" id="jmlXII" name="jmlXII" value="<?php echo $jumlahXII; ?>">
	</div>
	<?php
	if( $_SESSION['admin'] != 1 ){	
	?>
	<div class="input-group">
		<div class="input-group-addon">Resi</div>
		<input type="text" class="form-control" id="resi" name="resi" required placeholder="No resi">
	</div>
	<?php } ?>
  </div>
  <button type="submit" class="btn btn-default" name="submit" value="bayar">Bayar</button>
</form>
<?php
		echo '</td></tr>';
		echo '<tr class="info"><th width="50">#</th><th>Kelas</th><th width="120">Tahun Ajaran</th><th width="100">Bulan</th><th>Tanggal Bayar</th><th>Jumlah</th><th>Status</th>';
		echo '<th>&nbsp;</th>';
		echo '</tr>';
		
		if( $_SESSION['admin'] == 1 ){	
		//tampilkan histori pembayaran, jika ada
		$qbayar = mysql_query("SELECT kelas,tapel,bulan,tgl_bayar,jumlah, 
		CASE when status = '0' then 'Pending'
		when status = '1' then 'Sukses'
		when status = '2' then 'Gagal' END AS status
		FROM pembayaran left join tapel on id = idthajaran
		WHERE nis='$nis' and status = '1' ORDER BY tgl_bayar DESC");
		} else {
			//tampilkan histori pembayaran, jika ada user
		$qbayar = mysql_query("SELECT kelas,tapel,bulan,tgl_bayar,jumlah, 
		CASE when status = '0' then 'Pending'
		when status = '1' then 'Sukses'
		when status = '2' then 'Gagal' END AS status
		FROM pembayaran left join tapel on id = idthajaran
		WHERE nis='$nis' ORDER BY tgl_bayar DESC");
		}
		if(mysql_num_rows($qbayar) > 0){
			$no = 1;
			while(list($kelas,$thajaran,$bulan,$tgl,$jumlah,$status) = mysql_fetch_array($qbayar)){
				echo '<tr><td>'.$no.'</td>';
				echo '<td>'.$kelas.'</td>';
				echo '<td>'.$thajaran.'</td>';
				echo '<td>'.$bulan.'</td>';
				echo '<td>'.$tgl.'</td>';
				echo '<td>'.$jumlah.'</td>';
				echo '<td>'.$status.'</td><td width="200">';
				
				if( $_SESSION['admin'] == 1 ){
					echo '<a href="./admin.php?hlm=bayar&submit=hapus&kls='.$kelas.'&nis='.$nis.'&bln='.$bulan.'" class="btn btn-danger btn-xs">Hapus</a>';
				}
            echo ' <a href="./cetak.php?submit=nota&kls='.$kelas.'&nis='.$nis.'&bln='.$bulan.'" target="_blank" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>';
				echo '</td></tr>';
				
				$no++;
			}
		} else {
			echo '<tr><td colspan="12"><em>Belum ada data!</em></td></tr>';
		}
		echo '</table></div></div>';
	} else {
?>
<?php
if( $_SESSION['admin'] == 1 ){	
?>
<!-- form input nomor induk siswa -->
<form class="form-horizontal" role="form" method="post" action="./admin.php?hlm=bayar">
  <div class="form-group">
    <label for="nis" class="col-sm-2 control-label">Nomor Induk Siswa</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="nis" name="nis" placeholder="Nomor Induk Siswa" required autofocus>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-3">
      <button type="submit" name="submit" class="btn btn-default">Lanjut</button>
    </div>
  </div>
</form>
<?php 
		}
		else{
			header('Location: ./admin.php?hlm=bayar&submit=v&nis='.$nis);
		}
	}
}
?>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
	$(document).ready(function() {
			$("#kls").change(function () {
				var rec = $("#kls option:selected").val();
				var jmlX = document.getElementById("jmlX").value;
				var jmlXI = document.getElementById("jmlXI").value;
				var jmlXII = document.getElementById("jmlXII").value;
				switch (rec) {
					case '':
								$('#jml').val("");
								break;
						case 'X':
								$('#jml').val(jmlX);
								break;
						case 'XI':
								$('#jml').val(jmlXI);
								break;
						case 'XII':
								$('#jml').val(jmlXII);
								break;
				}
			});
	});
</script>

