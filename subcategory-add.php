<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_POST['subcategoryAdd'])) {
  $categoryId = $_POST['categoryId'];
  $name = $_POST['name'];
  $subcategorySlug = $_POST['slug'];
  $sqlSelect = "SELECT slug FROM product_subcategory WHERE slug = '$subcategorySlug'";
  $resultSelect = mysqli_query($conn, $sqlSelect);
  $description = $_POST['description'];
  $image = $_FILES['image']['name'];
  $tmpImageName = $_FILES['image']['tmp_name'];
  $path = "../assets/img/";
  $imageExt = pathinfo($image, PATHINFO_EXTENSION);
  $fileName = time().'.'.$imageExt;
  if($categoryId === "Izvēlies kategoriju"){
    $errorEmptyCategory = "Kategorija ir obligāti jāizvēlas";
  } 
  else if(empty($name)) {
    $errorName = "Vārds ir obligāts lauks";
  } 
  else if(empty($subcategorySlug)) {
    $errorSlug = "Taka ir obligāts lauks";
  } 
  else if(mysqli_num_rows($resultSelect) > 0) {
    $errorSlug = "Birka jau pastāv citai apakškategorijai, nomainiet to";
  } else {
    move_uploaded_file($tmpImageName, $path.$fileName);
    $sql = "INSERT INTO product_subcategory (categoryId, name, slug, description, image) VALUES
    ('$categoryId', '$name', '$subcategorySlug', '$description', '$fileName')";
    $result = mysqli_query($conn, $sql);
    if($result) {
      $_SESSION['successMessageSubcategory'] = 'Apakškategorija veiksmīgi pievienota';
      header("Location: subcategory-add");
      exit;
    } else {
      echo "error";
    }
  } 
}
?>
<div class="container">
  <div class="container-inner">
    <?php 
    if(isset($_SESSION['successMessageSubcategory'])) {
    ?>
    <span class="success-insertion-notification"><?php echo $_SESSION['successMessageSubcategory']; ?></span>
    <?php
      unset($_SESSION['successMessageSubcategory']);
    }
    ?>
    <form class="subcategory-add-form subcategory-form" action="" method="post" enctype="multipart/form-data">
      <h1>Apakškategorijas pievienošana</h1>
      <div class="form-item">
        <label>Kategorija</label>
        <select name="categoryId">
          <option selected>Izvēlies kategoriju</option>
          <?php
          $sql = "SELECT * FROM product_category";
          $result = mysqli_query($conn, $sql);
          while($row = mysqli_fetch_assoc($result)) {
          ?>
            <option value="<?php echo $row['id'];?>"
            <?php
              //Ja forma tiek nosutita ar tuksiem laukiem
              if(isset($_POST['categoryId']) && $_POST['categoryId'] == $row['id']) echo 'selected'; 
              ?>>
            <?php echo $row['name'];?></option>
          <?php 
          }
          ?>
        </select></br>
        <span class="error-message"><?php if(isset($errorEmptyCategory)) { echo $errorEmptyCategory; } ?></span>
      </div>
      <div class="form-item">
        <label>Apakškategorijas nosaukums</label>
        <input id="subcategoryName" class="subcategory-name-input" type="text" name="name" oninput="generateSubcategorySlug()" value="<?php if(isset($name)) { echo $name; }?>"></br>
        <span class="error-message"><?php if(isset($errorName)) { echo $errorName; }?></span>
      </div>
      <div class="form-item">
        <label>Apakškategorijas birka</label>
        <input id="subcategorySlug" class="subcategory-name-input" type="text" name="slug" value="<?php if(isset($subcategorySlug)) { echo $subcategorySlug; }?>"></br>
        <span class="error-message"><?php if(isset($errorSlug)) { echo $errorSlug; }?></span>
      </div>
      <div class="form-item form-item-description">
        <label>Apakškategorijas apraksts</label>
        <textarea name="description" rows="10" cols="75"></textarea>
      </div>
      <div class="form-item">
        <label>Apakškategorijas attēls</label>
        <input type="file" name="image">
      </div>
      <div class="form-item">
        <input class="subcategory-add-btn save-btn" type="submit" name="subcategoryAdd" value="Saglabāt">
      </div>          
     </form>
  </div>
</div>
<?php require_once('footer.php'); ?>