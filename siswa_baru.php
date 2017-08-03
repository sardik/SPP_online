<?php
if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	if( isset( $_REQUEST['submit'] )){
		$nis = $_REQUEST['nis'];
		$nama = $_REQUEST['nama'];
		$idprodi = $_REQUEST['idprodi'];
		$idthajaran = $_REQUEST['idthajaran'];
		
		$sql = mysql_query("INSERT INTO siswa VALUES('$nis','$nama','$idprodi','$idthajaran')");
		$sql2 = mysql_query("INSERT INTO user VALUES('','$nis',md5('123'),'0','$nama')");
		
		if($sql > 0 && $sql2 > 0){
			echo '<div class="alert alert-info">Data Tersimpan, Data Informasi Login User, catat untuk diinformasikan kepada user:';
			echo '<br>User : <strong>'.$nis.'</strong>';
			echo '<br>Pass   : 123';
			echo '<br><a href="./admin.php?hlm=master&sub=siswa" class="btn btn-sm btn-success">Kembali</a> ';
			echo '</div>';
			// header('Location: ./admin.php?hlm=master&sub=siswa');
			// die();
		} else {
			echo 'ERROR! Periksa penulisan querynya.';
		}
	} else {
?>
<h2>Tambah Siswa</h2>
<hr>
<form method="post" id="formfield" enctype="multipart/form-data" action="admin.php?hlm=master&sub=siswa&aksi=baru" class="form-horizontal" role="form">
	<?php $date = date("Ym"); 
	
	$sql = mysql_query("SELECT MAX(nis) As nis from siswa where nis like '%$date%'");
	list($nis) = mysql_fetch_array($sql);
	// echo $date;
	// echo $nis;
	if($nis != ""){
		$nisval = $nis + 1;
		echo '<div class="form-group">';
		echo '<label for="nis" class="col-sm-2 control-label">NIS</label>';
		echo '<div class="col-sm-2">';
		echo '<input type="text" class="form-control" id="nis" name="nis" value='.$nisval.'>';
		echo '</div>';
		echo '</div>';
	}
	else {
		$running = 1001 ;
		echo '<div class="form-group">';
		echo '<label for="nis" class="col-sm-2 control-label">NIS</label>';
		echo '<div class="col-sm-2">';
		echo '<input type="text" class="form-control" id="nis" name="nis" value='.$date.$running.'>';
		echo '</div>';
		echo '</div>';
	}
	?>
	
	<div class="form-group">
		<label for="nama" class="col-sm-2 control-label">Nama siswa</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" required>
		</div>
	</div>
	<div class="form-group">
		<label for="prodi" class="col-sm-2 control-label">Program Studi</label>
		<div class="col-sm-4">
			<select name="idprodi" class="form-control">
			<?php
			$qprodi = mysql_query("SELECT * FROM prodi ORDER BY idprodi DESC");
			while(list($idprodi,$prodi)=mysql_fetch_array($qprodi)){
				echo '<option value="'.$idprodi.'">'.$prodi.'</option>';
			}
			?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="idthajaran" class="col-sm-2 control-label">Tahun Pelajaran</label>
		<div class="col-sm-4">
			<select name="idthajaran" class="form-control">
			<?php
			$qtapel = mysql_query("SELECT * FROM tapel ORDER BY tapel DESC");
			while(list($idthajaran,$thajaran)=mysql_fetch_array($qtapel)){
				echo '<option value="'.$idthajaran.'">'.$thajaran.'</option>';
			}
			?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default" name="submit">Simpan</button>
			<a href="./admin.php?hlm=master&sub=siswa" class="btn btn-link">Batal</a>
		</div>
	</div>
	
</form>
			
<?php
	}
}
?>