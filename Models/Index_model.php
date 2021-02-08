<?php
class Index_model extends Conexion
{
    function __construct(){
        parent::__construct();
        date_default_timezone_set('America/Tegucigalpa');
    }
    function userLogin($email,$password,$model,$model1){
        $where = " WHERE Email = :Email";
        $param = array('Email' => $email);
        $response = $this->db->select1("*",'usuarios',$where,$param);
        if (is_array($response)) {
            $response = $response['results'];
           // echo var_dump($response);
            if (0 != count($response)){
                $where = " WHERE Estado = :Estado AND Asignada = :Asignada";
                $param = array('Estado' => true,
                               'Asignada' => false);
                $cajas = $this->db->select1("*",'cajas',$where,$param);               
                if (password_verify($password,$response[0]["Password"])) {
                    if("Admin" == $response[0]["Roles"]){
                        $this->db->pdo->beginTransaction();
                        $model->IdUsuario = $response[0]["IdUsuario"];
                        $model->Nombre = $response[0]["Nombre"];
                        $model->Apellido = $response[0]["Apellido"];
                        $model->Usuario = $response[0]["Usuario"];
                        $model->Role = $response[0]["Roles"];
                        $model->IdCaja = 0;
                        $model->Caja = 0;
                        $model->Estado = true;
                        $model->Hora = date('h:i:s');
                        $model->Dia = date("d");
                        $model->Mes = date("m");
                        $model->Year = date("Y");
                        $model->Fecha = date("d/m/Y");
                        $value = "INSERT INTO cajas_registros (IdUsuario,Nombre,Apellido,Usuario,Role,IdCaja,Caja,Estado,Hora,Dia,Mes,Year,Fecha) VALUES (:IdUsuario,:Nombre,:Apellido,:Usuario,:Role,:IdCaja,:Caja,:Estado,:Hora,:Dia,:Mes,:Year,:Fecha)";
                        $sth = $this->db->pdo->prepare($value);
                        $sth->execute((array)$model);
                        $this->db->pdo->commit();
                        $data = array(
                            "IdUsuario" => $response[0]["IdUsuario"],
                            "Nombre" => $response[0]["Nombre"],
                            "Apellido" => $response[0]["Apellido"],
                            "Roles" => $response[0]["Roles"],
                            "Usuario" => $response[0]["Usuario"],
                            "Imagen" => $response[0]["Imagen"],
                            "Email" => $response[0]["Email"],
                            "IdCaja" => 0,
                            "Caja" => 0,
                        );
                        Session::setSession("User",$data);
                        return $data;
                        //return var_dump($model);
                    }else{
                        $this->db->pdo->beginTransaction();
                        $cajas = $cajas['results'];
                        $cajas = reset($cajas);
                       
                        $model->IdUsuario = $response[0]["IdUsuario"];
                        $model->Nombre = $response[0]["Nombre"];
                        $model->Apellido = $response[0]["Apellido"];
                        $model->Usuario = $response[0]["Usuario"];
                        $model->Role = $response[0]["Roles"];
                        $model->IdCaja = $cajas["IdCaja"];
                        $model->Caja = $cajas["Caja"];
                        $model->Estado = true;
                        $model->Hora = date('h:i:s');
                        $model->Dia = date("d");
                        $model->Mes = date("m");
                        $model->Year = date("Y");
                        $model->Fecha = date("d/m/Y");
                        $value = "INSERT INTO cajas_registros (IdUsuario,Nombre,Apellido,Usuario,Role,IdCaja,Caja,Estado,Hora,Dia,Mes,Year,Fecha) VALUES (:IdUsuario,:Nombre,:Apellido,:Usuario,:Role,:IdCaja,:Caja,:Estado,:Hora,:Dia,:Mes,:Year,:Fecha)";
                        $sth = $this->db->pdo->prepare($value);
                        $sth->execute((array)$model);

                        $model1->Caja = $cajas["Caja"];
                        $model1->Estado = true;
                        $model1->Asignada = true;
                        $model1->Usuario = $response[0]["Email"];

                        $query =  "UPDATE cajas SET Caja = :Caja,Estado = :Estado,Asignada = :Asignada,Usuario = :Usuario WHERE IdCaja = ".$cajas["IdCaja"];

                        $sth = $this->db->pdo->prepare($query);
                        $sth->execute((array)$model1);

                        $this->db->pdo->commit();
                        $data = array(
                            "IdUsuario" => $response[0]["IdUsuario"],
                            "Nombre" => $response[0]["Nombre"],
                            "Apellido" => $response[0]["Apellido"],
                            "Roles" => $response[0]["Roles"],
                            "Usuario" => $response[0]["Usuario"],
                            "Imagen" => $response[0]["Imagen"],
                            "Email" => $response[0]["Email"],
                            "IdCaja" => $cajas["IdCaja"],
                            "Caja" => $cajas["Caja"],
                        );
                        Session::setSession("User",$data);
                        return $data;
                    }
                   
                } else {
                    $data = array(
                        "IdUsuario" => 0,
                    );
                    return $data;
                }
            }else{
                return "El email no esta registrado";
            }
        } else {
            return  $response;
        } 
      
    }
}


?>