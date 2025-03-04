<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Venta
 * excel
 */
class Reporte_exel extends CI_Controller {
    const SELECT_PAGADA =
    'cr.Num_Cuota as Num_cuota,
    Cargos_Envios,
    idCuenta_Corriente_Cliente as id,
    cr.Num_Recibo,
    fa.Monto_Total as monto_totales,
    fa.Cliente_idCliente as idCliente,
    fa.idFactura_Venta,
    cr.Importe as inporte_total,
    cr.Fecha_Ven,
    cr.idCuenta_Corriente_Cliente,
    cr.Estado as crestado,
    cl.Nombres,
    cl.Apellidos,
    cl.Limite_max_Credito,
    fa.idFactura_Venta,
    fa.Tipo_Venta,
    fa.Contado_Credito,Tipo_Venta,
    fa.Estado as esta,fa.Num_Factura_Venta'
    ;
    public function __construct () {
        parent::__construct();
         
        // inicializamos la librería
        $this->load->library('Classes/PHPExcel.php');
    }
    // end: construc
     
	public function header_exel($value='')
	{
	// configuramos las propiedades del documento
				$this->phpexcel->getProperties()->setCreator("Arkos Noem Arenom")
								 ->setLastModifiedBy("Arkos Noem Arenom")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Test result file");
	}

	public function output_exel($nombre='',$fecha='')
	{
			// configuramos el documento para que la hoja
			// de trabajo número 0 sera la primera en mostrarse
			// al abrir el documento
			$this->phpexcel->setActiveSheetIndex(0);
			
			
			// redireccionamos la salida al navegador del cliente (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$nombre.'_'.$fecha.'.xlsx"');
			header('Cache-Control: max-age=0');
			
			$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');
			$objWriter->save('php://output');
	}

	
	public function style_horizontal($value='')
	{
   		$styleArray = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        ),
		    'borders' => array(
		        'top' => array(
		            'style' => PHPExcel_Style_Border::BORDER_THIN,
		        ),
		    ),
		    'font' => array('size' => 15,'bold' => true,'color' => array('rgb' => ''))
		);
	    return $styleArray;
	}


	public function style_cuerpo($value='')
	{
   		$styleArray = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        ),
		    'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => ''))
		);
	    return $styleArray;
	} 


	public function style_list($value='')
	{
   		$styleArray = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        ),
		    'font' => array('size' => 11,'bold' => false,'color' => array('rgb' => ''))
		);
	    return $styleArray;
	} 


   public function cabecera($nombre,$convinacion)
   {
					    $this->db->select('*');
					    $query = $this->db->get('Empresa');
					    $row = $query->row();
   	                    $this->phpexcel->getActiveSheet()->getStyle($convinacion)->applyFromArray($this->style_horizontal());
    					$this->phpexcel->getActiveSheet()->mergeCells($convinacion);
    					$this->phpexcel->getActiveSheet()->getRowDimension($convinacion)->setRowHeight(30);
						$this->phpexcel->setActiveSheetIndex(0)
						     ->setCellValue('A1', $row->Nombre.PHP_EOL. $nombre);
						$this->phpexcel->getActiveSheet()
							 ->getStyle('A1')
							 ->getAlignment()
							 ->setWrapText(true);

   }

   

   public function styleHead($value='')
   {
   		if (!empty($value)) {
   			$color = $value;
   		}else{
   			$color = 'FFA0A0A0';

   		}
		$styleArray = array(
	         'font' => array(
	         	'size' => 12,
	         	'bold' => true,
	         	'color' => array('rgb' => '')
	         ),

	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        ),
		    'borders' => array(
		        'top' => array(
		            'style' => PHPExcel_Style_Border::BORDER_THIN,
		        ),
		    ),

		    'fill' => array(
		        'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
		        'rotation' => 90,
		        'startcolor' => array(
		            'argb' => $color,
		        ),
		        'endcolor' => array(
		            'argb' => 'FFFFFFFF',
		        ),
		    ),
		);
		return$styleArray;

   }
	function caja($id='')
	{


		$this->load->model('Caja_Model','Caja');
		$rowuser = $this->Caja->load_user($id);
		$list = $this->Caja->get_caja($id);
		if( !empty( $list ) )
		{

						$this->header_exel();
						$this->cabecera('Listado de Movimiento Segun Caja Nº '.$id,'A1:F1');		
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Usuario')
						            ->setCellValue('B2', 'Descripción')
						            ->setCellValue('C2', 'Fecha Hora')
						            ->setCellValue('D2', 'Ingreso')
						            ->setCellValue('E2', 'Egreso')
						            ->setCellValue('F2', 'Total');
						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);



						$x = 3;
						$xx = 2;

						$recordsFiltered = $this->Caja->count_filter($id);
						$monto_inicial  = $this->monto_inicial($id);
						$haber = 0;
						$debe = 0;
						$total = 0;
						$as = '';
						$monto_inicial =  str_replace($this->config->item('caracteres'),"",$monto_inicial);
						foreach ($list as $caja) {
							$this->phpexcel->getActiveSheet()->getRowDimension( $x)->setRowHeight(20);

							$resultadohaber = str_replace($this->config->item('caracteres'),"",$caja->haber);
							$resultadodebe  = str_replace($this->config->item('caracteres'),"",$caja->debe);
							$haber          +=$resultadohaber;
							$debe           +=$resultadodebe;

							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('A'.$x, $rowuser->Usuario)
							->setCellValue('B'.$x, $caja->descripcion)
							->setCellValue('C'.$x, $caja->fecha);
							if ($caja->debe > 0) {
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('D'.$x,number_format($caja->debe,0,',',''));
							}else {
							$this->phpexcel->setActiveSheetIndex(0)
                             ->setCellValue('D'.$x, $caja->debe);
								}
							if ($caja->haber > 0) {
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('E'.$x, number_format($caja->haber,0,',',''));
								}else {
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('E'.$x,$caja->haber);
							}
							// $this->phpexcel->getActiveSheet()->setCellValue('F'.$x,'=SUMA(E3,D3)');
							////////////////////////////
						$x++;
						}

						$val = $x;
						$this->phpexcel->getActiveSheet()->getRowDimension( $val)->setRowHeight(20);
						// $this->phpexcel->getActiveSheet()->getStyle('F'.$val)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
								if ($haber < $debe) {
									$as = $debe - $haber;
									$total =  number_format($as,0,',',',');
									for ($i = 0; $i <1 ; $i++) {
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('E'.$val, 'Parcial')
										->setCellValue('F'.$val, $total);
									}
								} else {
									$as = $debe - $haber;
									$total =  number_format($as,0,',',',');
									for ($i = 0; $i <1 ; $i++) {
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('E'.$val, 'Parcial')
										->setCellValue('F'.$val, $total);

									}

								} 

						$val = $x+1;
						$va2 = $x+2;
							$this->phpexcel->getActiveSheet()->getRowDimension( $va2)->setRowHeight(20);
						// $this->phpexcel->getActiveSheet()->getStyle('F'.$va2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

						if ($monto_inicial < $as) {
							$monto_final =  number_format($as + $monto_inicial,0,',',',');
										$this->phpexcel->setActiveSheetIndex(0)
							            ->setCellValue('E'.$val, 'Inicial')
							            ->setCellValue('F'.$val, number_format($monto_inicial,0,',',','))
							            ->setCellValue('E'.$va2, 'Total')
							            ->setCellValue('F'.$va2, $monto_final);

						} else {
							$monto_final =  number_format($monto_inicial + $as,0,',',',');
										$this->phpexcel->setActiveSheetIndex(0)
							            ->setCellValue('E'.$val, 'Inicial')
							            ->setCellValue('F'.$val, number_format($monto_inicial,0,',',','))      
							            ->setCellValue('E'.$va2, 'Total')
							            ->setCellValue('F'.$va2, $monto_final);

						}
						$this->phpexcel->getActiveSheet()->getStyle('E'.$va2.':F'.$va2)->applyFromArray($this->styleHead('F93107'));	


						
						// Renombramos la hoja de trabajo
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('Caja'.$id);
						// salida#]
						$this->output_exel('Caja',$id );

						// La variable $x se utiliza para mostrar un número consecutivo
		} else {
				$data     = array(
				'titulo'  => 'No existen datos de busqueda',
				'titulo2' => 'Listado caja '.$id,
				'titulo3' => 'No existen datos', );
				$this->load->view('Error/error_exel.php', $data, FALSE);
		}
	}

	function Caja_list($id="")
	{

						$this->load->model('Caja_Model','Caja');
						$list = $this->Caja->get_caja($id);
						if( !empty( $list ) ){

						$this->header_exel();
						$this->cabecera('Listado de Movimiento  Caja','A1:F1');

					
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Usuario')
						            ->setCellValue('B2', 'Descripción')
						            ->setCellValue('C2', 'Fecha Hora')
						            ->setCellValue('D2', 'Ingreso')
						            ->setCellValue('E2', 'Egreso')
						            ->setCellValue('F2', 'Total');
						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);


						$x = 3;
						$xx = 2;

						$recordsFiltered = $this->Caja->count_filter($id);
						$monto_inicial  = $this->monto_inicial($id);
						$haber = 0;
						$debe = 0;
						$total = 0;
						$as = '';
						$monto_inicial =  str_replace($this->config->item('caracteres'),"",$monto_inicial);
						foreach ($list as $caja) {
							$this->db->select('Usuario');
							$this->db->join('Usuario', 'Caja.Usuario_idUsuario = Usuario.idUsuario', 'inner');
							$this->db->where('idCaja', $caja->Caja_idCaja);
							$query = $this->db->get('Caja');
							$row = $query->row();
							$this->phpexcel->getActiveSheet()->getRowDimension( $x)->setRowHeight(20);

							$resultadohaber = str_replace($this->config->item('caracteres'),"",$caja->haber);
							$resultadodebe  = str_replace($this->config->item('caracteres'),"",$caja->debe);
							$haber          +=$resultadohaber;
							$debe           +=$resultadodebe;

							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('A'.$x, $row->Usuario)
							->setCellValue('B'.$x, $caja->descripcion)
							->setCellValue('C'.$x, $caja->fecha);
							if ($caja->debe > 0) {
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('D'.$x,number_format($caja->debe,0,',',''));
							}else {
							$this->phpexcel->setActiveSheetIndex(0)
                             ->setCellValue('D'.$x, $caja->debe);
								}
							if ($caja->haber > 0) {
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('E'.$x, number_format($caja->haber,0,',',''));
								}else {
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('E'.$x,$caja->haber);
							}
							////////////////////////////
						$x++;
						}

						$val = $x;
						$this->phpexcel->getActiveSheet()->getRowDimension( $val)->setRowHeight(20);
						// $this->phpexcel->getActiveSheet()->getStyle('F'.$val)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
								if ($haber < $debe) {
									$as = $debe - $haber;
									$total =  number_format($as,0,',',',');
									for ($i = 0; $i <1 ; $i++) {
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('E'.$val, 'Parcial')
										->setCellValue('F'.$val, $total);
									}
								} else {
									$as = $debe - $haber;
									$total =  number_format($as,0,',',',');
									for ($i = 0; $i <1 ; $i++) {
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('E'.$val, 'Parcial')
										->setCellValue('F'.$val, $total);

									}

								} 

						$val = $x+1;
						$va2 = $x+2;
							$this->phpexcel->getActiveSheet()->getRowDimension( $va2)->setRowHeight(20);
						// $this->phpexcel->getActiveSheet()->getStyle('F'.$va2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

						if ($monto_inicial < $as) {
							$monto_final =  number_format($as + $monto_inicial,0,',',',');
										$this->phpexcel->setActiveSheetIndex(0)
							            ->setCellValue('E'.$val, 'Inicial')
							            ->setCellValue('F'.$val, number_format($monto_inicial,0,',',','))
							            ->setCellValue('E'.$va2, 'Total')
							            ->setCellValue('F'.$va2, $monto_final);

						} else {
							$monto_final =  number_format($monto_inicial + $as,0,',',',');
										$this->phpexcel->setActiveSheetIndex(0)
							            ->setCellValue('E'.$val, 'Inicial')
							            ->setCellValue('F'.$val, number_format($monto_inicial,0,',',','))      
							            ->setCellValue('E'.$va2, 'Total')
							            ->setCellValue('F'.$va2, $monto_final);

						}
						$this->phpexcel->getActiveSheet()->getStyle('E'.$va2.':F'.$va2)->applyFromArray($this->styleHead('F93107'));	



						
						// Renombramos la hoja de trabajo
						$fecha = date("Y-m-d");
							$this->phpexcel->getActiveSheet()->setTitle('Caja'.$fecha);
						// salida#]
							$this->output_exel('Caja',$fecha );

						// La variable $x se utiliza para mostrar un número consecutivo
		} else {
				$data     = array(
				'titulo'  => 'No existen datos de busqueda',
				'titulo2' => $nombre,
				'titulo3' => 'No existen datos', );
				$this->load->view('Error/error_exel.php', $data, FALSE);
		}
	}


    	function Producto($id='')
	{
			$nombre = 'Listados de Productos';
						$this->load->model('Producto_Model','Producto');
						$list = $this->Producto->getproducto();
						// echo var_dump($list);
						if( !empty( $list ) )
						{
						$this->header_exel();
						$this->cabecera($nombre,'A1:F1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Codigo')
						            ->setCellValue('B2', 'Nombre')
						            ->setCellValue('C2', 'Precio')
						            ->setCellValue('D2', 'Cantidad')
						            ->setCellValue('E2', 'Categoria')
						            ->setCellValue('F2', 'Iva');
						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
						$this->phpexcel->getActiveSheet()->setShowGridlines(true);

								$x = 3;								
								foreach ($list as $key => $Producto) {

						        $this->phpexcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);
                                $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':F'.$x)->applyFromArray($this->style_cuerpo());
									$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 10); 
									$Precio_Venta =  number_format($resultado,0,',',',');
									$total = $Producto->Cantidad_A + $Producto->Cantidad_D;
									$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('A'.$x, $Producto->Codigo)
										->setCellValue('B'.$x, $Producto->Nombre)
										->setCellValue('C'.$x, $Precio_Venta)
      	                                ->setCellValue('D'.$x, $total)
										->setCellValue('E'.$x, $Producto->Categoria);
										if ($Producto->Iva ==0) {
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('F'.$x, 'Excenta');
											}else {
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('F'.$x,$Producto->Iva);
										}
									 	$x ++;		

								}
						// Renombramos la hoja de trabajo
						$fecha = date("Y-m-d");
							$this->phpexcel->getActiveSheet()->setTitle('Productos'.$fecha);
						// salida#]
							$this->output_exel('Productos',$fecha );

						} else {
								$data     = array(
								'titulo'  => 'No existen datos de busqueda',
								'titulo2' => $nombre,
								'titulo3' => 'No existen datos', );
                            	$this->load->view('Error/error_exel.php', $data, FALSE);
						}

	}
    	function listatodo($id='')
	{
			if (empty($id)) {
				$nombre = 'Listado Total de Productos';
			}else{
				$nombre = 'Listados de Productos Con Alertas de Stock';
			}
						$this->load->model('Producto_Model','Producto');
						$list = $this->Producto->getproducto();
						// echo var_dump($list);
						if( !empty( $list ) )
						{
						$this->header_exel();
						$this->cabecera($nombre,'A1:F1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Codigo')
						            ->setCellValue('B2', 'Nombre')
						            ->setCellValue('C2', 'Precio')
						            ->setCellValue('D2', 'Cantidad')
						            ->setCellValue('E2', 'Categoria')
						            ->setCellValue('F2', 'Iva');
						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
						$this->phpexcel->getActiveSheet()->setShowGridlines(true);

								$x = 3;								
								foreach ($list as $key => $Producto) {
							    if (!empty($id)) {
							    	if (10>($Producto->Cantidad_A + $Producto->Cantidad_D)) {
											$this->phpexcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);
											$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':F'.$x)->applyFromArray($this->style_cuerpo());
											$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 10); 
											$Precio_Venta =  number_format($resultado,0,',',',');
											$total = $Producto->Cantidad_A + $Producto->Cantidad_D;
											$this->phpexcel->setActiveSheetIndex(0)
											->setCellValue('A'.$x, $Producto->Codigo)
											->setCellValue('B'.$x, $Producto->Nombre)
											->setCellValue('C'.$x, $Precio_Venta)
											->setCellValue('D'.$x, $total)
											->setCellValue('E'.$x, $Producto->Categoria);
											if ($Producto->Iva ==0) {
											$this->phpexcel->setActiveSheetIndex(0)
											->setCellValue('F'.$x, 'Excenta');
											}else {
											$this->phpexcel->setActiveSheetIndex(0)
											->setCellValue('F'.$x,$Producto->Iva);
											}
									}
							    	
							    }else{
									$this->phpexcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);
									$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':F'.$x)->applyFromArray($this->style_cuerpo());
									$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 10); 
									$Precio_Venta =  number_format($resultado,0,',',',');
									$total = $Producto->Cantidad_A + $Producto->Cantidad_D;
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $Producto->Codigo)
									->setCellValue('B'.$x, $Producto->Nombre)
									->setCellValue('C'.$x, $Precio_Venta)
									->setCellValue('D'.$x, $total)
									->setCellValue('E'.$x, $Producto->Categoria);
									if ($Producto->Iva ==0) {
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('F'.$x, 'Excenta');
									}else {
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('F'.$x,$Producto->Iva);
									}
							    }

	
									 	$x ++;		

								}
						// Renombramos la hoja de trabajo
						$fecha = date("Y-m-d");
							$this->phpexcel->getActiveSheet()->setTitle('Productos'.$fecha);
						// salida#]
							$this->output_exel('Productos',$fecha );

						} else {
								$data     = array(
								'titulo'  => 'No existen datos de busqueda',
								'titulo2' => $nombre,
								'titulo3' => 'No existen datos', );
                            	$this->load->view('Error/error_exel.php', $data, FALSE);
						}

	}

	/**
	 * @param string
	 */
	public function Oventa($value='')
	{
	$nombre = 'Listados de Orden de Venta';
				// Se obtienen los clientes de la base de datos
		       $this->load->model("Ordenventa_Model",'Orden_V');
				$list = $this->Orden_V->get_oventa($value);
				if( !empty( $list ) )
				{
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'cliente')
						            ->setCellValue('B2', 'Telefono')
						            ->setCellValue('C2', 'Entrega')
						            ->setCellValue('D2', 'Estado')
						            ->setCellValue('E2', 'Monto');
						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 3;
						foreach ($list as $key => $orden_v) {
						$resultado = intval(preg_replace('/[^0-9]+/', '', $orden_v->Monto_Estimado), 10); 
						$resultado2 = intval(preg_replace('/[^0-9]+/', '', $orden_v->Monto_envio), 10); 
						$Monto =  number_format($resultado+$resultado2,0,',',',');
									$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('A'.$x, $orden_v->Nombres)
										->setCellValue('B'.$x,$orden_v->tel)
										->setCellValue('C'.$x, $orden_v->Entrega)
      	                                ->setCellValue('D'.$x, $orden_v->Estado)
										->setCellValue('E'.$x, $Monto);
						$x ++;	
						}
						$fecha = date("Y-m-d");
							$this->phpexcel->getActiveSheet()->setTitle('orden_Vernta'.$fecha);
						// salida#]
							$this->output_exel('orden_Vernta',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function Ovent($id='')
	{
	$nombre = 'Orden de Venta';
		
		         $this->load->model("Ordenventa_Model",'Orden_V');
		       	$this->load->library('Cart');
				$this->Orden_V->ver_detalles(array('Orden_idOrden' => $id ),$id );
		       	$list = $this->Orden_V->get_oventa($id);

				if( !empty( $list ) )
				{
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						///////////////////////////////////////////
						 $this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->style_cuerpo());

	    				foreach ($list as $key => $value) {
						$this->phpexcel->setActiveSheetIndex(0)
						     ->setCellValue('A2', 'Usuario: '.$value->user)
						     ->setCellValue('B2', 'Cliente: '.$value->Nombres)
						     ->setCellValue('C2', '')
						     ->setCellValue('D2', 'Telefono: '.$value->tel)
						     ->setCellValue('E2', 'Orden: #00'.$value->idOrden);

	    				}


						$this->phpexcel->getActiveSheet()
							 ->getStyle('A2')
							 ->getAlignment()
							 ->setWrapText(true);
						/////////////////////////////////////////

						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A3', 'Codigo')
						            ->setCellValue('B3', 'Producto')
						            ->setCellValue('C3', 'Cantidad')
						            ->setCellValue('D3', 'Precio')
						            ->setCellValue('E3', 'Subtotal');
						$this->phpexcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$x = 4;
						$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
		                foreach ($this->cart->contents() as $items) {
		                        foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
		                                        $iva =  $option_value;
		                     }
											$this->phpexcel->setActiveSheetIndex(0)
												->setCellValue('A'.$x, $items['id'])
												->setCellValue('B'.$x,$items['name'])
												->setCellValue('C'.$x,$items['qty'])
		      	                                ->setCellValue('D'.$x,$this->cart->format_number($items['price']))
												->setCellValue('E'.$x, $this->cart->format_number($items['subtotal']));


		                 $x++;
		                }
		                $val = $x+1;
		   				$res = $val+1;
								foreach ($list as $key => $value) {
									$total = $value->Monto_envio + $value->Monto_Estimado;
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$val,'Envio: '.$value->Monto_envio)
									->setCellValue('E'.$res, 'Monto total: '.number_format($total,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$val)->applyFromArray($this->styleHead('75362A'));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$res)->applyFromArray($this->styleHead('F93107'));


								}
						$fecha = date("Y-m-d");
							$this->phpexcel->getActiveSheet()->setTitle('orden_Vernta'.$fecha);
						// salida#]
							$this->output_exel('orden_Vernta',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function Ocompra($value='')
	{
	$nombre = 'Listados de Orden de Compra';

				// Se obtienen los clientes de la base de datos
		        $this->load->model("Orden_Model",'Orden');
				$list = $this->Orden->get_compra($value);
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:F1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'cliente')
						            ->setCellValue('B2', 'Telefono')
						            ->setCellValue('C2', 'Entrega')
						            ->setCellValue('D2', 'Devolución')
						            ->setCellValue('E2', 'Estado')
						            ->setCellValue('F2', 'Monto');
						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

					    $x = 3;
						foreach ($list as $key => $orden_v) {
						$resultado = intval(preg_replace('/[^0-9]+/', '', $orden_v->Monto_Estimado), 10); 
						$Monto =  number_format($resultado,0,',',',');
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('A'.$x, $orden_v->Razon_Social)
										->setCellValue('B'.$x, $orden_v->tel)
										->setCellValue('C'.$x, $orden_v->Entrega)
      	                                ->setCellValue('D'.$x, $orden_v->Devolucion)
      	                                ->setCellValue('E'.$x, $orden_v->Estado)
      	                                ->setCellValue('F'.$x, $Monto);
						$x ++;	
						}
	
						$fecha = date("Y-m-d");
							$this->phpexcel->getActiveSheet()->setTitle('Orden_compra'.$fecha);
							$this->output_exel('Orden_compra',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function Orden($value='')
	{
	$nombre = 'Orden de Compra';

				// Se obtienen los clientes de la base de datos
		        $this->load->model("Orden_Model",'Orden');
				$list = $this->Orden->get_compra($value);
		       	$this->load->library('Cart');
				$this->Orden->ver_detalles(array('Orden_idOrden' => $value ),$value );
				if( !empty( $list ) ){

						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						///////////////////////////////////////////
						 $this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->style_cuerpo());

	    				foreach ($list as $key => $value) {
						$this->phpexcel->setActiveSheetIndex(0)
						     ->setCellValue('A2', 'Usuario: '.$value->user)
						     ->setCellValue('B2', 'Proveedor: '.$value->Razon_Social)
						     ->setCellValue('C2', '')
						     ->setCellValue('D2', 'Telefono: '.$value->tel)
						     ->setCellValue('E2', 'Orden: #00'.$value->idOrden);

	    				}


						$this->phpexcel->getActiveSheet()
							 ->getStyle('A2')
							 ->getAlignment()
							 ->setWrapText(true);
						/////////////////////////////////////////

						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A3', 'Codigo')
						            ->setCellValue('B3', 'Producto')
						            ->setCellValue('C3', 'Cantidad')
						            ->setCellValue('D3', 'Precio')
						            ->setCellValue('E3', 'Subtotal');
						$this->phpexcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$x = 4;
						$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
		                foreach ($this->cart->contents() as $items) {
		                        foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
		                                        $iva =  $option_value;
		                     }
											$this->phpexcel->setActiveSheetIndex(0)
												->setCellValue('A'.$x, $items['id'])
												->setCellValue('B'.$x,$items['name'])
												->setCellValue('C'.$x,$items['qty'])
		      	                                ->setCellValue('D'.$x,$this->cart->format_number($items['price']))
												->setCellValue('E'.$x, $this->cart->format_number($items['subtotal']));


		                 $x++;
		                }
		                $val = $x+1;
								foreach ($list as $key => $value) {
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$val, 'Monto total: '.number_format($value->Monto_Estimado,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$val)->applyFromArray($this->styleHead('F93107'));


								}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('orden_Vernta'.$fecha);
						$this->output_exel('orden_Vernta',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function remision($value='')
	{
	$nombre = 'Listados de Notas de Remision';

				// Se obtienen los clientes de la base de datos
		       $this->load->model("Ordenventa_Model",'Orden_V');
				$list = $this->Orden_V->getremision();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Razon Social')
						            ->setCellValue('B2', 'Fecha')
						            ->setCellValue('C2', 'Condicion')
						            ->setCellValue('D2', 'Monto');

						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);


					    $x = 3;
						foreach ($list as $key => $orden_v) {
						$resultado = intval(preg_replace('/[^0-9]+/', '', $orden_v->Monto_Estimado), 10); 
						$Monto =  number_format($resultado,0,',',',');
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('A'.$x, $orden_v->Nombres)
										->setCellValue('B'.$x, $orden_v->Entrega);
										$val;
										switch ($orden_v->Compra_Venta) {
										case '3':
											$val='Nota de Entrada Productos ';
											break;
										case '4':
											$val='Nota de Salida Productos';
											break;
										case '5':
											$val='Nota de Devolucion Productos';
											break;
										case '6':
											$val='Entrada Productos En Produccion ';
											break;
										case '7':
											$val='Sin Accion';
											break;
										}
										$this->phpexcel->setActiveSheetIndex(0)										
										->setCellValue('C'.$x, $val)
      	                                ->setCellValue('D'.$x, $Monto);
;
						$x ++;	
						}
	
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('Remision'.$fecha);
						$this->output_exel('Remision',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function lisremision($value='')
	{
				$nombre = 'Notas de Remision';

				// Se obtienen los clientes de la base de datos
		        $this->load->model("Orden_Model",'Orden');
				$list = $this->Orden->get_compra($value);
		       	$this->load->library('Cart');
				$this->Orden->ver_detalles(array('Orden_idOrden' => $value ),$value );
				if( !empty( $list ) ){

						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						///////////////////////////////////////////
						 $this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->style_cuerpo());

	    				foreach ($list as $key => $value) {
						$this->phpexcel->setActiveSheetIndex(0)
						     ->setCellValue('A2', 'Usuario: '.$value->user)
						     ->setCellValue('B2', 'Proveedor: '.$value->Razon_Social)
						     ->setCellValue('C2', '')
						     ->setCellValue('D2', 'Telefono: '.$value->tel)
						     ->setCellValue('E2', 'Orden: #00'.$value->idOrden);

	    				}


						$this->phpexcel->getActiveSheet()
							 ->getStyle('A2')
							 ->getAlignment()
							 ->setWrapText(true);
						/////////////////////////////////////////

						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A3', 'Codigo')
						            ->setCellValue('B3', 'Producto')
						            ->setCellValue('C3', 'Cantidad')
						            ->setCellValue('D3', 'Precio')
						            ->setCellValue('E3', 'Subtotal');
						$this->phpexcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$x = 4;
						$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
		                foreach ($this->cart->contents() as $items) {
		                        foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
		                                        $iva =  $option_value;
		                     }
											$this->phpexcel->setActiveSheetIndex(0)
												->setCellValue('A'.$x, $items['id'])
												->setCellValue('B'.$x,$items['name'])
												->setCellValue('C'.$x,$items['qty'])
		      	                                ->setCellValue('D'.$x,$this->cart->format_number($items['price']))
												->setCellValue('E'.$x, $this->cart->format_number($items['subtotal']));


		                 $x++;
		                }
		                $val = $x+1;
								foreach ($list as $key => $value) {
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$val, 'Monto total: '.number_format($value->Monto_Estimado,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$val)->applyFromArray($this->styleHead('F93107'));


								}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('Remision_detalle'.$fecha);
						$this->output_exel('Remision_detalle',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function Deuda_e($value='')
	{
				$nombre = 'Listados Deuda Empresa';
				// Se obtienen los clientes de la base de datos
		        $this->load->model("Deuda_empresa_Model",'Deuda');
				$list = $this->Deuda->getdeuda();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Cuota Pendiente')
						            ->setCellValue('B2', 'Proveedor')
						            ->setCellValue('C2', 'Monto Total')
						            ->setCellValue('D2', 'Pago Parcial')
						            ->setCellValue('E2', 'Monto Pendiente');
						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);				

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
					    $x = 3;
					  
						foreach ($list as $key => $Deuda) {
						$Parcial_todo = $this->sum_pagos_tods($Deuda->idCuenta_Corriente_Empresa);
						$xx =  round($Deuda->inporte_total) ;
						$mpendiente = round( $xx - $Parcial_todo) ;
  						$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
			    		if ($Deuda->Num_cuota == 1 ) {
							if ($mpendiente > 0)
							{
							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x,$Deuda->Num_cuota)
								->setCellValue('B'.$x, $this->mi_libreria->getSubString($Deuda->Razon_Social, 25).' ('.$this->mi_libreria->getSubString($Deuda->Vendedor, 25).')')
								->setCellValue('C'.$x, number_format($xx,0,',',','))
								->setCellValue('D'.$x, number_format($Parcial_todo,0,',',','))
								->setCellValue('E'.$x, number_format($mpendiente,0,',',','));
							}
						}else{
							if ($Deuda->Num_cuota == 0) {
							    $this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x,'1');
							}else{
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x,$Deuda->Num_cuota);
							}
							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x, $this->mi_libreria->getSubString($Deuda->Razon_Social, 25).' ('.$this->mi_libreria->getSubString($Deuda->Vendedor, 25).')')
								->setCellValue('C'.$x, number_format($xx,0,',',','))
								->setCellValue('D'.$x, number_format($Parcial_todo,0,',',','))
								->setCellValue('E'.$x, number_format($mpendiente,0,',',','));
						}

						$x ++;	

						}
	
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('Deuda_Empresa'.$fecha);
						$this->output_exel('Deuda_Empresa',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function lisdeuda($id)
	{
				$nombre = 'Listado Detallado Deuda Empresa';
				// Se obtienen los clientes de la base de datos
		        $this->load->model("Deuda_empresa_Model",'Deuda');
				$list = $this->Deuda->get_Deudalist($id);

				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );


						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Cuota Nº')
						            ->setCellValue('B2', 'Comprovante')
						            ->setCellValue('C2', 'Provvedor')
						            ->setCellValue('D2', 'Importe a Pagar')
						            ->setCellValue('E2', 'Vencimiento');
						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$x = 4;
						$sum = 0;

		                foreach ($list as $key => $Deuda) {
						$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
		                $Parcial_todo = $this->Deuda->sum_pagos_($Deuda->idCuenta_Corriente_Empresa);
						$xx =  round($Deuda->inporte_total) ;
						$mpendiente =  $Deuda->inporte_total - $Parcial_todo ;
											$this->phpexcel->setActiveSheetIndex(0)
												->setCellValue('A'.$x, $Deuda->Num_cuota)
												->setCellValue('B'.$x,'Recibo Nº '. $Deuda->Num_Recibo)
												->setCellValue('C'.$x,$this->mi_libreria->getSubString($Deuda->Razon_Social, 25).' ('.$this->mi_libreria->getSubString($Deuda->Vendedor, 25).')')
		      	                                ->setCellValue('D'.$x,number_format($mpendiente,0,',',','))
												->setCellValue('E'.$x, $Deuda->Fecha_Ven);
												$sum += $Deuda->inporte_total - $Parcial_todo;

		                 $x++;
		                }
		                $val = $x+1;

									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$val, 'Monto total: '.number_format($sum,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$val)->applyFromArray($this->styleHead('F93107'));

						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('lista_deuda'.$fecha);
						$this->output_exel('lista_deuda',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function pagadas()
	{
				$nombre = 'Lista de Deudas Pagadas';
				// Se obtienen los clientes de la base de datos
		       $this->load->model("Deuda_empresa_Model",'Deuda');
				$list = $this->Deuda->get_Deuda_pagads();
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:F1' );


						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Cuota Nº')
						            ->setCellValue('B2', 'Comprovante')
						            ->setCellValue('C2', 'Provvedor')
						            ->setCellValue('D2', 'Importe a Pagar')
						            ->setCellValue('E2', 'Monto Pagado')
						            ->setCellValue('F2', 'Monto Pendiente');

						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

						$x = 4;
						$sum = 0;

		                foreach ($list as $key => $Deuda) {
							$Parcial_todo = $this->Deuda->sum_pagos_($Deuda->id);
							$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':F'.$x)->applyFromArray($this->style_list());
							if ($Deuda->inporte_total > $Parcial_todo  ) {
								$mpendiente =  number_format($Deuda->inporte_total - $Parcial_todo,0,',',',') ;
							}else{
								$mpendiente =  '';
							}
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, $Deuda->Num_cuota);
							if ($Deuda->Tipo_Compra == 0 ) { // voleta
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x, 'Recibo Nº '. $Deuda->Num_Recibo);
							}elseif ($Deuda->Tipo_Compra == 1 ) { // factura
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x, 'Recibo Nº '. $Deuda->Num_factura_Compra)	;				
							}
											$this->phpexcel->setActiveSheetIndex(0)
												->setCellValue('C'.$x,$this->mi_libreria->getSubString($Deuda->Razon_Social, 25).' ('.$this->mi_libreria->getSubString($Deuda->Vendedor, 25).')')
		      	                                ->setCellValue('D'.$x, number_format($Deuda->inporte_total,0,',',','))
												->setCellValue('E'.$x, number_format($Parcial_todo,0,',',','))
												->setCellValue('F'.$x, $mpendiente);

												$sum += $Parcial_todo;

		                 $x++;
		                }
		                $val = $x+1;

									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$val, 'Monto total: '.number_format($sum,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$val)->applyFromArray($this->styleHead('F93107'));

						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('lista_pagadas'.$fecha);
						$this->output_exel('lista_pagadas',$fecha );

						// if ($mpendiente == 0 && $Deuda->esta != 1) {
						// 	$this->Deuda->Estado_1($Deuda->id);
						// }elseif ($Parcial_todo > 0 && $Deuda->esta != 3) {
						// 	// $this->Deuda->Estado_3($Deuda->id);
						// }elseif ($Parcial_todo == 0 && $Deuda->esta != 0) {
						// 	   $this->Deuda->Estado_0($Deuda->id);
						// }
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function compras($value='')
	{
				$nombre = 'Listado Compras';

				// Se obtienen los clientes de la base de datos
		        $this->load->model("Compra_Model",'Compra');
		        $list = $this->Compra->getCompra();
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Comprobante')
						            ->setCellValue('B2', 'Provvedor')
						            ->setCellValue('C2', 'Fecha')
						            ->setCellValue('D2', 'Estado')
						            ->setCellValue('E2', 'Monto Total');
						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);

						$x = 4;
						$sum = 0;
				foreach ($list as $key => $Compra) {
						$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
				if ($Compra->Estado == 0) {
							if ($Compra->Tipo_Compra == 0) {
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Recibo Nº  '.$Compra->Ticket);
							}else{
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Factura Nº  '. $Compra->Num_factura_Compra);
							}
							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x, $this->mi_libreria->getSubString($Compra->Razon_Social.'-'.$Compra->Vendedor, 50))
								->setCellValue('C'.$x,$Compra->Fecha_expedicion.'  '.$Compra->Hora)
		      	                ->setCellValue('D'.$x, 'Pagado')
								->setCellValue('E'.$x, number_format($Compra->Monto_Total,0,',',','));
												$x ++;
				}elseif ($Compra->Estado == 1) {
							if ($Compra->Tipo_Compra == 0) {
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Recibo Nº  '.$Compra->Ticket);
							}else{
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Factura Nº  '. $Compra->Num_factura_Compra);
							}

							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x, $this->mi_libreria->getSubString($Compra->Razon_Social.'-'.$Compra->Vendedor, 50))
								->setCellValue('C'.$x,$Compra->Fecha_expedicion.'  '.$Compra->Hora)
		      	                ->setCellValue('D'.$x, 'Parcial')
								->setCellValue('E'.$x, number_format($Compra->Monto_Total,0,',',','));
												$x ++;
				}elseif ($Compra->Estado == 2) {
							if ($Compra->Tipo_Compra == 0) {
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Recibo Nº  '.$Compra->Ticket);
							}else{
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Factura Nº  '. $Compra->Num_factura_Compra);
							}

							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x,  $this->mi_libreria->getSubString($Compra->Razon_Social.'-'.$Compra->Vendedor, 50))
								->setCellValue('C'.$x,$Compra->Fecha_expedicion.'  '.$Compra->Hora)
		      	                ->setCellValue('D'.$x, 'No Pagado')
								->setCellValue('E'.$x, number_format($Compra->Monto_Total,0,',',','));
												$x ++;
				}



				}

						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('compras'.$fecha);
						$this->output_exel('compras',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function compra($id='')
	{
		        $this->load->model("Compra_Model",'Compra');
		        $this->db->select('Descuento_Total,Num_factura_Compra,Ticket,Estado');
		        $this->db->where('idFactura_Compra', $id);
		        $query=$this->db->get('Factura_Compra');
		        $row = $query->row();
		        if (!empty($row->Num_factura_Compra)) {
		        $nombre = 'Listado Detalle Compras Segun Factura Nº '.$row->Num_factura_Compra;
		        }else{
		        $nombre = 'Listado Detalle Compras Segun Recibo Nº '.$row->Ticket;
		        }
		        $list = $this->Compra->detale(array('Factura_Compra_idFactura_Compra' => $id));
				// echo var_dump($row);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Cantidad')
						            ->setCellValue('B2', 'Nombre')
						            ->setCellValue('C2', 'Precio')
						            ->setCellValue('D2', 'Subtotal');
						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);


						$x = 3;
						$sum = 0;
				foreach ($list as $key => $listc) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':D'.$x)->applyFromArray($this->style_list());
							$resultado = intval(preg_replace('/[^0-9]+/', '', $listc->Precio_Costo), 10); 
							$val = $resultado * $listc->can;
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('A'.$x, $listc->can)
							->setCellValue('B'.$x, $listc->Nombre)
							->setCellValue('C'.$x, number_format( $resultado,0,',',','))
							->setCellValue('D'.$x, number_format( $val,0,',',','));
							$sum += $val;
				$x ++;
				}
				$val = $x+1;
				$va2 = $val+1;
				if (empty($row->Descuento_Total)) {
								$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$val, 'Monto total: '.number_format($sum,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$val)->applyFromArray($this->styleHead('F93107'));

				}else{
					$total = $sum - $row->Descuento_Total;
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$val, 'Descuento: '.number_format($row->Descuento_Total,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$val)->applyFromArray($this->styleHead('75362A'));
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$va2, 'Monto total: '.number_format($total,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$va2)->applyFromArray($this->styleHead('F93107'));

				}

					$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('compras'.$fecha);
						$this->output_exel('compras',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function compranull($id='')
	{
	

                $nombre = 'Listados de Comprobantes Anulados';
				// Se obtienen los clientes de la base de datos
		        $this->load->model("Compra_Model",'Compra');
                $list = $this->Compra->getanul();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:C1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Comprobante')
						            ->setCellValue('B2', 'Proveedor')
						            ->setCellValue('C2', 'Monto Total');
						$this->phpexcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);

						$x = 3;

				foreach ($list as $key => $listc) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':C'.$x)->applyFromArray($this->style_list());
							if ($listc->Tipo_Compra == 0 ) { // voleta
							$this->phpexcel->setActiveSheetIndex(0)
							               ->setCellValue('A'.$x, 'Recibo Nº '. $listc->Ticket);
							}elseif ($listc->Tipo_Compra == 1 ) { // factura
							$this->phpexcel->setActiveSheetIndex(0)
							               ->setCellValue('A'.$x, 'Factura Nº '. $listc->Num_factura_Compra);
							}
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('B'.$x, $listc->Razon_Social.' '.$listc->Vendedor)
							->setCellValue('C'.$x, number_format( $listc->Monto_Total,0,',',','));

				$x ++;
				}
					$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('Compras_null'.$fecha);
						$this->output_exel('Compras_null',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function Lisnul($id='')
	{

				// Se obtienen los clientes de la base de datos
		        $this->load->model("Compra_Model",'Compra');
		        $this->db->select('Descuento_Total,Num_factura_Compra,Ticket');
		        $this->db->where('idFactura_Compra', $id);
		        $query=$this->db->get('Factura_Compra');
		        $row = $query->row();
		        if (!empty($row->Num_factura_Compra)) {
		        $nombre = 'Listado Detalle Compras Anuladas Segun Factura Nº '.$row->Num_factura_Compra;
		        }else{
		        $nombre = 'Listado Detalle Compras Anuladas Segun Recibo Nº '.$row->Ticket;
		        }
		        $list = $this->Compra->detale(array('Factura_Compra_idFactura_Compra' => $id));
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Cantidad')
						            ->setCellValue('B2', 'Nombre')
						            ->setCellValue('C2', 'Precio')
						            ->setCellValue('D2', 'Subtotal');


						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);


						$x = 3;
						$sum = 0;
				foreach ($list as $key => $listc) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':D'.$x)->applyFromArray($this->style_list());
							$resultado = intval(preg_replace('/[^0-9]+/', '', $listc->Precio_Costo), 10); 
							$val = $resultado * $listc->can;
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $listc->can)
									->setCellValue('B'.$x, $listc->Nombre)
									->setCellValue('C'.$x, number_format( $resultado,0,',',','))
									->setCellValue('D'.$x, number_format( $val,0,',',','));

							$sum += $val;
							$x ++;
				}
				$val = $x+2;
				$val2 = $val+1;
				if (empty($row->Descuento_Total)) {
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$val, 'Monto total: '.number_format($sum,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$val)->applyFromArray($this->styleHead('F93107'));


				}else{
					$total = $sum - $row->Descuento_Total;
					$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$val, 'Descuento: '.number_format($row->Descuento_Total,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$val)->applyFromArray($this->styleHead('75362A'));
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$val2, 'Monto total: '.number_format($total,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$val2)->applyFromArray($this->styleHead('F93107'));
				}
						$this->phpexcel->getActiveSheet()->setTitle('Compras_null'.$id);
						$this->output_exel('Compras_null',$id );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function cdevolucion($value='')
	{

				// Se obtienen los clientes de la base de datos
	            $this->load->model("Cdevolver_Model",'Devolver');

		        $nombre = 'Listado de Devoluciones';

		         $list = $this->Devolver->getDevolver();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Comprobante')
						            ->setCellValue('B2', 'Proveedor')
						            ->setCellValue('C2', 'Fecha')
						            ->setCellValue('D2', 'Monto Total');


						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
						$x = 3;

				foreach ($list as $key => $listc) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':D'.$x)->applyFromArray($this->style_list());
							if ($listc->Tipo_Compra == 0 ) { // voleta
							$this->phpexcel->setActiveSheetIndex(0)
							               ->setCellValue('A'.$x, 'Recibo Nº '. $listc->Ticket);
							}elseif ($listc->Tipo_Compra == 1 ) { // factura
							$this->phpexcel->setActiveSheetIndex(0)
							               ->setCellValue('A'.$x, 'Factura Nº '. $listc->Num_factura_Compra);
							}
									$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('B'.$x, $listc->Razon_Social.' '.$listc->Vendedor)
							->setCellValue('C'.$x, $listc->Fecha)
     						->setCellValue('D'.$x, number_format( $listc->Monto_Total,0,',',','));
     			$x ++;

				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('Cdevoluciones'.$fecha);
						$this->output_exel('Cdevoluciones',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function devolucion($id='')
	{
	            $this->load->model("Cdevolver_Model",'Devolver');
                $query = $this->Devolver->getDevolver(array('idDevoluciones' => $id));
		        $row = $query->row();
		        if (!empty($row->Num_factura_Compra)) {
		        $nombre = 'Listado Detalle Compras Anuladas Segun Factura N. '.$row->Num_factura_Compra;
		        }else{
		        $nombre = 'Listado Detalle Compras Anuladas Segun Recibo N. '.$row->Ticket;
		        }
		        $list = $this->Devolver->detalele(array('Devoluciones_idDevoluciones' => $id));
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Cantidad')
						            ->setCellValue('B2', 'Nombre')
						            ->setCellValue('C2', 'Precio')
						            ->setCellValue('D2', 'Condicion')
						            ->setCellValue('E2', 'Subtotal');


						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
						$x = 3;
				foreach ($list as $key => $listc) {
					    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $listc->Cantidad)
									->setCellValue('B'.$x, $listc->Nombre)
									->setCellValue('C'.$x, number_format( $listc->Precio,0,',',','))
									->setCellValue('D'.$x, $listc->Estado)
									->setCellValue('E'.$x, number_format( $listc->Precio * $listc->Cantidad,0,',',','));

							$x ++;
				}
				$val = $x+2;
						$this->phpexcel->setActiveSheetIndex(0)
						->setCellValue('E'.$val, 'Monto total: '.number_format($row->Monto_Total,0,',',','));
						$this->phpexcel->getActiveSheet()->getStyle('E'.$val)->applyFromArray($this->styleHead('F93107'));

						$this->phpexcel->getActiveSheet()->setTitle('Cdevolucion_null'.$id);
						$this->output_exel('Cdevolucion_null',$id );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}
    public function get_Deudalist($id='')
    {
        $this->db->select(self::SELECT_PAGADA);
        $this->db->from('Cuenta_Corriente_Cliente cr');
        $this->db->join('Factura_Venta fa', 'cr.Factura_Venta_idFactura_Venta = fa.idFactura_Venta', 'left');
        $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'inner');
        $this->db->where('cr.Estado = 0 and cr.Factura_Venta_idFactura_Venta = '.$id.' or cr.Estado = 3 and cr.Factura_Venta_idFactura_Venta = '.$id.'');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_Deuda_pagads($value='')
    {
        $this->db->select(self::SELECT_PAGADA);
        $this->db->from('Factura_Venta fa');
        $this->db->join('Cuenta_Corriente_Cliente cr', 'cr.Factura_Venta_idFactura_Venta = fa.idFactura_Venta', 'left outer');
        $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'inner');
        $this->db->where('cr.Estado = 1 or cr.Estado = 3');
                if ($this->session->userdata('idUsuario') != 1) {
        $this->db->where('fa.Caja_idCaja', $this->session->userdata('idcaja'));
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function sum_pagos_($id)
    {
        $consult="
        SELECT sum(Monto) as total1,'' as cuenta from Caja_Cobros  WHERE  Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente ='$id'
        UNION ALL
        SELECT sum(Monto) as total1,'' as cuenta from Cuenta_Fabor
        JOIN Cuenta_Corriente_Empresa_has_Cuenta_Fabor has ON Cuenta_Fabor.idCuenta_Fabor = has.Cuenta_Fabor_idCuenta_Fabor
        WHERE  Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa ='$id'
        UNION ALL
        SELECT sum(Importe) as total1,'' as cuenta from Movimientos  WHERE  Activo_Inactivo = '1' AND Cobros = '1' AND Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente ='$id'
        ";
        $query = $this->db->query($consult);
        $pagados = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $value) {
                    $pagados += $value->total1 ;
            }
        }
        return $pagados ;
    }



   public function sum_pagos_tods($id)
    {
        $consult="
        SELECT sum(Monto) as total1,'' as cuenta from Caja_Pagos  WHERE  Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa ='$id'
        UNION ALL
        SELECT sum(Monto) as total1,'' as cuenta from Cuenta_Fabor
        LEFT JOIN Cuenta_Corriente_Empresa_has_Cuenta_Fabor has ON Cuenta_Fabor.idCuenta_Fabor = has.Cuenta_Fabor_idCuenta_Fabor
        WHERE  Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa ='$id' OR idCuenta_Corriente_Empresa ='$id'
        UNION ALL
        SELECT sum(Importe) as total1,'' as cuenta from Movimientos  WHERE  Activo_Inactivo = '1' AND Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa ='$id'
        UNION ALL
        SELECT '' as total1, sum(Monto) as cuenta from Cuenta_Fabor
        WHERE   Estado = '3' AND idCuenta_Corriente_Empresa ='$id'
        ";
        $query = $this->db->query($consult);
        $pagados = '';
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $value) {
                    $pagados += $value->total1 - $value->cuenta;
            }
        }
        return $pagados ;
    }

	public function deudacliente($value='')
	{
	            $nombre = 'Listados Deuda Cliente';
	            $this->db->select(
					'COUNT(cr.Num_Cuota ) as Num_cuota,
					Monto_Total as monto_totales,
					fa.Cliente_idCliente as idCliente,
					fa.idFactura_Venta,
					fa.Num_Factura_Venta,Tipo_Venta,
					sum(cr.Importe) as inporte_total,
					cr.Fecha_Ven,
					cr.idCuenta_Corriente_Cliente,
					cr.Fecha_Pago,
					cl.Nombres,
					cl.Apellidos,
					fa.Contado_Credito,fa.Estado as esta
        		');
                $this->db->join('Factura_Venta fa', 'cr.Factura_Venta_idFactura_Venta = fa.idFactura_Venta', 'inner');
                $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'left');
		        $this->db->where('cr.Estado = 0 or cr.Estado = 3');
		        if ($this->session->userdata('idUsuario') != 1) {
		        $this->db->where('fa.Caja_idCaja', $this->session->userdata('idcaja'));
		        }
		         // $this->db->group_by(self::Group_By);
				$query = $this->db->get('Cuenta_Corriente_Cliente cr');
				$list =   $query->result();
				if($list ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Cuota Pendiente')
						            ->setCellValue('B2', 'Proveedor')
						            ->setCellValue('C2', 'Monto Total')
						            ->setCellValue('D2', 'Pago Parcial')
						            ->setCellValue('E2', 'Monto Pendiente');
						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);				

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
					    $x = 3;
					  
						foreach ($list as $key => $Cuenta) {
						$Parcial_todo = $this->sum_pagos_tods($Cuenta->idCuenta_Corriente_Cliente);
						$xx =  round($Cuenta->inporte_total) ;
						$mpendiente = round( $xx - $Parcial_todo) ;
  						$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
			    		if ($Cuenta->Num_cuota == 1 ) {
							if ($mpendiente > 0)
							{
							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x,$Cuenta->Num_cuota)
								->setCellValue('B'.$x, $this->mi_libreria->getSubString($Cuenta->Nombres, 35).' ('.$this->mi_libreria->getSubString($Cuenta->Apellidos, 35).')')
								->setCellValue('C'.$x, number_format($xx,0,',',','))
								->setCellValue('D'.$x, number_format($Parcial_todo,0,',',','))
								->setCellValue('E'.$x, number_format($mpendiente,0,',',','));
							}
						}else{
							if ($Cuenta->Num_cuota == 0) {
							    $this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x,'1');
							}else{
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x,$Cuenta->Num_cuota);
							}
							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x, $this->mi_libreria->getSubString($Cuenta->Nombres, 25).' ('.$this->mi_libreria->getSubString($Cuenta->Apellidos, 25).')')
								->setCellValue('C'.$x, number_format($xx,0,',',','))
								->setCellValue('D'.$x, number_format($Parcial_todo,0,',',','))
								->setCellValue('E'.$x, number_format($mpendiente,0,',',','));
						}

						$x ++;	

						}
				        $fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('Deuda_Cliente'.$fecha);
						$this->output_exel('Deuda_Cliente',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function listadeuda($id)
	{
	$nombre = 'Listado Detallado Deuda de Clientes';

				// Se obtienen los clientes de la base de datos
				$list = $this->get_Deudalist($id);
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );


						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Cuota Nº')
						            ->setCellValue('B2', 'Comprovante')
						            ->setCellValue('C2', 'Cliente')
						            ->setCellValue('D2', 'Importe a Pagar')
						            ->setCellValue('E2', 'Vencimiento');
						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$x = 4;
						$sum = 0;

		                foreach ($list as $key => $Cuenta) {
						$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
		                $Parcial_todo = $this->sum_pagos_($Cuenta->idCuenta_Corriente_Cliente);
						$xx =  round($Cuenta->inporte_total) ;
						$mpendiente =  $Cuenta->inporte_total - $Parcial_todo ;
											$this->phpexcel->setActiveSheetIndex(0)
												->setCellValue('A'.$x, $Cuenta->Num_cuota)
												->setCellValue('B'.$x,'Recibo Nº '. $Cuenta->Num_Recibo)
												->setCellValue('C'.$x,$this->mi_libreria->getSubString($Cuenta->Nombres, 25).' ('.$this->mi_libreria->getSubString($Cuenta->Apellidos, 25).')')
		      	                                ->setCellValue('D'.$x,number_format($mpendiente,0,',',','))
												->setCellValue('E'.$x, $Cuenta->Fecha_Ven);
												$sum += $Cuenta->inporte_total - $Parcial_todo;

		                 $x++;
		                }
		                $val = $x+1;

									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$val, 'Monto total: '.number_format($sum,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$val)->applyFromArray($this->styleHead('F93107'));

						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('lista_Cuenta'.$fecha);
						$this->output_exel('lista_Cuenta',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function lispagadas()
	{
	$nombre = 'Lista de Cuentas Pagadas';

				// Se obtienen los clientes de la base de datos
		        $this->load->model("Deuda_cliente_Model",'Cuenta');
				$list = $this->Cuenta->get_Deuda_pagads();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:F1' );


						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Cuota Nº')
						            ->setCellValue('B2', 'Comprovante')
						            ->setCellValue('C2', 'Cliente')
						            ->setCellValue('D2', 'Importe a Cobrar')
						            ->setCellValue('E2', 'Monto Cobrado')
						            ->setCellValue('F2', 'Monto Pendiente');

						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);					

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

						$x = 4;
						$sum = 0;

		                foreach ($list as $key => $Cuenta) {
							$Parcial_todo = $this->Cuenta->sum_pagos_($Cuenta->id);
							$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':F'.$x)->applyFromArray($this->style_list());
							if ($Cuenta->inporte_total > $Parcial_todo  ) {
								$mpendiente =  number_format($Cuenta->inporte_total - $Parcial_todo,0,',',',') ;
							}else{
								$mpendiente =  '';
							}
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, $Cuenta->Num_cuota);
							if ($Cuenta->Tipo_Venta == 0 ) { // voleta
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x, 'Recibo Nº '. $Cuenta->Num_Recibo);
							}elseif ($Cuenta->Tipo_Venta == 1 ) { // factura
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x, 'Recibo Nº '. $Cuenta->Num_Factura_Venta)	;				
							}
											$this->phpexcel->setActiveSheetIndex(0)
												->setCellValue('C'.$x,$this->mi_libreria->getSubString($Cuenta->Nombres, 25).' ('.$this->mi_libreria->getSubString($Cuenta->Apellidos, 25).')')
		      	                                ->setCellValue('D'.$x, number_format($Cuenta->inporte_total,0,',',','))
												->setCellValue('E'.$x, number_format($Parcial_todo,0,',',','))
												->setCellValue('F'.$x, $mpendiente);

												$sum += $Parcial_todo;

		                 $x++;
		                }
		                $val = $x+1;

									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$val, 'Monto total: '.number_format($sum,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$val)->applyFromArray($this->styleHead('F93107'));

						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('lista_pagadas'.$fecha);
						$this->output_exel('lista_pagadas',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function ventas($value='')
	{
				$nombre = 'Listado Ventas';

				// Se obtienen los clientes de la base de datos
				$this->load->model("Venta_Model",'Venta');
				$list = $this->Venta->getVenta();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Comprobante')
						            ->setCellValue('B2', 'cliente')
						            ->setCellValue('C2', 'Fecha')
						            ->setCellValue('D2', 'Estado')
						            ->setCellValue('E2', 'Monto Total');
						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);

						$x = 4;
						$sum = 0;
				foreach ($list as $key => $Venta) {
						$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
				if ($Venta->Estado == 0) {
							if ($Venta->Tipo_Venta == 0) {
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Recibo Nº  '.$Venta->Ticket);
							}else{
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Factura Nº  '. $Venta->Num_Factura_Venta);
							}
							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x,$this->mi_libreria->getSubString($Venta->Nombres.'-'.$Venta->Apellidos, 50))
								->setCellValue('C'.$x,$Venta->Fecha_expedicion.'  '.$Venta->Hora)
		      	                ->setCellValue('D'.$x, 'Pagado')
								->setCellValue('E'.$x, number_format($Venta->Monto_Total,0,',',','));
										$x ++;
				}elseif ($Venta->Estado == 1) {
							if ($Venta->Tipo_Venta == 0) {
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Recibo Nº  '.$Venta->Ticket);
							}else{
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Factura Nº  '. $Venta->Num_Factura_Venta);
							}

							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x,  $this->mi_libreria->getSubString($Venta->Nombres.'-'.$Venta->Apellidos, 50))
								->setCellValue('C'.$x,$Venta->Fecha_expedicion.'  '.$Venta->Hora)
		      	                ->setCellValue('D'.$x, 'Parcial')
								->setCellValue('E'.$x, number_format($Venta->Monto_Total,0,',',','));
										$x ++;
				}elseif ($Venta->Estado == 2) {
							if ($Venta->Tipo_Venta == 0) {
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Recibo Nº  '.$Venta->Ticket);
							}else{
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Factura Nº  '. $Venta->Num_Factura_Venta);
							}

							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x, $this->mi_libreria->getSubString($Venta->Nombres.'-'.$Venta->Apellidos, 50))
								->setCellValue('C'.$x,$Venta->Fecha_expedicion.'  '.$Venta->Hora)
		      	                ->setCellValue('D'.$x, 'No Pagado')
								->setCellValue('E'.$x, number_format($Venta->Monto_Total,0,',',','));
										$x ++;
				}
		


				}

						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('ventas'.$fecha);
						$this->output_exel('ventas',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function venta($id='')
	{

				// Se obtienen los clientes de la base de datos
				$this->load->model("Venta_Model",'Venta');
		        $this->db->select('Descuento_Total,Num_Factura_Venta,Ticket');
		        $this->db->where('idFactura_Venta', $id);
		        $query=$this->db->get('Factura_Venta');
		        $row = $query->row();
		        if (!empty($row->Num_Factura_Venta)) {
		        $nombre = 'Listado Detalle Compras Segun Factura Nº '.$row->Num_Factura_Venta;
		        }else{
		        $nombre = 'Listado Detalle Compras Segun Recibo Nº '.$row->Ticket;
		        }
		        $list = $this->Venta->detale(array('Factura_Venta_idFactura_Venta' => $id));
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Cantidad')
						            ->setCellValue('B2', 'Nombre')
						            ->setCellValue('C2', 'Precio')
						            ->setCellValue('D2', 'Subtotal');
						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);


						$x = 3;
						$sum = 0;
				foreach ($list as $key => $listc) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':D'.$x)->applyFromArray($this->style_list());
							$resultado = intval(preg_replace('/[^0-9]+/', '', $listc->Precio_Venta), 10); 
							$val = $resultado * $listc->can;
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('A'.$x, $listc->can)
							->setCellValue('B'.$x, $listc->Nombre)
							->setCellValue('C'.$x, number_format( $resultado,0,',',','))
							->setCellValue('D'.$x, number_format( $val,0,',',','));
							$sum += $val;
				$x ++;
				}
				$val = $x+1;
				$va2 = $val+1;
				if (empty($row->Descuento_Total)) {
								$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$val, 'Monto total: '.number_format($sum,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$val)->applyFromArray($this->styleHead('F93107'));

				}else{
					$total = $sum - $row->Descuento_Total;
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$val, 'Descuento: '.number_format($row->Descuento_Total,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$val)->applyFromArray($this->styleHead('75362A'));
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$va2, 'Monto total: '.number_format($total,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$va2)->applyFromArray($this->styleHead('F93107'));

				}

					$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('venta'.$fecha);
						$this->output_exel('venta',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function ventanull($id='')
	{
	
               
                $nombre = 'Listados de Comprobantes Anulados';
				// Se obtienen los clientes de la base de datos
				$this->load->model("Venta_Model",'Venta');
                $list = $this->Venta->getanul();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:C1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Comprobante')
						            ->setCellValue('B2', 'Cliente')
						            ->setCellValue('C2', 'Monto Total');
						$this->phpexcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);

						$x = 3;

				foreach ($list as $key => $listc) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':C'.$x)->applyFromArray($this->style_list());
							if ($listc->Tipo_Venta == 0 ) { // voleta
							$this->phpexcel->setActiveSheetIndex(0)
							               ->setCellValue('A'.$x, 'Recibo Nº '. $listc->Ticket);
							}elseif ($listc->Tipo_Venta == 1 ) { // factura
							$this->phpexcel->setActiveSheetIndex(0)
							               ->setCellValue('A'.$x, 'Factura Nº '. $listc->Num_Factura_Venta);
							}
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('B'.$x, $listc->Nombres.' '.$listc->Apellidos)
							->setCellValue('C'.$x, number_format( $listc->Monto_Total,0,',',','));

				$x ++;
				}
					$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('Ventas_null'.$fecha);
						$this->output_exel('Ventas_null',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function lisventanul($id='')
	{

				// Se obtienen los clientes de la base de datos
				$this->load->model("Venta_Model",'Venta');
		        $this->db->select('Descuento_Total,Num_Factura_Venta,Ticket');
		        $this->db->where('idFactura_Venta', $id);
		        $query=$this->db->get('Factura_Venta');
		        $row = $query->row();
		        if (!empty($row->Num_Factura_Venta)) {
		        $nombre = 'Listado Detalle Compras Anuladas Segun Factura Nº '.$row->Num_Factura_Venta;
		        }else{
		        $nombre = 'Listado Detalle Compras Anuladas Segun Recibo Nº '.$row->Ticket;
		        }
		        $list = $this->Venta->detale(array('Factura_Venta_idFactura_Venta' => $id));
				// echo var_dump($list);
				if( !empty( $list ) )
				{
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Cantidad')
						            ->setCellValue('B2', 'Nombre')
						            ->setCellValue('C2', 'Precio')
						            ->setCellValue('D2', 'Subtotal');


						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);


						$x = 3;
						$sum = 0;
						foreach ($list as $key => $listc) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':D'.$x)->applyFromArray($this->style_list());
							$resultado = intval(preg_replace('/[^0-9]+/', '', $listc->Precio_Venta), 10); 
							$val = $resultado * $listc->can;
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $listc->can)
									->setCellValue('B'.$x, $listc->Nombre)
									->setCellValue('C'.$x, number_format( $resultado,0,',',','))
									->setCellValue('D'.$x, number_format( $val,0,',',','));

							$sum += $val;
							$x ++;
						}
						$val = $x+2;
						$val2 = $val+1;
						if (empty($row->Descuento_Total)) {
											$this->phpexcel->setActiveSheetIndex(0)
											->setCellValue('D'.$val, 'Monto total: '.number_format($sum,0,',',','));
											$this->phpexcel->getActiveSheet()->getStyle('D'.$val)->applyFromArray($this->styleHead('F93107'));


						}else{
							$total = $sum - $row->Descuento_Total;
							$this->phpexcel->setActiveSheetIndex(0)
											->setCellValue('D'.$val, 'Descuento: '.number_format($row->Descuento_Total,0,',',','));
											$this->phpexcel->getActiveSheet()->getStyle('D'.$val)->applyFromArray($this->styleHead('75362A'));
											$this->phpexcel->setActiveSheetIndex(0)
											->setCellValue('D'.$val2, 'Monto total: '.number_format($total,0,',',','));
											$this->phpexcel->getActiveSheet()->getStyle('D'.$val2)->applyFromArray($this->styleHead('F93107'));
						}
								$this->phpexcel->getActiveSheet()->setTitle('Detalle_Ventas_anuladas'.$id);
								$this->output_exel('Ventas_anuladas',$id );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function ventadevol($value='')
	{

				// Se obtienen los clientes de la base de datos
		        $this->load->model("VDevolver_Model",'Devolver');

		        $nombre = 'Listado de   Devoluciones Segun Comprobante';

		         $list = $this->Devolver->getDevolver();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Comprobante')
						            ->setCellValue('B2', 'Cliente')
						            ->setCellValue('C2', 'Fecha')
						            ->setCellValue('D2', 'Monto Total');


						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
						$x = 3;

				foreach ($list as $key => $listc) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':D'.$x)->applyFromArray($this->style_list());
							if ($listc->Tipo_Venta == 0 ) { // voleta
							$this->phpexcel->setActiveSheetIndex(0)
							               ->setCellValue('A'.$x, 'Recibo Nº '. $listc->Ticket);
							}elseif ($listc->Tipo_Venta == 1 ) { // factura
							$this->phpexcel->setActiveSheetIndex(0)
							               ->setCellValue('A'.$x, 'Factura Nº '. $listc->Num_Factura_Venta);
							}
									$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('B'.$x, $listc->Nombres.' '.$listc->Apellidos)
							->setCellValue('C'.$x, $listc->Fecha)
     						->setCellValue('D'.$x, number_format( $listc->Monto_Total,0,',',','));
     			$x ++;

				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('vdevolucion'.$fecha);
						$this->output_exel('vdevolucion',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function vdevolucion($id='')
	{

		        $this->load->model("VDevolver_Model",'Devolver');
                $query = $this->Devolver->getDevolver(array('idDevoluciones' => $id));
		        $row = $query->row();
		        if (!empty($row->Num_Factura_Venta)) {
		        $nombre = 'Listado Detalle ventas Anuladas Segun Factura N. '.$row->Num_Factura_Venta;
		        }else{
		        $nombre = 'Listado Detalle ventas Anuladas Segun Recibo N. '.$row->Ticket;
		        }
		        $list = $this->Devolver->detalele(array('Devoluciones_idDevoluciones' => $id));
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Cantidad')
						            ->setCellValue('B2', 'Nombre')
						            ->setCellValue('C2', 'Precio')
						            ->setCellValue('D2', 'Condicion')
						            ->setCellValue('E2', 'Subtotal');


						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);

						$x = 3;
				foreach ($list as $key => $listc) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $listc->Cantidad)
									->setCellValue('B'.$x, $listc->Nombre)
									->setCellValue('C'.$x, number_format( $listc->Precio,0,',',','))
									->setCellValue('D'.$x, $listc->Estado)
									->setCellValue('E'.$x, number_format( $listc->Precio * $listc->Cantidad,0,',',','));

							$x ++;
				}
				$val = $x+2;
						$this->phpexcel->setActiveSheetIndex(0)
						->setCellValue('E'.$val, 'Monto total: '.number_format($row->Monto_Total,0,',',','));
						$this->phpexcel->getActiveSheet()->getStyle('E'.$val)->applyFromArray($this->styleHead('F93107'));

						$this->phpexcel->getActiveSheet()->setTitle('detalle_venta_anuladas'.$id);
						$this->output_exel('detalle_venta_anuladas',$id );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function Cobros($id='')
	{
                

                $this->load->model("Cobro_Model",'Cobro');

		        $list = $this->Cobro->getCobro();
		        $nombre = 'Listado Cobros';
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Descripción')
						            ->setCellValue('B2', 'Monto')
						            ->setCellValue('C2', 'Comprobantes')
						            ->setCellValue('D2', 'Razon Social')
						            ->setCellValue('E2', 'Fecha Pago');


						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
						$x = 3;
						foreach ($list as $key => $listc) {
							if (is_null($listc->idcce)) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $listc->Concepto)
									->setCellValue('B'.$x, number_format( $listc->Monto,0,',',','));

									if ($listc->Tipo_Venta == 0 ) { // voleta
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('C'.$x, 'Recibo Nº '. $listc->Ticket);
									}elseif ($listc->Tipo_Venta == 1 ) { // factura
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('C'.$x, 'Factura Nº '. $listc->Num_Factura_Venta);
									}
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$x, $listc->Nombres)
									->setCellValue('E'.$x, $listc->Fecha.' - '.$listc->Hora);
							}else{
							$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, 'Cobros de Cuotas')
									->setCellValue('B'.$x, number_format( $listc->total1,0,',',','));
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('C'.$x, 'Recibo Cuota Nº '.$listc->Num_Recibo)
									->setCellValue('D'.$x, $listc->Nombres. ' (' .$listc->Ruc.')')
									->setCellValue('E'.$x, $listc->Fecha_Pago);
							}


									$x ++;
						}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('cobro'.$fecha);
						$this->output_exel('cobro',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function pagos($id='')
	{
                

		        $this->load->model("Pago_Model",'Pago');
			    $list = $this->Pago->getPago();
		        $nombre = 'Listado Pagos';
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Descripción')
						            ->setCellValue('B2', 'Monto')
						            ->setCellValue('C2', 'Comprobantes')
						            ->setCellValue('D2', 'Razon Social')
						            ->setCellValue('E2', 'Fecha Pago');


						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
						$x = 3;
						foreach ($list as $key => $listc) {
							if (is_null($listc->idcce)) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $listc->Concepto)
									->setCellValue('B'.$x, number_format( $listc->Monto,0,',',','));

									if ($listc->Tipo_Compra == 0 ) { // voleta
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('C'.$x, 'Recibo Nº '. $listc->Ticket);
									}elseif ($listc->Tipo_Compra == 1 ) { // factura
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('C'.$x, 'Factura Nº '. $listc->Num_factura_Compra);
									}
									if (!is_null($listc->Empleado_idEmpleado)) {
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('D'.$x, $listc->Nombres. ' (' .$listc->Apellidos.')');
									}else{
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('D'.$x, '');
									}
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$x, $listc->Fecha.' - '.$listc->Hora);
							}else{
							$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, 'Pagos de Cuotas')
									->setCellValue('B'.$x, number_format( $listc->total1,0,',',','))
									->setCellValue('C'.$x, 'Recibo Cuota Nº '.$listc->Num_Recibo)
									->setCellValue('D'.$x, $listc->Razon_Social)
									->setCellValue('E'.$x, $listc->Fecha_Pago);
							}


						$x ++;
						}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('pagos'.$fecha);
						$this->output_exel('pagos',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function Bancos($id='')
	{
                

			    $list = $this->db->get('Gestor_Bancos')->result();
		        $nombre = 'Listado de Bancos';
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:C1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Nombre Bancario')
						            ->setCellValue('B2', 'Numero Bancario')
						            ->setCellValue('C2', 'Monto');


						$this->phpexcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);

						$x = 3;
				foreach ($list as $key => $listc) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $listc->Nombre)
									->setCellValue('B'.$x, $listc->Numero);
									if (!empty($listc->MontoActivo)) {
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('C'.$x, number_format($listc->MontoActivo,0,',',','));
									}else{
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('C'.$x, '0');
									}
									
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('banco'.$fecha);
						$this->output_exel('banco',$fecha );


				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function movimiwnto($id='')
	{
                

 		        $this->load->model('Banco_Model', 'Banco');
			    $list = 	$this->Banco->detale($id);
		        $nombre = 'Listado de Movimiento';
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Cheque')
						            ->setCellValue('B2', 'Plan de Cuenta')
						            ->setCellValue('C2', 'Fecha Expedicion')
						            ->setCellValue('D2', 'Entrada Salida')
						            ->setCellValue('E2', 'Importe');


						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$x = 3;$entrada=0;$salida=0;
						foreach ($list as $key => $listc) {
							if ($listc->NumeroCheque > 0) {
							$NumeroCheque = 'Cheque';
							}else{
							$NumeroCheque  = 'Efectivo';
							}
							if ($listc->PlandeCuenta_idPlandeCuenta > 0) {
							$sub = $listc->Balance_General;
							}else{
							
							$sub = $listc->ConceptoSalida;
							}
								    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
											$this->phpexcel->setActiveSheetIndex(0)
											->setCellValue('A'.$x, $NumeroCheque)
											->setCellValue('B'.$x, $sub)
											->setCellValue('C'.$x, $listc->FechaExpedicion)
											->setCellValue('D'.$x, $listc->Entrada_Salida)
											->setCellValue('E'.$x, number_format( $listc->Importe,0,',',','));
											;
								if ($listc->Entrada_Salida == 'Entrada') {
								$entrada += $listc->Importe;
							}else{
								$salida  += $listc->Importe;
							}
						$x ++;
						}
						$val = $x +1;
						$va2 = $val  +1;
						$va3 =  $va2 +1;

									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$val,'Total Entrada: ')
									->setCellValue('E'.$val,number_format( $entrada,0,',',','))
									->setCellValue('D'.$va2,'Total Salida: ')
									->setCellValue('E'.$va2,number_format( $salida,0,',',','))
									->setCellValue('D'.$va3,'Saldo: ')
									->setCellValue('E'.$va3, number_format( $entrada - $salida,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$val.':E'.$val)->applyFromArray($this->styleHead('75362A'));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$va2.':E'.$va2)->applyFromArray($this->styleHead('75362A'));
									$this->phpexcel->getActiveSheet()->getStyle('D'.$va3.':E'.$va3)->applyFromArray($this->styleHead('F93107'));
						
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('movimientobanco'.$fecha);
						$this->output_exel('movimientobanco',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function mbanco($id='')
	{
                

				$this->db->select('*');
				$this->db->join('Gestor_Bancos', 'Gestor_Bancos.idGestor_Bancos = Movimientos.Gestor_Bancos_idGestor_Bancos', 'inner');
				$this->db->join('PlandeCuenta', 'PlandeCuenta.idPlandeCuenta = Movimientos.PlandeCuenta_idPlandeCuenta', 'left');
				$query = $this->db->get('Movimientos');
				$list =  $query->result();
				$nombre = 'Listado de Movimiento Banco';
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:F1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Cheque')
						            ->setCellValue('B2', 'Concepto')
						            ->setCellValue('C2', 'Fecha Expedicion')
						            ->setCellValue('D2', 'Importe')
						            ->setCellValue('E2', 'Banco')
						            ->setCellValue('F2', 'Accion')
						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

						$x = 3;	
						$entrada = 0;
						$salida = 0;
						foreach ($list as  $value) {
							if ($value->NumeroCheque > 0) {
							$NumeroCheque = $value->NumeroCheque;
							}else{
							$NumeroCheque  = 'Efectivo';
							}
							
					    	if (!empty($value->PlandeCuenta_idPlandeCuenta)) {
					    		$row =  $value->Balance_General;
					    	}else{
						    	if ($value->Entrada_Salida == 'Entrada') {
						    	$row =  $value->ConceptoEntrada;
						    	}else{
						    	 $row =  $value->ConceptoSalida;
						    	}
					    	}
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':F'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $NumeroCheque)
									->setCellValue('B'.$x, $row)
									->setCellValue('C'.$x, $value->FechaExpedicion)
									->setCellValue('D'.$x, number_format($value->Importe,0,',',','))
									->setCellValue('E'.$x, $value->Nombre)
									->setCellValue('F'.$x, $value->Entrada_Salida);

							if ($value->Entrada_Salida == 'Entrada') {
								$entrada += $value->Importe;
							}else{
								$salida  += $value->Importe;
							}

						}
						$val = $x +2;
						$va2 = $val  +1;
						$va3 =  $va2 +1;

									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$val,'Total Entrada: ')
									->setCellValue('F'.$val,number_format( $entrada,0,',',','))
									->setCellValue('E'.$va2,'Total Salida: ')
									->setCellValue('F'.$va2,number_format( $salida,0,',',','))
									->setCellValue('E'.$va3,'Saldo: ')
									->setCellValue('F'.$va3, number_format( $entrada - $salida,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$val.':F'.$val)->applyFromArray($this->styleHead('75362A'));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$va2.':F'.$va2)->applyFromArray($this->styleHead('75362A'));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$va3.':F'.$va3)->applyFromArray($this->styleHead('F93107'));
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('listamovimiento'.$fecha);
						$this->output_exel('listamovimiento',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}	



	public function aciento($id='')
	{
                
	
				$this->load->model("Acientos_Model",'Acientos');
				$list = $this->Acientos->getAciento();
				$nombre = 'Listado de Aciento Diario';
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Fecha')
						            ->setCellValue('B2', 'Deuda Cuenta')
						            ->setCellValue('C2', 'Haber Cuenta')
						            ->setCellValue('D2', 'Debe')
						            ->setCellValue('E2', 'Haber');


						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);

						$x = 3;
						foreach ($list as $Acientos) 
						{

							if (!is_null($Acientos->Balance_General)) {
						           $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									     ->setCellValue('A'.$x, $Acientos->Fecha);
								    	if (!is_null($Acientos->DebeDetalle)) {
											$this->phpexcel->setActiveSheetIndex(0)
									             ->setCellValue('B'.$x, $Acientos->Balance_General.' '.$Acientos->DebeDetalle);

								    	}else
								    	{
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('B'.$x, '');

								    	}

										if (!is_null($Acientos->HaberDetalle)) {
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('C'.$x, $Acientos->Balance_General.' '.$Acientos->HaberDetalle);
									   }else
									   {
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('C'.$x, '');

									   }

										if (!is_null($Acientos->Debe)) {
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('D'.$x, number_format($Acientos->Debe,0,',',','));
										}else
										{
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('D'.$x, '');

										}

									    if (!is_null($Acientos->Haber)) {
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('E'.$x, number_format($Acientos->Haber,0,',',','));
										}else{
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('E'.$x, '');
										}

							}else{
								    $this->phpexcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('B'.$x, $this->mi_libreria->remplse($Acientos->DebeDetalle));
									$this->phpexcel->getActiveSheet()->getStyle('B'.$x.':E'.$x)->applyFromArray($this->mi_libreria->styleaciento('E3F1F1'));
    								$this->phpexcel->getActiveSheet()->mergeCells('B'.$x.':E'.$x);


							}
						$x++;
						}

				 $fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('acientos'.$fecha);
						$this->output_exel('acientos',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function baciento()
	{
		$fecha = $this->security->xss_clean( $this->input->post('buscaprfecha',FALSE));
		$caja  =$this->security->xss_clean( $this->input->post('seleccaja',FALSE));
		$forma =$this->security->xss_clean( $this->input->post('selectforma',FALSE));



				if (!empty($caja)) {
					$cajas = 'Segun Caja Nº '.$caja;
				}else{
					$cajas = '';
				}
                $nombre = 'Listado de Aciento  '.$fecha.' '.$cajas;
				if (!empty($fecha)) {
	
					$this->load->model("Acientos_Model",'Acientos');
					$list = $this->Acientos->getAcientosear($fecha,$caja,$forma );

				}
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Fecha')
						            ->setCellValue('B2', 'Deuda Cuenta')
						            ->setCellValue('C2', 'Haber Cuenta')
						            ->setCellValue('D2', 'Debe')
						            ->setCellValue('E2', 'Haber');


						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);


						$x = 3;
						foreach ($list as $Acientos) 
						{

							if (!is_null($Acientos->Balance_General)) {
						           $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									     ->setCellValue('A'.$x, $Acientos->Fecha);
								    	if (!is_null($Acientos->DebeDetalle)) {
											$this->phpexcel->setActiveSheetIndex(0)
									             ->setCellValue('B'.$x, $Acientos->Balance_General.' '.$Acientos->DebeDetalle);

								    	}else
								    	{
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('B'.$x, '');

								    	}

										if (!is_null($Acientos->HaberDetalle)) {
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('C'.$x, $Acientos->Balance_General.' '.$Acientos->HaberDetalle);
									   }else
									   {
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('C'.$x, '');

									   }

										if (!is_null($Acientos->Debe)) {
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('D'.$x, number_format($Acientos->Debe,0,',',','));
										}else
										{
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('D'.$x, '');

										}

									    if (!is_null($Acientos->Haber)) {
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('E'.$x, number_format($Acientos->Haber,0,',',','));
										}else{
										$this->phpexcel->setActiveSheetIndex(0)
									         ->setCellValue('E'.$x, '');
										}

							}else{
								    $this->phpexcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('B'.$x, $this->mi_libreria->remplse($Acientos->DebeDetalle));
									$this->phpexcel->getActiveSheet()->getStyle('B'.$x.':E'.$x)->applyFromArray($this->mi_libreria->styleaciento('E3F1F1'));
    								$this->phpexcel->getActiveSheet()->mergeCells('B'.$x.':E'.$x);


							}
						$x++;
						}

						$this->phpexcel->getActiveSheet()->setTitle('acientos'.$fecha);
						$this->output_exel('acientos',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}	

	public function libromayor()
	{
		$fecha = $this->security->xss_clean( $this->input->post('fechamayor',FALSE));
		$caja  =$this->security->xss_clean( $this->input->post('cajamayor',FALSE));
		$forma =$this->security->xss_clean( $this->input->post('planmayor',FALSE));
				if (!empty($caja)) {
					$cajas = 'Segun Caja Nº '.$caja;
				}else{
					$cajas = '';
				}
                $nombre = 'Libro Mayor  '.$fecha.' '.utf8_decode($cajas);
				if (!empty($fecha)) {
	
					$this->load->model("Acientos_Model",'Acientos');
					$list = $this->Acientos->load_mayor($fecha,$caja,$forma );

				}
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:C1' );
						$this->phpexcel->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);


							$x = 2;
						foreach ($list as $value) 
						{

							$haber = 0;		
							$debe = 0;
							$total = 0;
									$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':C'.$x)->applyFromArray($this->styleHead('B9CCCC'),$this->style_list());
									$this->phpexcel->getActiveSheet()->getRowDimension($x)->setRowHeight(25);
						           $this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A'.$x, utf8_decode(str_replace('', '_', $value->Balance_General)))
						            ->setCellValue('B'.$x, 'Debe')
						            ->setCellValue('C'.$x, 'Haber');

							  $Acientos = loadmayor($value->PlandeCuenta_idPlandeCuenta,$value->Fecha);
							  $xx = $x +1;
							  foreach ($Acientos as $key => $val) {

								if (!is_null($val->Debe)) {
								$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('C'.$xx,'['.$val->Acientos_idAcientos.']  '.number_format($val->Debe,0,',',','));


								}
								if (!is_null($val->Haber)) {
								$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('B'.$xx, number_format($val->Haber,0,',',',').'  ['.$val->Acientos_idAcientos.']');
								}
								$haber          +=$val->Haber;
								$debe           +=$val->Debe;

							$xx ++;
			  	
							  }

							  $this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('B'.$xx, number_format($debe,0,',',','))
						            ->setCellValue('C'.$xx++, number_format($haber,0,',',','));
						       
	     						if ($debe > $haber  ) {
						           $this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A'.$xx , 'Saldo Deudor ')
						            ->setCellValue('B'.$xx , number_format($debe  - $haber,0,',',','));

								}else{
								 $this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A'.$xx ,'Saldo Acreedor ')
						            ->setCellValue('C'.$xx , number_format($haber  - $debe,0,',',','));

								}
								$this->phpexcel->getActiveSheet()->getRowDimension($xx)->setRowHeight(25);
								$this->phpexcel->getActiveSheet()->getStyle('A'.$xx.':C'.$xx)->applyFromArray($this->style_list('E3F1F1'));
						$x = $xx+1;

						}

						$this->phpexcel->getActiveSheet()->setTitle('libromayor'.$fecha);
						$this->output_exel('libromayor',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen resultado de busqueda',
									'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}	


	public function balance()
	{

		$ano = $this->security->xss_clean( $this->input->post('fechaanmo',FALSE));
		$mes  =$this->security->xss_clean( $this->input->post('fechames',FALSE));
                $nombre = 'Balance  '.$mes.' '.utf8_decode($ano);
	
					$this->load->model("Acientos_Model",'Acientos');
					$list = $this->Acientos->loadbalance($mes,$ano);
					$recordsFiltered = $this->Acientos->count_filtro_balance($mes,$ano);;
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', '')
						            ->setCellValue('B2', 'Plan de Cuenta')
						            ->setCellValue('C2', 'Debe')
						            ->setCellValue('D2', 'Haber');


						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
			

			$x = 3;
            $hb = 0;
			$db = 0;
			$no = 0;
			foreach ($list as $key => $Acientos) {
			 $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
			 $this->phpexcel->setActiveSheetIndex(0)
				  ->setCellValue('A'.$x, $Acientos->Nombre)
				  ->setCellValue('B'.$x, $Acientos->Balance_General);
				  ;
				if (!is_null($Acientos->debe) &&  !is_null($Acientos->haber)) {
					if ($Acientos->debe > $Acientos->haber) {
						$row1 = $Acientos->debe - $Acientos->haber;
			 		$this->phpexcel->setActiveSheetIndex(0)
				 		 ->setCellValue('C'.$x, number_format($row1,0,',',','));
						$db          +=$Acientos->debe;
						$hb           +=$Acientos->haber;
					}else {
                        $row2 =  $Acientos->haber - $Acientos->debe;
			 		$this->phpexcel->setActiveSheetIndex(0)
				 		 ->setCellValue('D'.$x, number_format($row2,0,',',','));
						$db          +=$Acientos->debe;
						$hb           +=$Acientos->haber;
					}
				}elseif (is_null($Acientos->debe) &&  !is_null($Acientos->haber)) {
			 		$this->phpexcel->setActiveSheetIndex(0)
				 		 ->setCellValue('D'.$x, number_format($Acientos->haber,0,',',','));
						$db          +=$Acientos->debe;
						$hb           +=$Acientos->haber;
				}elseif (!is_null($Acientos->debe) &&  is_null($Acientos->haber)) {
			 		$this->phpexcel->setActiveSheetIndex(0)
				 		 ->setCellValue('C'.$x, number_format($Acientos->debe,0,',',','));
						$db          +=$Acientos->debe;
						$hb           +=$Acientos->haber;
				}
			$no++;
			$x++;
			if ($no == $recordsFiltered) {

			 		$this->phpexcel->setActiveSheetIndex(0)
				 		 ->setCellValue('B'.$x, 'Resultados')
				 		 ->setCellValue('C'.$x, number_format($db,0,',',','))
				 		 ->setCellValue('D'.$x, number_format($hb,0,',',','))
				 		 ;
			$this->phpexcel->getActiveSheet()->getRowDimension($x)->setRowHeight(20);
				$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':D'.$x)->applyFromArray($this->styleHead('B9CCCC'),$this->style_list());
			}
			}

			    $fecha = date("Y-m-d");

						$this->phpexcel->getActiveSheet()->setTitle('Balance'.$fecha);
						$this->output_exel('Balance',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen resultado de busqueda',
									'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}	

	public function PlandeCuenta($id='')
	{
	
		
                 
                $nombre = 'Listados de Plan de Cuenta';
				// Se obtienen los clientes de la base de datos
					$this->db->join('SubPlanCuenta', 'pc.Control = SubPlanCuenta.idSubPlanCuenta', 'left');

                $list = $this->db->get('PlandeCuenta pc')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:C1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Codigo')
						            ->setCellValue('B2', 'Nombre de la Cuenta')
						            ->setCellValue('C2', 'Categoria');


						$this->phpexcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);

						$x = 3;
				foreach ($list as $key => $listc) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $listc->Codificacion)
									->setCellValue('B'.$x,$listc->Balance_General)
									->setCellValue('C'.$x, $listc->Nombre);

									
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('Plan de Cuentas'.$fecha);
						$this->output_exel('Plan de Cuentas',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function cambio($id='')
	{
	
		
                 
                $nombre = 'Listados de Cambios de Moneda';
				// Se obtienen los clientes de la base de datos
				$this->db->join('Cambios', 'Moneda.Cambios_idCambios = Cambios.idCambios', 'INNER');
                $list = $this->db->get('Moneda')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Moneda')
						            ->setCellValue('B2', 'Cambio')
						            ->setCellValue('C2', 'Estado')
						            ->setCellValue('D2', 'Fecha')
						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);


						$x = 3;
				foreach ($list as $key => $Cambio) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $Cambio->Moneda.'  '.$Cambio->Nombre)
									->setCellValue('B'.$x,$Cambio->Compra)
									->setCellValue('D'.$x, date("Y-m-d"));
									if ($Cambio->Estado == 0) {
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('C'.$x, 'Activo');																
									}else{
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('C'.$x, 'Inactivo');								
									}
										
									
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('cambios'.$fecha);
						$this->output_exel('cambios',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function cliente($id='')
	{
	
                $nombre = 'Listados Cliente';
                $list = $this->db->where('idCliente !=1')->get('Cliente')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Nombre')
						            ->setCellValue('B2', 'Apellidos')
						            ->setCellValue('C2', 'Direccion')
						            ->setCellValue('D2', 'Telefono')
						            ->setCellValue('E2', 'Correo')

						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$x = 3;
				foreach ($list as $key => $Cliente) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $Cliente->Nombres)
									->setCellValue('B'.$x,$Cliente->Apellidos)
									->setCellValue('C'.$x,$Cliente->Direccion)
									->setCellValue('D'.$x, $Cliente->Telefono)															
									->setCellValue('E'.$x, $Cliente->Correo);								

									
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('cliente'.$fecha);
						$this->output_exel('cliente',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function proveedor($id='')
	{
	 
                $nombre = 'Listados Proveedor';
                $list = $this->db->get('Proveedor')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:F1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Vendedor')
						            ->setCellValue('B2', 'Ruc')
						            ->setCellValue('C2', 'Razon Social')
						            ->setCellValue('D2', 'Direccion')
						            ->setCellValue('E2', 'Telefono')
						            ->setCellValue('E2', 'Correo')


						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

						$x = 3;
				foreach ($list as $key => $Cliente) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $Cliente->Vendedor)
									->setCellValue('B'.$x,$Cliente->Ruc)
									->setCellValue('C'.$x,$Cliente->Razon_Social)
									->setCellValue('D'.$x, $Cliente->Direccion)															
									->setCellValue('E'.$x, $Cliente->Telefono)
									->setCellValue('F'.$x, $Cliente->Correo);								


									
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('proveedor'.$fecha);
						$this->output_exel('proveedor',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function empleado($id='')
	{
	
                $nombre = 'Listados de Empleado';
                $list = $this->db->get('Empleado')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:F1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Nombres')
						            ->setCellValue('B2', 'Cargo')
						            ->setCellValue('C2', 'Sueldo')
						            ->setCellValue('D2', 'Direccion')
						            ->setCellValue('E2', 'Telefono')
						            ->setCellValue('E2', 'Correo')


						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

						$x = 3;
				foreach ($list as $key => $Empleado) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $Empleado->Nombres.' '.$Empleado->Apellidos)
									->setCellValue('B'.$x,$Empleado->Cargo)
									->setCellValue('C'.$x,$Empleado->Sueldo)
									->setCellValue('D'.$x, $Empleado->Direccion)															
									->setCellValue('E'.$x, $Empleado->Telefono)
									->setCellValue('F'.$x, $Empleado->Correo);								


									
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('Empleado'.$fecha);
						$this->output_exel('Empleado',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}
	public function user($id='')
	{
	
                $nombre = 'Listados de Usuarios';
                $this->db->join('Usuario user', 'user.Permiso_idPermiso = pr.idPermiso', 'INNER');
                $list = $this->db->get('Permiso pr')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				$this->header_exel();
						$this->cabecera($nombre,'A1:C1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Nombre')
						            ->setCellValue('B2', 'Descripción')
						            ->setCellValue('C2', 'Opservacion')
						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);


						$x = 3;
				foreach ($list as $key => $user) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':C'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $user->Usuario)
									->setCellValue('B'.$x,$user->Descripcion)
									->setCellValue('C'.$x, $user->Oservacion);
					

										
									
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('usuario'.$fecha);
						$this->output_exel('usuario',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function empresa($id='')
	{
                $nombre = 'Listados de Empresa';
                $list = $this->db->get('Empresa')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:F1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Nombres')
						            ->setCellValue('B2', 'Direccion')
						            ->setCellValue('C2', 'Telefono')
						            ->setCellValue('D2', 'Correo')
						            ->setCellValue('E2', 'Ruc')
						            ->setCellValue('F2', 'Comprovante')

						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);



						$x = 3;
						foreach ($list as $key => $empresa) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':F'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $empresa->Nombre)
									->setCellValue('B'.$x,$empresa->Direccion)
									->setCellValue('D'.$x, $empresa->Email)
									->setCellValue('C'.$x, $empresa->Telefono)
									->setCellValue('E'.$x, $empresa->R_U_C)
									->setCellValue('F'.$x, $empresa->Comprovante)

									;								
										
									
							$x ++;
						}		
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('empresa'.$fecha);
						$this->output_exel('empresa',$fecha );
			} else {
					$data     = array(
					'titulo'  => 'No existen datos de busqueda',
					'titulo2' => $nombre,
					'titulo3' => 'No existen datos', );
		        	$this->load->view('Error/error_exel.php', $data, FALSE);
			}
	}

	public function permi($id='')
	{
	
                $nombre = 'Listados de Permisos';
				$this->db->where('(idPermiso != 1) AND (idPermiso != 3)');
                $list = $this->db->get('Permiso')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				$this->header_exel();
						$this->cabecera($nombre,'A1:C1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Nombre')
						            ->setCellValue('B2', 'Descripción')
						            ->setCellValue('C2', 'Opservacion')
						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);


						$x = 3;
				$this->load->model('Permiso_Model', 'Permiso');

				foreach ($list as $key => $permiso) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':C'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $permiso->Descripcion)
									->setCellValue('B'.$x,$permiso->Oservacion);
									$hass = $this->Permiso->permiso_has($permiso->idPermiso);
									if ($hass == '') {
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('C'.$x, 'Sin Acceso');

									} else {
									$this->phpexcel->setActiveSheetIndex(0)										
									->setCellValue('C'.$x, 'Permiso');
									}
									
					

										
									
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('permiso'.$fecha);
						$this->output_exel('permiso',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function produccion($id='')
	{
	
		
                 
                $nombre = 'Listados Producto  en Produccion';
				$this->db->join('Producto', 'dp.Producto_idProducto = Producto.idProducto', 'inner');
				$this->db->where('Produccion =2 ');
                $list = $this->db->get('Detale_Produccion dp')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Nombres')
						            ->setCellValue('B2', 'Produccion')
						            ->setCellValue('C2', 'Estado')
						            ->setCellValue('D2', 'Fecha Produccion')

						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);



						$x = 3;
				foreach ($list as $key => $Producto) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':C'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $Producto->Nombre);

									;	
						    if ($Producto->Estado_d == null)
						    {
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('B'.$x,$Producto->CantidadProduccion)

									->setCellValue('D'.$x, '...Produciendo...')

									->setCellValue('C'.$x, $Producto->Fecha_Produccion);
							}
							else
							{
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('B'.$x,$Producto->CantidadProduccion)

									->setCellValue('D'.$x, 'Producto Producido')

									->setCellValue('C'.$x, $Producto->Fecha_Produccion);
							}

										
									
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('produccion'.$fecha);
						$this->output_exel('produccion',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function stock($id='')
	{
	
		
                 
                $nombre = 'Listados Producto  en Stock';
 				 $this->load->model("Producto_Model");
		        if (empty($id)) {
		         $list = $this->Producto_Model->getProducto();
		        }else{
		         $list = $this->Producto_Model->getProducto($id);
		        }

				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:F1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Nombre')
						            ->setCellValue('B2', 'Totales')
						            ->setCellValue('C2', 'Total Stock')
						            ->setCellValue('D2', 'Total Deposito')
						            ->setCellValue('E2', 'Precio Venta')
						            ->setCellValue('F2', 'Unidad || Medida')

						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

						$x = 3;
				foreach ($list as $key => $Producto) {
							$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 10); 
							$Unidad = intval(preg_replace('/[^0-9]+/', '', $Producto->Unidad), 10); 
							$Precio_Venta =  number_format($resultado,0,',',',');
							$cantidad = ($Producto->Cantidad_A + $Producto->Cantidad_D);
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':F'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $Producto->Nombre." (". $Producto->Marca.")")
									->setCellValue('B'.$x, $cantidad )
									->setCellValue('D'.$x, $Producto->Cantidad_A)
									->setCellValue('C'.$x, $Producto->Cantidad_D)
									->setCellValue('E'.$x, $Precio_Venta)
									->setCellValue('F'.$x, $Unidad."  ".$Producto->Medida)

									;								
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('stock'.$fecha);
						$this->output_exel('stock',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function detalleproduccion($id='')
	{
	
		
                 
                $nombre = 'Listados Detalle Produccion';
		        $this->load->model("Produccion_Model",'Producir');
                $list =  $this->Producir->detalle(array('idProduccion' => $id));
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Cantidad')
						            ->setCellValue('B2', 'Nombre')
						            ->setCellValue('C2', 'Precio')
						            ->setCellValue('D2', 'Subtotal');


						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$x = 3;$suma = 0;
				foreach ($list as $key => $listc) {
					    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':D'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $listc->Cantidad)
									->setCellValue('B'.$x, $listc->Nombre)
									->setCellValue('C'.$x, number_format( $listc->Precio,0,',',','))
									->setCellValue('D'.$x, number_format( $listc->Precio * $listc->Cantidad,0,',',','));
									$suma += $listc->Cantidad*$listc->Precio;
							$x ++;
				}
				$val = $x+1;
						$this->phpexcel->setActiveSheetIndex(0)
						->setCellValue('D'.$val, 'Monto total: '.number_format($suma,0,',',','));
						$this->phpexcel->getActiveSheet()->getStyle('D'.$val)->applyFromArray($this->styleHead('F93107'));

						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('detalleproduccion'.$fecha);
						$this->output_exel('detalleproduccion',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function categoria($id='')
	{
	
		
                 
                $nombre = 'Listados de Categoria';
				// Se obtienen los clientes de la base de datos
                $list = $this->db->get('Categoria')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:B1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Categoria')
						            ->setCellValue('B2', 'Descripción')
						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
						$x = 3;
				foreach ($list as $key => $Categoria) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':F'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $Categoria->Categoria)
									->setCellValue('B'.$x, $Categoria->Descrip )
								;								
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('categoria'.$fecha);
						$this->output_exel('categoria',$fecha );

				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function marca($id='')
	{
	
		
                 
                $nombre = 'Listados de Marcas';
				// Se obtienen los clientes de la base de datos
                $list = $this->db->get('Marca')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:B1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Marca')
						            ->setCellValue('B2', 'Descripción')
						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(80);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(80);
						$x = 3;
				foreach ($list as $key => $Categoria) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':F'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $Categoria->Marca)
									->setCellValue('B'.$x, $Categoria->Descripcion )
								;								
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('marca'.$fecha);
						$this->output_exel('marca',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function Descuento($id='')
	{
	
		
                 
                $nombre = 'Listados de Productos Con Descuento';
				// Se obtienen los clientes de la base de datos
				$this->load->model('Descuento_Model','des');
                $list = $this->des->getdescuento();
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:D1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Marca')
						            ->setCellValue('B2', 'Descripción')
						            ->setCellValue('C2', 'Descripción')
						            ->setCellValue('D2', 'Descripción')

						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:D2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);

						$x = 3;
				foreach ($list as $key => $Categoria) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':D'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $Categoria->Nombre)
									->setCellValue('B'.$x, $Categoria->Categoria )
									->setCellValue('C'.$x, $Categoria->Marca )
									->setCellValue('D'.$x, $Categoria->Descuento.' %' )

								;								
							$x ++;
				}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('descuento'.$fecha);
						$this->output_exel('descuento',$fecha );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}

	public function productonull()
	{
				$id = $this->security->xss_clean( $this->input->post('control',FALSE));
		
                 
                $nombre = 'Listados de Marcas';
				$this->db->select('idDetalle_Devolucion,Estado,Motivo,Precio,Cantidad,Nombre,Codigo,Img');
				$this->db->from('Detalle_Devolucion dd');
				$this->db->join('Producto', 'dd.Producto_idProducto = Producto.idProducto', 'inner');
				$this->db->where('Motivo', $id);
				$list = $this->db->get()->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:C1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Codigo')
						            ->setCellValue('B2', 'Produccto')
						            ->setCellValue('C2', $id)

						            ;


						$this->phpexcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);

						$x = 3;
				foreach ($list as $key => $Producto) {
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':C'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x, $Producto->Codigo)
									->setCellValue('B'.$x, $Producto->Nombre )
									->setCellValue('C'.$x, $Producto->Cantidad )

								;								
							$x ++;
				}
						$this->phpexcel->getActiveSheet()->setTitle('producto'.$id);
						$this->output_exel('producto',$id );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}
	public function factura($nombre,$id,$alias)
	{
    	     $this->headfactura($nombre,$alias);
			$this->phpexcel->setActiveSheetIndex(0)
		         ->setCellValue('A2', 'Fecha de Emision:   '.$alias->Fecha_expedicion);

			if ($alias->Tipo_Venta == 0) {
			$this->phpexcel->getActiveSheet()->mergeCells('D2:E2');
			$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('D2', 'Cond. de Venta                            XXX');
			}else{
			$this->phpexcel->getActiveSheet()->mergeCells('D2:E2');
			$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('D2', 'Cond. de Venta                                                         XXX')	;

			}

		     			$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
									$this->phpexcel->getActiveSheet()->mergeCells('D3:E3');
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A3', 'Senhor (ES): '.$alias->Nombres.' '.$alias->Apellidos)
									->setCellValue('D3', 'RUC/CI :                              '.$alias->Ruc);
									$this->phpexcel->getActiveSheet()->mergeCells('D4:E4');
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A4', 'Nota de Remision:')

									->setCellValue('D4', 'Telefono                                '.$alias->Telefono);


						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A5', 'Cantidad')
						            ->setCellValue('B5', 'Descripcion de Mercadería y/o Servicios')
						            ->setCellValue('C5', 'Impuesto')
						            ->setCellValue('D5', 'Precio')
						            ->setCellValue('E5', 'Subtotal')
						            ;
						$this->phpexcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray($this->styleHead('7F3C2E'));
						$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('B6', $alias->Concepto)
									->setCellValue('C6', number_format($alias->Monto_total_Iva,0,',',',') )
									->setCellValue('D6', number_format($alias->Monto_Total,0,',',',') )
									->setCellValue('E6', number_format($alias->Monto_Total,0,',',',') );		

						$this->phpexcel->getActiveSheet()->mergeCells('D8:E8');
						$this->phpexcel->setActiveSheetIndex(0)
						->setCellValue('D8', 'Total A Pagar: '.number_format($alias->Monto_Total-$alias->Monto_total_Iva,0,',',','));
						$this->phpexcel->getActiveSheet()->getStyle('D8')->applyFromArray($this->styleHead(''));
						$this->phpexcel->getActiveSheet()->mergeCells('D9:E9');
						$this->phpexcel->setActiveSheetIndex(0)
						->setCellValue('A9', 'Liquidacion del Iva (5%):')
						->setCellValue('B9', '(10%):  '.number_format($alias->Monto_total_Iva))
						->setCellValue('D9', 'Total IVA:  '.number_format($alias->Monto_total_Iva));
						$this->phpexcel->getActiveSheet()->getStyle('A9:E9')->applyFromArray($this->styleHead(''));

					    $this->phpexcel->getActiveSheet()->setTitle('Factura'.$id);
						$this->output_exel('Factura',$id );
	}

   public function headboleta($nombre,$id, $objeto)
   {
					    $this->db->select('*');
					    $query = $this->db->get('Empresa');
					    $row = $query->row();
   	                    $this->phpexcel->getActiveSheet()->getStyle('A1')->applyFromArray($this->style_horizontal());
    					
    					$this->phpexcel->getActiveSheet()
							 ->getStyle('A1')
							 ->getAlignment()
							 ->setWrapText(true);
						$this->phpexcel->setActiveSheetIndex(0)
						     ->setCellValue('A1', $row->Nombre.PHP_EOL. $nombre); 
    					$this->phpexcel->getActiveSheet()->mergeCells('B1:C1');
						$this->phpexcel->setActiveSheetIndex(0)
						     ->setCellValue('B1','Direccion : '.$row->Direccion.PHP_EOL. 'Telefono : '.$row->Telefono.PHP_EOL.'Correo  : '.$row->Email   );
						$this->phpexcel->getActiveSheet()
							 ->getStyle('B1')
							 ->getAlignment()
							 ->setWrapText(true);

						$this->phpexcel->getActiveSheet()->mergeCells('D1:E1');
						$this->phpexcel->setActiveSheetIndex(0)
						     ->setCellValue('D1',$nombre.PHP_EOL. 'R.U.C :  '.$row->R_U_C .PHP_EOL.'Nº :  '.$objeto  );
						$this->phpexcel->getActiveSheet()
							 ->getStyle('D1')
							 ->getAlignment()
							 ->setWrapText(true);
						$this->phpexcel->getActiveSheet()->getStyle('D1:E1')->applyFromArray($this->style_horizontal());
					    $this->phpexcel->getActiveSheet()->getStyle('A2:E2')
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(60);	

   }

      public function headfactura($nombre='',$objeto = null)
   {
					    $this->db->select('*');
					    $query = $this->db->get('Empresa');
					    $row = $query->row();
   	                    $this->phpexcel->getActiveSheet()->getStyle('A1')->applyFromArray($this->style_horizontal());
    					
    					$this->phpexcel->getActiveSheet()
							 ->getStyle('A1')
							 ->getAlignment()
							 ->setWrapText(true);
						$this->phpexcel->setActiveSheetIndex(0)
						     ->setCellValue('A1', $row->Nombre); 
    					$this->phpexcel->getActiveSheet()->mergeCells('B1:C1');
						$this->phpexcel->setActiveSheetIndex(0)
						     ->setCellValue('B1','Direccion : '.$row->Direccion.PHP_EOL. 'Telefono : '.$row->Telefono.PHP_EOL.'Correo  : '.$row->Email   );
						$this->phpexcel->getActiveSheet()
							 ->getStyle('B1')
							 ->getAlignment()
							 ->setWrapText(true);

						$this->phpexcel->getActiveSheet()->mergeCells('D1:E1');
						if (!is_null($objeto)) {
						$this->phpexcel->setActiveSheetIndex(0)
						     ->setCellValue('D1',$nombre.PHP_EOL. 'R.U.C :  '.$row->R_U_C .PHP_EOL.'Nº :  '.$row->Series.' - '.$row->Timbrado.'                '.$objeto->Num_Factura_Venta);
						$this->phpexcel->getActiveSheet()
							 ->getStyle('D1')
							 ->getAlignment()
							 ->setWrapText(true);
						}else{
						}

						$this->phpexcel->getActiveSheet()->getStyle('D1:E1')->applyFromArray($this->style_horizontal());
					    $this->phpexcel->getActiveSheet()->getStyle('A2:E2')
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(60);	


   }
      public function head_factura($nombre='',$objeto = null)
   {
   	                    $this->phpexcel->getActiveSheet()->getStyle('A1')->applyFromArray($this->style_horizontal());
    					
    					$this->phpexcel->getActiveSheet()
							 ->getStyle('A1')
							 ->getAlignment()
							 ->setWrapText(true);
    					$this->phpexcel->getActiveSheet()->mergeCells('A1:E1');
						if (!is_null($objeto)) {
						$this->phpexcel->setActiveSheetIndex(0)
						     ->setCellValue('A1',$nombre.PHP_EOL);
						$this->phpexcel->getActiveSheet()
							 ->getStyle('D1')
							 ->getAlignment()
							 ->setWrapText(true);
						}else{
						}

						$this->phpexcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($this->style_horizontal());
					    $this->phpexcel->getActiveSheet()->getStyle('A2:E2')
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(60);	


   }
	public function boletas($nombre,$id, $alias)
	{

		     			$this->headboleta($nombre, $alias, $alias->Ticket );


						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Fecha de Emision:   '.$alias->Fecha_expedicion);

			if ($alias->Tipo_Venta == 0) {
			$this->phpexcel->getActiveSheet()->mergeCells('D2:E2');
			$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('D2', 'Cond. de Venta                            XXX');
			}else{
			$this->phpexcel->getActiveSheet()->mergeCells('D2:E2');
			$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('D2', 'Cond. de Venta                                                         XXX')	;

			}

		     			$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
									$this->phpexcel->getActiveSheet()->mergeCells('D3:E3');
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A3', 'Senhor (ES): '.$alias->Nombres.' '.$alias->Apellidos)
									->setCellValue('D3', 'RUC/CI :                              '.$alias->Ruc);
									$this->phpexcel->getActiveSheet()->mergeCells('D4:E4');
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A4', 'Nota de Remision:')

									->setCellValue('D4', 'Telefono                                '.$alias->Telefono);

						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A5', 'Cantidad')
						            ->setCellValue('B5', 'Descripcion de Mercadería y/o Servicios')
						            ->setCellValue('C5', 'Impuesto')
						            ->setCellValue('D5', 'Precio')
						            ->setCellValue('E5', 'Subtotal')
						            ;
						$this->phpexcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray($this->styleHead('7F3C2E'));
	

					    $this->phpexcel->getActiveSheet()->getStyle('A6'.':C6')->applyFromArray($this->style_list());
						$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('B6', $alias->Concepto)
									->setCellValue('D6', number_format($alias->Monto_Total,0,',',',') )
									->setCellValue('E6', number_format($alias->Monto_Total,0,',',',') )

								;		
						$this->phpexcel->getActiveSheet()->mergeCells('D8:E8');
						$this->phpexcel->setActiveSheetIndex(0)
						->setCellValue('D8', 'Total A Pagar: '.number_format($alias->Monto_Total,0,',',','));
						$this->phpexcel->getActiveSheet()->getStyle('D8')->applyFromArray($this->styleHead('F93107'));

						$this->phpexcel->getActiveSheet()->setTitle('comprobante'.$id);
						$this->output_exel('comprobante',$id );
	}
	public function cob_ro($id='')
	{
				 
				// Se obtienen los clientes de la base de datos
		        $this->db->select('*');
		        $this->db->join('Cuenta_Corriente_Cliente ccc', 'Caja_Cobros.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = ccc.idCuenta_Corriente_Cliente', 'left');
		        $this->db->join('Cliente', 'ccc.Cliente_idCliente = Cliente.idCliente', 'left');
		         $this->db->join('Proveedor', 'ccc.Proveedor_idProveedor = Proveedor.idProveedor', 'left');
		        $this->db->where('idCuenta_Corriente_Cliente', $id);
		        $query1=$this->db->get('Caja_Cobros');
		        $row = $query1->row();
		        // echo var_dump($row);
		        $nombre = 'Boleta de Venta';

				if( !empty(  $row ) ){
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);

							$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A2', 'Fecha de Emision:   '.$row->Fecha,'')
									->setCellValue('B2', '')
									->setCellValue('C2', 'Recibo Cuota Nº '.$row->Num_Recibo)
									->setCellValue('D2', 'Cond. de Venta ')
									->setCellValue('E2', 'XXX');
							$this->phpexcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($this->style_list());		
							if ($row->idCliente) {
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A3', 'Senhor (ES)')
									->setCellValue('B3', $row->Nombres.' '.$row->Apellidos)
									->setCellValue('C3', 'RUC/CI :   ')
									->setCellValue('D3', $row->Ruc)
									->setCellValue('E3', 'XXX');
						     }else{
									$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A3', 'Senhor (ES)')
									->setCellValue('B3', $row->Razon_Social.' '.$row->Vendedor)
									->setCellValue('C3', 'RUC/CI :   ')
									->setCellValue('D3', $row->Ruc)
									->setCellValue('E3', 'XXX');
						     }
							$this->phpexcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray($this->style_list());		
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A4', 'Nota de Remision:')
									->setCellValue('D4', 'Telefono')
									->setCellValue('E4', $row->Telefono);


						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A5', 'Cantidad')
						            ->setCellValue('B5', 'Descripción de Mercadería y/o Servicios')
						            ->setCellValue('C5', 'Inpuesto')
						            ->setCellValue('D5', 'Precio')
						            ->setCellValue('E5', 'Subtotal')

						            ;
						$this->phpexcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	
							$mmonto = intval(preg_replace('/[^0-9]+/', '', $row->Monto), 10); 
							if ($row->Importe > $mmonto) {
							   $Monto = $mmonto;
							} else {
							  $Monto = $row->Importe;
							}
							$this->phpexcel->getActiveSheet()->getStyle('A6:E6')->applyFromArray($this->style_list());		
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A6', '1')
									->setCellValue('B6', 'Cobro Cuota Nº '.$row->Num_cuota)
									->setCellValue('D6', number_format( $Monto,0,',',','))
									->setCellValue('E6', number_format( $Monto,0,',',','))

									;

						$this->phpexcel->setActiveSheetIndex(0)
						->setCellValue('E8', 'Monto total: '.number_format($Monto,0,',',','));
						$this->phpexcel->getActiveSheet()->getStyle('E8')->applyFromArray($this->styleHead('F93107'));
						$this->phpexcel->getActiveSheet()->setTitle('pagos'.$id);
						$this->output_exel('pagos',$id );
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error_exel.php', $data, FALSE);
				}
	}


	public function cobro($id='')
	{
		        $this->db->select('*');
		        $this->db->join('Cliente', 'fv.Cliente_idCliente = Cliente.idCliente', 'left');
		        $this->db->where('idFactura_Venta', $id);
		        $query1=$this->db->get('Factura_Venta fv');
		        $row = $query1->row();
		        if ($row->Num_Factura_Venta<1) {
		        	 $nombre = 'Boleta';

		        	 $this->boletas($nombre,$id,$row);
		        }else{
		        	 $nombre = 'Factura';
		        	 $this->factura($nombre,$id, $row );
		        }

	}

	public function pago($id='')
	{           
                $this->db->join('Cuenta_Corriente_Empresa  cce', 'Caja_Pagos.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cce.idCuenta_Corriente_Empresa', 'left');
		        $this->db->join('Factura_Compra', 'Caja_Pagos.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra  ', 'left');
		        $this->db->where('idFactura_Compra', $id);
		        $this->db->or_where('idCuenta_Corriente_Empresa',  $id);
		        $query1=$this->db->get('Caja_Pagos');
		        $row = $query1->row();
		         if ($row->Num_factura_Compra<1) {
		        	 // echo var_dump($row);

		        	 $nombre = strtoupper('Boleta de pago');

		        	 $this->bolet_a($nombre,$id,$row);
		        }else{
		        	 $nombre = strtoupper('Factura de PaGo');
		        	 // echo var_dump($row);
		        	 $this->factura_compra($nombre,$id, $row );
		        }
	}

	public function factura_compra($nombre,$id,$alias)
	{
    	     $this->head_factura($nombre,$alias);
					    $this->db->select('*');
					    $query = $this->db->get('Empresa');
					    $row = $query->row();
   	                    $this->phpexcel->getActiveSheet()->getStyle('B1')->applyFromArray($this->style_horizontal());
    					
      		$this->phpexcel->setActiveSheetIndex(0)
		         ->setCellValue('A2', 'Fecha de Emision:   '.$alias->Fecha_expedicion);

			if ($alias->Tipo_Compra == 0) {
			$this->phpexcel->getActiveSheet()->mergeCells('D2:E2');
			$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('D2', 'Cond. de Pago                            XXX');
			}else{
			$this->phpexcel->getActiveSheet()->mergeCells('D2:E2');
			$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('D2', 'Cond. de Pago                                                         XXX')	;

			}

		     			$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
									$this->phpexcel->getActiveSheet()->mergeCells('D3:E3');
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A3', 'Nombre o Razón Social: '.$row->Nombre)
									->setCellValue('D3', 'RUC/CI :                              '.$row->R_U_C);
									$this->phpexcel->getActiveSheet()->mergeCells('D4:E4');
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A4', 'Nota de Remision:')

									->setCellValue('D4', 'Telefono                                '.$row->Telefono);


						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A5', 'Cantidad')
						            ->setCellValue('B5', 'Descripcion de Mercadería y/o Servicios')
						            ->setCellValue('C5', 'Impuesto')
						            ->setCellValue('D5', 'Precio')
						            ->setCellValue('E5', 'Subtotal')
						            ;
						$this->phpexcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray($this->styleHead('7F3C2E'));
						$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('B6', $alias->Concepto)
									->setCellValue('C6', number_format($alias->Monto_Total_Iva,0,',',',') )
									->setCellValue('D6', number_format($alias->Monto,0,',',',') )
									->setCellValue('E6', number_format($alias->Monto,0,',',',') );		

						$this->phpexcel->getActiveSheet()->mergeCells('D8:E8');
						$this->phpexcel->setActiveSheetIndex(0)
						->setCellValue('D8', 'Total A Pagar: '.number_format($alias->Monto,0,',',','));
						$this->phpexcel->getActiveSheet()->getStyle('D8')->applyFromArray($this->styleHead(''));
						$this->phpexcel->getActiveSheet()->mergeCells('D9:E9');
						$this->phpexcel->setActiveSheetIndex(0)
						->setCellValue('A9', 'Liquidacion del Iva (5%):')
						->setCellValue('B9', '(10%):  '.number_format($alias->Monto_Total_Iva))
						->setCellValue('D9', 'Total IVA:  '.number_format($alias->Monto_Total_Iva));
						$this->phpexcel->getActiveSheet()->getStyle('A9:E9')->applyFromArray($this->styleHead(''));

					    $this->phpexcel->getActiveSheet()->setTitle('Factura'.$id);
						$this->output_exel('Factura',$id );
	}


	public function bolet_a($nombre,$id, $alias)
	{
    	     $this->head_factura($nombre,$alias);
					    $this->db->select('*');
					    $query = $this->db->get('Empresa');
					    $row = $query->row();
   	                    $this->phpexcel->getActiveSheet()->getStyle('B1')->applyFromArray($this->style_horizontal());
    					
      		$this->phpexcel->setActiveSheetIndex(0)
		         ->setCellValue('A2', 'Fecha de Emision:   '.$alias->Fecha_expedicion);

			if ($alias->Tipo_Compra == 0) {
			$this->phpexcel->getActiveSheet()->mergeCells('D2:E2');
			$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('D2', 'Cond. de Pago                            XXX');
			}else{
			$this->phpexcel->getActiveSheet()->mergeCells('D2:E2');
			$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('D2', 'Cond. de Pago                                                         XXX')	;

			}

		     			$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
									$this->phpexcel->getActiveSheet()->mergeCells('D3:E3');
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A3', 'Nombre o Razón Social: '.$row->Nombre)
									->setCellValue('D3', 'RUC/CI :                              '.$row->R_U_C);
									$this->phpexcel->getActiveSheet()->mergeCells('D4:E4');
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A4', 'Nota de Remision:')

									->setCellValue('D4', 'Telefono                                '.$row->Telefono);


						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A5', 'Cantidad')
						            ->setCellValue('B5', 'Descripcion de Mercadería y/o Servicios')
						            ->setCellValue('C5', 'Impuesto')
						            ->setCellValue('D5', 'Precio')
						            ->setCellValue('E5', 'Subtotal')
						            ;
						$this->phpexcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray($this->styleHead('7F3C2E'));
						if (!empty($alias->idCuenta_Corriente_Empresa)) {
							if ($alias->Monto > $alias->Importe) {
								$monto = $alias->Importe;
							} else {
								$monto = $alias->Monto;
							}
						$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('B6', 'Pago de Cuota Nº '.$alias->Num_Cuotas)
									->setCellValue('D6', number_format($monto,0,',',',') )
									->setCellValue('E6', number_format($monto,0,',',',') );	
						$this->phpexcel->getActiveSheet()->mergeCells('D8:E8');
						$this->phpexcel->setActiveSheetIndex(0)
						->setCellValue('D8', 'Total A Pagar: '.number_format($monto ,0,',',','));
						$this->phpexcel->getActiveSheet()->getStyle('D8')->applyFromArray($this->styleHead(''));
						$this->phpexcel->getActiveSheet()->mergeCells('D9:E9');
						} else {
						$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('B6', $alias->Concepto)
									->setCellValue('D6', number_format($alias->Monto,0,',',',') )
									->setCellValue('E6', number_format($alias->Monto,0,',',',') );	
							$this->phpexcel->getActiveSheet()->mergeCells('D8:E8');
						$this->phpexcel->setActiveSheetIndex(0)
						->setCellValue('D8', 'Total A Pagar: '.number_format($alias->Monto,0,',',','));
						$this->phpexcel->getActiveSheet()->getStyle('D8')->applyFromArray($this->styleHead(''));
						$this->phpexcel->getActiveSheet()->mergeCells('D9:E9');
						}
	



					    $this->phpexcel->getActiveSheet()->setTitle('boleta'.$id);
						$this->output_exel('boleta',$id );
	}






	private function monto_inicial($id)
	{
		if (empty($id)) {
				$this->db->select('SUM(Monto_inicial) AS Monto');
				$query = $this->db->get('Caja');
				$row = $query->row();
				return $row->Monto;


		}else {
				$this->db->select('Monto_inicial');
				$this->db->where("idCaja",$id);
			    $query = $this->db->get('Caja');
				$row = $query->row();
				return $row->Monto_inicial;
		}

	}



    // end: setExcel
}
// end: excel