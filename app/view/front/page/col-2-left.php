<?php
//Example 1 column layout
?>
<!doctype html>
<html amp lang="en">
<?php $this->getThemeElement("page/html/head",$__forward); ?>
<?php $this->getBodyBefore(); ?>
<body class="">
  <?php $this->getThemeElement("page/html/menu_left",$__forward);?>
  <?php $this->getThemeElement("page/html/header",$__forward);?>

  <div class="main-content">
    <!-- main content-->
    <?php $this->getThemeContent(); ?>
    <!-- main content-->
  </div>

  <!--footer-->
  <?php $this->getThemeElement('page/html/footer',$__forward); ?>
  <!--end footer-->

  <!-- load JS in footer-->
  <?php $this->getJsFooter(); ?>
  <!-- End load JS in footer-->

  <!-- default JS Script-->
  <script>
  $(document).ready(function(e){
    <?php $this->getJsReady(); ?>
    <?php $this->getJsContent(); ?>
  });
  </script>
  <!-- default JS Script-->
</body>
</html>
