function init()
{
	//alert(sessionStorage.userId);
	init2();
	document.getElementById('name').innerHTML = sessionStorage.userName;
	document.getElementById('role').innerHTML = sessionStorage.role;
	document.getElementById('sell').value = sessionStorage.userId;
}

function addProduct(code)
{
	var amount = document.getElementById(code).value;
	window.location.href = 'indexAdminisitrador.php?action=add&code='+code+'&amount='+amount;
}

function deleteProduct(code)
{
	window.location.href = 'indexAdminisitrador.php?action=remove&code='+code;
}

function vender()
{
	var empleado = document.getElementById('sell').value;
	window.location.href = 'indexAdminisitrador.php?action=sell&code='+empleado;
}

function logout()
{
	sessionStorage.clear();
	window.location = '../index.php';
}

function init2()
{
	console.log('Obteniendo ventas...');
	//create request
	var x = new XMLHttpRequest();
	//prepare request
	x.open('GET', 'http://localhost:8080/ventaComputadoras/apis/venta.php', true);
	//send request
	x.send();
	//handle readyState change event
	x.onreadystatechange = function() 
	{
		// check status
		// status : 200=OK, 404=Page not found, 500=server denied access
		// readyState : 4=Back with data
		if (x.status == 200 && x.readyState == 4) 
		{
			//show buildings
			mostrarVentas(x.responseText);
		}
	}
}

function mostrarVentas(data) 
{
	var tableDetalle = document.getElementById('tableDetalle');
	tableDetalle.innerHTML = '';

	//buildings element
	var table = document.getElementById('tableVentas');
	//clear
	table.innerHTML = '';
	//parse to JSON
	console.log(data);
	var JSONdata = JSON.parse(data); 
	//get buildings array
	var ventas = JSONdata.ventas; 
	//read buildings
	var tableHeader = document.createElement('thead');
	tableHeader.className = "productsHeader";
	var rowHeader = document.createElement('tr');
	var id = document.createElement('td');
	id.innerHTML = "ID de la venta";

	var fecha = document.createElement('td');
	fecha.innerHTML = "Fecha";

	var total = document.createElement('td');
	total.innerHTML = "Total de la venta";

	var accion = document.createElement('td');
	accion.innerHTML = "Acciones";
	

	rowHeader.appendChild(id);
	rowHeader.appendChild(total);
	rowHeader.appendChild(fecha);
	rowHeader.appendChild(accion);
	tableHeader.appendChild(rowHeader);
	table.appendChild(tableHeader);

	var tableBody = document.createElement('tbody');
	tableBody.className = "productsBody";

	for(var i = 0; i < ventas.length; i++) 
	{
		console.log(ventas[i]);
		//create row
		var row = document.createElement('tr');
		
		//create id cell
		var cellId = document.createElement('td');
		cellId.innerHTML = ventas[i].id;
		//create name cell
		var cellTotal = document.createElement('td');
		cellTotal.innerHTML = ventas[i].total;

		var cellFecha = document.createElement('td');
		cellFecha.innerHTML = ventas[i].fecha.date;
		
		var buttonActionView = document.createElement('button');
		var cellView = document.createElement('td');
		buttonActionView.innerHTML = "Mirar detalle";
		buttonActionView.setAttribute("onclick","getDetalle("+ventas[i].id+")")
		cellView.appendChild(buttonActionView);

		var buttonActionDelete = document.createElement('button');
		buttonActionDelete.innerHTML = "Eliminar venta";
		buttonActionDelete.setAttribute("onclick","deleteVenta("+ventas[i].id+")")
		cellView.appendChild(buttonActionDelete);

		//add cells to row
		row.appendChild(cellId);
		row.appendChild(cellTotal);
		row.appendChild(cellFecha);
		row.appendChild(cellView);
		//add row to table
		tableBody.appendChild(row);
		
	}//for
	table.appendChild(tableBody);
	
}

function getDetalle(id)
{
	window.scrollTo(0, 0);
	console.log('Obteniendo ventas...');
	//create request
	var x = new XMLHttpRequest();
	//prepare request
	x.open('GET', 'http://localhost:8080/ventaComputadoras/apis/venta.php?idAll='+id, true);
	//send request
	x.send();
	//handle readyState change event
	x.onreadystatechange = function() 
	{
		// check status
		// status : 200=OK, 404=Page not found, 500=server denied access
		// readyState : 4=Back with data
		if (x.status == 200 && x.readyState == 4) 
		{
			//show buildings
			//mostrarVentas(x.responseText);
			var table = document.getElementById('tableDetalle');
			//clear
			table.innerHTML = '';
			//parse to JSON
			console.log(x.responseText);
			var JSONdata = JSON.parse(x.responseText); 
			//get buildings array
			var detalle = JSONdata.detalle; 
			//read buildings
			var tableHeader = document.createElement('thead');
			tableHeader.className = "cartHeader";
			var rowHeader = document.createElement('tr');
			var id = document.createElement('td');
			id.innerHTML = "ID";

			var producto = document.createElement('td');
			producto.innerHTML = "Producto";

			var productoDescripcion = document.createElement('td');
			productoDescripcion.innerHTML = "Descripcion del producto";

			var fecha = document.createElement('td');
			fecha.innerHTML = "Fecha";

			var vendedor = document.createElement('td');
			vendedor.innerHTML = "Vendedor";

			var cantidad = document.createElement('td');
			cantidad.innerHTML = "Cantidad";

			var costoTotal = document.createElement('td');
			costoTotal.innerHTML = "Costo Total";

			var precio = document.createElement('td');
			precio.innerHTML = "Precio";

			rowHeader.appendChild(id);
			rowHeader.appendChild(producto);
			rowHeader.appendChild(productoDescripcion);
			rowHeader.appendChild(fecha);
			rowHeader.appendChild(vendedor);
			rowHeader.appendChild(precio);
			rowHeader.appendChild(cantidad);
			rowHeader.appendChild(costoTotal);

			tableHeader.appendChild(rowHeader);
			table.appendChild(tableHeader);

			var tableBody = document.createElement('tbody');
			tableBody.className = "cartBody";

			for(var i = 0; i < detalle.length; i++) 
			{
				console.log(detalle[i]);
				//create row
				var row = document.createElement('tr');
				//create id cell
				var cellId = document.createElement('td');
				cellId.innerHTML = detalle[i].id;
				//create name cell
				var cellProducto = document.createElement('td');
				cellProducto.innerHTML = detalle[i].product.marca + detalle[i].product.modelo;

				var cellProductoDescripcion = document.createElement('td');
				cellProductoDescripcion.innerHTML = detalle[i].product.descripcion;

				var cellFecha = document.createElement('td');
				cellFecha.innerHTML = detalle[i].venta.fecha.date;

				var cellVendedor = document.createElement('td');
				cellVendedor.innerHTML = detalle[i].vendedor.nombre + ' ' +detalle[i].vendedor.apPaterno;

				var cellPrecio = document.createElement('td');
				cellPrecio.innerHTML = detalle[i].product.precio;

				var cellCantidad = document.createElement('td');
				cellCantidad.innerHTML = detalle[i].cantidad;

				var cellCostoTotal = document.createElement('td');
				cellCostoTotal.innerHTML = detalle[i].costoTotal;

				//add cells to row
				row.appendChild(cellId);
				row.appendChild(cellProducto);
				row.appendChild(cellProductoDescripcion);
				row.appendChild(cellFecha);
				row.appendChild(cellVendedor);
				row.appendChild(cellPrecio);
				row.appendChild(cellCantidad);
				row.appendChild(cellCostoTotal);
				//add row to table
				tableBody.appendChild(row);
				
			}//for
			table.appendChild(tableBody);
		}
	}
}

function deleteVenta(code)
{
		console.log('Obteniendo ventas...');
	//create request
	var x = new XMLHttpRequest();
	//prepare request
	x.open('GET', 'http://localhost:8080/ventaComputadoras/apis/venta.php?delete='+code, true);
	//send request
	x.send();
	//handle readyState change event
	x.onreadystatechange = function() 
	{
		// check status
		// status : 200=OK, 404=Page not found, 500=server denied access
		// readyState : 4=Back with data
		if (x.status == 200 && x.readyState == 4) 
		{
			var JSONdata = JSON.parse(x.responseText); 
			if (JSONdata.status == 0) 
			{
				alert(JSONdata.errorMessage);
				init2();
			}
			else
			{
				alert(JSONdata.errorMessage);
			}
		}
	}

}