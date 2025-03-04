<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pago_Model extends CI_Model {
	var $column = array(
		'Descripcion',
		'Monto',
		'Num_factura_Compra',
		'Fecha',);
	var $order = array('Fecha' => 'acs');
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
		$this->load->database();
	}

	const SELECT = 'sum(Importe) as total1,  cc.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa  as idcce,
	fv.idFactura_Compra,fv.Num_factura_Compra,fv.Tipo_Compra,fv.Ticket,fv.Estado,fv.Insert,fv.Concepto,fv.Monto_Total as Monto,fv.Fecha_expedicion as Fecha,fv.Hora,
	em.Nombres,em.Apellidos,cce.Num_Recibo,
	cc.Caja_idCaja,cc.Factura_Compra_idFactura_Compra,cc.idCaja_Pagos,cc.Empleado_idEmpleado,cc.Estado,
  cc.Movimientos_idMovimientos as idm, Fecha_Pago,
  cc.Tarjeta_idTarjeta as idtj,
  Razon_Social,Monto_Total_Iva,Monto_Total,Fecha_expedicion
	';
  const SELECTT = '
     cc.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa,cc.Factura_Compra_idFactura_Compra
  ';

    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const PAGO = 'Caja_Pagos cc';

    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'idCaja_Pagos';
	private function _get_datatables_query()
	{
      $ultimo = $this->ultimaCaja();
        $this->db->select(self::SELECT);
        $this->db->join('Cuenta_Corriente_Empresa  cce', 'cc.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cce.idCuenta_Corriente_Empresa', 'left');
        $this->db->join('Factura_Compra  fv', 'cc.Factura_Compra_idFactura_Compra = fv.idFactura_Compra', 'left');
        $this->db->join('Proveedor', 'cce.Proveedor_idProveedor = Proveedor.idProveedor', 'left');
        $this->db->join('Empleado  em', 'cc.Empleado_idEmpleado = em.idEmpleado', 'left');
        $this->db->where('fv.Estado !=4');
        $this->db->or_where('cce.Estado !=4');
        $this->db->group_by(self::SELECTT);
        $this->db->order_by('cc.Factura_Compra_idFactura_Compra', 'desc');
        if ($this->session->userdata('idUsuario')!= 1) {
         $this->db->where('fv.Caja_idCaja', $ultimo);
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

	function get_Pago()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(self::PAGO);
		return $query->result();
	}

  public function getPago($id='')
  {
        $this->db->select(self::SELECT);
        $this->db->join('Cuenta_Corriente_Empresa  cce', 'cc.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cce.idCuenta_Corriente_Empresa', 'left');
        $this->db->join('Factura_Compra  fv', 'cc.Factura_Compra_idFactura_Compra = fv.idFactura_Compra', 'left');
        $this->db->join('Proveedor', 'cce.Proveedor_idProveedor = Proveedor.idProveedor', 'left');
        $this->db->join('Empleado  em', 'cc.Empleado_idEmpleado = em.idEmpleado', 'left');
        $this->db->where('fv.Estado !=4');
        $this->db->or_where('cce.Estado !=4');
        $this->db->group_by(self::SELECTT);
        $this->db->order_by('cc.Factura_Compra_idFactura_Compra', 'desc');
        if ($this->session->userdata('idUsuario')!= 1) {
         $this->db->where('fv.Caja_idCaja', $this->session->userdata('idCaja'));
          }
        if (!empty($id)) {
          $this->db->where('idFactura_Compra', $id);
          return $this->db->get(self::PAGO);
        }else{
              $query = $this->db->get(self::PAGO);
               return $query->result();
        }

  }

	function count_filtro()
	{
		$this->_get_datatables_query();
		$query = $this->db->get(self::PAGO);
		return $query->num_rows();
	}

	public function count_todas()
	{
      $ultimo = $this->ultimaCaja();
        $this->db->select(self::SELECT);
        $this->db->join('Factura_Compra  fv', 'cc.Factura_Compra_idFactura_Compra = fv.idFactura_Compra', 'left');
        $this->db->join('Cuenta_Corriente_Empresa  cce', 'cc.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cce.idCuenta_Corriente_Empresa', 'left');
        $this->db->join('Proveedor', 'cce.Proveedor_idProveedor = Proveedor.idProveedor', 'left');
        $this->db->join('Empleado  em', 'cc.Empleado_idEmpleado = em.idEmpleado', 'left');
        $this->db->where('fv.Estado !=4');
        $this->db->or_where('cce.Estado !=4');
        $this->db->group_by(self::SELECTT);
        $this->db->order_by('cc.Factura_Compra_idFactura_Compra', 'desc');
        if ($this->session->userdata('idUsuario')!= 1) {
         $this->db->where('fv.Caja_idCaja', $ultimo);
          }

		$this->db->get(self::PAGO);
		return $this->db->count_all_results();
	}

  public function get_by_id($letra,$Value){ 
    // $this->output->enable_profiler(TRUE);
        $this->db->select('ccc.Num_Recibo,ccc.Num_Cuotas,cc.Descripcion,cc.Monto,cc.Fecha,cc.Hora,cc.Caja_idCaja,cc.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa,cc.Factura_Compra_idFactura_Compra,cc.Tarjeta_idTarjeta,CONCAT("delete_(",cc.idCaja_Pagos,")") as id,cc.Estado');
        $this->db->from(self::PAGO);
        $this->db->join('Cuenta_Corriente_Empresa ccc', 'cc.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = ccc.idCuenta_Corriente_Empresa', 'left');
       switch ($letra) {
         case 'idf':
           $this->db->where('cc.Factura_Compra_idFactura_Compra', $Value);
           break;
         
         case 'idcc':
           $this->db->where('cc.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa', $Value);
           break;
       }
         $query = $this->db->get();
        return $query->result();
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

      public function cobros($PlandeCuenta,$Totalparclal,$ultimaCaja ,$Nombre_Tipo,$Descripcion ,$tipoComprovante,$comprobante )
    {   $hora = strftime( "%H:%M", time() );
        $this->db->trans_begin();
                if ($tipoComprovante == 0 ) {
                   $Num_factura_Compra = '';
                   $Ticket = $comprobante;
                }else {
                   $Num_factura_Compra = $comprobante;
                   $Ticket = '';
                }
                    $object                 = array(
                     'Fecha_expedicion'  =>  date("Y-m-d"),
                     'Hora'              => strftime( "%H:%M", time() ),
                     'Concepto'          => ''.$Nombre_Tipo,
                     'Observaciones'     => $Descripcion,
                     'Estado'            => 0,
                     'Num_factura_Compra' => $Num_factura_Compra,
                     'Ticket'            => $Ticket,
                     'Tipo_Compra'        => $tipoComprovante,
                     'Monto_Total'       => $Totalparclal,
                     'Monto_Total_Iva'   => round($Totalparclal / 11) ,
                     'Contado_Credito'   => 1,
                     'Insert'            => 2,
                     'Usuario_idUsuario' => $this->session->userdata('idUsuario'),
                     'Caja_idCaja'       => $ultimaCaja,

                   );
                   $this->db->insert('Factura_Compra', $object);
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

    public function Pago(
    		$Totalparclal,
            $parcial1,
            $moneda,

            $parcial2,
            $numcheque,
            $fecha_pago,
            $Razon,$Descripcion ,$tipoComprovante,$comprobante ,$R_H ,$cuenta_bancaria,$Nombre_Tipo,
            $parcial3,
            $efectivoTarjeta,
            $Tarjeta,$PlandeCuenta )
    {
           $this->db->trans_begin();
           $ultimaCaja = $this->ultimaCaja();
           $idcompra = $this->cobros($PlandeCuenta,$Totalparclal,$ultimaCaja ,$Nombre_Tipo,$Descripcion ,$tipoComprovante,$comprobante);
           if ($Razon == 1) {
             $idempleado = $R_H;
           }else{
             $idempleado = null;
           }
                  if ($tipoComprovante == 0) {
                  $data                         = array(
                  'Fecha'                       => date("Y-m-d"),
                  'Hora'                        => strftime( "%H:%M", time() ),
                  'Factura_Compra_idFactura_Compra' => $idcompra,
                  'Caja_idCaja'                 => $this->session->userdata('idCaja'),
                  );
                  $this->db->insert('Acientos', $data);
                  $idAcientos = $this->db->insert_id();
                  $data                         = array(
                  'PlandeCuenta_idPlandeCuenta' => $PlandeCuenta,
                  'Acientos_idAcientos'         => $idAcientos,
                  'DebeDetalle'                 => '(Ac +)',
                  'HaberDetalle'                => null,
                  'Debe'                        => $Totalparclal,
                  'Haber'                       => null,
                  'Descuento_Debe'              => NULL,
                  'Descuento_Haber'             => NULL,
                  );
                  $this->db->insert('PlandeCuenta_has_Acientos', $data);
                  }else{
                    $res = round($Totalparclal / 11) ;
                  $data                           = array(
                  'Fecha'                         => date("Y-m-d"),
                  'Hora'                          => strftime( "%H:%M", time() ),
                  'Factura_Compra_idFactura_Compra' => $idcompra,
                  'Caja_idCaja'                   => $this->session->userdata('idCaja'),
                  );
                  $this->db->insert('Acientos', $data);
                  $idAcientos = $this->db->insert_id();
                   $data                         = array(
                  'PlandeCuenta_idPlandeCuenta' => $PlandeCuenta,
                  'Acientos_idAcientos'         => $idAcientos,
                  'DebeDetalle'                 => '(Ac +)',
                  'HaberDetalle'                => null,
                  'Debe'                        => $Totalparclal - $res,
                  'Haber'                       => null,
                  'Descuento_Debe'              => NULL,
                  'Descuento_Haber'             => NULL,
                  );
                  $this->db->insert('PlandeCuenta_has_Acientos', $data);
                  $data                         = array(
                  'PlandeCuenta_idPlandeCuenta' => '479',
                  'Acientos_idAcientos'         => $idAcientos,
                  'DebeDetalle'                 => '(Ac +)',
                  'HaberDetalle'                => null,
                  'Debe'                        => $res,
                  'Haber'                       => null,
                  'Descuento_Debe'              => NULL,
                  'Descuento_Haber'             => NULL,
                  );
                  $this->db->insert('PlandeCuenta_has_Acientos', $data);
                }


           if (!empty($parcial1)) {
                  foreach ($moneda as $key => $value) {
                          $data                                                 = array(
                          'Descripcion'                                         => 'Pago de '.$Nombre_Tipo.' '.$value['EF'].' '.$value['signo'] ,
                          'Monto'                                               => $value['cambiado'],
                          'Fecha'                                               => date("Y-m-d"),
                          'Hora'                                                => strftime( "%H:%M", time() ),
                          'Empleado_idEmpleado'                                 => $idempleado,
                          'Caja_idCaja'                                         => $ultimaCaja,
                          'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
                          'Devoluciones_idDevoluciones'                         => null,
                          'Factura_Compra_idFactura_Compra'                     => $idcompra,
                          'Moneda_idMoneda'                                     =>  $value['Moneda'], 
                         );
                     $this->db->insert('Caja_Pagos', $data);
                     $id = $this->db->insert_id();
                  }
                           $this->add_aciento_plan( $idAcientos,$tipoComprovante,$PlandeCuenta,'2',$parcial1);

           }

            if (!empty($parcial2)) {
              if (empty($cuenta_bancaria)) {
                $data                                                 = array(
                'NumeroCheque'                                        => $numcheque,
                'Control'                                             => 0,
                'ConceptoSalida'                                     => 'Pago de  '.$Nombre_Tipo,
                'FechaExpedicion'                                     => date("Y-m-d"),
                'Hora'                                                => strftime( "%H:%M", time() ),
                'Importe'                                             => $parcial2,
                'Pagos'                                               => 1,
                'Cobros'                                              => null,
                'Activo_Inactivo'                                     => 1,
                'FechaPago'                                           => $fecha_pago ,
                'Entrada_Salida'                                     => 'Salida',
                'Proveedor_idProveedor'                               => null,
                'Usuario_idUsuario'                                   => $this->session->userdata('idUsuario'),
                'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
                'Caja_idCaja'                                         => $ultimaCaja,
                );
               $this->db->insert('Movimientos', $data);
                $idm = $this->db->insert_id();
              $this->add_aciento_plan( $idAcientos,$tipoComprovante,$PlandeCuenta,'4',$parcial2);

              }else{
                $data                                                 = array(
                'NumeroCheque'                                        => $numcheque,
                'Control'                                             => 0,
                'ConceptoSalida'                                     => 'Pago de  '.$Nombre_Tipo,
                'FechaExpedicion'                                     => date("Y-m-d"),
                'Hora'                                                => strftime( "%H:%M", time() ),
                'Importe'                                             => $parcial2,
                'Pagos'                                               => 1,
                'Cobros'                                              => null,
                'Activo_Inactivo'                                     => 2,
                'FechaPago'                                           => $fecha_pago ,
                'Entrada_Salida'                                     => 'Salida',
                'Gestor_Bancos_idGestor_Bancos'                       => $cuenta_bancaria,
                'Proveedor_idProveedor'                               => null,
                'Usuario_idUsuario'                                   => $this->session->userdata('idUsuario'),
                'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
                'Caja_idCaja'                                         => $ultimaCaja,
                );
                $this->db->insert('Movimientos', $data);
                $idm = $this->db->insert_id();
                $this->db->set('MontoActivo', 'MontoActivo-'.$parcial2  , FALSE);
                $this->db->where('idGestor_Bancos',$cuenta_bancaria);
                $this->db->update('Gestor_Bancos');
               $this->add_aciento_plan( $idAcientos,$tipoComprovante,$PlandeCuenta,'4',$parcial2);

              }

              $_data                                                 = array(
               'Descripcion'                                         => 'Pago de '.$Nombre_Tipo.' (Cheque)' .$numcheque,
               'Monto'                                               => $parcial2,
               'Fecha'                                               => date("Y-m-d"),
               'Hora'                                                => strftime( "%H:%M", time() ),
               'Empleado_idEmpleado'                                 => $idempleado,
               'Caja_idCaja'                                         => $ultimaCaja,
               'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
               'Devoluciones_idDevoluciones'                        => null,
               'Factura_Compra_idFactura_Compra'                      => $idcompra,
                'Movimientos_idMovimientos'                            =>$idm
               );
           $this->db->insert('Caja_Pagos', $_data);
           }

            if (!empty($parcial3)) {
              switch ($Tarjeta) {
                case 1:
                 $Tarje = "(Tarjetas de Crédito)";
                  break;
                case 2:
                 $Tarje = "(Tarjetas de Débito)";
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
               'Descripcion'                                         => 'Pago de '.$Nombre_Tipo.' con '.$Tarje,
               'Monto'                                               => $parcial3,
               'Fecha'                                               => date("Y-m-d"),
               'Hora'                                                => strftime( "%H:%M", time() ),
               'Empleado_idEmpleado'                                 => $idempleado,
               'Caja_idCaja'                                         => $ultimaCaja,
               'Factura_Compra_idFactura_Compra'                      => $idcompra,
               'Tarjeta_idTarjeta'                            =>$idt
               );
              $this->db->insert('Caja_Pagos', $_data);
              $this->add_aciento_plan( $idAcientos,$tipoComprovante,$PlandeCuenta,'304',$parcial3);
           }
              $this->add_flou( $idAcientos,$Totalparclal,$PlandeCuenta,$parcial3);
           if ($this->db->trans_status() === FALSE)
           {
                   $this->db->trans_rollback();
           }
           else
           {
                   $this->db->trans_commit();
                   return $idcompra;
           }

    }

    /**
     * Inserts new data into database
     *
     * @param Array $data Associative array with field_name=>value pattern to be inserted into database
     * @return mixed Inserted row ID, or false if error occured
     */
    public function insert_tipo(Array $data) {
        if ($this->db->insert('Tipos_de_Pago', $data)) {
            $id = $this->db->insert_id();
          $this->db->select('idTipos_de_Pago as id,NombreTipo as nom');
          $this->db->where('idTipos_de_Pago', $id);
          return $this->db->get('Tipos_de_Pago')->row();
        } else {
            return false;
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

        public function check_nom($id)
    {
        $this->db->select('NombreTipo');
        $this->db->where('NombreTipo',$id);
        $consulta = $this->db->get('Tipos_de_Pago');
        if ($consulta->num_rows()> 0) {
            return true;
        }
    }

        public function add_aciento_plan($idAcientos,$tipoComprovante,$PlandeCuenta,$value='',$parcial)
    {
          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => $value,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => NULL,
          'HaberDetalle'                => '(Ac -)',
          'Debe'                        => null,
          'Haber'                       => $parcial,
          'Descuento_Debe'              => NULL,
          'Descuento_Haber'             => NULL,
          );
          $this->db->insert('PlandeCuenta_has_Acientos', $data);

    }

    public function add_flou( $idAcientos,$Totalparclal,$PlandeCuenta,$parcial3)
    {

          $data                         = array(
          'PlandeCuenta_idPlandeCuenta' => NULL,
          'Acientos_idAcientos'         => $idAcientos,
          'DebeDetalle'                 => '<p class="text-danger">Pagos</p>',
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