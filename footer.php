    <script src="assets/js/script.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script>
    //Maina apakskategoriju sarakstu atbilstosi izveletai kategorijai
    $(document).ready(function() {
      $('select[name="categoryId"]').on('change', function() {
        var categoryId = $(this).val();
        $.ajax({
          url: 'subcategory-get.php',
          type: 'post',
          data: { categoryId: categoryId },
          success: function(response) {
            $('select[name="subcategoryId"]').html(response);
          }
        });
      });
    });
  </script>
  </body>
</html>