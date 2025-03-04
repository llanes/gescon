<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Form_validation extends CI_Form_validation{
     function __construct($config = array()){
          parent::__construct($config);
     }
    public function limite($val,$total)
    {
    	if ($val > $total) {
    		return false;
    	}else{
    		return true;
    	}
    }
}