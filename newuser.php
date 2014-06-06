<? session_start(); ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
    <!-- Loading Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<style>
		
		.jumbotron{
			background: white;
			border: 1px solid rgb(221, 221, 221);
			margin-top: -1px;
		}

	</style>
</head>
<body>
	<header class="container">
		<div  class="col-md-offset-4 col-md-4">
			<img src="img/logo-latch.png" alt="" width="100%">
			<h2 align="justify">Ejemplo de inicio de sesion y Latch con el SDK de PHP</h2>	
		</div>
		<div class="col-md-offset-4 col-md-4">
			<hr>
			<hr>
		</div>
	</header>
	<article class="container">
		<div class="col-md-offset-4 col-md-4">
			<ul class="nav nav-tabs">
				<li><a href="index.php">Iniciar sesion</a></li>
			  	<li class="active"><a href="newuser.php">Registrar</a></li>
			</ul>
		</div>
		<div class="col-md-offset-4 col-md-4 jumbotron">
			<h3>Formulario de registro</h3>
			<form action="funciones.php" method="post">
				<div class="form-group">
					<label for="nombre">Introduce tu nombre</label>
					<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre">
				</div>
				<div class="form-group">
					<label for="email">Introduce tu email</label>
					<input type="text" class="form-control" name="email" id="email" placeholder="Email">
				</div>
				<div class="form-group">
					<label for="usuario">Introduce tu nombre de usuario</label>
					<input type="text" class="form-control" name="usuario" id="usuario" placeholder="Nombre de usuario">
				</div>
				<div class="form-group">
					<label for="password">Introduce tu password</label>
					<input type="password" class="form-control" name="password" id="password" placeholder="Password">
				</div>
				<button type="submit" name="newUser" class="btn btn-success">Registrar</button>
			</form>
			<br/>
			<? 
				if( isset( $_SESSION['error'] ) ) echo '<h5>'.$_SESSION['error'].'</h5>'; 
				unset($_SESSION['error']);
			?>

		</div>
	</article>

</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
<? session_destroy(); ?>