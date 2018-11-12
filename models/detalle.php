<?php
	
	require_once('sqlsrvconnection.php');
	require_once('venta.php');
	require_once('product.php');
	require_once('employee.php');
	require_once('exceptions/recordnotfoundexception.php');

	/**
	* 
	*/
	class Detalle
	{
		private $id;
		private $producto;
		private $venta;
		private $vendedor;
		private $costoTotal;
		private $cantidad;

		
		function __construct()
		{
			if (func_num_args() == 0) 
			{
				$this->id = 0;
				$this->producto = new Product();
				$this->venta = new Venta();
				$this->vendedor = new Employee();
				$this->costoTotal = 0;

			}

			if (func_num_args() == 6) 
			{
				$arguments = func_get_args();
				$this->id = $arguments[0];
				$this->producto = $arguments[1];
				$this->venta = new Venta($arguments[2]);
				$this->vendedor = new Employee($arguments[3]);
				$this->costoTotal = $arguments[4];
				$this->cantidad = $arguments[5];
			}
		}

		public function toJson()
		{
			return json_encode(array(
				'id' => $this->id,
				'product' => json_decode($this->producto->toJson()),
				'venta' => json_decode($this->venta->toJson()),
				'vendedor' => json_decode($this->vendedor->toJson()),
				'costoTotal' => $this->costoTotal,
				'cantidad' => $this->cantidad

			));
		}

		//Todas los productos de una venta
		public static function getAll($venta)
		{
			$list = array();
			//get connection
			$connection = SqlSrvConnection::getConnectionAdministrador();
			//query
			$query = 'SELECT de.id as id, mo.descripcion as descripcion, mo.nombre as producto, ma.nombre as marca, em.control as empleado, v.id as venta, de.cantidad as qty, de.precio as precio, (de.cantidad * de.precio) as costoTotal
				FROM ventas.detalle as de
				inner join catalogo.modelos as mo on de.producto = mo.id
				inner join catalogo.marcas as ma on ma.id = mo.marca
				inner join rh.empleados as em on em.control = de.vendedor
				inner join ventas.ventas as v  on v.id = de.venta
				where de.venta = ?';
			$params = array($venta);
			$command = sqlsrv_query($connection, $query, $params);
			while($detalle = sqlsrv_fetch_array($command))
            {
            	$product = new Product($detalle['marca'], $detalle['producto'], $detalle['precio'], $detalle['descripcion']);
				array_push($list, new Detalle($detalle['id'], $product, $detalle['venta'], $detalle['empleado'], $detalle['costoTotal'],$detalle['qty']));
            } /*While*/
            sqlsrv_free_stmt($command);
            sqlsrv_close($connection);
			//list
			return $list;
		}//getAll

		public static function getAllJson($venta)
		{
			//list
			$list = array();
			//encode to json
			foreach (self::getAll($venta) as $item)
			{
				array_push($list, json_decode($item->toJson()));
			}//foreach
			return json_encode(array('detalle' => $list));
		}
	}

?>