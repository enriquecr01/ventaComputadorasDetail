<?php

/**
* 
*/
class Product
{
	private $marca;
	private $modelo;
	private $precio;
	private $descripcion;
	
	function __construct()
	{
		if (func_num_args() == 0) 
		{
			$this->marca = '';
			$this->modelo = '';
			$this->precio = 0;
			$this->descripcion = '';
		}

		if (func_num_args() == 4) 
		{
			$arguments = func_get_args();
			$this->marca = $arguments[0];
			$this->modelo = $arguments[1];
			$this->precio = $arguments[2];
			$this->descripcion = $arguments[3];
		}
	}


	public function toJson()
	{
		return json_encode(array(
			'marca' => $this->marca,
			'modelo' =>$this->modelo,
			'precio' => $this->precio,
			'descripcion' => $this->descripcion
		));
	}
}

?>