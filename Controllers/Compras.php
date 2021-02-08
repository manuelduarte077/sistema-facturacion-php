<?php
class Compras extends Controllers
{
    private $archivo = null;
    private $tipo = null;
    function __construct()
	{
        parent::__construct();
    }
    public function productos()
    {
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Roles"]){
                $this->view->render($this,"productos",null);
            }else{
                header("Location:".URL."Principal/principal");
            }
        }else{
            header("Location:".URL);
        }
    }
    public function compras()
    {
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Roles"]){
                Session::setSession("Compra","");
                $this->view->render($this,"compras",null);
            }else{
                header("Location:".URL."Principal/principal");
            }
        }else{
            header("Location:".URL);
        }
    }
    public function getProveedores(){
       
        $user = Session::getSession("User");
         if (null != $user) {
             $count = 0;
             $dataFilter = null;
             $data = $this->model->getProveedores($_POST["search"],$_POST["page"],$this->page);
             if(is_array($data)){
                 $array = $data["results"] ;
                 foreach ($array as $key => $value) {
                     $dataProveedor =json_encode($array[$count]);
                     $urlImage = URL."Resource/images/fotos/proveedores/".$value["Email"].".png";
                     $url = URL."Proveedores/reportes/?email=".$value["Email"];
                     //$url = URL."Clientes/reportes/".$value["Email"];
                    
                     $dataFilter .= "<tr>" .
                     "<td >".
                         "<ul class='collection'>".
                             "<li class='collection-item avatar'>".
                                 "<img class='responsive circle' src='".$urlImage."'/>
                             </li>
                         </ul>
                     </td>".
                     "<td>".$value["Proveedor"]."</td>".
                    "<td>".
                    "<a onclick='compras.dataProveedor(".$dataProveedor .")' class='btn-small'>Select</a>  ".
                    "</td>".
                "</tr>";
                $count++;
                 }
                 $paginador ="<p>Resultados " .$data["pagi_info"]."</p><p>".$data["pagi_navegacion"]."</p> ";
                 echo json_encode( array(
                     "dataFilter" => $dataFilter,
                     "paginador" => $paginador
                 ));
             }else{
                 echo $data;
             }
         }
     }
     public function detallesCompras(){
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Roles"]) {
                if (empty($_POST["Descripcion"])) {
                    echo "El campo Descripcion es obligatorios";
                } else {
                    if (empty($_POST["Precio"])) {
                        echo "El campo Precio es obligatorios";
                    } else {
                        $Codigo = $this->model->getCodigo("compras",$_POST["Email"]);
                        $Proveedor = $this->model->getProveedor($_POST["IdProveedor"],$_POST["Email"]);
                       // echo var_dump( $Proveedor);
                        Session::setSession("Compra",array(
                            $_POST["Descripcion"],
                            $_POST["Cantidad"],
                            $_POST["Precio"],
                            0,
                            $_POST["IdProveedor"],
                            $_POST["Proveedor"],
                            $_POST["Email"],
                            $_POST["Credito"],
                            0,
                            $Codigo
                        ));
                        if (is_numeric($Codigo)){
                            if(is_array($Proveedor)){
                                if(isset($_FILES['file'])){
                                    $this->tipo =  $_FILES['file']["type"];
                                    $this->archivo =  $_FILES['file']["tmp_name"];
                                }
                                $this->image->cargar_imagen($this->tipo,$this->archivo,$Codigo,"compras");
                                echo json_encode( array(
                                    "Codigo" => $Codigo,
                                    "results" => $Proveedor
                                ));
                            }else{
                                echo $Proveedor;
                            }
                        }else{
                            echo $Codigo;
                        }
                    
                    }
                }

            }
        }
     }
     public function Detalles(){
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Roles"]){
                $this->view->render($this,"detalles",null);
            }else{
                header("Location:".URL."Principal/principal");
            }
        }else{
            header("Location:".URL);
        }
     }
     public function comprar(){
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Roles"]){
               echo $this->model->comprar(
                    $this->TCompras(array()),
                    $this->TCompras_temp(Session::getSession("Compra"))
                );
            }
        }
     }
     public function getCompras(){
        $user = Session::getSession("User");
        if (null != $user){
            $count = 0;
            $dataFilter = null;
            $data = $this->model->getCompras($_POST["search"],$_POST["page"],$this->page);
            if (is_array($data)) {
                $array = $data["results"] ;
                foreach ($array as $key => $value){
                    $dataCompra =json_encode($array[$count]);
                    $urlImage = URL."Resource/images/fotos/compras/".$value["Codigo"].".png";
                    $dataFilter .= "<tr>" .
                     "<td >".
                         "<ul class='collection'>".
                             "<li class='collection-item avatar'>".
                                 "<img class='responsive circle' src='".$urlImage."'/>
                             </li>
                         </ul>
                     </td>".
                     "<td>".$value["Descripcion"]."</td>".
                     "<td>".$value["Precio"]."</td>".
                    "<td>".
                    "<a  class='btn-small' onclick='compras.getCompra(".$dataCompra .")'>Detalles</a>  ".
                    "</td>".
                "</tr>";
                $count++;
                }
                $paginador ="<p>Resultados " .$data["pagi_info"]."</p><p>".$data["pagi_navegacion"]."</p> ";
                echo json_encode( array(
                    "dataFilter" => $dataFilter,
                    "paginador" => $paginador
                ));
                
            } else {
                echo $data;
            }
            
        }
     }
     public function exportarCompras()
     {
        $user = Session::getSession("User");
        if(null != $user ){
            $data = $this->model->getCompras($_POST["search"],$_POST["page"],$this->page);
            if(is_array($data)){
                $this->export->exportarExcel($data["results"],"Compras.xls","Compras");
            }else{
                return $data;
            }
        }
     }
}


?>