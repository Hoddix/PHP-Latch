<?php
session_start();

require_once 'conexion.php';
require_once 'usuario.php';
require_once 'latch/Latch.php';
require_once 'latch/LatchResponse.php';
require_once 'latch/Error.php';

$APP_ID 		 = 'Ktyp3vXzpZtXw4fdcCRA';
$APP_SECRET 	 = 'u4uPAqyyrz2mRrtnRVnKWatDcRLgvYd2eKRqiFeC';
$api 			 = new Latch($APP_ID, $APP_SECRET);
$conexion 		 = New Conexion();
$user 			 = New Usuario();

if(isset($_POST['newUser'])){

	$nombre   = $_POST['nombre'];
	$email    = $_POST['email'];
	$usuario  = $_POST['usuario'];
	$password = $_POST['password'];
	
	if($user->newUser($nombre,$email,$usuario,$password)){

		$_SESSION['nombre'] = $nombre;
		
		header('location: paring.php');
	
	}else{

		$_SESSION['error'] = 'No se ha podido registrar al usuario.';

		header('location: newuser.php');

	}

}
elseif(isset($_POST['paring'])){
	
	$pairResponse = $api->pair($_POST['codigo_pareo']);

	if($success = $pairResponse->getData() ){

		//UPDATE EN LA DB PARA AÑADIR LA ID LATCH
		$query = 'UPDATE usuarios set latch_id = "'.$success->accountId.'" where id_usuario= "'.$_SESSION['id_usuario'].'"';

		if($conexion->conectar->query($query)){

			$_SESSION['status'] = 'Latch pareado con exito.';

			$_SESSION['desparear'] = true;

			unset($_SESSION['omitido']);	

			header('location: useraccount.php');
		
		}else{

     		$_SESSION['status'] = true;

     		header('location: paring.php');

		}
	
	}else{

     	$_SESSION['status'] = true;

     	header('location: paring.php');
	
	}

}
elseif(isset($_POST['iniciar'])){
	
	$usuario  	= $_POST['usuario'];
	$password 	= $_POST['password'];

	$login 		= $user->loginUsuario($usuario,$password);

	$query 		= 'SELECT latch_id from usuarios where usuario = "'.$usuario.'"';

	if($consulta = $conexion->conectar->query($query)){

		$ACCOUNT_ID = $consulta->fetch_assoc();
		$latch_id = $ACCOUNT_ID['latch_id'];

	}

	if( $latch_id ){

		if( $login ){

			$statusResponse = $api->status($latch_id);	
			$estado 		= $statusResponse->data->operations->Ktyp3vXzpZtXw4fdcCRA->status;	

			if( $estado == 'on'){

				$_SESSION['status']    = 'Latch Desbloqueado, sesion iniciada.';	

				$_SESSION['desparear'] = true;

				unset($_SESSION['omitido']);

				header('location: useraccount.php');

			}else{

				$_SESSION['error'] = 'Latch Bloqueado, desbloquear para iniciar sesion.';

				header('location: index.php');

			}
		
		}else{

			$_SESSION['error'] = 'El usuario o contraseña no existe.';

			header('location: index.php');

		}

	}else{

		if( $login ){
			
			$_SESSION['omitido'] = true;

			unset($_SESSION['desparear']);		

			header('location: useraccount.php');

		}else{

			$_SESSION['error'] = 'El usuario o contraseña no existe.';

			header('location: index.php');

		}

	}
	
}
elseif(isset($_POST['omitir'])){
	
	$_SESSION['omitido'] = true;

	unset($_SESSION['desparear']);	

	header('location: useraccount.php');

}
elseif(isset($_POST['unparing'])){
	
	$id_usuario = $_SESSION['id_usuario'];

	$query = "SELECT latch_id from usuarios where id_usuario = '$id_usuario'";	

	if($consulta = $conexion->conectar->query($query)){
		
		$latch_id = $consulta->fetch_assoc();
	
		$accountId = $latch_id['latch_id'];

		$unpairResponse = $api->unpair($accountId);
		
		if( $unpairResponse->data == null && $unpairResponse->error == null){

			$query = "UPDATE usuarios SET latch_id=Null WHERE id_usuario='$id_usuario'";
			
			if ( $conexion->conectar->query($query)) {

				$_SESSION['omitido'] = true;

				unset($_SESSION['desparear']);

				header('location: useraccount.php');
			
			}else{
				
				$_SESSION['status'] = true;

				header('location: unparing.php');

			}

		}else{

			$_SESSION['status'] = true;
			
			header('location: unparing.php');

		}

	}else{

		$_SESSION['status'] = true;

		header('location: unparing.php');		

	}


}

?>