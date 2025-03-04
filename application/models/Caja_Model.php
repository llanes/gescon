<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Caja_Model extends CI_Model {
	var $column = array('idCaja','Fecha_apertura','Fecha_cierre','Monto_inicial','Monto_final','Usuario');
	var $order = array();
	var $where = "(Cierre = 0) OR (Cierre = '')";
	var $column1 = array('idAuditoria_caja','Descripcion','A_Fecha','Caja_idCaja','Usuario');
	var $order1  = array();

	public function __construct()
	{
		parent::__construct();
	}
	const  CAJA = 'Caja';
	const  CONTROL =  'Auditoria_caja';


	/**
	 * [abrir_Cerrar_Caja description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function abrir_Cerrar_Caja($data)
	{
		$this->db->select("Caja.cierre as cierre");
		$this->db->where('Fecha',$unix);
		$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
		$query = $this->db->get(st);
		return $query->result_array();
	} 

	/**
	 * [inicio_caja description]
	 * @param  [type] $unix [description]
	 * @return [type]       [description]
	 */
		function inicio_caja($fecha)
	{
			$id  = $this->ultimoCaja();
			$this->db->select("Cierre");
			$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
			$this->db->where('idCaja',$id);
			// $this->db->where('Fecha_apertura',$fecha);
			$query = $this->db->get(self::CAJA);
			foreach($query->result_array() as $d)
			{
				return( $d['Cierre']);
			}
	}

	/**
	 * [ultimoCaja description]
	 * @return [type] [description]
	 */
	public function ultimoCaja()
    {
            $this->db->select_max('idCaja');
            $this->db->where("Cierre = 0");
            $this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
            $query = $this->db->get('Caja');
            $row = $query->row();
            return $row->idCaja;
    }


	public function inicio_busca()
	{
			$this->db->select("Cierre");
			$this->db->where('Cierre',0);
			$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
			$query = $this->db->get(self::CAJA);
			return $query->num_rows();
	}
	// consulta para vista caja
		// inicio control de caja
	function vista($unix)
	{	
		$this->db->select("*");
		$this->db->where("Cierre = 0");
		$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
		// $this->db->where('Fecha_apertura',$unix);
		$query = $this->db->get(self::CAJA);
		return $query->result_array();
	}

	function inicial()
	{
		$this->db->select('Monto_inicial');
		$this->db->where("Cierre = 0");
		$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
		$query = $this->db->get(self::CAJA);
		foreach($query->result_array() as $d)
		{
			return( $d['Monto_inicial']);
		}

	}

	// ultima fecha

	 function monto_final()
	{
		$this->db->select('Monto_final');
		$this->db->where("Cierre = 0");
		$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
		$query = $this->db->get(self::CAJA);
		$row = $query->row();
		return $row->Monto_final;
	}



	public function datatime()
	{
		$this->db->select("CONCAT(MAX(Fecha_apertura),' : ',Hora_apertura) as date_time,MAX(idCaja) as id");
		$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
		$this->db->where("Cierre = 0");
		$query = $this->db->get(self::CAJA);
		return $query->row();

	}


	function monto_final_abrir()
	{
		$id  = $this->id();
		if (!is_null($id)) {
				$this->db->select('Monto_final', 'Monto_final');
				$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
				$this->db->where('idCaja',$id);
				$query = $this->db->get(self::CAJA);
				foreach($query->result_array() as $d)
				{
					return( $d['Monto_final']);
				}
		}else {
           return 0;
		}

	}

	function id()
	{
		$this->db->select_max('idCaja', 'idCaja');
		$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
		$query = $this->db->get(self::CAJA);
		foreach($query->result_array() as $d)
		{
			return( $d['idCaja']);
		}
	}


	//	Insertar caja apertura
	function add_caja($data,$impor)
	{
		$this->db->trans_begin();
		// $this->output->enable_profiler(TRUE);
		$this->db->insert(self::CAJA,$data);
		$id = $this->db->insert_id();
        $this->session->set_userdata('idCaja',$id );
		$this->auditoria($id ,$var2= $this->session->userdata('Usuario'). 'Abrio una Caja Nueva');

		$data                     = array(
		'Fecha'                       => date("Y-m-d"),
		'Hora'                        => strftime( "%H:%M", time() ),
		'Caja_idCaja'                 => $id,
		);
		$this->db->insert('Acientos', $data);
		$idAcientos = $this->db->insert_id();
				if (!empty($impor)) {
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 1,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => '(Ac +)',
							'HaberDetalle'                => NULL,
							'Debe'                        => $impor,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
				}
				$totalmercaderia = $this->montomercaderia();
				if (!empty($totalmercaderia)) {
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 58,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => '(Ac +)',
							'HaberDetalle'                => NULL,
							'Debe'                        => $totalmercaderia,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);

				}
				$totalbanco = $this->totalbanco();
				if (!empty($totalbanco)) {
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 8,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => '(Ac +)',
							'HaberDetalle'                => NULL,
							'Debe'                        => $totalbanco,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);

				}
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 224,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => Null,
							'HaberDetalle'                => '(Pa +)',
							'Debe'                        => NULL,
							'Haber'                       => $impor+ $totalmercaderia ,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
     


 		if ($this->db->trans_status() === FALSE)
		{
		     return   $this->db->trans_rollback();
		}
		else
		{
		     return   $this->db->trans_commit();

		}
		// $this->db->trans_complete();
	}

	function add_set_caja($data,$idCaja,$impor='')
	{
		if (empty($idCaja)) {
		
            
		   $id  = $this->ultimoCaja();
			$this->db->set($data, FALSE);
			$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
			$this->db->where('idCaja', $id);
			$this->db->update(self::CAJA);
			$this->auditoria($id ,$var2= $this->session->userdata('Usuario').' Se Cerro una Caja Nueva');
	        $data                     = array(
            'Fecha'                       => date("Y-m-d"),
            'Hora'                        => strftime( "%H:%M", time() ),
            'Caja_idCaja'                 => $id,
            );
            $this->db->insert('Acientos', $data);
            $idAcientos = $this->db->insert_id();
				if (!empty($impor)) {
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 1,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => '(Ac +)',
							'HaberDetalle'                => NULL,
							'Debe'                        => $impor,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);
				}
				$totalmercaderia = $this->montomercaderia();
				if (!empty($totalmercaderia)) {
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 58,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => '(Ac +)',
							'HaberDetalle'                => NULL,
							'Debe'                        => $totalmercaderia,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);

				}
				$totalbanco = $this->totalbanco();
				if (!empty($totalbanco)) {
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 8,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => '(Ac +)',
							'HaberDetalle'                => NULL,
							'Debe'                        => $totalbanco,
							'Haber'                       => NULL,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);

				}
			            $data                     = array(
							'PlandeCuenta_idPlandeCuenta' => 224,
							'Acientos_idAcientos'         => $idAcientos,
							'DebeDetalle'                => Null,
							'HaberDetalle'                => '(Pa +)',
							'Debe'                        => NULL,
							'Haber'                       => $impor + ($totalmercaderia + $totalbanco) ,
							'Descuento_Debe'              => NULL,
							'Descuento_Haber'             => NULL,
			                );
			                $this->db->insert('PlandeCuenta_has_Acientos', $data);


		}else {
           $id = $idCaja;
			$this->db->set($data, FALSE);
			$this->db->where('idCaja', $id);
			$this->db->update(self::CAJA);
			$this->auditoria($id ,$var2= $this->session->userdata('Usuario').' Cerro la Caja '.$id );


		}
        $this->session->unset_userdata('idCaja');
		$this->db->trans_begin();

		if ($this->db->trans_status() === FALSE)
		{
		       return $this->db->trans_rollback();
		}
		else
		{
		       $this->db->trans_commit();
		       return $this->db->affected_rows();
		}
	}

	public function set_caja($importe,$idCaja="")
	{
		if (empty($idCaja)) {
		   $id  = $this->ultimoCaja();
		}else {
           $id = $idCaja;
		}

		$this->db->set('Monto_final', $importe, FALSE);
		$this->db->set('Cierre', 1, FALSE);
		$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
		$this->db->where('idCaja', $id);
		$this->db->update(self::CAJA);
		$this->auditoria($id ,$var2='Se Actualizo una Caja Editada');
		return $this->db->affected_rows();
	}

	public function montomercaderia()
	{
		$this->db->select('SUM(Precio_Venta*(Cantidad_A+Cantidad_D)) AS total');
		$this->db->where('(Cantidad_A+Cantidad_D) > 0');
		$query = $this->db->get('Producto');
		$row = $query->row();
		return $row->total;

	}

	public function totalbanco()
	{
		$this->db->select('MontoActivo AS total');
		$query = $this->db->get('Gestor_Bancos');
		$row = $query->row();
		if ($query->num_rows()>0) {
             return $row->total;
		}


	}


	public function edit_caja($id)
	{
		$this->db->trans_begin();
			$this->db->set('Cierre',0);
			$this->db->set('Control',1);
			$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
			$this->db->where('idCaja', $id);
			$this->db->update(self::CAJA);

			$this->auditoria($id ,$var2='Se Abrio una Caja Cerrada!!');
            $this->session->set_userdata('idCaja',$id );
		if ($this->db->trans_status() === FALSE)
		{
		        $this->db->trans_rollback();
		}
		else
		{
		        $this->db->trans_commit();
		}
	}

	public function auditoria($idCaja,$Descripcion)
	{
		$this->db->trans_begin();
			$data = array(
			'Descripcion'       => $Descripcion,
			'A_Fecha'           => date("Y-m-d"),
			'A_Hora'            => strftime( "%H:%M", time()),
			'Caja_idCaja'       => $idCaja,
			'Usuario_idUsuario' => $this->session->userdata('idUsuario')
			);
		$this->db->insert(self::CONTROL,$data);
		if ($this->db->trans_status() === FALSE)
		{
		        $this->db->trans_rollback();
		}
		else
		{
		        $this->db->trans_commit();
		}

	}


	function get_caja($id)
	{
		if (!empty($id)) {
		$consult="
		(SELECT CONCAT(cp.Fecha,' ', cp.Hora) as fecha, Descripcion as descripcion, Null as debe, MontoRecibido as haber, cp.Caja_idCaja from Caja_Pagos cp
		LEFT JOIN Factura_Compra ON cp.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra
		LEFT JOIN Cuenta_Corriente_Empresa ON cp.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = Cuenta_Corriente_Empresa.idCuenta_Corriente_Empresa
		WHERE  cp.Caja_idCaja ='".$id."' AND cp.Estado = 0 )
		UNION ALL
		(SELECT CONCAT(cc.Fecha,' ', cc.Hora) as fecha, Descripcion as descripcion, MontoRecibido as debe, Null as haber, cc.Caja_idCaja from Caja_Cobros cc
		LEFT JOIN Factura_Venta ON cc.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta
		LEFT JOIN Cuenta_Corriente_Cliente ON cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = Cuenta_Corriente_Cliente.idCuenta_Corriente_Cliente
		WHERE cc.Caja_idCaja ='".$id."' AND cc.Estado = 0  )

		";
		}else{
		$consult="
		(SELECT CONCAT(cp.Fecha,' ', cp.Hora) as fecha, Descripcion as descripcion, Null as debe, MontoRecibido as haber, cp.Caja_idCaja from Caja_Pagos cp
		LEFT JOIN Factura_Compra ON cp.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra
		LEFT JOIN Cuenta_Corriente_Empresa ON cp.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = Cuenta_Corriente_Empresa.idCuenta_Corriente_Empresa
		WHERE  cp.Estado = 0 )
		UNION ALL
		(SELECT CONCAT(cc.Fecha,' ', cc.Hora) as fecha, Descripcion as descripcion, MontoRecibido as debe, Null as haber, cc.Caja_idCaja from Caja_Cobros cc
		LEFT JOIN Factura_Venta ON cc.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta
		LEFT JOIN Cuenta_Corriente_Cliente ON cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = Cuenta_Corriente_Cliente.idCuenta_Corriente_Cliente
		WHERE cc.Estado = 0  )

		";
		}

		$query = $this->db->query($consult);
		return $query->result();
	}
	function count_filter($id)
	{
		if (!empty($id)) {

		$consult="
		(SELECT CONCAT(cp.Fecha,' ', cp.Hora) as fecha, Descripcion as descripcion, Null as debe, MontoRecibido as haber from Caja_Pagos cp
		LEFT JOIN Factura_Compra ON cp.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra
		LEFT JOIN Cuenta_Corriente_Empresa ON cp.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = Cuenta_Corriente_Empresa.idCuenta_Corriente_Empresa
		WHERE  cp.Caja_idCaja ='".$id."' AND cp.Estado = 0 )
		UNION ALL
		(SELECT CONCAT(cc.Fecha,' ', cc.Hora) as fecha, Descripcion as descripcion, MontoRecibido as debe, Null as haber from Caja_Cobros cc
		LEFT JOIN Factura_Venta ON cc.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta
		LEFT JOIN Cuenta_Corriente_Cliente ON cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = Cuenta_Corriente_Cliente.idCuenta_Corriente_Cliente
		WHERE cc.Caja_idCaja ='".$id."' AND cc.Estado = 0  )

		";
		}else{

		$consult="
		(SELECT CONCAT(cp.Fecha,' ', cp.Hora) as fecha, Descripcion as descripcion, Null as debe, MontoRecibido as haber from Caja_Pagos cp
		LEFT JOIN Factura_Compra ON cp.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra
		LEFT JOIN Cuenta_Corriente_Empresa ON cp.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = Cuenta_Corriente_Empresa.idCuenta_Corriente_Empresa
		WHERE cp.Estado = 0 )
		UNION ALL
		(SELECT CONCAT(cc.Fecha,' ', cc.Hora) as fecha, Descripcion as descripcion, MontoRecibido as debe, Null as haber from Caja_Cobros cc
		LEFT JOIN Factura_Venta ON cc.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta
		LEFT JOIN Cuenta_Corriente_Cliente ON cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = Cuenta_Corriente_Cliente.idCuenta_Corriente_Cliente
		WHERE cc.Estado = 0  )

		";
		}

		$query = $this->db->query($consult);
		return $query->num_rows();
	}
	public function count_todas($id)
	{
		if (!empty($id)) {

		$consult="
		(SELECT CONCAT(cp.Fecha,' ', cp.Hora) as fecha, Descripcion as descripcion, Null as debe, MontoRecibido as haber from Caja_Pagos cp
		LEFT JOIN Factura_Compra ON cp.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra
		LEFT JOIN Cuenta_Corriente_Empresa ON cp.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = Cuenta_Corriente_Empresa.idCuenta_Corriente_Empresa
		WHERE  cp.Caja_idCaja ='".$id."' AND cp.Estado = 0 )
		UNION ALL
		(SELECT CONCAT(cc.Fecha,' ', cc.Hora) as fecha, Descripcion as descripcion, MontoRecibido as debe, Null as haber from Caja_Cobros cc
		LEFT JOIN Factura_Venta ON cc.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta
		LEFT JOIN Cuenta_Corriente_Cliente ON cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = Cuenta_Corriente_Cliente.idCuenta_Corriente_Cliente
		WHERE cc.Caja_idCaja ='".$id."' AND cc.Estado = 0  )

		";
		}else{

		$consult="
		(SELECT CONCAT(cp.Fecha,' ', cp.Hora) as fecha, Descripcion as descripcion, Null as debe, MontoRecibido as haber from Caja_Pagos cp
		LEFT JOIN Factura_Compra ON cp.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra
		LEFT JOIN Cuenta_Corriente_Empresa ON cp.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = Cuenta_Corriente_Empresa.idCuenta_Corriente_Empresa
		WHERE cp.Estado = 0 )
		UNION ALL
		(SELECT CONCAT(cc.Fecha,' ', cc.Hora) as fecha, Descripcion as descripcion, MontoRecibido as debe, Null as haber from Caja_Cobros cc
		LEFT JOIN Factura_Venta ON cc.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta
		LEFT JOIN Cuenta_Corriente_Cliente ON cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = Cuenta_Corriente_Cliente.idCuenta_Corriente_Cliente
		WHERE cc.Estado = 0  )

		";
		}
		$query = $this->db->query($consult);
		return $this->db->count_all_results();
	}


	//////////////////// lista de movimiento caja////////////////////////////////////////////////
		private function _get_datatables_query()
	{
		if ($this->session->userdata('idUsuario') !=1) {
					$this->db->from(self::CAJA);
					$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
					$this->db->join('Usuario', 'Caja.Usuario_idUsuario = Usuario.idUsuario', 'INNER');
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
		}else {
					$this->db->from(self::CAJA);
					$this->db->join('Usuario', 'Caja.Usuario_idUsuario = Usuario.idUsuario', 'INNER');
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

	}

	function get_caja_list()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function get_count_filtro()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_count_todas()
	{
		if ($this->session->userdata('idUsuario') !=1) {
			$this->db->from(self::CAJA);
			$this->db->where('Usuario_idUsuario ='.$this->session->userdata('idUsuario'));
			$this->db->join('Usuario', 'Caja.Usuario_idUsuario = Usuario.idUsuario', 'INNER');
			return $this->db->count_all_results();
		}else {
			$this->db->from(self::CAJA);
			$this->db->join('Usuario', 'Caja.Usuario_idUsuario = Usuario.idUsuario', 'INNER');
			return $this->db->count_all_results();
		}
	}
	//////////////////////////////////////////////////////////////////////////////////////

		private function _get_histori_query()
	{
		$this->db->from('Usuario');
		$this->db->join('Auditoria_caja', 'Auditoria_caja.Usuario_idUsuario = Usuario.idUsuario', 'INNER');		
		$i = 0;

		foreach ($this->column1 as $item)
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column1[$i] = $item;
			$i++;
		}

		if(isset($_POST['order']))
		{
			$this->db->order_by($column1[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order1))
		{
			$order1 = $this->order1;
			$this->db->order_by(key($order1), $order1[key($order1)]);
		}
	}

	function get_hist_list()
	{
		$this->_get_histori_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function get_histcount_filtro()
	{
		$this->_get_histori_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_histcount_todas()
	{
		$this->db->from('Usuario');
		$this->db->join('Auditoria_caja', 'Auditoria_caja.Usuario_idUsuario = Usuario.idUsuario', 'INNER');		
		return $this->db->count_all_results();
	}



	// ----------------------------------------------------------------------------------------------------------

	function get_activas($id )
	{
		$consult="
		(SELECT CONCAT(cp.Fecha,' ', cp.Hora) as fecha, Descripcion as descripcion, Null as debe, MontoRecibido as haber from Caja_Pagos cp
		LEFT JOIN Factura_Compra ON cp.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra
		LEFT JOIN Cuenta_Corriente_Empresa ON cp.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = Cuenta_Corriente_Empresa.idCuenta_Corriente_Empresa
		WHERE  cp.Caja_idCaja ='".$id."' AND cp.Estado = 0 )
		UNION ALL
		(SELECT CONCAT(cc.Fecha,' ', cc.Hora) as fecha, Descripcion as descripcion, MontoRecibido as debe, Null as haber from Caja_Cobros cc
		LEFT JOIN Factura_Venta ON cc.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta
		LEFT JOIN Cuenta_Corriente_Cliente ON cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = Cuenta_Corriente_Cliente.idCuenta_Corriente_Cliente
		WHERE cc.Caja_idCaja ='".$id."' AND cc.Estado = 0  )

		";

		$query = $this->db->query($consult);
		return $query->result();
	}
	function count_filter_activas($id )
	{
		$consult="
		(SELECT CONCAT(cp.Fecha,' ', cp.Hora) as fecha, Descripcion as descripcion, Null as debe, MontoRecibido as haber from Caja_Pagos cp
		LEFT JOIN Factura_Compra ON cp.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra
		LEFT JOIN Cuenta_Corriente_Empresa ON cp.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = Cuenta_Corriente_Empresa.idCuenta_Corriente_Empresa
		WHERE  cp.Caja_idCaja ='".$id."' AND cp.Estado = 0 )
		UNION ALL
		(SELECT CONCAT(cc.Fecha,' ', cc.Hora) as fecha, Descripcion as descripcion, MontoRecibido as debe, Null as haber from Caja_Cobros cc
		LEFT JOIN Factura_Venta ON cc.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta
		LEFT JOIN Cuenta_Corriente_Cliente ON cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = Cuenta_Corriente_Cliente.idCuenta_Corriente_Cliente
		WHERE cc.Caja_idCaja ='".$id."' AND cc.Estado = 0  )

		";

		$query = $this->db->query($consult);
		return $query->num_rows();
	}
	public function count_activas($id )
	{
		$consult="
		(SELECT CONCAT(cp.Fecha,' ', cp.Hora) as fecha, Descripcion as descripcion, Null as debe, MontoRecibido as haber from Caja_Pagos cp
		LEFT JOIN Factura_Compra ON cp.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra
		LEFT JOIN Cuenta_Corriente_Empresa ON cp.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = Cuenta_Corriente_Empresa.idCuenta_Corriente_Empresa
		WHERE  cp.Caja_idCaja ='".$id."' AND cp.Estado = 0 )
		UNION ALL
		(SELECT CONCAT(cc.Fecha,' ', cc.Hora) as fecha, Descripcion as descripcion, MontoRecibido as debe, Null as haber from Caja_Cobros cc
		LEFT JOIN Factura_Venta ON cc.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta
		LEFT JOIN Cuenta_Corriente_Cliente ON cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = Cuenta_Corriente_Cliente.idCuenta_Corriente_Cliente
		WHERE cc.Caja_idCaja ='".$id."' AND cc.Estado = 0  )

		";
		$query = $this->db->query($consult);
		return $this->db->count_all_results();
	}

	public function load_user($value='')
	{
		$this->db->select('Usuario');
		$this->db->join('Usuario', 'Caja.Usuario_idUsuario = Usuario.idUsuario', 'inner');
		$this->db->where('idCaja', $value);
		$query = $this->db->get('Caja');
		return $query->row();
	}



}

/* End of file caja_model.php */
/* Location: ./application/models/caja_model.php */