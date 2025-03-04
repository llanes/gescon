<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cobro_Model extends CI_Model {
	var $column = array(
		'Descripcion',
		'Monto',
		'Num_Factura_Venta',
		'Fecha',);
	var $order = array('Fecha' => 'acs');
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
		$this->load->database();
	}

  const SELECT = 'sum(Importe) as total1,  cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente  as idcce,
 fv.idFactura_Venta,fv.Num_Factura_Venta,fv.Tipo_Venta,fv.Ticket,fv.Estado,fv.Insert,fv.Concepto,fv.Monto_Total as Monto,fv.Fecha_expedicion as Fecha,fv.Hora,Cliente.Nombres,Cliente.Ruc,ccc.Num_Recibo,Razon_Social,
  cc.Caja_idCaja,cc.Factura_Venta_idFactura_Venta,cc.idCaja_Cobros,cc.Estado,
  cc.Movimientos_idMovimientos as idm, Fecha_Pago,
  cc.Tarjeta_idTarjeta as idtj,
  ';
  const SELECTT = '
     cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente,cc.Factura_Venta_idFactura_Venta,Concepto
  ';

    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const COBRO = 'Caja_Cobros cc';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'idCaja_Cobros';
	private function _get_datatables_query()
	{
          
          $this->db->select(self::SELECT);
 
          $this->db->join('Factura_Venta fv', 'cc.Factura_Venta_idFactura_Venta  = fv.idFactura_Venta', 'left');
          $this->db->join('Cliente', 'fv.Cliente_idCliente = Cliente.idCliente', 'left');
          $this->db->join('Cuenta_Corriente_Cliente ccc', 'cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = ccc.idCuenta_Corriente_Cliente', 'left');
          $this->db->join('Proveedor', 'ccc.Proveedor_idProveedor = Proveedor.idProveedor', 'left');
          $this->db->where('fv.Estado !=4');
          $this->db->or_where('ccc.Estado !=4');
          $this->db->group_by(self::SELECTT);
          if ($this->session->userdata('idUsuario')!= 1) {
          $this->db->where('fv.Caja_idCaja', $this->session->userdata('idCaja'));
          }


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

	function get_Cobro()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(self::COBRO);
		return $query->result();
	}

  function getCobro()
  {
          $this->db->select(self::SELECT);
          $this->db->join('Cuenta_Corriente_Cliente ccc', 'cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = ccc.idCuenta_Corriente_Cliente', 'left');
          $this->db->join('Proveedor', 'ccc.Proveedor_idProveedor = Proveedor.idProveedor', 'left');
          $this->db->join('Factura_Venta fv', 'cc.Factura_Venta_idFactura_Venta  = fv.idFactura_Venta', 'left');
          $this->db->join('Cliente', 'fv.Cliente_idCliente = Cliente.idCliente', 'left');
          $this->db->where('fv.Estado !=4');
          $this->db->or_where('ccc.Estado !=4');
          $this->db->group_by(self::SELECTT);
          if ($this->session->userdata('idUsuario')!= 1) {
          $this->db->where('fv.Caja_idCaja', $this->session->userdata('idCaja'));
          }

    $query = $this->db->get(self::COBRO);
    return $query->result();
  }

	function count_filtro()
	{
		$this->_get_datatables_query();
		$query = $this->db->get(self::COBRO);
		return $query->num_rows();
	}

	public function count_todas()
	{
          $this->db->select(self::SELECT);
          $this->db->join('Cuenta_Corriente_Cliente ccc', 'cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = ccc.idCuenta_Corriente_Cliente', 'left');
          $this->db->join('Proveedor', 'ccc.Proveedor_idProveedor = Proveedor.idProveedor', 'left');
          $this->db->join('Factura_Venta fv', 'cc.Factura_Venta_idFactura_Venta  = fv.idFactura_Venta', 'left');
          $this->db->join('Cliente', 'fv.Cliente_idCliente = Cliente.idCliente', 'left');
          $this->db->where('fv.Estado !=4');
          $this->db->or_where('ccc.Estado !=4');
          $this->db->group_by(self::SELECTT);
          if ($this->session->userdata('idUsuario')!= 1) {
          $this->db->where('fv.Caja_idCaja', $this->session->userdata('idCaja'));
          }
		$this->db->get(self::COBRO);
		return $this->db->count_all_results();
	}

  public function get_by_id($id){ 
// $this->output->enable_profiler(TRUE);
        $this->db->select('Tipo_Venta,Monto,Factura_Venta_idFactura_Venta,Descripcion,Fecha,Num_Factura_Venta,Ticket');
        $this->db->from(self::COBRO);
        $this->db->join('Factura_Venta ', 'cc.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta', 'inner');
        $this->db->where('cc.Factura_Venta_idFactura_Venta',$id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
           return $query->result();
        }else{
        $this->db->select('*');
        $this->db->from(self::COBRO);
        $this->db->join('Cuenta_Corriente_Cliente ccc', 'cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = ccc.idCuenta_Corriente_Cliente', 'left');
        $this->db->where('cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente',$id);
         $query = $this->db->get();
        return $query->result();
        }

  }
  public function get_by_id2($id){ 
    $this->output->enable_profiler(TRUE);

  }
    public function ultimaCaja()
    {
            $this->db->select_max('idCaja');
            $this->db->where("Cierre = 0");
            $this->db->where('Usuario_idUsuario',$this->session->userdata('idUsuario'));
            $query = $this->db->get('Caja');
            $row = $query->row();
            return $row->idCaja;
    }

      public function cobros($PlandeCuenta,$Totalparclal,$ultimaCaja ,$Cliente,$Descripcion ,$tipoComprovante,$comprobante ,$Ticke)
    {   $hora = strftime( "%H:%M", time() );
    // $this->output->enable_profiler(TRUE);
        $this->db->trans_begin();
        if ( $tipoComprovante == 1) {
          $tipo = $comprobante;
          $Ticket = '';
        }else{
          $tipo = '';
          $Ticket = $Ticke ;
        }
                if ($Cliente !='') {
                  $idCliente = $Cliente;
                }else{
                  $idCliente = 1;

                }
                    $object                 = array(
                     'Fecha_expedicion'  =>  date("Y-m-d"),
                     'Hora'              => strftime( "%H:%M", time() ),
                     'Concepto'          => $Descripcion,
                     'Odservaciones'     => $Descripcion,
                     'Estado'            => 0,
                     'Num_factura_Venta' => $tipo,
                     'Ticket'            => $Ticket,
                     'Tipo_Venta'        => $tipoComprovante,
                     'Monto_Total'       => $Totalparclal,
                     'Monto_Total_Iva'   => round($Totalparclal / 11) ,
                     'Contado_Credito'   => 1,
                     'Insert'            => 2,
                     'Usuario_idUsuario' => $this->session->userdata('idUsuario'),
                     'Cliente_idCliente' => $idCliente,
                     'Caja_idCaja'       => $ultimaCaja,

                   );
                   $this->db->insert('Factura_Venta', $object);
                   $id = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE)
        {
                $this->db->trans_rollback();
        }
        else
        {
                $this->db->trans_commit();
                return $id ;
        }

    }

    public function cobro(
    		$Totalparclal,
            $parcial1,
            $moneda,

            $parcial2,
            $numcheque,
            $fecha_pago,
            $Cliente,$Descripcion ,$tipoComprovante,$comprobante ,$Ticket,$PlandeCuenta,

            $parcial3,
            $efectivoTarjeta,
            $Tarjeta )
    {
           $this->db->trans_begin();
           $ultimaCaja = $this->ultimaCaja();
           $idventa = $this->cobros($PlandeCuenta,$Totalparclal,$ultimaCaja ,$Cliente,$Descripcion ,$tipoComprovante,$comprobante ,$Ticket);
                  $data                         = array(
                  'Fecha'                       => date("Y-m-d"),
                  'Hora'                        => strftime( "%H:%M", time() ),
                  'Factura_Venta_idFactura_Venta'                       => $idventa,
                  'Caja_idCaja'                 => $this->session->userdata('idCaja'),
                  );
                  $this->db->insert('Acientos', $data);
                  $idAcientos = $this->db->insert_id();
           if (!empty($parcial1)) {
                  foreach ($moneda as $key => $value) {
                         $data                                                 = array(
                         'Descripcion'                                         =>  'Cobro '.$Descripcion.' '.number_format($value['EF'],0,'.',',').' '.$value['signo'] ,
                         'Monto'                                               => $value['cambiado'],
                         'Fecha'                                               => date("Y-m-d"),
                         'Hora'                                                => strftime( "%H:%M", time() ),
                         'Caja_idCaja'                                         => $ultimaCaja,
                         'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => null,
                         'Devoluciones_idDevoluciones'                         => null,
                         'Factura_Venta_idFactura_Venta'                       => $idventa,
                         'Moneda_idMoneda'                                     =>  $value['Moneda'], 
                         );
                     $this->db->insert('Caja_Cobros', $data);
                     $id = $this->db->insert_id();
                  }
                  $this->add_aciento_plan( $idAcientos,$tipoComprovante,$PlandeCuenta,'2',$parcial1);

           }

            if (!empty($parcial2)) {
                $data                                                 = array(
                'NumeroCheque'                                        => $numcheque,
                'Control'                                             => 0,
                'ConceptoEntrada'                                     => 'Cobro '.$Descripcion,
                'FechaExpedicion'                                     => date("Y-m-d"),
                'Hora'                                                => strftime( "%H:%M", time() ),
                'Importe'                                             => $parcial2,
                'Pagos'                                               => null,
                'Cobros'                                              => 1,
                'Activo_Inactivo'                                     => 1,
                'FechaPago'                                           => $fecha_pago ,
                'Entrada_Salida'                                     => 'Entrada',
                'Gestor_Bancos_idGestor_Bancos'                       => null,
                'Proveedor_idProveedor'                               => null,
                'Cliente_idCliente'                                   => $Cliente,
                'Usuario_idUsuario'                                   => $this->session->userdata('idUsuario'),
                'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
                'Caja_idCaja'                                         => $ultimaCaja,
                );
                $this->db->insert('Movimientos', $data);
                $idm = $this->db->insert_id();
               $_data                                                 = array(
               'Descripcion'                                         => 'Cobro '.$Descripcion.' en Cheque Nº' .$numcheque,
               'Monto'                                               => $parcial2,
               'Fecha'                                               => date("Y-m-d"),
               'Hora'                                                => strftime( "%H:%M", time() ),
               'Caja_idCaja'                                         => $ultimaCaja,
               'Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente' => null,
               'Devoluciones_idDevoluciones'                        => null,
               'Factura_Venta_idFactura_Venta'                      => $idventa,
                'Movimientos_idMovimientos'                            =>$idm
               );
           $this->db->insert('Caja_Cobros', $_data);
           $this->add_aciento_plan( $idAcientos,$tipoComprovante,$PlandeCuenta,'4',$parcial2);
           }

            if (!empty($parcial3)) {
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
                'MontodeTarjeta' => $parcial3,
                'FechaTarjeta'   => date("Y-m-d")
                );
                $this->db->insert('Tarjeta', $data);
                $idt = $this->db->insert_id();
               $_data                                                 = array(
               'Descripcion'                                         =>  'Cobro '.$Descripcion.' con '.$Tarje,
               'Monto'                                               => $parcial3,
               'Fecha'                                               => date("Y-m-d"),
               'Hora'                                                => strftime( "%H:%M", time() ),
               'Caja_idCaja'                                         => $ultimaCaja,
               'Factura_Venta_idFactura_Venta'                      => $idventa,
               'Tarjeta_idTarjeta'                            =>$idt
               );
           $this->db->insert('Caja_Cobros', $_data);
           $this->add_aciento_plan( $idAcientos,$tipoComprovante,$PlandeCuenta,'304',$parcial3);
           }
           $this->add_flou( $idAcientos,$Totalparclal,$PlandeCuenta,$tipoComprovante);
           if ($this->db->trans_status() === FALSE)
           {
                   $this->db->trans_rollback();
           }
           else
           {
                   $this->db->trans_commit();
                   return  $idventa ;
           }

    }

    /**
     * Inserts new data into database
     *
     * @param Array $data Associative array with field_name=>value pattern to be inserted into database
     * @return mixed Inserted row ID, or false if error occured
     */
    public function insert(Array $data) {
        if ($this->db->insert(self::TABLE_NAME, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */
    public function update(Array $data, $where = array()) {
            if (!is_array($where)) {
                $where = array(self::PRI_INDEX => $where);
            }
        $this->db->update(self::TABLE_NAME, $data, $where);
        return $this->db->affected_rows();
    }

    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function delete_by_id($where = array()) {
        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        $this->db->delete('Caja_Cobros', $where);
        return $this->db->affected_rows();
    }

    public function add_aciento_plan($idAcientos,$tipoComprovante,$PlandeCuenta,$value='',$parcial)
    {
          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => $value,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => '(Ac +)',
          'HaberDetalle'                => NULL,
          'Debe'                        => $parcial,
          'Haber'                       => NULL,
          'Descuento_Debe'              => NULL,
          'Descuento_Haber'             => NULL,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);
    }

    public function add_flou( $idAcientos,$Totalparclal,$PlandeCuenta,$tipoComprovante)
    {
        if ($tipoComprovante > 0 ) {
              $val = ($Totalparclal / 11);
              $data                         = array(
              'PlandeCuenta_idPlandeCuenta' => $PlandeCuenta,
              'Acientos_idAcientos'         => $idAcientos,
              'DebeDetalle'                 => Null,
              'HaberDetalle'                => '(Ac -)',
              'Debe'                        => NULL,
              'Haber'                       => $Totalparclal - $val ,
              'Descuento_Debe'              => NULL,
              'Descuento_Haber'             => NULL,
              );
              $this->db->insert('PlandeCuenta_has_Acientos', $data);

               $data                         = array(
               'PlandeCuenta_idPlandeCuenta' => '480',
               'Acientos_idAcientos'         => $idAcientos,
               'DebeDetalle'                 => null,
               'HaberDetalle'                => '(Ac -)',
               'Debe'                        => null,
               'Haber'                       => $val,
               'Descuento_Debe'              => NULL,
               'Descuento_Haber'             => NULL,

               );
               $this->db->insert('PlandeCuenta_has_Acientos', $data);
      }else{
              $data                         = array(
              'PlandeCuenta_idPlandeCuenta' => $PlandeCuenta,
              'Acientos_idAcientos'         => $idAcientos,
              'DebeDetalle'                 => Null,
              'HaberDetalle'                => '(Ac -)',
              'Debe'                        => NULL,
              'Haber'                       => $Totalparclal,
              'Descuento_Debe'              => NULL,
              'Descuento_Haber'             => NULL,
              );
              $this->db->insert('PlandeCuenta_has_Acientos', $data);
      }
          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => NULL,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => '<p class="text-danger">Cobros</p>',
          'HaberDetalle'                => NULL,
          'Debe'                        => NULL,
          'Haber'                       => NULL,
          'Descuento_Debe'              => NULL,
          'Descuento_Haber'             => NULL,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);
    }
}


/* End of file Cobro_Model.php */
/* Location: ./application/models/Cobro_Model.php */ ?>