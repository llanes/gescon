<link href   ="<?php echo base_url();?>content/plugins/pikear/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src  ="<?php echo base_url();?>bower_components/select2/dist/js/select2.js"></script> 
<script src  ="<?php echo base_url();?>content/plugins/pikear/js/moment.js"></script>
<script src  ="<?php echo base_url();?>content/plugins/pikear/es.js"></script>
<script src  ="<?php echo base_url();?>content/plugins/pikear/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
var table,loadbalance,table2,save_method;
$(function() {
  $('#aciento').tab('show');

  // $('#tabla_acientos').DataTable();
});
$( "#acien,#Contabilidad" ).addClass( "active" );
$( "#a_c_i_e_n" ).addClass( "text-red" );
$( "#accion" ).addClass( "fa fa-plus-square" );
    $(function() {
    table =   $('#tabla_acientos').dataTable({
             // processing: true,
             serverSide: true,
             // bPaginate : false,
             bFilter : false,
             // bInfo : false,
             bAutoWidth : false,
             bLengthChange : false,
             // sort : false,
              // Load data for the table's content from an Ajax source
              ajax: {
                  url: "<?php echo site_url('AcientoContable/ajax_list'); ?>",
                  type: "POST",
                dataSrc: function(data){
                  // $("#monto_final1").append('<p class="text-danger">'+data.Importe+'&nbsp;₲.</p>').css({"display":"block"});
                  // Importe = (data.Importe + '').replace(/[^0-9]/g, '');
                  // $('[name="Importe"]').val(Importe);
                  // $('[name="editarmonto"]').val(data.Importe);
                 return data.data;


                }
              },
              //Set column definition initialisation properties.
              columnDefs: [
              { 
                targets: [ -1 ], //last column
                orderable: false, //set not orderable
              },
              ],

            });
    });

    function reload_table()
    {
      table.ajax.reload(); //reload datatable ajax 
    }

    function reloadtable() {

    }

$(function() {

  $('#selectforma,#seleccaja').change(function() {
    var fecha =$('#buscaprfecha').val();

    var caja =$('#seleccaja').val();
    var con =$('#selectforma').val();
            $('#aciento_busqueda').dataTable().fnDestroy();
                table2 =  $('#aciento_busqueda').dataTable({
                   serverSide: true,
                   bFilter : false,
                   bAutoWidth : false,
                   bLengthChange : false,
                    ajax: {
                        url: "<?php echo site_url('AcientoContable/ajax'); ?>",
                        type: "POST",
                        data: {fecha:fecha,caja:caja,con:con,},
                        dataSrc: function(data){
                       return data.data;
                      }
                    },
                    columnDefs: [
                    { 
                      targets: [ -1 ], //last column
                      orderable: false, //set not orderable
                    },
                    ],

                  });
  });
  $( "#planmayor,#cajamayor,#cuenta_bancaria,#seleccaja,#selectforma" ).select2( {
          allowClear: true,
          placeholder: 'Busca y Selecciona',
          width: null,
          theme: "bootstrap"
        } );
    $('#buscaprfecha').datetimepicker({
         // minDate: new Date(),
         format: 'YYYY-MM-DD',
         disabledHours: [0, 1, 2, 3, 4,] ,
         enabledHours: [ 5, 6, 7, 12,18, 19, 20, 21, 22, 23, 24,8,9, 10, 11, 13, 14, 15, 16] ,
    }).on('dp.change',function(event){
    var fecha =$('#buscaprfecha').val();
    var caja =$('#seleccaja').val();
    var con =$('#selectforma').val();
            $('#aciento_busqueda').dataTable().fnDestroy();
                table2 =  $('#aciento_busqueda').dataTable({
                   serverSide: true,
                   bFilter : false,
                   bAutoWidth : false,
                   bLengthChange : false,
                    ajax: {
                        url: "<?php echo site_url('AcientoContable/ajax'); ?>",
                        type: "POST",
                        data: {fecha:fecha,caja:caja,con:con,},
                        dataSrc: function(data){
                       return data.data;
                      }
                    },
                    columnDefs: [
                    { 
                      targets: [ -1 ], //last column
                      orderable: false, //set not orderable
                    },
                    ],

                  });
  });


  $('#planmayor,#cajamayor').change(function() {
    $('#ALERTADOS').hide();
    var fecha =$('#fechamayor').val();
    var plan  =$('#planmayor').val();
    var caja   =$('#cajamayor').val();
    var data  = {fecha:fecha,plan:plan,caja:caja,};
    var url   = "<?php echo site_url('AcientoContable/load_mayor'); ?>";
       $('#loader').load( url , data );
  });
    $('#fechamayor').datetimepicker({
         // minDate: new Date(),
         format: 'YYYY-MM-DD',
         disabledHours: [0, 1, 2, 3, 4,] ,
         enabledHours: [ 5, 6, 7, 12,18, 19, 20, 21, 22, 23, 24,8,9, 10, 11, 13, 14, 15, 16] ,
    }).on('dp.change',function(event){
    var fecha =$('#fechamayor').val();
    var plan  =$('#planmayor').val();
    var caja   =$('#cajamayor').val();
    var data  = {fecha:fecha,plan:plan,caja:caja,};
    var url   = "<?php echo site_url('AcientoContable/load_mayor'); ?>";
       $('#loader').load( url , data );

  });

        $('#fechames').datetimepicker({
                viewMode: 'months',
              format: 'MM',
    });



    $('#fechaanmo').datetimepicker({
                viewMode: 'years',
               format: 'YYYY',
    }).on('dp.change',function(event){
    var fechames =$('#fechames').val();
    var fechaanmo  =$('#fechaanmo').val();

    var expression = '';
    if (fechames > 0) {
      expression = new Date(2021, fechames - 1).toLocaleString('es-ES', { month: 'long' });
    }
    $('#comfecha').html(expression +' Año '+ fechaanmo);
    var data  = {mes:fechames,ano:fechaanmo};
    var url   = "<?php echo site_url('AcientoContable/balance'); ?>";
           $('#loadbalance').dataTable().fnDestroy();
                loadbalance =  $('#loadbalance').dataTable({
                   serverSide: true,
                   bFilter : false,
                   bAutoWidth : false,
                   bLengthChange : false,
                   "iDisplayLength": 25,
                    ajax: {
                        url: url,
                        type: "POST",
                        data: data,
                        dataSrc: function(data){
                       return data.data;
                      }
                    },
                    columnDefs: [
                    { 
                      targets: [ -1 ], //last column
                      orderable: false, //set not orderable
                    },
                    ],

                  });

  });
});



</script>
