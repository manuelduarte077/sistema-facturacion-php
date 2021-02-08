<?php
class Cajas_model extends Conexion{
    function __construct(){
        parent::__construct();
    }
    function setCaja($model){
        $where = " WHERE Caja = :Caja";
        $array = array(
            'Caja' => $model->Caja
        );
        $cajas = $this->db->select1("Caja",'cajas',$where,$array );
        if (is_array($cajas)){
            if (0 == count($cajas)){
                $value = " (Caja,Estado,Asignada,Usuario) VALUES (:Caja,:Estado,:Asignada,:Usuario)";
                $data =  $this->db->insert("cajas",$model,$value);
                if (is_bool($data)){
                    return 0;
                }else{
                    return $data ;
                }
            }else{
                return "El numero de caja ya esta registrado" ; 
            }
        }else{
            return $cajas;
        }
    }
    function getCajas($page,$model){
        $where = " WHERE Caja LIKE :Caja";
        $array = array(
            'Caja' => '%'."".'%',
        );
        $columns = "ID,Caja,Estado,Asignada,Usuario";
        return $model->paginador("*","cajas","Cajas",$page,$where,$array);
    }
    function updateState($IdCaja,$state){
        $value =  "Estado = :Estado";
        $where = " WHERE IdCaja = ".$IdCaja;
        $state = $state == 0 ? 1:0;
        $array = array(
            "Estado" => $state
        );
        return $this->db->update("cajas",$array,$value, $where);
    }
}

?>