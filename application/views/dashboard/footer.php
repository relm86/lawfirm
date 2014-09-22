<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/libs/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
<?php if ( isset($sticky) ): ?>
<script type='text/javascript' src='<?php echo base_url();?>js/jquery.sticky.js'></script>
<script type='text/javascript' src='<?php echo base_url();?>js/sticky.js'></script>
<?php endif; ?>
<?php if ( isset($login_page) ): ?>
<script type='text/javascript' src='<?php echo base_url();?>js/login.js'></script>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/libs/plupload.full.min.js"></script>
<?php if ( isset($preview_page)) : ?>
<script src="<?=base_url('js/dashboard2.js');?>"></script>
<?php else : ?>
<script src="<?=base_url('js/dashboard.js');?>"></script>
<?php endif; ?>
</body>
</html>