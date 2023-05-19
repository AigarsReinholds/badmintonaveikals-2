<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_POST['productAdd'])) {
  $categoryId = $_POST['categoryId'];
  $subcategoryId = $_POST['subcategoryId'];
  $name = $_POST['name'];
  $slug = $_POST['slug'];
  $sqlSelect = "SELECT slug FROM product WHERE slug = '$slug'";
  $resultSelect = mysqli_query($conn, $sqlSelect);
  $description = $_POST['description'];
  $price = $_POST['price'];
  $salePrice = $_POST['discount'];
  $qty = $_POST['qty'];
  $image = $_FILES['featuredImage']['name'];
  $tmpImageName = $_FILES['featuredImage']['tmp_name'];
  $path = "../assets/img/";
  $imageExt = pathinfo($image, PATHINFO_EXTENSION);
  $fileName = time().'.'.$imageExt;
  if($categoryId === "Izvēlies kategoriju"){
    $errorEmptyCategory = "Kategorija ir obligāti jāizvēlas";
  }
  else if(empty($name)) {
    $errorName = "Vārds ir obligāts lauks";
  } 
  else if(empty($slug)) {
    $errorSlug = "Birka ir obligāts lauks";
  }
  else if(mysqli_num_rows($resultSelect) > 0) {
    $errorSlug = "Birka jau pastāv citam produktam, nomainiet to";
  }
  else if(empty($price)) {
    $errorPrice = "Cena ir obligāts lauks";
  }
  else if(empty($qty)) {
    $errorQty = "Daudzums ir obligāts lauks";
  } else {
    move_uploaded_file($tmpImageName, $path.$fileName);
    $sql = "INSERT INTO product (name, slug, description, price, discount, qty, featuredImage, categoryId, subcategoryId) VALUES
    ('$name', '$slug', '$description', '$price', '$salePrice', '$qty', '$fileName', '$categoryId', '$subcategoryId')";
    $result = mysqli_query($conn, $sql);
    if($result) {
      $lastProductId = mysqli_insert_id($conn);
      echo "Pēdējais pievienotā produkta ID: " . $lastProductId;
      if(isset($_POST['size']) && !empty($_POST['size'])) {
        $sizes = explode(',', $_POST['size']);
        foreach($sizes as $size) {
          $size = trim($size);
          if(empty($size)) {
            continue;
          }
          $sizeSql = "INSERT INTO product_size (productId, sizeValue) VALUES ('$lastProductId', '$size')";
          $sizeResult = mysqli_query($conn, $sizeSql);
        }
      }
      if(isset($_FILES['images']['name'][0])) {
        $galleryFiles = $_FILES['images'];
        $galleryPath = "../assets/img/product/";
        $galleryFileError = array();
        for($i = 0; $i < count($galleryFiles['name']); $i++) {
          $galleryImageExt = pathinfo($galleryFiles['name'][$i], PATHINFO_EXTENSION);
          $galleryImageName = time().'_'.$i.'.'.$galleryImageExt;
          $galleryTmpImageName = $galleryFiles['tmp_name'][$i];
          if(!move_uploaded_file($galleryTmpImageName, $galleryPath.$galleryImageName)) {
            $galleryFileError[] = $galleryFiles['name'][$i];
          } else {
            $galleryImageSql = "INSERT INTO product_image(image, productId) VALUES ('$galleryImageName', '$lastProductId')";
            $galleryResult = mysqli_query($conn, $galleryImageSql);
          }
        }
        if(!empty($galleryFileError)) {
          echo "Notika kļūda, pievienojot šos failus: ".implode(',', $galleryFileError);
        }
      }
      $_SESSION['successMessageProduct'] = 'Produkts veiksmīgi pievienots';
      header("Location: product-add");
      exit;
    } else {
      echo "Kļūda pievienojot produktu";
    }
  } 
}
?>
<div class="container">
  <div class="container-inner">
    <?php 
    if(isset($_SESSION['successMessageProduct'])) {
    ?>
    <span class="success-insertion-notification"><?php echo $_SESSION['successMessageProduct']; ?></span>
    <?php
      unset($_SESSION['successMessageProduct']);
    }
    ?>
    <form id="productAddForm" class="product-add-form product-form" action="" method="post" enctype="multipart/form-data">
      <h1>Produkta pievienošana</h1>
      <div class="form-item">
        <label>Produkta kategorija <span class="required-label">*</span></label>
        <select class="form-select" name="categoryId" id="categorySelect">
          <option selected>Izvēlies kategoriju</option>
          <?php
            //izvada kategorijas 
            $sql = "SELECT * FROM product_category";
            $result = mysqli_query($conn, $sql);
            foreach($result as $row) {
          ?>
              <option value="<?php echo $row['id'];?>"
              <?php
              //Ja forma tiek nosutita ar tuksiem laukiem
              if(isset($_POST['categoryId']) && $_POST['categoryId'] == $row['id']) echo 'selected'; 
              ?>>
              <?php echo $row['name'];?>
              </option>
              <?php    
            }
          ?>
        </select></br>
        <?php if(isset($errorEmptyCategory)) { ?>
          <span class="error-message"><?php echo $errorEmptyCategory; ?></span>
        <?php } ?>
      </div>
      <div class="form-item">
        <label>Produkta apakškategorija</label>
        <select class="form-select" name="subcategoryId" id="subcategorySelect">
          <option selected>Izvēlies apakškategoriju</option>
          <?php
          //Ja forma tiek nosutita ar tuksiem laukiem
          if(isset($_POST['categoryId'])) {
            $categoryId = $_POST['categoryId'];
            $sql = "SELECT * FROM product_subcategory WHERE categoryId = '$categoryId'";
            $result = mysqli_query($conn, $sql);
            foreach($result as $row) {
          ?>
            <option value="<?php echo $row['id'];?>"
            <?php if(isset($_POST['subcategoryId']) && $_POST['subcategoryId'] == $row['id']) echo 'selected'; ?>>
            <?php echo $row['name'];?>
            </option>
            <?php
            }
          } 
          ?>
        </select>  
      </div> 
      <div class="form-item">
        <label>Produkta nosaukums <span class="required-label">*</span></label>
        <input id="productName" class="product-name-input" type="text" name="name" oninput="generateSlug()" value="<?php if(isset($name)) { echo $name; }?>"></br>
        <?php if(isset($errorName)) { ?>
          <span class="error-message"><?php echo $errorName; ?></span>
        <?php } ?>
      </div>
      <div class="form-item">
        <label>Produkta birka <span class="required-label">*</span></label>
        <input id="productSlug" class="product-name-input" type="text" name="slug" value="<?php if(isset($slug)) { echo $slug; }?>"></br>
        <?php if(isset($errorSlug)) { ?>
          <span class="error-message"><?php echo $errorSlug; ?></span>
        <?php } ?>
      </div>
      <div class="form-item form-item-description">
        <label>Produkta apraksts</label>
        <textarea name="description" rows="10" cols="75"><?php if(isset($description)) { echo $description; } ?></textarea>
      </div>
      <div class="form-item">
        <label>Produkta cena <span class="required-label">*</span></label>
        <input class="product-price-input" type="text" name="price" value="<?php if(isset($price)) { echo $price; }?>"></br>
        <?php if(isset($errorPrice)) { ?>
          <span class="error-message"><?php echo $errorPrice; ?></span>
        <?php } ?>
      </div>
      <div class="form-item">
        <label>Produkta cena ar atlaidi</label>
        <input class="product-price-input" type="text" name="discount" value="<?php if(isset($salePrice)) { echo $salePrice; }?>">
      </div>
      <div class="form-item">
        <label>Produkta izmērs</label>
        <input type="text" name="size" value="<?php if(isset($_POST['size'])) { echo $_POST['size']; } ?>">
      </div>
      <div class="form-item">
        <label>Produktu daudzums <span class="required-label">*</span></label>
        <input type="number" class="input-qty" value="<?php echo $qty;?>" name="qty" size="5" min="0" value="<?php if(isset($qty)) { echo $qty; }?>"></br>
        <?php if(isset($errorQty)) { ?>
          <span class="error-message"><?php echo $errorQty; ?></span>
        <?php } ?>
      </div>
      <div class="form-item">
        <label>Produkta attēls</label>
        <input type="file" name="featuredImage" onchange="previewFile()">
        <div id="previewFile"></div>
      </div>
      <div class="form-item">
        <label>Produkta galerija</label>
        <input type="file" name="images[]" multiple onchange="previewFiles()">
        <div id="previewFiles"></div>
      </div>
      <div class="form-item">
        <input class="product-add-btn save-btn" type="submit" name="productAdd" value="Saglabāt">
      </div>
    </form>
  </div>
</div>
<?php require_once('footer.php'); ?>