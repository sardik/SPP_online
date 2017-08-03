<?php
if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	
	echo '<h2>Transaksi Pembayaran Baru</h2><hr>';
	if(isset($_REQUEST['submit'])){
		
		$submit = $_REQUEST['submit'];
		$nis = $_REQUEST['nis'];
	//proses Approve pembayaran, hanya ADMIN
		if($submit=='approve'){
			$kls = $_REQUEST['kls'];
			$bln = $_REQUEST['bln'];
			// $tgl = $_REQUEST['tgl'];
			// $jml = $_REQUEST['jml'];
			// $nis = $_REQUEST['nis'];
			
			$qapprove = mysql_query("UPDATE pembayaran set status = 1 where kelas='$kls' AND nis='$nis' AND bulan='$bln'");
			
			if($qapprove > 0){
				echo '<div class="alert alert-success">Transaksi Berhasil di Setujui';
				echo '<br><br><a href="./admin.php?hlm=dashboard" class="btn btn-sm btn-info">Tutup</a> ';
				echo '</div>';
				// header('Location: ./admin.php?hlm=bayar&submit=v&nis='.$nis);
				// die();
			} else {
				echo 'ada ERROR dg query';
			}
		}
		//proses Void pembayaran, hanya ADMIN
		if($submit=='void'){
			$kls = $_REQUEST['kls'];
			$bln = $_REQUEST['bln'];
			// $tgl = $_REQUEST['tgl'];
			// $jml = $_REQUEST['jml'];
			// $nis = $_REQUEST['nis'];
			
			$qapprove = mysql_query("UPDATE pembayaran set status = 2 where kelas='$kls' AND nis='$nis' AND bulan='$bln'");
			
			if($qapprove > 0){
				echo '<div class="alert alert-success">Transaksi telah di batalkan';
				echo '<br><br><a href="./admin.php?hlm=dashboard" class="btn btn-sm btn-info">Tutup</a> ';
				echo '</div>';
				// header('Location: ./admin.php?hlm=bayar&submit=v&nis='.$nis);
				// die();
			} else {
				echo 'ada ERROR dg query';
			}
		}
	}

		//menampilkan isi data dalam tabel
		$sql = mysql_query("SELECT p.nis,s.nama,p.kelas,j.prodi,t.tapel,p.bulan,p.tgl_bayar,p.jumlah,p.resi,
		CASE when status = '0' then 'Pending'
		when status = '1' then 'Sukses'
		when status = '2' then 'Gagal' END AS status
		FROM pembayaran p left join tapel t on t.id = p.idthajaran
		left join siswa s on s.nis = p.nis
		left join prodi j on j.idprodi = s.idprodi
		WHERE p.status = '0' ORDER BY p.tgl_bayar DESC");
		?>
		
      	<div class="row">
		<div class="col-md-12">
		<table id="example" class="table table-bordered">
		<thead>
		<tr class="info">
		<th>#</th>
		<th>NIS</th>
		<th>Nama</th>
		<th>Kelas</th>
		<th>Jurusan</th>
		<th>Tahun Ajaran</th>
		<th>Bulan</th>
		<th>Tanggal Bayar</th>
		<th>Jumlah</th>
		<th>Resi</th>
		<th>Status</th>
		<th>Aksi</th></tr>
		</thead>
		<tbody>
		<?php
		if( mysql_num_rows($sql) > 0 ){
			$no = 1;
			while(list($nis,$nama,$kelas,$jurusan,$thajaran,$bulan,$tgl,$jumlah,$resi,$status) = mysql_fetch_array($sql)){
				echo '<tr><td>'.$no.'</td>';
				echo '<td>'.$nis.'</td>';
				echo '<td>'.$nama.'</td>';
				echo '<td>'.$kelas.'</td>';
				echo '<td>'.$jurusan.'</td>';
				echo '<td>'.$thajaran.'</td>';
				echo '<td>'.$bulan.'</td>';
				echo '<td>'.$tgl.'</td>';
				echo '<td>'.$jumlah.'</td>';
				echo '<td>'.$resi.'</td>';
				echo '<td>'.$status.'</td>';
				echo '<td><a href="./admin.php?hlm=dashboard&submit=approve&kls='.$kelas.'&nis='.$nis.'&bln='.$bulan.'" class="btn btn-success btn-xs">Approve</a> ';
				echo '<a href="./admin.php?hlm=dashboard&submit=void&kls='.$kelas.'&nis='.$nis.'&bln='.$bulan.'" class="btn btn-danger btn-xs">Void</a></td>';
				echo '</tr>';
				$no++;
			}
		
		} else {
			echo '<tbody><tr><td colspan="5"><em>Belum ada data</em></td></tr></tbody>';
		}
	
		?>
		</tbody>
		</table>
		</div>
		</div>

	<?php }
?>