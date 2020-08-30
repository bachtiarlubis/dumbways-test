<?php 
  session_start();

  ini_set('display_errors',1);
  error_reporting(E_ALL);

  $conn = new mysqli("localhost", "root", "", "db_streaming_video"); 

  function imageResize($imageResourceId,$width,$height) {

    $targetWidth =150;
    $targetHeight =100;
    $targetLayer=imagecreatetruecolor($targetWidth,$targetHeight);
    imagecopyresampled($targetLayer,$imageResourceId,0,0,0,0,$targetWidth,$targetHeight, $width,$height);
    return $targetLayer;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Video Upload</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- Style Khususu $_GET[mod] = videos -->
    <style>
      body{
        margin: 0;
        padding: 0;
        /*color: #FFFFFF;*/
        background-color: #263029 !important;
      }

      .container{
        margin-top: 12%;
      }

      h1{
        margin-bottom: 50px !important;
      }

      .img-fluid{
        cursor: pointer;
      }
      .col-md-4:hover .play-btn{
        opacity: 1;
      }

      .modal-content{
        background: transparent !important;
        border: none !important;
      }

      .the-title{
        color: #FFFFFF;
      }
      
      label{
        color: #FFFFFF;
      }
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  </head>
  <body>

    <div class="container mt-5 mb-5">
    
      <div class="btn-group float-left">
        <button class="btn btn-success" onclick="document.location.href='?mod=videos'"> VIDEOS </button>
      </div>

      <div class="btn-group float-right">
        <button class="btn btn-primary mr-2" onclick="document.location.href='?mod=upload'"> UPLOAD </button>
        <button class="btn btn-warning" onclick="document.location.href='?mod=category'"> NEW CATEGORY </button>
      </div>

      <br><br>

<?php 
        if(isset($_GET["error"]) && isset($_SESSION["msg"])){
          
          if ($_GET["error"] === "yes") {
?>
        <div class="alert alert-danger alert-dismissible mt-2" role="alert">
          <strong>Sorry.</strong> <?= $_SESSION["msg"]; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
<?php 
            unset($_SESSION["msg"]);
          }elseif($_GET["error"] === "no"){
?>
        <div class="alert alert-success alert-dismissible mt-2" role="alert">
          <strong>Success.</strong> <?= $_SESSION["msg"]; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
<?php
            unset($_SESSION["msg"]);
          }
          
        }

        $mod = isset($_GET["mod"]) ? $_GET["mod"] : "";
        switch ($mod) {
          // upload video
          case 'upload':
?>

<?php if (isset($_GET["id_update"])) { ?>
              <h1 class="the-title mt-3">Update a Video</h1>
<?php }else{  ?>
              <h1 class="the-title mt-3">Upload a Video</h1>
<?php } ?>

          <form action="" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id_update" class="form-control" value="<?php echo isset($_GET['id_update']) ? $row['id'] : ''; ?>">
              <div class="form-group mt-2">
                <label>Title Video</label>
                <input type="text" required="yes" name="title" id="title" class="form-control" placeholder="Masukkan Title Video">
              </div>

              <div class="form-group">
                <label>Category Video</label>
                <select name="category_id" id="category_id" class="form-control">
<?php 
                      $sql = "SELECT * FROM category_tb";
                      $result = $conn->query($sql);
                      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
?>
                        <option value="<?= $row['id']; ?>"><?= $row["name"]; ?></option>
<?php
                      }
?>
                </select>
              </div>

              <div class="form-group mt-2">
                <label>Upload Thumbnail</label>
                <input type="file" required="yes" name="thumbnail" id="thumbnail" class="form-control">
              </div>
              
              <div class="form-group mt-2">
                <label>Upload Video</label>
                <input type="file" required="yes" name="file" id="file" class="form-control">
              </div>

              <input type="submit" class="btn btn-primary" name="submit" value="UPLOAD">
          </form>

<?php 
            // UPDATE ONLY
            if (isset($_GET["id_update"])) { 
              $id_update = $_GET["id_update"];
              $sql = "SELECT * FROM video_tb WHERE id = '$id_update'";
              $result = $conn->query($sql);
              $row = $result->fetch_array(MYSQLI_ASSOC);
                echo "  <script>
                          $('#title').val('".$row["title"]."');
                          $('#category_id').val('".$row["category_id"]."');
                          $('input').removeAttr('required');
                        </script>";
            } 

            // INSERT / UPDATE
            if (isset($_POST["submit"])) {

              $error="";
              $title = $_POST["title"];
              $categoryId = $_POST["category_id"];
              
              $thumbnailName = $_FILES["thumbnail"]["name"];
              $thumbnailName = explode(".", $thumbnailName);
              $newThumbnailName = "image_".time();
              $thumbnailTmp = $_FILES["thumbnail"]["tmp_name"];
              // $thumbnailType = $_FILES["thumbnail"]["type"];
              // $thumbnailSize = $_FILES["thumbnail"]["size"];
              $imgFolderPath = __DIR__;
              $ext = end($thumbnailName);

              $videoName = $_FILES["file"]["name"];
              $videoName = explode(".", $videoName);
              $videoTmp = $_FILES["file"]["tmp_name"];
              $newVideoName = "video_".time().".".end($videoName);
              $videoFolderPath = __DIR__;
              /*$videoType = $_FILES["file"]["type"];
              $videoSize = $_FILES["file"]["size"];*/
              
              

              if (isset($id_update)) {
                $sql_get1 = "SELECT thumbnail FROM video_tb WHERE id='".$id_update."'";
                $result = $conn->query($sql_get1);
                $row_unlink_thumbnail = $result->fetch_array(MYSQLI_ASSOC);

                $sql_get2 = "SELECT attached FROM video_tb WHERE id='".$id_update."'";
                $result = $conn->query($sql_get2);
                $row_unlink_video = $result->fetch_array(MYSQLI_ASSOC);

                if (empty($videoTmp) && empty($thumbnailTmp)) {
                  $sql = "UPDATE video_tb 
                      SET title='$title', category_id='$categoryId'
                      WHERE id='".$id_update."'";                  
                }else{
                  if (!empty($thumbnailTmp) && empty($thumbnailTmp)) {
                    
                    $sql = "UPDATE video_tb 
                      SET title='$title', category_id='$categoryId', thumbnail='".$newThumbnailName.".".$ext."'
                      WHERE id='".$id_update."'";

                  }elseif (!empty($videoTmp) && empty($thumbnailTmp)) {

                    $sql = "UPDATE video_tb 
                      SET title='$title', category_id='$categoryId', attached='$newVideoName'
                      WHERE id='".$id_update."'";

                  }elseif(!empty($thumbnailTmp) && !empty($videoTmp)){
                    $sql = "UPDATE video_tb 
                      SET title='$title', category_id='$categoryId', attached='$newVideoName', thumbnail='".$newThumbnailName.".".$ext."'
                      WHERE id='".$id_update."'";
                  }
                }
              }else{
                  $sql = "INSERT INTO video_tb 
                      VALUES
                      (null, '$title', '$categoryId', '$newVideoName', '".$newThumbnailName.".".$ext."')";
              } // END of update

              if(!isset($id_update)){
                // UPLOAD / BUKAN UPDATE
                if (!$conn->query($sql)) {
                  
                  $_SESSION["msg"] = "There is an error when insert " . $conn->error;
                  $error = "yes";

                }else{
                    $sourceProperties = getimagesize($thumbnailTmp);
                    $imageType = $sourceProperties[2];
                    switch ($imageType) {
                      case IMAGETYPE_PNG:
                          $imageResourceId = imagecreatefrompng($thumbnailTmp); 
                          $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                          imagepng($targetLayer,$imgFolderPath. $newThumbnailName. ".". $ext);
                          break;

                      case IMAGETYPE_GIF:

                          $imageResourceId = imagecreatefromgif($thumbnailTmp); 
                          $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                          imagegif($targetLayer,$imgFolderPath. $newThumbnailName. ".". $ext);
                          break;

                      case IMAGETYPE_JPEG:
                          $imageResourceId = imagecreatefromjpeg($thumbnailTmp); 
                          $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                          imagejpeg($targetLayer,$imgFolderPath. $newThumbnailName. ".". $ext);
                          break;

                      default:
                          $_SESSION["msg"] = "Invalid Image type.";
                          $error = "yes";
                          break;
                    }

                    if (!$error) {
                      if (chmod($videoFolderPath, 777)) {
                          move_uploaded_file($videoTmp, $videoFolderPath. $newVideoName);  
                          $_SESSION["msg"] = "Uploading is successfully !";
                          $error = "no";
                      }else{
                          $_SESSION["msg"] = "CHMOD failed on video !";
                          $error = "yes";
                      }

                      if (chmod($imgFolderPath, 777)) {
                          move_uploaded_file($thumbnailTmp, $imgFolderPath. $newThumbnailName. ".". $ext);
                          $_SESSION["msg"] = "Uploading is successfully !";
                          $error = "no";
                      }else{
                          $_SESSION["msg"] = "CHMOD failed on thumbnail !";
                          $error = "yes";
                      }
                      
                    }
                }

                header("location:4b.php?mod=upload&error=$error");
                exit();
                
              }elseif(isset($id_update)){
                // UPDATE
                // cek apakah terdapat upload baru
                // if (!empty($videoTmp) || !empty($thumbnailTmp)) {
                  if (!$conn->query($sql)) {
                    $_SESSION["msg"] = "There is an error when update " . $conn->error;
                    $error = "yes";
                  }else{

                    // cek apakah ada upload file
                    if ((!empty($thumbnailTmp) && !empty($videoTmp)) || !empty($thumbnailTmp)) {
                        $sourceProperties = getimagesize($thumbnailTmp);
                        $imageType = $sourceProperties[2];
                        switch ($imageType) {
                          case IMAGETYPE_PNG:
                              $imageResourceId = imagecreatefrompng($thumbnailTmp); 
                              $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                              imagepng($targetLayer,$imgFolderPath. $newThumbnailName. ".". $ext);
                              break;

                          case IMAGETYPE_GIF:

                              $imageResourceId = imagecreatefromgif($thumbnailTmp); 
                              $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                              imagegif($targetLayer,$imgFolderPath. $newThumbnailName. ".". $ext);
                              break;

                          case IMAGETYPE_JPEG:
                              $imageResourceId = imagecreatefromjpeg($thumbnailTmp); 
                              $targetLayer = imageResize($imageResourceId,$sourceProperties[0],$sourceProperties[1]);
                              imagejpeg($targetLayer,$imgFolderPath. $newThumbnailName. ".". $ext);
                              break;

                          default:
                              echo "Invalid Image type.";
                              $error = "yes";
                              break;
                        }

                        // cek apakah ada error
                        if (!$error) {
                          if (!empty($videoTmp)) {
                            if (chmod($videoFolderPath, 777)) {
                                move_uploaded_file($videoTmp, $videoFolderPath. $newVideoName);
                                unlink($videoFolderPath. $row_unlink_video["attached"]);

                                $_SESSION["msg"] = "Uploading is successfully !";
                                $error = "no";
                            }else{
                                $_SESSION["msg"] = "CHMOD failed on video !";
                                $error = "yes";
                            }
                          }

                          if (chmod($imgFolderPath, 777)) {
                              move_uploaded_file($thumbnailTmp, $imgFolderPath. $newThumbnailName. ".". $ext);
                              unlink($imgFolderPath. $row_unlink_thumbnail["thumbnail"]);

                              $_SESSION["msg"] = "Uploading is successfully !";
                              $error = "no";
                          }else{
                              $_SESSION["msg"] = "CHMOD failed on thumbnail !";
                              $error = "yes";
                          }
                        } // END of cek error
                    }elseif (!empty($videoTmp) && empty($thumbnailTmp)) {
                      if (!$error) {
                        if (chmod($videoFolderPath, 777)) {
                            move_uploaded_file($videoTmp, $videoFolderPath. $newVideoName); 
                            unlink($videoFolderPath. $row_unlink_video["attached"]);

                            $_SESSION["msg"] = "Uploading is successfully !";
                            $error = "no";
                        }else{
                            $_SESSION["msg"] = "CHMOD failed on video !";
                            $error = "yes";
                        }
                      }
                    } // END of cek ada upload file

                  } // END of update
                // } // END of cek upload baru

                header("location:4b.php?mod=video&error=$error");
                exit();
              } // END of isset update
            } // END of isset submit

            break;

            // tambah category
            case 'category':
 
                  if (!isset($_GET["id_update"])) {
?>
                    <h1 class="the-title mt-3">Add a Category</h1>
<?php
                  }else{
                      $sql = "SELECT name FROM category_tb WHERE id = '".$_GET["id_update"]."'";
                      $result = $conn->query($sql);
                      $row = $result->fetch_array(MYSQLI_ASSOC);
?>
                    <h1 class="the-title mt-3">Update Category <b><?= $row["name"]; ?></b> </h1>
<?php
                  }
?>

              <form action="" method="POST">
                <div class="form-group mt-2">
                  <label>Title Video</label>
                  <input type="text" required="yes" name="category" class="form-control" placeholder="Masukkan Category Baru">
                </div>
                <input type="submit" class="btn btn-primary" name="submit" value="SUBMIT">
              </form>

              <!-- START TABLE -->
              <table class="table table-striped table-bordered mt-2">
                <thead>
                    <tr class="bg-warning text-black">
                        <th>No.</th>
                        <th>Category Name</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
<?php
              if (isset($_POST["submit"])) {

                  $category = $_POST["category"];

                  if (!isset($_GET["id_update"])) {
                      $sql = "INSERT INTO category_tb VALUES (null, '$category')";
                      $sql_cek = "SELECT * FROM category_tb WHERE name LIKE '$category'";
                  }else{
                      $sql = "UPDATE category_tb SET name = '$category' WHERE id = '".$_GET["id_update"]."'";
                      $sql_cek = "SELECT * FROM category_tb WHERE name LIKE '$category' AND id != '".$_GET["id_update"]."'";
                  }
                  
                  $result = $conn->query($sql_cek);
                  
                  if (!$result->num_rows) {
                    
                    if(!$conn->query($sql)){
                      $_SESSION["msg"] = "There is an error " . $conn->error;
                      $error = "yes";
                    }else{
                      $_SESSION["msg"] = "New category is saved successfully!";
                      $error = "no";
                    }

                  }else{
                    $_SESSION["msg"] = "Category {$category} already exist !";
                    $error = "yes";
                  }

                  header("location:4b.php?mod=category&error=$error");
                  exit();
              }

                $sql = "SELECT * FROM category_tb";
                $result = $conn->query($sql);
                if($result->num_rows > 0){
                    $s  =   '';
                    while($row = $result->fetch_array(MYSQLI_ASSOC)){
                        $s++;
?>
                        <tr class="text-white">
                            <td><?php echo $s;?></td>
                            <td><?php echo $row['name'];?></td>
                            <td align="center" class="p-1 w-50">
                                <button class="btn btn-warning" onclick="document.location.href='?mod=category&id_update=<?= $row['id']; ?>'"> UPDATE </button>
                                <button class="btn btn-danger" onclick="confDelete('Do you want to delete this category ?' ,'4b.php?mod=delete_cat&id_delete=<?= $row['id']; ?>&redir=category')"> DELETE </button>
                            </td>
                 
                        </tr>
<?php
                    }
                }else{
?>
                    <tr><td colspan="6" align="center">No Record(s) Found!</td></tr>
<?php 
                } 
?>
            </tbody>
        </table> 
        <!-- END of table -->

<?php
              break;

            // hapus video
            case 'delete_vid':
              $redir = isset($_GET["redir"]) ? $_GET["redir"] : '';
              if (isset($_GET["id_delete"])){
                  if (!empty($_GET["id_delete"])) {
                    $sql = "DELETE FROM video_tb WHERE id = '".$_GET["id_delete"]."'";
                    if (!$conn->query($sql)) {
                       $_SESSION["msg"] = "There is an error " . $conn->error;
                       $error = "yes";
                    }else{
                       $sql = "SELECT attached FROM video_tb WHERE id = '".$_GET["id_delete"]."'";
                       if (!$result = $conn->query($sql)) {
                           $_SESSION["msg"] = "There is an error " . $conn->error;
                           $error = "yes";
                           header("location:4b.php?mod=$redir&error=$error");
                           exit();
                       }
                       $row = $result->fetch_array(MYSQLI_ASSOC);
                       $imgFolderPath = __DIR__;
                       $videoFolderPath = __DIR__;
                       $imgToDelete = $imgFolderPath.$row["attached"];
                       $vidToDelete = $videoFolderPath.$row["attached"];
                      if(chmod($videoFolderPath, 777) && chmod($imgFolderPath, 777)){
                         if (unlink($vidToDelete)) {
                           if (unlink($imgToDelete)) {
                              $_SESSION["msg"] = "Data with id ".$_GET["id_delete"]." was deleted successfully!";
                              $error = "no";
                           }else{
                             $_SESSION["msg"] = "Delete almost success. Thumbnail was failed to deleted !";
                             $error = "yes";
                             header("location:4b.php?mod=$redir&error=$error");
                             exit();
                           }
                         }else{
                           $_SESSION["msg"] = "Delete almost success. Video was failed to deleted !";
                           $error = "yes";
                           header("location:4b.php?mod=$redir&error=$error");
                           exit();
                         }
                        }else{
                            $_SESSION["msg"] = "Delete almost success. But CHMOD was failed !";
                            $error = "yes";
                            header("location:4b.php?mod=$redir&error=$error");
                            exit();
                      } // END of chmod
                    }
                  }else{
                       $_SESSION["msg"] = "Delete failed. There is no any selected id !";
                       $error = "yes";
                  }
              }else{
                  $_SESSION["msg"] = "Delete failed. There is something wrong in server !";
                  $error = "yes";
              }

              header("location:4b.php?mod=$redir&error=$error");
              exit();

              break;

            // delete category
            case 'delete_cat':
              $redir = isset($_GET["redir"]) ? $_GET["redir"] : '';
              if (isset($_GET["id_delete"])){
                  if (!empty($_GET["id_delete"])) {
                    $sql = "DELETE FROM category_tb WHERE id = '".$_GET["id_delete"]."'";
                    if (!$conn->query($sql)) {
                       $_SESSION["msg"] = "There is an error " . $conn->error;
                       $error = "yes";
                       header("location:4b.php?mod=$redir&error=$error");
                       exit();
                    }else{
                      $_SESSION["msg"] = "Category has been deleted !";
                      $error = "no";
                      header("location:4b.php?mod=$redir&error=$error");
                      exit();
                    }
                  }
               }

              break;

            // display video
            default:
?>
            <h1 class="the-title mt-3">Videos</h1>

            <div class="row">
<?php
            $sql = "SELECT a.*, b.name  FROM video_tb a
                    LEFT JOIN category_tb b ON a.category_id = b.id";
            $result = $conn->query($sql);

            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
              
              $id = $row["id"];
              $title = $row["title"];
              $category = $row["name"];
              $attached = $row["attached"];
              $thumbnail = $row["thumbnail"];

              if (!empty($attached)) {
?>
                <div class="col-md-4">
                  
                  <div class="btn-group m-2">
                    <button class="btn btn-warning mr-1" onclick="document.location.href='?mod=upload&id_update=<?= $id; ?>'"> UPDATE </button>
                    <button class="btn btn-danger" onclick="confDelete('Do you want to delete this content ?' ,'4b.php?mod=delete_vid&id_delete=<?= $id; ?>&redir=video')"> DELETE </button>
                  </div>

                  <div class="card" style="width: 18rem;">
                    <img src="thumbnails/<?= $thumbnail; ?>" class="img-fluid rounded" data-toggle="modal" data-target="#play-video-<?= $id;?>">
                    <div class="card-body m-1 p-1">
                      <h5 class="card-title">Title : <?= $title; ?></h5>
                      <h6 class="card-subtitle mb-2 text-muted">Category : <?= $title; ?></h6>
                    </div>
                  </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="play-video-<?= $id; ?>">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-body">
                        <video class="w-100" controls loop>
                          <source src="videos/<?= $attached ?>" type="video/mp4">
                        </video>
                      </div>
                    </div>
                  </div>
                </div>
<?php          
              } // END of if attached
            } // END of while loop
?>
              </div> <!-- END of row -->
<?php
            break;
        } // END of switch case  
?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
      function confDelete(message, url){
          $.confirm({
            title: 'Warning !',
            content: message,
            buttons: {
              confirm: function () {
                  window.location.replace(url);
              },
              cancel: function () {

              }
            }
          });
      }
    </script>
  </body>
</html>