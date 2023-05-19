<?php require_once('header.php'); ?>
<?php
$category = '';
$errorExistMessage = '';
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM product_category WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  $count = mysqli_num_rows($result);
  if($count > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $categorySlug = $row['slug'];
    $description = $row['description'];
    $image = $row['image'];
  } else {
    header("Location: category");
  }
}
if(isset($_POST['categoryEdit'])) {
  if(isset($_POST['update']) && $_POST['update'] == 'true' ) {
    if($_POST['confirmUpdate'] == 'true') {
      $id = $_POST['id'];
      $name = $_POST['name'];
      $categorySlug = $_POST['slug'];
      $description = $_POST['description'];
      $newImage = $_FILES['image']['name'];
      $oldImage = $_POST['imageOld'];
      $tmpImageName = $_FILES['image']['tmp_name'];
      $path = "../assets/img/";
      if($newImage != "") {
        $imageExt = pathinfo($newImage, PATHINFO_EXTENSION);
        $updateFileName = time().'.'.$imageExt;
      } else {
        $updateFileName = $oldImage;
      }
      if($_FILES['image']['error'] == 0) {
        if(file_exists($path.$newImage)) {
          $fileName = $_FILES['image']['name'];
          echo "Attēls jau eksistē ".$fileName;
        } else {
          $sql = "UPDATE product_category SET name = '$name', slug = '$categorySlug', description = '$description', image = '$updateFileName' WHERE id = '$id'";
          $result = mysqli_query($conn, $sql);
          if($result) {
            move_uploaded_file($tmpImageName, $path.$updateFileName);
            unlink($path.$oldImage);
            $_SESSION['successEditMessageCategory'] = 'Kategorijas informācija veiksmīgi rediģēta';
            header("Location: category-edit?id=$id");
            exit;
          }
          else { 
          echo "Radusies kļūda rediģējot kategoriju";
          }
        }  
      } else {
        $sql = "UPDATE product_category SET name = '$name', description = '$description', image = '$updateFileName' WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        if($result) {
          $_SESSION['successEditMessageCategory'] = 'Kategorijas informācija veiksmīgi rediģēta';
          header("Location: category-edit?id=$id");
          exit;
        } else {
            echo "Radusies kļūda rediģējot kategoriju";
        } 
      }
    }
  }      
}
?>
<div class="container">
  <div class="container-inner">
    <?php 
    if(isset($_SESSION['successEditMessageCategory'])) {
    ?>
    <span class="success-insertion-notification"><?php echo $_SESSION['successEditMessageCategory']; ?></span>
    <?php
      unset($_SESSION['successEditMessageCategory']);
    }
    ?>
    <div class="container-inner-top">
      <div class="category-edit">
        <a href="category-add">Pievienot jaunu kategoriju</a>
      </div>
    </div>
    <h2>Rediģēt kategoriju:</h2>
    <form id="updateForm-<?php echo $row['id'];?>" class="category-edit-form category-form" method="post" action="" enctype="multipart/form-data">
      <div class="form-item">
        <label>Kategorijas nosaukums</label>
        <input type="text" class="category-name-input" name="name" value="<?php echo $name; ?>">
      </div>
      <div class="form-item">
        <label>Kategorijas birka</label>
        <input type="text" class="category-name-input" name="slug" value="<?php echo $categorySlug; ?>">
      </div>
      <div class="form-item form-item-description">
        <label>Kategorijas apraksts</label>
        <textarea name="description" rows="10" cols="75"><?php echo $description; ?></textarea>
      </div>
      <div class="form-item">
        <label>Kategorijas attēls</label>
        <input type="file" name="image">
        <input type="hidden" name="imageOld" value="<?php echo $image;?>">
      </div>
      <div class="form-item">
        <img src="../assets/img/<?php echo $image; ?>" width = "100px" height = "100px">
      </div>
      <div class="form-item">
        <input class="category-edit-btn save-btn" type="submit" name="categoryEdit" value="Saglabāt" onclick="confirmUpdate(event, '<?php echo $row['id'];?>')">
        <input type="hidden" name="update" value="true">
        <input type="hidden" name="id" value="<?php echo $row['id'];?>">
        <input type="hidden" name="confirmUpdate" id="confirmUpdateInput-<?php echo $row['id']; ?>" value="">
      </div>
    </form>  
  </div>
</div>
<?php 
?>
<?php require_once('footer.php'); ?>