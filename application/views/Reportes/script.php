<link href="<?php echo base_url();?>content/plugins/pikear/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src ="<?php echo base_url();?>bower_components/select2/dist/js/select2.js"></script> 
<script src ="<?php echo base_url();?>bower_components/select2/dist/js//i18n/es.js"></script>
<!-- <script src="<?php echo base_url();?>content/plugins/chartjs/Chart.min.js" type="text/javascript"></script> -->
<!-- datetimepicker-->
<script src="<?php echo base_url();?>content/plugins/pikear/js/moment.js"></script>
<script src="<?php echo base_url();?>content/plugins/pikear/es.js"></script>
<script src="<?php echo base_url();?>content/plugins/pikear/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
  $(function() {
            $( "#Reportes,#reporcaja" ).addClass( "active" );
      $( "#repor_caja" ).addClass( "text-red" );

	  $( "#caja,#reservation" ).select2( {
	        allowClear: true,
	        placeholder: 'Busca',
	        width: null,
	          theme: "bootstrap"
	      } );

  });
  // datetime picker end
     $(function () {
            $('#fecha').datetimepicker({
			// minDate: new Date(),
			format: 'YYYY-MM-DD',
            });
            $('#fecha2').datetimepicker({
			// minDate: new Date(),
			format: 'YYYY-MM-DD',
            });
            $("#fecha").on("dp.change", function (e) {
                $('#fecha2').data("DateTimePicker").minDate(e.date);
            });
            $("#fecha2").on("dp.change", function (e) {
                $('#fecha').data("DateTimePicker").maxDate(e.date);
            });
    });

function view_my_report() {  
    $('#view_form').attr('action', 'Caja');
   var mapForm = document.getElementById("view_form");
   map=window.open("","Map","status=0,title=0,height="+screen.height+",width="+screen.width+",scrollbars=1");
   if (map) {
      mapForm.submit();
   } else {
      alert('You must allow popups for this map to work.');
   }
}
function listarpdf(){
		var inicio      = $('#fecha').val();
		var fin         = $('#fecha2').val();
		var caja        = $('#caja').val();
 		$.ajax({
 			url: '<?php echo site_url('Reporte/caja_list'); ?>',
 			type: 'POST',
 			dataType: 'json',
 			data: {inicio: inicio,fin: fin,caja: caja,reservation: reservation},
 		})
 		.done(function(data) {
 		})
 		.fail(function() {
 			console.log("error");
 		})
 		.always(function() {
 			console.log("complete");
 		});


}
$(function() {

	$('#EXEL').click(function(event){
	$('#view_form').attr('action', 'Caja/1');
		$('[name="view_form"]').submit();
	});
});


    </script>