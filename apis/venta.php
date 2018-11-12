<?php
	//allow access
	header('Access-Control-Allow-Access: *');
	//allow methods
	header('Access-Control-Allow-Methods: GET, DELETE');
	//allow headers
	
	//use user class
	require_once($_SERVER['DOCUMENT_ROOT'].'/ventaComputadoras/models/venta.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/ventaComputadoras/models/detalle.php');

	if ($_SERVER['REQUEST_METHOD'] == 'GET') 
	{
		//one object
		if (isset($_GET['id'])) 
		{
			try 
			{
				//create object
				$v = new Venta($_GET['id']);
				//display
				echo json_encode(array(
					'status' => 0,
					'venta' => json_decode($v->toJson())
				));
			}
			catch(RecordNotFoundException $ex) {
				echo json_encode(array(
					'status' => 1,
					'errorMessage' => $ex->get_message()
				));
			}
		}
		else if(isset($_GET['idAll']))
		{
			echo Detalle::getAllJson($_GET['idAll']);
		}
		else if (isset($_GET['delete'])) 
		{
			try 
			{
				//create object
				$v = new Venta($_GET['delete']);
				$v->delete();
				echo json_encode(array(
					'status' => 0,
					'errorMessage' => "Venta eliminada satisfactoriamente"
				));
			}
			catch(RecordNotFoundException $ex) 
			{
				echo json_encode(array(
					'status' => 2,
					'errorMessage' => $ex->get_message()
				));
			}
			
		}
		else 
		{
			echo Venta::getAllJson();
		}
	}


?>