    <script  type="text/javascript" charset="utf-8" async defer>
       var data = <?php echo $this->session->userdata('urisegment');?>;
       $(function() {
			if (data == 0 || data > 0) {
				stocktodas(data);
				<?php $this->session->set_userdata('urisegment',null);?>
			}
       });
    </script>