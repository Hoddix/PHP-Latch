<?php session_start(); ?>
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
		<section>
			<div class="col-md-offset-4 col-md-4">
				<ul class="nav nav-tabs">
				  	<li class="active"><a href="index.php">Iniciar sesion</a></li>
				  	<li><a href="newuser.php">Registrar</a></li>
				</ul>
			</div>
			<div class="col-md-offset-4 col-md-4 jumbotron">
				<form action="funciones.php" method="post">
					<label>Email address</label>
					<div class="input-group">
					  	<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
					  	<input type="text" name="usuario" class="form-control" placeholder="Username">
					</div>
					<label>Password</label>
					<div class="input-group">
					  	<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
					  	<input type="text" name="password" class="form-control" placeholder="Password">
					</div>
					<br/>
					<button type="submit" name="iniciar" class="btn btn-success">Iniciar sesion</button>
					<button type="reset" class="btn btn-danger">Borrar</button>
				</form>	
				<br/>

				<? 
					if( isset( $_SESSION['error'] ) ) echo $_SESSION['error']; 
					unset($_SESSION['error']);
				?>

			</div>
		</section>
	</article>

</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
<?php session_destroy(); ?>