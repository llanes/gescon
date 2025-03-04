var toastem = (function($){

    var normal = function(content){
      var item = $('<div class="notification normal"><span>'+content+'</span></div>');
      $("#toastem").append($(item));
      $(item).animate({"right":"12px"}, "fast");
      setInterval(function(){
        $(item).animate({"right":"-400px"},function(){
          $(item).remove();
        });
      },3500);
  };
  
  
  
  var success = function(content){
        var item = $('<div class="notification success"><span>'+content+'</span></div>');
        $("#toastem").append($(item));
        $(item).animate({"right":"12px"}, "fast");
        setInterval(function(){
          $(item).animate({"right":"-400px"},function(){
            $(item).remove();
          });
        },3500);
  };
  
  
  var error = function(content){
      var item = $('<div class="notification error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>  <span>'+content+'</span></div>');
      $("#toastem").append($(item));
      $(item).animate({"right":"20px"}, "fast");
      setInterval(function(){
        $(item).animate({"right":"-400px"},function(){
          $(item).remove();
        });
      },5000);
  };
  var abrir = function(content){
      var item = $('<div class="notification abrir"><i class="fa fa-check" aria-hidden="true"></i> <span>'+content+'</span></div>');
      $("#toastem").append($(item));
      $(item).animate({"right":"12px"}, "fast");
      setInterval(function(){
        $(item).animate({"right":"-400px"},function(){
          $(item).remove();
        });
      },3500);
  };
  var cerrar = function(content){
      var item = $('<div class="notification cerrar"><i class="fa fa-check" aria-hidden="true"></i> <span>'+content+'</span></div>');
      $("#toastem").append($(item));
      $(item).animate({"right":"12px"}, "fast");
      setInterval(function(){
        $(item).animate({"right":"-400px"},function(){
          $(item).remove();
        });
      },3500);
  };
  
    $(document).on('click','.notification', function(){
        $(this).fadeOut(400,function(){
          $(this).remove();
        });
    });
  
    return{
      normal: normal,
      success: success,
      error: error,
      abrir: abrir,
      cerrar: cerrar
  
    };
  
  
  })(jQuery);
  
  var formatNumber = {
   separador: ",", // separador para los miles
   sepDecimal: '.', // separador para los decimales
   formatear:function (num){
     num +='';
     var splitStr = num.split('.');
     var splitLeft = splitStr[0];
     var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
     var regx = /(\d+)(\d{3})/;
     while (regx.test(splitLeft)) {
       splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
     }
     return splitLeft +splitRight;
   },
   new:function(num, simbol){
     return this.formatear(num);
   }
  }
  
  function operaciones(num,num2,acction) {
    if(!isNaN(num) && !isNaN(num2)){
      switch(acction) {
        case '*':
              return (parseFloat(num) * parseFloat(num2));
          break;
        case '-':
              return (parseFloat(num) - parseFloat(num2));
          break;
        case '+':
              return (parseFloat(num) + parseFloat(num2));
          break;
      case '/':
              return (parseFloat(num) / parseFloat(num2));
          break;
      }
  
    }
    return num+num2;
  
  
  }
  
  function liteString(limite_text,max_chars) {
    if (limite_text.length > max_chars) {
      // toastem.success(limite_text);
      return  limite_text.substr(0, max_chars)+" ...";
    }else{
      // toastem.success(limite_text);
      return limite_text;
    }
  }
  
  function liteStringmncv(limite_text,max_chars) {
    if (limite_text.length > max_chars) {
      // toastem.success(limite_text);
      return  limite_text.substr(0, max_chars)+" ...";
    }else{
      // toastem.success(limite_text);
      return limite_text;
    }
  }
  
  function pdf_exporte (direccion,id) {
    if (id > 0) {
      window.open('Reportes/'+direccion+'/'+id+'','_blank','height='+screen.height+', width='+screen.width);
    }else{
         window.open('Reportes/'+direccion+'','_blank','height='+screen.height+', width='+screen.width);
    }
  
  
  }
  function pdfexport (direccion,id) {
    var fecha = $('#buscaprfecha').val();
    var caja = $('#seleccaja').val();
    var forma = $('#selectforma').val();
      window.open('Reportes/'+direccion+'/'+fecha+'/'+caja+'/'+forma+'','_blank','height='+screen.height+', width='+screen.width);
  }
  
  function pdfmayor (direccion,id) {
    var fecha = $('#fechamayor').val();
    var caja = $('#cajamayor').val();
    var forma = $('#planmayor').val();
      window.open('Reportes/'+direccion+'/'+fecha+'/'+caja+'/'+forma+'','_blank','height='+screen.height+', width='+screen.width);
  }
  
  function pdfbalance (direccion,id) {
    var mes = $('#fechames').val();
    var ano = $('#fechaanmo').val();
      window.open('Reportes/'+direccion+'/'+ano+'/'+mes,'_blank','height='+screen.height+', width='+screen.width);
  }
  
  function pdf_null(direccion,id) {
    var val = $('#controllll').val();
    window.open('Reportes/'+direccion+'/'+val,'_blank','height='+screen.height+', width='+screen.width);
  
  }
  function exel_exporte(direccion,id) {
  
  
      if (id > 0) {
      var url = "Reporte_exel/"+direccion+"/" + id;
    }else{
      var url = "Reporte_exel/"+direccion+"";
    }
  
    $.ajax({
      url : url,
      type: 'POST',
      dataType: 'json',
      // data: {param1: 'value1'},
    })
    .done(function() {
      console.log("success");
    })
    .fail(function() {
      console.log("error");
    })
    .always(function() {
      console.log("complete");
    });
    
    // var val = $('#controllll').val();
    // window.open('Reportes/'+direccion+'/'+val,'_blank','height='+screen.height+', width='+screen.width);
  
  }
  
  function limpiar(text){
  
        text = text.replace(/[áàäâå]/, 'a');
        text = text.replace(/[éèëê]/, 'e');
        text = text.replace(/[íìïî]/, 'i');
        text = text.replace(/[óòöô]/, 'o');
        text = text.replace(/[úùüû]/, 'u');
        text = text.replace(/[ýÿ]/, 'y');
        text = text.replace(/[ñ]/, 'n');
        text = text.replace(/[ç]/, 'c');
        text = text.replace(/['"]/, '');
        text = text.replace(/[^a-zA-Z0-9-]/, ''); 
        text = text.replace(/\s+/, '-');
        text = text.replace(/' '/, '-');
        text = text.replace(/(_)$/, '');
        text = text.replace(/^(_)/, '');
        text = text.replace(/^_/,'');
        return text;
     }
  function alertacaja(){
    Swal.fire("CAJA CERRADA!", "ES NECESARIO ABRIR CAJA PARA GENERAR TRANSACCIONES");
  }
  
  (function($) {
    jQuery.isEmpty = function(obj){
      var isEmpty = false;
      if (typeof obj == 'undefined' || obj === null || obj === ''){
        isEmpty = true;
      }
      if (typeof obj == 'number' && isNaN(obj)){
        isEmpty = true;
      }
      if (obj instanceof Date && isNaN(Number(obj))){
        isEmpty = true;
      }
      return isEmpty;
    }
  })(jQuery);
  
  
  $(window).keypress(function(event) {
    // toastem.success(event.which );
    if (event.which == 2){
          if (save_method == 'add') {
           $('[name="productos"]').select2("close");
           $("#cuotas").click();
           $("button[name=add]").click();
          }else if (save_method == 'add_add') {
            $("button[name=add_add]").click();
          }else if (save_method == 'add_cliente') {
            $("button[name=loading]").click();
          }else if (save_method == 'add_add_') {
            $("button[name=add_add_]").click();
          }if (save_method == 'update') {
            $("button[name=add_add]").click();
          }
    }else  if (event.which == 26){
        $('[name="productos"]').select2("open");
    }else  if (event.which == 17 ){
        $("button[name=seart_t]").click();
        $('[name="Cliente"]').click();
    }else if ( event.which == 10){
        $('[name="montofinal"]').focus();
        $('[name="seartttt"]').click();
    }else  {
      return  true;
    } 
  
  event.preventDefault();
  });
  
  function lismeses(meses){
    if (meses == 1) {return "Enero";}
    if (meses == 2) {return "Febrero";}
    if (meses == 3) {return "Marzo";}
    if (meses == 4) {return "Abril";}
    if (meses == 5) {return "Mayo";}
    if (meses == 6) {return "Junio";}
    if (meses == 7) {return "Julio";}
    if (meses == 8) {return "Agosto";}
    if (meses == 9) {return "Septiembre";}
    if (meses == 10) {return "Octubre";}
    if (meses == 11) {return "Noviembre";}
    if (meses == 12) {return "Diciembre";}
  }
  
  
  
   
  
   
  
   
  
   
  
   
  
   
  
   
  
   
  
   
  
   
  
   
  