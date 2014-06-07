<?php
//INICIAMOS UNA SESSION
session_start();

//IMPORTAMOS LAS CLASES NECESARIAS
require_once 'config_db.php';
require_once 'conexion.php';
require_once 'usuario.php';

//IMPORTAMOS EL SDK DE LATCH PARA PHP
require_once 'latch/Latch.php';
require_once 'latch/LatchResponse.php';
require_once 'latch/Error.php';

//INICIALIZAMOS LAS VARIABLES CON LOS DATOS DE NUESTA APP EN LATCH
$APP_ID 	= 'Ktyp3vXzpZtXw4fdcCRA';
$APP_SECRET = 'u4uPAqyyrz2mRrtnRVnKWatDcRLgvYd2eKRqiFeC';

//CREAMOS EL OBJETO API QUE HACE REFERENCIA A LA CLASE LATCH
$api 		= new Latch($APP_ID, $APP_SECRET);
//CREMAOS EL OBJETO CONEXION QUE HACE REFERENCIA A LA CLASE CONEXION
$conexion 	= New Conexion();
//CREMOA EL OBJETO USER QUE HACER REFERENCIA A LA CLASE USUARIO
$user 	  	= New Usuario();


//SI NOS VIENE DEL FORMULARIO DE REGISTRO
if(isset($_POST['newUser'])){
	//OBTENEMOS LOS DATOS QUE ENVIAR EL FORMULARIO
	$nombre   = $_POST['nombre'];
	$email    = $_POST['email'];
	$usuario  = $_POST['usuario'];
	$password = $_POST['password'];
	
	//INSERTAMOS AL NUEVO USUARIO MEDIANTE EL METODO NEWUSER DE LA CLASE USUARIO
	if($user->newUser($nombre,$email,$usuario,$password)){

		//SI ES CORRECTO NOS GUARDARA EL NOMBRE DEL USUARIO EN UNA VARIABLE DE SESSION
		$_SESSION['nombre'] = $nombre;
		//NOS REDIRECCIONA A LA WEB DONDE HAREMOS EL PARING CON LATCH
		header('location: paring.php');
	
	}else{
		
		//EN EL CASO DE QUE SE GENERE UN ERROR NOS GUARDA EL MENSAJE EN UNA VARIABLE DE SESSION
		$_SESSION['error'] = 'No se ha podido registrar al usuario.';
		//NOS REDIRECCIONA A LA WEB DE REGISTRO
		header('location: newuser.php');

	}

}
//SI NOS VIENE DEL FORMULARIO DE PARING
elseif(isset($_POST['paring'])){
	
	//RECOGEMOS EL CODIGO DE PAREO Y SE LO MANDAMOS AL METODO PAIR DE LA CLASE LATCH
	$pairResponse = $api->pair($_POST['codigo_pareo']);

	//REALIZAMOS EL PAREO 
	if($success = $pairResponse->getData() ){

		$id_usuario= $_SESSION['id_usuario'];
		$accountId = $success->accountId;

		//SI EL CORRECTO RECOGEMOS EL LATCH_ID Y LOS SUBIMOS A LA DB
		$query = "UPDATE usuarios set latch_id = '$accountId' where id_usuario = '$id_usuario'";

		//REALIZAMOS LA CONSULTA
		if($conexion->conectar->query($query)){

			//SI TODO ES CORRECTO GUARDAMOS EL MENSAJE EN UNA VARIABLE DE SESSION
			$_SESSION['status']    = 'Latch pareado con exito.';
			//INDICAMOS MEDIANTE UNA VARIABLE DE SESSION QUE NOS DE LA OPCION DE DESPAREAR
			$_SESSION['desparear'] = true;
			//VACIAMOS LA VARIABLE DE SESSION QUE SE ENCARGA DE MOSTRARNOS EL LINK PARA HACER PARING
			unset($_SESSION['omitido']);	
			//NOS REDIRECCIONA A NUESTRA CUENTA DE USUARIO
			header('location: useraccount.php');
		
		}else{
			
			//NO SE HA PODIDO AÑADIR LA LATCH_ID A LA DB, MOSTRAMOS UN ERROR
     		$_SESSION['error'] = 'No se ha podido añadir su latch_id a la base de datos, 
     		pongase en contacto con el administrador para solucionar el problema.';
     		//NOS ACTUALIZA LA PAGINA DE PARING PARA MOSTRARNOS EL ERROR
     		header('location: paring.php');

		}
	
	}else{
		
		//NO SE HA PODIDO AÑADIR EL CODIGO DE PAREO A LA DB DE LATCH
     	$_SESSION['error'] = 'No se ha podido parear con Latch, revise el codigo. 
     	En caso de no poder parear con exito en varios intentos, intentelo de nuevo mas tarde.';
     	//NOS ACTUALIZA LA PAGINA DE PARING PARA MOSTRARNOS EL ERROR
     	header('location: paring.php');
	
	}

}
//SI NOS VIENE DEL FORMULARIO DE LOGIN
elseif(isset($_POST['iniciar'])){
	
	//RECOGEMOS LOS VALORES DEL FORMULARIO DE LOGIN
	$usuario  = $_POST['usuario'];
	$password = $_POST['password'];
	//LLAMAMOS AL METODO PARA HACER LOGIN PASANDOLE LOS PARAMETROS REVIBIDOS 
	$login 	  = $user->loginUsuario($usuario,$password);
	//SENTENCIA PARA OBTENER LA LATCH_ID DEL USUARIO QUE INTENTA HACER LOGIN
	$query 	  = "SELECT latch_id from usuarios where usuario = '$usuario'";

	//REALIZAMOS LA CONSULTA
	if($consulta = $conexion->conectar->query($query)){
	
		//EXTRAEMOS LOS DATOS
		$ACCOUNT_ID = $consulta->fetch_assoc();
		//VARIABLE CON EL CONTENIDO FINAL
		$latch_id 	= $ACCOUNT_ID['latch_id'];

	}

	//SI NO ES NULA, SE SUPONE QUE EL USUARIO TIENE PARING
	if( $latch_id ){

		//SI NO ES FALSO, SE SUPONE QUE EL USUARIO ESTA EN LA DB
		
		if( $login ){
		
			//OBTENEMOS EL ESTADO DEL PESTILLO ON/OFF
			$statusResponse = $api->status($latch_id);
			//LO EXTRAEMOS	
			$estado 		= $statusResponse->data->operations->Ktyp3vXzpZtXw4fdcCRA->status;	

			//SI ES "ON"
			if( $estado == 'on'){
		
				//CORRECTO Y GUARDAMOS EL MENSAJE EN UNA VARIABLE DE SESSION
				$_SESSION['status']    = 'Latch Desbloqueado, sesion iniciada.';	
				//LE DAMOS LA OPCION DE DESPAREAR
				$_SESSION['desparear'] = true;
				//LE QUITMAMOS LA OPCION DE PAREAR
				unset($_SESSION['omitido']);
				//REDIRECCIONAMOS A LA CUENTA DEL USUARIO
				header('location: useraccount.php');

			}else{
		
				//SI ESTA EL PESTILLO ECHADO... "OFF"
				$_SESSION['error'] = 'Latch Bloqueado, desbloquear para iniciar sesion.';
				//VOLVEMOS AL INDEX.PHP (PAGINA DE INICIO DE SESSION)
				header('location: index.php');

			}
		
		}else{
			
			//CONTRASEÑA MAL INTRODUCIDA
			$_SESSION['error'] = 'El usuario o contraseña no existe.';
			//RECARGAMOS LA PAGINA Y MOSTRAMOS EL ERROR
			header('location: index.php');

		}

	}
	//EL USUARIO ESTA REGISTRADO PERO NO HA PAREADO CON LATCH
	else{
		//LOGIN CORRECTO
		if( $login ){
			
			//LE DAMOS LA OPCION DE HACER EL PAREMO
			$_SESSION['omitido'] = true;
			//LE QUITAMOS LA OPCION DE DESPAREARSE
			unset($_SESSION['desparear']);		
			//REDIRECCIONAMOS A LA PAGINA DEL USUARIO
			header('location: useraccount.php');

		}else{
			
			//LOGIN INCORRECTO Y SE LO HACEMOS SABER GUARDANDO UN MENSAJE EN LA VARIABLE DE SESSION
			$_SESSION['error'] = 'El usuario o contraseña no existe.';
			//RECARGAMOS LA PAGINA PARA QUE VEA EL MENSAJE
			header('location: index.php');

		}

	}
	
}
//SI VIENE DEL FORMULARIO DE PARING PERO OMITIENDO EL PASO
elseif(isset($_POST['omitir'])){
	
	//HABILITAMOS LA OPCION DE HACER PAGIN EN OTRO MOMENTO
	$_SESSION['omitido'] = true;
	//QUITAMOS LA OPCION DE DESPAREAR
	unset($_SESSION['desparear']);	
	//REDIRECCIONAMOS A LA PAGINA DEL USUARIO
	header('location: useraccount.php');

}
//SI VIENE DEL FORMULARIO DE DESPAREO
elseif(isset($_POST['unparing'])){
	
	//RECOGEMOS LA ID_USUARIO DE LA VARIABLE DE SESSION
	$id_usuario = $_SESSION['id_usuario'];
	//SENTENCIA PARA OBTENER LA LATCH_ID DEL USUARIO QUE SE QUIERE DESPAREAR
	$query 		= "SELECT latch_id from usuarios where id_usuario = '$id_usuario'";	

	//REALIZAMOS LA CONSULTA
	if($consulta = $conexion->conectar->query($query)){
		
		//EXTRAEMOS LOS DATOS
		$latch_id 		= $consulta->fetch_assoc();
		//LOS GUARDAMOS EN SU VARIABLE FINAL
		$accountId 		= $latch_id['latch_id'];
		//LLAMAMOS AL METODO UNPAIR DE LA CLASE LATCH Y RECOGEMOS EL RESULTADO
		$unpairResponse = $api->unpair($accountId);
		
		//SI TODO SE QUEDA NULL ES QUE SE HA DESPAREADO BIEN
		if( $unpairResponse->data == null && $unpairResponse->error == null){
			
			//SENTENCIA PARA ACTUALIZAR NUESTRA DB BORRANDO LA LATCH_ID Y DEJANDOLA A NULL
			$query = "UPDATE usuarios SET latch_id=Null WHERE id_usuario = '$id_usuario'";
			
			//REALIZAMOS LA CONSULTA
			if ( $conexion->conectar->query($query)) {
				
				//SI TODO ES CORRECTO HABILITAMOS DE NUEVO LA OPCION DE PAREO
				$_SESSION['omitido'] = true;
				//OCULTAMOS EL DESPAREO
				unset($_SESSION['desparear']);
				//VOLVEMOS A LA PAGINA DEL USAURIO
				header('location: useraccount.php');
			
			}else{
				
				//SI NO SE HA PODIDO ACTUALIZAR LA DB
				$_SESSION['error'] = 'No se ha podido eliminar su latch_id de la base de dato, 
				pongase en contacto con el administrador para solucionar el problema.';
				//RECARGAMOS LA PAGINA
				header('location: unparing.php');

			}

		}else{
			
			//SI NO SE HA PODIDO DESPAREAR
			$_SESSION['error'] = 'No se ha podido desparear con exito.';
			//RECARGAMOS LA WEB
			header('location: unparing.php');

		}

	}else{
		
		//SI NO SE HA PODIDO RECUPERAR LA LATCH_ID
		$_SESSION['error'] = 'No tiene Latch pareado, no puede continuar.';
		//RECARGA LA WEB
		header('location: unparing.php');		

	}


}

?>