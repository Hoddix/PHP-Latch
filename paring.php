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
				<li class="active"><a href="useraccount.php">Mi cuenta</a></li>
			  	<li><a href="index.php">Cerrar sesion</a></li>
			</ul>
		</div>
		<div class="col-md-offset-4 col-md-4 jumbotron">

			<h5>Has iniciado sesion como <? echo $_SESSION['nombre']; ?></h5>
			
			<form action="funciones.php" method="post">
				<div class="form-group">
					<img src="img/logo-latch.png" alt="" width="50%">
				</div>
				<div class="form-group">
					<div class="input-group">
					  	<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
					  	<input type="text" class="form-control" name="codigo_pareo" placeholder="Codigo de pareo">
					</div>
				</div>
				<div class="form-group">
					<button type="submit" name="paring" class="btn btn-primary">Parear con Latch</button>
					<button type="submit" name="omitir" class="btn btn-primary">Omitir</button>	
				</div>
				
				<? 
					if( isset( $_SESSION['status'] ) ) echo '<h5>No se ha podido parear con exito.</h5>'; 
					unset($_SESSION['status']);
				?>

			</form>	
		</div>
	</article>

</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>