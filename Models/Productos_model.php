<?php
class Productos_model extends Conexion{
    function __construct(){
        parent::__construct();
    }
    function getCompras($filter,$page,$model){
        $where = " WHERE Descripcion LIKE :Descripcion";
        $array = array(
            'Descripcion' => '%'.$filter.'%'
        );
        return $model->paginador("*","compras_temp","CompraTempo",$page,$where,$array);
    }
    public function getProducto($idTemp){
        $where = " WHERE IdTemp = :IdTemp";
        $array = array(
            'IdTemp' => $idTemp
        );
        $Producto = $this->db->select1("*",'compras_temp',$where,$array );
        if (is_array($Producto)){
            $data = $Producto["results"];
            $codigo = Codigo::getCodeBarra($this->db,"productos");
            if (is_numeric($codigo)){
                Session::setSession("compras_temp",array(
                    'Codigo' => $data[0]["Codigo"],
                    'Precio' => $data[0]["Precio"],
                    'Cantidad' => $data[0]["Cantidad"]
             ));
                return array($data[0],$codigo);
            }else{
                return $codigo;
            }
        }else{
            return $Producto;
        }
    }
    public function getCodigo(){
        return Codigo::getCodeBarra($this->db,"productos");
     }
     public function registrarProducto($model1,$model2){
         try {
            $this->db->pdo->beginTransaction();
            $query1 = "INSERT INTO productos (Codigo,Descripcion,Precio,Departamento,Catgoria,Descuento,Dia,Mes,Year,Fecha,Compra,Image) VALUES (:Codigo,:Descripcion,:Precio,:Departamento,:Catgoria,:Descuento,:Dia,:Mes,:Year,:Fecha,:Compra,:Image)";
            $sth = $this->db->pdo->prepare($query1);
            $sth->execute((array)$model1);

            $query2 = "INSERT INTO bodega (Codigo,Existencia,Dia,Mes,Year,Fecha) VALUES (:Codigo,:Existencia,:Dia,:Mes,:Year,:Fecha)";
            $sth = $this->db->pdo->prepare($query2);
            $sth->execute((array)$model2);
            $this->db->pdo->commit();
            return 0;
         } catch (\Throwable $th) {
            $this->db->pdo->rollBack();
            return $th->getMessage();
         }
     }
     function getInventario($filter,$page,$model){
        $where = " WHERE Descripcion LIKE :Descripcion OR Codigo LIKE :Codigo";
        $array = array(
            'Descripcion' => '%'.$filter.'%',
            'Codigo' => '%'.$filter.'%'
        );
        return $model->paginador("*","productos","InventarioProducto",$page,$where,$array);
     }
     public function getDetalles($id){
        $where = " WHERE ID = :ID";
        $array = array(
            'ID' => $id
        );
        $Producto = $this->db->select1("*",'productos',$where,$array );
        if (is_array($Producto)){
            $data = $Producto["results"];
            Session::setSession("detalles",$data[0]);
            return $data[0];
        }else{
            return $Producto;
        }
     }
     public function actualizarProducto($model,$id){
         try {
            $this->db->pdo->beginTransaction();
            $query = "UPDATE productos SET Codigo = :Codigo,Descripcion = :Descripcion,Precio = :Precio,Departamento = :Departamento,Catgoria = :Catgoria,Descuento = :Descuento,Dia =:Dia ,Mes = :Mes,Year = :Year,Fecha = :Fecha,Compra = :Compra,Image = :Image WHERE ID = ".$id;
            $sth = $this->db->pdo->prepare($query);
            $sth->execute((array)$model);
            $this->db->pdo->commit();
            return 0;
         } catch (\Throwable $th) {
            $this->db->pdo->rollBack();
            return $e->getMessage();
         }
        
     }
}
?>