<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_POST['categoryAdd'])) {
  $name = $_POST['name'];
  $categorySlug = $_POST['slug'];
  $sqlSelect = "SELECT slug FROM product_category WHERE slug = '$categorySlug'";
  $resultSelect = mysqli_query($conn, $sqlSelect);
  $description = $_POST['description'];
  $image = $_FILES['image']['name'];
  $tmpImageName = $_FILES['image']['tmp_name'];
  $path = "../assets/img/";
  $imageExt = pathinfo($image, PATHINFO_EXTENSION);
  $fileName = time().'.'.$imageExt;
  if(empty($name)) {
    $errorName = "Nosaukums ir obligāts lauks";
  }
  else if(empty($categorySlug)) {
    $errorSlug = "Birka ir obligāts lauks";
  }
  else if(mysqli_num_rows($resultSelect) > 0) {
    $errorSlug = "Birka jau pastāv citai kategorijai, nomainiet to";
  } else {
    move_uploaded_file($tmpImageName, $path.$fileName);
    $sql = "INSERT INTO product_category (name, slug, description, image) VALUES
    ('$name', '$categorySlug', '$description', '$fileName')";
    $result = mysqli_query($conn, $sql);
    if($result) {
      $_SESSION['successMessageCategory'] = 'Kategorija veiksmīgi pievienota';
      header("Location: category-add");
      exit;
    } else {
      echo "Kļūda pievienojot kategoriju";
    }
  } 
}
?>
<div class="container">
  <div class="container-inner">
    <?php 
    if(isset($_SESSION['successMessageCategory'])) {
    ?>
    <span class="success-insertion-notification"><?php echo $_SESSION['successMessageCategory']; ?></span>
    <?php
      unset($_SESSION['successMessageCategory']);
    }
    ?>
    <form class="category-add-form category-form" action="" method="post" enctype="multipart/form-data">
      <h1>Kategorijas pievienošana</h1>
      <div class="form-item">
        <label>Kategorijas nosaukums</label>
        <input id="categoryName" class="category-name-input" type="text" name="name" oninput="generateCategorySlug()" value="<?php if(isset($name)) { echo $name; }?>"></br>
        <span class="error-message"><?php if(isset($errorName)) { echo $errorName; }?></span>
      </div>
      <div class="form-item">
        <label>Kategorijas birka</label>
        <input id="categorySlug" class="category-name-input" type="text" name="slug" value="<?php if(isset($categorySlug)) { echo $categorySlug; }?>"></br>
        <span class="error-message"><?php if(isset($errorSlug)) { echo $errorSlug; }?></span>
      </div>
      <div class="form-item form-item-description">
        <label>Kategorijas apraksts</label>
        <textarea name="description" rows="10" cols="75"></textarea>
      </div>
      <div class="form-item">
        <label>Kategorijas attēls</label>
        <input type="file" name="image">
      </div>
      <div class="form-item">
        <input class="category-add-btn save-btn" type="submit" name="categoryAdd" value="Saglabāt">
      </div>          
     </form>
  </div>
</div>
<?php require_once('footer.php'); ?>