<?php
require_once './conexion/DB_Connect.php';
require_once './modelo/Usuarios.php';

class DB
{ 
    private $db;
    private $pdo;

    function __construct() 
    {
        $this->db = new DB_Connect();
        $this->pdo = $this->db->connect();
    }
 
    function __destruct() { }

         public function getAllUsers() 
         {
             $stmt = $this->pdo->prepare('SELECT * FROM usuarios');                                     
             $stmt->execute();
             $array = array();
            
             $ind = 0;
             foreach ($stmt as $row) 
             {
                 $itm = new Usuarios();  
                 $itm->id_user= $row['id_user'];
                 $itm->nickname = $row['nickname'];   
                 $itm->password = $row['password'];                  
                 $itm->nombre = $row['nombre'];  
                 $itm->apellidos = $row['apellidos'];  
                 $itm->email = $row['email'];  
     
                 $array[$ind] = $itm;
                 $ind++;
             }
             return $array;
         }

         public function getUser($email) 
         {
             $stmt = $this->pdo->prepare('SELECT * from usuarios where email = :email ');                                     
             $stmt->execute( array('email' => $email));
             $array = array();
            
             $ind = 0;
             foreach ($stmt as $row) 
             {
                $itm = new Usuarios();  
                 $itm->id_user= $row['id_user'];
                 $itm->nickname = $row['nickname'];   
                 $itm->password = $row['password']; 
                 $itm->nombre = $row['nombre'];  
                 $itm->apellidos = $row['apellidos'];  
                 $itm->email = $row['email'];

                 $array[$ind] = $itm;
                 $ind++;
             }
             return $array;
          }

          public function addUser($obj)
          {
            $emailquery = $this->pdo->prepare('SELECT * from usuarios where email = :email ');                                     
            $emailquery->execute( array('email' => $obj->email));
            
            if ($emailquery->rowCount() > 0)
            {   
                $error = 1;
                return $error;
            }else
              {

            $stmt = $this->pdo->prepare('insert into usuarios(nickname, password,nombre, apellidos, email) 
                                         values(:nickname,:password,:nombre,:apellidos,:email)');
            $stmt->execute(array(
                                    'nickname' => $obj->nickname,
                                    'password' => $obj->password, 
                                    'nombre' => $obj->nombre,
                                    'apellidos' => $obj->apellidos,
                                    'email' => $obj->email
                                )); 
 
                    $res = false;
                    if($stmt)
                    {
                       $res = true;
                    }
                    return res;
                }
          }
   

          public function updateUser($email,$obj)
          {
            $stmt = $this->pdo->prepare('update usuarios set 
            nickname = :nickname,
            password = :password,
            nombre = :nombre,
            apellidos = :apellidos
                                        where email = :email');
            $stmt->execute(array(
                                    'email' => $email,
                                    'nickname' => $obj->nickname, 
                                    'password' => $obj->password,
                                    'nombre' => $obj->nombre,
                                    'apellidos' => $obj->apellidos
                                )); 
 
                    $res = false;
                    if($stmt)
                    {
                       $res = true;
                    }
                    return res;
          }
   
          public function deleteUser($email)
          {
            $stmt = $this->pdo->prepare('delete from usuarios where email = :email');
            $stmt->execute(array(
                                    'email' => $email
                                )); 
 
                    $res = false;
                    if($stmt)
                    {
                       $res = true;
                    }
                    return res;
          }
 
    
}
 
?>