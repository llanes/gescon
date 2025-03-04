function alertcaja(argument) {
Swal.fire({
  title: 'How old are you?',
  type: 'question',
  input: 'range',
  inputAttributes: {
    min: 8,
    max: 120,
    step: 1
  },
  inputValue: 25
})
}

const formatNum = (num) => {
  const separador = ",";
  const sepDecimal = ".";
  const numStr = num.toString();
  const [integerPart, decimalPart] = numStr.split(".");
  const formattedIntegerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, separador);
  const formattedDecimalPart = decimalPart ? sepDecimal + decimalPart : "";
  return formattedIntegerPart + formattedDecimalPart;
};


function alertasave(data) {
  let timerInterval;
  Swal.fire({
    icon: 'success',
    title: 'Datos Registrado correctamente',
    text: data,
    timer: 2000, // Aumentamos el tiempo de visualización de la alerta a 5 segundos
    onBeforeOpen: () => {
      Swal.showLoading();
      timerInterval = setInterval(() => {
        const strongElement = Swal.getContent().querySelector('strong');
        if (strongElement) {
          strongElement.textContent = (Swal.getTimerLeft() / 1000).toFixed(0);
        }
      }, 100);
    },
    onClose: () => {
      clearInterval(timerInterval);
    }
  });
}

function alertError(data) {
    let timerInterval;
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: data,
      timer: 2000, // Aumentamos el tiempo de visualización de la alerta a 5 segundos
      onBeforeOpen: () => {
        Swal.showLoading();
        timerInterval = setInterval(() => {
          const strongElement = Swal.getContent().querySelector('strong');
          if (strongElement) {
            strongElement.textContent = (Swal.getTimerLeft() / 1000).toFixed(0);
          }
        }, 100);
      },
      onClose: () => {
        clearInterval(timerInterval);
      }
    });
  }
  
  async function cargarContenido(url, elemento) {
    try {
      const response = await fetch(url);
      const data = await response.text();
      document.getElementById(elemento).innerHTML = data;
      totalfinal();
    } catch (error) {
      console.error('Error al cargar el contenido:', error);
    }
  }

const totalfinal = () => {
  let flete = $('.sumar').val();
  flete = (flete + '').replace(/[^0-9]/g, '');
  let carto = $('#cartotal').val();
  let id;
  let final;

  if (flete > 0) {
    id = operaciones(carto, flete, '+');
    $('.finales, #Importe1').text(formatNum(id));
    $('.finalcarrito').val(id);
    final = id;
  } else {
    $('.finales, #Importe1').text(formatNum(carto));
    $('.finalcarrito').val(carto);
    final = carto;
  }

  final > 0 ?
    $('#add01').prop({
      type: 'submit',
      disabled: false,
    }) :
    $('#add01').prop({
      type: 'button',
      disabled: true,
    });
};

const formatRuc = (rucInput) => {
  let ruc = rucInput.value.trim();
  let formattedRuc = ruc;

  if (ruc.length >= 6 && ruc.length <= 10) {
    if (ruc.includes('-')) {
      ruc = ruc.replace('-', '');
    }
    formattedRuc = ruc.slice(0, -1) + '-' + ruc.slice(-1);
  }

  rucInput.value = formattedRuc;
};

function formatPrice(price) {
  return new Intl.NumberFormat('es-PY', { style: 'decimal' }).format(price);
}


$(function() {


	$('div.slimScrollDiv > ul.menu > li > a').click(function (e) {

        stock($(this).data('id'));
		e.preventDefault();
	});

	$('ul.dropdown-menu > li.footer > a').click(function (e) {
        stock($(this).data('id'));
		e.preventDefault();
	});
	function stock(id) {
		$.ajax({
			url: 'Producto/uriswg',
			type: 'POST',
			dataType: 'JSON',
			data: {val: id},
		})
		.done(function() {
			$("#_stc").click();
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");

		});
		
	}
});


