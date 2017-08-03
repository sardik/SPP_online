<?php
if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} 
?>

<html>
<head>
</head>
<body>

<div class="container">
  <h2>Informasi</h2><hr>

  <p>Berikut adalah informasi detail untuk informasi tentang sistem pemabayaran SPP <br>
  di MA blabla untuk penjelasan tentang cara pembayaran dan jumlah biaya yang harus dibayarkan klik detail informasi.</p>
  <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#informasi">Detail</button>
  <div id="informasi" class="collapse">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit,
    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
  </div>
</div>

<div class="container">
  <h2>Berita</h2><hr>

  <p>Berikut adalah informasi detail apabila ada berita atau informasi seputar berita <br>
  di MA blabla untuk penjelasan tentang cara pembayaran dan jumlah biaya yang harus dibayarkan klik detail informasi.</p>
  <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#berita">Detail</button>
  <div id="berita" class="collapse">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit,
    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
  </div>
</div>

</body>
</html>