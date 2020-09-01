<?php 
	const TITLE = "fungsi untuk meng-generate serial number yang dibutuhkan <br> format : XXXX-XXXX-XXXX-XXXX";

	// Penghasil string alpanumerik acak
	function generate_string($strength = 3) {
		// $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';

	    $permitted_chars_length = strlen($permitted_chars);
	    $random_string = '';
	    for($i = 0; $i < $strength; $i++) {
	        $random_character = $permitted_chars[mt_rand(0, $permitted_chars_length - 1)];
	        $random_string .= $random_character;
	    }
	 
	    return $random_string;
	}
	
	// Penghasil serial number sebanyak baris yang ditentukan
	function generate_serial_number($line = 3){
		$i = 0;
		$separator = "-";
		$end = "x";
		$hasil = "";
		while ($i < $line) {
			$hasil .= generate_string(4).$separator.generate_string(4).$separator.generate_string(4).$separator.generate_string().$end;
			$hasil .= "\n";
			$i++;
		}
		return $hasil;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Serial Number Generator</title>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

		<script>
			// fungsi untuk memastikan input hanya angka integer
			function isNumberKey(evt){
			    var charCode = (evt.which) ? evt.which : evt.keyCode
			    if (charCode > 31 && (charCode < 48 || charCode > 57))
			        return false;
			    return true;
			}
		</script>
	</head>
	<body>
		<div class="container">
			<h3 class="mt-3 text-center"><?= ucwords(TITLE); ?></h3>
			<hr class="mb-3">
			<?php 
				if(isset($_POST["submit"])){
					if (empty($_POST["jlh_baris"])) {
			?>
				<div class="alert alert-danger alert-dismissible mt-2" role="alert">
				  <strong>Maaf.</strong> Mohon masukkan jumlah baris serial number lebih besar 0 terlebih dahulu !
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				  </button>
				</div>			
			<?php	
					}
				}
			?>
			<form action="" method="POST">
				<div class="form-group mt-2">
			    	<label>Masukkan Jumlah Baris Serial Number</label>
			    	<input type="text" name="jlh_baris" class="form-control" aria-describedby="inputHelp" autocomplete="off" onkeypress="return isNumberKey(event)" value="<?= isset($_POST["jlh_baris"]) ? $_POST["jlh_baris"] : NULL; ?>">
			    	<small id="inputHelp" class="form-text text-muted">Contoh : 3</small>
			  	</div>
				<input type="submit" class="btn btn-primary" name="submit" value="Proses">
			</form>
			<div class="form-group mt-5">
				<div class="input-group">
					<?php 
						if (isset($_POST["submit"])) {
							if (!empty($_POST["jlh_baris"])) {

								// implementasi
								$jlh_baris = $_POST["jlh_baris"];
								$hasil = generate_serial_number($jlh_baris);

								echo "	<div class='input-group-prepend'>
											<span class='input-group-text'>Hasil</span>
				  						</div>";
								echo "	<textarea class='form-control' aria-label='Hasil' rows='5'>{$hasil}	</textarea>";
							}
						}
					?>
				</div>
		 	</div>
		 </div>

	</body>
</html>