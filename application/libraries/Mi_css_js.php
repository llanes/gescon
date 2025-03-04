<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mi_css_js {
    var $css_path = '';
    var $js_path = '';
    var $css = array(
		'bootstrap'                =>'content/bootstrap/css/', 
		'font-awesome'             =>'content/font-awesome/css/',
		'AdminLTE'                 =>'content/dist/css/',
        'main'                     =>'content/bootstrap/css/',
		'_all-skins.min'           =>'content/dist/css/skins/', 
		// 'dark-theme'           =>'content/bootstrap/css/', 

		'sweetalert2'              =>'content/alert/', 
		'pace-theme-center-simple' =>'bower_components/pace/themes/red/'
         );
    var $js = array(
        'jQuery-2.1.4.min'        =>'content/plugins/jQuery/', 
        'bootstrap.min'            =>'content/bootstrap/js/', 
        'app.min'                  =>'content/dist/js/',
        'adminlte'                 =>'content/dist/js/', 
        'pace'                     =>'bower_components/pace/',
        'sweetalert2.min'          =>'content/alert/',
        'myscript'                 =>'bower_components/script_vista/'

    );
    var $functions = array();
	protected $CI;

	public function __construct()
	{
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->helper('html');
	}


    /**
     * Se inicializan todos los parametros
     * @param array $params Contiene los parametros enviados por el usuario
     */
    function init_css_js($params = null,$style) {
        if (!is_null($params)) {
        	if ($style == 'css') {
        		$data['css'] = array_merge($this->css,$params);
        	}
        	if ($style == 'js') {
        		$data['js'] = array_merge($this->js,$params);
        	}
            
        }else{
        	$data = $this->$style;
        }

        foreach ($data as $key => $val) {
            if (isset($this->$key)) {
                $this->$key = $val;
            }
        }
    }
    /**
     * Metodo para cargar las hojas de estilos CSS
     * @return string Etiquetas HTML con las hojas de estilos
     */
    function css() {
        $content = NULL;
        foreach ($this->css as $key => $val) {
            // Se verifica que $key no sea entero
            // Si no es entero es un arreglo asociativo
            // Si es entero es un arreglo unidimensional
            if(!is_int($key)) {
                $content .= link_tag($val . $key . '.css');
            }
            else {
                // Se verifica si el valor enviado es una URL correcta
                if(filter_var($val, FILTER_VALIDATE_URL)) {
                    $content .= link_tag($val);
                }
                else {
                    $content .= link_tag($this->css_path . $val . '.css');
                }
            }
        }
        return $content;
    }

    /**
     * Metodo para cargar los scripts JS
     * @return string Etiquetas HTML con las archivos JS
     */
    function js() {
        $content = NULL;
        foreach ($this->js as $key => $val) {
            // Se verifica que $key no sea entero
            // Si no es entero es un arreglo asociativo
            // Si es entero es un arreglo unidimensional
            if(!is_int($key)) {
                $content .= '<script type="text/javascript" src="' . base_url() . $val . $key . '.js"></script>';
            }
            else {
                // Se verifica si el valor enviado es una URL correcta
                if(filter_var($val, FILTER_VALIDATE_URL)) {
                    $content .= '<script type="text/javascript" src="' . $val . '"></script>';
                }
                else {
                    $content .= '<script type="text/javascript" src="' . base_url() . $this->js_path . $val . '.js"></script>';
                }
            }
        }
        return $content;
    }



	/**
	 * Metodo para incluir metodos JS dentro de vistas
	 * @return string Metodo JS
	 */
	function functions() {
		if (!empty($this->functions)) {
			$content = '<script>$(function() {';
			foreach ($this->functions as $item) {
				$content .= $item;
			}
			$content .= '});</script>';
			return $content;
		}
	}


}

/* End of file Mi_css.php */
/* Location: ./application/libraries/Mi_css.php */
 ?>