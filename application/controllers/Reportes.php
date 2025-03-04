<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
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
	public function __construct()
	{
		parent::__construct();
		//Carga  Dependencies
	}
    public function Header($nombre){
    $this->db->select('Nombre');
    $query = $this->db->get('Empresa');
    $row = $query->row();
    $this->pdf->SetFont('Arial','B',13);
    $this->pdf->Cell(30);
    $this->pdf->Cell(120,10,$row->Nombre,0,0,'C');
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->Cell(50,10,'Fecha:  '.date('Y-d-m').' ',0,0,'C');
    $this->pdf->Ln(5);
    $this->pdf->SetFont('Arial','B',10);
    $this->pdf->Cell(30);            
    $this->pdf->Cell(120,10,$nombre,0,0,'C');
    $this->pdf->Ln(10);
   }

   public function headfactura($nombre='',$objeto = null)
   {
    $this->db->select('*');
    $query = $this->db->get('Empresa');
    $row = $query->row();
						$this->pdf->SetFont('Arial','B',12);
						$this->pdf->Cell(40,7,$row->Nombre,'',0,'L');
						$this->pdf->SetFont('Arial','B',9);
						$this->pdf->Cell(12);
						$this->pdf->Cell(70,7,'Direccion : '.$row->Direccion ,'',0,'L');

						$this->pdf->SetFont('Arial','B',12);
						$this->pdf->Cell(68,7,'R.U.C : '.$row->R_U_C ,'TL R',0,'C');
						$this->pdf->Ln(7);

						$this->pdf->Cell(52);	
						$this->pdf->SetFont('Arial','B',9);
						$this->pdf->Cell(70,7,'Telefono : '.$row->Telefono ,'',0,'L');

						$this->pdf->SetTextColor(250, 0, 0);
						$this->pdf->SetFont('Arial','B',12);
						$this->pdf->Cell(68,7,$nombre,'L R',0,'C');

						$this->pdf->SetTextColor(0, 0, 0);
						$this->pdf->Ln(7);
						$this->pdf->Cell(52);	
						$this->pdf->SetFont('Arial','B',9);
						$this->pdf->Cell(70,7,'Correo  : '.$row->Email ,'',0,'L');
						$this->pdf->SetFont('Arial','B',12);
						if (!is_null($objeto)) {

		                if (!empty($objeto->Num_Factura_Venta)) {
		                  $this->pdf->Cell(30,7,utf8_decode('Nº ').$row->Series.' - '.$row->Timbrado ,'L B',0,'L');
		                  $this->pdf->SetTextColor(250, 0, 0);
		                  $this->pdf->Cell(38,7,$objeto->Num_Factura_Venta ,' BR',0,'C');
		                }else{
		                  $this->pdf->Cell(30,7,'','L B',0,'L');
		                  $this->pdf->SetTextColor(250, 0, 0);
		                   $this->pdf->Cell(38,7,$objeto->Ticket ,' BR',0,'C');
		                }

						$this->pdf->SetTextColor(0, 0, 0);
						}else{
						$this->pdf->Cell(30,7,'' ,'L B',0,'L');
		                $this->pdf->SetTextColor(250, 0, 0);
						$this->pdf->Cell(40,7,'',' BR',0,'C');
						$this->pdf->SetTextColor(0, 0, 0);
						}

						$this->pdf->Ln(10);
   }

      public function headfacturacompra($nombre='',$objeto = null)
   {
						$this->pdf->SetFont('Arial','B',12);
						$this->pdf->Cell(40,7,'',0,'L');
						$this->pdf->SetFont('Arial','B',9);
						$this->pdf->Cell(12);

						$this->pdf->SetFont('Arial','B',12);
						$this->pdf->Ln(7);

						$this->pdf->Cell(52);	
						$this->pdf->SetFont('Arial','B',9);

						$this->pdf->SetTextColor(250, 0, 0);
						$this->pdf->SetFont('Arial','B',12);
						$this->pdf->Cell(70,7,$nombre,'L R',0,'C');

						$this->pdf->SetTextColor(0, 0, 0);
						$this->pdf->Ln(7);
						$this->pdf->Cell(52);	
						$this->pdf->SetFont('Arial','B',9);
						$this->pdf->SetFont('Arial','B',12);

						$this->pdf->Ln(5);
   }
   public function headboleta($nombre,$row,$objeto)
   {
    $this->db->select('*');
    $query = $this->db->get('Empresa');
    $row = $query->row();
						$this->pdf->SetFont('Arial','B',12);
						$this->pdf->Cell(40,7,$row->Nombre,'',0,'L');
						$this->pdf->SetFont('Arial','B',9);
						$this->pdf->Cell(12);
						$this->pdf->Cell(70,7,'Direccion : '.$row->Direccion ,'',0,'L');

						$this->pdf->SetFont('Arial','B',12);
						$this->pdf->Cell(68,7,'R.U.C : '.$row->R_U_C ,'TL R',0,'C');
						$this->pdf->Ln(7);

						$this->pdf->Cell(52);	
						$this->pdf->SetFont('Arial','B',9);
						$this->pdf->Cell(70,7,'Telefono : '.$row->Telefono ,'',0,'L');

						$this->pdf->SetTextColor(250, 0, 0);
						$this->pdf->SetFont('Arial','B',12);
						$this->pdf->Cell(68,7,$nombre,'L R',0,'C');

						$this->pdf->SetTextColor(0, 0, 0);
						$this->pdf->Ln(7);
						$this->pdf->Cell(52);	
						$this->pdf->SetFont('Arial','B',9);
						$this->pdf->Cell(70,7,'Correo  : '.$row->Email ,'',0,'L');
						$this->pdf->SetFont('Arial','B',12);
						$this->pdf->Cell(30,7,'N. ' ,'L B',0,'L');
		                $this->pdf->SetTextColor(250, 0, 0);
						$this->pdf->Cell(38,7,$objeto ,' BR',0,'C');
						$this->pdf->SetTextColor(0, 0, 0);
	
						$this->pdf->Ln(10);
   }
	function caja($id='')
	{

			$nombre = 'Lista de Movimiento Caja  '.$id;
			// Se carga la libreria fpdf
        	$this->load->library('pdf');
						// Se obtienen los clientes de la base de datos
						$this->load->model('Caja_Model','Caja');
						$list = $this->Caja->get_caja($id);
						if( !empty( $list ) ){
						// Creacion del PDF
						/*
						* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
						* heredó todos las variables y métodos de fpdf
						*/
						$this->pdf = new Pdf();
						// Agregamos una página
						$this->pdf->AddPage();
						// Define el alias para el número de página que se imprimirá en el pie
						$this->Header($nombre);
						$this->pdf->AliasNbPages();

						/* Se define el titulo, márgenes izquierdo, derecho y
						* el color de relleno predeterminado
						*/
						$this->pdf->SetTitle($nombre);
						$this->pdf->SetLeftMargin(10);
						$this->pdf->SetRightMargin(10);
						$this->pdf->SetFillColor(200,200,200);

						// Se define el formato de fuente: Arial, negritas, tamaño 9
						$this->pdf->SetFont('Arial', '', 9);
						/*
						* TITULOS DE COLUMNAS
						*
						* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
						*/
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(20,7,'Usuario:','TBL',0,'C','1');
						$this->pdf->Cell(65,7,'Descripción:','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Fecha Hora','TB',0,'C','1');
						$this->pdf->Cell(30,7,'Ingreso','TB',0,'C','1');
						$this->pdf->Cell(30,7,'Egreso','TB BR ',0,'C','1');
						$this->pdf->Ln(7);

						// La variable $x se utiliza para mostrar un número consecutivo
				$x = 0;
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
					$resultadohaber = str_replace($this->config->item('caracteres'),"",$caja->haber);
					$resultadodebe  = str_replace($this->config->item('caracteres'),"",$caja->debe);
					$haber          +=$resultadohaber;
					$debe           +=$resultadodebe;
					$x++;
					$Descrip = $this->mi_libreria->getSubString($caja->descripcion,40);
                    $this->pdf->Cell(10,5,$x,'BL',0,'L',0);
                    $this->pdf->Cell(20,5,$row->Usuario,'BL',0,'L',0);
                    $this->pdf->Cell(65,5,$Descrip,'BL',0,'L',0);
					$this->pdf->Cell(35,5,$caja->fecha,'BL',0,'L',0);
					if ($caja->debe > 0) {
					    $this->pdf->Cell(30,5,number_format($caja->debe,0,'.',','),'BL',0,'L',0);
					}else {
					    $this->pdf->Cell(30,5,$caja->debe,'BL',0,'L',0);
						}
					if ($caja->haber > 0) {
						$this->pdf->Cell(30,5,number_format($caja->haber,0,'.',','),'BL BB BR',0,'L',0);
						}else {
						$this->pdf->Cell(30,5,$caja->haber,'BL BB BR ',0,'L',0);
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
							    $this->pdf->Cell(30,5,'Parcial','B',0,'L',0);
								$this->pdf->Cell(30,5,$total,'BL BB BR ',0,'C',0);
							}
						} else {
							$as = $debe - $haber;
							$total =  number_format($as,0,'.',',');
							for ($i = 0; $i <1 ; $i++) {
                                $this->pdf->Cell(10,5,'','BL',0,'L',0);
			                    $this->pdf->Cell(70,5,'','BL',0,'L',0);
								$this->pdf->Cell(50,5,'','B',0,'L',0);
							    $this->pdf->Cell(30,5,'Parcial','B',0,'L',0);
								$this->pdf->Cell(30,5,$total,'BL BB BR ',0,'C',0);

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
							    $this->pdf->Cell(30,5,'Total','TB',0,'L',0);
								$this->pdf->Cell(30,5,$monto_final,'TB BR ',0,'C',0);
				} else {
					$monto_final =  number_format($monto_inicial + $as,0,'.',',');
					            $this->pdf->Cell(10,5,'','TBL',0,'C',0);
			                    $this->pdf->Cell(70,5,'Inicial','TBL',0,'C',0);
								$this->pdf->Cell(50,5,number_format($monto_inicial,0,'.',','),'TB',0,'L',0);
							    $this->pdf->Cell(30,5,'Total','TB',0,'L',0);
								$this->pdf->Cell(30,5,$monto_final,'TB BR ',0,'C',0);
				}

			$this->pdf->Output('pdf.pdf','F');  //save pdf
			$this->pdf->Output('pdf.pdf', 'I'); // show pdf
		} else {
				$data     = array(
				'titulo'  => 'No existen datos de busqueda',
				'titulo2' => $nombre,
				'titulo3' => 'No existen datos', );
				$this->load->view('Error/error.php', $data, FALSE);
		}
	}

	function Producto($id='')
	{
			$nombre = 'Listados de Productos';
     		// Se carga la libreria fpdf
	        $this->load->library('pdf');
						// Se obtienen los clientes de la base de datos
						$this->load->model('Producto_Model','Producto');
						$list = $this->Producto->getproducto();
						if( !empty( $list ) ){
						// Creacion del PDF
						/*
						* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
						* heredó todos las variables y métodos de fpdf
						*/
						$this->pdf = new Pdf();
						// $this->pdf->Header($nombre);
						// Agregamos una página
						$this->pdf->AddPage();
						$this->Header($nombre);
						$this->pdf->AliasNbPages();

						/* Se define el titulo, márgenes izquierdo, derecho y
						* el color de relleno predeterminado
						*/
						$this->pdf->SetTitle($nombre);
						$this->pdf->SetLeftMargin(10);
						$this->pdf->SetRightMargin(10);
						$this->pdf->SetFillColor(200,200,200);

						// Se define el formato de fuente: Arial, negritas, tamaño 9
						$this->pdf->SetFont('Arial', 'B', 9);
						/*
						* TITULOS DE COLUMNAS
						*
						* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
						*/
								$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
								$this->pdf->Cell(35,7,'Codigo','TBL',0,'C','1');
								$this->pdf->Cell(35,7,'Nombre','TBL',0,'C','1');
								$this->pdf->Cell(30,7,'Precio','TBL',0,'C','1');
								$this->pdf->Cell(30,7,'Cantidad Total','TBL',0,'C','1');
								$this->pdf->Cell(30,7,'Categoria','TBL',0,'C','1');
								$this->pdf->Cell(15,7,'Iva','TBL BR',0,'C','1');
								$this->pdf->Ln(7);
								// La variable $x se utiliza para mostrar un número consecutivo
								$x = 1;
								foreach ($list as $key => $Producto) {
									$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 10); 
									$Precio_Venta =  number_format($resultado,0,'.',',');
										$Nombre = $this->mi_libreria->getSubString($Producto->Nombre, 20);
								$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
								// Se imprimen los datos de cada cliente
								$this->pdf->Cell(35,5,$this->mi_libreria->getSubString($Producto->Codigo, 12),'BL',0,'L',0);
								$this->pdf->Cell(35,5,$Nombre,'BL',0,'L',0);
								$this->pdf->Cell(30,5,$Precio_Venta,'BL',0,'L',0);
								$this->pdf->Cell(30,5, $Producto->Cantidad_A += $Producto->Cantidad_D,'BL',0,'L',0);
								$this->pdf->Cell(30,5,$Producto->Categoria,'BL',0,'L',0);
								if ($Producto->Iva ==0) {
								 $this->pdf->Cell(15,5,'Excenta','BL BB BR ',0,'L',0);
								}else{
								 $this->pdf->Cell(15,5,$Producto->Iva,'BL BB BR ',0,'L',0);
								}

								//Se agrega un salto de linea
								$this->pdf->Ln(5);
								}
							$this->pdf->Output('pdf.pdf','F');  //save pdf
							$this->pdf->Output('pdf.pdf', 'I'); // show pdf
						} else {
								$data     = array(
								'titulo'  => 'No existen datos de busqueda',
								'titulo2' => $nombre,
								'titulo3' => 'No existen datos', );
                            	$this->load->view('Error/error.php', $data, FALSE);
						}

	}
	function listatodo($id='')
	{
			if (empty($id)) {
				$nombre = 'Listado Total de Productos';
			}else{
				$nombre = 'Listados de Productos Con Alertas de Stock';
			}
			
     		// Se carga la libreria fpdf
	        $this->load->library('pdf');
						// Se obtienen los clientes de la base de datos
						$this->load->model('Producto_Model','Producto');
						$list = $this->Producto->getproducto();
						if( !empty( $list ) ){
						// Creacion del PDF
						/*
						* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
						* heredó todos las variables y métodos de fpdf
						*/
						$this->pdf = new Pdf();
						// $this->pdf->Header($nombre);
						// Agregamos una página
						$this->pdf->AddPage();
						$this->Header($nombre);
						$this->pdf->AliasNbPages();

						/* Se define el titulo, márgenes izquierdo, derecho y
						* el color de relleno predeterminado
						*/
						$this->pdf->SetTitle($nombre);
						$this->pdf->SetLeftMargin(10);
						$this->pdf->SetRightMargin(10);
						$this->pdf->SetFillColor(200,200,200);

						// Se define el formato de fuente: Arial, negritas, tamaño 9
						$this->pdf->SetFont('Arial', 'B', 9);
						/*
						* TITULOS DE COLUMNAS
						*
						* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
						*/
								$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
								$this->pdf->Cell(35,7,'Codigo','TBL',0,'C','1');
								$this->pdf->Cell(35,7,'Nombre','TBL',0,'C','1');
								$this->pdf->Cell(30,7,'Precio','TBL',0,'C','1');
								$this->pdf->Cell(30,7,'Cantidad Total','TBL',0,'C','1');
								$this->pdf->Cell(30,7,'Categoria','TBL',0,'C','1');
								$this->pdf->Cell(15,7,'Iva','TBL BR',0,'C','1');
								$this->pdf->Ln(7);
								// La variable $x se utiliza para mostrar un número consecutivo
								$x = 1;
								foreach ($list as $key => $Producto) {
								if (!empty($id)) {
										if (10>($Producto->Cantidad_A + $Producto->Cantidad_D)) {
											$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 10); 
											$Precio_Venta =  number_format($resultado,0,'.',',');
											$Nombre = $this->mi_libreria->getSubString($Producto->Nombre, 20);
											$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
											// Se imprimen los datos de cada cliente
											$this->pdf->Cell(35,5,$this->mi_libreria->getSubString($Producto->Codigo, 12),'BL',0,'L',0);
											$this->pdf->Cell(35,5,$Nombre,'BL',0,'L',0);
											$this->pdf->Cell(30,5,$Precio_Venta,'BL',0,'L',0);
											$this->pdf->Cell(30,5, $Producto->Cantidad_A += $Producto->Cantidad_D,'BL',0,'L',0);
											$this->pdf->Cell(30,5,$Producto->Categoria,'BL',0,'L',0);
											if ($Producto->Iva ==0) {
											 $this->pdf->Cell(15,5,'Excenta','BL BB BR ',0,'L',0);
											}else{
											 $this->pdf->Cell(15,5,$Producto->Iva,'BL BB BR ',0,'L',0);
											}

											//Se agrega un salto de linea
											$this->pdf->Ln(5);
									    }
								}else{
									$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 10); 
									$Precio_Venta =  number_format($resultado,0,'.',',');
									$Nombre = $this->mi_libreria->getSubString($Producto->Nombre, 20);
									$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
									// Se imprimen los datos de cada cliente
									$this->pdf->Cell(35,5,$this->mi_libreria->getSubString($Producto->Codigo, 12),'BL',0,'L',0);
									$this->pdf->Cell(35,5,$Nombre,'BL',0,'L',0);
									$this->pdf->Cell(30,5,$Precio_Venta,'BL',0,'L',0);
									$this->pdf->Cell(30,5, $Producto->Cantidad_A += $Producto->Cantidad_D,'BL',0,'L',0);
									$this->pdf->Cell(30,5,$Producto->Categoria,'BL',0,'L',0);
									if ($Producto->Iva ==0) {
									 $this->pdf->Cell(15,5,'Excenta','BL BB BR ',0,'L',0);
									}else{
									 $this->pdf->Cell(15,5,$Producto->Iva,'BL BB BR ',0,'L',0);
									}

									//Se agrega un salto de linea
									$this->pdf->Ln(5);
									}
								}
							$this->pdf->Output('pdf.pdf','F');  //save pdf
							$this->pdf->Output('pdf.pdf', 'I'); // show pdf
						} else {
								$data     = array(
								'titulo'  => 'No existen datos de busqueda',
								'titulo2' => $nombre,
								'titulo3' => 'No existen datos', );
                            	$this->load->view('Error/error.php', $data, FALSE);
						}

	}

	public function Oventa($value='')
	{
	$nombre = 'Listados de Orden de Venta';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		       $this->load->model("Ordenventa_Model",'Orden_V');
				$list = $this->Orden_V->get_oventa($value);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);
				$this->pdf->SetFillColor(200,200,200);

				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Cliente','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Telefono','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Entrega','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Estado ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Monto','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						foreach ($list as $key => $orden_v) {
				$resultado = intval(preg_replace('/[^0-9]+/', '', $orden_v->Monto_Estimado), 10); 
				$resultado2 = intval(preg_replace('/[^0-9]+/', '', $orden_v->Monto_envio), 10); 
				$Monto =  number_format($resultado+$resultado2,0,'.',',');
				$mon = $this->mi_libreria->getSubString($orden_v->Nombres,30 );
						$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
						// Se imprimen los datos de cada cliente
						$this->pdf->Cell(40,5,$mon,'BL',0,'L',0);
						$this->pdf->Cell(40,5,$orden_v->tel,'BL',0,'L',0);
						$this->pdf->Cell(30,5,$orden_v->Entrega,'BL',0,'L',0);
						$this->pdf->Cell(40,5, $orden_v->Estado,'BL',0,'L',0);
						$this->pdf->Cell(30,5,$this->mi_libreria->getSubString($Monto,10 ),'BL BB BR',0,'L',0);
						//Se agrega un salto de linea
						$this->pdf->Ln(5);
						}
					$this->pdf->Output('pdf.pdf','F');  //save pdf
					$this->pdf->Output('pdf.pdf', 'I'); // show pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function Ovent($value='')
	{
	$nombre = 'Orden de Venta';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		       $this->load->model("Ordenventa_Model",'Orden_V');
		       		$this->load->library('Cart');
				$this->Orden_V->ver_detalles(array('Orden_idOrden' => $value ),$value );
		       	$list = $this->Orden_V->get_oventa($value);

				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/
						foreach ($list as $key => $value) {
						$this->pdf->Cell(35,7,'Usuario: '.$value->user,'0',0,'L');
						$this->pdf->Cell(60,7,'Cliente: '.$value->Nombres,'0',0,'C');
						$this->pdf->Cell(60,7,'Telefono: '.$value->tel,'0',0,'C');
						$this->pdf->Cell(35,7,'Orden: #00'.$value->idOrden ,' 0',0,'R');
						$this->pdf->Ln(7);
						}

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Codigo','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Producto','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Cantidad','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Precio ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Subtotal','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
				$x = 1;
				$i = 1;
                foreach ($this->cart->contents() as $items) {
                        foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                                        $iva =  $option_value;
                     }
                        $this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
						// Se imprimen los datos de cada cliente
						$this->pdf->Cell(40,5,$items['id'],'BL',0,'L',0);
						$this->pdf->Cell(40,5,$items['name'],'BL',0,'L',0);
						$this->pdf->Cell(30,5,$items['qty'],'BL',0,'L',0);
						$this->pdf->Cell(40,5, $this->cart->format_number($items['price']),'BL',0,'L',0);
						$this->pdf->Cell(30,5,$this->cart->format_number($items['subtotal']),'BL BB BR',0,'L',0);
						$this->pdf->Ln(5);
                 $i++;
                }
   
						foreach ($list as $key => $value) {
						$total = $value->Monto_envio + $value->Monto_Estimado;
						$this->pdf->Cell(120,7,'','',0,'c');
						$this->pdf->Cell(70,7,'Envio: '.$value->Monto_envio,' BB ',0,'L',0);
						$this->pdf->Ln(7);
						$this->pdf->Cell(120,7,'','0',0,'c');
						$this->pdf->Cell(70,7,'Monto total: '.number_format($total,0,'.',','),' BB ',0,'L',0);
						$this->pdf->Ln(7);
						}
				$this->pdf->Output('pdf.pdf','F');  //save pdf
				$this->pdf->Output('pdf.pdf', 'I'); // show pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function Ocompra($value='')
	{
	$nombre = 'Listados de Orden de Compra';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		        $this->load->model("Orden_Model",'Orden');
				$list = $this->Orden->get_compra($value);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);
				$this->pdf->SetFillColor(200,200,200);

				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'cliente','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Entrega','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Devolución','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Estado ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Monto','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						foreach ($list as $key => $orden_v) {
						$resultado = intval(preg_replace('/[^0-9]+/', '', $orden_v->Monto_Estimado), 10); 
						$Monto =  number_format($resultado,0,'.',',');
						$mon = $this->mi_libreria->getSubString($orden_v->Razon_Social,30 );
						$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
						// Se imprimen los datos de cada cliente
						$this->pdf->Cell(40,5,$mon,'BL',0,'L',0);
						$this->pdf->Cell(40,5,$orden_v->Entrega,'BL',0,'L',0);
						$this->pdf->Cell(30,5,$orden_v->Devolucion,'BL',0,'L',0);
						$this->pdf->Cell(40,5, $orden_v->Estado,'BL',0,'L',0);
						$this->pdf->Cell(30,5,$this->mi_libreria->getSubString($Monto,10 ),'BL BB BR',0,'L',0);
						//Se agrega un salto de linea
						$this->pdf->Ln(5);
						}
					$this->pdf->Output('pdf.pdf','F');  //save pdf
					$this->pdf->Output('pdf.pdf', 'I'); // show pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function Orden($value='')
	{
	$nombre = 'Orden de Compra';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		        $this->load->model("Orden_Model",'Orden');
				$list = $this->Orden->get_compra($value);
		       	$this->load->library('Cart');
				$this->Orden->ver_detalles(array('Orden_idOrden' => $value ),$value );
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/
						foreach ($list as $key => $value) {
						$this->pdf->Cell(35,7,'Usuario: '.$value->user,'0',0,'L');
						$this->pdf->Cell(60,7,'Cliente: '.$value->Razon_Social,'0',0,'C');
						$this->pdf->Cell(60,7,'Telefono: '.$value->tel,'0',0,'C');
						$this->pdf->Cell(35,7,'Orden: #00'.$value->idOrden ,' 0',0,'R');
						$this->pdf->Ln(7);
						}

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Codigo','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Producto','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Cantidad','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Precio ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Subtotal','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
				$x = 1;
				$i = 1;
                foreach ($this->cart->contents() as $items) {
                        foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                                        $iva =  $option_value;
                     }
                        $this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
						// Se imprimen los datos de cada cliente
						$this->pdf->Cell(40,5,$items['id'],'BL',0,'L',0);
						$this->pdf->Cell(40,5,$items['name'],'BL',0,'L',0);
						$this->pdf->Cell(30,5,$items['qty'],'BL',0,'L',0);
						$this->pdf->Cell(40,5, $this->cart->format_number($items['price']),'BL',0,'L',0);
						$this->pdf->Cell(30,5,$this->cart->format_number($items['subtotal']),'BL BB BR',0,'L',0);
						$this->pdf->Ln(5);
                 $i++;
                }
   
						foreach ($list as $key => $value) {
						$this->pdf->Cell(120,7,'','0',0,'c');
						$this->pdf->Cell(70,7,'Monto total: '.number_format($value->Monto_Estimado,0,'.',','),' BB ',0,'L',0);
						$this->pdf->Ln(7);
						}
				$this->pdf->Output('pdf.pdf','F');  //save pdf
				$this->pdf->Output('pdf.pdf', 'I'); // show pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function remision($value='')
	{
	$nombre = 'Listados de Notas de Remision';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		       $this->load->model("Ordenventa_Model",'Orden_V');
				$list = $this->Orden_V->getremision();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);
				$this->pdf->SetFillColor(200,200,200);

				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Razon Social','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Fecha','TBL',0,'C','1');
						$this->pdf->Cell(55,7,'Condicion','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Monto','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						foreach ($list as $key => $orden_v) {
						$resultado = intval(preg_replace('/[^0-9]+/', '', $orden_v->Monto_Estimado), 10); 
						$Monto =  number_format($resultado,0,'.',',');
						$mon = $this->mi_libreria->getSubString($orden_v->Nombres,30 );
						$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
						// Se imprimen los datos de cada cliente
						$this->pdf->Cell(50,5,$mon,'BL',0,'L',0);
						$this->pdf->Cell(35,5,$orden_v->Entrega,'BL',0,'L',0);
				switch ($orden_v->Compra_Venta) {
					case '3':
						$this->pdf->Cell(55,5,'Nota de Entrada Productos ','BL',0,'L',0);
						break;
					case '4':
						$this->pdf->Cell(55,5,'Nota de Salida Productos ','BL',0,'L',0);
						break;
					case '5':
						$this->pdf->Cell(55,5,'Nota de Devolucion Productos ','BL',0,'L',0);
						break;
					case '6':
						$this->pdf->Cell(55,5,'Entrada Productos En Produccion ','BL',0,'L',0);
						break;
					case '7':
						$this->pdf->Cell(55,5,'Sin Accion ','BL',0,'L',0);
						break;
				}
						
						$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Monto,10 ),'BL BB BR',0,'L',0);
						//Se agrega un salto de linea
						$this->pdf->Ln(5);
						}
					$this->pdf->Output('pdf.pdf','F');  //save pdf
					$this->pdf->Output('pdf.pdf', 'I'); // show pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function lisremision($value='')
	{
	$nombre = 'Notas de Remision';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		        $this->load->model("Orden_Model",'Orden');
				$list = $this->Orden->get_compra($value);
		       	$this->load->library('Cart');
				$this->Orden->ver_detalles(array('Orden_idOrden' => $value ),$value );
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/
						foreach ($list as $key => $value) {
						$this->pdf->Cell(35,7,'Usuario: '.$value->user,'0',0,'L');
						$this->pdf->Cell(60,7,'Razon Zocial: '.$value->Razon_Social,'0',0,'C');
						$this->pdf->Cell(60,7,'Telefono: '.$value->tel,'0',0,'C');
						$this->pdf->Cell(35,7,'Orden: #00'.$value->idOrden ,' 0',0,'R');
						$this->pdf->Ln(7);
						}

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Codigo','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Producto','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Cantidad','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Precio ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Subtotal','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
				$x = 1;
				$i = 1;
                foreach ($this->cart->contents() as $items) {
                        foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value) {
                                        $iva =  $option_value;
                     }
                        $this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
						// Se imprimen los datos de cada cliente
						$this->pdf->Cell(40,5,$items['id'],'BL',0,'L',0);
						$this->pdf->Cell(40,5,$items['name'],'BL',0,'L',0);
						$this->pdf->Cell(30,5,$items['qty'],'BL',0,'L',0);
						$this->pdf->Cell(40,5, $this->cart->format_number($items['price']),'BL',0,'L',0);
						$this->pdf->Cell(30,5,$this->cart->format_number($items['subtotal']),'BL BB BR',0,'L',0);
						$this->pdf->Ln(5);
                 $i++;
                }
   
						foreach ($list as $key => $value) {
						$this->pdf->Cell(120,7,'','0',0,'c');
						$this->pdf->Cell(70,7,'Monto total: '.number_format($value->Monto_Estimado,0,'.',','),' BB ',0,'L',0);
						$this->pdf->Ln(7);
						}
				$this->pdf->Output('pdf.pdf','F');  //save pdf
				$this->pdf->Output('pdf.pdf', 'I'); // show pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function Deuda_e($value='')
	{
	$nombre = 'Listados Deuda Empresa';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		       $this->load->model("Deuda_empresa_Model",'Deuda');
				$list = $this->Deuda->getdeuda();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);
				$this->pdf->SetFillColor(200,200,200);

				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Cuota Pendiente','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Proveedor','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Monto Total','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Pago Parcial','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Monto Pendiente','TBL BR',0,'C','1');


						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						foreach ($list as $key => $Deuda) {
						$Parcial_todo = $this->Deuda->sum_pagos_tods($Deuda->idCuenta_Corriente_Empresa);
						$xx =  round($Deuda->inporte_total) ;
						$mpendiente = round( $xx - $Parcial_todo) ;
			    		if ($Parcial_todo == 0 && $Deuda->esta != 2 && $Deuda->Num_cuota > 0) {
			    			$this->Deuda->res_factura($Deuda->idFactura_Compra,2);
			    		}elseif ($Parcial_todo > 0 && $Deuda->esta != 1 && $Deuda->Num_cuota > 0) {
			    			$this->Deuda->res_factura($Deuda->idFactura_Compra,1);
			    		}
						if ($Deuda->Num_cuota == 1 ) {
							if ($mpendiente > 0)
							{
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(35,5,$Deuda->Num_cuota,'BL',0,'L',0);
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Deuda->Razon_Social, 15).' ('.$this->mi_libreria->getSubString($Deuda->Vendedor, 15).')','BL',0,'L',0);
							$this->pdf->Cell(35,5,number_format($xx,0,',','.'),'BL',0,'L',0);
					        $this->pdf->Cell(35,5,number_format($Parcial_todo,0,',','.'),'BL',0,'L',0);
					         $this->pdf->Cell(35,5,number_format($mpendiente,0,',','.'),'BL BB BR',0,'L',0);
							}
						}else{
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($Deuda->Num_cuota == 0) {
							$this->pdf->Cell(35,5,'1','BL',0,'L',0);
							}else{
							$this->pdf->Cell(35,5,$Deuda->Num_cuota,'BL',0,'L',0);
							}
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Deuda->Razon_Social, 15).' ('.$this->mi_libreria->getSubString($Deuda->Vendedor, 15).')','BL',0,'L',0);
							$this->pdf->Cell(35,5,number_format($xx,0,',','.'),'BL',0,'L',0);
					        $this->pdf->Cell(35,5,number_format($Parcial_todo,0,',','.'),'BL',0,'L',0);
					        $this->pdf->Cell(35,5,number_format($mpendiente,0,',','.'),'BL BB BR',0,'L',0);
						}
						//Se agrega un salto de linea
						$this->pdf->Ln(5);
						}
					$this->pdf->Output('pdf.pdf','F');  //save pdf
					$this->pdf->Output('pdf.pdf', 'I'); // show pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function lisdeuda($id)
	{
	$nombre = 'Listado Detallado Deuda Empresa';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		       $this->load->model("Deuda_empresa_Model",'Deuda');
				$list = $this->Deuda->get_Deudalist($id);
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(15,7,'Cuota N.','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Comprovante','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Provvedor','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Importe a Pagar ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Vencimiento','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
				foreach ($list as $key => $Deuda) {
					$Parcial_todo = $this->Deuda->sum_pagos_($Deuda->idCuenta_Corriente_Empresa);
					$xx =  round($Deuda->inporte_total) ;
					$mpendiente =  $Deuda->inporte_total - $Parcial_todo ;
		    		if ($Parcial_todo == 0 && $Deuda->esta != 2 && $Deuda->Num_cuota > 0) {
		    			$this->Deuda->res_factura($Deuda->idFactura_Compra,2);
		    		}elseif ($Parcial_todo > 0 && $Deuda->esta != 1 && $Deuda->Num_cuota > 0) {
		    			$this->Deuda->res_factura($Deuda->idFactura_Compra,1);
		    		}
						$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
						$this->pdf->Cell(15,5,$Deuda->Num_cuota,'BL',0,'C',0);
						$this->pdf->Cell(50,5,'Recibo N. '. $Deuda->Num_Recibo,'BL',0,'C',0);
						$this->pdf->Cell(50,5,$this->mi_libreria->getSubString($Deuda->Razon_Social, 15).' ('.$this->mi_libreria->getSubString($Deuda->Vendedor, 15).')','BL',0,'C',0);
				        $this->pdf->Cell(35,5, number_format($mpendiente,0,',','.'),'BL',0,'C',0);
				         $this->pdf->Cell(30,5,$Deuda->Fecha_Ven,'BL BB BR',0,'C',0);
				         $sum += $Deuda->inporte_total - $Parcial_todo;
				 $this->pdf->Ln(5);
				}
						$this->pdf->Cell(105,7,'','0',0,'c');
						$this->pdf->Cell(30,7,'Monto total:',' BB ',0,'L',0);
						$this->pdf->Cell(30,7,number_format($sum,0,'.',','),' BB ',0,'L',0);
						$this->pdf->Ln(7);

        		$this->pdf->Output('pdf.pdf','F');  //save pdf
				$this->pdf->Output('pdf.pdf', 'I'); // show pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function pagadas()
	{
	$nombre = 'Lista de Deudas Pagadas';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		       $this->load->model("Deuda_empresa_Model",'Deuda');
				$list = $this->Deuda->get_Deuda_pagads();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(15,7,'Cuota N.','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Comprovante','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Provvedor','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Importe a Pagar ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'M. Pagado','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'M. Pendiente','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
				foreach ($list as $key => $Deuda) {
						$Parcial_todo = $this->Deuda->sum_pagos_($Deuda->id);
						if ($Deuda->inporte_total > $Parcial_todo  ) {
							$mpendiente =  number_format($Deuda->inporte_total - $Parcial_todo,0,',','.') ;
						}else{
							$mpendiente =  '';
						}
						if ($mpendiente == 0 && $Deuda->esta != 1) {
							$this->Deuda->Estado_1($Deuda->id);
						}elseif ($Parcial_todo > 0 && $Deuda->esta != 3) {
							// $this->Deuda->Estado_3($Deuda->id);
						}elseif ($Parcial_todo == 0 && $Deuda->esta != 0) {
							   $this->Deuda->Estado_0($Deuda->id);
						}
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(15,5,$Deuda->Num_cuota,'BL',0,'C',0);
						if ($Deuda->Tipo_Compra == 0 ) { // voleta
						$this->pdf->Cell(40,5,'Recibo N. '. $Deuda->Num_Recibo,'BL',0,'C',0);
						}elseif ($Deuda->Tipo_Compra == 1 ) { // factura
						$this->pdf->Cell(40,5,'Recibo N. '. $Deuda->Num_factura_Compra,'BL',0,'C',0);						
						}

							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Deuda->Razon_Social, 15).' ('.$this->mi_libreria->getSubString($Deuda->Vendedor, 15).')','BL',0,'C',0);
							$this->pdf->Cell(30,5, number_format($Deuda->inporte_total,0,',','.'),'BL',0,'C',0);
							$this->pdf->Cell(30,5,number_format($Parcial_todo,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Cell(30,5,$mpendiente,'BL BB BR',0,'C',0);
							$sum += $Parcial_todo;
				 $this->pdf->Ln(5);
				}
						$this->pdf->Cell(115,7,'','0',0,'c');
						$this->pdf->Cell(30,7,'Monto total:',' BB ',0,'L',0);
						$this->pdf->Cell(30,7,number_format($sum,0,'.',','),' BB ',0,'L',0);
						$this->pdf->Ln(7);

        		$this->pdf->Output('pdf.pdf','F');  //save pdf
				$this->pdf->Output('pdf.pdf', 'I'); // show pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function compras($value='')
	{
	$nombre = 'Listado Compras';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		        $this->load->model("Compra_Model",'Compra');
		        $list = $this->Compra->getCompra();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

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
				foreach ($list as $key => $Compra) {

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
						// $this->pdf->Cell(105,7,'','0',0,'c'); compra
						// $this->pdf->Cell(30,7,'Monto total:',' BB ',0,'L',0);
						// $this->pdf->Cell(30,7,number_format($sum,0,'.',','),' BB ',0,'L',0);
						// $this->pdf->Ln(7);
				$fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/Compra/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Compra/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}





	public function compranull($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Comprobantes Anulados';
				// Se obtienen los clientes de la base de datos
		        $this->load->model("Compra_Model",'Compra');
                $list = $this->Compra->getanul();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Comprobante','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Proveedor','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Monto Total ','TBL BR',0,'C','1');

						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
				foreach ($list as $key => $listc) {
				            $Monto =  number_format( $listc->Monto_Total,0,'.',',');
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($listc->Tipo_Compra == 0 ) { // voleta
							$this->pdf->Cell(60,5,'Recibo N. '. $listc->Ticket,'BL',0,'C',0);
							}elseif ($listc->Tipo_Compra == 1 ) { // factura
							$this->pdf->Cell(60,5,'Factura N. '. $listc->Num_factura_Compra,'BL',0,'C',0);
							}

							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString($listc->Razon_Social, 30).' ('.$this->mi_libreria->getSubString($listc->Vendedor, 30).')','BL',0,'C',0);
							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString($Monto,30 ),'BL BB BR ',0,'C',0);
							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/compranull/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/compranull/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function Lisnul($id='')
	{
    // Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		        $this->load->model("Compra_Model",'Compra');
		        $this->db->select('Descuento_Total,Num_factura_Compra,Ticket');
		        $this->db->where('idFactura_Compra', $id);
		        $query=$this->db->get('Factura_Compra');
		        $row = $query->row();
		        if (!empty($row->Num_factura_Compra)) {
		        $nombre = 'Listado Detalle Compras Anuladas Segun Factura N. '.$row->Num_factura_Compra;
		        }else{
		        $nombre = 'Listado Detalle Compras Anuladas Segun Recibo N. '.$row->Ticket;
		        }
		        $list = $this->Compra->detale(array('Factura_Compra_idFactura_Compra' => $id));
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Cantidad','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Nombre','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Precio ','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Descuento ','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Subtotal','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
				foreach ($list as $key => $listc) {
							$resultado = intval(preg_replace('/[^0-9]+/', '', $listc->Precio_Costo), 10); 
							$val = $resultado * $listc->can;
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(35,5,$listc->can,'BL',0,'C',0);
							$this->pdf->Cell(35,5,$this->mi_libreria->getSubString($listc->Nombre, 25),'BL',0,'C',0);
							$this->pdf->Cell(35,5,number_format( $resultado,0,',','.'),'BL ',0,'C',0);
							$this->pdf->Cell(35,5,'Pagado','BL ',0,'C',0);
							$this->pdf->Cell(40,5, number_format( $val,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
							$sum += $val;
				}
				if (empty($row->Descuento_Total)) {
						$this->pdf->Cell(115,7,'','0',0,'c');
						$this->pdf->Cell(35,7,'Monto total:',' BB ',0,'L',0);
						$this->pdf->Cell(40,7,number_format($sum,0,'.','.'),' BB ',0,'C',0);
						$this->pdf->Ln(7);
				}else{
					$total = $sum - $row->Descuento_Total;
						$this->pdf->Cell(115,7,'','0',0,'c');
						$this->pdf->Cell(35,7,'Descuento:',' BB ',0,'L',0);
						$this->pdf->Cell(40,7,number_format($row->Descuento_Total,0,'.','.'),' BB ',0,'C',0);
						$this->pdf->Ln(7);
						$this->pdf->Cell(115,7,'','0',0,'c');
						$this->pdf->Cell(35,7,'Monto total:',' BB ',0,'L',0);
						$this->pdf->Cell(40,7,number_format($total,0,'.','.'),' BB ',0,'C',0);
						$this->pdf->Ln(7);
				}


        		$this->pdf->Output('pdf/Compra_Detallado/compra'.$id.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Compra_Detallado/compra'.$id.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function cdevolucion($value='')
	{
    // Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
	            $this->load->model("Cdevolver_Model",'Devolver');

		        $nombre = 'Listado de Devoluciones';

		         $list = $this->Devolver->getDevolver();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Comprobante','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Proveedor','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Fecha ','TBL ',0,'C','1');
		                $this->pdf->Cell(40,7,'Monto Total ','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Devolver) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
						if ($Devolver->Tipo_Compra == 0 ) { // voleta
							$this->pdf->Cell(50,5,'Recibo N. '. $Devolver->Ticket,'BL',0,'C',0);
							}elseif ($Devolver->Tipo_Compra == 1 ) { // factura
							$this->pdf->Cell(50,5,'Factura N. '. $Devolver->Num_factura_Compra,'BL',0,'C',0);
							}
							$this->pdf->Cell(50,5,$this->mi_libreria->getSubString($Devolver->Razon_Social.'-'.$Devolver->Vendedor, 35),'BL',0,'C',0);
							$this->pdf->Cell(40,5,$Devolver->Fecha,'BL  ',0,'C',0);
								$this->pdf->Cell(40,5,number_format($Devolver->Monto_Total,0,'.',','),'BL BB BR ',0,'C',0);
							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/Cdevolucion/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Cdevolucion/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function devolucion($id='')
	{
                // Se carga la libreria fpdf
                $this->load->library('pdf');
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
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(15,7,'Cantidad','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Nombre','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Precio ','TBL',0,'C','1');
						$this->pdf->Cell(75,7,'Estado ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Subtotal','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
				foreach ($list as $key => $listc) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(15,5,$listc->Cantidad,'BL',0,'C',0);
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString($listc->Nombre, 25),'BL',0,'C',0);
							$this->pdf->Cell(30,5,$listc->Precio,'BL ',0,'C',0);
							$this->pdf->Cell(75,5,$this->mi_libreria->getSubString($listc->Estado, 43),'BL ',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $listc->Precio * $listc->Cantidad,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
							$sum += $listc->Precio * $listc->Cantidad;
				}

						$this->pdf->Cell(125,7,'','0',0,'c');
						$this->pdf->Cell(35,7,'Monto total:',' BB ',0,'L',0);
						$this->pdf->Cell(30	,7,number_format( $row->Monto_Total,0,'.','.'),' BB ',0,'C',0);
						$this->pdf->Ln(7);


        		$this->pdf->Output('pdf/Compra_Detallado/compra'.$id.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Compra_Detallado/compra'.$id.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
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
				// echo var_dump($list);
				if($list ){
				// $this->load->model('Deuda_cliente_Model', 'Cuenta');
                $this->load->library('pdf');
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetFillColor(200,200,200);

				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Cuota Pendiente','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Cliente','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Monto Total','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Pago Parcial','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Monto Pendiente','TBL BR',0,'C','1');


						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						foreach ($list as $key => $Cuenta) {
						$Parcial_todo = $this->sum_pagos_tods($Cuenta->idCuenta_Corriente_Cliente);
						$xx =  round($Cuenta->inporte_total) ;
						$mpendiente = round( $xx - $Parcial_todo) ;
			    		if ($Parcial_todo == 0 && $Cuenta->esta != 2 && $Cuenta->Num_cuota > 0) {
			    			$this->Cuenta->res_factura($Cuenta->idFactura_Venta,2);
			    		}elseif ($Parcial_todo > 0 && $Cuenta->esta != 1 && $Cuenta->Num_cuota > 0) {
			    			$this->Cuenta->res_factura($Cuenta->idFactura_Venta,1);
			    		}
						if ($Cuenta->Num_cuota == 1 ) {
							if ($mpendiente > 0)
							{
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(35,5,$Cuenta->Num_cuota,'BL',0,'L',0);
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Cuenta->Nombres, 15).' ('.$this->mi_libreria->getSubString($Cuenta->Apellidos, 15).')','BL',0,'L',0);
							$this->pdf->Cell(35,5,number_format($xx,0,',','.'),'BL',0,'L',0);
					        $this->pdf->Cell(35,5,number_format($Parcial_todo,0,',','.'),'BL',0,'L',0);
					         $this->pdf->Cell(35,5,number_format($mpendiente,0,',','.'),'BL BB BR',0,'L',0);
							}
						}else{
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($Cuenta->Num_cuota == 0) {
							$this->pdf->Cell(35,5,'1','BL',0,'L',0);
							}else{
							$this->pdf->Cell(35,5,$Cuenta->Num_cuota,'BL',0,'L',0);
							}
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Cuenta->Nombres, 15).' ('.$this->mi_libreria->getSubString($Cuenta->Apellidos, 15).')','BL',0,'L',0);
							$this->pdf->Cell(35,5,number_format($xx,0,',','.'),'BL',0,'L',0);
					        $this->pdf->Cell(35,5,number_format($Parcial_todo,0,',','.'),'BL',0,'L',0);
					        $this->pdf->Cell(35,5,number_format($mpendiente,0,',','.'),'BL BB BR',0,'L',0);
						}
						//Se agrega un salto de linea
						$this->pdf->Ln(5);
						}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
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
	public function listadeuda($id)
	{
	$nombre = 'Listado Detallado Deuda de Clientes';
	// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
				$list = $this->get_Deudalist($id);
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(15,7,'Cuota N.','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Comprovante','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Cliente','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Importe a Cobrar ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Vencimiento','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
				foreach ($list as $key => $Cuenta) {
					$Parcial_todo = $this->sum_pagos_($Cuenta->idCuenta_Corriente_Cliente);
					$xx =  round($Cuenta->inporte_total) ;
					$mpendiente =  $Cuenta->inporte_total - $Parcial_todo ;
		    		if ($Parcial_todo == 0 && $Cuenta->esta != 2 && $Cuenta->Num_cuota > 0) {
		    			$this->Cuenta->res_factura($Cuenta->idFactura_Compra,2);
		    		}elseif ($Parcial_todo > 0 && $Cuenta->esta != 1 && $Cuenta->Num_cuota > 0) {
		    			$this->Cuenta->res_factura($Cuenta->idFactura_Compra,1);
		    		}
						$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
						$this->pdf->Cell(15,5,$Cuenta->Num_cuota,'BL',0,'C',0);
						$this->pdf->Cell(50,5,'Recibo N. '. $Cuenta->Num_Recibo,'BL',0,'C',0);
						$this->pdf->Cell(50,5,$this->mi_libreria->getSubString($Cuenta->Nombres, 15).' ('.$this->mi_libreria->getSubString($Cuenta->Apellidos, 15).')','BL',0,'C',0);
				        $this->pdf->Cell(35,5, number_format($mpendiente,0,',','.'),'BL',0,'C',0);
				         $this->pdf->Cell(30,5,$Cuenta->Fecha_Ven,'BL BB BR',0,'C',0);
				         $sum += $Cuenta->inporte_total - $Parcial_todo;
				 $this->pdf->Ln(5);
				}
						$this->pdf->Cell(105,7,'','0',0,'c');
						$this->pdf->Cell(30,7,'Monto total:',' BB ',0,'L',0);
						$this->pdf->Cell(30,7,number_format($sum,0,'.',','),' BB ',0,'L',0);
						$this->pdf->Ln(7);

			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/deudaCliente/detallado/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/deudaCliente/detallado/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function lispagadas()
	{
	$nombre = 'Lista de Cuentas Pagadas';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos

				$list = $this->get_Deuda_pagads();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(15,7,'Cuota N.','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Comprovante','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Cliente','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Importe a Cobrar ','TBL',0,'C','1');
						$this->pdf->Cell(25,7,'M. Pagado','TBL',0,'C','1');
						$this->pdf->Cell(25,7,'Pendiente','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
						// $this->load->model("Deuda_Cliente_Model",'Cuenta');
				foreach ($list as $key => $Cuenta) {
						$Parcial_todo = $this->sum_pagos_($Cuenta->id);
						if ($Cuenta->inporte_total > $Parcial_todo  ) {
							$mpendiente =  number_format($Cuenta->inporte_total - $Parcial_todo,0,',','.') ;
						}else{
							$mpendiente =  '';
						}
						if ($mpendiente == 0 && $Cuenta->esta != 1) {
							$this->Estado_1($Cuenta->id);
						}elseif ($Parcial_todo > 0 && $Cuenta->esta != 3) {
							// $this->Cuenta->Estado_3($Cuenta->id);
						}elseif ($Parcial_todo == 0 && $Cuenta->esta != 0) {
							   $this->Estado_0($Cuenta->id);
						}
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(15,5,$Cuenta->Num_cuota,'BL',0,'C',0);
						if ($Cuenta->Tipo_Venta == 0 ) { // voleta
						$this->pdf->Cell(40,5,'Recibo N. '. $Cuenta->Num_Recibo,'BL',0,'C',0);
						}elseif ($Cuenta->Tipo_Venta == 1 ) { // factura
						$this->pdf->Cell(40,5,'Recibo N. '. $Cuenta->Num_Factura_Venta,'BL',0,'C',0);						
						}

							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Cuenta->Nombres, 15).' ('.$this->mi_libreria->getSubString($Cuenta->Apellidos, 15).')','BL',0,'C',0);
							$this->pdf->Cell(30,5, number_format($Cuenta->inporte_total,0,',','.'),'BL',0,'C',0);
							$this->pdf->Cell(25,5,number_format($Parcial_todo,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Cell(25,5,$mpendiente,'BL BB BR',0,'C',0);
							$sum += $Parcial_todo;
				 $this->pdf->Ln(5);
				}
						$this->pdf->Cell(115,7,'','0',0,'c');
						$this->pdf->Cell(30,7,'Monto total:',' BB ',0,'L',0);
						$this->pdf->Cell(30,7,number_format($sum,0,'.',','),' BB ',0,'L',0);
						$this->pdf->Ln(7);

			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/deudaCliente/detallado'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/deudaCliente/detallado'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}
    public function Estado_0($id)
    {

                    $this->db->set('Estado', '0');
                    $this->db->where('idCuenta_Corriente_Cliente', $id);
                    $this->db->set('Fecha_Pago', date("Y-m-d").' : '.strftime( "%H:%M", time() ));
                    $query = $this->db->update('Cuenta_Corriente_Cliente');

    }

    public function Estado_1($id)
    {

                    $this->db->set('Estado', '1');
                    $this->db->where('idCuenta_Corriente_Cliente', $id);
                    $this->db->set('Fecha_Pago', date("Y-m-d").' : '.strftime( "%H:%M", time() ));
                    $query = $this->db->update('Cuenta_Corriente_Cliente');

    }

	public function ventas($value='')
	{
	$nombre = 'Listado Ventas';
		// Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
				$this->load->model("Venta_Model",'Venta');
				$list = $this->Venta->getVenta();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

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
				foreach ($list as $key => $Compra) {

				if ($Compra->Estado == 0) {
							
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($Compra->Tipo_Venta == 0) {
								$this->pdf->Cell(45,5,'Recibo N.'.$Compra->Ticket,'BL',0,'C',0);
							}else{
								$this->pdf->Cell(45,5,'Factura N. '. $Compra->Num_Factura_Venta,'BL',0,'C',0);
							}
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Compra->Nombres.'-'.$Compra->Apellidos, 25),'BL',0,'C',0);
							$this->pdf->Cell(35,5,$Compra->Fecha_expedicion.'  '.$Compra->Hora,'BL ',0,'C',0);
							$this->pdf->Cell(30,5,'Pagado','BL ',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $Compra->Monto_Total,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
				}elseif ($Compra->Estado == 1) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($Compra->Tipo_Venta == 0) {
								$this->pdf->Cell(45,5,'Recibo N.'.$Compra->Ticket,'BL',0,'C',0);
							}else{
								$this->pdf->Cell(45,5,'Factura N. '. $Compra->Num_Factura_Venta,'BL',0,'C',0);
							}

							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Compra->Nombres.'-'.$Compra->Apellidos, 25),'BL',0,'C',0);
							$this->pdf->Cell(35,5,$Compra->Fecha_expedicion.'  '.$Compra->Hora,'BL ',0,'C',0);
							$this->pdf->Cell(30,5,'Parcial','BL ',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $Compra->Monto_Total,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
				}elseif ($Compra->Estado == 2) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($Compra->Tipo_Venta == 0) {
								$this->pdf->Cell(45,5,'Recibo N.'.$Compra->Ticket,'BL',0,'C',0);
							}else{
								$this->pdf->Cell(45,5,'Factura N. '. $Compra->Num_Factura_Venta,'BL',0,'C',0);
							}

							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($Compra->Nombres.'-'.$Compra->Apellidos, 25),'BL',0,'C',0);
							$this->pdf->Cell(35,5,$Compra->Fecha_expedicion.'  '.$Compra->Hora,'BL ',0,'C',0);
							$this->pdf->Cell(30,5,'No Pagado','BL ',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $Compra->Monto_Total,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
				}


				}
						// $this->pdf->Cell(105,7,'','0',0,Venta'c');
						// $this->pdf->Cell(30,7,'Monto total:',' BB ',0,'L',0);
						// $this->pdf->Cell(30,7,number_format($sum,0,'.',','),' BB ',0,'L',0);
						// $this->pdf->Ln(7);
				$fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/Venta/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Venta/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function venta($id='')
	{
    // Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
				$this->load->model("Venta_Model",'Venta');
				$this->db->join('Cliente', 'Factura_Venta.Cliente_idCliente = Cliente.idCliente', 'inner');
		        $this->db->where('idFactura_Venta', $id);
		        $query=$this->db->get('Factura_Venta');
		        $row = $query->row();
		        // echo var_dump($row);
		        if (!empty($row->Num_Factura_Venta)) {
		        $nombre = 'Factura';
		        }else{
		        $nombre = 'Recibo';
		        }
		        $list = $this->Venta->detale(array('Factura_Venta_idFactura_Venta' => $id));
				// echo var_dump($list);
				if( !empty( $list ) ){
			/*
			* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
			* heredó todos las variables y métodos de fpdf
			*/
			$this->load->library('factura');
			$this->pdf = new Factura();
			// $this->pdf->Header($nombre);
			// Agregamos una página
			$this->pdf->AddPage();
			// Define el alias para el número de página que se imprimirá en el pie
			$this->pdf->AliasNbPages();
		     $this->headfactura($nombre,$row);
			$this->pdf->SetFillColor(250,250,250);
			$this->pdf->SetFont('Arial', 'b', 8);
			$this->pdf->Cell(25,10,'FECHA:   '.date("d",strtotime($row->Fecha_expedicion)),'',0,'L','1');
			$this->pdf->Cell(50,10,'DE   '.nombremes(date("m",strtotime($row->Fecha_expedicion))),'',0,'L','1');
			$this->pdf->Cell(25,10,'DEL 20'.date("y",strtotime($row->Fecha_expedicion)),'',0,'L','1');
			if ($row->Contado_Credito == 1) {
			   $this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE VENTA'),'',0,'L','1');
			    $this->pdf->Cell(25,10,'CONTADO:  X','',0,'C','1');
			    $this->pdf->Cell(25,10,utf8_decode('CRÉDITO: '),'',0,'C','1');
			}else{
			   $this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE VENTA'),'',0,'L','1');
			    $this->pdf->Cell(25,10,'CONTADO:','',0,'C','1');
			     $this->pdf->Cell(25,10,utf8_decode('CRÉDITO X'),'',0,'C','1');
			}


			$this->pdf->Ln(7);

			$this->pdf->Cell(42,10,utf8_decode('NOMBRE O RAZÓN SOCIAL:'),'',0,'L','1');
			$this->pdf->Cell(98,10,utf8_decode($row->Nombres.' '.$row->Apellidos),'',0,'L','1');
			$this->pdf->Cell(50,10,'R.U.C. : '.$row->Ruc,'',0,'L','1');
			$this->pdf->Ln(7);

			$this->pdf->Cell(60,10,utf8_decode('NOTA DE REMISIÓN Nº:'),'',0,'L','1');
			$this->pdf->Cell(40,10,'TEL.: '.$row->Telefono,'',0,'L','1');
			$this->pdf->Cell(90,10,'DOMICILIO:','',0,'L','1');
			$this->pdf->Ln(10);

			/* Se define el titulo, márgenes izquierdo, derecho y
			* el color de relleno predeterminado*/
			$this->pdf->SetFillColor(250,250,250);

			// Se define el formato de fuente: Arial, negritas, tamaño 9
			/*
			* TITULOS DE COLUMNAS
			*
			* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
			*/
		    $this->pdf->SetFont('Arial', 'b', 8);
			$this->pdf->Cell(14,10,'Cantidad','TBL',0,'C','1');
			$this->pdf->Cell(75,10,utf8_decode('Descripción de Mercadería y/o Servicios'),'TBL',0,'C','1');
			$this->pdf->Cell(21,10,'Precio Unitario','TBL',0,'C','1');
			$this->pdf->Cell(80,5,'VALOR DE VENTAS','TBL BR',0,'C','1');
			$this->pdf->Ln(5);
			$this->pdf->Cell(110,5,'',0,'C','1');
			$this->pdf->Cell(20,5,'Exentas','TBL BR',0,'C','1');
			$this->pdf->Cell(30,5,'5%','TBL BR',0,'C','1');
			$this->pdf->Cell(30,5,'10%','TBL BR',0,'C','1');
			$this->pdf->Ln(5);

						$x = 1;
						$sum = 0; $iva5 = 0; $iva10 =0;
				foreach ($list as $key => $listc) {
							$resultado = intval(preg_replace('/[^0-9]+/', '', $listc->Precio), 10); 
							$val = $resultado * $listc->can;
							$sum += $val;
				$this->pdf->SetFont('Arial', 'b', 7);
				$this->pdf->Cell(14,6,$listc->can,'TBL BR',0,'C',0);
				$this->pdf->Cell(75,6,$this->mi_libreria->getSubString($listc->Nombre, 25),'TBL BR',0,'L',0);
				$this->pdf->Cell(21,6,number_format( $resultado,0,',','.'),'TBL BR',0,'C',0);
				if ($listc->Iva == '0') {
				    $this->pdf->Cell(20,6,number_format( $val,0,',','.'),'TBL BR',0,'C',0);

				}else{
				    $this->pdf->Cell(20,6,'','TBL BR',0,'C',0);
				}
				if ($listc->Iva == '5') {
				    $this->pdf->Cell(30,6,number_format( $val,0,',','.'),'TBL BR',0,'C',0);
				     $iva5 += $val/21;
				}else{
				    $this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
				}
				if ($listc->Iva == '10') {
				    $this->pdf->Cell(30,6,number_format( $val,0,',','.'),'TBL BR',0,'C',0);
				     $iva10 += $val/11;
				}else{
				    $this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
				}
				$this->pdf->Ln(6);
				$x ++;




				}
				if ($x<10) {
					$res = 10 - $x;
					for ($i=0; $i <= $res; $i++) { 
						$this->pdf->Cell(14,6,'','TBL BR',0,'C',0);
						$this->pdf->Cell(75,6,'','TBL BR',0,'L',0);
						$this->pdf->Cell(21,6,'','TBL BR',0,'C',0);
						$this->pdf->Cell(20,6,'','TBL BR',0,'C',0);
						$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
						$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
						$this->pdf->Ln(6);
					}
					
				}
		    	$this->pdf->SetFillColor(0, 27, 0, 1);
				$this->pdf->SetDrawColor(0, 27, 0, 1);
				$this->pdf->SetTextColor(0, 27, 0, 1);
				$this->pdf->Cell(160,6,'  SUBTOTAL: ','TBL BR',0,'L',0);
				$this->pdf->Cell(30,6,' '.number_format($row->Monto_Total,0,',','.').'  ','TBL BR',0,'C',0);
				$this->pdf->Ln(6);
				$this->pdf->Cell(160,6,'  DESCUENTO: ','TBL BR',0,'L',0);
				$this->pdf->Cell(30,6,' '.number_format($row->Descuento_Total,0,',','.').'  ','TBL BR',0,'C',0);
				$this->pdf->Ln(8);
	

				$this->pdf->Cell(150,5,'  TOTAL A PAGAR:   '.numtoletras($row->Monto_Total),'TBL BR',0,'L',0);
				$this->pdf->Cell(40,10,'GS. '.number_format($row->Monto_Total,0,',','.').'  ','TBL BR',0,'L',0);
				$this->pdf->Ln(5);
				$this->pdf->Cell(150,5,'  ','TBL BR',0,'L',0);
				$this->pdf->Ln(5);
				if (!empty($row->Num_Factura_Venta)) {
				$this->pdf->Cell(60,6,utf8_decode(strtoupper('Liquidación del Iva (5%):    ')).number_format($iva5,0,',','.'),'TBL BR',0,'L',0);
				$this->pdf->Cell(65,6,'  (10%):     '.number_format($iva10,0,',','.'),'TBL BR',0,'L',0);
				$this->pdf->Cell(65,6,' Total IVA:   '.number_format($row->Monto_total_Iva,0,',','.').'  ','TBL BR',0,'L',0);
				}
        		$this->pdf->Output('pdf/Venta/venta'.$id.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Venta/venta'.$id.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function ventanull($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Comprobantes Anulados';
				// Se obtienen los clientes de la base de datos
				$this->load->model("Venta_Model",'Venta');
                $list = $this->Venta->getanul();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Comprobante','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Cliente','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Monto Total ','TBL BR',0,'C','1');

						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
				foreach ($list as $key => $listc) {
				            $Monto =  number_format( $listc->Monto_Total,0,'.',',');
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($listc->Tipo_Venta == 0 ) { // voleta
							$this->pdf->Cell(60,5,'Recibo N. '. $listc->Ticket,'BL',0,'C',0);
							}elseif ($listc->Tipo_Venta == 1 ) { // factura
							$this->pdf->Cell(60,5,'Factura N. '. $listc->Num_Factura_Venta,'BL',0,'C',0);
							}

							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString($listc->Nombres, 30).' ('.$this->mi_libreria->getSubString($listc->Apellidos, 30).')','BL',0,'C',0);
							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString($Monto,30 ),'BL BB BR ',0,'C',0);
							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/ventanul/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/ventanul/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function lisventanul($id='')
	{
    // Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
				$this->load->model("Venta_Model",'Venta');
		        $this->db->select('Descuento_Total,Num_Factura_Venta,Ticket');
		        $this->db->where('idFactura_Venta', $id);
		        $query=$this->db->get('Factura_Venta');
		        $row = $query->row();
		        if (!empty($row->Num_Factura_Venta)) {
		        $nombre = 'Listado Detalle Compras Anuladas Segun Factura N. '.$row->Num_Factura_Venta;
		        }else{
		        $nombre = 'Listado Detalle Compras Anuladas Segun Recibo N. '.$row->Ticket;
		        }
		        $list = $this->Venta->detale(array('Factura_Venta_idFactura_Venta' => $id));
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Cantidad','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Nombre','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Precio ','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Descuento ','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Subtotal','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
				foreach ($list as $key => $listc) {
							$resultado = intval(preg_replace('/[^0-9]+/', '', $listc->Precio_Costo), 10); 
							$val = $resultado * $listc->can;
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(35,5,$listc->can,'BL',0,'C',0);
							$this->pdf->Cell(35,5,$this->mi_libreria->getSubString($listc->Nombre, 25),'BL',0,'C',0);
							$this->pdf->Cell(35,5,number_format( $resultado,0,',','.'),'BL ',0,'C',0);
							$this->pdf->Cell(35,5,'Pagado','BL ',0,'C',0);
							$this->pdf->Cell(40,5, number_format( $val,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
							$sum += $val;
				}
				if (empty($row->Descuento_Total)) {
						$this->pdf->Cell(115,7,'','0',0,'c');
						$this->pdf->Cell(35,7,'Monto total:',' BB ',0,'L',0);
						$this->pdf->Cell(40,7,number_format($sum,0,'.','.'),' BB ',0,'C',0);
						$this->pdf->Ln(7);
				}else{
					$total = $sum - $row->Descuento_Total;
						$this->pdf->Cell(115,7,'','0',0,'c');
						$this->pdf->Cell(35,7,'Descuento:',' BB ',0,'L',0);
						$this->pdf->Cell(40,7,number_format($row->Descuento_Total,0,'.','.'),' BB ',0,'C',0);
						$this->pdf->Ln(7);
						$this->pdf->Cell(115,7,'','0',0,'c');
						$this->pdf->Cell(35,7,'Monto total:',' BB ',0,'L',0);
						$this->pdf->Cell(40,7,number_format($total,0,'.','.'),' BB ',0,'C',0);
						$this->pdf->Ln(7);
				}


        		$this->pdf->Output('pdf/ventanul/nul'.$id.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/ventanul/nul'.$id.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function ventadevol($value='')
	{
    // Se carga la libreria fpdf
    $this->load->library('pdf');
				// Se obtienen los clientes de la base de datos
		        $this->load->model("VDevolver_Model",'Devolver');

		        $nombre = 'Listado de   Devoluciones Segun Comprobante';

		         $list = $this->Devolver->getDevolver();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Comprobante','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Cliente','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Fecha ','TBL ',0,'C','1');
		                $this->pdf->Cell(40,7,'Monto Total ','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Devolver) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
						if ($Devolver->Tipo_Venta == 0 ) { // voleta
							$this->pdf->Cell(50,5,'Recibo N. '. $Devolver->Ticket,'BL',0,'C',0);
							}elseif ($Devolver->Tipo_Venta == 1 ) { // factura
							$this->pdf->Cell(50,5,'Factura N. '. $Devolver->Num_Factura_Venta,'BL',0,'C',0);
							}
							$this->pdf->Cell(50,5,$this->mi_libreria->getSubString($Devolver->Nombres.'-'.$Devolver->Apellidos, 35),'BL',0,'C',0);
							$this->pdf->Cell(40,5,$Devolver->Fecha,'BL  ',0,'C',0);
								$this->pdf->Cell(40,5,number_format($Devolver->Monto_Total,0,'.',','),'BL BB BR ',0,'C',0);
							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/vdevolucion/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/vdevolucion/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function vdevolucion($id='')
	{
                // Se carga la libreria fpdf
                $this->load->library('pdf');
		        $this->load->model("VDevolver_Model",'Devolver');
                $query = $this->Devolver->getDevolver(array('idDevoluciones' => $id));
		        $row = $query->row();
		        if (!empty($row->Num_Factura_Venta)) {
		        $nombre = 'Listado Detalle Ventas Anuladas Segun Factura N. '.$row->Num_Factura_Venta;
		        }else{
		        $nombre = 'Listado Detalle Ventas Anuladas Segun Recibo N. '.$row->Ticket;
		        }
		        $list = $this->Devolver->detalele(array('Devoluciones_idDevoluciones' => $id));
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(16,7,'Cantidad','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Nombre','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Precio ','TBL',0,'C','1');
						$this->pdf->Cell(75,7,'Estado ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Subtotal','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$sum = 0;
						foreach ($list as $key => $listc) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(16,5,$listc->Cantidad,'BL',0,'C',0);
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString($listc->Nombre, 25),'BL',0,'C',0);
							$this->pdf->Cell(30,5,$listc->Precio,'BL ',0,'C',0);
							$this->pdf->Cell(75,5,$this->mi_libreria->getSubString($listc->Estado, 45),'BL ',0,'C',0);
							$this->pdf->Cell(30,5, number_format( $listc->Precio * $listc->Cantidad,0,',','.'),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
							$sum += $listc->Precio * $listc->Cantidad;
						}

						$this->pdf->Cell(126,7,'','0',0,'c');
						$this->pdf->Cell(35,7,'Monto total:',' BB ',0,'L',0);
						$this->pdf->Cell(30,7,number_format( $row->Monto_Total,0,'.','.'),' BB ',0,'C',0);
						$this->pdf->Ln(7);


        		$this->pdf->Output('pdf/vdevolucion/devolucion'.$id.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/vdevolucion/devolucion'.$id.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}



	public function Cobros($id='')
	{
                // Se carga la libreria fpdf
				$this->load->library('pdf');
                $this->load->model("Cobro_Model",'Cobro');

		        $list = $this->Cobro->getCobro();
		        // echo var_dump($list);
		        $nombre = 'Listado Cobros';
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(45,7,utf8_decode('Descripción'),'TBL',0,'C','1');
						$this->pdf->Cell(25,7,'Monto','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Comprobantes ','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Razon Social ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Fecha Pago','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						foreach ($list as $key => $listc) {

			           if (is_null($listc->idcce)) { ///  listcs
			               /////////////////////////////////////////////////////////////////////////
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(45,5,$this->mi_libreria->getSubString($listc->Concepto,40),'BL',0,'C',0);
							$this->pdf->Cell(25,5, number_format( $listc->Monto,0,'.',','),'BL',0,'C',0);
						    if ($listc->Tipo_Venta == 0 ) { // voleta
							$this->pdf->Cell(40,5,'Recibo N. '. $listc->Ticket,'BL',0,'C',0);
							   }elseif ($listc->Tipo_Venta == 1 ) { // factura
							$this->pdf->Cell(40,5,'Factura N. '. $listc->Num_Factura_Venta,'BL',0,'C',0);
							}
						if (!empty($listc->Razon_Social)) {
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($listc->Razon_Social,40 ),'BL ',0,'C',0);
						}else{
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($listc->Nombres,40 ),'BL ',0,'C',0);
						}

							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString($listc->Fecha.' - '.$listc->Hora,40 ),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);

		               ////////////////////////////////////////////////////////////////////
			           }
			           else  /// cuotas
			           {
			           	////////////////////////////////////////////////////////////////////
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(45,5,'Cobros de Cuotas','BL',0,'C',0);
							$this->pdf->Cell(25,5, number_format( $listc->total1,0,'.',','),'BL',0,'C',0);
							$this->pdf->Cell(40,5,'Recibo Cuota N. '.$listc->Num_Recibo,'BL',0,'C',0);
						if (!empty($listc->Razon_Social)) {
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($listc->Razon_Social. ' (' .$listc->Ruc.')',40 ),'BL ',0,'C',0);
						}else{
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($listc->Nombres. ' (' .$listc->Ruc.')',40 ),'BL ',0,'C',0);
						}
							$this->pdf->Cell(30,5,$listc->Fecha_Pago,'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
			           	////////////////////////////////////////////////////////////////////
			           }
				}


			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/Cobro/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Cobro/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function pagos($id='')
	{
                // Se carga la libreria fpdf
				$this->load->library('pdf');
		        $this->load->model("Pago_Model",'Pago');
			    $list = $this->Pago->getPago();
		        $nombre = 'Listado Pagos';
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(45,7,'Descripción','TBL',0,'C','1');
						$this->pdf->Cell(25,7,'Monto','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Comprobantes ','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Razon Social ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Fecha Pago','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						foreach ($list as $key => $listc) {

			           if (is_null($listc->idcce)) { ///  listcs
			               /////////////////////////////////////////////////////////////////////////
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(45,5,$this->mi_libreria->getSubString($listc->Concepto,40),'BL',0,'C',0);
							$this->pdf->Cell(25,5, number_format( $listc->Monto,0,'.',','),'BL',0,'C',0);
						    if ($listc->Tipo_Compra == 0 ) { // voleta
							$this->pdf->Cell(40,5,'Recibo N. '. $listc->Ticket,'BL',0,'C',0);
							   }elseif ($listc->Tipo_Compra == 1 ) { // factura
							$this->pdf->Cell(40,5,'Factura N. '. $listc->Num_factura_Compra,'BL',0,'C',0);
							}
							if (!is_null($listc->Empleado_idEmpleado)) {
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($listc->Nombres. ' (' .$listc->Apellidos.')',40 ),'BL ',0,'C',0);
							}else{
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($listc->Concepto,40 ),'BL ',0,'C',0);
							}
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString($listc->Fecha.' - '.$listc->Hora,40 ),'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);

		               ////////////////////////////////////////////////////////////////////
			           }
			           else  /// cuotas
			           {
			           	////////////////////////////////////////////////////////////////////
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(45,5,'Pagos de Cuotas','BL',0,'C',0);
							$this->pdf->Cell(25,5, number_format( $listc->total1,0,'.',','),'BL',0,'C',0);
							$this->pdf->Cell(40,5,'Recibo Cuota N. '.$listc->Num_Recibo,'BL',0,'C',0);

							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString($listc->Razon_Social,40 ),'BL ',0,'C',0);
							$this->pdf->Cell(30,5,$listc->Fecha_Pago,'BL BB BR',0,'C',0);
							$this->pdf->Ln(5);
			           	////////////////////////////////////////////////////////////////////
			           }
				}


			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/Cobro/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Cobro/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function compra($id='')
	{
		        $this->load->model("Compra_Model",'Compra');
		        $this->db->where('idFactura_Compra', $id);
		        $query=$this->db->get('Factura_Compra');
		        $row = $query->row();
		        $lista = $this->Compra->detale(array('Factura_Compra_idFactura_Compra' => $id));
		        if (empty($row->Num_factura_Compra)) {
		        	 $nombre = strtoupper('Boleta  DE PAGO');

		        	 $this->boleta_pago($nombre,$id,$row,$lista);
		        	 // echo var_dump($lista);
		        }else{
		        	 $nombre = strtoupper('Factura DE PAGO');
		        	 // echo var_dump($lista);

		        	 $this->factura_compra($nombre,$id, $row ,$lista);
		        }
	}


	public function cobro($id='')
	{
		        $this->db->select('*');
		        $this->db->join('Cuenta_Corriente_Cliente	  cce', 'Caja_Cobros.Cuenta_Corriente_Cliente_idCuenta_Corriente_Cliente = cce.idCuenta_Corriente_Cliente', 'left');
		        $this->db->join('Factura_Venta', 'Caja_Cobros.Factura_Venta_idFactura_Venta = Factura_Venta.idFactura_Venta  ', 'left');
		        $this->db->join('Cliente', 'Factura_Venta.Cliente_idCliente = Cliente.idCliente', 'left');
		        $this->db->where('idFactura_Venta', $id);
		        $this->db->or_where('idCuenta_Corriente_Cliente	',  $id);
		        $query1=$this->db->get('Caja_Cobros');
		        $row = $query1->row();
		        if ($row->Num_Factura_Venta<1) {
		        	 $nombre = strtoupper('Boleta  ');
		        	 $this->boletas($nombre,$id,$row);
		        }else{
		        	 $nombre = strtoupper('Factura ');
		        	 $this->factura($nombre,$id, $row );
		        }

	}

	public function pago($id='')
	{           
		        $this->db->select('*');
                $this->db->join('Cuenta_Corriente_Empresa  cce', 'Caja_Pagos.Cuenta_Corriente_Empresa_idCuenta_Corriente_Empresa = cce.idCuenta_Corriente_Empresa', 'left');
		        $this->db->join('Factura_Compra', 'Caja_Pagos.Factura_Compra_idFactura_Compra = Factura_Compra.idFactura_Compra  ', 'left');
		        $this->db->where('idFactura_Compra', $id);
		        $this->db->or_where('idCuenta_Corriente_Empresa',  $id);
		        $query1=$this->db->get('Caja_Pagos');
		        $row = $query1->row();
		        if ($row->Num_factura_Compra<1) {
		        	 $nombre = strtoupper('Boleta  DE PAGO');
		        	 // echo var_dump($row);
		        	 $this->boleta_pago($nombre,$id,$row);
		        }else{
		        	 $nombre = strtoupper('Factura DE PAGO');
		        	 $this->factura_compra($nombre,$id, $row );
		        }
	}




	public function factura_compra($nombre,$id,$alias,$lista='')
	{
		// echo var_dump($alias);

			/*
			* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
			* heredó todos las variables y métodos de fpdf
			*/
				$this->load->library('factura');
				$this->pdf = new Factura();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				// Define el alias para el número de página que se imprimirá en el pie
				$this->pdf->AliasNbPages();
				$this->headfacturacompra($nombre,$alias);
				$this->pdf->SetFillColor(250,250,250);
				$this->pdf->SetFont('Arial', 'b', 8);
				$this->pdf->Cell(25,10,'FECHA:   '.date("d",strtotime($alias->Fecha_expedicion)),'',0,'L','1');
				$this->pdf->Cell(50,10,'DE   '.nombremes(date("m",strtotime($alias->Fecha_expedicion))),'',0,'L','1');
				$this->pdf->Cell(25,10,'DEL 20'.date("y",strtotime($alias->Fecha_expedicion)),'',0,'L','1');
			if ($alias->Contado_Credito == 1) {
				$this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE PAGO'),'',0,'L','1');
				$this->pdf->Cell(25,10,'CONTADO:  X','',0,'C','1');
				$this->pdf->Cell(25,10,utf8_decode('CRÉDITO: '),'',0,'C','1');
			}else{
					$this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE PAGO'),'',0,'L','1');
					$this->pdf->Cell(25,10,'CONTADO:','',0,'C','1');
					$this->pdf->Cell(25,10,utf8_decode('CRÉDITO X'),'',0,'C','1');
			}


			$this->pdf->Ln(7);
		    $this->db->select('*');
		    $query = $this->db->get('Empresa');
		    $row = $query->row();
			$this->pdf->Cell(42,10,utf8_decode('NOMBRE O RAZÓN SOCIAL:'),'',0,'L','1');
			$this->pdf->Cell(98,10,utf8_decode($row->Nombre),'',0,'L','1');
			$this->pdf->Cell(50,10,'R.U.C. : '.$row->R_U_C,'',0,'L','1');
			$this->pdf->Ln(7);

			$this->pdf->Cell(60,10,utf8_decode('NOTA DE REMISIÓN Nº:'),'',0,'L','1');
			$this->pdf->Cell(40,10,'TEL.: '.$row->Telefono,'',0,'L','1');
			$this->pdf->Cell(90,10,'DOMICILIO:'.$row->Direccion,'',0,'L','1');
			$this->pdf->Ln(10);


			/* Se define el titulo, márgenes izquierdo, derecho y
			* el color de relleno predeterminado*/
			$this->pdf->SetFillColor(250,250,250);

			// Se define el formato de fuente: Arial, negritas, tamaño 9
			/*
			* TITULOS DE COLUMNAS
			*
			* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
			*/
		    $this->pdf->SetFont('Arial', 'b', 8);
			$this->pdf->Cell(14,10,'Cantidad','TBL',0,'C','1');
			$this->pdf->Cell(75,10,utf8_decode('Descripción de Mercadería y/o Servicios'),'TBL',0,'C','1');
			$this->pdf->Cell(21,10,'Precio Unitario','TBL',0,'C','1');
			$this->pdf->Cell(80,5,'VALOR DE COMPRAS','TBL BR',0,'C','1');
			$this->pdf->Ln(5);
			$this->pdf->Cell(110,5,'',0,'C','1');
			$this->pdf->Cell(20,5,'Exentas','TBL BR',0,'C','1');
			$this->pdf->Cell(30,5,'5%','TBL BR',0,'C','1');
			$this->pdf->Cell(30,5,'10%','TBL BR',0,'C','1');
			$this->pdf->Ln(5);
				if (is_array($lista)) {
				$i = 0;
				$iva5=0;
				$iva10=0;

					foreach ($lista as $key => $value) {
						$Precio_Costo = str_replace($this->config->item('caracteres'),"",$value->Precio_Costo);
		    			$this->pdf->SetFont('Arial', 'b', 7);
						$this->pdf->Cell(14,6,$value->can,'TBL BR',0,'C',0);
						$this->pdf->Cell(75,6,$value->Nombre,'TBL BR',0,'L',0);
						switch ($value->Iva) {
							case '0':
									$this->pdf->Cell(21,6,number_format($Precio_Costo,0,',','.'),'TBL BR',0,'C',0);
									$this->pdf->Cell(20,6,number_format($Precio_Costo*$value->can,0,',','.').'  ','TBL BR',0,'C',0);
									$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
					               $this->pdf->Cell(30,6,'','TBL BR',0,'C',0);

								break;
							case '5':
									$this->pdf->Cell(21,6,number_format($Precio_Costo,0,',','.'),'TBL BR',0,'C',0);
									$this->pdf->Cell(20,6,'','TBL BR',0,'C',0);
									$this->pdf->Cell(30,6,number_format($Precio_Costo*$value->can,0,',','.').'  ','TBL BR',0,'C',0);
									$iva5 +=$Precio_Costo/21;
					                $this->pdf->Cell(30,6,'','TBL BR',0,'C',0);

								break;
							case '10':
									$this->pdf->Cell(21,6,number_format($Precio_Costo,0,',','.'),'TBL BR',0,'C',0);
									$this->pdf->Cell(20,6,'','TBL BR',0,'C',0);
									$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
									$iva10 +=$Precio_Costo/11;
					                $this->pdf->Cell(30,6,number_format($Precio_Costo*$value->can,0,',','.').'  ','TBL BR',0,'C',0);

								break;							
						}
					$this->pdf->Ln(6);
					$i++;  
					}
					if ($i < 10) {
						for ($j=$i; $j <= 10; $j++) { 
						$this->pdf->Cell(14,6,'','TBL BR',0,'C',0);
						$this->pdf->Cell(75,6,'','TBL BR',0,'L',0);
						$this->pdf->Cell(21,6,'','TBL BR',0,'C',0);
						$this->pdf->Cell(20,6,'','TBL BR',0,'C',0);
						$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
						$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
						$this->pdf->Ln(6);
						}
					}
			    	$this->pdf->SetFillColor(0, 27, 0, 1);
					$this->pdf->SetDrawColor(0, 27, 0, 1);
					$this->pdf->SetTextColor(0, 27, 0, 1);
					$this->pdf->Cell(160,6,'  SUBTOTAL: ','TBL BR',0,'L',0);
					$this->pdf->Cell(30,6,' '.number_format($alias->Monto_Total,0,',','.').'  ','TBL BR',0,'C',0);
					$this->pdf->Ln(6);
					$this->pdf->Cell(160,6,'  DESCUENTO: ','TBL BR',0,'L',0);
					if (!empty($alias->Descuento_Total)) {
					$this->pdf->Cell(30,6,' '.number_format($alias->Descuento_Total,0,',','.').'  ','TBL BR',0,'C',0);
						
					}else{
					$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);

					}
					$this->pdf->Ln(8);
					$this->pdf->Cell(150,5,'  TOTAL A PAGAR:   '.numtoletras($alias->Monto_Total ),'TBL BR',0,'L',0);
					$this->pdf->Cell(40,10,'GS. '.number_format($alias->Monto_Total ,0,',','.').'  ','TBL BR',0,'L',0);
					$this->pdf->Ln(5);
					$this->pdf->Cell(150,5,'  ','TBL BR',0,'L',0);
					$this->pdf->Ln(5);

					$this->pdf->Cell(60,6,utf8_decode(strtoupper('Liquidación del Iva (5%):    ')).number_format($iva5,0,',','.'),'TBL BR',0,'L',0);
					$this->pdf->Cell(65,6,'  (10%):     '.number_format($iva10,0,',','.'),'TBL BR',0,'L',0);
					$this->pdf->Cell(65,6,' Total IVA:   '.number_format($alias->Monto_Total_Iva,0,',','.').'  ','TBL BR',0,'L',0);			
					

				} else {
	    			$this->pdf->SetFont('Arial', 'b', 7);
					$this->pdf->Cell(14,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(75,6,$alias->Concepto,'TBL BR',0,'L',0);
					$this->pdf->Cell(21,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(20,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,number_format($alias->Monto_Total,0,',','.').'  ','TBL BR',0,'C',0);
					$this->pdf->Ln(6);
					for ($i=0; $i <10; $i++) { 
					$this->pdf->Cell(14,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(75,6,'','TBL BR',0,'L',0);
					$this->pdf->Cell(21,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(20,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
					$this->pdf->Ln(6);
					}
	    	$this->pdf->SetFillColor(0, 27, 0, 1);
			$this->pdf->SetDrawColor(0, 27, 0, 1);
			$this->pdf->SetTextColor(0, 27, 0, 1);
			$this->pdf->Cell(160,6,'  SUBTOTAL: ','TBL BR',0,'L',0);
			$this->pdf->Cell(30,6,' '.number_format($alias->Monto_Total,0,',','.').'  ','TBL BR',0,'C',0);
			$this->pdf->Ln(6);
			$this->pdf->Cell(160,6,'  DESCUENTO: ','TBL BR',0,'L',0);
			$this->pdf->Cell(30,6,' '.number_format($alias->Descuento_Total,0,',','.').'  ','TBL BR',0,'C',0);
			$this->pdf->Ln(8);
			$this->pdf->Cell(150,5,'  TOTAL A PAGAR:   '.numtoletras($alias->Monto_Total),'TBL BR',0,'L',0);
			$this->pdf->Cell(40,10,'GS. '.number_format($alias->Monto_Total,0,',','.').'  ','TBL BR',0,'L',0);
			$this->pdf->Ln(5);
			$this->pdf->Cell(150,5,'  ','TBL BR',0,'L',0);
			$this->pdf->Ln(5);

			$this->pdf->Cell(60,6,utf8_decode(strtoupper('Liquidación del Iva (5%):    ')),'TBL BR',0,'L',0);
			$this->pdf->Cell(65,6,'  (10%):     '.number_format($alias->Monto_Total_Iva,0,',','.').'  ','TBL BR',0,'L',0);
			$this->pdf->Cell(65,6,' Total IVA:   '.number_format($alias->Monto_Total_Iva,0,',','.').'  ','TBL BR',0,'L',0);					
		}

							$this->pdf->Output('pdf.pdf','F');  //save pdf
							$this->pdf->Output('pdf.pdf', 'I'); // show pdf
	}


	public function boleta_pago($nombre,$id, $alias,$lista='')
	{
		/*
			* Se crea un objeto de la clase Pdf, recuerda que la clase Pdfcompra
			* heredó todos las variables y métodos de fpdf
			*/
			$this->load->library('factura');
			$this->pdf = new Factura();
			// $this->pdf->Header($nombre);
			// Agregamos una página
			$this->pdf->AddPage();
			// Define el alias para el número de página que se imprimirá en el pie
			$this->pdf->AliasNbPages();
		     $this->headfacturacompra($nombre, $id, $alias->Ticket );
			$this->pdf->SetFillColor(250,250,250);
			if (!empty($alias->idCuenta_Corriente_Empresa)) {
				$this->pdf->SetFont('Arial', 'b', 8);
				$this->pdf->Cell(25,10,'FECHA:   '.date("d",strtotime($alias->Fecha)),'',0,'L','1');
				$this->pdf->Cell(50,10,'DE   '.nombremes(date("m",strtotime($alias->Fecha))),'',0,'L','1');
				$this->pdf->Cell(25,10,'DEL 20'.date("y",strtotime($alias->Fecha)),'',0,'L','1');
				$this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE PAGO'),'',0,'L','1');
				$this->pdf->Cell(25,10,'CONTADO:  X','',0,'C','1');
				$this->pdf->Cell(25,10,utf8_decode('CRÉDITO: '),'',0,'C','1');
			} else {
				$this->pdf->SetFont('Arial', 'b', 8);
				$this->pdf->Cell(25,10,'FECHA:   '.date("d",strtotime($alias->Fecha_expedicion)),'',0,'L','1');
				$this->pdf->Cell(50,10,'DE   '.nombremes(date("m",strtotime($alias->Fecha_expedicion))),'',0,'L','1');
				$this->pdf->Cell(25,10,'DEL 20'.date("y",strtotime($alias->Fecha_expedicion)),'',0,'L','1');
			if ($alias->Contado_Credito == 1) {
				$this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE PAGO'),'',0,'L','1');
				$this->pdf->Cell(25,10,'CONTADO:  X','',0,'C','1');
				$this->pdf->Cell(25,10,utf8_decode('CRÉDITO: '),'',0,'C','1');
			}else{
					$this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE PAGO'),'',0,'L','1');
					$this->pdf->Cell(25,10,'CONTADO:','',0,'C','1');
					$this->pdf->Cell(25,10,utf8_decode('CRÉDITO X'),'',0,'C','1');
			}

			}


			$this->pdf->Ln(7);
		    $this->db->select('*');
		    $query = $this->db->get('Empresa');
		    $row = $query->row();
			$this->pdf->Cell(42,10,utf8_decode('NOMBRE O RAZÓN SOCIAL:'),'',0,'L','1');
			$this->pdf->Cell(98,10,utf8_decode($row->Nombre),'',0,'L','1');
			$this->pdf->Cell(50,10,'R.U.C. : '.$row->R_U_C,'',0,'L','1');
			$this->pdf->Ln(7);

			$this->pdf->Cell(60,10,utf8_decode('NOTA DE REMISIÓN Nº:'),'',0,'L','1');
			$this->pdf->Cell(40,10,'TEL.: '.$row->Telefono,'',0,'L','1');
			$this->pdf->Cell(90,10,'DOMICILIO:'.$row->Direccion,'',0,'L','1');
			$this->pdf->Ln(10);
			/* Se define el titulo, márgenes izquierdo, derecho y
			* el color de relleno predeterminado*/
			$this->pdf->SetFillColor(250,250,250);

			// Se define el formato de fuente: Arial, negritas, tamaño 9
			/*
			* TITULOS DE COLUMNAS
			*
			* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
			*/
			$this->pdf->Cell(25,7,'Cantidad','TBL',0,'C','1');
			$this->pdf->Cell(80,7,utf8_decode('Descripción de Mercadería y/o Servicios'),'TBL',0,'C','1');
			$this->pdf->Cell(25,7,'Inpuesto','TBL',0,'C','1');
			$this->pdf->Cell(30,7,'Precio','TBL',0,'C','1');
			$this->pdf->Cell(30,7,'Subtotal','TBL BR',0,'C','1');
			$this->pdf->Ln(7);
				// se imprime el numero actual y despues se incrementa el valor de $x en uno
				// Se imprimen los datos de cada cliente
				if (is_array($lista)) {
					$i = 0 ;
					foreach ($lista as $key => $value) {
						$Precio_Costo = str_replace($this->config->item('caracteres'),"",$value->Precio_Costo);
						$this->pdf->Cell(25,6,$value->can,'TBL BR',0,'C',0);
						$this->pdf->Cell(80,6,$value->Nombre,'TBL BR',0,'L',0);
						$this->pdf->Cell(25,6,''.' ','TBL BR',0,'C',0);
						$this->pdf->Cell(30,6,number_format($Precio_Costo,0,',','.').'  ','TBL BR',0,'C',0);
						$this->pdf->Cell(30,6,number_format($Precio_Costo*$value->can,0,',','.').'  ','TBL BR',0,'C',0);
						//Se agrega un salto de linea
						$this->pdf->Ln(6);
						$i++;
					}
					if ($i < 10) {
						for ($j=$i; $j <10 ; $j++) { 
						$this->pdf->Cell(25,6,'','TBL BR',0,'C',0);
						$this->pdf->Cell(80,6,'','TBL BR',0,'L',0);
						$this->pdf->Cell(25,6,''.' ','TBL BR',0,'C',0);
						$this->pdf->Cell(30,6,'  ','TBL BR',0,'C',0);
						$this->pdf->Cell(30,6,'  ','TBL BR',0,'C',0);
						$this->pdf->Ln(6);
						}
					}
						$this->pdf->Ln(7);
						$this->pdf->SetFillColor(0, 27, 0, 1);
						$this->pdf->SetDrawColor(0, 27, 0, 1);
						$this->pdf->SetTextColor(0, 27, 0, 1);
						$this->pdf->Cell(160,5,'  DESCUENTO: ','TBL BR',0,'L',0);
						if (!empty($alias->Descuento_Total)) {
						$this->pdf->Cell(30,5,' '.number_format($alias->Descuento_Total,0,',','.').'  ','TBL BR',0,'C',0);	
						}else{
						$this->pdf->Cell(30,5,' '.$alias->Descuento_Total,'TBL BR',0,'C',0);
						}

						$this->pdf->Ln(5);
						$this->pdf->Cell(160,5,'  Total A Pagar:     '.'  ','TBL BR',0,'L',0);
						$this->pdf->Cell(30,5,' '.number_format($alias->Monto_Total-$alias->Descuento_Total,0,',','.').'  ','TBL BR',0,'C',0);
						$this->pdf->Ln(8);
				}else{
					$this->pdf->Cell(25,6,'1','TBL BR',0,'C',0);
					$mmonto = intval(preg_replace('/[^0-9]+/', '', $alias->Monto), 10); 
					if (!empty($alias->idCuenta_Corriente_Empresa)) {

					if ($alias->Importe > $mmonto) {
					   $Monto = $mmonto;
					} else {
					  $Monto = $alias->Importe;
					}
					$this->pdf->Cell(80,6,utf8_decode('Pago de Cuota Nº ').$alias->Num_Cuotas,'TBL BR',0,'L',0);
					$this->pdf->Cell(25,6,''.' ','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,number_format($Monto,0,',','.').'  ','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,number_format($Monto,0,',','.').'  ','TBL BR',0,'C',0);
					$this->pdf->Ln(6);
					} else {
					$this->pdf->Cell(80,6,$alias->Concepto,'TBL BR',0,'L',0);
					$this->pdf->Cell(25,6,''.' ','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,number_format($mmonto,0,',','.').'  ','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,number_format($mmonto,0,',','.').'  ','TBL BR',0,'C',0);
					$this->pdf->Ln(6);					
					}

					for ($i=0; $i <10 ; $i++) { 
					$this->pdf->Cell(25,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(80,6,'','TBL BR',0,'L',0);
					$this->pdf->Cell(25,6,''.' ','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,'  ','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,'  ','TBL BR',0,'C',0);
					//Se agrega un salto de linea
					$this->pdf->Ln(6);
					}

					$this->pdf->Ln(7);
					$this->pdf->SetFillColor(0, 27, 0, 1);
					$this->pdf->SetDrawColor(0, 27, 0, 1);
					$this->pdf->SetTextColor(0, 27, 0, 1);
					$this->pdf->Cell(160,5,'  DESCUENTO: ','TBL BR',0,'L',0);
					if (!empty($alias->Descuento_Total)) {
					$this->pdf->Cell(30,5,' '.number_format($alias->Descuento_Total,0,',','.').'  ','TBL BR',0,'C',0);	
					}else{
						$this->pdf->Cell(30,5,'','TBL BR',0,'C',0);
					}
					
					$this->pdf->Ln(5);
					$this->pdf->Cell(160,5,'  Total A Pagar:     '.'  ','TBL BR',0,'L',0);
					$this->pdf->Cell(30,5,' '.number_format($mmonto,0,',','.').'  ','TBL BR',0,'C',0);
							$this->pdf->Ln(8);
				}




							$this->pdf->Output('pdf.pdf','F');  //save pdf
							$this->pdf->Output('pdf.pdf', 'I'); // show pdf
	}

	public function Bancos($id='')
	{
                // Se carga la libreria fpdf
				$this->load->library('pdf');
			    $list = $this->db->get('Gestor_Bancos')->result();
		        $nombre = 'Listado de Bancos';
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Nombre Bancario','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Numero Bancario','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Monto','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						foreach ($list as $key => $listc) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString($listc->Nombre,40),'BL',0,'C',0);
							$this->pdf->Cell(60,5,$listc->Numero,'BL',0,'C',0);
							if (!empty($listc->MontoActivo)) {
							$this->pdf->Cell(60,5,number_format($listc->MontoActivo,0,'.',',').' ','BL BB BR',0,'C',0);
								# code...
							}else{
							$this->pdf->Cell(60,5,utf8_decode('0 '),'BL BB BR',0,'C',0);

							}
							$this->pdf->Ln(5);
				}

        		$this->pdf->Output('pdf/Banco/Banco.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Banco/Banco.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function movimiwnto($id='')
	{
                // Se carga la libreria fpdf
				$this->load->library('pdf');
 		        $this->load->model('Banco_Model', 'Banco');

			    $list = 	$this->Banco->detale($id);
		        $nombre = 'Listado de Movimiento';
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Cheque','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Plan de Cuenta','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Fecha Expedicion','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Entrada Salida','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Importe','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
						$entrada = 0;
						$salida = 0;
						foreach ($list as $key => $value) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							if ($value->NumeroCheque > 0) {
							$NumeroCheque = 'Cheque';
							}else{
							$NumeroCheque  = 'Efectivo';
							}

							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString($NumeroCheque,40),'BL',0,'C',0);
							if ($value->PlandeCuenta_idPlandeCuenta > 0) {
							$sub = $value->Balance_General;
							}else{
							
							$sub = $value->ConceptoSalida;
							}
							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString($sub,40),'BL',0,'C',0);
							$this->pdf->Cell(30,5,$value->FechaExpedicion,'BL',0,'C',0);
							$this->pdf->Cell(30,5,$value->Entrada_Salida,'BL',0,'C',0);

							$this->pdf->Cell(30,5,number_format($value->Importe,0,'.',','),'BL  BB BR',0,'C',0);

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

        		$this->pdf->Output('pdf/Banco/'.$id.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Banco/'.$id.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function mbanco($id='')
	{
                // Se carga la libreria fpdf
				$this->load->library('pdf');
				$this->db->select('*');
				$this->db->join('Gestor_Bancos', 'Gestor_Bancos.idGestor_Bancos = Movimientos.Gestor_Bancos_idGestor_Bancos', 'inner');
				$this->db->join('PlandeCuenta', 'PlandeCuenta.idPlandeCuenta = Movimientos.PlandeCuenta_idPlandeCuenta', 'left');
				$query = $this->db->get('Movimientos');
				$list =  $query->result();
				$nombre = 'Listado de Movimiento Banco';
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/


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
						foreach ($list as  $value) {
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
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}	
	public function aciento($id='')
	{
                // Se carga la libreria fpdf
					$this->load->library('pdf');
					$this->load->model("Acientos_Model",'Acientos');
					$list = $this->Acientos->getAciento();
					$nombre = 'Listado de Aciento Diario';
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/


				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(20,7,'Fecha','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Deuda Cuenta','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Haber Cuenta ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Debe','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Haber','TBLBR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;	

						foreach ($list as $Acientos) 
						{

							if (!is_null($Acientos->Balance_General)) {
										$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
										$this->pdf->Cell(20,5,$Acientos->Fecha,'BL',0,'C',0);
								    	if (!is_null($Acientos->DebeDetalle)) {
											$this->pdf->Cell(50,5,utf8_decode($Acientos->Balance_General).' '.$Acientos->DebeDetalle,'BL',0,'C',0);
								    	}else
								    	{
											$this->pdf->Cell(50,5,'','BL',0,'C',0);
								    	}

										if (!is_null($Acientos->HaberDetalle)) {
										$this->pdf->Cell(50,5,utf8_decode($Acientos->Balance_General).' '.$Acientos->HaberDetalle,'BL',0,'C',0);

									   }else
									   {
										$this->pdf->Cell(50,5,'','BL',0,'C',0);

									   }

										if (!is_null($Acientos->Debe)) {
										$this->pdf->Cell(30,5,number_format($Acientos->Debe,0,'.',','),'BL',0,'C',0);

										}else
										{
										$this->pdf->Cell(30,5,'','BL',0,'C',0);

										}

									    if (!is_null($Acientos->Haber)) {
										$this->pdf->Cell(30,5,number_format($Acientos->Haber,0,'.',','),'BL  BB BR',0,'C',0);

										}else{
										$this->pdf->Cell(30,5,'','BL  BB BR',0,'C',0);

										}
											$this->pdf->Ln(5);
							}else{
									$this->pdf->Cell(10,7,$x++,'BL',0,'C',0);
									$this->pdf->Cell(20,7,'','BL',0,'C',0);

									$this->pdf->Cell(160,7,$this->mi_libreria->remplse($Acientos->DebeDetalle),'BL BB BR',0,'L',0);
									$this->pdf->Ln(7);
							}

						}

				 $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/Contabilidad/aciento'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Contabilidad/aciento'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}	

	public function baciento($fecha='',$caja='',$forma='')
	{
				if (!empty($caja)) {
					$cajas = 'Segun Caja Nº '.$caja;
				}else{
					$cajas = '';
				}
                $nombre = 'Listado de Aciento  '.$fecha.' '.utf8_decode($cajas);
				if (!empty($fecha)) {
					$this->load->library('pdf');
					$this->load->model("Acientos_Model",'Acientos');
					$list = $this->Acientos->getAcientosear($fecha,$caja,$forma );

				}
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/


				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(20,7,'Fecha','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Deuda Cuenta','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Haber Cuenta ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Debe','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Haber','TBLBR',0,'C','1');
						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;	

						foreach ($list as $Acientos) 
						{

							if (!is_null($Acientos->Balance_General)) {
										$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
										$this->pdf->Cell(20,5,$Acientos->Fecha,'BL',0,'C',0);
								    	if (!is_null($Acientos->DebeDetalle)) {
											$this->pdf->Cell(50,5,utf8_decode($Acientos->Balance_General).' '.$Acientos->DebeDetalle,'BL',0,'C',0);
								    	}else
								    	{
											$this->pdf->Cell(50,5,'','BL',0,'C',0);
								    	}

										if (!is_null($Acientos->HaberDetalle)) {
										$this->pdf->Cell(50,5,utf8_decode($Acientos->Balance_General).' '.$Acientos->HaberDetalle,'BL',0,'C',0);

									   }else
									   {
										$this->pdf->Cell(50,5,'','BL',0,'C',0);

									   }

										if (!is_null($Acientos->Debe)) {
										$this->pdf->Cell(30,5,number_format($Acientos->Debe,0,'.',','),'BL',0,'C',0);

										}else
										{
										$this->pdf->Cell(30,5,'','BL',0,'C',0);

										}

									    if (!is_null($Acientos->Haber)) {
										$this->pdf->Cell(30,5,number_format($Acientos->Haber,0,'.',','),'BL  BB BR',0,'C',0);

										}else{
										$this->pdf->Cell(30,5,'','BL  BB BR',0,'C',0);

										}
											$this->pdf->Ln(5);
							}else{
									$this->pdf->Cell(10,7,$x++,'BL',0,'C',0);
									$this->pdf->Cell(20,7,'','BL',0,'C',0);

									$this->pdf->Cell(160,7,$this->mi_libreria->remplse($Acientos->DebeDetalle),'BL BB BR',0,'L',0);
									$this->pdf->Ln(7);
							}

						}

				 $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/Contabilidad/aciento'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Contabilidad/aciento'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}	

	public function libromayor($fecha='',$caja='',$forma='')
	{
				if (!empty($caja)) {
					$cajas = 'Segun Caja Nº '.$caja;
				}else{
					$cajas = '';
				}
                $nombre = 'Libro Mayor  '.$fecha.' '.utf8_decode($cajas);
				if (!empty($fecha)) {
					$this->load->library('pdf');
					$this->load->model("Acientos_Model",'Acientos');
					$list = $this->Acientos->load_mayor($fecha,$caja,$forma );

				}
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9

				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

						foreach ($list as $value) 
						{
							$haber = 0;			$x = 1;	
							$debe = 0;
							$total = 0;
				            $this->pdf->SetFont('Arial', 'B', 10);
					        $this->pdf->SetFillColor(200,200,200);
							$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
							$this->pdf->Cell(70,7,utf8_decode(str_replace('', '_', $value->Balance_General)),'TBL',0,'C','1');
							$this->pdf->Cell(55,7,'Debe','TBL',0,'C','1');
							$this->pdf->Cell(55,7,'Haber','TBLBR',0,'C','1');
							$this->pdf->Ln(7);

							  $Acientos = loadmayor($value->PlandeCuenta_idPlandeCuenta,$value->Fecha);
							  foreach ($Acientos as $key => $val) {
				               $this->pdf->SetFont('Arial', 'B', 9);
								$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
								$this->pdf->Cell(70,5,'','BL',0,'C',0);
								if (!is_null($val->Debe)) {
								$this->pdf->Cell(15,5,'['.$val->Acientos_idAcientos.']','BL','','C',0);
								$this->pdf->Cell(40,5,number_format($val->Debe,0,'.',','),'B  ',0,'C',0);
								$this->pdf->Cell(55,5,'','BL  BB BR',0,'C',0);
								$this->pdf->Ln(5);

								}
								if (!is_null($val->Haber)) {
								$this->pdf->Cell(55,5,'','BL',0,'C',0);
								$this->pdf->Cell(40,5,number_format($val->Haber,0,'.',','),'BL',0,'C',0);						
								$this->pdf->Cell(15,5,'['.$val->Acientos_idAcientos.']','B   BB BR','','C',0);

								$this->pdf->Ln(5);

								}
								$haber          +=$val->Haber;
								$debe           +=$val->Debe;
			  	
							  }
								$this->pdf->Cell(10,7,'','BL',0,'C',0);
								$this->pdf->Cell(70,7,'','BL',0,'C',0);								
								$this->pdf->Cell(55,7,number_format($debe,0,'.',','),'BL',0,'C',0);
								$this->pdf->Cell(55,7,number_format($haber,0,'.',','),'BL BB BR',0,'C',0);
								$this->pdf->Ln(7);
								if ($debe > $haber  ) {
								$this->pdf->Cell(10,7,'','BL',0,'C',0);
								$this->pdf->Cell(70,7,'Saldo Deudor ','BL',0,'C',0);								
								$this->pdf->Cell(55,7,' '.number_format($debe  - $haber,0,'.',','),'BL',0,'C',0);
								$this->pdf->Cell(55,7,'','BL BB BR',0,'L',0);
								$this->pdf->Ln(7);
								}else{
								$this->pdf->Cell(10,7,'','BL',0,'C',0);
								$this->pdf->Cell(70,7,'Saldo Acreedor ','BL',0,'C',0);								
								$this->pdf->Cell(55,7,'','BL',0,'L',0);
								$this->pdf->Cell(55,7,' '.number_format($haber  - $debe,0,'.',','),'BL BB BR',0,'C',0);
								$this->pdf->Ln(7);
								}


						}

				 $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/Contabilidad/libro'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Contabilidad/libro'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen resultado de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}	


	public function balance($ano='',$mes='')
	{
                $nombre = 'Balance '.$mes.' '.utf8_decode($ano);
					$this->load->library('pdf');
					$this->load->model("Acientos_Model",'Acientos');
					$list = $this->Acientos->loadbalance($mes,$ano);
					$recordsFiltered = $this->Acientos->count_filtro_balance($mes,$ano);;
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9

				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/
			
				$this->pdf->SetFont('Arial', 'B', 10);
				$this->pdf->SetFillColor(200,200,200);
				$this->pdf->Cell(35,7,'','TBL',0,'C','1');
				$this->pdf->Cell(70,7,'Plan de Cuenta','TBL',0,'C','1');
				$this->pdf->Cell(43,7,'Debe','TBL',0,'C','1');
				$this->pdf->Cell(43,7,'Haber','TBLBR',0,'C','1');
				$this->pdf->Ln(7);
				
            $hb = 0;
			$db = 0;
			$no = 0;
			foreach ($list as $key => $Acientos) {
				$this->pdf->SetFont('Arial', 'B', 9);
				$this->pdf->Cell(35,6,$Acientos->Nombre,'BL',0,'C',0);
				$this->pdf->Cell(70,6,utf8_decode($Acientos->Balance_General),'BL',0,'C',0);
				if (!is_null($Acientos->debe) &&  !is_null($Acientos->haber)) {
					if ($Acientos->debe > $Acientos->haber) {
						$row1 = $Acientos->debe - $Acientos->haber;
						$this->pdf->Cell(43,6,number_format($row1,0,'.',','),'BL  ',0,'C',0);
						$this->pdf->Cell(43,6,'','BL  BB BR',0,'C',0);
						$db          +=$Acientos->debe;
						$hb           +=$Acientos->haber;
					}else {
                        $row2 =  $Acientos->haber - $Acientos->debe;
						$this->pdf->Cell(43,6,'','BL',0,'C',0);
						$this->pdf->Cell(43,6,number_format($row2,0,'.',','),'BL BB BR',0,'C',0);
						$db          +=$Acientos->debe;
						$hb           +=$Acientos->haber;
					}
				}elseif (is_null($Acientos->debe) &&  !is_null($Acientos->haber)) {
						$this->pdf->Cell(43,6,'','BL',0,'C',0);
						$this->pdf->Cell(43,6,number_format($Acientos->haber,0,'.',','),'BL BB BR',0,'C',0);
						$db          +=$Acientos->debe;
						$hb           +=$Acientos->haber;
				}elseif (!is_null($Acientos->debe) &&  is_null($Acientos->haber)) {
						$this->pdf->Cell(43,6,number_format($Acientos->debe,0,'.',','),'BL  ',0,'C',0);
						$this->pdf->Cell(43,6,'','BL  BB BR',0,'C',0);
						$db          +=$Acientos->debe;
						$hb           +=$Acientos->haber;
				}
					$this->pdf->Ln(6);
			$no++;
			if ($no == $recordsFiltered) {

								$this->pdf->SetFont('Arial', 'B', 10);
								$this->pdf->Cell(35,7,'','BL',0,'C',0);
								$this->pdf->Cell(70,7,'Resultados','B',0,'C',0);
								$this->pdf->Cell(43,7,number_format($db,0,'.',','),'B  ',0,'C',0);
								$this->pdf->Cell(43,7,number_format($hb,0,'.',','),'BB BR',0,'C',0);
							   $this->pdf->Ln(7);

			}
			}

			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/Contabilidad/balance'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Contabilidad/balance'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen resultado de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}	

	public function PlandeCuenta($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Plan de Cuenta';
				// Se obtienen los clientes de la base de datos
					$this->db->join('SubPlanCuenta', 'pc.Control = SubPlanCuenta.idSubPlanCuenta', 'left');

                $list = $this->db->get('PlandeCuenta pc')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Codigo','TBL',0,'C','1');
						$this->pdf->Cell(90,7,'Nombre de la Cuenta ','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Categoria ','TBL BR',0,'C','1');

						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $listc) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);


							$this->pdf->Cell(40,5,$listc->Codificacion,'BL',0,'C',0);

							$this->pdf->Cell(90,5,$this->mi_libreria->getSubString(utf8_decode($listc->Balance_General), 50),'BL',0,'L',0);
							$this->pdf->Cell(50,5,$this->mi_libreria->getSubString($listc->Nombre,40 ),'BL BB BR ',0,'C',0);
							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/plan/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/plan/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function cambio($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Cambios de Moneda';
				// Se obtienen los clientes de la base de datos
				$this->db->join('Cambios', 'Moneda.Cambios_idCambios = Cambios.idCambios', 'INNER');
                $list = $this->db->get('Moneda')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Moneda','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Cambio ','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Estado ','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Fecha ','TBL BR',0,'C','1');

						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Cambio) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);


							$this->pdf->Cell(60,5,$Cambio->Moneda.'  '. utf8_decode($Cambio->Nombre),'BL',0,'C',0);

							$this->pdf->Cell(40,5,$Cambio->Compra,'BL',0,'L',0);
							if ($Cambio->Estado == 0) {
							$this->pdf->Cell(40,5,'Activo','BL',0,'L',0);
								
							}else{
							$this->pdf->Cell(40,5,'Inactivo','BL',0,'L',0);

							}
							$this->pdf->Cell(40,5,date("Y-m-d"),'BL BB BR ',0,'C',0);
							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/plan/'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/plan/'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function cliente($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados Cliente';
				// Se obtienen los clientes de la base de datos

                $list = $this->db->where('idCliente !=1')->get('Cliente')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Nombre','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Apellidos ','TBL',0,'C','1');
						$this->pdf->Cell(40,7,'Direccion ','TBL',0,'C','1');
						$this->pdf->Cell(35,7,'Telefono ','TBL',0,'C','1');						
						$this->pdf->Cell(35,7,'Correo ','TBL BR',0,'C','1');

						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Cliente) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(35,5,$this->mi_libreria->getSubString(utf8_decode($Cliente->Nombres), 20),'BL',0,'C',0);
							$this->pdf->Cell(35,5,$this->mi_libreria->getSubString(utf8_decode($Cliente->Apellidos), 20),'BL',0,'L',0);
							$this->pdf->Cell(40,5,$this->mi_libreria->getSubString(utf8_decode($Cliente->Direccion), 20),'BL',0,'L',0);						
							$this->pdf->Cell(35,5,$Cliente->Telefono,'BL',0,'L',0);
							$this->pdf->Cell(35,5,utf8_decode($Cliente->Correo),'BL BB BR ',0,'C',0);

							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/cliente'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/cliente'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function proveedor($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados Proveedor';
				// Se obtienen los clientes de la base de datos

                $list = $this->db->get('Proveedor')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Vendedor','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Ruc ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Razon Social ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Direccion ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Telefono ','TBL',0,'C','1');			

						$this->pdf->Cell(30,7,'Correo ','TBL BR',0,'C','1');

						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Cliente) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString(utf8_decode($Cliente->Vendedor), 20),'BL',0,'C',0);
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString(utf8_decode($Cliente->Ruc), 20),'BL',0,'L',0);
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString(utf8_decode($Cliente->Razon_Social), 20),'BL',0,'L',0);		
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString(utf8_decode($Cliente->Direccion), 20),'BL',0,'L',0);	
							$this->pdf->Cell(30,5,$Cliente->Telefono,'BL',0,'L',0);
							$this->pdf->Cell(30,5,utf8_decode($Cliente->Correo),'BL BB BR ',0,'C',0);

							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/proveedor'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/proveedor'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function empleado($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Empleado';
				// Se obtienen los clientes de la base de datos

                $list = $this->db->get('Empleado')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Nombres','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Cargo ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Sueldo ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Direccion ','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Telefono ','TBL',0,'C','1');			

						$this->pdf->Cell(30,7,'Correo ','TBL BR',0,'C','1');

						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Cliente) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString(utf8_decode($Cliente->Nombres).'  '.utf8_decode($Cliente->Apellidos), 20),'BL',0,'C',0);
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString(utf8_decode($Cliente->Cargo), 20),'BL',0,'L',0);
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString(utf8_decode($Cliente->Sueldo), 20),'BL',0,'L',0);		
							$this->pdf->Cell(30,5,$this->mi_libreria->getSubString(utf8_decode($Cliente->Direccion), 20),'BL',0,'L',0);	
							$this->pdf->Cell(30,5,$Cliente->Telefono,'BL',0,'L',0);
							$this->pdf->Cell(30,5,utf8_decode($Cliente->Correo),'BL BB BR ',0,'C',0);

							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/empleado'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/empleado'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}
	public function user($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Usuarios';
				// Se obtienen los clientes de la base de datos
                $this->db->join('Usuario user', 'user.Permiso_idPermiso = pr.idPermiso', 'INNER');
                $list = $this->db->get('Permiso pr')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Nombre','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Descripción ','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Opservacion ','TBL BR',0,'C','1');

						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $user) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString(utf8_decode($user->Usuario), 40),'BL',0,'C',0);
							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString(utf8_decode($user->Descripcion), 40),'BL',0,'L',0);
							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString(utf8_decode($user->Oservacion), 40),'BL BB BR ',0,'C',0);

							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/user'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/user'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function empresa($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Empresa';
				// Se obtienen los clientes de la base de datos

                $list = $this->db->get('Empresa')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

			        $this->pdf->SetFillColor(200,200,200);
					$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
					$this->pdf->Cell(45,7,'Nombres','TBL',0,'C','1');
					$this->pdf->Cell(45,7,'Direccion ','TBL',0,'C','1');
					$this->pdf->Cell(45,7,'Telefono ','TBL',0,'C','1');			

					$this->pdf->Cell(45,7,'Correo ','TBL BR',0,'C','1');

					$this->pdf->Ln(7);
					// La variable $x se utiliza para mostrar un número consecutivo
					$x = 1;
			foreach ($list as $key => $empresa) {
				$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
				$this->pdf->Cell(45,5,$this->mi_libreria->getSubString(utf8_decode($empresa->Nombre), 20),'BL',0,'C',0);
				$this->pdf->Cell(45,5,$this->mi_libreria->getSubString(utf8_decode($empresa->Direccion), 20),'BL',0,'L',0);	
				$this->pdf->Cell(45,5,$empresa->Telefono,'BL',0,'L',0);
				$this->pdf->Cell(45,5,utf8_decode($empresa->Email),'BL BB BR ',0,'C',0);
				$this->pdf->Ln(5);
			}
		    $fecha = date("Y-m-d");
			$this->pdf->Output('pdf/empleado'.$fecha.'.pdf','F');  //save pdf pdf.pdf
			$this->pdf->Output('pdf/empleado'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
			} else {
					$data     = array(
					'titulo'  => 'No existen datos de busqueda',
					'titulo2' => $nombre,
					'titulo3' => 'No existen datos', );
		        	$this->load->view('Error/error.php', $data, FALSE);
			}
	}

	public function permiso($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Permiso';
				$this->db->where('(idPermiso != 1) AND (idPermiso != 3)');
                $list = $this->db->get('Permiso')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Nombre','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Descripción ','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Permiso ','TBL BR',0,'C','1');

						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				$this->load->model('Permiso_Model', 'Permiso');
				foreach ($list as $key => $permiso) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString(utf8_decode($permiso->Descripcion), 40),'BL',0,'C',0);
							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString(utf8_decode($permiso->Oservacion), 40),'BL',0,'L',0);
							$hass = $this->Permiso->permiso_has($permiso->idPermiso);
							if ($hass == '') {
							$this->pdf->Cell(60,5,'Sin Acceso','BL BB BR ',0,'C',0);

							} else {
							$this->pdf->Cell(60,5,'Permiso','BL BB BR ',0,'C',0);
							}

							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/permiso'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/permiso'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function produccion($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados Producto  en Produccion';
				$this->db->join('Producto', 'dp.Producto_idProducto = Producto.idProducto', 'inner');
				$this->db->where('Produccion =2 ');
                $list = $this->db->get('Detale_Produccion dp')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(45,7,'Nombre','TBL',0,'C','1');
						$this->pdf->Cell(45,7,'Produccion ','TBL',0,'C','1');
						$this->pdf->Cell(45,7,'Estado ','TBL ',0,'C','1');
						$this->pdf->Cell(45,7,'Fecha Produccion ','TBL ',0,'C','1');


						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Producto) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(45,5,$this->mi_libreria->getSubString(utf8_decode($Producto->Nombre), 40),'BL',0,'C',0);
						    if ($Producto->Estado_d == null)
						    {
								$this->pdf->Cell(45,5,$Producto->CantidadProduccion,'BL BB BR ',0,'C',0);
								$this->pdf->Cell(45,5,'...Produciendo...','BL BB BR ',0,'C',0);
								$this->pdf->Cell(45,5,$Producto->Fecha_Produccion,'BL BB BR ',0,'C',0);
							}
							else
							{
								$this->pdf->Cell(45,5,$Producto->CantidadProduccion,'BL BB BR ',0,'C',0);
								$this->pdf->Cell(45,5,'Producro Producido','BL BB BR ',0,'C',0);
								$this->pdf->Cell(45,5,$Producto->Fecha_Produccion,'BL BB BR ',0,'C',0);
							}
							


							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/produccion'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/produccion'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function stock($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados Producto  en Stock';
 				 $this->load->model("Producto_Model");
		        if (empty($id)) {
		         $list = $this->Producto_Model->getProducto();
		        }else{
		         $list = $this->Producto_Model->getProducto($id);
		        }

				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Nombre','TBL',0,'C','1');
						$this->pdf->Cell(20,7,' Totales','TBL',0,'C','1');
						$this->pdf->Cell(25,7,'Total Stock ','TBL ',0,'C','1');
						$this->pdf->Cell(25,7,' Total Deposito','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Precio Venta ','TBL ',0,'C','1');
						$this->pdf->Cell(30,7,'Unidad || Medida ','TBL BR',0,'C','1');



						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Producto) {

					$Marca  = $this->mi_libreria->getSubString($Producto->Marca,22);
					$Nombre = $this->mi_libreria->getSubString($Producto->Nombre, 22);
					$resultado = intval(preg_replace('/[^0-9]+/', '', $Producto->Precio_Venta), 10); 
					$Unidad = intval(preg_replace('/[^0-9]+/', '', $Producto->Unidad), 10); 
					$Precio_Venta =  number_format($resultado,0,'.',',');
						$cantidad = ($Producto->Cantidad_A + $Producto->Cantidad_D);

						$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
						$this->pdf->Cell(50,5,$Nombre." (". $Marca.")",'BL',0,'C',0);
						$this->pdf->Cell(20,5,$cantidad,'BL BB BR ',0,'C',0);
						$this->pdf->Cell(25,5,$Producto->Cantidad_A,'BL BB BR ',0,'C',0);						
						$this->pdf->Cell(25,5,$Producto->Cantidad_D,'BL BB BR ',0,'C',0);
						$this->pdf->Cell(30,5,$Precio_Venta,'BL BB BR ',0,'C',0);
						$this->pdf->Cell(30,5,$Unidad."  ".$Producto->Medida,'BL BB BR ',0,'C',0);
						$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/lisproduccion'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/lisproduccion'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function detalleproduccion($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados Detalle Produccion';
		        $this->load->model("Produccion_Model",'Producir');
                $list =  $this->Producir->detalle(array('idProduccion' => $id));
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(45,7,'Cantidad','TBL',0,'C','1');
						$this->pdf->Cell(45,7,'Nombre ','TBL',0,'C','1');
						$this->pdf->Cell(45,7,'Precio ','TBL ',0,'C','1');
						$this->pdf->Cell(45,7,'Subtotal ','TBL BR',0,'C','1');


						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;$suma = 0;
				foreach ($list as $key => $Producto) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(45,5,$Producto->Cantidad,'BL',0,'C',0);
							$this->pdf->Cell(45,5,$Producto->Nombre,'BL BB BR ',0,'C',0);
							$this->pdf->Cell(45,5,number_format($Producto->Precio,0,'.',','),'BL BB BR ',0,'C',0);
							$this->pdf->Cell(45,5,$Producto->Cantidad*$Producto->Precio,'BL BB BR ',0,'C',0);
							$this->pdf->Ln(5);
							$suma += $Producto->Cantidad*$Producto->Precio;
				}
						$this->pdf->Cell(115,7,'','0',0,'c');
						$this->pdf->Cell(30,7,'Monto total:',' BB ',0,'L',0);
						$this->pdf->Cell(45,7,number_format($suma,0,'.',','),' BB ',0,'C',0);
						$this->pdf->Ln(7);

			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/lisproduccion'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/lisproduccion'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}

	public function categoria($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Categoria';
				// Se obtienen los clientes de la base de datos
                $list = $this->db->get('Categoria')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(90,7,'Categoria','TBL',0,'C','1');
						$this->pdf->Cell(90,7,utf8_decode('Descripción '),'TBL BR',0,'C','1');

						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Categoria) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(90,5,$this->mi_libreria->getSubString(utf8_decode($Categoria->Categoria), 40),'BL',0,'C',0);
							$this->pdf->Cell(90,5,$this->mi_libreria->getSubString(utf8_decode($Categoria->Descrip), 40),'BL BB BR',0,'C',0);


							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/user'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/user'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function Descuento($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Productos Con Descuento';
				$this->load->model('Descuento_Model','des');
                $list = $this->des->getdescuento();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Producto','TBL',0,'C','1');
						$this->pdf->Cell(60,7,utf8_decode('Categoría'),'TBL',0,'C','1');
						$this->pdf->Cell(50,7,'Marca ','TBL BR',0,'C','1');
						$this->pdf->Cell(20,7,'Descuento ','TBL BR',0,'C','1');


						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Categoria) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(50,5,$this->mi_libreria->getSubString(utf8_decode($Categoria->Nombre), 40),'BL',0,'C',0);
							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString(utf8_decode($Categoria->Categoria), 40),'BL',0,'C',0);
							$this->pdf->Cell(50,5,$this->mi_libreria->getSubString(utf8_decode($Categoria->Marca), 40),'BL ',0,'C',0);
							$this->pdf->Cell(20,5,$this->mi_libreria->getSubString(utf8_decode($Categoria->Descuento).' %', 40),'BL BB BR',0,'C',0);


							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/Descuento'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Descuento'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function marca($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Marcas';
				// Se obtienen los clientes de la base de datos
                $list = $this->db->get('Marca')->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(90,7,'Marca','TBL',0,'C','1');
						$this->pdf->Cell(90,7,utf8_decode('Descripción '),'TBL BR',0,'C','1');

						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Categoria) {
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(90,5,$this->mi_libreria->getSubString(utf8_decode($Categoria->Marca), 40),'BL',0,'C',0);
							$this->pdf->Cell(90,5,$this->mi_libreria->getSubString(utf8_decode($Categoria->Descripcion), 40),'BL BB BR',0,'C',0);


							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/user'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/user'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function productonull($id='')
	{
	
		// Se carga la libreria fpdf
                 $this->load->library('pdf');
                 $nombre = 'Listados de Productos';
				$this->db->select('idDetalle_Devolucion,Estado,Motivo,Precio,Cantidad,Nombre,Codigo,Img');
				$this->db->from('Detalle_Devolucion dd');
				$this->db->join('Producto', 'dd.Producto_idProducto = Producto.idProducto', 'inner');
				$this->db->where('Motivo', $id);
				$list = $this->db->get()->result();
				// echo var_dump($list);
				if( !empty( $list ) ){
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->pdf = new Pdf();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				$this->Header($nombre);
				$this->pdf->AliasNbPages();

				/* Se define el titulo, márgenes izquierdo, derecho y
				* el color de relleno predeterminado
				*/
				$this->pdf->SetTitle($nombre);
				$this->pdf->SetLeftMargin(10);
				$this->pdf->SetRightMargin(10);


				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/

				        $this->pdf->SetFillColor(200,200,200);
						$this->pdf->Cell(10,7,'#','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Codigo','TBL',0,'C','1');
						$this->pdf->Cell(60,7,'Produccto ','TBL ',0,'C','1');
						$this->pdf->Cell(60,7,utf8_decode($id),'TBL BR',0,'C','1');


						$this->pdf->Ln(7);
						// La variable $x se utiliza para mostrar un número consecutivo
						$x = 1;
				foreach ($list as $key => $Producto) {
					        $Nombre = $this->mi_libreria->getSubString($Producto->Nombre, 10);
							$this->pdf->Cell(10,5,$x++,'BL',0,'C',0);
							$this->pdf->Cell(60,5,$this->mi_libreria->getSubString($Producto->Codigo, 12),'BL',0,'C',0);
							$this->pdf->Cell(60,5,$Nombre,'BL ',0,'C',0);
							$this->pdf->Cell(60,5, $Producto->Cantidad,'BL BB BR',0,'C',0);



							$this->pdf->Ln(5);
				}
			    $fecha = date("Y-m-d");
        		$this->pdf->Output('pdf/user'.$fecha.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/user'.$fecha.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
	}


	public function factura($nombre,$id,$alias)
	{

			/*
			* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
			* heredó todos las variables y métodos de fpdf
			*/
			$this->load->library('factura');
			$this->pdf = new Factura();
			// $this->pdf->Header($nombre);
			// Agregamos una página
			$this->pdf->AddPage();
			// Define el alias para el número de página que se imprimirá en el pie
			$this->pdf->AliasNbPages();
		     $this->headfactura($nombre,$alias);
			$this->pdf->SetFillColor(250,250,250);
			$this->pdf->SetFont('Arial', 'b', 8);
			$this->pdf->Cell(25,10,'FECHA:   '.date("d",strtotime($alias->Fecha_expedicion)),'',0,'L','1');
			$this->pdf->Cell(50,10,'DE   '.nombremes(date("m",strtotime($alias->Fecha_expedicion))),'',0,'L','1');
			$this->pdf->Cell(25,10,'DEL 20'.date("y",strtotime($alias->Fecha_expedicion)),'',0,'L','1');
			if ($alias->Contado_Credito == 1) {
			   $this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE VENTA'),'',0,'L','1');
			    $this->pdf->Cell(25,10,'CONTADO:  X','',0,'C','1');
			    $this->pdf->Cell(25,10,utf8_decode('CRÉDITO: '),'',0,'C','1');
			}else{
			   $this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE VENTA'),'',0,'L','1');
			    $this->pdf->Cell(25,10,'CONTADO:','',0,'C','1');
			     $this->pdf->Cell(25,10,utf8_decode('CRÉDITO X'),'',0,'C','1');
			}


			$this->pdf->Ln(7);

			$this->pdf->Cell(42,10,utf8_decode('NOMBRE O RAZÓN SOCIAL:'),'',0,'L','1');
			$this->pdf->Cell(98,10,utf8_decode($alias->Nombres.' '.$alias->Apellidos),'',0,'L','1');
			$this->pdf->Cell(50,10,'R.U.C. : '.$alias->Ruc,'',0,'L','1');
			$this->pdf->Ln(7);

			$this->pdf->Cell(60,10,utf8_decode('NOTA DE REMISIÓN Nº:'),'',0,'L','1');
			$this->pdf->Cell(40,10,'TEL.: '.$alias->Telefono,'',0,'L','1');
			$this->pdf->Cell(90,10,'DOMICILIO:','',0,'L','1');
			$this->pdf->Ln(10);



			/* Se define el titulo, márgenes izquierdo, derecho y
			* el color de relleno predeterminado*/
			$this->pdf->SetFillColor(250,250,250);

			// Se define el formato de fuente: Arial, negritas, tamaño 9
			/*
			* TITULOS DE COLUMNAS
			*
			* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
			*/
		    $this->pdf->SetFont('Arial', 'b', 8);
			$this->pdf->Cell(14,10,'Cantidad','TBL',0,'C','1');
			$this->pdf->Cell(75,10,utf8_decode('Descripción de Mercadería y/o Servicios'),'TBL',0,'C','1');
			$this->pdf->Cell(21,10,'Precio Unitario','TBL',0,'C','1');
			$this->pdf->Cell(80,5,'VALOR DE VENTAS','TBL BR',0,'C','1');
			$this->pdf->Ln(5);
			$this->pdf->Cell(110,5,'',0,'C','1');
			$this->pdf->Cell(20,5,'Exentas','TBL BR',0,'C','1');
			$this->pdf->Cell(30,5,'5%','TBL BR',0,'C','1');
			$this->pdf->Cell(30,5,'10%','TBL BR',0,'C','1');
			$this->pdf->Ln(5);
				$this->pdf->SetFont('Arial', 'b', 7);
				$this->pdf->Cell(14,6,'','TBL BR',0,'C',0);
				$this->pdf->Cell(75,6,$alias->Concepto,'TBL BR',0,'L',0);
				$this->pdf->Cell(21,6,'','TBL BR',0,'C',0);
				$this->pdf->Cell(20,6,'','TBL BR',0,'C',0);
				$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
				$this->pdf->Cell(30,6,number_format($alias->Monto_Total,0,',','.').'  ','TBL BR',0,'C',0);
				$this->pdf->Ln(6);
				for ($i=0; $i <10; $i++) { 
				$this->pdf->Cell(14,6,'','TBL BR',0,'C',0);
				$this->pdf->Cell(75,6,'','TBL BR',0,'L',0);
				$this->pdf->Cell(21,6,'','TBL BR',0,'C',0);
				$this->pdf->Cell(20,6,'','TBL BR',0,'C',0);
				$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
				$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
				$this->pdf->Ln(6);
				}
    	$this->pdf->SetFillColor(0, 27, 0, 1);
		$this->pdf->SetDrawColor(0, 27, 0, 1);
		$this->pdf->SetTextColor(0, 27, 0, 1);

		$this->pdf->Cell(160,6,'  SUBTOTAL: ','TBL BR',0,'L',0);
		$this->pdf->Cell(30,6,' '.number_format($alias->Monto_Total,0,',','.').'  ','TBL BR',0,'C',0);
		$this->pdf->Ln(6);

		$this->pdf->Cell(160,6,'  DESCUENTO: ','TBL BR',0,'L',0);
		$this->pdf->Cell(30,6,' '.number_format($alias->Descuento_Total,0,',','.').'  ','TBL BR',0,'C',0);
		$this->pdf->Ln(8);

		$this->pdf->Cell(150,5,'  TOTAL A PAGAR:   '.numtoletras($alias->Monto_Total),'TBL BR',0,'L',0);
		$this->pdf->Cell(40,10,'GS. '.number_format($alias->Monto_Total,0,',','.').'  ','TBL BR',0,'L',0);
		$this->pdf->Ln(5);
		$this->pdf->Cell(150,5,'  ','TBL BR',0,'L',0);
		$this->pdf->Ln(5);

		$this->pdf->Cell(60,6,utf8_decode(strtoupper('Liquidación del Iva (5%):    ')),'TBL BR',0,'L',0);
		$this->pdf->Cell(65,6,'  (10%):     '.number_format($alias->Monto_total_Iva,0,',','.'),'TBL BR',0,'L',0);
		$this->pdf->Cell(65,6,' Total IVA:   '.number_format($alias->Monto_total_Iva,0,',','.').'  ','TBL BR',0,'L',0);
							$this->pdf->Output('pdf.pdf','F');  //save pdf
							$this->pdf->Output('pdf.pdf', 'I'); // show pdf
	}


	public function boletas($nombre,$id, $alias)
	{
		/*
			* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
			* heredó todos las variables y métodos de fpdf
			*/
			$this->load->library('factura');
			$this->pdf = new Factura();
			// $this->pdf->Header($nombre);
			// Agregamos una página
			$this->pdf->AddPage();
			// Define el alias para el número de página que se imprimirá en el pie
			$this->pdf->AliasNbPages();
		     $this->headboleta($nombre, $alias, $alias->Ticket );
			$this->pdf->SetFillColor(250,250,250);
			$this->pdf->SetFont('Arial', 'b', 8);
			$this->pdf->Cell(25,10,'FECHA:   '.date("d",strtotime($alias->Fecha_expedicion)),'',0,'L','1');
			$this->pdf->Cell(50,10,'DE   '.nombremes(date("m",strtotime($alias->Fecha_expedicion))),'',0,'L','1');
			$this->pdf->Cell(25,10,'DEL 20'.date("y",strtotime($alias->Fecha_expedicion)),'',0,'L','1');
			if ($alias->Contado_Credito == 1) {
			   $this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE VENTA'),'',0,'L','1');
			    $this->pdf->Cell(25,10,'CONTADO:  X','',0,'C','1');
			    $this->pdf->Cell(25,10,utf8_decode('CRÉDITO: '),'',0,'C','1');
			}else{
			   $this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE VENTA'),'',0,'L','1');
			    $this->pdf->Cell(25,10,'CONTADO:','',0,'C','1');
			     $this->pdf->Cell(25,10,utf8_decode('CRÉDITO X'),'',0,'C','1');
			}


			$this->pdf->Ln(7);
			$this->pdf->Cell(20,10,'Senhor (ES):   ','',0,'L','1');
			$this->pdf->Cell(100,10,''.$alias->Nombres.' '.$alias->Apellidos,'',0,'L','1');
			$this->pdf->Cell(20,10,'RUC/CI :   ','',0,'L','1');
			$this->pdf->Cell(50,10,''.$alias->Ruc,'',0,'L','1');
			$this->pdf->Ln(7);
			$this->pdf->Cell(30,10,'Nota de Remision:   ','',0,'L','1');
			$this->pdf->Cell(90,10,'','',0,'L','1');
			$this->pdf->Cell(20,10,'Telefono :   ','',0,'L','1');
			$this->pdf->Cell(50,10,''.$alias->Telefono,'',0,'L','1');
			$this->pdf->Ln(10);





			/* Se define el titulo, márgenes izquierdo, derecho y
			* el color de relleno predeterminado*/
			$this->pdf->SetFillColor(250,250,250);
			$this->pdf->Cell(25,7,'Cantidad','TBL',0,'C','1');
			$this->pdf->Cell(80,7,utf8_decode('Descripción de Mercadería y/o Servicios'),'TBL',0,'C','1');
			$this->pdf->Cell(25,7,'Inpuesto','TBL',0,'C','1');
			$this->pdf->Cell(30,7,'Precio','TBL',0,'C','1');
			$this->pdf->Cell(30,7,'Subtotal','TBL BR',0,'C','1');
			$this->pdf->Ln(7);


				$this->pdf->Cell(25,6,'','TBL BR',0,'C',0);
				$this->pdf->Cell(80,6,$alias->Concepto,'TBL BR',0,'L',0);
				$this->pdf->Cell(25,6,''.' ','TBL BR',0,'C',0);
				$this->pdf->Cell(30,6,number_format($alias->Monto_Total,0,',','.').'  ','TBL BR',0,'C',0);
				$this->pdf->Cell(30,6,number_format($alias->Monto_Total,0,',','.').'  ','TBL BR  ',0,'C',0);
				$this->pdf->Ln(6);
				for ($i=0; $i <10; $i++) { 
					$this->pdf->Cell(25,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(80,6,'','TBL BR',0,'L',0);
					$this->pdf->Cell(25,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
					$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
					$this->pdf->Ln(6);
				}


		$this->pdf->Ln(7);
		$this->pdf->SetFillColor(0, 27, 0, 1);
		$this->pdf->SetDrawColor(0, 27, 0, 1);
		$this->pdf->SetTextColor(0, 27, 0, 1);
		$this->pdf->Cell(160,6,'  DESCUENTO: ','TBL BR',0,'L',0);
		$this->pdf->Cell(30,6,' '.number_format($alias->Descuento_Total,0,',','.').'  ','TBL BR',0,'C',0);
		$this->pdf->Ln(6);
		$this->pdf->Cell(160,5,'  Total :     '.'  ',' TBL BR',0,'L',0);
		$this->pdf->Cell(30,5,' '.number_format($alias->Monto_Total,0,',','.').'  ','TBL BR ',0,'C',0);
				$this->pdf->Ln(8);
							$this->pdf->Output('pdf.pdf','F');  //save pdf
							$this->pdf->Output('pdf.pdf', 'I'); // show pdf
	}

	public function cob_ro($id='')
	{
   				 // Se carga la libreria fpdf
   				 $this->load->library('pdf');
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
				// Creacion del PDF
				/*
				* Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
				* heredó todos las variables y métodos de fpdf
				*/
				$this->load->library('factura');
				$this->pdf = new Factura();
				// $this->pdf->Header($nombre);
				// Agregamos una página
				$this->pdf->AddPage();
				// Define el alias para el número de página que se imprimirá en el pie
				$this->pdf->AliasNbPages();
			    $this->headboleta($nombre,'', $row->Num_Recibo);
					$this->pdf->SetFillColor(250,250,250);
					$this->pdf->SetFont('Arial', 'b', 8);
					$this->pdf->Cell(25,10,'FECHA:   '.date("d",strtotime($row->Fecha)),'',0,'L','1');
					$this->pdf->Cell(50,10,'DE   '.nombremes(date("m",strtotime($row->Fecha))),'',0,'L','1');
					$this->pdf->Cell(25,10,'DEL 20'.date("y",strtotime($row->Fecha)),'',0,'L','1');
				    $this->pdf->Cell(40,10,utf8_decode('CONDICIÓN DE VENTA'),'',0,'L','1');
				    $this->pdf->Cell(25,10,'CONTADO:  X','',0,'C','1');
					$this->pdf->Cell(25,10,utf8_decode('CRÉDITO: '),'',0,'C','1');
					$this->pdf->Ln(7);
					if (is_null($row->idCliente)) {
						$this->pdf->Cell(20,10,'Senhor (ES):   ','',0,'L','1');
						$this->pdf->Cell(100,10,''.$row->Razon_Social.' '.$row->Vendedor,'',0,'L','1');
						$this->pdf->Cell(20,10,'RUC/CI :   ','',0,'L','1');
						$this->pdf->Cell(50,10,''.$row->Ruc,'',0,'L','1');
					}else{
						$this->pdf->Cell(20,10,'Senhor (ES):   ','',0,'L','1');
						$this->pdf->Cell(100,10,''.$row->Nombres.' '.$row->Apellidos,'',0,'L','1');
						$this->pdf->Cell(20,10,'RUC/CI :   ','',0,'L','1');
						$this->pdf->Cell(50,10,''.$row->Ruc,'',0,'L','1');
					}

					$this->pdf->Ln(7);
					$this->pdf->Cell(30,10,'Nota de Remision:   ','',0,'L','1');
					$this->pdf->Cell(90,10,'','',0,'L','1');
					$this->pdf->Cell(20,10,'Telefono :   ','',0,'L','1');
					$this->pdf->Cell(50,10,''.'','',0,'L','1');
					$this->pdf->Ln(10);
				// Se define el formato de fuente: Arial, negritas, tamaño 9
				$this->pdf->SetFont('Arial', 'B', 9);
				/*
				* TITULOS DE COLUMNAS
				*
				* $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
				*/
				        $this->pdf->SetFillColor(250,250,250);
						$this->pdf->Cell(25,7,'Cantidad','TBL',0,'C','1');
						$this->pdf->Cell(80,7,utf8_decode('Descripción de Mercadería y/o Servicios'),'TBL',0,'C','1');
						$this->pdf->Cell(25,7,'Inpuesto','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Precio','TBL',0,'C','1');
						$this->pdf->Cell(30,7,'Subtotal','TBL BR',0,'C','1');
						$this->pdf->Ln(7);
							$mmonto = intval(preg_replace('/[^0-9]+/', '', $row->Monto), 10); 
							if ($row->Importe > $mmonto) {
							   $Monto = $mmonto;
							} else {
							  $Monto = $row->Importe;
							}
							$this->pdf->Cell(25,6,'1','BL',0,'C',0);
							$this->pdf->Cell(80,6,utf8_decode('Cobro Cuota Nº ').$row->Num_cuota,'BL',0,'C',0);
							$this->pdf->Cell(25,6,'','TBL BR',0,'C',0);
							$this->pdf->Cell(30,6,number_format( $Monto,0,',','.'),'BL',0,'C',0);
							$this->pdf->Cell(30,6,number_format( $Monto,0,',','.'),'BL BB BR ',0,'C',0);
							$this->pdf->Ln(6);
							for ($i=0; $i <10; $i++) { 
								$this->pdf->Cell(25,6,'','TBL BR',0,'C',0);
								$this->pdf->Cell(80,6,'','TBL BR',0,'L',0);
								$this->pdf->Cell(25,6,'','TBL BR',0,'C',0);
								$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
								$this->pdf->Cell(30,6,'','TBL BR',0,'C',0);
								$this->pdf->Ln(6);
							}
				$this->pdf->Ln(7);
				$this->pdf->SetFillColor(0, 27, 0, 1);
				$this->pdf->SetDrawColor(0, 27, 0, 1);
				$this->pdf->SetTextColor(0, 27, 0, 1);
				$this->pdf->Cell(160,5,'  Total A Pagar:     '.'  ','TBL BR',0,'L',0);
				$this->pdf->Cell(30,5,' '.number_format($Monto,0,',','.').'  ','TBL BR',0,'C',0);
				$this->pdf->Ln(8);
				$this->pdf->Output('pdf/Cobro/'.$id.'.pdf','F');  //save pdf pdf.pdf
				$this->pdf->Output('pdf/Cobro/'.$id.'.pdf', 'I'); // show pdf pdf.pdf
				} else {
						$data     = array(
						'titulo'  => 'No existen datos de busqueda',
						'titulo2' => $nombre,
						'titulo3' => 'No existen datos', );
                    	$this->load->view('Error/error.php', $data, FALSE);
				}
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
}

/* End of pdf Reportes.php */
/* Location: ./application/controllers/Reportes.php */
