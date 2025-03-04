 <?php defined('BASEPATH') OR exit('No direct script access allowed');
class MovimientoBanco_Model extends CI_Model {
	var $column = array(
		'NumeroCheque',
		'Concepto',
		'FechaExpedicion',
		'Importe',
		'Nombre',
		);
	var $order = array('idMovimientos' => 'desc');

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
		$this->load->database();
	}

    const Movimientos  = 'Movimientos';
    const ID  = 'idMovimientos';

    /**
     * [_get_datatables_query description]
     * @return [type] [description]
     */
	private function _get_datatables_query()
	{
		$this->db->join('Gestor_Bancos', 'Gestor_Bancos.idGestor_Bancos = Movimientos.Gestor_Bancos_idGestor_Bancos', 'inner');
		$this->db->join('PlandeCuenta', 'PlandeCuenta.idPlandeCuenta = Movimientos.PlandeCuenta_idPlandeCuenta', 'left');
		// $this->db->where('Activo_Inactivo = 0 AND ');
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

	function get_Movi()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(self::Movimientos);
		return $query->result();
	}

	public function getMovi()
	{
		$this->db->select('*');
		$this->db->join('Gestor_Bancos', 'Gestor_Bancos.idGestor_Bancos = Movimientos.Gestor_Bancos_idGestor_Bancos', 'inner');
		$this->db->join('PlandeCuenta', 'PlandeCuenta.idPlandeCuenta = Movimientos.PlandeCuenta_idPlandeCuenta', 'left');
		$query = $this->db->get(self::Movimientos);
		return $query->result();
	}

	function count_filtro()
	{
		$this->_get_datatables_query();
		$query = $this->db->get(self::Movimientos);
		return $query->num_rows();
	}

	public function count_todas()
	{
        $this->db->join('Gestor_Bancos', 'Gestor_Bancos.idGestor_Bancos = Movimientos.Gestor_Bancos_idGestor_Bancos', 'inner');
        $this->db->join('PlandeCuenta', 'PlandeCuenta.idPlandeCuenta = Movimientos.PlandeCuenta_idPlandeCuenta', 'left');
		$this->db->get(self::Movimientos);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from(self::Movimientos);
		$this->db->join('Gestor_Bancos', 'Gestor_Bancos.idGestor_Bancos = Movimientos.Gestor_Bancos_idGestor_Bancos', 'inner');
		$this->db->join('PlandeCuenta', 'PlandeCuenta.idPlandeCuenta = Movimientos.PlandeCuenta_idPlandeCuenta', 'left');
		$this->db->where(self::ID,$id);
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * [insert description]
	 * @param  Array  $data [description]
	 * @return [type]       [description]
	 */
    public function insert($Cheques ,$PlandeCuenta,$cuenta_bancaria,$movi,$Acheque_m    ) {
    	$this->db->trans_begin();
    	     if (!empty($Cheques)) {
    	     	    	            $seleccionados = explode(',',$Cheques);
    	     	    	            $selecc = explode(',',$Acheque_m);
    	     		$monto ;
                    for ($i=0;$i<count($seleccionados);$i++)
                    {
		                  for ($j=0;$j<count($selecc);$j++)
		                    {
			                      $this->db->set('Activo_Inactivo', '2', FALSE);
			                      $this->db->set('Gestor_Bancos_idGestor_Bancos', $cuenta_bancaria, FALSE);
			                      $this->db->set('PlandeCuenta_idPlandeCuenta', $PlandeCuenta, FALSE);
			                      $this->db->where('idMovimientos', $seleccionados[$i]);
			                      $this->db->update('Movimientos');

			                      $this->db->set('MontoActivo', 'MontoActivo+'.$selecc[$j], FALSE);
			                      $this->db->where('idGestor_Bancos', $cuenta_bancaria);
			                      $this->db->update('Gestor_Bancos');
		                    }
		                    $monto +=$selecc[$j];

                    }
				if (!empty($monto )) {
					$data                         = array(
					'Fecha'                       => date("Y-m-d"),
					'Hora'                        => strftime( "%H:%M", time() ),
					'Caja_idCaja'                 => $this->session->userdata('idCaja'),
					);
					$this->db->insert('Acientos', $data);
					$idAcientos = $this->db->insert_id();
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 8,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => '(Ac +)',
							'HaberDetalle'                => NULL,
							'Debe'                        => $monto,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);

			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => $PlandeCuenta,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                 => Null,
							'HaberDetalle'                => '(Ac -)',
							'Debe'                        => NULL,
							'Haber'                       => $monto,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
			            $data                     = array(
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                 => '<p class="text-danger"> (Deposito de cheque a cuenta bancaria)</p>',
							'HaberDetalle'                => NULL,
							'Debe'                        => NULL,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
					}

    	     }else {
    	     	return FALSE;
    	     }


    	if ($this->db->trans_status() === FALSE)
    	{
    	        $this->db->trans_rollback();
    	}
    	else
    	{
    	     return   $this->db->trans_commit();
    	}
    }
    public function insert2($Numeru ,$PlandeCuenta,$cuenta_bancaria,$Importe,$fecha,$movi  )
    {
    	$this->db->trans_begin();
                $data                                                 = array(
                'NumeroCheque'                                        => $Numeru,
                'Control'                                             => 1,
                'FechaExpedicion'                                     => date("Y-m-d"),
                'Hora'                                                => strftime( "%H:%M", time() ),
                'Importe'                                             => $Importe,
                'Pagos'                                               => null,
                'Cobros'                                              => null,
                'Activo_Inactivo'                                     => 2,
                'FechaPago'                                           => $fecha ,
                'Entrada_Salida'                                      => $movi,
                'Gestor_Bancos_idGestor_Bancos'                       => $cuenta_bancaria,
                'Proveedor_idProveedor'                               => null,
                'Cliente_idCliente'                                   => null,
                'Usuario_idUsuario'                                   => $this->session->userdata('idUsuario'),
                'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
                'PlandeCuenta_idPlandeCuenta'                         => $PlandeCuenta,
                'Caja_idCaja'										  => $this->ultimaCaja()
                );
				$this->db->insert('Movimientos', $data);
				$idmovi = $this->db->insert_id();
               if ($movi == 'Entrada') {

                $this->db->set('MontoActivo', 'MontoActivo+'.$Importe, FALSE);
                $this->db->where('idGestor_Bancos',$cuenta_bancaria);
                $this->db->update('Gestor_Bancos');
					$data                         = array(
					'Fecha'                       => date("Y-m-d"),
					'Hora'                        => strftime( "%H:%M", time() ),
					'Caja_idCaja'                 => $this->session->userdata('idCaja'),
					'Movimientos_idMovimientos'   => $idmovi,
					);
					$this->db->insert('Acientos', $data);
					$idAcientos = $this->db->insert_id();
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 8,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => '(Ac +)',
							'HaberDetalle'                => NULL,
							'Debe'                        => $Importe,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);

			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => $PlandeCuenta,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                 => Null,
							'HaberDetalle'                => '(Ac -)',
							'Debe'                        => NULL,
							'Haber'                       => $Importe,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
			            $data                     = array(
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                 => '<p class="text-danger">(Deposito de cheque a cuenta bancaria) Segun Cheque Nº '.$Numeru.'</p>',
							'HaberDetalle'                => NULL,
							'Debe'                        => NULL,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
                } else {
                $this->db->set('MontoActivo', 'MontoActivo-'.$Importe, FALSE);
                $this->db->where('idGestor_Bancos',$cuenta_bancaria);
                $this->db->update('Gestor_Bancos');
						$data                         = array(
						'Fecha'                       => date("Y-m-d"),
						'Hora'                        => strftime( "%H:%M", time() ),
					    'Caja_idCaja'                 => $this->session->userdata('idCaja'),
					    'Movimientos_idMovimientos'   => $idmovi,
						);
						$this->db->insert('Acientos', $data);
						$idAcientos = $this->db->insert_id();
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => $PlandeCuenta,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => '(Ac +)',
							'HaberDetalle'                => NULL,
							'Debe'                        => $Importe,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);

			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 8,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                 => Null,
							'HaberDetalle'                => '(Ac -)',
							'Debe'                        => NULL,
							'Haber'                       => $Importe,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
			            $data                     = array(
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                 => '<p class="text-danger">(Extraccion de cheque de cuenta bancaria) Segun Cheque Nº '.$Numeru.'</p>',
							'HaberDetalle'                => NULL,
							'Debe'                        => NULL,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
                }

     	if ($this->db->trans_status() === FALSE)
    	{
    	        $this->db->trans_rollback();
    	}
    	else
    	{
    	    return    $this->db->trans_commit();
    	}
    }
    public function insert3($movi ,$PlandeCuenta,$moneda,$cuenta_bancaria) {
    	$this->db->trans_begin();
    	     if (!empty($moneda)) {
    	     	$monedavar = 0;
                foreach ($moneda as $key => $value)
                    {
			                $data                                                 = array(
			                'Control'                                             => 1,
			                'FechaExpedicion'                                     => date("Y-m-d"),
			                'Hora'                                                => strftime( "%H:%M", time() ),
			                'Importe'                                             => $value['importe'],
			                'Pagos'                                               => null,
			                'Cobros'                                              => null,
			                'Activo_Inactivo'                                     => 2,
			                'FechaPago'                                           => null ,
			                'Entrada_Salida'                                      => $movi,
			                'Gestor_Bancos_idGestor_Bancos'                       => $cuenta_bancaria,
			                'Proveedor_idProveedor'                               => null,
			                'Cliente_idCliente'                                   => null,
			                'Usuario_idUsuario'                                   => $this->session->userdata('idUsuario'),
			                'Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa' => null,
			                'PlandeCuenta_idPlandeCuenta'                         => $PlandeCuenta,
			                'Caja_idCaja'										  =>  $this->session->userdata('idCaja')
			                );
			                $this->db->insert('Movimientos', $data);
			                $idmovi = $this->db->insert_id();
			                if ($movi == 'Entrada') {
			                $this->db->set('MontoActivo', 'MontoActivo+'. $value['importe'], FALSE);
			                $this->db->where('idGestor_Bancos',$cuenta_bancaria);
			                $this->db->update('Gestor_Bancos');
			                }else{
			                $this->db->set('MontoActivo', 'MontoActivo-'. $value['importe'], FALSE);
			                $this->db->where('idGestor_Bancos',$cuenta_bancaria);
			                $this->db->update('Gestor_Bancos');

			                }
						    $data                           = array(
							'Gestor_Bancos_idGestor_Bancos' => $cuenta_bancaria,
							'Moneda_idMoneda'               => $value['Moneda'],
							'MontoIngresado'                => $value['importe'],
							'Fecha'                         => date("Y-m-d"),
							'Hora'                          => strftime( "%H:%M", time() ),
							'EntradaSalida'                 => $movi,
							'Caja_idCaja'                   => $this->session->userdata('idCaja')
			                );
			                 $this->db->insert('Gestor_Bancos_has_Moneda', $data);

                    	$monedavar +=$value['importe'];

                    }
 					if ($movi == 'Entrada') {
					$data                         = array(
					'Fecha'                       => date("Y-m-d"),
					'Hora'                        => strftime( "%H:%M", time() ),
					'Caja_idCaja'                 => $this->session->userdata('idCaja'),
					'Movimientos_idMovimientos'   => $idmovi,
					);
					$this->db->insert('Acientos', $data);
					$idAcientos = $this->db->insert_id();
							$data                         = array(
							'PlandeCuenta_idPlandeCuenta' =>  8,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                 => '(Ac +)',
							'HaberDetalle'                => NULL,
							'Debe'                        => $monedavar,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);

			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => $PlandeCuenta,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                 => Null,
							'HaberDetalle'                => '(Ac -)',
							'Debe'                        => NULL,
							'Haber'                       => $monedavar,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
			            $data                     = array(
			            	'PlandeCuenta_idPlandeCuenta' => NULL,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                 => '<p class="text-danger">(Deposito En Efectivo cuenta bancaria)</p>',
							'HaberDetalle'                => NULL,
							'Debe'                        => NULL,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
                    }else{
						$data                         = array(
						'Fecha'                       => date("Y-m-d"),
						'Hora'                        => strftime( "%H:%M", time() ),
						'Caja_idCaja'                 => $this->session->userdata('idCaja'),
						'Movimientos_idMovimientos'   => $idmovi,
						);
						$this->db->insert('Acientos', $data);
						$idAcientos = $this->db->insert_id();
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => $PlandeCuenta,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => '(Ac +)',
							'HaberDetalle'                => NULL,
							'Debe'                        => $monedavar,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);

			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 8,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                 => Null,
							'HaberDetalle'                => '(Ac -)',
							'Debe'                        => NULL,
							'Haber'                       => $monedavar,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
			            $data                     = array(
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                 => '<p class="text-danger">(Extraxion en Efectivo de  cuenta bancaria)</p>',
							'HaberDetalle'                => NULL,
							'Debe'                        => NULL,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
                  }
    	     }else {
    	     	return FALSE;
    	     }


    	if ($this->db->trans_status() === FALSE)
    	{
    	        $this->db->trans_rollback();
    	}
    	else
    	{
    	   return     $this->db->trans_commit();
    	}
    }


    public function ultimaCaja()
    {
            $this->db->select_max('idCaja');
            $this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario').' or Usuario_idUsuario = 1');
            $query = $this->db->get('Caja');
            $row = $query->row();
            return $row->idCaja;
    }




   	public function update($where, $data)
	{
					$this->db->update(self::Movimientos, $data, $where);
					return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->trans_begin();
				$this->db->where(self::ID, $id);
				$this->db->delete('Movimientos');
		if ($this->db->trans_status() === FALSE)
		{
		        $this->db->trans_rollback();
		}
		else
		{
		        $this->db->trans_commit();
		}
	}

	public function username_check($Numeru_id)
	{
				$this->db->select('NumeroCheque');
				$this->db->where('NumeroCheque',$Numeru_id);
				$consulta = $this->db->get(self::Movimientos);
				if ($consulta->num_rows()> 0) {
					return true;
						}



	}

	public function username_checkk($Numeru_id,$id)
	{

				$this->db->select('NumeroCheque');
				$this->db->where('NumeroCheque',$Numeru_id);
				$this->db->where('idMovimientos !=', $id);
				$consulta = $this->db->get(self::Movimientos);
				if ($consulta->num_rows()> 0) {
					return true;
				}

	}

}

/* End of file MovimientoBanco_Model.php */
/* Location: ./application/models/MovimientoBanco_Model.php */