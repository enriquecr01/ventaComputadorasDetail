function login() 
{
	  // Create request
	  var x = new XMLHttpRequest();
	  // Prepare request
	  x.open('GET', 'http://localhost:8080/ventaComputadoras/apis/login.php', true);
	  x.setRequestHeader('user', document.getElementById('user').value);
	  x.setRequestHeader('password', document.getElementById('password').value);
	  // Send request
	  x.send();

	  // Handle readyState change event
	  x.onreadystatechange = function() 
	  {
	    // check status
	    //status : 200=OK, 404=Page not found, 500=server denied access
	    // readyState : 4=Back with data
	    if (x.status == 200 && x.readyState == 4) 
	    {
	    	var JSONdata = JSON.parse(x.responseText); 
	      	if (JSONdata.status == 0) 
	      	{
	      		sessionStorage.authenticated = true;
				sessionStorage.userId = JSONdata.user.empleado.control ;
				sessionStorage.userName = JSONdata.user.empleado.nombre;
				sessionStorage.role = JSONdata.user.role.name;
				sessionStorage.token = JSONdata.token;
				//alert(sessionStorage.userName);
				if (sessionStorage.role == 'Vendedor') { window.location = 'client/indexVendedor.php'; }
				else { window.location = 'client/indexAdminisitrador.php'; }
	      	}
	      	else
	      	{
	      		//document.getElementById('message').innerHTML = JSONdata.errorMessage;
	      		ejemplo('ERROR', 300, 300);
	      	}
	    }
	  }
}

function ejemplo(titulo, ancho, altura)
{
	var popup = new PopupWindow(titulo, ancho, altura);
	popup.show();
}
