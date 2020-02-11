<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; 2020 <a href="#">Carbon</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <?php  $time = time() + date('Z'); ?>
      <span id="jqclock" class="jqclock" data-time="<?php echo $time?>"></span>

        @push('scripts')
        <script type="text/javascript" src="//gitcdn.link/repo/Lwangaman/jQuery-Clock-Plugin/master/jqClock.min.js"></script> 
        <script type="text/javascript"> 
          $(document).ready(function(){ 
            customtimestamp = parseInt($("#jqclock").data("time"));
            $("#jqclock").clock({"langSet":"en","timestamp ":customtimestamp});  
          }); 
        </script> 
        @endpush

        <style type="text/css"> 
          .clockdate { padding-right: 10px;} 
        </style> 
    </div>
  </footer>