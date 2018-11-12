<?php 
	include_once('../models/producto.php');
	include_once('../models/carrito.php');
	$product = new Product();
	$cart = new Cart();
	if(isset($_GET['action'])){
		switch ($_GET['action']){
			case 'add':
				$cart->add_item($_GET['code'], $_GET['amount']);
			break;
			case 'remove':
				$cart->remove_item($_GET['code']);
			break;
			case 'sell':
				$cart->addDetalleVenta($_GET['code']);
				break;
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Carrito de compras</title>
	<script type="text/javascript" src="js/functions.js"></script>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body onload="init()">
	<div class="topnav" id="myTopnav">
  		<a class="active" href="#home" id="name"></a>
  		<a  id="role">News</a>
  		<a onclick="logout()">Logout</a>
	</div>
	<div class="content">
		<br><br>
		<table border="1px" cellpadding="5px" width="100%" class="derecho">
			<thead class="productsHeader">
				<tr>
					<th colspan="6">LISTA DE PRODUCTOS</th>
				</tr>
				<tr>
					<th>Codigo</th>
					<th>Producto</th>
					<th>Descripcion</th>
					<th>Precio</th>
					<th>Cantidad</th>
					<th>Opcion</th>
				</tr>
			</thead>
			<tbody class="productsBody">
				<?=$product->get_products();?>
			</tbody>
		</table>

		<table border="1px" cellpadding="5px" width="100%" class="izquierdo">
			<thead class="cartHeader" display="off">
				<tr>
					<th colspan="6">MI CARRITO DE COMPRAS</th>
				</tr>
				<tr>
					<th colspan="3">Total pagar: <?=$cart->get_total_payment();?></th>
					<th colspan="3">Total items: <?=$cart->get_total_items();?></th>
				</tr>
			</thead>
			<tbody class="cartBody">
				<tr>
					<th>Codigo</th>
					<th>Producto</th>
					<th>Precio</th>
					<th>Cantidad</th>
					<th>Subtotal</th>
					<th>Opcion</th>
				</tr>
				<?=$cart->get_items();?>
			</tbody>
		</table>
	</div>
	<input type="hidden" id="sell">
	<button class="izquierdoButton" onclick="vender()">Vender</button>
</body>
</html>