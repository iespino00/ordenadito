<?php
require_once('BackEnd/ScriptsMysql.php');   //Consultas con PDO
class API 
{ 



     public function API_METHOD()
     {
         header('Content-Type: application/JSON');                
         $method = $_SERVER['REQUEST_METHOD'];
         switch ($method) 
         {
         case 'GET'://consulta
             $this->get();
             break; 
         case 'POST': //Inserciones
             $this->post();
             break;    
         case 'PUT': //Actualizaciones
             $this->put();
             break;  
         case 'DELETE':  //Eliminar
            $this->delete();
            break;
         default://metodo NO soportado
             echo 'METODO NO SOPORTADO';
             break;
         }
     }   

        /**
     * Respuesta al cliente
     * @param int $code Codigo de respuesta HTTP
     * @param String $status indica el estado de la respuesta puede ser "success" o "error"
     * @param String $message Descripcion de lo ocurrido
     */
     function response($code=200, $status="", $message="") 
       {
        http_response_code($code);
        if( !empty($status) && !empty($message) )
           {
            $response = array("status" => $status ,"message"=>$message);  
            echo json_encode($response,JSON_PRETTY_PRINT);    
            } 
       }

     function get()
     {
         if($_GET['action']=='users')
            {         
                 $dbPDO = new DB();   //Objeto de clase DB

                 if(isset($_GET['email']))
                 {

                     $response = $dbPDO->getUser($_GET['email']);   //Consultas con PDO         
                     echo json_encode($response,JSON_PRETTY_PRINT);
                 }else
                     { //muestra todos los registros                   
                     $response = $dbPDO->getAllUsers();   //Consultas con PDO          
                     echo json_encode($response,JSON_PRETTY_PRINT);
                     }
           }
        
           /*elseif($_GET['action']=='equipos')
            {
                $dbPDO = new DB();
                if(isset($_GET['id']))
                 {
                     
                     $response = $dbPDO->getEquipo($_GET['id']);     
                     echo json_encode($response,JSON_PRETTY_PRINT);
                
                 }else
                     {                
                     $response = $dbPDO->getAllEquipos();        
                     echo json_encode($response,JSON_PRETTY_PRINT);
                  
                     }
                
            }*/


         else{
               $this->response(400);
              }       
     }  
  
     function post()
     {
        if($_GET['action']=='adduser')
        {   
            //Decodifica un string de JSON
            $obj = json_decode( file_get_contents('php://input') );   
            $objArr = (array)$obj;
            if (empty($objArr))
            {
               $this->response(422,"error","No se ha podido agregar. Verifica tu json");                           
            }
            else if(isset($obj))
                {
                    $dbPDO = new DB();   
                    $respuesta = $dbPDO->addUser( $obj );

                    if($respuesta == 1)
                    {
                        $this->response(422,"Error","El correo ya existe");    
                    }
                    else{

                    
                    $this->response(200,"Exito","Registro Agregado con éxito"); 
                  //  $this->response(200, "Hecho", $obj);                        
                        }       
                }
                else{
                    $this->response(422,"Error","La propiedad no está definida");
                     }
        } else{               
            $this->response(400);
        }  
     }


     function put() 
     {
       if( isset($_GET['action']) && isset($_GET['email']) )
        {
           if($_GET['action']=='user')
           {
                   $obj = json_decode( file_get_contents('php://input') );   
                   $objArr = (array)$obj;
                   if (empty($objArr))
                       {                        
                       $this->response(422,"Error","No se ha podido agregar. Verifica tu json");                        
                       }
                else if(isset($obj))
                           {
                           $dbPDO = new DB();  
                          $respuesta = $dbPDO->updateUser($_GET['email'], $obj);
                           $this->response(200,"Exitoso","Registro Actualizado");      
                       //    $this->response(200,"Exitoso",$_GET['id'].$obj);                     
                           }else
                               {
                                 $this->response(422,"Error","La propiedad no está definida");                        
                               }     
             exit;
            }
        }
        $this->response(400);
     }

     function delete()
     {
            if( isset($_GET['action']) && isset($_GET['email']) )
              {
                    if($_GET['action']=='user')
                       {                   
                        $dbPDO = new DB();  
                        $dbPDO->deleteUser($_GET['email']);
                       // $this->response(204);                   
                       $this->response(200,"Exitoso","Usuario Eliminado");  
                       exit;
                       }
               }
            $this->response(400);
      }



}//end class
?>