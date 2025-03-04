<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
	 *
	 * @return void
	 * @author Christian LLanes
	 **/
    $common_rules = 'trim|strip_tags';
    $required_rules = $common_rules . '|required';
    $numeric_rules = $common_rules . '|numeric';

       $config = array(
//////////////////////////////////////////validation login////////////////////////////////////////////////////////////////////////////

                'Login_validation' => array(
                                   array(
                                            'field' => 'usuario',
                                            'label' => 'Usuario',
                                            'rules' => 'trim|required|callback_check_nombre|strip_tags'
                                         ),
                                     array(
                                            'field' => 'password',
                                            'label' => 'Contraseña',
                                            'rules' => 'trim|required|callback_check_pass|strip_tags'
                                         ),
                    ),
                                       // final
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Validacion registro_user///////////////////////////////////////////////////////////////////////
                            'registro_user' => array(
                                array(
                                    'field' => 'cargo',
                                    'label' => 'Tipo de Cargo',
                                    'rules' => 'trim|required|min_length[1]|max_length[12]|strip_tags'
                                ),
                                array(
                                    'field' => 'usuario',
                                    'label' => 'Usuario',
                                    'rules' => 'trim|required|callback_check_User|strip_tags'
                                ),
                                array(
                                    'field' => 'password',
                                    'label' => 'Contraseña',
                                    'rules' => 'trim|required|strip_tags'
                                ),
                                array(
                                    'field' => 'passconf',
                                    'label' => 'Confirmar',
                                    'rules' => 'trim|required|strip_tags|matches[password]|md5'
                                ),
                            ),


                                       // final
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Validacion registro_user///////////////////////////////////////////////////////////////////////
                'user_update' => array(

                                    array(
                                            'field' => 'cargo',
                                            'label' => 'Tipo de Cargo',
                                            'rules' => 'trim|required|min_length[1]|max_length[12]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'usuario',
                                            'label' => 'Usuario',
                                            'rules' => 'trim|strip_tags'
                                         ),
                                     array(
                                            'field' => 'password',
                                            'label' => 'Contraseña',
                                            'rules' => 'trim|strip_tags'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'Confirmar',
                                            'rules' => 'trim|strip_tags|matches[password]|md5'
                                         ),

                    ),
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                'user_update2' => array(

                                    array(
                                            'field' => 'cargo',
                                            'label' => 'Tipo de Cargo',
                                            'rules' => 'trim|required|min_length[1]|max_length[12]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'pasantigua',
                                            'label' => 'Contraseña',
                                            'rules' => 'trim|required|strip_tags'
                                         ),
                                    array(
                                            'field' => 'usuario',
                                            'label' => 'Usuario',
                                            'rules' => 'trim|required|strip_tags'
                                         ),
                                     array(
                                            'field' => 'password',
                                            'label' => 'Contraseña',
                                            'rules' => 'trim|required|strip_tags'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'Confirmar',
                                            'rules' => 'trim|required|strip_tags|matches[password]|md5'
                                         ),

                    ),
/////////////////////////////////////////////Validacion update_cliente///////////////////////////////////////////////////////////////////////
                'ajax_update' => array(

                                    array(
                                            'field' => 'Nombres',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|min_length[2]|max_length[30]|strip_tags'
                                         ),

                                    array(
                                            'field' => 'Direccion',
                                            'label' => 'Direccion',
                                            'rules' => 'trim|required|min_length[2]|max_length[50]|strip_tags'
                                         ),
                                            array(
                                            'field' => 'ci_ruc',
                                            'label' => 'CI RUC',
                                            'rules' => 'trim|required|min_length[4]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Telefono',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|required|min_length[2]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Email',
                                            'label' => 'Correo',
                                            'rules' => 'trim|required|valid_emails|min_length[1]|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'usuario',
                                            'label' => 'Usuario',
                                            'rules' => 'trim|required|strip_tags'
                                         ),
                                     array(
                                            'field' => 'password',
                                            'label' => 'Contraseña',
                                            'rules' => 'trim|required|strip_tags'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'Confirmar',
                                            'rules' => 'trim|required|strip_tags|matches[password]|md5'
                                         ),

                    ),

                                       // final

 'registro_Producto' => array(
                                   array(
                                            'field' => 'Codigo',
                                            'label' => 'Código',
                                            'rules' => 'trim|required|callback_check_codigo|min_length[3]|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Nombre',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|min_length[3]|max_length[40]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Marca',
                                            'label' => 'Marca',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Categoria',
                                            'label' => 'Categoria',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Cantidad_A',
                                            'label' => 'Almacen',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Unidad',
                                            'label' => 'Unidad',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Cantidad_D',
                                            'label' => 'Deposito',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                      array(
                                            'field' => 'Stock_Min',
                                            'label' => 'Minimo',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Precio_Costo',
                                            'label' => 'Precio Costo ',
                                            'rules' => 'trim|required|min_length[1]|max_length[30]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Porcentaje_Venta',
                                            'label' => 'Porcentaje Venta',
                                            'rules' => 'trim|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                      array(
                                            'field' => 'Precio_Venta',
                                            'label' => 'Precio Venta',
                                            'rules' => 'trim|min_length[1]|max_length[30]|strip_tags'
                                         ), 
                                      array(
                                            'field' => 'iva',
                                            'label' => 'Iva',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                      array(
                                            'field' => 'Medida',
                                            'label' => 'Medida',
                                            'rules' => 'trim|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                       array(
                                            'field' => 'Descuento',
                                            'label' => 'Descuento',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                        array(
                                            'field' => 'multi',
                                            'label' => 'Proveedor',
                                            'rules' => 'trim|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                           array(
                                            'field' => 'Descripcion',
                                            'label' => 'Descripcion',
                                            'rules' => 'trim|max_length[100]|strip_tags'
                                         ),
                                    

                    ),

                                      // final
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Validacion registro_cliente///////////////////////////////////////////////////////////////////////
                'registro_Empleado' => array(
                                    array(
                                            'field' => 'Nombres',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Apellidos',
                                            'label' => 'Apellidos',
                                            'rules' => 'trim|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Direccion',
                                            'label' => 'Direccion',
                                            'rules' => 'trim|required|min_length[5]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Email',
                                            'label' => 'Correo',
                                            'rules' => 'trim|valid_email|min_length[1]|max_length[40]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Telefono',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|required|min_length[6]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Sueldo',
                                            'label' => 'Sueldo',
                                            'rules' => 'trim|required|min_length[4]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Cargo',
                                            'label' => 'cargo',
                                            'rules' => 'trim|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Usuario',
                                            'label' => 'Usuario',
                                            'rules' => 'trim|callback_check_User|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Password',
                                            'label' => 'Contraseña',
                                            'rules' => 'trim|strip_tags'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'Confirmar',
                                            'rules' => 'trim|strip_tags|matches[Password]|md5'
                                         ),

                    ),

                                       // final
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Validacion registro_cliente///////////////////////////////////////////////////////////////////////
                'registro_Empleado_user' => array(
                                    array(
                                            'field' => 'Nombres',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Apellidos',
                                            'label' => 'Apellidos',
                                            'rules' => 'trim|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Direccion',
                                            'label' => 'Direccion',
                                            'rules' => 'trim|required|min_length[5]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Email',
                                            'label' => 'Correo',
                                            'rules' => 'trim|valid_email|min_length[1]|max_length[40]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Telefono',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|required|min_length[6]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Sueldo',
                                            'label' => 'Sueldo',
                                            'rules' => 'trim|required|min_length[4]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Cargo',
                                            'label' => 'cargo',
                                            'rules' => 'trim|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Usuario',
                                            'label' => 'Usuario',
                                            'rules' => 'trim|required|min_length[4]|max_length[30]|callback_check_User|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Password',
                                            'label' => 'Contraseña',
                                            'rules' => 'trim|required|min_length[4]|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'Confirmar',
                                            'rules' => 'trim|required|min_length[4]|max_length[30]|strip_tags|matches[Password]|md5'
                                         ),

                    ),

                                       // final
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Validacion registro_cliente///////////////////////////////////////////////////////////////////////
                'registro_Cliente' => array(
                                    array(
                                            'field' => 'Nombres',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Apellidos',
                                            'label' => 'Apellidos',
                                            'rules' => 'trim|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'ruc',
                                            'label' => 'Ruc CD',
                                            'rules' => 'trim|callback_check_ruc|min_length[7]|max_length[25]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Direccion',
                                            'label' => 'Direccion',
                                            'rules' => 'trim|required|min_length[5]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Email',
                                            'label' => 'Correo',
                                            'rules' => 'trim|valid_email|min_length[1]|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Telefono',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|min_length[6]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Limite_max_Credito',
                                            'label' => 'Limite Credito',
                                            'rules' => 'trim|min_length[4]|max_length[45]|strip_tags'
                                         ),

                    ),

                                       // final
/////////////////////////////////////////////Validacion update_cliente///////////////////////////////////////////////////////////////////////
                'ajax_update_Empleado' => array(
                                                   array(
                                            'field' => 'Nombres',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Apellidos',
                                            'label' => 'Apellidos',
                                            'rules' => 'trim|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Direccion',
                                            'label' => 'Direccion',
                                            'rules' => 'trim|required|min_length[5]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Email',
                                            'label' => 'Correo',
                                            'rules' => 'trim|valid_email|min_length[1]|max_length[40]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Telefono',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|required|min_length[6]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Sueldo',
                                            'label' => 'Sueldo',
                                            'rules' => 'trim|required|min_length[4]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Cargo',
                                            'label' => 'cargo',
                                            'rules' => 'trim|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Usuario',
                                            'label' => 'Usuario',
                                            'rules' => 'trim|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Password',
                                            'label' => 'Contraseña',
                                            'rules' => 'trim|strip_tags'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'Confirmar',
                                            'rules' => 'trim|strip_tags|matches[Password]|md5'
                                         ),


                    ),

                                       // final
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Validacion update_cliente///////////////////////////////////////////////////////////////////////
                'ajax_update_Empleado_user' => array(
                                                   array(
                                            'field' => 'Nombres',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Apellidos',
                                            'label' => 'Apellidos',
                                            'rules' => 'trim|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Direccion',
                                            'label' => 'Direccion',
                                            'rules' => 'trim|required|min_length[5]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Email',
                                            'label' => 'Correo',
                                            'rules' => 'trim|valid_email|min_length[1]|max_length[40]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Telefono',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|required|min_length[6]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Sueldo',
                                            'label' => 'Sueldo',
                                            'rules' => 'trim|required|min_length[4]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Cargo',
                                            'label' => 'cargo',
                                            'rules' => 'trim|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Usuario',
                                            'label' => 'Usuario',
                                            'rules' => 'trim|required|min_length[1]|max_length[30]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Password',
                                            'label' => 'Contraseña',
                                            'rules' => 'trim|required|min_length[1]|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'passconf',
                                            'label' => 'Confirmar',
                                            'rules' => 'trim|required|min_length[1]|max_length[30]|strip_tags|matches[Password]|md5'
                                         ),


                    ),

                                       // final
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Validacion update_cliente///////////////////////////////////////////////////////////////////////
                'ajax_update_Cliente' => array(
                                    array(
                                            'field' => 'Nombres',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Apellidos',
                                            'label' => 'Apellidos',
                                            'rules' => 'trim|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'ruc',
                                            'label' => 'Ruc CD',
                                            'rules' => 'trim|min_length[7]|max_length[25]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Direccion',
                                            'label' => 'Direccion',
                                            'rules' => 'trim|required|min_length[5]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Email',
                                            'label' => 'Correo',
                                            'rules' => 'trim|valid_email|min_length[1]|max_length[40]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Telefono',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|min_length[6]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Limite_max_Credito',
                                            'label' => 'Limite Credito',
                                            'rules' => 'trim|min_length[4]|max_length[45]|strip_tags'
                                         ),

                    ),

                                       // final
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                'ajax_update_Producto' => array(
                                   array(
                                            'field' => 'Codigo',
                                            'label' => 'Código',
                                            'rules' => 'trim|required|min_length[3]|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Nombre',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|min_length[3]|max_length[40]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Marca',
                                            'label' => 'Marca',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Categoria',
                                            'label' => 'Categoria',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Cantidad_A',
                                            'label' => 'Almacen',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Unidad',
                                            'label' => 'Unidad',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Cantidad_D',
                                            'label' => 'Deposito',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                      array(
                                            'field' => 'Stock_Min',
                                            'label' => 'Minimo',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Precio_Costo',
                                            'label' => 'Precio Costo ',
                                            'rules' => 'trim|required|min_length[1]|max_length[30]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Porcentaje',
                                            'label' => 'Porcentaje Venta',
                                            'rules' => 'trim|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                      array(
                                            'field' => 'Precio_Venta',
                                            'label' => 'Precio Venta',
                                            'rules' => 'trim|required|min_length[1]|max_length[30]|strip_tags'
                                         ), 
                                      array(
                                            'field' => 'iva',
                                            'label' => 'Iva',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                      array(
                                            'field' => 'Medida',
                                            'label' => 'Medida',
                                            'rules' => 'trim|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                       array(
                                            'field' => 'Descuento',
                                            'label' => 'Descuento',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                        array(
                                            'field' => 'multi',
                                            'label' => 'Proveedor',
                                            'rules' => 'trim|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                           array(
                                            'field' => 'Descripcion',
                                            'label' => 'Descripcion',
                                            'rules' => 'trim|max_length[100]|strip_tags'
                                         ),
                                    

                    ),

                                       // final
/////////////////////////////////////////////////////////////////////////////////////
       'registro_Categoria' => array(
                                                   array(
                                            'field' => 'Categoria',
                                            'label' => 'Categoria',
                                            'rules' => 'trim|required|callback_check_Cate|min_length[3]|max_length[100]|strip_tags'
                                         ),
                    ),
      'update_Categoria' => array(
                                           array(
                                    'field' => 'Categoria',
                                    'label' => 'Categoria',
                                    'rules' => 'trim|required|min_length[3]|max_length[100]|strip_tags'
                                 ),
            ),

                                       // final
/////////////////////////////////////////////////////////////////////////////////////
                    'ajax_update_Producto' => array(
                                    array(
                                            'field' => 'Codigo',
                                            'label' => 'Código',
                                            'rules' => 'trim|required|min_length[3]|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Nombre',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|min_length[3]|max_length[40]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Marca',
                                            'label' => 'Marca',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Categoria',
                                            'label' => 'Categoria',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Cantidad',
                                            'label' => 'Cantidad',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Precio_Costo',
                                            'label' => 'Precio Costo ',
                                            'rules' => 'trim|required|min_length[1]|max_length[30]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Porcentaje',
                                            'label' => 'Porcentaje Venta',
                                            'rules' => 'trim|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                      array(
                                            'field' => 'Precio_Venta',
                                            'label' => 'Precio Venta',
                                            'rules' => 'trim|required|min_length[1]|max_length[30]|strip_tags'
                                         ), 
                                      array(
                                            'field' => 'iva',
                                            'label' => 'Iva',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                       array(
                                            'field' => 'Descuento',
                                            'label' => 'Descuento',
                                            'rules' => 'trim|max_length[30]|strip_tags'
                                         ),
                                        // array(
                                        //     'field' => 'multi[]',
                                        //     'label' => 'Proveedor',
                                        //     'rules' => 'trim|required|min_length[1]|max_length[45]|strip_tags'
                                        //  ),
                                           array(
                                            'field' => 'Descripcion',
                                            'label' => 'Descripcion',
                                            'rules' => 'trim|max_length[100]|strip_tags'
                                         ),

                    ),
/////////////////////////////////////////////Validacion egistro_cliente///////////////////////////////////////////////////////////////////////
                'registro_Proveedor' => array(
                                    array(
                                            'field' => 'Razon_Social',
                                            'label' => 'Razon Social',
                                            'rules' => 'trim|required|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Vendedor',
                                            'label' => 'Vendedor',
                                            'rules' => 'trim|required|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Direccion',
                                            'label' => 'Direccion',
                                            'rules' => 'trim|required|min_length[5]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Correo',
                                            'label' => 'Correo',
                                            'rules' => 'trim|valid_email|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Ruc',
                                            'label' => 'Ruc',
                                            'rules' => 'trim|required|callback_check_ruc|min_length[6]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Telefono',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|required|min_length[6]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Pagina_Web',
                                            'label' => 'Pagina Web',
                                            'rules' => 'trim|valid_url|min_length[6]|max_length[45]|strip_tags'
                                         ),

                    ),

                                       // final
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Validacion update_cliente///////////////////////////////////////////////////////////////////////
                'ajax_update_Proveedor' => array(
                                    array(
                                            'field' => 'Razon_Social',
                                            'label' => 'Razon Social',
                                            'rules' => 'trim|required|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Vendedor',
                                            'label' => 'Vendedor',
                                            'rules' => 'trim|required|min_length[3]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Direccion',
                                            'label' => 'Direccion',
                                            'rules' => 'trim|required|min_length[5]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Correo',
                                            'label' => 'Correo',
                                            'rules' => 'trim|valid_email|min_length[1]|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Ruc',
                                            'label' => 'Ruc',
                                            'rules' => 'trim|required|min_length[6]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Telefono',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|required|min_length[6]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Pagina_Web',
                                            'label' => 'Pagina Web',
                                            'rules' => 'trim|valid_url|min_length[6]|max_length[45]|strip_tags'
                                         ),

                    ),
                                       // final
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Validacion caaaaaaaaaaaarrito servicio/////////////////////////////////////////////////
                 'agregar_carrito_serv' => array(

                                    array(
                                            'field' => 'id_articulo',
                                            'label' => 'Articulo',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'cantidad',
                                            'label' => 'cantidad',
                                            'rules' => 'trim|required|min_length[1]|max_length[5]|strip_tags'
                                         ),

                     ),
/////////////////////////////////////////////Validacion Marca/////////////////////////////////////////////////
                'registro_marca' => array(

                                    array(
                                            'field' => 'Marca',
                                            'label' => 'Marca',
                                            'rules' => 'trim|required|callback_check_marca|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Descripcion',
                                            'label' => 'Descripcion',
                                            'rules' => 'trim|min_length[1]|max_length[50]|strip_tags'
                                         ),

                     ),
                'update_marca' => array(

                                    array(
                                            'field' => 'Marca',
                                            'label' => 'Marca',
                                            'rules' => 'trim|required|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Descripcion',
                                            'label' => 'Descripcion',
                                            'rules' => 'trim|max_length[50]|strip_tags'
                                         ),

                     ),
/////////////////////////////////////////////Validacion Permiso/////////////////////////////////////////////////
                'registro_Permiso' => array(

                                    array(
                                            'field' => 'Nombre',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|callback_check_permiso|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Oservacion',
                                            'label' => 'Descripcion',
                                            'rules' => 'trim|min_length[1]|max_length[50]|strip_tags'
                                         ),

                     ),
                'update_Permiso' => array(

                                    array(
                                            'field' => 'Nombre',
                                            'label' => 'Marca',
                                            'rules' => 'trim|required|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Oservacion',
                                            'label' => 'Descripcion',
                                            'rules' => 'trim|max_length[50]|strip_tags'
                                         ),

                     ),  
/////////////////////////////////////////////Validacion caaaaaaaaaaaarrito servicio///////////////////////////////////////////////////////////////////////
                 'agregar_carrito' => array(

                                    array(
                                            'field' => 'idProducto_Servicio',
                                            'label' => 'Articulo',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Cantidad',
                                            'label' => 'cantidad',
                                            'rules' => 'trim|required|numeric|min_length[1]|max_length[5]|strip_tags'
                                         ),

                     ),
                'add_presupuesto' => array(

                                    array(
                                            'field' => 'idCliente',
                                            'label' => 'Cliente',
                                            'rules' => 'trim|required|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Fecha_Pre_Arqui',
                                            'label' => 'Fecha Alquiler',
                                            'rules' => 'trim|required|min_length[1]|max_length[50]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Fecha_Devolucion',
                                            'label' => 'Fecha Devolucion',
                                            'rules' => 'trim|required|min_length[1]|max_length[50]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Nombres_servicios',
                                            'label' => 'Nombre Servicio',
                                            'rules' => 'trim|required|min_length[1]|max_length[50]|strip_tags'
                                         ),
                     ),

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    'registro_pagos' => array(

                                    array(
                                            'field' => 'Descripcion',
                                            'label' => 'Descripcion',
                                            'rules' => 'trim|required|alpha|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Monto',
                                            'label' => 'Monto',
                                            'rules' => 'trim|numeric|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                        array(
                                            'field' => 'Tipos_Pagos',
                                            'label' => 'Tipo Pagos',
                                            'rules' => 'trim|required|min_length[1]|max_length[50]|strip_tags'
                                         ),

                     ),
                'registro_pagos_1' => array(

                                    array(
                                            'field' => 'idEmpleado',
                                            'label' => 'Empleado',
                                            'rules' => 'trim|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Monto',
                                            'label' => 'Monto',
                                            'rules' => 'trim|required|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                        array(
                                            'field' => 'Tipos_Pagos',
                                            'label' => 'Tipo Pagos',
                                            'rules' => 'trim|required|min_length[1]|max_length[50]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Descripcion',
                                            'label' => 'Descripcion',
                                            'rules' => 'trim|required|min_length[5]|max_length[50]|strip_tags'
                                         ),
                     ),
            ////////////////////////////////////////////////////////////////////////////
            'cobrar_credito' => array(
                                    array(
                                            'field' => 'Descripcion',
                                            'label' => 'Descripcion',
                                            'rules' => 'trim|required|min_length[5]|max_length[50]|strip_tags'
                                         ),
            ),



/////////////////////////////////////////////Validacion registro_empresa///////////////////////////////////////////////////////////////////////
                'registro_empresa' => array(

                                    array(
                                            'field' => 'Nombre',
                                            'label' => 'Nombre Empresa',
                                            'rules' => 'trim|required|min_length[2]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Comprovante',
                                            'label' => 'Comprobante',
                                            'rules' => 'trim|required|min_length[2]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Direccion',
                                            'label' => 'Direccion',
                                            'rules' => 'trim|required|min_length[2]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                        'field' => 'ruc',
                                        'label' => 'CI RUC',
                                        'rules' => 'trim|required|min_length[4]|max_length[45]|strip_tags'
                                     ),
                                    array(
                                            'field' => 'Telefono',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|required|min_length[2]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Email',
                                            'label' => 'Correo',
                                            'rules' => 'trim|required|valid_email|min_length[1]|max_length[30]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Timbrado',
                                            'label' => 'Timbrado',
                                            'rules' => 'trim|min_length[2]|max_length[20]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Series',
                                            'label' => 'Series',
                                            'rules' => 'trim|min_length[2]|max_length[20]|strip_tags'
                                         ),

                    ),

                                       // final
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Validacion ajax_update_empresa///////////////////////////////////////////////////////////////////////
                'ajax_update_empresa' => array(


                                    array(
                                            'field' => 'Nombre',
                                            'label' => 'Nombre Empresa',
                                            'rules' => 'trim|required|min_length[2]|max_length[40]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Direccion',
                                            'label' => 'Direccion',
                                            'rules' => 'trim|required|min_length[2]|max_length[50]|strip_tags'
                                         ),
                                            array(
                                            'field' => 'ruc',
                                            'label' => 'CI RUC',
                                            'rules' => 'trim|required|min_length[4]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Telefono',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|required|min_length[2]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Email',
                                            'label' => 'Correo',
                                            'rules' => 'trim|required|valid_email|min_length[1]|max_length[40]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Timbrado',
                                            'label' => 'Timbrado',
                                            'rules' => 'trim|min_length[2]|max_length[20]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'Series',
                                            'label' => 'Series',
                                            'rules' => 'trim|min_length[2]|max_length[20]|strip_tags'
                                         ),
                                      array(
                                            'field' => 'credito',
                                            'label' => 'Limite de Credito',
                                            'rules' => 'trim|min_length[2]|max_length[20]|strip_tags'
                                         ),

                    ),

                                       // final
///////////////////////////////////////CAJA////////////////////////////////////////////////////////////////
                 'abrir_Cerrar_Caja' => array(

                                    array(
                                            'field' => 'inicio',
                                            'label' => 'Monto',
                                            'rules' => 'trim|required|min_length[1]|max_length[19]|strip_tags'
                                         ),
                  ),
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
                 'update_rowid' => array(

                                    array(
                                            'field' => 'qty',
                                            'label' => 'Cantidad',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                  ),
                 'update2_rowid' => array(

                                    array(
                                            'field' => 'qty',
                                            'label' => 'Cantidad',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'price',
                                            'label' => 'Precio',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                  ),
///////////////////////////////////////////add_orden_compra////////////////////////////////////////////////////
                 'add_orden_compra' => array(

                                    array(
                                            'field' => 'observacobservac',
                                            'label' => 'Observacion',
                                            'rules' => 'trim|min_length[1]|max_length[50]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Entrega',
                                            'label' => 'Entrega',
                                            'rules' => 'trim|min_length[1]|max_length[20]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Devolucion',
                                            'label' => 'Devolucion',
                                            'rules' => 'trim|min_length[1]|max_length[20]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Estado',
                                            'label' => 'Estado',
                                            'rules' => 'trim|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'changeprove',
                                            'label' => 'Proveedor',
                                            'rules' => 'trim|required|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                ),
///////////////////////////////////////////update_orden_compra////////////////////////////////////////////////////
                 'insercliente' => array(

                                    array(
                                            'field' => 'nom',
                                            'label' => 'Nombre',
                                            'rules' => 'trim|required|min_length[3]|max_length[40]|strip_tags'
                                         ),

                                    array(
                                            'field' => 'telefon',
                                            'label' => 'Telefono',
                                            'rules' => 'trim|min_length[3]|max_length[40]|strip_tags'
                                         ),

array(
    'field' => 'ruc',
    'label' => 'Ruc O CI',
    'rules' => 'trim|required|alpha_dash|min_length[6]|max_length[20]|callback_check_ruc'
),
array(
    'field' => 'credito',
    'label' => 'Limite Credito',
    'rules' => 'trim|min_length[4]|max_length[45]|strip_tags'
 ),




                  ),
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                 'add_Orden_venta' => array(

                                    array(
                                            'field' => 'observac',
                                            'label' => 'Observacion',
                                            'rules' => 'trim|min_length[1]|max_length[50]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Entrega',
                                            'label' => 'Entrega',
                                            'rules' => 'trim|min_length[1]|max_length[20]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Devolucion',
                                            'label' => 'Devolucion',
                                            'rules' => 'trim|min_length[1]|max_length[20]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Estado',
                                            'label' => 'Estado',
                                            'rules' => 'trim|min_length[1]|max_length[45]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'changecliente',
                                            'label' => 'Cliente',
                                            'rules' => 'trim|required|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'Envio_m',
                                            'label' => 'Monto Envio',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                ),
///////////////////////////////////////////////////////////////////////////////////////////  
                 'pagar_todo' => array(

                                    array(
                                            'field' => 'efectivo',
                                            'label' => 'Efectivo',
                                            'rules' => 'trim|min_length[1]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'cuenta',
                                            'label' => 'Cuenta',
                                            'rules' => 'trim|min_length[1]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'numcheque',
                                            'label' => 'Numero de Cheque',
                                            'rules' => 'trim|min_length[1]|max_length[20]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'importe',
                                            'label' => 'importe',
                                            'rules' => 'trim|min_length[1]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'fecha_pago',
                                            'label' => 'fecha',
                                            'rules' => 'trim|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'cuenta_bancaria',
                                            'label' => 'Cuenta',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'ch_o',
                                            'label' => 'Cheque Tercero',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'inputmontopagar',
                                            'label' => 'Total a pagar',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'inputdiferencia',
                                            'label' => 'Diferencia',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[1]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'agremicuenta',
                                            'label' => 'Mi cuenta agregar',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[15]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'agustar',
                                            'label' => 'Ajustar',
                                            'rules' => 'trim|min_length[1]|max_length[15]|strip_tags'
                                         ),
                ),


                 'pagar_parcial' => array(

                                    array(
                                            'field' => 'efectivo',
                                            'label' => 'Efectivo',
                                            'rules' => 'trim|min_length[1]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'cuenta',
                                            'label' => 'Cuenta',
                                            'rules' => 'trim|min_length[1]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'numcheque',
                                            'label' => 'Numero de Cheque',
                                            'rules' => 'trim|min_length[1]|max_length[20]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'importe',
                                            'label' => 'importe',
                                            'rules' => 'trim|min_length[1]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'fecha_pago',
                                            'label' => 'fecha',
                                            'rules' => 'trim|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'cuenta_bancaria',
                                            'label' => 'Cuenta',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'ch_o',
                                            'label' => 'Cheque Tercero',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'inputmontopagar',
                                            'label' => 'Total a pagar',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'inputdiferencia',
                                            'label' => 'Diferencia',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[20]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'agremicuenta',
                                            'label' => 'Mi cuenta agregar',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[15]|strip_tags'
                                         ),
                                     array(
                                            'field' => 'agustar',
                                            'label' => 'Ajustar',
                                            'rules' => 'trim|min_length[1]|max_length[15]|strip_tags'
                                         ),
                ),

////////////////////////////////////////////comprar////////////////////////////////////////////////////////////////////////////////
                 'Comprar' => array(

                                    array(
                                            'field' => 'proveedor',
                                            'label' => 'Proveedor',
                                            'rules' => 'trim|numeric|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'comprobante',
                                            'label' => 'Numero',
                                            'rules' => 'trim|callback_check_num|required|min_length[1]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'orden',
                                            'label' => 'Orden',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'finalcarrito',
                                            'label' => 'Monto Final',
                                            'rules' => 'trim|numeric|required|min_length[1]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'montofinal',
                                            'label' => 'Monto',
                                            'rules' => 'trim|matches[finalcarrito]|numeric|required|min_length[1]|max_length[15]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'tipoComprovante',
                                            'label' => '',
                                            'rules' => 'trim|numeric|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'fecha',
                                            'label' => 'Fecha',
                                            'rules' => 'trim|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'inicial',
                                            'label' => 'Carga',
                                            'rules' => 'trim|numeric|required|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'condicion',
                                            'label' => 'Pago',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[11]|strip_tags'
                                         ),
                                    array(
                                            'field' => 'cuotas',
                                            'label' => 'cuotas',
                                            'rules' => 'trim|numeric|required|min_length[1]|max_length[2]|strip_tags'
                                         ),
                                   array(
                                            'field' => 'cart_total',
                                            'label' => 'Total',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[15]|strip_tags'
                                   ),
                                   array(
                                            'field' => 'descuento',
                                            'label' => 'Descuento',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[15]|strip_tags|limite[cart_total]'
                                   ),
                                   array(
                                            'field' => 'fletes',
                                            'label' => 'Fletes',
                                            'rules' => 'trim|numeric|min_length[1]|max_length[15]|strip_tags|limite[cart_total]'
                                         ),
                                     array(
                                            'field' => 'observaciones',
                                            'label' => 'observaciones',
                                            'rules' => 'trim|min_length[1]|max_length[50|strip_tags'
                                         ),
                ),
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    'produccion' => [
        ['field' => 'proveedor', 'label' => 'Proveedor', 'rules' => 'min_length[1]|max_length[15]'],
        ['field' => 'idProduct', 'label' => 'Productos', 'rules' => $required_rules . '|min_length[1]|max_length[15]'],
        ['field' => 'cantidad_producir', 'label' => 'cantidad', 'rules' => $common_rules . '|min_length[1]|max_length[15]'],
        ['field' => 'fecha_produccion', 'label' => 'fecha', 'rules' => $common_rules . '|min_length[16]|max_length[16]'],
        ['field' => 'estado_produccion', 'label' => 'estado', 'rules' => $required_rules . '|numeric|min_length[1]|max_length[1]'],
        ['field' => 'responsable_produccion', 'label' => 'responsable', 'rules' => $common_rules . '|min_length[1]|max_length[45]'],
        ['field' => 'tiempo_produccion', 'label' => 'tiempo', 'rules' => 'min_length[5]|max_length[5]'],
        ['field' => 'lotes', 'label' => 'lotes', 'rules' => $required_rules . '|numeric|min_length[1]|max_length[2]'],
    ],
    'registro_Plan' => [
        ['field' => 'Codigo', 'label' => 'Codigo', 'rules' => $required_rules . '|callback_check_codigo|min_length[3]|max_length[40]'],
        ['field' => 'nombre', 'label' => 'Nombre', 'rules' => $required_rules . '|min_length[3]|max_length[100]'],
    ],

                                       // final
/////////////////////////////////////////////////////////////////////////////////////













);




