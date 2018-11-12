<?php
	
	require_once('sqlsrvconnection.php');
	require_once('exceptions/recordnotfoundexception.php');

	/**
	* Venta
	*/
	class Venta
	{
		
		private $id;
		private $fecha;
		private $status;
		private $total;

		function __construct()
		{
			if (func_get_args() == 0) 
			{
				$this->id = 0;
				$this->fecha = '';
				$this->status = '';
			}//if 0

			if (func_num_args() == 1) 
			{
				//get connection
				$connection = SqlSrvConnection::getConnectionAdministrador();
				$id = func_get_arg(0);
				//query
				$query = 'select ID, fecha, status 
							from ventas.ventas
							where id = ?';
				$params = array($id);
				$command = sqlsrv_query($connection, $query, $params);
				$found = sqlsrv_has_rows($command);
				if ($found) 
				{
					while($venta = sqlsrv_fetch_array($command))
	            	{
						$this->id = $venta['ID'];
						$this->fecha = $venta['fecha'];
						$this->status = $venta['status'];
	            	} /*While*/	
				}
				else throw new RecordNotFoundException;
				sqlsrv_free_stmt($command);
		        sqlsrv_close($connection);
			}

			if (func_num_args() == 3) 
			{
				$arguments = func_get_args();
				$this->id = $arguments[0];
				$this->total = $arguments[1];
				$this->fecha = $arguments[2];
			}

			/*
			if (func_num_args() == 4) 
			{
				$arguments = func_get_args();
				$this->id = $arguments[0];
				$this->fecha = $arguments[1];
				$this->status = $arguments[2];
			}
			*/
		}//Constructor




		public static function addVenta()
		{
			//get connection
			$connection = SqlSrvConnection::getConnectionVendedor();
			//query
			$query = 'INSERT INTO ventas.ventas(fecha) VALUES(GETDATE());';
			$command = sqlsrv_query($connection, $query);
			//$respuesta = sqlsrv_fetch_array($command);
			//echo "ya llegue";
			sqlsrv_free_stmt($command);
	        sqlsrv_close($connection);
		}

		public function delete()
		{
			$connection = SqlSrvConnection::getConnectionAdministrador();
			//query
			$query = "UPDATE ventas.ventas SET status = 'DW' where id = ?";
			//echo $this->id;
			$params = array($this->id);
			$command = sqlsrv_query($connection, $query, $params);
			//$respuesta = sqlsrv_fetch_array($command);
			//echo "ya llegue";
			sqlsrv_free_stmt($command);
	        sqlsrv_close($connection);
		}



		public static function getLastId()
		{
			//get connection
			$connection = SqlSrvConnection::getConnectionAdministrador();
			//query
			$query = 'SELECT top 1 id from ventas.ventas order by id desc;';
			$command = sqlsrv_query($connection, $query);
			while($id = sqlsrv_fetch_array($command))
            {
				$lastId = $id['id'];
            } /*While*/
			$respuesta = sqlsrv_fetch_array($command);
			sqlsrv_free_stmt($command);
	        sqlsrv_close($connection);
	        return $lastId;
		}

		public function toJson()
		{
			return json_encode(array(
				'id' => $this->id,
				'fecha' => $this->fecha,
				'status' => $this->status,
				'total' => $this->total

			));
		}


		//Obtener todas las ventas 
		public static function getAll()
		{
			$list = array();
			//get connection
			$connection = SqlSrvConnection::getConnection();
			//query
			$query = "SELECT de.venta, v.fecha, SUM((de.cantidad * de.precio)) as costoTotal
						FROM ventas.detalle as de
						inner join ventas.ventas as v  on v.id = de.venta
						inner join rh.empleados as e on e.control = de.vendedor
						where v.status = 'UP'
						GROUP BY de.venta, v.fecha";
			$command = sqlsrv_query($connection, $query);
			while($ventas = sqlsrv_fetch_array($command))
            {
				array_push($list, new Venta($ventas['venta'], $ventas['costoTotal'], $ventas['fecha']));
            } /*While*/
            sqlsrv_free_stmt($command);
            sqlsrv_close($connection);
			//list
			return $list;
		}//getAll

		public static function getAllJson()
		{
			//list
			$list = array();
			//encode to json
			foreach (self::getAll() as $item)
			{
				array_push($list, json_decode($item->toJson()));
			}//foreach
			return json_encode(array('ventas' => $list));
		}






	}

?>