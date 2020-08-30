<?php 
	// fungsi handshake
	function count_handshake($jlh_orang = 0){
		if ($jlh_orang < 1) {
			return false;
		}

		$i = 0;
		$t = 0;
		for ($i=0; $i < $jlh_orang; $i++) { 
			$t += $i;
		}
		return $t;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Handshake Counter</title>

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
			<?php 
				if(isset($_POST["submit"])){
					if (empty($_POST["jlh_orang"])) {
			?>
				<div class="alert alert-danger alert-dismissible mt-2" role="alert">
				  <strong>Maaf.</strong> Mohon masukkan angka dan lebih besar dari 0 terlebih dahulu !
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
			    	<label>Masukkan Angka</label>
			    	<input type="text" name="jlh_orang" class="form-control" aria-describedby="inputHelp" autocomplete="off" onkeypress="return isNumberKey(event)">
			    	<small id="inputHelp" class="form-text text-muted">Contoh : 10</small>
			  	</div>
				<input type="submit" class="btn btn-primary" name="submit" value="Proses">
			</form>
			<div class="form-group mt-5">
				<?php 
					if (isset($_POST["submit"])) {
						if (!empty($_POST["jlh_orang"])) {

							$jlh_orang = $_POST["jlh_orang"];
							$hasil = count_handshake($jlh_orang);

							echo "<label>Hasil</label>";
							echo "<input type='text' class='form-control' value='{$hasil}' readonly>";
						}
					}
				 ?>
		 	</div>
		 </div>

	</body>
</html>
	
