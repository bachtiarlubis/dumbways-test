<?php 
	function mySortArray(array $theArr){
		$count = count($theArr);
		for ($i = 0; $i < $count; $i++) {
	      	for ($j = $i + 1; $j < $count; $j++) {
    	      	if ($theArr[$i] > $theArr[$j]) {
	              	$temp = $theArr[$i];
	              	$theArr[$i] = $theArr[$j];
	              	$theArr[$j] = $temp;
          		}
	      	}
	  	}
	  	return $theArr;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Array Elements Orderer</title>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

		<script>
			// fungsi untuk memastikan input hanya A-Z, a-z, ,(comma), dan spasi
			function isArrayNumForm(evt){
			    var charCode = (evt.which) ? evt.which : evt.keyCode
			    if (charCode > 32 && (charCode != 44 && charCode != 91 && charCode != 93 && charCode < 65 || charCode > 90 && charCode < 97 || charCode > 122))
			        return false;
			    return true;
			}
		</script>
	</head>
	<body>
		<div class="container mt-5">
			<?php 
				if(isset($_POST["submit"])){
					if (empty($_POST["data0"])) {
						unset($_POST);
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
		      <div class="form-group">
		        <label>Pilih jumlah Array yang Diinginkan (Max. 10)</label>
		        <select class="form-control" id="jlhInput">
		          <option value="0">0</option>
		          <option value="1">1</option>
		          <option value="2">2</option>
		          <option value="3">3</option>
		          <option value="4">4</option>
		          <option value="5">5</option>
		          <option value="6">6</option>
		          <option value="7">7</option>
		          <option value="8">8</option>
		          <option value="9">9</option>
		          <option value="10">10</option>
		        </select>
		      </div>

		      <!-- Disini input tag akan diisi dengan fungsi jQuery -->
		      <div class="form-group" id="inputArr">
		      </div>

			</form>
			<div class="form-group mt-5">
				<div class="input-group">
					
					<?php 
						if (isset($_POST["submit"])) {
							
							$jlh_sbm = count($_POST);
							$dataInput = [];
							$dataOutput = [];
							echo " <label>Hasil : </label>";
			  				echo "	<span class='border border-success w-100 p-3'>";
			  				echo "		<pre>";

							// -1 karena submit POST tidak dihitung
							for ($i=0; $i < $jlh_sbm-1; $i++) { 
								$toArr = explode(",", str_replace(" ", "", $_POST["data".$i]));
								$dataInput[] = array_filter($toArr);
							}

							// proses sorter dimulai
							foreach ($dataInput as $v) {
								$dataOutput[] = mySortArray($v);
							}

							// Cetak hasil sorting
							print_r($dataOutput);

							echo "		</pre>";
							echo "	</span>";
						}
					?>


				</div>
		 	</div>
		 </div>
		 
		 <script>		     
		 	  // Pembuatan input form berdasarkan nilai select
		      $("#jlhInput").change(function() {
		        var value = +$(this).val();
		        var nr = 0;
		        var elem = $('#inputArr').empty();
		        while (nr < value) {

		          	elem.append($('<label>').text('Masukkan Larik Array '+nr));

		          	elem.append($('<input>', {
		          		type : "text",
		            	name : "data"+nr, 
		            	class : "form-control",
		            	onkeypress : "return isArrayNumForm(event)",
		            	required: "yes"
		          	}));

		          	elem.append($('<small>', {
		          		id : "inputHelp",
		            	class : "form-text text-muted"
		          	}).text("Contoh : a, b, c atau A, B, C"));
		          
		          	nr++;

		        } // END of while loop
		        if (value == 0) {
		        	$('#inputArr').empty();
		        }else{
					elem.append($('<input>', {
			          	type : "submit",
			            name : "submit", 
			            class : "btn btn-primary",
			            value : "Proses"
			        }));		        	
		        }
		        
		      });
	    </script>

	</body>
</html>