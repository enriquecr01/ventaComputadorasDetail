function init()
{
	//alert(sessionStorage.userId);
	document.getElementById('sell').value = sessionStorage.userId;
	document.getElementById('name').innerHTML = sessionStorage.userName;
	document.getElementById('role').innerHTML = sessionStorage.role;
}

function addProduct(code)
{
	var amount = document.getElementById(code).value;
	window.location.href = 'index.php?action=add&code='+code+'&amount='+amount;
}

function deleteProduct(code)
{
	window.location.href = 'index.php?action=remove&code='+code;
}

function vender()
{
	var empleado = document.getElementById('sell').value;
	window.location.href = 'index.php?action=sell&code='+empleado;
}

function logout()
{
	sessionStorage.clear();
	window.location = '../index.php';
}

