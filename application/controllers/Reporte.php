<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Classes/PHPExcel.php');
			if (!$this->session->userdata('idUsuario')) {
				redirect('Ingresar','refresh');
			} 

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

		public function Cajas( $offset = 0 )
	{
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Reportes/Caja.php');
									$this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/pie_js.php');
									$this->load->view('Reportes/script.php');
									// $this->output->enable_profiler(TRUE);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(20);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
										$data       = array (	"Alerta" => $this->Producto->get_alert(),
																		'data_view' => $variable,
																		);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Reportes/Caja.php');
												$this->load->view('Home/sidebar.php',FALSE);
												$this->load->view('Home/pie_js.php');
												$this->load->view('Reportes/script.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}


				}

	}

	public function Caja($value='')
	{
			$inicio      = $this->security->xss_clean($this->input->post('fecha',FALSE));
			$fin         = $this->security->xss_clean($this->input->post('fecha2',FALSE));
			$caja        = $this->security->xss_clean($this->input->post('caja',FALSE));
			$reservation = $this->security->xss_clean($this->input->post('reservation',FALSE));
			if ($caja > 0) {
				$nombre = 'Reporte de Caja Nº '.$caja.'';
			}else{
				$nombre = 'Reporte de Caja ';
			}
			if (!empty($reservation)) {
				switch ($reservation) {
					case '0':
						$where = 'cp.Estado = 0 ';
						$or_where ='cc.Estado = 0 ';
						break;
					case '1':
						$where = 'cp.Moneda_idMoneda IS NOT NULL AND cp.Estado = 0';
						$or_where ='cc.Moneda_idMoneda IS NOT NULL AND cc.Estado = 0';
						break;
					case '2':
						$where = 'cp.Movimientos_idMovimientos IS NOT NULL AND cp.Estado = 0';
						$or_where = 'cc.Movimientos_idMovimientos IS NOT NULL AND cc.Estado = 0';
						break;
					case '3':
						$where    = 'cp.Tarjeta_idTarjeta IS NOT NULL AND cp.Estado = 0';
						$or_where = 'cc.Tarjeta_idTarjeta IS NOT NULL AND cc.Estado = 0';
						break;
				}

			}else{
					$where =  "cp.Estado = 0" ;
					$or_where = "cc.Estado = 0" ;
			}
			if ($inicio == $fin ) {
				$BETWEEN     = "= '".$inicio."'";
				$nombrefecha = "Fecha : ".$inicio;
			}else{
				$BETWEEN     = "BETWEEN '".$inicio."' AND '".$fin."'";
				$nombrefecha = "Fecha Desde ".$inicio."  Hasta  ".$fin;
			}


		if ($caja > 0) {
		$consult="
		(SELECT CONCAT(cp.Fecha,' ', cp.Hora) as fecha, Descripcion as descripcion, Null as debe, Monto as haber, cp.Caja_idCaja from Caja_Pagos cp
		WHERE cp.Fecha  $BETWEEN 
		AND  cp.Caja_idCaja ='".$caja."' AND ".$where." 
		)
		UNION ALL
		(SELECT CONCAT(cc.Fecha,' ', cc.Hora) as fecha, Descripcion as descripcion, Monto as debe, Null as haber, cc.Caja_idCaja from Caja_Cobros cc
		WHERE cc.Fecha  $BETWEEN 
		AND cc.Caja_idCaja ='".$caja."' AND ".$or_where."   )
		";
		}else{
		$consult="
		(SELECT CONCAT(cp.Fecha,' ', cp.Hora) as fecha, Descripcion as descripcion, Null as debe, Monto as haber, cp.Caja_idCaja from Caja_Pagos cp
		WHERE cp.Fecha  $BETWEEN 
		AND  ".$where."  )
		UNION ALL
		(SELECT CONCAT(cc.Fecha,' ', cc.Hora) as fecha, Descripcion as descripcion, Monto as debe, Null as haber, cc.Caja_idCaja from Caja_Cobros cc
		WHERE cc.Fecha  $BETWEEN 
		AND ".$or_where." )
		";
		}

		$query = $this->db->query($consult);

		if ($query->num_rows() > 0) {
			if (empty($value)) {
				$this->load->library('pdf');
				$this->pdf = new Pdf();
				// Agregamos una página
				$this->pdf->AddPage();
				// Define el alias para el número de página que se imprimirá en el pie
				$this->Header($nombre,$nombrefecha);
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);
				$this->pdf->SetFillColor(200,200,200);
				$this->pdf->SetFont('Arial', '', 9);
				$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
				$this->pdf->Cell(20,7,'Usuario:','TBL',0,'C','1');
				$this->pdf->Cell(75,7,utf8_decode('Descripción:'),'TBL',0,'C','1');
				$this->pdf->Cell(35,7,'Fecha Hora','TB',0,'C','1');
				$this->pdf->Cell(25,7,'Ingreso','TB',0,'C','1');
				$this->pdf->Cell(25,7,'Egreso','TB BR ',0,'C','1');
				$this->pdf->Ln(7);
				$x = 0;
				$recordsFiltered = $query->num_rows();
				$monto_inicial  = $this->monto_inicial($caja);
				$haber = 0;
				$debe = 0;
				$total = 0;
				$as = '';
				$monto_inicial =  str_replace($this->config->item('caracteres'),"",$monto_inicial);
				foreach ($query->result() as $caja) {
						$this->db->select('Usuario');
						$this->db->join('Usuario', 'Caja.Usuario_idUsuario = Usuario.idUsuario', 'inner');
						$this->db->where('idCaja', $caja->Caja_idCaja);
						$query = $this->db->get('Caja');
						$row = $query->row();
					$resultadohaber = str_replace($this->config->item('caracteres'),"",$caja->haber);
					$resultadodebe  = str_replace($this->config->item('caracteres'),"",$caja->debe);
					$haber          +=$resultadohaber;
					$debe           +=$resultadodebe;
					$x++;
					$Descrip = $this->mi_libreria->getSubString($caja->descripcion,40);
                    $this->pdf->Cell(10,5,$x,'BL',0,'L',0);
                    $this->pdf->Cell(20,5,$row->Usuario,'BL',0,'L',0);
                    $this->pdf->Cell(75,5,utf8_decode($Descrip),'BL',0,'L',0);
					$this->pdf->Cell(35,5,$caja->fecha,'BL',0,'L',0);
					if ($caja->debe > 0) {
					    $this->pdf->Cell(25,5,number_format($caja->debe,0,'.',','),'BL',0,'L',0);
					}else {
					    $this->pdf->Cell(25,5,$caja->debe,'BL',0,'L',0);
						}
					if ($caja->haber > 0) {
						$this->pdf->Cell(25,5,number_format($caja->haber,0,'.',','),'BL BB BR',0,'L',0);
						}else {
						$this->pdf->Cell(25,5,$caja->haber,'BL BB BR ',0,'L',0);
					}
					$this->pdf->Ln(5);
					////////////////////////////
					if ($x == $recordsFiltered) {
						if ($haber < $debe) {
							$as = $debe - $haber;
							$total =  number_format($as,0,'.',',');
							for ($i = 0; $i <1 ; $i++) {
			                    $this->pdf->Cell(10,5,'','BL',0,'L',0);
			                    $this->pdf->Cell(70,5,'','BL',0,'L',0);
								$this->pdf->Cell(50,5,'','B',0,'L',0);
							    $this->pdf->Cell(30,5,'Parcial','B',0,'C',0);
								$this->pdf->Cell(30,5,$total,'BB BR ',0,'C',0);
							}
						} else {
							$as = $debe - $haber;
							$total =  number_format($as,0,'.',',');
							for ($i = 0; $i <1 ; $i++) {
                                $this->pdf->Cell(10,5,'','BL',0,'L',0);
			                    $this->pdf->Cell(70,5,'','BL',0,'L',0);
								$this->pdf->Cell(50,5,'','B',0,'L',0);
							    $this->pdf->Cell(30,5,'Parcial','B',0,'C',0);
								$this->pdf->Cell(30,5,$total,'BB BR ',0,'C',0);

							}

						} 
						$this->pdf->Ln(5);
					}
				}
				if ($monto_inicial < $as) {
					$monto_final =  number_format($as + $monto_inicial,0,'.',',');
					            $this->pdf->Cell(10,5,'','TBL',0,'C',0);
			                    $this->pdf->Cell(70,5,'Inicial','TBL',0,'C',0);
								$this->pdf->Cell(50,5,number_format($monto_inicial,0,'.',','),'TB',0,'L',0);
							    $this->pdf->Cell(30,5,'Total','TB',0,'C',0);
								$this->pdf->Cell(30,5,$monto_final,'TB BR ',0,'C',0);
				} else {
					$monto_final =  number_format($monto_inicial + $as,0,'.',',');
					            $this->pdf->Cell(10,5,'','TBL',0,'C',0);
			                    $this->pdf->Cell(70,5,'Inicial','TBL',0,'C',0);
								$this->pdf->Cell(50,5,number_format($monto_inicial,0,'.',','),'TB',0,'L',0);
							    $this->pdf->Cell(30,5,'Total','TB',0,'C',0);
								$this->pdf->Cell(30,5,$monto_final,'TB BR ',0,'C',0);
				}

			$this->pdf->Output('pdf.pdf','F');  //save pdf
			$this->pdf->Output('pdf.pdf', 'I'); // show pdf
				
			}else{

;
						$this->header_exel();
						if (empty($caja)) {
							$this->cabecera('Listado de Movimiento  Caja ','A1:F1');	
						}else{
							$this->cabecera('Listado de Movimiento Segun Caja Nº '.$caja,'A1:F1');	
						}
		
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

						$recordsFiltered = $query->num_rows();
						$monto_inicial  = $this->monto_inicial($caja);
						$haber = 0;
						$debe = 0;
						$total = 0;
						$as = '';
						$monto_inicial =  str_replace($this->config->item('caracteres'),"",$monto_inicial);
						foreach ($query->result() as $caja) {
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
						$this->phpexcel->getActiveSheet()->setTitle('Caja');
						// salida#]
						$this->output_exel('Caja' );				
			}


		}else{
				$data     = array(
					'titulo'  => 'No existen datos de busqueda',
					'titulo2' => $nombre,
					'titulo3' => 'No existen datos', );
				if (empty($value)) {
					$this->load->view('Error/error.php', $data, FALSE);
				}else{
					$this->load->view('Error/error_exel.php', $data, FALSE);
				}
		}



	}
    public function Header($nombre,$nombrefecha){
    $this->db->select('Nombre');
    $query = $this->db->get('Empresa');
    $row = $query->row();
    $this->pdf->SetFont('Arial','B',13);
    $this->pdf->Cell(30);
    $this->pdf->Cell(80,10,$row->Nombre,0,0,'R');
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->Cell(80,10,$nombrefecha,0,0,'R');
    $this->pdf->Ln(5);
    $this->pdf->SetFont('Arial','B',10); 
    $this->pdf->Cell(190,10,$nombre,0,0,'C');
    $this->pdf->Ln(10);
   }
	private function monto_inicial($id)
	{
		if (empty($id)) {
				$this->db->select('SUM(Monto_inicial) AS Monto_inicial');
				$query = $this->db->get('Caja');
				$row = $query->row();
				return $row->Monto_inicial;


		}else {
				$this->db->select('Monto_inicial');
				$this->db->where("idCaja",$id);
			    $query = $this->db->get('Caja');
				$row = $query->row();
				return $row->Monto_inicial;
		}

	}

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

	public function Compra( $offset = 0 )
	{
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Reportes/Compra.php');
									$this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/pie_js.php');
									$this->load->view('Reportes/script_Compra.php');
									// $this->output->enable_profiler(TRUE);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(20);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
										$data       = array (	"Alerta" => $this->Producto->get_alert(),
																		'data_view' => $variable,
																		);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Reportes/Compra.php');
												$this->load->view('Home/sidebar.php',FALSE);
												$this->load->view('Home/pie_js.php');
												$this->load->view('Reportes/script_Compra.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}


				}

	}
public function Compras($value='')
	{
		// $this->output->enable_profiler(TRUE);
			$inicio      = $this->security->xss_clean($this->input->post('fecha',FALSE));
			$fin         = $this->security->xss_clean($this->input->post('fecha2',FALSE));
			$caja        = $this->security->xss_clean($this->input->post('caja',FALSE));
			$reservation = $this->security->xss_clean($this->input->post('reservation',FALSE));
			$nombre = 'Reporte de Compras ';
			if (!empty($reservation)) {
				switch ($reservation) {
					case '0':
						$where = 'fa.Estado != 4';
						break;
					case '1':
						$where = 'fa.Estado != 4  AND fa.Estado = 0 ';
						break;
					case '2':
						$where = 'fa.Estado != 4  AND fa.Estado = 1 OR fa.Estado = 2';
						break;
				}

			}else{
					$where =  "fa.Estado != 4" ;
			}
			if ($inicio == $fin ) {
				$BETWEEN     = "Fecha_expedicion = '$inicio'";
				$nombrefecha = "Fecha : ".$inicio;
			}else{
				$BETWEEN     = "Fecha_expedicion BETWEEN '$inicio 'AND '$fin'";
				$nombrefecha = "Fecha Desde ".$inicio."  Hasta  ".$fin;
			}
        $this->db->select('Ticket,idFactura_Compra,Fecha_expedicion,Hora,Concepto,Estado,Num_factura_Compra,Tipo_Compra,Monto_Total,Contado_Credito,idProveedor,Insert,Ruc,Razon_Social,Vendedor');
        $this->db->join('Proveedor pr', 'fa.Proveedor_idProveedor = pr.idProveedor', 'inner');
        $this->db->where($BETWEEN );
        $this->db->where($where);
        $query = $this->db->get('Factura_Compra fa');
		if ($query->num_rows() > 0) {
			$nombre = 'Reporte de Compras ';
			if (empty($value)) {
				$this->load->library('pdf');
				$this->pdf = new Pdf();
				$this->pdf->AddPage();
				$this->Header($nombre ,$nombrefecha);
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);
				$this->pdf->SetFont('Arial', 'B', 9);
				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(45,7,'Comprobante N.','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Provvedor','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Fecha ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Estado ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Monto Total','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
				foreach ($query->result() as $key => $Compra) {
				if ($Compra->Estado == 0) {
							
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($Compra->Tipo_Compra == 0) {
								$this->pdf->Cell(45,5,'Recibo N.'.$Compra->Ticket,'BL',0,'C',0);
							}else{
								$this->pdf->Cell(45,5,'Factura N. '. $Compra->Num_factura_Compra,'BL',0,'C',0);
							}
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Compra->Razon_Social.'-'.$Compra->Vendedor, 25),'BL',0,'C',0);
							$this->pdf->Cell(35,5,$Compra->Fecha_expedicion.'  '.$Compra->Hora,'BL ',0,'C',0);
							$this->pdf->Cell(30,5,'Pagado','BL ',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $Compra->Monto_Total,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
				}elseif ($Compra->Estado == 1) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($Compra->Tipo_Compra == 0) {
								$this->pdf->Cell(45,5,'Recibo N.'.$Compra->Ticket,'BL',0,'C',0);
							}else{
								$this->pdf->Cell(45,5,'Factura N. '. $Compra->Num_factura_Compra,'BL',0,'C',0);
							}

							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Compra->Razon_Social.'-'.$Compra->Vendedor, 25),'BL',0,'C',0);
							$this->pdf->Cell(35,5,$Compra->Fecha_expedicion.'  '.$Compra->Hora,'BL ',0,'C',0);
							$this->pdf->Cell(30,5,'Parcial','BL ',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $Compra->Monto_Total,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
				}elseif ($Compra->Estado == 2) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($Compra->Tipo_Compra == 0) {
								$this->pdf->Cell(45,5,'Recibo N.'.$Compra->Ticket,'BL',0,'C',0);
							}else{
								$this->pdf->Cell(45,5,'Factura N. '. $Compra->Num_factura_Compra,'BL',0,'C',0);
							}

							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Compra->Razon_Social.'-'.$Compra->Vendedor, 25),'BL',0,'C',0);
							$this->pdf->Cell(35,5,$Compra->Fecha_expedicion.'  '.$Compra->Hora,'BL ',0,'C',0);
							$this->pdf->Cell(30,5,'No Pagado','BL ',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $Compra->Monto_Total,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
				}


				}
        		$this->pdf->Output('pdf/Compra/'.$nombrefecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Compra/'.$nombrefecha.'.pdf', 'I'); // show pdf pdf.pdf
			}else{

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
				foreach ($query->result() as $key => $Compra) {
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
			}
		}else{
				$data     = array(
					'titulo'  => 'No existen datos de busqueda',
					'titulo2' => $nombre,
					'titulo3' => 'No existen datos', );
				if (empty($value)) {
					$this->load->view('Error/error.php', $data, FALSE);
				}else{
					$this->load->view('Error/error_exel.php', $data, FALSE);
				}
		}

	}

	public function Venta( $offset = 0 )
	{
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Reportes/Venta.php');
									$this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/pie_js.php');
									$this->load->view('Reportes/script_Venta.php');
									// $this->output->enable_profiler(TRUE);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(20);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
										$data       = array (	"Alerta" => $this->Producto->get_alert(),
																		'data_view' => $variable,
																		);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Reportes/Venta.php');
												$this->load->view('Home/sidebar.php',FALSE);
												$this->load->view('Home/pie_js.php');
												$this->load->view('Reportes/script_Venta.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}


				}

	}
public function Ventas($value='')
	{
		// $this->output->enable_profiler(TRUE);
			$inicio      = $this->security->xss_clean($this->input->post('fecha',FALSE));
			$fin         = $this->security->xss_clean($this->input->post('fecha2',FALSE));
			$caja        = $this->security->xss_clean($this->input->post('caja',FALSE));
			$reservation = $this->security->xss_clean($this->input->post('reservation',FALSE));
			$nombre = 'Reporte de Ventas ';
			if (!empty($reservation)) {
				switch ($reservation) {
					case '0':
						$where = 'fa.Estado != 4';
						break;
					case '1':
						$where = 'fa.Estado != 4  AND fa.Estado = 0 ';
						break;
					case '2':
						$where = 'fa.Estado != 4  AND fa.Estado = 1 OR fa.Estado = 2';
						break;
				}

			}else{
					$where =  "fa.Estado != 4" ;
			}
			if ($inicio == $fin ) {
				$BETWEEN     = "Fecha_expedicion = '$inicio'";
				$nombrefecha = "Fecha : ".$inicio;
			}else{
				$BETWEEN     = "Fecha_expedicion BETWEEN '$inicio 'AND '$fin'";
				$nombrefecha = "Fecha Desde ".$inicio."  Hasta  ".$fin;
			}
        $this->db->select('Ticket,idFactura_Venta,Fecha_expedicion,Hora,Concepto,Estado,Num_Factura_Venta,Tipo_Venta,Monto_Total,Contado_Credito,idCliente,Insert,Ruc,Nombres,Apellidos,Flete');
        $this->db->join('Cliente cl', 'fa.Cliente_idCliente = cl.idCliente', 'inner');
        $this->db->where('Insert != 2');
        $this->db->where($BETWEEN );
        $this->db->where($where);
        $query = $this->db->get('Factura_Venta fa');
        // echo var_dump($query->result());
		if ($query->num_rows() > 0) {
			$nombre = 'Reporte de Ventas ';
			if (empty($value)) {
				$this->load->library('pdf');
				$this->pdf = new Pdf();
				$this->pdf->AddPage();
				$this->Header($nombre ,$nombrefecha);
				$this->pdf->AliasNbPages();
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);
				$this->pdf->SetFont('Arial', 'B', 9);
				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(45,7,'Comprobante N.','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Cliente','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Fecha ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Estado ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Monto Total','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
				foreach ($query->result() as $key => $Venta) {
				if ($Venta->Estado == 0) {
							
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($Venta->Tipo_Venta == 0) {
								$this->pdf->Cell(45,5,'Recibo N.'.$Venta->Ticket,'BL',0,'C',0);
							}else{
								$this->pdf->Cell(45,5,'Factura N. '. $Venta->Num_factura_Compra,'BL',0,'C',0);
							}
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Venta->Nombres.'-'.$Venta->Apellidos, 25),'BL',0,'C',0);
							$this->pdf->Cell(35,5,$Venta->Fecha_expedicion.'  '.$Venta->Hora,'BL ',0,'C',0);
							$this->pdf->Cell(30,5,'Pagado','BL ',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $Venta->Monto_Total,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
				}elseif ($Venta->Estado == 1) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($Venta->Tipo_Venta == 0) {
								$this->pdf->Cell(45,5,'Recibo N.'.$Venta->Ticket,'BL',0,'C',0);
							}else{
								$this->pdf->Cell(45,5,'Factura N. '. $Venta->Num_factura_Venta,'BL',0,'C',0);
							}

							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Venta->Nombres.'-'.$Venta->Apellidos, 25),'BL',0,'C',0);
							$this->pdf->Cell(35,5,$Venta->Fecha_expedicion.'  '.$Venta->Hora,'BL ',0,'C',0);
							$this->pdf->Cell(30,5,'Parcial','BL ',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $Venta->Monto_Total,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
				}elseif ($Venta->Estado == 2) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($Venta->Tipo_Venta == 0) {
								$this->pdf->Cell(45,5,'Recibo N.'.$Venta->Ticket,'BL',0,'C',0);
							}else{
								$this->pdf->Cell(45,5,'Factura N. '. $Venta->Num_factura_Venta,'BL',0,'C',0);
							}

							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Venta->Nombres.'-'.$Venta->Apellidos, 25),'BL',0,'C',0);
							$this->pdf->Cell(35,5,$Venta->Fecha_expedicion.'  '.$Venta->Hora,'BL ',0,'C',0);
							$this->pdf->Cell(30,5,'No Pagado','BL ',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $Venta->Monto_Total,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
				}


				}
        		$this->pdf->Output('pdf/Venta/'.$nombrefecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Venta/'.$nombrefecha.'.pdf', 'I'); // show pdf pdf.pdf
			}else{

						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
						            ->setCellValue('A2', 'Comprobante')
						            ->setCellValue('B2', 'Cliente')
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
				foreach ($query->result() as $key => $Venta) {
						$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
				if ($Venta->Estado == 0) {
							if ($Venta->Tipo_Venta == 0) {
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Recibo Nº  '.$Venta->Ticket);
							}else{
								$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('A'.$x, 'Factura Nº  '. $Venta->Num_factura_Venta);
							}
							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x, $this->mi_libreria->getSubString($Venta->Nombres.'-'.$Venta->Apellidos, 50))
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
								->setCellValue('A'.$x, 'Factura Nº  '. $Venta->Num_factura_Venta);
							}

							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x, $this->mi_libreria->getSubString($Venta->Nombres.'-'.$Venta->Apellidos, 50))
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
								->setCellValue('A'.$x, 'Factura Nº  '. $Venta->Num_factura_Venta);
							}

							$this->phpexcel->setActiveSheetIndex(0)
								->setCellValue('B'.$x,  $this->mi_libreria->getSubString($Venta->Nombres.'-'.$Venta->Apellidos, 50))
								->setCellValue('C'.$x,$Venta->Fecha_expedicion.'  '.$Venta->Hora)
		      	                ->setCellValue('D'.$x, 'No Pagado')
								->setCellValue('E'.$x, number_format($Venta->Monto_Total,0,',',','));
												$x ++;
				}



				}

						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('ventas'.$fecha);
						$this->output_exel('ventas',$fecha );
			}
		}else{
				$data     = array(
					'titulo'  => 'No existen datos de busqueda',
					'titulo2' => $nombre,
					'titulo3' => 'No existen datos', );
				if (empty($value)) {
					$this->load->view('Error/error.php', $data, FALSE);
				}else{
					$this->load->view('Error/error_exel.php', $data, FALSE);
				}
		}

	}
	public function devoluciones( $offset = 0 )
	{
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Reportes/devoluciones.php');
									$this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/pie_js.php');
									$this->load->view('Reportes/script_devoluciones.php');
									// $this->output->enable_profiler(TRUE);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(20);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
										$data       = array (	"Alerta" => $this->Producto->get_alert(),
																		'data_view' => $variable,
																		);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Reportes/devoluciones.php');
												$this->load->view('Home/sidebar.php',FALSE);
												$this->load->view('Home/pie_js.php');
												$this->load->view('Reportes/script_devoluciones.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}


				}

	}
	public function devolucion($id='')
	{
                $this->load->library('pdf');
                // $this->output->enable_profiler(TRUE);
							$inicio      = $this->security->xss_clean($this->input->post('fecha',FALSE));
							$fin         = $this->security->xss_clean($this->input->post('fecha2',FALSE));
							$caja        = $this->security->xss_clean($this->input->post('caja',FALSE));
							$reservation = $this->security->xss_clean($this->input->post('reservation',FALSE));
							$nombre = 'Reporte de Ventas ';

							if ($inicio == $fin ) {
								$BETWEEN     = "Devoluciones.Fecha = '$inicio'";
								$nombrefecha = "Fecha : ".$inicio;
							}else{
								$BETWEEN     = "Devoluciones.Fecha BETWEEN '$inicio 'AND '$fin'";
								$nombrefecha = "Fecha Desde ".$inicio."  Hasta  ".$fin;
							}

								switch ($reservation) {
									case '1':
							$this->db->select('Tipo_Compra,idDevoluciones,dd.Estado,dd.Motivo,Devoluciones.Fecha,Devoluciones.Monto_Total,Venta_Compra,Nombre,dd.Cantidad,dd.Precio,Razon_Social,Ticket , Num_factura_Compra,Ruc');
										$this->db->join('Factura_Compra fc', 'Devoluciones.Factura_Compra_idFactura_Compra = fc.idFactura_Compra', 'inner');
										$this->db->join('Detalle_Devolucion dd', 'Devoluciones.idDevoluciones = dd.Devoluciones_idDevoluciones', 'inner');
										$this->db->join('Producto', 'dd.Producto_idProducto = Producto.idProducto', 'inner');
										$this->db->join('Proveedor pr', 'fc.Proveedor_idProveedor = pr.idProveedor', 'left');
										$nombre = 'Listado de Devoluciones de Compras';
										break;
									case '2':
							$this->db->select('Tipo_Venta,idDevoluciones,dd.Estado,dd.Motivo,Devoluciones.Fecha,Devoluciones.Monto_Total,Venta_Compra,Nombre,dd.Cantidad,dd.Precio,Nombres,Apellidos,Ticket , Num_Factura_Venta');
							            $this->db->join('Factura_Venta fv', 'Devoluciones.Factura_Venta_idFactura_Venta = fv.idFactura_Venta', 'inner');
										$this->db->join('Detalle_Devolucion dd', 'Devoluciones.idDevoluciones = dd.Devoluciones_idDevoluciones', 'inner');
							            $this->db->join('Cliente cl', 'fv.Cliente_idCliente  = cl.idCliente', '');
										$this->db->join('Producto', 'dd.Producto_idProducto = Producto.idProducto', 'inner');
										$nombre = 'Listado de Devoluciones de Ventas';
										break;
								}
								$this->db->where($BETWEEN);
							    $query = $this->db->get('Devoluciones');
					if( $query->num_rows() > 0 ){
							if (empty($id)) {
								$this->pdf = new Pdf();
								$this->pdf->AddPage();
								$this->Header($nombre ,$nombrefecha);
								$this->pdf->AliasNbPages();
								$this->pdf->SetTitle($nombre);
								$this->pdf->SetLeftMargin(10);
								$this->pdf->SetRightMargin(10);
								$this->pdf->SetFont('Arial', 'B', 9);
									        $this->pdf->SetFillColor(200,200,200);
											$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
											$this->pdf->Cell(16,7,'Cantidad','TBL',0,'C','1');
											$this->pdf->Cell(30,7,'Nombre','TBL',0,'C','1');
											$this->pdf->Cell(30,7,'Precio ','TBL',0,'C','1');
											$this->pdf->Cell(65,7,'Estado ','TBL',0,'C','1');
											if ($reservation == 1) {
													$this->pdf->Cell(40,7,'Proveedor','TBL BR',0,'C','1');
											}else{
													$this->pdf->Cell(40,7,'Cliente','TBL BR',0,'C','1');

											}
											$this->pdf->Ln(7);
											// La variable $x se utiliza para mostrar un número consecutivo
											$x = 1;
											$sum = 0;
											foreach ($query->result() as $key => $listc) {
												$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
												$this->pdf->Cell(16,5,$listc->Cantidad,'BL',0,'C',0);
												$this->pdf->Cell(30,5,$this->mi_libreria->getSubString($listc->Nombre, 25),'BL',0,'C',0);
												$this->pdf->Cell(30,5,$listc->Precio,'BL ',0,'C',0);
												$this->pdf->Cell(65,5,$this->mi_libreria->getSubString($listc->Estado, 45),'BL ',0,'C',0);
												if($reservation == 1){
												$this->pdf->Cell(40,5, $this->mi_libreria->getSubString($listc->Razon_Social, 25),'BL BB BR',0,'C',0);
												}else{
												$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($listc->Nombres.'-'.$listc->Apellidos, 35),'BL BB BR',0,'C',0);
												}
												$this->pdf->Ln(5);
												$sum += $listc->Precio * $listc->Cantidad;
											}

											$this->pdf->Cell(126,7,'','0',0,'c');
											$this->pdf->Cell(35,7,'Monto total:',' BB ',0,'L',0);
											$this->pdf->Cell(30,7,number_format( $listc->Monto_Total,0,'.','.'),' BB ',0,'C',0);
											$this->pdf->Ln(7);

				        		$this->pdf->Output('pdf/devolucion.pdf','F');  //save pdf pdf.pdf
								$this->pdf->Output('pdf/devolucion.pdf', 'I'); // show pdf pdf.pdf
								}
								else  // exel
								{

									$this->header_exel();
									$this->cabecera($nombre,'A1:E1' );
									$this->phpexcel->setActiveSheetIndex(0)
					                            ->setCellValue('A2', 'Cantidad')
									            ->setCellValue('B2', 'Nombre')
									            ->setCellValue('C2', 'Precio')
									            ->setCellValue('D2', 'Condicion');
											if ($reservation == 1) {
												$this->phpexcel->setActiveSheetIndex(0)
					                			  ->setCellValue('E2', 'Proveedor');
											}else{
												$this->phpexcel->setActiveSheetIndex(0)
					                			  ->setCellValue('E2', 'Cliente');

											}

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
							foreach ($query->result() as $key => $listc) {
								    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
												$this->phpexcel->setActiveSheetIndex(0)
												->setCellValue('A'.$x, $listc->Cantidad)
												->setCellValue('B'.$x, $listc->Nombre)
												->setCellValue('C'.$x, number_format( $listc->Precio,0,',',','))
												->setCellValue('D'.$x, $listc->Estado);
												if($reservation == 1){
												$this->phpexcel->setActiveSheetIndex(0)
												->setCellValue('E'.$x, $this->mi_libreria->getSubString($listc->Razon_Social, 25));
												}else{
												$this->phpexcel->setActiveSheetIndex(0)
                 								->setCellValue('E'.$x, $this->mi_libreria->getSubString($listc->Nombres.'-'.$listc->Apellidos, 35));
												}

										$x ++;
							}
							$val = $x+2;
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$val, 'Monto total: '.number_format($listc->Monto_Total,0,',',','));
									$this->phpexcel->getActiveSheet()->getStyle('E'.$val)->applyFromArray($this->styleHead('F93107'));

									$this->phpexcel->getActiveSheet()->setTitle('devolucion');
									$this->output_exel('devolucion' );


								}



				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function Cobros( $offset = 0 )
	{
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Reportes/Cobros.php');
									$this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/pie_js.php');
									$this->load->view('Reportes/script_Cobros.php');
									// $this->output->enable_profiler(TRUE);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(20);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
										$data       = array (	"Alerta" => $this->Producto->get_alert(),
																		'data_view' => $variable,
																		);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Reportes/Cobros.php');
												$this->load->view('Home/sidebar.php',FALSE);
												$this->load->view('Home/pie_js.php');
												$this->load->view('Reportes/script_Cobros.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}


				}

	}
	public function Cobro($id='')
	{
                $this->load->library('pdf');
                // $this->output->enable_profiler(TRUE);
							$inicio      = $this->security->xss_clean($this->input->post('fecha',FALSE));
							$fin         = $this->security->xss_clean($this->input->post('fecha2',FALSE));
							$caja        = $this->security->xss_clean($this->input->post('caja',FALSE));
							$reservation = $this->security->xss_clean($this->input->post('reservation',FALSE));
							$nombre = 'Reporte de cobros ';
							switch ($reservation) {
								case '0':
									$where = 'cc.Estado = 0 ';
									break;
								case '1':
									$where = 'cc.Moneda_idMoneda IS NOT NULL AND cc.Estado = 0';
									break;
								case '2':
									$where = 'cc.Movimientos_idMovimientos IS NOT NULL AND cc.Estado = 0';
									break;
								case '3':
									$where    = 'cc.Tarjeta_idTarjeta IS NOT NULL AND cc.Estado = 0';
									break;
								case '4':
									$where    = 'cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente IS NOT NULL AND cc.Estado = 0';
									break;
							 }
							if ($inicio == $fin ) {
								$BETWEEN     = "cc.Fecha = '$inicio'";
								$nombrefecha = "Fecha : ".$inicio;
							}else{
								$BETWEEN     = "cc.Fecha BETWEEN '$inicio 'AND '$fin'";
								$nombrefecha = "Fecha Desde ".$inicio."  Hasta  ".$fin;
							}

							$this->db->join('Factura_Venta ', 'cc.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta', 'left');
					        $this->db->join('Cuenta_Corriente_Cliente ccc', 'cc.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = ccc.idCuenta_Corriente_Cliente', 'left');
					        $this->db->join('Cliente', 'Factura_Venta.Cliente_idCliente = Cliente.idCliente', 'left');
							$this->db->where($where );
							$this->db->where($BETWEEN);
							if ($caja > 0) {
								$this->db->where('cc.Caja_idCaja', $caja);
							}
							$query = $this->db->get('Caja_Cobros cc');
							// echo var_dump($query->result_array());
				if( $query->num_rows() > 0 ){
					if (empty($id)) {
							$this->load->library('pdf');
							$this->pdf = new Pdf();
							$this->pdf->AddPage();
							$this->Header($nombre ,$nombrefecha);
							$this->pdf->AliasNbPages();
							$this->pdf->SetTitle($nombre);
							$this->pdf->SetLeftMargin(10);
							$this->pdf->SetRightMargin(10);
							$this->pdf->SetFont('Arial', 'B', 9);
							$this->pdf->SetFillColor(200,200,200);
							$this->pdf->Cell(70,7,'Forma de Cobros','TBL',0,'C','1');
							$this->pdf->Cell(30,7,utf8_decode('Monto'),'TBL',0,'C','1');
							$this->pdf->Cell(45,7,'Razon Social ','TBL',0,'C','1');
							$this->pdf->Cell(25,7,'Fecha ','TBL',0,'C','1');
							$this->pdf->Cell(25,7,'Referencia','TBL BR',0,'C','1');
							$this->pdf->Ln(7);
     						$x = 1;
						foreach ($query->result() as $key => $listc) {

			           if (!is_null($listc->idFactura_Venta)) { ///  listcs
			               /////////////////////////////////////////////////////////////////////////
							$this->pdf->Cell(70,5,$this->mi_libreria->getSubString($listc->Descripcion,40),'BL',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $listc->Monto,0,'.',','),'BL',0,'C',0);
						if (!empty($listc->idCliente)) {
							$this->pdf->Cell(45,5,$this->mi_libreria->getSubString($listc->Nombres,40 ),'BL ',0,'C',0);
						}else{
							$this->pdf->Cell(45,5,$this->mi_libreria->getSubString($listc->Nombres,40 ),'BL ',0,'C',0);
						}
							$this->pdf->Cell(25,5,$this->mi_libreria->getSubString($listc->Fecha,40 ),'BL ',0,'C',0);
					    if ($listc->Tipo_Venta == 0 ) { // voleta
							$this->pdf->Cell(25,5,'Recibo N. '. $listc->Ticket,'BL BB BR',0,'C',0);
							   }elseif ($listc->Tipo_Venta == 1 ) { // factura
							$this->pdf->Cell(25,5,'Factura N. '. $listc->Num_Factura_Venta,'BL BB BR',0,'C',0);
							}
							$this->pdf->Ln(5);

		               ////////////////////////////////////////////////////////////////////
			           }
			           elseif (!is_null($listc->idCuenta_Corriente_Cliente)) {
			           	////////////////////////////////////////////////////////////////////
							$this->pdf->Cell(70,5,$this->mi_libreria->getSubString($listc->Descripcion,40),'BL',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $listc->Monto,0,'.',','),'BL',0,'C',0);
						
						if (!empty($listc->idCliente)) {
							$this->pdf->Cell(45,5,$this->mi_libreria->getSubString($listc->Nombres,40 ),'BL ',0,'C',0);
						}else{
							$this->pdf->Cell(45,5,$this->mi_libreria->getSubString($listc->Nombres,40 ),'BL ',0,'C',0);
						}
						$this->pdf->Cell(25,5,$this->mi_libreria->getSubString($listc->Fecha,40 ),'BL ',0,'C',0);
						$this->pdf->Cell(25,5,'Recibo Cuota N. '.$listc->Num_Recibo,'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
			           	////////////////////////////////////////////////////////////////////
			           }  
			          elseif (is_null($listc->idCuenta_Corriente_Cliente) && is_null($listc->idFactura_Venta)) 
			           {
							$this->pdf->Cell(70,5,$this->mi_libreria->getSubString($listc->Descripcion,40),'BL',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $listc->Monto,0,'.',','),'BL',0,'C',0);
							$this->pdf->Cell(45,5,'','BL ',0,'C',0);
							$this->pdf->Cell(25,5,$this->mi_libreria->getSubString($listc->Fecha,40 ),'BL ',0,'C',0);
							$this->pdf->Cell(25,5,'','BL BB BR ',0,'C',0);
							$this->pdf->Ln(5);
						}

					}
					    $fecha = date("Y-m-d");
		        		$this->pdf->Output('pdf/Cobro/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
						$this->pdf->Output('pdf/Cobro/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
					}
					else  // exel
					{
						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Forma de Cobros')
						            ->setCellValue('B2', 'Monto')
						            ->setCellValue('C2', 'Razon Social ')
						            ->setCellValue('D2', 'Fecha')
						            ->setCellValue('E2', 'Referencias');


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
						foreach ($query->result() as $key => $listc) {

			           if (!is_null($listc->idFactura_Venta)) { ///  listcs
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x,$this->mi_libreria->getSubString($listc->Descripcion,40))
									->setCellValue('B'.$x, number_format( $listc->Monto,0,',',','));
						if (!empty($listc->idCliente)) {
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('C'.$x, $listc->Nombres);

						}else{
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('C'.$x, $listc->Nombres);
						}
									$this->phpexcel->setActiveSheetIndex(0)
											->setCellValue('D'.$x, $listc->Fecha);
									if ($listc->Tipo_Venta == 0 ) { // voleta
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$x, 'Recibo Nº '. $listc->Ticket);
									}elseif ($listc->Tipo_Venta == 1 ) { // factura
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$x, 'Factura Nº '. $listc->Num_Factura_Venta);
									}

		               	////////////////////////////////////////////////////////////////////
			           	} elseif (!is_null($listc->idCuenta_Corriente_Cliente)) {
			           	////////////////////////////////////////////////////////////////////
							$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x,$this->mi_libreria->getSubString($listc->Descripcion,40))
									->setCellValue('B'.$x, number_format( $listc->Monto,0,'.',','));
									if (!empty($listc->idCliente)) {
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('C'.$x, $listc->Nombres);

									}else{
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('C'.$x, $listc->Nombres);
									}

									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$x, $listc->Fecha)
									->setCellValue('E'.$x, 'Recibo Cuota Nº '.$listc->Num_Recibo);

	
							}
				          		elseif (is_null($listc->idCuenta_Corriente_Cliente) && is_null($listc->idFactura_Venta)) 
				           {
							$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x,$this->mi_libreria->getSubString($listc->Descripcion,40))
									->setCellValue('B'.$x, number_format( $listc->Monto,0,'.',','))
                                    ->setCellValue('D'.$x, $this->mi_libreria->getSubString($listc->Fecha,40 ));
							}

									$x ++;
						}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('cobro'.$fecha);
						$this->output_exel('cobro',$fecha );
				}
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}
	public function Pagos( $offset = 0 )
	{
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Reportes/Pagos.php');
									$this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/pie_js.php');
									$this->load->view('Reportes/script_Pagos.php');
									// $this->output->enable_profiler(TRUE);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(20);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
										$data       = array (	"Alerta" => $this->Producto->get_alert(),
																		'data_view' => $variable,
																		);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Reportes/Pagos.php');
												$this->load->view('Home/sidebar.php',FALSE);
												$this->load->view('Home/pie_js.php');
												$this->load->view('Reportes/script_Pagos.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}


				}

	}
	public function Pago($id='')
	{
                $this->load->library('pdf');
                // $this->output->enable_profiler(TRUE);
							$inicio      = $this->security->xss_clean($this->input->post('fecha',FALSE));
							$fin         = $this->security->xss_clean($this->input->post('fecha2',FALSE));
							$caja        = $this->security->xss_clean($this->input->post('caja',FALSE));
							$reservation = $this->security->xss_clean($this->input->post('reservation',FALSE));
							$nombre = 'Reporte de Pagos ';
							switch ($reservation) {
								case '0':
									$where = 'cc.Estado = 0 ';
									break;
								case '1':
									$where = 'cc.Moneda_idMoneda IS NOT NULL AND cc.Estado = 0';
									break;
								case '2':
									$where = 'cc.Movimientos_idMovimientos IS NOT NULL AND cc.Estado = 0';
									break;
								case '3':
									$where    = 'cc.Tarjeta_idTarjeta IS NOT NULL AND cc.Estado = 0';
									break;
								case '4':
									$where    = 'cc.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa IS NOT NULL AND cc.Estado = 0';
									break;
							 }
							if ($inicio == $fin ) {
								$BETWEEN     = "cc.Fecha = '$inicio'";
								$nombrefecha = "Fecha : ".$inicio;
							}else{
								$BETWEEN     = "cc.Fecha BETWEEN '$inicio 'AND '$fin'";
								$nombrefecha = "Fecha Desde ".$inicio."  Hasta  ".$fin;
							}

							$this->db->join('Factura_Compra ', 'cc.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra', 'left');
					        $this->db->join('Cuenta_Corriente_Empresa ccc', 'cc.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = ccc.idCuenta_Corriente_Empresa', 'left');
					        $this->db->join('Proveedor', 'Factura_Compra.Proveedor_idProveedor = Proveedor.idProveedor', 'left');
							$this->db->where($where );
							$this->db->where($BETWEEN);
							if ($caja > 0) {
								$this->db->where('cc.Caja_idCaja', $caja);
							}
							$query = $this->db->get('Caja_Pagos cc');
							// echo var_dump($query->result_array());
				if( $query->num_rows() > 0 ){
					if (empty($id)) {
							$this->load->library('pdf');
							$this->pdf = new Pdf();
							$this->pdf->AddPage();
							$this->Header($nombre ,$nombrefecha);
							$this->pdf->AliasNbPages();
							$this->pdf->SetTitle($nombre);
							$this->pdf->SetLeftMargin(10);
							$this->pdf->SetRightMargin(10);
							$this->pdf->SetFont('Arial', 'B', 9);
							$this->pdf->SetFillColor(200,200,200);
							$this->pdf->Cell(75,7,'Forma de Cobros','TBL',0,'C','1');
							$this->pdf->Cell(25,7,utf8_decode('Monto'),'TBL',0,'C','1');
							$this->pdf->Cell(35,7,'Proveedor ','TBL',0,'C','1');
							$this->pdf->Cell(25,7,'Fecha ','TBL',0,'C','1');
							$this->pdf->Cell(35,7,'Referencia','TBL BR',0,'C','1');
							$this->pdf->Ln(7);
     						$x = 1;
						foreach ($query->result() as $key => $listc) {

			           if (!is_null($listc->idFactura_Compra)) { ///  listcs
			               /////////////////////////////////////////////////////////////////////////
							$this->pdf->Cell(75,5,$this->mi_libreria->getSubString($listc->Descripcion,40),'BL',0,'L',0);
							$this->pdf->Cell(25,5, number_format( $listc->Monto,0,'.',','),'BL',0,'C',0);
						if (!empty($listc->idProveedor)) {
							$this->pdf->Cell(35,5,$this->mi_libreria->getSubString($listc->Razon_Social,40 ),'BL ',0,'C',0);
						}else{
							$this->pdf->Cell(35,5,$this->mi_libreria->getSubString($listc->Razon_Social,40 ),'BL ',0,'C',0);
						}
							$this->pdf->Cell(25,5,$this->mi_libreria->getSubString($listc->Fecha,40 ),'BL ',0,'C',0);
					    if ($listc->Tipo_Compra == 0 ) { // voleta
							$this->pdf->Cell(35,5,'Recibo N. '. $listc->Ticket,'BL BB BR',0,'L',0);
							   }elseif ($listc->Tipo_Compra == 1 ) { // factura
							$this->pdf->Cell(35,5,'Factura N. '. $listc->Num_factura_Compra,'BL BB BR',0,'L',0);
							}
							$this->pdf->Ln(5);

		               ////////////////////////////////////////////////////////////////////
			           }
			           elseif (!is_null($listc->idCuenta_Corriente_Empresa)) {
			           	////////////////////////////////////////////////////////////////////
							$this->pdf->Cell(75,5,$this->mi_libreria->getSubString($listc->Descripcion,40),'BL',0,'L',0);
							$this->pdf->Cell(25,5, number_format( $listc->Monto,0,'.',','),'BL',0,'C',0);
						
						if (!empty($listc->Proveedor_idProveedor)) {
						     $this->db->where('idProveedor', $listc->Proveedor_idProveedor);
						     $ssqrty = $this->db->get('Proveedor');
						    $row = $ssqrty->row();
							$this->pdf->Cell(35,5,$this->mi_libreria->getSubString($row->Razon_Social,40 ),'BL ',0,'C',0);
						}else{
							$this->pdf->Cell(35,5,$this->mi_libreria->getSubString($row->Razon_Social,40 ),'BL ',0,'C',0);
						}
						$this->pdf->Cell(25,5,$this->mi_libreria->getSubString($listc->Fecha,40 ),'BL ',0,'C',0);
						$this->pdf->Cell(35,5,'Recibo N. '.$listc->Num_Recibo,'BL BB BR',0,'L',0);
							$this->pdf->Ln(5);
			           	////////////////////////////////////////////////////////////////////
			           }  
			          elseif (is_null($listc->idCuenta_Corriente_Empresa) && is_null($listc->idFactura_Compra)) 
			           {
							$this->pdf->Cell(75,5,$this->mi_libreria->getSubString($listc->Descripcion,40),'BL',0,'L',0);
							$this->pdf->Cell(25,5, number_format( $listc->Monto,0,'.',','),'BL',0,'C',0);
							$this->pdf->Cell(35,5,'','BL ',0,'C',0);
							$this->pdf->Cell(25,5,$this->mi_libreria->getSubString($listc->Fecha,40 ),'BL ',0,'C',0);
							$this->pdf->Cell(35,5,'','BL BB BR ',0,'L',0);
							$this->pdf->Ln(5);
						}

					}
					    $fecha = date("Y-m-d");
		        		$this->pdf->Output('pdf/Cobro/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
						$this->pdf->Output('pdf/Cobro/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
					}
					else  // exel
					{

						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
						$this->phpexcel->setActiveSheetIndex(0)
		                            ->setCellValue('A2', 'Forma de Cobros')
						            ->setCellValue('B2', 'Monto')
						            ->setCellValue('C2', 'Proveedor ')
						            ->setCellValue('D2', 'Fecha')
						            ->setCellValue('E2', 'Referencias');


						$this->phpexcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($this->styleHead('7F3C2E'))
						               ->getActiveSheet()
						               ->getRowDimension()
						               ->setRowHeight(40);	

						$this->phpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
						$this->phpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
						$this->phpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(35);
						$x = 3;
						foreach ($query->result() as $key => $listc) {

			           if (!is_null($listc->idFactura_Compra)) { ///  listcs
						    $this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x,$this->mi_libreria->getSubString($listc->Descripcion,40))
									->setCellValue('B'.$x, number_format( $listc->Monto,0,',',','));
						if (!empty($listc->idProveedor)) {
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('C'.$x, $listc->Razon_Social);

						}else{
							$this->phpexcel->setActiveSheetIndex(0)
							->setCellValue('C'.$x, $listc->Razon_Social);
						}
									$this->phpexcel->setActiveSheetIndex(0)
											->setCellValue('D'.$x, $listc->Fecha);
									if ($listc->Tipo_Compra == 0 ) { // voleta
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$x, 'Recibo Nº '. $listc->Ticket);
									}elseif ($listc->Tipo_Compra == 1 ) { // factura
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('E'.$x, 'Factura Nº '. $listc->Num_Factura_Venta);
									}

		               	////////////////////////////////////////////////////////////////////
			           	} elseif (!is_null($listc->idCuenta_Corriente_Empresa)) {
			           	////////////////////////////////////////////////////////////////////
							$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x,$this->mi_libreria->getSubString($listc->Descripcion,40))
									->setCellValue('B'.$x, number_format( $listc->Monto,0,'.',','));
										$this->db->where('idProveedor', $listc->Proveedor_idProveedor);
									    $sqr = $this->db->get('Proveedor');
									    $row = $sqr->row();
										$this->phpexcel->setActiveSheetIndex(0)
										->setCellValue('C'.$x, $row->Razon_Social);
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('D'.$x, $listc->Fecha)
									->setCellValue('E'.$x, 'Recibo Cuota Nº '.$listc->Num_Recibo);

	
							}
				          		elseif (is_null($listc->idCuenta_Corriente_Empresa) && is_null($listc->idFactura_Compra)) 
				           {
							$this->phpexcel->getActiveSheet()->getStyle('A'.$x.':E'.$x)->applyFromArray($this->style_list());
									$this->phpexcel->setActiveSheetIndex(0)
									->setCellValue('A'.$x,$this->mi_libreria->getSubString($listc->Descripcion,40))
									->setCellValue('B'.$x, number_format( $listc->Monto,0,'.',','))
                                    ->setCellValue('D'.$x, $this->mi_libreria->getSubString($listc->Fecha,40 ));
							}

									$x ++;
						}
						$fecha = date("Y-m-d");
						$this->phpexcel->getActiveSheet()->setTitle('pagos'.$fecha);
						$this->output_exel('pagos',$fecha );
				}
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}
	// 	Reporte Bancario
	public function Bancario( $offset = 0 )
	{
		    	if ($this->session->userdata('Permiso_idPermiso') == 1) {
		        //////////////////////////////////////Vista orden Solo admin///////////////////////////////////////////////////////
									$data       = array (	"Alerta" => $this->Producto->get_alert(),
															);
								 	$this->load->view('Home/head.php',$data,FALSE);
							        $this->load->view('Home/header.php',FALSE);
									$this->load->view('Home/aside.php');
									$this->load->view('Reportes/Bancos.php');
									$this->load->view('Home/sidebar.php',FALSE);
									$this->load->view('Home/pie_js.php');
									$this->load->view('Reportes/script_Bancos.php');
									// $this->output->enable_profiler(TRUE);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				} else {
					$variable =  $this->Model_Menu->octener(20);
					if (!empty($variable)) {
						$variable =  $this->Model_Menu->octenerMenu($this->session->userdata('Permiso_idPermiso'));
										$data       = array (	"Alerta" => $this->Producto->get_alert(),
																		'data_view' => $variable,
																		);
											 	$this->load->view('Home/head.php',$data,FALSE);
										        $this->load->view('Home/header.php',FALSE);
												$this->load->view('Home/aside2.php',FALSE);
												$this->load->view('Reportes/Bancos.php');
												$this->load->view('Home/sidebar.php',FALSE);
												$this->load->view('Home/pie_js.php');
												$this->load->view('Reportes/script_Bancos.php');
						   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					}else {
						$this->load->view('errors/404.php');
					}


				}

	}
	public function Movimiento($id='')
	{
                $this->load->library('pdf');
                // $this->output->enable_profiler(TRUE);
							$inicio      = $this->security->xss_clean($this->input->post('fecha',FALSE));
							$fin         = $this->security->xss_clean($this->input->post('fecha2',FALSE));
							$caja        = $this->security->xss_clean($this->input->post('caja',FALSE));
							$reservation = $this->security->xss_clean($this->input->post('reservation',FALSE));
							$nombre = 'Reporte de Movimientos Bancario ';

							if ($inicio == $fin ) {
								$BETWEEN     = "FechaExpedicion = '$inicio'";
								$nombrefecha = "Fecha : ".$inicio;
							}else{
								$BETWEEN     = "FechaExpedicion BETWEEN '$inicio 'AND '$fin'";
								$nombrefecha = "Fecha Desde ".$inicio."  Hasta  ".$fin;
							}
							$this->db->join('Gestor_Bancos', 'Gestor_Bancos.idGestor_Bancos = Movimientos.Gestor_Bancos_idGestor_Bancos', 'inner');
							$this->db->join('PlandeCuenta', 'PlandeCuenta.idPlandeCuenta = Movimientos.PlandeCuenta_idPlandeCuenta', 'left');
							switch ($reservation) {
								case '1':
									$where = 'Entrada';
									$this->db->where('Entrada_Salida', $where);
									break;
								case '2':
									$where = 'Salida';
									$this->db->where('Entrada_Salida', $where);
									break;

							 }
							$this->db->where($BETWEEN);
							if ($caja > 0) {
								$this->db->where('Gestor_Bancos_idGestor_Bancos', $caja);
							}
							$query = $this->db->get('Movimientos');
							// echo var_dump($query->result_array());
				if( $query->num_rows() > 0 ){
					if (empty($id)) {
							$this->load->library('pdf');
							$this->pdf = new Pdf();
							$this->pdf->AddPage();
							$this->Header($nombre ,$nombrefecha);
							$this->pdf->AliasNbPages();
							$this->pdf->SetTitle($nombre);
							$this->pdf->SetLeftMargin(10);
							$this->pdf->SetRightMargin(10);
							$this->pdf->SetFont('Arial', 'B', 9);
						 $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Cheque','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Concepto','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Fecha ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Importe','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Banco','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Accion','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;	
						$entrada = 0;
						$salida = 0;
						foreach ($query->result() as  $value) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($value->NumeroCheque > 0) {
							$NumeroCheque = $value->NumeroCheque;
							}else{
							$NumeroCheque  = 'Efectivo';
							}
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString($NumeroCheque,40),'BL',0,'C',0);
					    	if (!empty($value->PlandeCuenta_idPlandeCuenta)) {
					    		$row =  $value->Balance_General;
					    	}else{
						    	if ($value->Entrada_Salida == 'Entrada') {
						    	$row =  $value->ConceptoEntrada;
						    	}else{
						    	 $row =  $value->ConceptoSalida;
						    	}
					    	}
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString($row,40),'BL',0,'C',0);
							$this->pdf->Cell(30,5,$value->FechaExpedicion,'BL',0,'C',0);
							$this->pdf->Cell(30,5,number_format($value->Importe,0,'.',','),'BL',0,'C',0);

							$this->pdf->Cell(30,5,$value->Nombre,'BL  ',0,'C',0);
							$this->pdf->Cell(30,5,$value->Entrada_Salida,'BL  BB BR',0,'C',0);


							$this->pdf->Ln(5);
							if ($value->Entrada_Salida == 'Entrada') {
								$entrada += $value->Importe;
							}else{
								$salida  += $value->Importe;
							}

						}
						$this->pdf->Ln(2);
						$this->pdf->Cell(126,7,'','0',0,'c');
						$this->pdf->Cell(35,7,'Total Entrada',' BB ',0,'L',0);
						$this->pdf->Cell(30,7,number_format( $entrada,0,'.','.'),' BB ',0,'C',0);
						$this->pdf->Ln(7);
						$this->pdf->Cell(126,7,'','0',0,'c');
						$this->pdf->Cell(35,7,'Total Salida',' BB ',0,'L',0);
						$this->pdf->Cell(30,7,number_format( $salida,0,'.','.'),' BB ',0,'C',0);
						$this->pdf->Ln(7);
						$this->pdf->Cell(126,7,'','0',0,'c');
						$this->pdf->Cell(35,7,' Saldo',' BB ',0,'L',0);
						$this->pdf->Cell(30,7,number_format( $entrada - $salida,0,'.','.'),' BB ',0,'C',0);
						$this->pdf->Ln(7);
        				$this->pdf->Output('pdf/Banco/mbanco'.$id.'.pdf','F');  //save pdf pdf.pdf
						$this->pdf->Output('pdf/Banco/mbanco'.$id.'.pdf', 'I'); // show pdf pdf.pdf
					}
					else  // exel
					{

						$this->header_exel();
						$this->cabecera($nombre,'A1:E1' );
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
						foreach ($query->result() as  $value) {
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
						$this->phpexcel->getActiveSheet()->setTitle('Movimiento'.$fecha);
						$this->output_exel('Movimiento',$fecha );
				}
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}



}

/* End of pdf Reporte.php */
/* Location: ./application/controllers/Reporte.php */
