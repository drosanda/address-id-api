<!DOCTYPE html>
<html lang="en">
<?php $this->getThemeElement("page/html/head",$__forward); ?>
<?php $this->getBodyBefore(); ?>
<body class="">
  <!-- top -->
  <?php $this->getThemeElement("page/html/topbar",$__forward); ?>
  <!-- end top -->

  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <!-- main content -->
        <div class="text-center" style="padding-top: 1vh;padding-bottom: 10vh;">
          <h1 style="font-size: 14em; font-weight: 100; padding-bottom:0; margin-bottom: 0;">404</h1>
          <p>The page you request currently unavailable or underconstruction.</p>
        </div>
        <!-- end of main content -->
      </div>


    </div>
    <!--footer-->
    <?php $this->getThemeElement('page/html/footer',$__forward); ?>
    <!--end footer-->
  </div>
  <?php $this->getJsFooter(); ?>

  <script>
  $(document).ready(function(e){
    <?php $this->getJsReady(); ?>
    <?php $this->getJsContent(); ?>
  });
  </script>
</body>
</html>
