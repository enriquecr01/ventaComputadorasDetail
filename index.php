<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Inicio</title>
	<!--Style Sheets-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/popup.css">
	<!--Scripts-->
	<script  src="js/login.js"></script>
	<script  src="js/popupclass.js"></script>
	<script  src="js/popup.js"></script>
	<script  src="js/globals.js"></script>
</head>
<body>
	<div class="form">
		<div class="forceColor"></div>
		<div class="topbar">
			<div class="spanColor"></div>
			<input type="text" class="input" id="user" placeholder="Usuario"/>
			<input type="password" class="input" id="password" placeholder="Contraseña"/>
		</div>
		<button class="submit" id="submit" onclick="login()">Ingresar</button>
		<label id="message"></label>
	</div>
</body>
</html>
