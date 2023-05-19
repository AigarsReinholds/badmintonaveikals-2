<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM product WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  $count = mysqli_num_rows($result);
  if($count > 0) { 
    $row = mysqli_fetch_array($result);
    $id = $row['id'];
    $categoryId = $row['categoryId'];
    $subcategoryId = $row['subcategoryId'];
    $name = $row['name'];
    $slug = $row['slug'];
    $description = $row['description'];
    $price = $row['price'];
    $salePrice = $row['discount'];
    $qty = $row['qty'];
    $image = $row['featuredImage'];
    $galleryImageSql = "SELECT * FROM product_image WHERE productId = '$id'";
    $galleryResult = mysqli_query($conn, $galleryImageSql);

    $sizeSql = "SELECT * FROM product_size WHERE productId = '$id'";
    $sizeResult = mysqli_query($conn, $sizeSql);
    $sizes = array();
    while($sizeRow = mysqli_fetch_array($sizeResult)) {
      $sizes[] = $sizeRow['sizeValue'];
    }
  } else {
    header("Location: product");
  }
}
?>
<div class="container">
  <div class="container-inner">
    <?php 
    if(isset($_SESSION['successEditMessageProduct'])) {
    ?>
    <span class="success-insertion-notification"><?php echo $_SESSION['successEditMessageProduct']; ?></span>
    <?php
      unset($_SESSION['successEditMessageProduct']);
    }
    ?>
    <div class="container-inner-top">
      <div class="product-edit">
        <a href="product-add">Pievienot jaunu produktu</a>
      </div>
    </div>
    <h2>Rediģēt produktu:</h2>
    <form class="product-edit-form product-form" method="post" action="" enctype="multipart/form-data">
      <div class="form-item">
        <label>Kategorija</label>
        <select name="categoryId">
          <option value="" selected>Izvēlies kategoriju</option>
          <?php
          $sql = "SELECT * FROM product_category";
          $result = mysqli_query($conn, $sql);
          while($row = mysqli_fetch_array($result)) {
            if($row['id'] == $categoryId) {
          ?>
              <option value="<?php echo $row['id']?>" selected><?php echo $row['name'];?></option>
          <?php
           } else { ?>
            <option value="<?php echo $row['id']?>"><?php echo $row['name'];?></option>
          <?php 
            }
          }
          ?>
        </select>
      </div>
      <div class="form-item">
        <label>Apakškategorija</label>
        <select name="subcategoryId">
          <option value="" >Izvēlies apakškategoriju</option>
          <?php
          $sql = "SELECT * FROM product_subcategory WHERE categoryId = '$categoryId'";
          $result = mysqli_query($conn, $sql);
          while($row = mysqli_fetch_array($result)) {
            if($row['id'] == $subcategoryId) {
          ?>
              <option value="<?php echo $row['id']?>" selected><?php echo $row['name'];?></option>
          <?php
          } else { ?>
            <option value="<?php echo $row['id']?>"><?php echo $row['name'];?></option>
         <?php  
            }
          }
          ?>
        </select>
      </div>
      <div class="form-item">
        <label>Produkta nosaukums</label>
        <input class="product-name-input" type="text" name="name" value="<?php echo $name;?>">
      </div>
      <div class="form-item">
        <label>Produkta birka</label>
        <input class="product-slug-input" type="text" name="slug" value="<?php echo $slug;?>">
      </div>
      <div class="form-item">
        <label>Produkta cena €</label>
        <input class="product-price-input" type="text" name="price" value="<?php echo $price;?>">
      </div>
      <div class="form-item">
        <label>Produkta cena ar atlaidi €</label>
        <input class="product-price-input" type="text" name="discount" value="<?php echo $salePrice;?>">
      </div>
      <div class="form-item">
        <label>Produkta apraksts</label>
        <textarea class="product-description-input" name="description" rows="10" cols="75"><?php echo $description;?></textarea>
      </div>
      <div class="form-item">
        <label>Produkta izmērs</label>
        <input type="text" name="size" 
        value="
        <?php foreach($sizes as $size) { ?>
          <?php echo $size; ?>
        <?php } ?>
        ">
      </div>
      <div class="form-item">
        <label>Produktu daudzums</label>
        <input type="number" class="input-qty" value="<?php echo $qty;?>" name="qty" size="5" min="0">
      </div>
      <div class="form-item">
        <label>Produkta attēls</label>
        <input type="file" name="featuredImage">
        <input type="hidden" name="featuredImageOld" value="<?php echo $image;?>">
      </div>
      <div class="form-item">
        <img src="../assets/img/<?php echo $image; ?>" width = "100px" height = "100px" alt="attēls">
        <button class="img-delete-btn" type="submit" name="featuredImageDelete"><i class="fa-solid fa-trash"></i></button>
      </div>
      <div class="form-item">
        <label>Produkta galerija</label>
        <input type="file" name="galleryImages[]" multiple>
      </div>
      <div class="form-item">
        <?php 
        while($row = mysqli_fetch_array($galleryResult)) {
        ?>
        <div class="gallery-item">
            <img src="../assets/img/product/<?php echo $row['image']; ?>" width = "100px" height = "100px" alt="">
            <button class="img-delete-btn" type="submit" name="galleryImageDelete"><i class="fa-solid fa-trash" data-id="<?php echo $row['id']; ?>"></i></button>
        </div>
        <?php  
        }
        ?>
      </div>  
      <div class="form-item">
        <input class="product-edit-btn save-btn" type="submit" name="productEdit" value="Saglabāt">
      </div>
    </form>
    <?php
if(isset($_POST['productEdit'])) {
  $categoryId = $_POST['categoryId'];
  $subcategoryId = $_POST['subcategoryId'];
  $name = $_POST['name'];
  $slug = $_POST['slug'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $salePrice = $_POST['discount'];
  $qty = $_POST['qty'];
  $newImage = $_FILES['featuredImage']['name'];
  $oldImage = $_POST['featuredImageOld'];
  $tmpImageName = $_FILES['featuredImage']['tmp_name'];
  $path = "../assets/img/";
  if($newImage != "") {
    $imageExt = pathinfo($newImage, PATHINFO_EXTENSION);
    $updateFileName = time().'.'.$imageExt;
  } else {
      $updateFileName = $oldImage;
  }
  if($_FILES['featuredImage']['error'] == 0) {
    if(file_exists($path.$newImage)) {
      $fileName = $_FILES['featuredImage']['name'];
      echo "Attēls jau eksistē: ".$fileName;
    } else {
        $sql = "UPDATE product SET name = '$name', slug = '$slug', description = '$description', price = '$price', discount = '$salePrice', qty = '$qty', featuredImage = '$updateFileName', categoryId = '$categoryId', subcategoryId = '$subcategoryId' WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        if($result) {
          move_uploaded_file($tmpImageName, "../assets/img/".$updateFileName);
          unlink($path.$oldImage);
          $_SESSION['successEditMessageProduct'] = 'Produkta informācija veiksmīgi rediģēta';
          header("Location: product-edit?id=$id");
          exit;
        } else { 
          echo "Kļūda rediģējot produktu";
        }
      }  
  } else {
    $sql = "UPDATE product SET name = '$name', slug = '$slug', description = '$description', price = '$price', discount = '$salePrice', qty = '$qty', featuredImage = '$updateFileName', categoryId = '$categoryId', subcategoryId = '$subcategoryId' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if($result) {
      if(isset($_POST['size']) && !empty($_POST['size'])) {
        $sizes = explode(',', $_POST['size']);
        foreach($sizes as $size) {
          $size = trim($size);
          if(empty($size)) {
            continue;
          }
          $sizeSql = "INSERT INTO product_size (productId, sizeValue) VALUES ('$id', '$size')";
          $sizeResult = mysqli_query($conn, $sizeSql);
        }
      }
      $_SESSION['successEditMessageProduct'] = 'Produkta informācija veiksmīgi rediģēta';
      header("Location: product-edit?id=$id");
      exit;
    } else {
      echo "Kļūda rediģējot produktu";
    }
  }
  if(isset($_FILES['galleryImages']['name'][0])) {
    $galleryImageSql = "SELECT * FROM product_image WHERE productId = '$id'";
    $galleryResult = mysqli_query($conn, $galleryImageSql);
    $galleryFiles = $_FILES['galleryImages'];
    $galleryPath = "../assets/img/product/";
    while($row = mysqli_fetch_array($galleryResult)) {
      unlink($galleryPath.$row['image']);
    }
    $galleryFileError = array();
    for($i = 0; $i < count($galleryFiles['name']); $i++) {
      $galleryImageExt = pathinfo($galleryFiles['name'][$i], PATHINFO_EXTENSION);
      $galleryImageName = time().'_'.$i.'.'.$galleryImageExt;
      $galleryTmpImageName = $galleryFiles['tmp_name'][$i];
      if(!move_uploaded_file($galleryTmpImageName, $galleryPath.$galleryImageName)) {
        $galleryFileError[] = $galleryFiles['name'][$i];
      } else {
        $galleryImageSql = "INSERT INTO product_image(image, productId) VALUES ('$galleryImageName', '$id')";
        $galleryResult = mysqli_query($conn, $galleryImageSql);
      }
    }
    if(!empty($galleryFileError)) {
      echo "Notika kļūda, pievienojot šos failus: ".implode(',', $galleryFileError);
    }
  }
}
if(isset($_GET['id'])) {  
  $id = $_GET['id'];
  $path = "../assets/img/";
  if(isset($_POST['featuredImageDelete'])) {
    if(file_exists($path.$image)) {
      unlink($path.$image);
    }
    $sql = "UPDATE product SET featuredImage = '' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
  }
  if($_POST['galleryImageDelete']) {
    $gallerySql = "DELETE FROM product_image WHERE productId = '$id'";
    $result = mysqli_query($conn, $gallerySql);
  }
  if($result) {
    header("Location: product-edit?id=$id");
  } else {
    echo "Kļūda dzēšot produkta attēlu";
  }
}
   ?>  
  </div>
</div>
<?php require_once('footer.php'); ?>