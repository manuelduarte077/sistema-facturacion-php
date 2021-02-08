<?php
class Ventas_model extends Conexion
{
    function __construct(){
        parent::__construct();
    }
    public function getProducto($codigo,$model){
        $dia = date("d");
        $mes = date("m");
        $year = date("Y");
        $fecha = date("d/m/Y");
        try {
            $this->db->pdo->beginTransaction();
            $where = " WHERE Codigo = :Codigo";
            $bodega = $this->db->select1("*",'bodega',$where,array('Codigo' => $codigo));
            if (is_array($bodega)){
                if (0 < count($bodega)){
                    $where = " WHERE Codigo = :Codigo";
                    $producto = $this->db->select1("*",'productos',$where,array('Codigo' => $codigo));
                    if (is_array($producto)){
                        if (0 < count($producto)){
                            $user = Session::getSession("User");
                            $where = " WHERE Usuario = :Usuario AND Dia = :Dia AND Mes = :Mes";
                            $array = array('Usuario' => $user["Usuario"],
                                'Dia' => $dia,'Mes' => $mes,
                            );
                            $caja = $this->db->select1("*",'cajas_registros',$where,$array);
                            $dataCaja = end($caja["results"]) ;
                            $dataProducto = $producto["results"];
                            $dataBodega = $bodega["results"];
                            $where = " WHERE Codigo = :Codigo AND Caja = :Caja AND IdUsuario = :IdUsuario";
                            $array1 = array('Codigo' => $codigo,
                            'Caja' => $dataCaja["Caja"],
                            'IdUsuario' => $dataCaja["IdUsuario"],
                            );
                            $tempo = $this->db->select1("*",'tempo_ventas',$where,$array1);
                            if (is_array($tempo)){
                                $precio = (float)str_replace("$", "", str_replace(",", "", $dataProducto[0]["Precio"]));
                                $model->Codigo = $codigo;
                                $model->Descripcion = $dataProducto[0]["Descripcion"];
                                $model->Precio = $precio;
                                $model->Caja = $dataCaja["Caja"];
                                $model->IdUsuario = $dataCaja["IdUsuario"];
                                if (0 == count($tempo["results"])){
                                   
                                    $model->Cantidad = 1;
                                    $model->Importe = $precio;
                                   
                                    $query1 = "INSERT INTO tempo_ventas (Codigo,Descripcion,Precio,Cantidad,Importe,Caja,IdUsuario) VALUES (:Codigo,:Descripcion, :Precio,:Cantidad,:Importe,:Caja,:IdUsuario)";
                                   
                                }else{
                                    $tempo = $tempo["results"];
                                    $cant = $tempo[0]["Cantidad"] +1;
                                    $importe = $precio * $cant;
                                    $model->Cantidad = $cant;
                                    $model->Importe = $importe;
                                    $query1 = "UPDATE tempo_ventas SET Codigo = :Codigo,Descripcion = :Descripcion,Precio = :Precio,Cantidad = :Cantidad,Importe = :Importe,Caja = :Caja,IdUsuario =:IdUsuario WHERE Id = ".$tempo[0]["Id"];
                                }
                                $sth = $this->db->pdo->prepare($query1);
                                $sth->execute((array)$model);
                                $this->db->pdo->commit();
                                return 0;
                            }else{
                                return $tempo;
                            }
                        }else{
                            return "No hay productos en existencia";
                        }
                    }else{
                        return $producto; 
                    }
                    
                }else{
                    return "No hay productos en existencia";
                }
            }else{
                return $bodega;
            }
        } catch (\Throwable $th) {
            $this->db->pdo->rollBack();
            return $th->getMessage();
        }
    }
    function GetProductos($page,$model){
        return $model->paginador("*","tempo_ventas","Productos",$page,null,null);
    }
}


?>