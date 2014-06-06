<?php  

require_once 'config_db.php'; 
 
class Conexion{ 

    public $conectar; 
    
    public function __construct(){ 
    	
    	//CREAMOS EL OBJETO QUE CONTENDRA LA CLASE MYSQLI
        if($this->conectar = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME)){
        
            $this->conectar->set_charset(DB_CHARSET); 
        
        }else{
            
            throw new DBException( "Error al conectar con el servidor MySql" ); 
            
        }
    	  
    } 

} 

?>