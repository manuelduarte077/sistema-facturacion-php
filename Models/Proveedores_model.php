<?php
class Proveedores_model extends Conexion 
{
    function __construct(){
        parent::__construct();
    }
    public function registerProvedores($model1,$model2){
        $where = " WHERE Email = :Email";
        $response = $this->db->select1("Email",'proveedores',$where,array('Email' => $model1->Email));
        if (is_array($response)){
            $response = $response['results'];
            if (0 == count($response)) {
                $value = " (Proveedor,Telefono,Email,Direccion) VALUES (:Proveedor,:Telefono,:Email,:Direccion)";
                $data =  $this->db->insert("proveedores",$model1,$value);
                if (is_bool($data)) {
                    $response = $this->db->select1("*",'proveedores',$where,array('Email' => $model1->Email));
                    if (is_array($response)){
                        $response = $response['results'];
                        $model2->IdProveedor= $response[0]["IdProveedor"];
                        $value = " (Deuda,FechaDeuda,Pago,FechaPago,Ticket,IdProveedor) VALUES (:Deuda,:FechaDeuda,:Pago,:FechaPago,:Ticket,:IdProveedor)";
                        $data =  $this->db->insert("reportes_proveedores",$model2,$value);
                        if (is_bool($data)) {
                            return 0;
                        } else {
                            return $data;
                        }
                    }else{
                        return  $response; 
                    }
                }else{
                    return  $data;
                }
            } else {
                return 1;
            }
            
        }else{
            return  $response;
        }
         
    }
    function getProveedores($filter,$page,$model){
        $where = " WHERE Proveedor LIKE :Proveedor OR Email LIKE :Email";
        $array = array(
            'Proveedor' => '%'.$filter.'%',
            'Email' => '%'.$filter.'%'
        );
        
        return $model->paginador("*","proveedores","Proveedores",$page,$where,$array);
    }
    public function dataProveedor($email){
        $where = " WHERE Email = :Email";
        $response = $this->db->select1("*",'proveedores',$where,array('Email' => $email));
        if (is_array($response)){
            return json_encode($response);
        }else{
            return  $response;
        }
    }
    public function editProveedor($model,$idProveedor){
        $where = " WHERE Email = :Email";
        $response = $this->db->select1("*",'proveedores',$where,array('Email' => $model->Email));
        if (is_array($response)) {
            $response = $response['results'];
            $value =  "Proveedor = :Proveedor,Telefono = :Telefono,Email = :Email,Direccion = :Direccion";

            $where = " WHERE IdProveedor = ".$idProveedor;
            if (0 == count($response)){
                $data = $this->db->update("proveedores",$model,$value, $where);
                if(is_bool($data)){
                    return 0;
                }else{
                    return  $data;
                }
            }else{
                if ($response[0]['IdProveedor'] == $idProveedor) {
                    $data = $this->db->update("proveedores",$model,$value, $where);
                    if (is_bool($data)) {
                        return 0;
                    } else {
                        return  $data;
                    }
                } else {
                    return 1;
                }
                
            }
        }else {
            return $response;
         }
    }
    public function getReporteProveedor($email){
        $where = " WHERE Email = :Email";
        $response1 = $this->db->select1("*",'proveedores',$where,array('Email' => $email));
        if(is_array($response1)){
            $response1 = $response1['results'];
            if (0 != count($response1)){
                $where = " WHERE IdProveedor = :IdProveedor";
                $response2 = $this->db->select1("*",'reportes_proveedores',$where,array('IdProveedor' => $response1[0]["IdProveedor"]));
                if (is_array($response2)) {
                    $response2 = $response2['results'];
                    if (0 != count($response2)) {
                        $data= array(
                            "Proveedor" => $response1[0]["Proveedor"],
                            "Email" => $response1[0]["Email"],
                            "IdReportes" => $response2[0]["IdReportes"],
                            "Deuda" => $response2[0]["Deuda"],
                            "FechaDeuda" => $response2[0]["FechaDeuda"],
                            "Pago" => $response2[0]["Pago"],
                            "FechaPago" => $response2[0]["FechaPago"],
                            "Ticket" => $response2[0]["Ticket"],
                            "IdProveedor" => $response2[0]["IdProveedor"]
                        );
                        Session::setSession("reportProveedor",$data);
                    return $data;
                    }else{
                        return 0;
                    }
                } else {
                    return $response2;
                }
                
            }else{
                return 0;
            }
        }else{
            return $response1;
        }
    }
    public function setPagos($model1,$model2,$idReporte){
         $Ticket = Codigo::Tickets($this->db,"ticket","Proveedor",$model2->Email);
         if (is_numeric($Ticket)) {
            $value =  "Deuda = :Deuda,FechaDeuda = :FechaDeuda,Pago = :Pago,FechaPago = :FechaPago,Ticket = :Ticket,IdProveedor = :IdProveedor";
            $where = " WHERE IdReportes = ".$idReporte;
            $model1->Ticket = (string)$Ticket;
            $data = $this->db->update("reportes_proveedores",$model1,$value, $where);
            if (is_bool($data)) {
                $value = " (Propietario,Deuda,FechaDeuda,Pago,FechaPago,Ticket,Email) VALUES (:Propietario,:Deuda,:FechaDeuda,:Pago,:FechaPago,:Ticket,:Email)";
                $model2->Ticket = (string)$Ticket;
                $data =  $this->db->insert("ticket",$model2,$value);
                if (is_bool($data)) {
                    return 0;
                } else {
                    return $data;
                }
            } else {
                return $data;
            }
            
         } else {
            return $Ticket;
         }
    }
    public function getTickets($filter,$page,$model){
        $where = " WHERE  Propietario = :Propietario AND Email LIKE :Email ";
        $array = array(
            'Propietario' => "Proveedor",
            'Email' => '%'.$filter.'%'
        );
        return $model->paginador("*","ticket","Ptickets",$page,$where,$array);
    }
}


?>