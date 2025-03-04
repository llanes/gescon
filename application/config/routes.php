<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = $route['Inicio'] = 'Home';;
////////////////////Caja//////////////////////////////
$route['Stock/(:num)'] = 'Producto/index_stock/$1';
$route['P_perdido'] = 'Producto_null';

$route['pagoDeuda/(:num)'] = 'DeudaEmpresa/pagoDeuda/$1';
$route['cobroDeuda/(:num)'] = 'DeudaCliente/cobroDeuda/$1';

////////////////////Caja//////////////////////////////
$route['O_Venta'] = 'Orden_venta';

$route['O_Comprar'] = 'Orden_compra';
      $route['select2remote'] = 'Orden_compra/select2remote';
      $route['O_Comprar'] = 'Orden_compra';

$route['Remision'] = 'Remisiones';
$route['updateStatud'] = 'Producto/updateStatud';
////////////////////Caja//////////////////////////////
$route['CA1'] = 'Caja';
$route['CA'] = 'CajaActiva';
$route['CA2'] = 'Caja/movimiento';
$route['CA3'] = 'Caja/historial';
$route['caja_list'] = 'Caja/caja_list';
//////////////////Compras///////////////////////////
$route['Deudae'] = 'Deuda_empresa';
$route['Comprar'] = 'Compra';

// $route['Anulados'] = 'Compra/compra_null';
$route['ComprasLista'] = 'Compra/Listar';
$route['ajax_add_pago'] = 'Compra/ajax_add_pago';
$route['ajax_venta'] = 'Venta/ajax_add_pago';


$route['CDevolucion'] = 'CDevoluciones';
/////////////////////ventas////////////////
$route['insercliente'] = 'Orden_venta/insercliente';


// $route['S4'] = 'Menu';
// //////////////////Venta///////////////////////////
$route['Deudac'] = 'Deuda_cliente';
$route['Vender'] = 'Venta';
$route['VentaLista'] = 'Venta/Listar';

$route['Anulado'] = 'Venta/venta_null';
$route['V_D'] = 'VDevoluciones';
//////////////////////////////////////////////////////
$route['Producciones'] = 'Produccion';
$route['PlanCuenta'] = 'Plan_de_Cuenta';
$route['Mov'] = 'MovimientoBanco';
$route['Bancos'] = 'Banco';

//////////////////////////////////////////////////////
$route['Inicio'] =  $route['Inicio'] = 'Home';;
$route['Ingresar'] = 'Login';
$route['Cerrar'] = 'Login/logout';
$route['Stock'] = 'Producto/index_stock';
$route['ajax_list_stock'] = 'Producto/ajax_list_stock';
$route['Cliente'] = 'Cliente';
//////////////////Seguridad///////////////////////////
$route['S1'] = 'Admin_User';
    $route['userajax_list'] = 'Admin_User/ajax_list';
    $route['verificarpass'] = 'Admin_User/verificarpass';
    $route['userajax_edit/(:num)'] = 'Admin_User/ajax_edit/$1';
    $route['userajax_add'] = 'Admin_User/ajax_add';
    $route['userajax_update'] = 'Admin_User/ajax_update';
    $route['userajax_delete/(:num)'] = 'Admin_User/ajax_delete/$1';




$route['S2'] = 'Empresa';

$route['S3'] = 'Permiso';
    $route['permiso_list'] = 'Permiso/ajax_list';
    $route['permiso_edit/(:num)'] = 'Permiso/ajax_edit/$1';
    $route['permiso_has/(:num)'] = 'Permiso/permiso_has/$1';
    $route['permiso_add'] = 'Permiso/ajax_add';
    $route['permiso_update'] = 'Permiso/ajax_update';
    $route['permiso_delete/(:num)'] = 'Permiso/ajax_delete/$1';

$route['S4'] = 'Menu';
//////////////////////////////////////////////////////
$route['Cobros'] = 'Cobro';
$route['CobroDC'] = 'DeudaCliente';
$route['Pagos'] = 'Pago';
$route['PagoDE'] = 'DeudaEmpresa';
//////////////////Seguridad///////////////////////////
//////////////////////////////////////////////////////
$route['Contabilidad'] = 'AcientoContable';
$route['Libro'] = 'Librodiario';
$route['Balance'] = 'Bgeneral';
//////////////////////////////////////////////////////
$route['compranull'] = 'Reporte_exel/compranull';
$route['Descuento'] = 'Descuentos';
//////////////////Seguridad///////////////////////////



//////////////////proveedor///////////////////////////
$route['select2remote'] = 'Proveedor/select2remote';


$route['caja/(:num)'] = 'Reportes/caja/$1';
$route['Cambios'] = 'Cambio';
$route['404_override'] = 'Error';
$route['translate_uri_dashes'] = FALSE;


// application/config/routes.php

$route['configuracion'] = 'Configuracion/index';
$route['configuracion/guardar_impresora'] = 'Configuracion/guardar_impresora';
$route['configuracion/editar_impresora/(:num)'] = 'Configuracion/editar_impresora/$1';
$route['configuracion/eliminar_impresora/(:num)'] = 'Configuracion/eliminar_impresora/$1';



$route['sifen/enviar_lote/(:any)'] = 'sifen/enviar_lote/$1';
$route['sifen/consultar_lote/(:any)/(:any)'] = 'sifen/consultar_lote/$1/$2';
$route['sifen/consultar_documento/(:any)/(:any)'] = 'sifen/consultar_documento/$1/$2';
