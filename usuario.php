<?php

class Usuario extends Conexion{

	//CONSTRUCTOR
	function __construct(){
		//CONSTRUCTOR PADRE (CLASE CONEXION)
		parent::__construct();

	}

	//GENERAMOS EL SAL CON QUE CREAREMOS EL HASH PARA CODIFICAR EL PASS DEL USUARIO
	function generarSal(){ // Revidada

		//DECLARAMOS LAS VARIABLES CON LAS CUALES GENERAREMOS EL SAL PARA CODIFICAR EL PASSWORD
		$salAleatorio 		= "";
		$length 			= 64; //INDICAMOS QUE EL TAMAÑO ES DE 64 CHARS
		$indice 			= "";
		$charElegido 		= "";
		$listaCaracteres 	= "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; //LISTA DE CARACTERES PARA HACER EL SAL
		
		//DEFINIMOS EL TIPO DE VARIABLE QUE VA A SER CADA UNA DE LAS ANTERIORES
		settype($length 		, "integer");
		settype($salAleatorio	, "string");
		settype($indice			, "integer");
		settype($charElegido	, "integer");
		
		//GENERAMOS EL SAL CHAR A CHAR
		for ($indice = 0; $indice <= $length; $indice++) {
			
			//VAMOS ELIGIENDO UN CARACGER DE FORMA ALEATORIA
			$charElegido   = rand(0, strlen($listaCaracteres) - 1);
			
			//VAMOS CONCATENANDO LOS CARACTERES
			$salAleatorio .= $listaCaracteres[$charElegido];
		
		}
		
		//RETORNAMOS EL SAL PARA GENERAR EL PASS, CADA USUARIO TIENE UN SAL DIFERENTE		
		return $salAleatorio;

	}

	//GENERAMOS EL PASS HASEADO CON EL SAL
	function generarPassword($password){ // Revidada

		//LLAMAMOS A LA FUNCION QUE GENERAR EL SAL
		$salAleatorio 			= $this->generarsal();
		
		//GENERAMOS EL PASS CIFRADO CON EL SAL
		$passwordHaseado 		= hash('SHA256', "-".$salAleatorio."-".$password."-");
		
		//RETORNAMOS EL HASH Y PASS HASEADO PARA GUARDARLOS EN LA BD 
		return $packEncriptado 	= array('0' => $salAleatorio,'1' => $passwordHaseado);
	
	}

	//FUNCION PARA AÑADIR UN NUEVO EMPLEADO A LA DB
	function newUser($nombre,$email,$usuario,$password){

		//OBTENEMOS LOS DATOS GENERADO A PARTIR DEL PASS DEL USUARIO
		$pack 			= $this->generarPassword($password);
		$sal 			= $pack[0];
		$password_hash 	= $pack[1];

		//CONSULA PARA SACAR LA ID DE USUARIOS REPETIDOS
		$query = "SELECT id_usuario from usuarios where email = '$email' or usuario = '$usuario'";

		//COMPROBAMOS SI HAY ALGUN EMAIL O NOMBRE USUARIO REPETIDO
		if($consulta = $this->conectar->query($query)){

			//SI NO OBTENEMOS NINGUN RESULTADO AÑADIMOS AL USUARIO
			if($consulta->num_rows == 0){

				//CONSULA CON LA QUE AÑADIMOS NUEVO USER
				$query = "INSERT INTO usuarios (id_usuario,nombre,email,usuario,password,user_hash) VALUES 
				(
					NULL, 
					'$nombre',
					'$email',
					'$usuario', 
					'$password_hash',
					'$sal'
				)";
		
				//INSERTAMOS AL USUARIO EN LA DB
				if($this->conectar->query($query)){

					//CONSULA PARA OBTENER LA ID DEL USUARIO QUE ACABAMOS DE AÑADIR
					$query = "SELECT id_usuario from usuarios where usuario = '$usuario'";

					//OBTENEMOS LA ID DEL USUARIO
					if($consulta = $this->conectar->query($query)){

						//GUARDAMOS LA ID EN UNA VARIABLE
						$id_usuario = $consulta->fetch_assoc();

						//CREAMOS UNA VARIABLE DE SESION PARA USAR LA ID DURANTE LA SESION
						$_SESSION['id_usuario'] = $id_usuario['id_usuario'];

						//TODO CORRECTO
						return true;

					}else return false; //ERROR AL OBTENER LA ID

				}else return false; //ERROR AL INSERTAR EL USUARIO

			}else return false; //ERROR EMAIL O USUARIO REPETIDO

		}else return false; //ERROR AL REALIZAR LA CONSULTA INICIAL

	}

	//FUNCION DE INICIO DE SESION
	function loginUsuario($usuario,$password){

    	//CONSULTA PARA OBTENER EL HASH DEL USUARIO QUE QUIERE INICIAR SESION Y ALGUNOS DATOS MAS
    	$query = "SELECT id_usuario,user_hash,usuario,password,nombre,latch_id from usuarios where usuario = '$usuario'";

    	//REALIZAMOS LA CONSULTA
		if($consulta = $this->conectar->query($query)){
			
			//SI NO OBTENEMOS LOS DATOS, ES QUE EL USUARIO NO EXISTE, ASI QUE DESCARTAMOS Y NOS AHORRAMOS EL RESTO DL PROCESO
			if($consulta->num_rows == 1){

				//SACAMOS EL RESULTADO DE LA CONSULTA ANTERIOR
				$resultado 	 	= $consulta->fetch_row();
				$id_usuario_db  = $resultado[0];
				$hash_db 	 	= $resultado[1];
				$usuario_db  	= $resultado[2];
				$password_db 	= $resultado[3];
				$nombre_db 	 	= $resultado[4];
				$latch_id_db 	= $resultado[5];

				//REHASEHAMOS EL PASSWORD DEL USUARIO PARA VER SI ES IGUAL QUE EL DE LA BASE DE DATOS
				$password_check = hash('SHA256', "-".$hash_db."-".$password."-");

				//COMPARAMOS DATOS Y SI TODO ES CORRECTO, OBTENEOS NUESTROS PARAMETROS Y SEGUIEMOS
				if(strtolower($usuario_db) === strtolower($usuario) && $password_db === $password_check){
	
					//VARIABLE DE SESSION PARA MOSTRER EL NOMBRE
					$_SESSION['nombre'] = $nombre_db;
				
					//VARIABLE DE SESSION PARA LLEVAR LA ID DE USUARIO
					$_SESSION['id_usuario'] = $id_usuario_db;
					
					//TODO CORRECTO
					return true;

				}else return false; //ERROR AL COMPROBAR LOS DATOS DE USUARIO

			}else return false; //ERROR NO HAY USUARIO CON ESAS CREDENCIALES

		}else return false; //ERROR AL REALIZAR LA CONSULTA

	}

}

?>