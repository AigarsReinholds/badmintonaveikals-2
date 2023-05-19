<?php require_once('header.php'); ?>
<?php
$errorExistMessage = '';
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM product_subcategory WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  $count = mysqli_num_rows($result);
  if($count > 0) {
    $row = mysqli_fetch_assoc($result);
    $categoryId = $row['categoryId'];
    $subcategoryName = $row['name'];
    $subcategorySlug = $row['slug'];
    $description = $row['description'];
    $image = $row['image'];
  } else {
    header("Location: subcategory");
  }
}
if(isset($_POST['subcategoryEdit'])) {
  $categoryId = $_POST['categoryId'];
  $subcategoryName = $_POST['name'];
  $subcategorySlug = $_POST['slug'];
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
      echo "Attēls jau eksistē: ".$fileName;
    } else {
        $sql = "UPDATE product_subcategory SET categoryId = '$categoryId', name = '$subcategoryName', slug = '$subcategorySlug', description = '$description', image = '$updateFileName' WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        if($result) {
          move_uploaded_file($tmpImageName, "../assets/img/".$updateFileName);
          unlink($path.$oldImage);
          $_SESSION['successEditMessageSubcategory'] = 'Apakškategorijas informācija veiksmīgi rediģēta';
          header("Location: subcategory-edit?id=$id");
          exit;
        } else { 
          echo "Kļūda redigejot apakškategoriju";
        }
      }  
  } else {
    $sql = "UPDATE product_subcategory SET categoryId = '$categoryId', name = '$subcategoryName', slug = '$subcategorySlug', description = '$description', image = '$updateFileName' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if($result) {
      $_SESSION['successEditMessageSubcategory'] = 'Apakškategorijas informācija veiksmīgi rediģēta';
      header("Location: subcategory-edit?id=$id");
      exit;
    } else {
      echo "Kļūda redigejot apakškategoriju";
    }
  }
}
?>
<div class="container">
  <div class="container-inner">
    <?php 
    if(isset($_SESSION['successEditMessageSubcategory'])) {
    ?>
    <span class="success-insertion-notification"><?php echo $_SESSION['successEditMessageSubcategory']; ?></span>
    <?php
      unset($_SESSION['successEditMessageSubcategory']);
    }
    ?>
    <div class="container-inner-top">
      <div class="subcategory-edit">
        <a href="subcategory-add.php">Pievienot jaunu apakskategoriju</a>
      </div>
    </div>
    <h2>Rediģēt apakškategoriju:</h2>
    <form class="subcategory-edit-form subcategory-form" method="post" action="" enctype="multipart/form-data">
      <div class="form-item">
        <label>Kategorija</label>
        <select name="categoryId">
          <option value="" selected>Izvēlies kategoriju</option>
          <?php
          $sql = "SELECT * FROM product_category";
          $result = mysqli_query($conn, $sql);
          while($row = mysqli_fetch_assoc($result)) {
            if($row['id'] == $categoryId) {
          ?>
              <option value="<?php echo $row['id']?>" selected><?php echo $row['name'];?></option>
          <?php  } else { ?>
                <option value="<?php echo $row['id']?>"><?php echo $row['name'];?></option>
          <?php
            }
          }
          ?>
        </select>
      </div>
      <div class="form-item">
        <label>Apakškategorijas nosaukums</label>
        <input type="text" name="name" value="<?php echo $subcategoryName; ?>">
      </div>
      <div class="form-item">
        <label>Apakškategorijas birka</label>
        <input type="text" name="slug" value="<?php echo $subcategorySlug; ?>">
      </div>
      <div class="form-item form-item-description">
        <label>Apakškategorijas apraksts</label>
        <textarea name="description" rows="10" cols="75"><?php echo $description; ?></textarea>
      </div>
      <div class="form-item">
        <label>Apakškategorijas attēls</label>
        <input type="file" name="image">
        <input type="hidden" name="imageOld" value="<?php echo $image;?>">
      </div>
      <div class="form-item">
        <img src="../assets/img/<?php echo $image; ?>" width = "100px" height = "100px">
      </div>
      <div class="form-item">
        <input class="subcategory-edit-btn save-btn" type="submit" name="subcategoryEdit" value="Saglabāt">
      </div>
    </form>  
  </div>
</div>
<?php require_once('footer.php'); ?>