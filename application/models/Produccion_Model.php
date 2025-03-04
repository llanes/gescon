<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Produccion_Model extends CI_Model {
	var $column = array('idDetale_Produccion','Cantidad_A','Cantidad_A','Nombre','Fecha_P');

	var $order = array('idDetale_Produccion' => 'desc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
		$this->load->database();
	}
	/**
	 * 
	 */
    //const EMPLEADO  = 'Empleado';
    const PRODUCTO  = 'Detale_Produccion dp';
    const INGRE = 'Ingredientes';
    const ACCESO  = 'Usuario';
    const ID  = 'idProducto';
    const PROVEEDOR  = 'Proveedor';
    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
    
    
	private function _get_datatables_query()
	{

		$this->db->from(self::PRODUCTO);
		$this->db->join('Producto', 'dp.Producto_idProducto = Producto.idProducto', 'inner');
    $this->db->join(self::PROVEEDOR, 'dp.Proveedor_idProveedor = '.self::PROVEEDOR.'.idProveedor', 'left');
		$this->db->where('Produccion =2 ');
		$this->db->order_by("idProducto", "asc");

		$i = 0;

		foreach ($this->column as $item)
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;

		}
    

		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_Producto($id ='')
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtro($id ='')
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_todas($id ='')
	{
		$this->db->from(self::PRODUCTO);
		$this->db->join('Producto', 'dp.Producto_idProducto = Producto.idProducto', 'inner');
    $this->db->join(self::PROVEEDOR, 'dp.Proveedor_idProveedor = '.self::PROVEEDOR.'.idProveedor', 'left');
		$this->db->where('Produccion =2 ');
		return $this->db->count_all_results();
	}

	  //   public function list_productos($where = NULL,$res= null,$id = NULL) {
    //   // $this->output->enable_profiler(TRUE);
    //     $this->db->select('idProducto,Marca,Nombre,Codigo,Precio_Costo,Unidad,Medida,Img,Iva,Cantidad_A');
    //     $this->db->from('Producto');
    //     $this->db->join('Marca', 'Producto.Marca_idMarca = Marca.idMarca', 'left');
    //     if ($res !== NULL) {
    //        $this->db->where($res);
    //     }
    //     $this->db->where($where);
    //     $query = $this->db->get();

    //        if ($query->num_rows() > 0) {
    //           $options='<option value=""></option>';
    //           foreach ($query->result() as $key => $value) {
    //             $precio = $this->security->xss_clean( intval(preg_replace('/[^0-9]+/', '',$value->Precio_Costo), 10));
    //             $unidad = $this->security->xss_clean( intval(preg_replace('/[^0-9]+/', '',$value->Unidad), 10));
    //             $nombre = url_title(convert_accented_characters($value->Nombre),'_',TRUE);
    //             $marca = url_title(convert_accented_characters($value->Marca),'_',TRUE);
    //             $options.='<option id="'.$value->idProducto.'"
    //             value='.$value->idProducto.','. $nombre.','.$precio.','.$value->Img.','.$value->Iva.','.$value->Cantidad_A.','.$unidad.','.$value->Medida.','.$marca.'>
    //             Codigo: ['.$value->Codigo.'] &nbsp;
    //             Nombre: '.$value->Nombre.'&nbsp;
    //              ['.intval(preg_replace('/[^0-9]+/', '', $value->Unidad), 10).': '.$value->Medida.']
    //             </option>';
    //           }
    //        return $options; 
    //        }

    // }

	/**
	 * [insert description]
	 * @param  Array  $data [description]
	 * @return [type]       [description]
	 */
public function insert($proveedor, $idProduct, $recetas, $cantidad, $fecha, $Estado_produccion, $responsible, $tiempo, $lote, $lesiva) {
    $object = array(
        'Fecha_Produccion'      => $fecha,
        'CantidadProduccion'    => $cantidad,
        'Producto_idProducto'   => $idProduct,
        'Estado_d'              => $Estado_produccion == 2 ? 1 : null,
        'Monto_Total'           => $this->cart->total(),
        'Proveedor_idProveedor' => $proveedor,
        'Recetas_idRecetas'     => $recetas,
        'Numero_idNumero'       => $responsible,
        'responsable'           => $tiempo,
        'tiempo_produccion'     => $lote,
    );
     $this->db->insert('Detale_Produccion', $object);
    $iddp = $this->db->insert_id();
     if ($Estado_produccion == 2) {
        $this->db->set('Cantidad_A', 'Cantidad_A+'. $cantidad, FALSE);
        $this->db->where('idProducto', $idProduct);
        $this->db->update('Producto');
    }
     $cartContents = $this->cart->contents();
     $data = array();
    foreach ($cartContents as $items) {
        $data[] = array(
            'Producto_idProducto' => $items['id'],
            'Cantidad'            => $items['qty'],
            'Precio'              => str_replace($this->config->item('caracteres'),"", $items['price']),
            'idProduccion'        => $iddp,
            'Fecha_Ingrediente'   => $fecha
        );
        $this->db->set('Cantidad_A', 'Cantidad_A-'.$items['qty'], FALSE);
        $this->db->where('idProducto', $items['id']);
        $this->db->update('Producto');

    }
     if (!empty($data)) {
        $this->db->insert_batch('Ingredientes', $data);
    }

     $this->acientos($iddp, $this->cart->total(), $Estado_produccion == 2 ? 1 : '', $proveedor);
     if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
    } else {
        return $cartContents;
    }
}

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function cantidad ($id,$id2)
    {
    	$this->db->select('CantidadProduccion,idDetale_Produccion');
		$this->db->where('Fecha_Produccion', $id);
		$this->db->where('Producto_idProducto', $id2);
		$this->db->where('Estado_d !=1');
		$query = $this->db->get('Detale_Produccion');
		return $query->result();


    }

    public function compra($tipoComprovante,$tipo,$Ticket, $deudapagar,$idproveedore,$vueltototal)
    {
        $object                 = array(
        'Fecha_expedicion'      => date("Y-m-d"),
        'Hora'                  => strftime( "%H:%M", time() ),
        'Concepto'              => 'Compras Mercaderia Producido',
        'Observaciones'         => 'Compra de mercaderias de productos producido por el Proveedor',
        'Estado'                => '0',
        'Num_factura_Compra'    => $tipo,
        'Ticket'                => $Ticket,
        'Tipo_Compra'           => $tipoComprovante,
        'Monto_Total'           => $deudapagar,
        'Monto_Total_Iva'      =>  $deudapagar/11,
        'Contado_Credito'       => '0',
        'Caja_idCaja'           => $this->session->userdata('idCaja'),
        'Usuario_idUsuario'     => $this->session->userdata('idUsuario'),
        'Proveedor_idProveedor' => $idproveedore,
        'Insert'                => '1',
        'Vuelto'                => $vueltototal,
        );
        $this->db->insert('Factura_Compra', $object);
        return $this->db->insert_id();
    }

    public function close_in_produc($tipoComprovante,$Montoapagar,$idproveedore ,$efectivo ,
            $pagoparcial1,
            $deudapagar,
            $vueltototal,
            $moneda,

            $pagoparcial2,
            $numcheque,
            $fecha_pago,
            $cuenta_bancaria ,
            $Acheque_tercero,$Acheque,


            $pagoparcial3,
            $efectivoTarjeta,
            $Tarjeta,$idProducto,$idDetalle,

            $pagoparcial4,
            $matriscuanta,$matris,$cantidadProduc)
    {
      $this->db->trans_begin();
       if ($tipoComprovante == 0) {
          $tipo =  '';
          $Ticket =  Ticket();
        }else
        {
          $tipo =  comprobante();
          $Ticket = '';
        }
        $idcomprar =  $this->compra($tipoComprovante,$tipo,$Ticket, $deudapagar,$idproveedore,$vueltototal );
        $data                           = array(
        'Fecha'                         => date("Y-m-d"),
        'Hora'                          => strftime( "%H:%M", time() ),
        'Factura_Compra_idFactura_Compra' => $idcomprar,
        'Caja_idCaja'                   => $this->session->userdata('idCaja'),
        );
        $this->db->insert('Acientos', $data);
        $idAcientos = $this->db->insert_id();
        if ($tipoComprovante == 0) {
        $this->add_aciento_plan( $idAcientos,'58',$deudapagar,'(Ac +)');
        }else{
        $lesiva = $deudapagar/11;
        $this->add_aciento_plan( $idAcientos,'58',$deudapagar-$lesiva ,'(Ac +)'); 
        $this->add_aciento_plan( $idAcientos,'479',$lesiva ,'(Ac +)'); 

        }

        $this->db->set('Cantidad_A', 'Cantidad_A+'.$cantidadProduc, FALSE);
        $this->db->where('idProducto', $idProducto);
        $this->db->update('Producto');
        $this->db->set('CantidadProduccion', 'CantidadProduccion+'.$cantidadProduc, FALSE);
        $this->db->set('Estado_d', '1', FALSE);
        $this->db->where('idDetale_Produccion',  $idDetalle,false );
        $this->db->update('Detale_Produccion');

        if (!empty($pagoparcial1) ) {
                  foreach ($moneda as $key => $value) {
                         $data                                                 = array(
                         'Descripcion'                                         => 'Pago por compra de Mercaderia Producido '.$value['EF'].' '.$value['signo'] ,
                         'Monto'                                               => $value['cambiado'],
                         'Fecha'                                               => date("Y-m-d"),
                         'Hora'                                                => strftime( "%H:%M", time() ),
                         'Caja_idCaja'                                         => $this->session->userdata('idCaja'),
                         'Empleado_idEmpleado' => null,
                         'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
                         'Devoluciones_idDevoluciones'                        => null,
                         'Factura_Compra_idFactura_Compra'                      => $idcomprar,
                         );
                     $this->db->insert('Caja_Pagos', $data);
                     $id = $this->db->insert_id();
                  }
             $this->add_aciento_debe( $idAcientos,'2',$pagoparcial1,'(Ac -)');
        }
        if (!empty($pagoparcial2)) {
              if ($numcheque > 0 && $efectivo > 0) {
                if (!empty( $cuenta_bancaria)) {
                $data                                                 = array(
                'NumeroCheque'                                        => $numcheque,
                'Control'                                             => 0,
                'ConceptoSalida'                                      => 'Pago por compra de Mercaderia Producido',
                'FechaExpedicion'                                     => date("Y-m-d"),
                'Hora'                                                => strftime( "%H:%M", time() ),
                'Importe'                                             => $efectivo,
                'Pagos'                                               => 1,
                'Cobros'                                              => null,
                'Activo_Inactivo'                                     => 2,
                'FechaPago'                                           => $fecha_pago ,
                'Entrada_Salida'                                     => 'Salida',
                'Gestor_Bancos_idGestor_Bancos'                       => $cuenta_bancaria,
                'Proveedor_idProveedor'                               => $idproveedore,
                'Cliente_idCliente'                                   => null,
                'Usuario_idUsuario'                                   => $this->session->userdata('idUsuario'),
                'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
                );
                $this->db->insert('Movimientos', $data);
                $idm = $this->db->insert_id();
                $this->db->set('MontoActivo', 'MontoActivo-'.$efectivo  , FALSE);
                $this->db->where('idGestor_Bancos',$cuenta_bancaria);
                $this->db->update('Gestor_Bancos');
                $this->add_aciento_debe( $idAcientos,'4',$efectivo);
                }else{
                 $data                                                 = array(
                'NumeroCheque'                                        => $numcheque,
                'Control'                                             => 0,
                'ConceptoSalida'                                      => 'Pago por compra de Mercaderia Producido',
                'FechaExpedicion'                                     => date("Y-m-d"),
                'Hora'                                                => strftime( "%H:%M", time() ),
                'Importe'                                             => $efectivo,
                'Pagos'                                               => 1,
                'Cobros'                                              => null,
                'Activo_Inactivo'                                     => 2,
                'FechaPago'                                           => $fecha_pago ,
                'Entrada_Salida'                                     => 'Salida',
                'Proveedor_idProveedor'                               => $idproveedore,
                'Cliente_idCliente'                                   => null,
                'Usuario_idUsuario'                                   => $this->session->userdata('idUsuario'),
                'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
                );
                $this->db->insert('Movimientos', $data);
                $idm = $this->db->insert_id();
              $this->add_aciento_debe( $idAcientos,'4',$efectivo,'(Ac -)');
                }

               $_data                                                 = array(
               'Descripcion'                                         => 'Pago Compra con Cheque N° ' .$numcheque,
               'Monto'                                               => $efectivo,
               'Fecha'                                               => date("Y-m-d"),
               'Hora'                                                => strftime( "%H:%M", time() ),
               'Caja_idCaja'                                         => $this->session->userdata('idCaja'),
               'Empleado_idEmpleado' => null,
               'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa'  => null,
               'Devoluciones_idDevoluciones'                          => null,
               'Factura_Compra_idFactura_Compra'                      => $idcomprar,
               'Movimientos_idMovimientos'                            =>$idm
               );
                  $this->db->insert('Caja_Pagos', $_data);
              if (!empty($Acheque_tercero)) {
                $this->output->enable_profiler(TRUE);
                $tyuo = "'Pago por compra de Mercaderia Producido'";
                    $seleccionados = explode(',',$Acheque_tercero);
                      $selecc = explode(',',$Acheque);
                      $parcialtodo = 0;
                    for ($i=0;$i<count($seleccionados);$i++)
                    {
                            for ($j=0;$j<count($selecc);$j++)
                            {
                              $this->db->set('Activo_Inactivo', '2', FALSE);
                              $this->db->set('Pagos', '1', FALSE);
                              $this->db->set('ConceptoSalida',  $tyuo );
                              $this->db->where('idMovimientos', $seleccionados[$i], FALSE);
                              $this->db->update('Movimientos');
                                 $data                                                 = array(
                                 'Descripcion'                                         => 'Pago Compra con Cheque Tercero',
                                 'Monto'                                               => $selecc[$j],
                                 'Fecha'                                               => date("Y-m-d"),
                                 'Hora'                                                => strftime( "%H:%M", time() ),
                                 'Caja_idCaja'                                         => $this->session->userdata('idCaja'),
                                 'Empleado_idEmpleado' => null,
                                 'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
                                 'Devoluciones_idDevoluciones'                        => null,
                                 'Factura_Compra_idFactura_Compra'                      => $idcomprar,
                                 'Movimientos_idMovimientos'                            =>$seleccionados[$i]
                                 );
                             $this->db->insert('Caja_Pagos', $data);
                              $parcialtodo +=$selecc[$j];
                            } 
                    } 
                  $this->add_aciento_debe( $idAcientos,$tipoComprovante,'33',$parcialtodo,'(Ac -)');
              }
        }
    }
        if (!empty($pagoparcial3) ) {
              switch ($Tarjeta) {
                case 1:
                 $Tarje = "'Tarjetas de Crédito'";
                  break;
                case 2:
                 $Tarje = "'Tarjetas de Débito'";
                  break;
              }
                $data                                                 = array(
                'TipodeTarjeta'  => $Tarjeta,
                'MontodeTarjeta' => $pagoparcial3,
                'FechaTarjeta'   => date("Y-m-d")
                );
                $this->db->insert('Tarjeta', $data);
                $idt = $this->db->insert_id();
               $_data                                                 = array(
               'Descripcion'                                         => 'Pago Compra con '.$Tarje,
               'Monto'                                               => $pagoparcial3,
               'Fecha'                                               => date("Y-m-d"),
               'Hora'                                                => strftime( "%H:%M", time() ),
               'Caja_idCaja'                                         => $this->session->userdata('idCaja'),
               'Empleado_idEmpleado' => null,
               'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa'  => null,
               'Devoluciones_idDevoluciones'                          => null,
               'Factura_Compra_idFactura_Compra'                      => $idcomprar,
               'Tarjeta_idTarjeta'  => $idt,
               );
           $this->db->insert('Caja_Pagos', $_data);
           $this->add_aciento_plan( $idAcientos,$tipoComprovante,'304',$pagoparcial3,'(Ac -)');
        }

        if (!empty($pagoparcial4)) 
        {
          // $this->output->enable_profiler(TRUE);
              $seleccionados = explode(',',$matriscuanta);
              $matri = explode(',',$matris);
              $contador = count( $seleccionados);
              $res = 1;
          if ($pagoparcial4 > $deudapagar) {
              $this->add_aciento_debe( $idAcientos,'34', $deudapagar,'(Ac -)');
              for ($i=0;$i<count($seleccionados);$i++)
              {
                        if ( $res == $contador) {
                             $this->db->set('Estado', '0', FALSE);
                             $this->db->set('Importe', ($pagoparcial4 - $deudapagar), FALSE);
                             $this->db->where('idCuenta_Corriente_Cliente', $seleccionados[$i]);
                             $this->db->update('Cuenta_Corriente_Cliente');
                        }else{
                             $this->db->set('Estado', '4', FALSE);
                             $this->db->where('idCuenta_Corriente_Cliente', $seleccionados[$i]);
                             $this->db->update('Cuenta_Corriente_Cliente');
                        }
                      $res++;
              } 
          }
           else
          {
            $this->add_aciento_debe( $idAcientos,'34',$pagoparcial4,'(Ac -)');
               for ($i=0;$i<count($seleccionados);$i++)
              {
                             $this->db->set('Estado', '1', FALSE);
                             $this->db->where('idCuenta_Corriente_Cliente', $seleccionados[$i]);
                             $this->db->update('Cuenta_Corriente_Cliente');
              } 
          }
        }else{

        }
        if (!empty($vueltototal)) {
          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => '483',
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => '(Ac +)',
          'HaberDetalle'                => null,
          'Debe'                        => $vueltototal,
          'Haber'                       => null,
          );
           $this->db->insert('PlandeCuenta_has_Acientos', $data);
           $data                                                 = array(
           'Descripcion'                                         =>  'Diferencia Vuelto',
           'Monto'                                               => $vueltototal,
           'Fecha'                                               => date("Y-m-d"),
           'Hora'                                                => strftime( "%H:%M", time() ),
           'Caja_idCaja'                                         => $this->session->userdata('idCaja'),

           );
               $this->db->insert('Caja_Cobros', $data);
           }

          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => NULL,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => '<p class="text-danger">Ingreso de Mercaderia de Producto Terminado</p>',
          'HaberDetalle'                => NULL,
          'Debe'                        => NULL,
          'Haber'                       => NULL,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);

      if ($this->db->trans_status() === FALSE)
      {
              $this->db->trans_rollback();
      }
      else
      {
              $this->db->trans_commit();
      }
    }

    function acientos($idProduct,$monto,$id,$proveedor)
    {
    if (!empty($id)) {
                      $data                         = array(
                  'Fecha'                       => date("Y-m-d"),
                  'Hora'                        => strftime( "%H:%M", time() ),
                  'Diferencia'                  => $idProduct,
                  'Caja_idCaja'                 => $this->session->userdata('idCaja'),
                  );
                  $this->db->insert('Acientos', $data);
                  $idAcientos = $this->db->insert_id();
                  $this->add_aciento_plan( $idAcientos,'58',$monto,'(Ac +)');
                  $this->add_aciento_debe( $idAcientos,'58',$monto,'(Ac -)');
					$data                         = array(
					'PlandeCuenta_idPlandeCuenta' => NULL,
					'Acientos_idAcientos'         => $idAcientos,
					'DebeDetalle'                 => '<p class="text-danger">Producto Producido</p>',
					'HaberDetalle'                => NULL,
					'Debe'                        => NULL,
					'Haber'                       => NULL,
					);
					$this->db->insert('PlandeCuenta_has_Acientos', $data);
    }else{
    	          $data                         = array(
                  'Fecha'                       => date("Y-m-d"),
                  'Hora'                        => strftime( "%H:%M", time() ),
                  'Diferencia'                  => $idProduct,
                  'Caja_idCaja'                 => $this->session->userdata('idCaja'),
                  );
                  $this->db->insert('Acientos', $data);
                  $idAcientos = $this->db->insert_id();
                  if ($proveedor>1) {
                  $this->add_aciento_plan( $idAcientos,'34',$monto,'(Ac +)');
                  $this->add_aciento_debe( $idAcientos,'58',$monto,'(Ac -)');
                  }else{
                  $this->add_aciento_plan( $idAcientos,'55',$monto,'(Ac +)');
                  $this->add_aciento_debe( $idAcientos,'54',$monto,'(Ac -)');
                  }
					$data                         = array(
					'PlandeCuenta_idPlandeCuenta' => NULL,
					'Acientos_idAcientos'         => $idAcientos,
					'DebeDetalle'                 => '<p class="text-danger">Producto en Produccion</p>',
					'HaberDetalle'                => NULL,
					'Debe'                        => NULL,
					'Haber'                       => NULL,
					);
					$this->db->insert('PlandeCuenta_has_Acientos', $data);
    }
    }


     function add_aciento_plan($idAcientos,$value='',$monto,$signo='')
    {
          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => $value,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => $signo,
          'HaberDetalle'                => NULL,
          'Debe'                        => $monto,
          'Haber'                       => NULL,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);
    }
     function add_aciento_debe($idAcientos,$value='',$monto,$signo='')
    {
          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => $value,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => null,
          'HaberDetalle'                =>  $signo,
          'Debe'                        => null,
          'Haber'                       => $monto,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);
    }

    public function detalle($where = NULL) {
        $this->db->select('Ingredientes.Cantidad,Ingredientes.Precio,Producto.Nombre,idIngredientes,Producto_idProducto,idProduccion');
        $this->db->from(self::INGRE);
        $this->db->join('Producto', 'Ingredientes.Producto_idProducto = Producto.idProducto', 'left');
        if ($where !== NULL) {
                $this->db->where($where);

        }
         $query = $this->db->get();
         return $query->result();
    }

    	public function get_by_id($id)
	{
		// $this->output->enable_profiler(TRUE);
        $this->db->from(self::INGRE);
        $this->db->join('Producto', 'Ingredientes.Producto_idProducto = Producto.idProducto', 'inner');
        $this->db->where('idProduccion', $id);
        $query = $this->db->get();
        $this->cart->destroy();
		if ($query->num_rows()>0) {

			 $item =0;
			foreach ($query->result() as $key => $value) {
				    $unidad = $this->security->xss_clean( intval(preg_replace('/[^0-9]+/', '',$value->Unidad), 10));
					$opciones =  array('Unidad Medida' => $unidad.'&nbsp;&nbsp;&nbsp;&nbsp;'.$this->mi_libreria->medida($value->Medida));
					$opcione =  array('Cantidad_max' => $value->Cantidad_A);
					$name = str_replace('_',' ', ucfirst(strtolower($value->Nombre)));
	                $optioniva =  array('iva' => $value->Iva);
					$data = array(
					'id'      => $value->Producto_idProducto,
					'qty'     => $value->Cantidad,
					'price'   => $value->Precio,
					'name'    => $value->Nombre,
					'options' => $opciones,
					'option' => $opcione,
					'option2' => $optioniva
					);
					$this->cart->insert($data);
				    $item++;
			}
			return $query->result();
		}
	}


   	/**
   	 * [update description]
   	 * @param  [type] $where [description]
   	 * @param  [type] $data  [description]
   	 * @return [type]        [description]
   	 */
   	public function update($where, $data)
	{
		
		$this->db->update('Producto', $data, $where);
		return $this->db->affected_rows();
	}
	
	/**
	 * [delete_by_id description]
	 * @param  [type] $idProducto [description]
	 * @return [type]             [description]
	 */
	public function delete_by_id($idProducto)
	{
		$this->db->where('Producto_idProducto', $idProducto);
		$this->db->delete(self::PRODUCTO_X_PRO);
		$this->db->where(self::ID, $idProducto);
		$this->db->delete(self::PRODUCTO);
	}

	public function ajax_update($proveedor,$idProduct,$Estado_produccion,$cantidad,$date,$newdate,$iddp) {
    				$this->db->trans_begin();
    	if ($Estado_produccion == 2) {

    				$this->db->select('*');
					$this->db->where('idProduccion',$iddp);
					$query = $this->db->get('Ingredientes');
					if ($query->num_rows()>0) {
						foreach ($query->result() as $key => $value) {
								$this->db->set('Cantidad_A', 'Cantidad_A+'. $value->Cantidad, FALSE);
								$this->db->where('idProducto', $value->Producto_idProducto);
								$this->db->update('Producto');
						}
						$this->db->where('idProduccion',$iddp);
						$this->db->delete('Ingredientes');
					}
					$this->db->set('Cantidad_A', 'Cantidad_A+'.$cantidad, FALSE);
					$this->db->set('Produccion',$Estado_produccion, FALSE);
					$this->db->set('id_Prove',$proveedor);
					$this->db->where('idProducto', $idProduct);
					$this->db->update('Producto');

					$this->db->set('Estado_d',1);
					$this->db->where('idDetale_Produccion', $iddp);
					$this->db->update('Detale_Produccion');
                    $i = 1;
                    foreach ($this->cart->contents() as $items) {
                            foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                            $iva =  $option_value;
                         }
							$data                             = array(
							'Producto_idProducto'              =>        $items['id'] , 
							'Cantidad'               =>  $items['qty'],
							'Precio'                           => str_replace($this->config->item('caracteres'),"",$items['price']),
							'idProduccion'		  => $iddp ,
							'Fecha_Ingrediente'   => $newdate
                         );
                        $this->db->insert('Ingredientes', $data);
                        $this->db->set('Cantidad_A', 'Cantidad_A-'.$items['qty'], FALSE);
                        $this->db->where('idProducto', $items['id']);
                        $this->db->update('Producto');
                     $i++;
                    }
    	}elseif ($Estado_produccion == 1) 
    	{
    				$this->db->select('*');
					$this->db->where('idProduccion',$iddp);
					$query = $this->db->get('Ingredientes');
					if ($query->num_rows()>0) {
						foreach ($query->result() as $key => $value) {
								$this->db->set('Cantidad_A', 'Cantidad_A+'. $value->Cantidad, FALSE);
								$this->db->where('idProducto', $value->Producto_idProducto);
								$this->db->update('Producto');
						}
						$this->db->where('idProduccion',$iddp);
						$this->db->delete('Ingredientes');
					}
					$this->db->set('Estado_d',null);
					$this->db->where('idDetale_Produccion', $iddp);
					$this->db->update('Detale_Produccion');

                    $i = 1;
                    foreach ($this->cart->contents() as $items) {
                            foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                                            $iva =  $option_value;
                         }
                         $data                             =  array(
                         'Producto_idProducto'             => $items['id'] , 
                         'Cantidad'                        => $items['qty'],
                         'Precio'                          => str_replace($this->config->item('caracteres'),"",$items['price']),
                         'idProduccion'		  => $iddp ,
                         'Fecha_Ingrediente'   =>$newdate
                         );
                        $this->db->insert('Ingredientes', $data);
                        $this->db->set('Cantidad_A', 'Cantidad_A-'.$items['qty'], FALSE);
                        $this->db->where('idProducto', $items['id']);
                        $this->db->update('Producto');
                     $i++;
                    }

    	}
    		$this->db->select('idAcientos');
    		$this->db->where('Diferencia', $iddp);
    		$query = $this->db->get('Acientos');
            $row = $query->row();
            $this->db->where('Acientos_idAcientos', $row->idAcientos);
            $this->db->delete('PlandeCuenta_has_Acientos');
            if ($Estado_produccion == 1) {
    		      $this->add_aciento_plan( $row->idAcientos,'55',$this->cart->total(),'(Ac +)');
                  $this->add_aciento_debe( $row->idAcientos,'58',$this->cart->total(),'(Ac -)');
                  					$data                         = array(
					'PlandeCuenta_idPlandeCuenta' => NULL,
					'Acientos_idAcientos'         => $row->idAcientos,
					'DebeDetalle'                 => '<p class="text-danger">Producto en Produccion</p>',
					'HaberDetalle'                => NULL,
					'Debe'                        => NULL,
					'Haber'                       => NULL,
					);
					$this->db->insert('PlandeCuenta_has_Acientos', $data);
            }else{
    		      $this->add_aciento_plan( $row->idAcientos,'58',$this->cart->total(),'(Ac +)');
                  $this->add_aciento_debe( $row->idAcientos,'58',$this->cart->total(),'(Ac -)');
                  					$data                         = array(
					'PlandeCuenta_idPlandeCuenta' => NULL,
					'Acientos_idAcientos'         => $row->idAcientos,
					'DebeDetalle'                 => '<p class="text-danger">Producto Producido</p>',
					'HaberDetalle'                => NULL,
					'Debe'                        => NULL,
					'Haber'                       => NULL,
					);
					$this->db->insert('PlandeCuenta_has_Acientos', $data);
            }


    	    if ($this->db->trans_status() === FALSE)
    		{
    		        $this->db->trans_rollback();
    		}
    		else
    		{
    		      return  $this->db->trans_commit();
    		}
    }

}

/* End of file Produccion_Model.php */
/* Location: ./application/models/Produccion_Model.php */