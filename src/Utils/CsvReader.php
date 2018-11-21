<?php
namespace App\Utils;

class CsvReader{
	
	// Ruta completa al archivo csv
	private $ruta;
	//Entity Manager Pasado desde el controlador
	private $entityManager;
	
	private  $CsvInterface;

	public function __construct() {
       $this->ruta = null;
       $this->entityManager = null;
       $this->CsvInterface = null;
       
   }

   public function setParameters($ruta,$entityManager, $CsvInterface){
   		$this->ruta = $ruta;
       	$this->entityManager = $entityManager;
       	$this->CsvInterface	 = $CsvInterface;
   }

   private function validateParameters(){
   		return !(		
   					$this->ruta == null
   				||  $this->entityManager == null
   				||	$this->CsvInterface == null
   		);

   }


   public function readCsv(){
   		if(!$this->validateParameters()){
   			throw new Exception("Error al validar los parámetros del lector csv", 1); 			
   		}
   		$linea = 0;
   		$em = $this->entityManager;
		//Abrimos nuestro archivo
		//$archivo = fopen($this->getParameter('kernel.project_dir').'/assets/req2.csv', "r");
		$archivo = fopen($this->ruta, "r");		
		$data = array();
		
    	$em->getConnection()->beginTransaction();
    	//Se obvian los encabezados
    	fgetcsv($archivo,5000, ";");
    	$dataOk = true;
    	$errors = array();
    	$linea = 1;
    	try{
			while (($datos = fgetcsv($archivo,5000, ";")) == true) 
			{
				$validation = $this->CsvInterface->validarRegistroCsv($em,$datos);
				$dataOk = $dataOk && $validation[0];
				if($dataOk){
					$this->CsvInterface->guardarRegistroCsv($em,$datos);
				}else if(!$validation[0]){
					$errors[] = "Error en la línea ".$linea." ".$validation[1];
				}
				$linea++;				
			}
			//Cerramos el archivo
			fclose($archivo);
			
			if($dataOk){
				//Si no existen errores se confirma la transacción y se retorna true
				$em->flush();	       
				$em->getConnection()->commit();
				return  array('success'=>true,'errors'=>array());
			}
			//En caso de que existan errores se regresa la transacción y se devuelven los errores
			$em->getConnection()->rollBack();
			return  array('success'=>false,'errors'=>$errors);
			
	    }catch(Exception $e){

	    	$em->getConnection()->rollBack();
	    	return array('success'=>false,'errors' => array('Error Inesperado al leer el archivo'));
		}
   }

}