<?php
class Clientes_model extends Conexion
{
    function __construct(){
        parent::__construct();
    }
    function getCreditos(){
        return $this->db->select1("*","creditos",null,null);
    }
    public function registerCliente($cliente,$r_cliente){
        $where = " WHERE Email = :Email";
        $response = $this->db->select1("*",'clientes',$where,array('Email' => $cliente->Email));
        if (is_array($response)) {
            $response = $response['results'];
            if (0 == count($response)) {
                $value = " (NID,Nombre,Apellido,Email,Telefono,Direccion,Creditos) VALUES (:NID,:Nombre,:Apellido,:Email,:Telefono,:Direccion,:Creditos)";
                $data =  $this->db->insert("clientes",$cliente,$value);
                if (is_bool($data)) {
                    $response = $this->db->select1("*",'clientes',$where,array('Email' => $cliente->Email));
                    if (is_array($response)) {
                        $response = $response['results'];
                        $r_cliente->IdClientes= $response[0]["IdClientes"];
                        $value = " (Deuda,FechaDeuda,Pago,FechaPago,Ticket,IdClientes) VALUES (:Deuda,:FechaDeuda,:Pago,:FechaPago,:Ticket,:IdClientes)";
                        $data =  $this->db->insert("reportes_clientes",$r_cliente,$value);
                        if (is_bool($data)) {
                            return 0;
                        } else {
                            return $data;
                        }
                        
                    } else {
                        return $data;
                    }
                    
                } else {
                    return $data; 
                } 
            } else {
                return 1;
            }
            
        } else {
            return  $response;
        }
    }
    function getClientes($filter,$page,$model){
        $where = " WHERE NID LIKE :NID OR Nombre LIKE :Nombre OR Apellido LIKE :Apellido";
        $array = array(
            'NID' => '%'.$filter.'%',
            'Nombre' => '%'.$filter.'%',
            'Apellido' => '%'.$filter.'%'
        );
        $columns = "IdClientes,NID,Nombre,Apellido,Email,Telefono,Direccion,Creditos";
        return $model->paginador($columns,"clientes","Clientes",$page,$where,$array);
    }
    function getReporteCliente($email){
        $where = " WHERE Email = :Email";
        $response1 = $this->db->select1("*",'clientes',$where,array('Email' => $email));
        if(is_array($response1)){
            $response1 = $response1['results'];
            if (0 != count($response1)) {
                $where = " WHERE IdClientes = :IdClientes";
                $response2 = $this->db->select1("*",'reportes_clientes',$where,array('IdClientes' => $response1[0]["IdClientes"]));
                if (is_array($response2)) {
                    $response2 = $response2['results'];
                    if (0 != count($response2)) {
                        
                        $data= array(
                                "Nombre" => $response1[0]["Nombre"],
                                "Apellido" => $response1[0]["Apellido"],
                                "Email" => $response1[0]["Email"],
                                "Creditos" => $response1[0]["Creditos"],
                                "IdReportes" => $response2[0]["IdReportes"],
                                "Deuda" => $response2[0]["Deuda"],
                                "FechaDeuda" => $response2[0]["FechaDeuda"],
                                "Pago" => $response2[0]["Pago"],
                                "FechaPago" => $response2[0]["FechaPago"],
                                "Ticket" => $response2[0]["Ticket"],
                                "IdClientes" => $response2[0]["IdClientes"]
                            );
                            Session::setSession("reportCliente",$data);
                        return $data;
                    } else {
                        return 0;
                    }
                    
                } else {
                    return  $response2;
                }
               
            } else {
                return 0;
            }
            
        }else{
            return  $response1;
        }
    }
    public function setPagos($model1,$model2,$idReporte){
        $Ticket = Codigo::Ticket($this->db,"ticket");
        if (is_numeric($Ticket)) {
            $value =  "Deuda = :Deuda,FechaDeuda = :FechaDeuda,Pago = :Pago,FechaPago = :FechaPago,Ticket = :Ticket,IdClientes = :IdClientes";
            $where = " WHERE IdReportes = ".$idReporte;
            $model1->Ticket = (string)$Ticket;
            $data = $this->db->update("reportes_clientes",$model1,$value, $where);
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
        
       
        
        //echo var_dump($model);
    }
    public function editCliente($idCliente,$cliente){
        $where = " WHERE Email = :Email";
        $response = $this->db->select1("*",'clientes',$where,array('Email' => $cliente->Email));
        if (is_array($response)) {
            $response = $response['results'];
            $value =  "NID = :NID,Nombre = :Nombre,Apellido = :Apellido,Email = :Email,Telefono = :Telefono,Direccion = :Direccion,Creditos = :Creditos";
            $where = " WHERE IdClientes = ".$idCliente;
            if (0 == count($response)) {
                $data = $this->db->update("clientes",$cliente,$value, $where);
                if (is_bool($data)) {
                    return 0;
                } else {
                    return  $data;
                }
                
            } else {
               if ($response[0]['IdClientes'] == $idCliente) {
                    $data = $this->db->update("clientes",$cliente,$value, $where);
                    if (is_bool($data)) {
                        return 0;
                    } else {
                        return  $data;
                    }
               }else{
                   return 1;
               }
            }
            
        } else {
           return $response;
        }
        
    }
    public function getTickets($filter,$page,$model){
        $where = " WHERE  Propietario = :Propietario AND Email LIKE :Email ";
        $array = array(
            'Propietario' => "Cliente",
            'Email' => '%'.$filter.'%'
        );
        return $model->paginador("*","ticket","Tickets",$page,$where,$array);
    }
}


?>