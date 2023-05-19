<?php require_once('header.php'); ?>
<!-- Produktu kategorijas -->
<div class="container">
  <div class="product-categories">
    <?php 
      if(isset($_SESSION['successRegistration'])) {
      ?>
      <span class="success-message"><?php echo $_SESSION['successRegistration']; ?></span>
      <?php
        unset($_SESSION['successRegistration']);
      }
      ?>
    <h2 class="categories-title">Katalogs</h2>
    <?php
      $sql = "SELECT * FROM product_category";
      $result = mysqli_query($conn, $sql);
      $count = 0;
      $row = mysqli_fetch_assoc($result);
      foreach ($result as $row) {
        //parbauda vai kategorijai ir apakskategorijas
        $categoryId = $row['id'];
        $sqlSubcategory = "SELECT * FROM product_subcategory WHERE categoryId = '$categoryId'";
        $resultSubcategory = mysqli_query($conn, $sqlSubcategory);
        if ($count % 4 == 0) {
    ?>
    <div class="row row-product-categories">
      <?php } $count++; ?>
      <div class="product-category">
        <?php
        //parbauda vai kategorijai ir apakskategorijas
        if(mysqli_num_rows($resultSubcategory) > 0) {
        ?>
        <a href="subcategory?category=<?php echo $row['slug']; ?>">
        <?php } else { ?>
        <a href="product?category=<?php echo $row['slug']?>">
        <?php } ?>
        <img class="product-category-img" src="assets/img/<?php echo $row['image'];?>" width="300px" height="300px" alt="Produkta kategorijas attēls">
        <h4 class="product-category-title"><?php echo $row['name'];?></h4>
        </a>
      </div>
      <?php if ($count % 4 == 0) {  ?>
    </div>
    <?php
        } 
      } 
    ?>  
  </div>
  <div class="latest-products">
    <h2 class="latest-products-title">Jaunākie produkti</h2>
    <?php
      $sql3 = "SELECT * FROM product ORDER BY id DESC LIMIT 6";
      $result3 = mysqli_query($conn, $sql3);
      $count2 = 0;
      $row3 = mysqli_fetch_assoc($result3);
      if (mysqli_num_rows($result3) > 0) {
        foreach ($result3 as $row3) {
          $row3['price'] = str_replace('.',',',$row3['price']);
          if($row3['discount'] != 0.00) {
            $row3['discount'] = str_replace('.',',',$row3['discount']);
          }
          if ($count2 % 3 == 0) {
    ?>
    <div class="row row-product-categories">
      <?php } $count2++; ?>
        <div class="product">
          <a href="product-single?product=<?php echo $row3['slug'];?>">
          <img class="product-img" src="assets/img/<?php echo $row3['featuredImage'];?>" width="300px" height="300px" alt="Produkta attēls">
          <h4 class="product-category-title"><?php echo $row3['name'];?></h4>
          <div class="product-price">
            <?php
            if($row3['discount'] != 0.00) {
              echo '<strike><span class="regular-price">'.$row3['price'].' €</span></strike>';
              echo '<span class="discount-price">'.$row3['discount'].' €</span>';
            } else {
              echo '<span>'.$row3['price'].' €</span>';
            }
            ?>
          </div>
          <div class="stock">
            <?php if($row3['qty'] >= 1) { ?>
              <span class="qty-status-stock">Noliktavā</span>
            <?php } else { ?>
              <span class="qty-status-stockout">Nav noliktavā</span>
            <?php } ?>
          </div>
          </a>
        </div>
      <?php if ($count2 % 3 == 0) {  ?>
      </div>
      <?php
      }
    }
  } 
      ?>
    </div>
  <div class="contact-us-section" id="contactSection">
    <h1 class="title">Sazinies ar mums!</h1>
      <?php if(isset($_SESSION['messageSuccess'])) { ?>
        <span class="success-message"><?php echo $_SESSION['messageSuccess']; ?></span>
      <?php unset($_SESSION['messageSuccess']); } ?>
      <?php if(isset($_SESSION['messageFailed'])) { ?>
        <span class="error-message"><?php echo $_SESSION['messageFailed']; ?></span>
      <?php unset($_SESSION['messageFailed']); } ?>
      <?php if(isset($_SESSION['messageFieldEmpty'])) { ?>
        <span class="error-message"><?php echo $_SESSION['messageFieldEmpty']; ?></span>
      <?php unset($_SESSION['messageFieldEmpty']); } ?>
      <?php if(isset($errorEmpty)) { ?><span class="error-message"><?php echo $errorEmpty; ?></span><?php } ?>
    <form id="messageContactform" class="contactform" method="POST" action="send-message.php"> <!-- onsubmit="submitContactForm(event)" -->
      <div class="form-item contactform-item">
        <label class="contactform-label">Vārds <span class="required-label">*</span></label>
        <input class="input-name" type="text" name="name" value="<?php if(isset($name)) { echo $name; }?>"></br>
      </div>
      <div class="form-item contactform-item">
        <label class="contactform-label">E-pasts <span class="required-label">*</span></label>
        <input class="input-email" type="email" name="email" value="<?php if(isset($email)) { echo $email; }?>"></br>
      </div>
      <div class="form-item contactform-item">
        <label class="contactform-label">Temats <span class="required-label">*</span></label>
        <input class="input-subject" type="text" name="subject" value="<?php if(isset($subject)) { echo $subject; }?>"></br>
      </div>     
      <div class="form-item contactform-item">
        <label class="contactform-label">Ziņa <span class="required-label">*</span></label>
        <textarea class="input-message" name="message" rows="10" cols="75"><?php if(isset($message)) { echo $message; }?></textarea>
      </div>
      <div class="form-item">
        <button id="sendMessage" class="message-btn" type="submit" name="sendMessage">Nosūtīt</button>
      </div>
    </form>
  </div>
</div>
</div>
<?php require_once('footer.php'); ?>