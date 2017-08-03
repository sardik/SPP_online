<?php
if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	if( isset( $_REQUEST['aksi'] )){
		$aksi = $_REQUEST['aksi'];
		switch($aksi){
			case 'baru':
				include 'siswa_baru.php';
				break;
			case 'edit':
				include 'siswa_edit.php';
				break;
			case 'hapus':
				include 'siswa_hapus.php';
				break;
		}
	} else {
		$sql = mysql_query("SELECT * FROM siswa ORDER BY nis DESC");
		?>
		<h2>Daftar Siswa</h2><hr>
      	<div class="row">
		<div class="col-md-9">
		<table  id="example" class="table table-bordered">

		<thead>
		<tr class="info">
		<th>#</th>
		<th width="100">NIS</th>
		<th>Nama Lengkap</th>
		<th>Prodi</th>
		<th>Tahun Ajaran</th>
		<th width="200"><a href="./admin.php?hlm=master&sub=siswa&aksi=baru" class="btn btn-default btn-xs">Tambah Data</a></th>
		</tr>
		</thead>
		<tbody>
			<?php if( mysql_num_rows($sql) > 0 ){
			$no = 1;
			while(list($nis,$nama,$idprodi,$idthajaran) = mysql_fetch_array($sql)){
				echo '<tr><td>'.$no.'</td>';
				echo '<td>'.$nis.'</td>';
				echo '<td>'.$nama.'</td>';
				$qprodi = mysql_query("SELECT prodi FROM prodi WHERE idprodi='$idprodi'");
				list($prodi) = mysql_fetch_array($qprodi);
				echo '<td>'.$prodi.'</td>';
				$qthajaran = mysql_query("SELECT tapel FROM tapel WHERE id='$idthajaran'");
				list($thajaran) = mysql_fetch_array($qthajaran);
				echo '<td>'.$thajaran.'</td>';
				echo '<td><a href="./admin.php?hlm=master&sub=siswa&aksi=edit&nis='.$nis.'" class="btn btn-success btn-xs">Edit</a> ';
				echo '<a href="./admin.php?hlm=master&sub=siswa&aksi=hapus&nis='.$nis.'" class="btn btn-danger btn-xs">Hapus</a></td>';
				echo '</tr>';
				$no++;
			}
		} else {
			echo '<tbody><tr><td colspan="5"><em>Belum ada data</em></td></tr></tbody>';
		} ?>
		</tbody>
		</table>
		</div>
		</div>

		<!-- if( mysql_num_rows($sql) > 0 ){
			$no = 1;
			while(list($nis,$nama,$idprodi) = mysql_fetch_array($sql)){
				echo '<tbody><tr><td>'.$no.'</td>';
				echo '<td>'.$nis.'</td>';
				echo '<td>'.$nama.'</td>';
				$qprodi = mysql_query("SELECT prodi FROM prodi WHERE idprodi='$idprodi'");
				list($prodi) = mysql_fetch_array($qprodi);
				echo '<td>'.$prodi.'</td>';
				echo '<td><a href="./admin.php?hlm=master&sub=siswa&aksi=edit&nis='.$nis.'" class="btn btn-success btn-xs">Edit</a> ';
				echo '<a href="./admin.php?hlm=master&sub=siswa&aksi=hapus&nis='.$nis.'" class="btn btn-danger btn-xs">Hapus</a></td>';
				echo '</tr></tbody>';
				$no++;
			}
		} else {
			echo '<tbody><tr><td colspan="5"><em>Belum ada data</em></td></tr></tbody>';
		}

		echo '</table></div></div>';
	} -->
<?php }
}
?>