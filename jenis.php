<?php
if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	if( isset( $_REQUEST['aksi'] )){
		//load sub-halaman yang sesuai
		$aksi = $_REQUEST['aksi'];
		switch($aksi){
			case 'baru':
				include 'jenis_baru.php';
				break;
			case 'edit':
				include 'jenis_edit.php';
				break;
			case 'hapus':
				include 'jenis_hapus.php';
				break;
		}
	} else {
		//tampilkan daftar jenis_pembayaran
		$sql = mysql_query("SELECT tapel,tingkat,jumlah FROM jenis_bayar left join tapel on id = idthajaran
		ORDER BY idthajaran DESC, tingkat ASC");
		?>
		<h2>Jenis Pembayaran</h2><hr>
      	<div class="row">
		<div class="col-md-7">
		<table id="example" class="table table-bordered">
		<thead>
		<tr class="info"><th>#</th><th width="200">Tahun Pelajaran</th><th>Tingkat</th><th>Jumlah Nominal</th>
		<th width="200"><a href="admin.php?hlm=master&sub=jenis&aksi=baru" class="btn btn-default btn-xs">Tambah Data</a></th></tr>
		</thead>
		<tbody>
		<?php
		if(mysql_num_rows($sql) > 0){
			$no = 1;
			while(list($tapel,$tingkat,$jumlah) = mysql_fetch_array($sql)){
				echo '<tr><td>'.$no.'</td>';
				echo '<td>'.$tapel.'</td><td>'.$tingkat.'</td><td>Rp <span class="pull-right">'.$jumlah.'</span></td><td>';
				echo '<a href="admin.php?hlm=master&sub=jenis&aksi=edit&tapel='.$tapel.'&tingkat='.$tingkat.'" class="btn btn-success btn-xs">Edit</a> ';
				echo '<a href="admin.php?hlm=master&sub=jenis&aksi=hapus&tapel='.$tapel.'&tingkat='.$tingkat.'" class="btn btn-danger btn-xs">Hapus</a>';
				echo '</td></tr>';
				$no++;
			}
		} else {
			echo '<tr><td colspan="5"><em>Belum ada data!</em></td></tr>';
		}
		?>
		</tbody>
		</table>
		</div>
		</div>
	<?php }
}
?>