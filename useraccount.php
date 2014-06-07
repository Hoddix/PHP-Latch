<?
//INICIAMOS SESSION
session_start();
//ACCESO SIN LOGIN RESTRINGIDO
if(isset($_SESSION['id_usuario'])){

?>
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
			<? 
				if(isset( $_SESSION['status'] ) ){
					
					echo '<h5>'.$_SESSION['status'].'</h5>'; 

				}
				if(isset( $_SESSION['omitido'] ) ){
			?>
					<div class="form-group">
						<label>Parea tu cuenta con Latch</label>
						<p><a href="paring.php"><img src="img/logo-latch.png" alt="" width="50%"></a></p>
					</div>
			<?
				}
				if(isset( $_SESSION['desparear'] ) ){
			?>
					<div class="form-group">
						<label>Desparea tu cuenta de Latch</label>
						<p><a href="unparing.php"><img src="img/logo-latch.png" alt="" width="50%"></a></p>
					</div>
			<?
				}
				unset($_SESSION['status']);	
			?>
		</div>
	</article>

</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
<?
}
//ACCESO SIN LOGIN RESTRINGIDO
else{
	header('location: index.php');
}
?>