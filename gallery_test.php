<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="style.css">
		
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	</head>
	<body>
		<div class="container text-center">
			<h1>OnClick Video PopUp</h1>
			<div class="row">
				<div class="col-md-4">
					<img src="thumbnails/image1.png" class="img-fluid rounded">
					<img src="images/play-button.png" class="play-btn" data-toggle="modal" data-target="#play-video-1">
				</div>

				<div class="col-md-4">
					<img src="thumbnails/image1.png" class="img-fluid rounded">
					<img src="images/play-button.png" class="play-btn" data-toggle="modal" data-target="#play-video-2">
				</div>

				<div class="col-md-4 ">
					<img src="thumbnails/image1.png" class="img-fluid rounded">
					<img src="images/play-button.png" class="play-btn" data-toggle="modal" data-target="#play-video-3">
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="play-video-1">
			  <div class="modal-dialog modal-dialog-centered">
			    <div class="modal-content">
			      <div class="modal-body">
			        <video class="w-100" controls autoplay loop>
			        	<source src="videos/video1.mp4" type="video/mp4">
			        </video>
			      </div>
			    </div>
			  </div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="play-video-2">
			  <div class="modal-dialog modal-dialog-centered">
			    <div class="modal-content">
			      <div class="modal-body">
			        <video class="w-100" controls autoplay loop>
			        	<source src="videos/video1.mp4" type="video/mp4">
			        </video>
			      </div>
			    </div>
			  </div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="play-video-3">
			  <div class="modal-dialog modal-dialog-centered">
			    <div class="modal-content">
			      <div class="modal-body">
			        <video class="w-100" controls autoplay loop>
			        	<source src="videos/video1.mp4" type="video/mp4">
			        </video>
			      </div>
			    </div>
			  </div>
			</div>
		</div>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	</body>
</html>