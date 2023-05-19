<?php require_once('header.php'); ?>
<?php
if(isset($_POST['categoryId'])) {
  $categoryId = $_POST['categoryId'];
  $sql = "SELECT * FROM product_subcategory WHERE categoryId = '$categoryId'";
  $result = mysqli_query($conn, $sql);
  $options = '<option selected>Izvēlies apakškategoriju</option>';
    foreach($result as $row) {
      $selected = '';
      if(isset($_POST['subcategoryId']) && $_POST['subcategoryId'] == $row['id']) {
        $selected = 'selected';
      }
      $options .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }
    echo $options;
}
?>
<?php require_once('footer.php'); ?>
