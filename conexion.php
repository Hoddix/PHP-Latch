<?php  
 
class Conexion{ 

    //VARIABLE CONECTAR QUE CONTENDRA LA CLASE MYSQLI
    public $conectar; 
    
    //CONTRUCTOR PARA LAS CLASES EXTENDIDAS
    public function __construct(){ 
    	
    	//CREAMOS EL OBJETO QUE CONTENDRA LA CLASE MYSQLI
        if($this->conectar = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME))
            //PONEMOS LA DB EN EL CHARSET UTF8
            $this->conectar->set_charset(DB_CHARSET); 
        //SI ALGO VA MAL...
        else
            return false;
    	  
    } 

} 

?>