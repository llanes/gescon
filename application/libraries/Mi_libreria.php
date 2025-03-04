<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mi_libreria
{
	protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();
	}

	function getSubString($string, $length=NULL)
	{
	    //Si no se especifica la longitud por defecto es 50
	    if ($length == NULL)
	        $length = 20;
	    if ($string == null) {
	    	return '';
	    } else {
	    //Primero eliminamos las etiquetas html y luego cortamos el string
	    $stringDisplay = substr(strip_tags($string), 0, $length);
	    //Si el texto es mayor que la longitud se agrega puntos suspensivos
	    if (strlen(strip_tags($string)) > $length)
	        $stringDisplay .= ' ...';
	    return $stringDisplay;
	    }

	}
	function getmenu($id)
	{
		if ($id != 1) {
				$this->ci->load->model('Model_Menu');
				return $dato = $this->ci->Model_Menu->octenerMenu($id);
		} else {
			return false;
		}
	}

	function sanear_string($string)
	{
	 $string = trim($string);

		    $string = str_replace(
		        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
		        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
		        $string
		    );

		    $string = str_replace(
		        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
		        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
		        $string
		    );
		   $string = str_replace(
		       array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
		       array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
		       $string
		   );
		   $string = str_replace(
		       array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
		       array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
		       $string
		   );
		   $string = str_replace(
		       array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
		       array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
		       $string
		   );
		   $string = str_replace(
		       array('ñ', 'Ñ', 'ç', 'Ç'),
		       array('n', 'N', 'c', 'C',),
		       $string
		   );
		 //Esta parte se encarga de eliminar cualquier caracter extraño
		    $string = str_replace(
		        array(
	        	 "©","\\", "¨", "º", "-", "~",
	             "#", "@", "|", "!", "\"",
	             "·", "$", "%", "&", "/",
	             "(", ")", "?", "'", "¡",
	             "¿", "[", "^", "`", "]",
	             "+", "}", "{", "¨", "´",
	             ">", "< ", ";", ",", ":",
	             ".","<code>"," "),'',
		        $string
		    );



	   return $string;
	}

	function remplse($string)
	{
	$strin = str_replace(')', '', substr_replace(substr_replace($string, '', -4, 23), '', 0, 23));
	return utf8_decode($strin);

	}


	/**
	 * [upload_file description]
	 * @param  string $file_name [description]
	 * @return [type]            [description]
	 */
	function upload_file($file_name)
	{			$nombre_img = url_title(convert_accented_characters($this->ci->security->xss_clean( ucfirst(strtolower($file_name)))),'_',TRUE);
				$nombre_modifi = str_replace(array("jpg"),'',$nombre_img);
				$nombre_modifi  .= '.jpg';
				$config['upload_path']   = './bower_components/uploads/';
				$config['allowed_types'] = 'jpg|png|jpeg';
				$config['file_name'] = $file_name.= '.jpg';
				$config['max_size']      = 2048;
				$config['max_width']     = 1024;
				$config['max_height']    = 768;
				$this->ci->load->library('upload', $config);
	}

	/**
	 * [create FUNCIÓN PARA CREAR LA MINIATURA A LA MEDIDA QUE LE DIGAMOS]
	 * @param  [type] $$nombre_modifi [description]
	 * @return [type]           [description]
	 */
    public function create($nombre_modifi){
    	$config['image_library'] = 'gd2';
        $config['source_image'] = './bower_components/uploads/'.$nombre_modifi;
        $config['width'] = 250;
        $config['height'] = 250;
        $this->ci->load->library('image_lib', $config); 
        $this->ci->image_lib->resize();
        return $nombre_modifi;
    }

    public function medida($value)
    {
    						switch ($value) {
						case 'kg':
							return $medida = 'Kilo';
							break;
						case 'mg':
							return $medida = 'Gramo';
							break;
						case 'kl':
							return $medida = 'Litro';
							break;
						case 'ml':
							return $medida = 'Milimetro' ;
							break;
						case 'un':
							return $medida = 'Unidad' ;
							break;
						case 'dc':
							return $medida = 'Docenas' ;
							break;
						case 'cn':
							return $medida = 'Centenas' ;
							break;
					}
    }

	   public function styleaciento($value='')
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
	    	'borders' => array(
		        'bottom' => array(
		            'style' => PHPExcel_Style_Border::BORDER_THIN,
		        ),
		        
		    ),
		);
		return$styleArray;

   }
	

}

/* End of file Conte_text.php */
/* Location: ./application/libraries/Conte_text.php */
