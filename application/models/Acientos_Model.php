<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Acientos_Model extends CI_Model {
        var $column = array(
        'Fecha',
        'Detalle',
        'Debe',
        'Haber',);
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Asuncion");
		$this->load->database();
	}

	function _get_datatables_query ()
	{
    if ($this->session->userdata('idUsuario') == '1') {
          $this->db->select('ac.idAcientos,DebeDetalle,HaberDetalle,Debe,Haber,Descuento_Debe,Descuento_Haber,pha.Movimientos_idMovimientos,ac.Fecha, pha.Iva10,pha.Iva5,pha.Totaliva,ac.Diferencia,ac.Factura_Compra_idFactura_Compra,ac.Factura_Venta_idFactura_Venta,ac.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa,ac.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente,ac.Caja_idCaja,Balance_General,Control');
    $this->db->select('Num_factura_Compra, Factura_Compra.Ticket as tc,Factura_Compra.Vuelto as fcv, Factura_Compra.Contado_Credito as fccc');
    $this->db->select('Num_Factura_Venta, Factura_Venta.Ticket as tv,Factura_Venta.Vuelto as fvv, Factura_Venta.Contado_Credito AS fvcc');
    $this->db->select('cce.Num_Recibo as ccen, cce.VueltoE as ccev');
    $this->db->select('ccc.Num_Recibo as cccn, ccc.VueltoC as cccv');
    $this->db->join('Acientos ac', 'pha.Acientos_idAcientos = ac.idAcientos', 'inner');
    $this->db->join('PlandeCuenta', 'pha.PlandeCuenta_idPlandeCuenta = PlandeCuenta.idPlandeCuenta', 'left');
    $this->db->join('Factura_Compra', 'ac.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra', 'left');
    $this->db->join('Factura_Venta', 'ac.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta', 'left');
    $this->db->join('Cuenta_Corriente_Empresa cce', 'ac.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cce.idCuenta_Corriente_Empresa', 'left');
    $this->db->join('Cuenta_Corriente_Cliente ccc', 'ac.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = ccc.idCuenta_Corriente_Cliente', 'left');
    $this->db->where('Fecha',date("Y-m-d"));
    }else{
          $this->db->select('ac.idAcientos,DebeDetalle,HaberDetalle,Debe,Haber,Descuento_Debe,Descuento_Haber,pha.Movimientos_idMovimientos,ac.Fecha, pha.Iva10,pha.Iva5,pha.Totaliva,ac.Diferencia,ac.Factura_Compra_idFactura_Compra,ac.Factura_Venta_idFactura_Venta,ac.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa,ac.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente,ac.Caja_idCaja,Balance_General,Control');
    $this->db->select('Num_factura_Compra, Factura_Compra.Ticket as tc,Factura_Compra.Vuelto as fcv, Factura_Compra.Contado_Credito as fccc');
    $this->db->select('Num_Factura_Venta, Factura_Venta.Ticket as tv,Factura_Venta.Vuelto as fvv, Factura_Venta.Contado_Credito AS fvcc');
    $this->db->select('cce.Num_Recibo as ccen, cce.VueltoE as ccev');
    $this->db->select('ccc.Num_Recibo as cccn, ccc.VueltoC as cccv');
    $this->db->join('Acientos ac', 'pha.Acientos_idAcientos = ac.idAcientos', 'inner');
    $this->db->join('PlandeCuenta', 'pha.PlandeCuenta_idPlandeCuenta = PlandeCuenta.idPlandeCuenta', 'left');
    $this->db->join('Factura_Compra', 'ac.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra', 'left');
    $this->db->join('Factura_Venta', 'ac.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta', 'left');
    $this->db->join('Cuenta_Corriente_Empresa cce', 'ac.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cce.idCuenta_Corriente_Empresa', 'left');
    $this->db->join('Cuenta_Corriente_Cliente ccc', 'ac.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = ccc.idCuenta_Corriente_Cliente', 'left');
    $this->db->where('Fecha',date("Y-m-d"));

    }
        $i = 0;

        foreach ($this->column as $item)
        {
            if($_POST['search']['value'])
                ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
            $column[$i] = $item;
            $i++;
        }

	}
        function get_Aciento()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit('15', $_POST['start']);
        $query = $this->db->get('PlandeCuenta_has_Acientos pha');
        return $query->result();
    }
        function getAciento()
    {
        if ($this->session->userdata('idUsuario') == '1') {
              $this->db->select('ac.idAcientos,DebeDetalle,HaberDetalle,Debe,Haber,Descuento_Debe,Descuento_Haber,pha.Movimientos_idMovimientos,ac.Fecha, pha.Iva10,pha.Iva5,pha.Totaliva,ac.Diferencia,ac.Factura_Compra_idFactura_Compra,ac.Factura_Venta_idFactura_Venta,ac.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa,ac.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente,ac.Caja_idCaja,Balance_General,Control');
        $this->db->select('Num_factura_Compra, Factura_Compra.Ticket as tc,Factura_Compra.Vuelto as fcv, Factura_Compra.Contado_Credito as fccc');
        $this->db->select('Num_Factura_Venta, Factura_Venta.Ticket as tv,Factura_Venta.Vuelto as fvv, Factura_Venta.Contado_Credito AS fvcc');
        $this->db->select('cce.Num_Recibo as ccen, cce.VueltoE as ccev');
        $this->db->select('ccc.Num_Recibo as cccn, ccc.VueltoC as cccv');
        $this->db->join('Acientos ac', 'pha.Acientos_idAcientos = ac.idAcientos', 'inner');
        $this->db->join('PlandeCuenta', 'pha.PlandeCuenta_idPlandeCuenta = PlandeCuenta.idPlandeCuenta', 'left');
        $this->db->join('Factura_Compra', 'ac.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra', 'left');
        $this->db->join('Factura_Venta', 'ac.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta', 'left');
        $this->db->join('Cuenta_Corriente_Empresa cce', 'ac.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cce.idCuenta_Corriente_Empresa', 'left');
        $this->db->join('Cuenta_Corriente_Cliente ccc', 'ac.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = ccc.idCuenta_Corriente_Cliente', 'left');
        $this->db->where('Fecha',date("Y-m-d"));
        }else{
              $this->db->select('ac.idAcientos,DebeDetalle,HaberDetalle,Debe,Haber,Descuento_Debe,Descuento_Haber,pha.Movimientos_idMovimientos,ac.Fecha, pha.Iva10,pha.Iva5,pha.Totaliva,ac.Diferencia,ac.Factura_Compra_idFactura_Compra,ac.Factura_Venta_idFactura_Venta,ac.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa,ac.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente,ac.Caja_idCaja,Balance_General,Control');
        $this->db->select('Num_factura_Compra, Factura_Compra.Ticket as tc,Factura_Compra.Vuelto as fcv, Factura_Compra.Contado_Credito as fccc');
        $this->db->select('Num_Factura_Venta, Factura_Venta.Ticket as tv,Factura_Venta.Vuelto as fvv, Factura_Venta.Contado_Credito AS fvcc');
        $this->db->select('cce.Num_Recibo as ccen, cce.VueltoE as ccev');
        $this->db->select('ccc.Num_Recibo as cccn, ccc.VueltoC as cccv');
        $this->db->join('Acientos ac', 'pha.Acientos_idAcientos = ac.idAcientos', 'inner');
        $this->db->join('PlandeCuenta', 'pha.PlandeCuenta_idPlandeCuenta = PlandeCuenta.idPlandeCuenta', 'left');
        $this->db->join('Factura_Compra', 'ac.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra', 'left');
        $this->db->join('Factura_Venta', 'ac.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta', 'left');
        $this->db->join('Cuenta_Corriente_Empresa cce', 'ac.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cce.idCuenta_Corriente_Empresa', 'left');
        $this->db->join('Cuenta_Corriente_Cliente ccc', 'ac.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = ccc.idCuenta_Corriente_Cliente', 'left');
        $this->db->where('Fecha',date("Y-m-d"));

        }
        $query = $this->db->get('PlandeCuenta_has_Acientos pha');
        return $query->result();
    }

	function count_filtro()
	{
    if ($this->session->userdata('idUsuario') == '1') {
          $this->_get_datatables_query();
        $this->db->where('Fecha',date("Y-m-d"));
        $query = $this->db->get('PlandeCuenta_has_Acientos pha');
        return $query->num_rows();
    }else{
          $this->_get_datatables_query();
        $this->db->where('Fecha',date("Y-m-d"));
        $query = $this->db->get('PlandeCuenta_has_Acientos pha');
        return $query->num_rows();
    }

	}

	public function count_todas()
	{
    if ($this->session->userdata('idUsuario') == '1') {
      $this->db->where('Fecha',date("Y-m-d"));
      $query = $this->db->get('Acientos');
      return $this->db->count_all_results();
    }else{
      $this->db->where('Fecha',date("Y-m-d"));

      $query = $this->db->get('Acientos');
      return $this->db->count_all_results();
    }

	}


    function _get_datatables_query_ ()
  {
          $this->db->select('ac.idAcientos,DebeDetalle,HaberDetalle,Debe,Haber,Descuento_Debe,Descuento_Haber,pha.Movimientos_idMovimientos,ac.Fecha, pha.Iva10,pha.Iva5,pha.Totaliva,ac.Diferencia,ac.Factura_Compra_idFactura_Compra,ac.Factura_Venta_idFactura_Venta,ac.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa,ac.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente,ac.Caja_idCaja,Balance_General,Control');
    $this->db->select('Num_factura_Compra, Factura_Compra.Ticket as tc,Factura_Compra.Vuelto as fcv, Factura_Compra.Contado_Credito as fccc');
    $this->db->select('Num_Factura_Venta, Factura_Venta.Ticket as tv,Factura_Venta.Vuelto as fvv, Factura_Venta.Contado_Credito AS fvcc');
    $this->db->select('cce.Num_Recibo as ccen, cce.VueltoE as ccev');
    $this->db->select('ccc.Num_Recibo as cccn, ccc.VueltoC as cccv');
    $this->db->join('Acientos ac', 'pha.Acientos_idAcientos = ac.idAcientos', 'left');
    $this->db->join('PlandeCuenta', 'pha.PlandeCuenta_idPlandeCuenta = PlandeCuenta.idPlandeCuenta', 'left');
    $this->db->join('Factura_Compra', 'ac.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra', 'left');
    $this->db->join('Factura_Venta', 'ac.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta', 'left');
    $this->db->join('Cuenta_Corriente_Empresa cce', 'ac.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cce.idCuenta_Corriente_Empresa', 'left');
    $this->db->join('Cuenta_Corriente_Cliente ccc', 'ac.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = ccc.idCuenta_Corriente_Cliente', 'left');



        // $i = 0;

        // foreach ($this->column as $item)
        // {
        //     if($_POST['search']['value'])
        //         ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
        //     $column[$i] = $item;
        //     $i++;
        // }


  }
        function get_Aciento_sear($fecha,$caja,$con )
    {

       $this->_get_datatables_query_();
      if (!empty($fecha)) {
        $this->db->where('ac.Fecha',$fecha);
      }
      if (!empty($caja)) {
        $this->db->where('ac.Caja_idCaja',$caja);
      }
      if ($con == 2) {
        $this->db->where('ac.Factura_Compra_idFactura_Compra IS NULL',NULL,false);

      }elseif ($con == 1) {
        $this->db->where('ac.Factura_Venta_idFactura_Venta IS NULL',NULL,false);
      }
        if($_POST['length'] != -1)
        $this->db->limit('15', $_POST['start']);
        $query = $this->db->get('PlandeCuenta_has_Acientos pha');
        return $query->result();
    }


    function getAcientosear($fecha,$caja,$con )
    {

       $this->_get_datatables_query_();
      if (!empty($fecha)) {
        $this->db->where('ac.Fecha',$fecha);
      }
      if (!empty($caja)) {
        $this->db->where('ac.Caja_idCaja',$caja);
      }
      if ($con == 2) {
        $this->db->where('ac.Factura_Compra_idFactura_Compra IS NULL',NULL,false);

      }elseif ($con == 1) {
        $this->db->where('ac.Factura_Venta_idFactura_Venta IS NULL',NULL,false);
      }
        $query = $this->db->get('PlandeCuenta_has_Acientos pha');
        return $query->result();
    }
  function count_filtro_($fecha,$caja,$con)
  {
      $this->_get_datatables_query_();
      if (!empty($fecha)) {
        $this->db->where('ac.Fecha',$fecha);
      }
      if (!empty($caja)) {
        $this->db->where('ac.Caja_idCaja',$caja);
      }
      if ($con == 2) {
        $this->db->where('ac.Factura_Compra_idFactura_Compra IS NULL',NULL,false);

      }elseif ($con == 1) {
        $this->db->where('ac.Factura_Venta_idFactura_Venta IS NULL',NULL,false);
      }
        $query = $this->db->get('PlandeCuenta_has_Acientos pha');
        return $query->num_rows();
  }

  public function count_todas_($fecha,$caja,$con)
  {
      if (!empty($fecha)) {
        $this->db->where('ac.Fecha',$fecha);
      }
      if (!empty($caja)) {
        $this->db->where('ac.Caja_idCaja',$caja);
      }
      if ($con == 2) {
        $this->db->where('ac.Factura_Compra_idFactura_Compra IS NULL',NULL,false);

      }elseif ($con == 1) {
        $this->db->where('ac.Factura_Venta_idFactura_Venta IS NULL',NULL,false);
      }
      $query = $this->db->get('Acientos ac');
      return $this->db->count_all_results();
  }

  public function load_mayor($fecha,$caja,$plan )
  {
    $this->db->join('Acientos ac', 'pha.Acientos_idAcientos = ac.idAcientos', 'left');
    $this->db->join('PlandeCuenta', 'pha.PlandeCuenta_idPlandeCuenta = PlandeCuenta.idPlandeCuenta', 'left');
    $this->db->where('ac.Fecha',$fecha);
      if (!empty($caja)) {
        $this->db->where('ac.Caja_idCaja',$caja);
      }
      if (!empty($plan)) {
        $this->db->where('pha.PlandeCuenta_idPlandeCuenta',$plan);
      }
      $this->db->where('PlandeCuenta_idPlandeCuenta IS NOT NULL');
      $this->db->group_by('PlandeCuenta_idPlandeCuenta');
      $query = $this->db->get('PlandeCuenta_has_Acientos pha');
      if ($query->num_rows() > 0) {
        return $query->result();
      }else {
      // return FALSE;
      }


  }

    function _get_load_balance ($mes,$ano)
  {
  	$this->db->select('SUM(Debe) as debe , SUM(Haber) as haber,Nombre,Balance_General');
    $this->db->join('Acientos ac', 'pha.Acientos_idAcientos = ac.idAcientos', 'left');
    $this->db->join('PlandeCuenta', 'pha.PlandeCuenta_idPlandeCuenta = PlandeCuenta.idPlandeCuenta', 'left');
    $this->db->join('SubPlanCuenta', 'PlandeCuenta.Control = SubPlanCuenta.idSubPlanCuenta', 'left');
      if (!empty($mes)) {
        $this->db->where("MONTH(ac.Fecha)=",$mes);
      }
      if (!empty($ano)) {
        $this->db->where("YEAR( ac.Fecha)=",$ano);
      }
      $this->db->where('PlandeCuenta_idPlandeCuenta IS NOT NULL');
      $this->db->group_by('PlandeCuenta_idPlandeCuenta');
      $this->db->order_by('Control', 'asc');



        $i = 0;

        foreach ($this->column as $item)
        {
            if($_POST['search']['value'])
                ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
            $column[$i] = $item;
            $i++;
        }


  }
        function load_balance($mes,$ano )
    {

       $this->_get_load_balance($mes,$ano);
       if($_POST['length'] != -1)
        $this->db->limit('15', $_POST['start']);
        $query = $this->db->get('PlandeCuenta_has_Acientos pha');
        return $query->result();
    }

            function loadbalance($mes,$ano )
    {

    $this->db->select('SUM(Debe) as debe , SUM(Haber) as haber,Nombre,Balance_General');
    $this->db->join('Acientos ac', 'pha.Acientos_idAcientos = ac.idAcientos', 'left');
    $this->db->join('PlandeCuenta', 'pha.PlandeCuenta_idPlandeCuenta = PlandeCuenta.idPlandeCuenta', 'left');
    $this->db->join('SubPlanCuenta', 'PlandeCuenta.Control = SubPlanCuenta.idSubPlanCuenta', 'left');
      if (!empty($mes)) {
        $this->db->where("MONTH(ac.Fecha)=",$mes);
      }
      if (!empty($ano)) {
        $this->db->where("YEAR( ac.Fecha)=",$ano);
      }
      $this->db->where('PlandeCuenta_idPlandeCuenta IS NOT NULL');
      $this->db->group_by('PlandeCuenta_idPlandeCuenta');
      $this->db->order_by('Control', 'asc');
        $query = $this->db->get('PlandeCuenta_has_Acientos pha');
        return $query->result();
    }

  function count_filtro_balance($mes,$ano)
  {
    $this->db->select('SUM(Debe) as debe , SUM(Haber) as haber,Nombre,Balance_General');
    $this->db->join('Acientos ac', 'pha.Acientos_idAcientos = ac.idAcientos', 'left');
    $this->db->join('PlandeCuenta', 'pha.PlandeCuenta_idPlandeCuenta = PlandeCuenta.idPlandeCuenta', 'left');
    $this->db->join('SubPlanCuenta', 'PlandeCuenta.Control = SubPlanCuenta.idSubPlanCuenta', 'left');
      if (!empty($mes)) {
        $this->db->where("MONTH(ac.Fecha)=",$mes);
      }
      if (!empty($ano)) {
        $this->db->where("YEAR( ac.Fecha)=",$ano);
      }
      $this->db->where('PlandeCuenta_idPlandeCuenta IS NOT NULL');
      $this->db->group_by('PlandeCuenta_idPlandeCuenta');
      $this->db->order_by('Control', 'asc');
       $query = $this->db->get('PlandeCuenta_has_Acientos pha');
        return $query->num_rows();
  }

  public function count_todas_balance($mes,$ano)
  {
  	$this->db->select('SUM(Debe) as debe , SUM(Haber) as haber');
    $this->db->join('Acientos ac', 'pha.Acientos_idAcientos = ac.idAcientos', 'left');
    $this->db->join('PlandeCuenta', 'pha.PlandeCuenta_idPlandeCuenta = PlandeCuenta.idPlandeCuenta', 'left');
    $this->db->join('SubPlanCuenta', 'PlandeCuenta.Control = SubPlanCuenta.idSubPlanCuenta', 'left');
      if (!empty($mes)) {
        $this->db->where("MONTH(ac.Fecha)=",$mes);
      }
      if (!empty($ano)) {
        $this->db->where("YEAR( ac.Fecha)=",$ano);
      }
      $this->db->where('PlandeCuenta_idPlandeCuenta IS NOT NULL');
      $this->db->group_by('PlandeCuenta_idPlandeCuenta');
      $query = $this->db->get('PlandeCuenta_has_Acientos pha');
      return $this->db->count_all_results();
  }

  public function load_calculo($planid,$ano,$mes)
  {
  	$this->db->select('SUM(Debe) as debe , SUM(Haber) as haber,Nombre,Balance_General');
  	$this->db->where('PlandeCuenta_idPlandeCuenta', $planid);
  	$query = $this->db->get('PlandeCuenta_has_Acientos');
  	return $query->result();
  }

}



/* End of file Cobro_Model.php */
/* Location: ./application/models/Cobro_Model.php */ ?>