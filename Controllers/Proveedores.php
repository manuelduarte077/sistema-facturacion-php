<?php

class Proveedores extends Controllers 
{
    private $archivo = null;
    private $tipo = null;
    function __construct()
	{
        parent::__construct();
    }
    public function proveedores()
    {
        if (null != Session::getSession("User")){
            $this->view->render($this,"proveedores",null);
        }else{
            header("Location:".URL);
        }
    }
    public function registrar()
    {
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Roles"]){
                $this->view->render($this,"registrar",null);
            }else{
                $error =  new Errors();
                $error ->error();
            }
            
        }
    }
    public function registerProvedores()
    {
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Roles"]){
               if (empty($_POST["proveedor"])) {
                   echo "El campo proveedor es obligatorios";
               } else {
                    if (empty($_POST["telefono"])) {
                        echo "El campo telefono es obligatorios";
                    } else {
                        if (empty($_POST["email"])) {
                            echo "El campo email es obligatorios";
                        } else {
                            if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                                if (empty($_POST["direccion"])) {
                                    echo "El campo direccion es obligatorios";
                                } else {
                                    $array1 = array(
                                        $_POST["proveedor"],$_POST["telefono"],
                                        $_POST["email"],$_POST["direccion"]);
                                        //echo var_dump( $this->proveedoresClass($array1));
                                        $array2 = array("$0.00","--/--/--","$0.00","--/--/--","000000",0);
                                       // echo var_dump($this->reportProveedores($array2));
                                       $data = $this->model->registerProvedores($this->proveedoresClass($array1),$this->reportProveedores($array2));
                                    if ($data == 1) {
                                        echo "El email ".$_POST["email"]." ya esta registrado..   ";
                                    } else {
                                        if ($data == 0){
                                            if(isset($_FILES['file'])){
                                                $this->tipo =  $_FILES['file']["type"];
                                                $this->archivo =  $_FILES['file']["tmp_name"];
                                            }
                                            $this->image->cargar_imagen($this->tipo,$this->archivo,$_POST["email"],"proveedores");
                                            echo 0;
                                         }else{
                                            echo $data;
                                        }        
                                        
                                    }
                                }
                            }else{
                                echo "El campo email no es valido";
                            }
                        }
                    }
               }
               
            }else{
                $error =  new Errors();
                $error ->error();
            }
            
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
                    ///$dataCliente =json_encode($array[$count]);
                    $urlImage = URL."Resource/images/fotos/proveedores/".$value["Email"].".png";
                    $url = URL."Proveedores/reportes/?email=".$value["Email"];
                    //$url = URL."Clientes/reportes/".$value["Email"];
                    if ($user["Roles"] == "Admin") {
                        $botonReporte = "<a href='".$url."' class='btn cyan darken-3 lighten-1 modal-trigger'>Reportes</a> |";
                    } else {
                        $botonReporte = "";
                    }
                    
                    $dataFilter .= "<tr>" .
                    "<td >".
                        "<ul class='collection'>".
                            "<li class='collection-item avatar'>".
                                "<img class='responsive circle' src='".$urlImage."'/>
                            </li>
                        </ul>
                    </td>".
                    "<td>".$value["Proveedor"]."</td>".
                   "<td>".$botonReporte.
                   "<a href='".URL."/Proveedores/registrar?email=".$value["Email"]."' class='btn modal-trigger'>Edit</a>  ".
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
    public function dataProveedor()
    {
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Roles"]){
                if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                    echo $data = $this->model->dataProveedor($_POST["email"]);
                }else{
                   echo 2;
                }
            }else{
                $error =  new Errors();
                $error ->error();
            }
        }else{
            echo 1;
        }
    }
    public function editProveedor(){
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Roles"]){
               if (empty($_POST["proveedor"])) {
                   echo "El campo proveedor es obligatorios";
               } else {
                    if (empty($_POST["telefono"])) {
                        echo "El campo telefono es obligatorios";
                    } else {
                        if (empty($_POST["email"])) {
                            echo "El campo email es obligatorios";
                        } else {
                            if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                                if (empty($_POST["direccion"])) {
                                    echo "El campo direccion es obligatorios";
                                } else {
                                   
                                    $array = array(
                                        $_POST["proveedor"],$_POST["telefono"],
                                        $_POST["email"],$_POST["direccion"]);
                                      
                                        echo $data = $this->model->editProveedor($this->proveedoresClass($array),$_POST["idProveedor"]);

                                    if ($data == 1) {
                                        echo "El email ".$_POST["email"]." ya esta registrado..   ";
                                    } else {
                                        if ($data == 0){
                                            if(isset($_FILES['file'])){
                                                $this->tipo =  $_FILES['file']["type"];
                                                $this->archivo =  $_FILES['file']["tmp_name"];
                                            }
                                            $this->image->cargar_imagen($this->tipo,$this->archivo,$_POST["email"],"proveedores");
                                            echo 0;
                                         }else{
                                            echo $data;
                                        }        
                                        
                                    }
                                }
                            }else{
                                echo "El campo email no es valido";
                            }
                        }
                    }
               }
               
            }else{
                $error =  new Errors();
                $error ->error();
            }
            
        }
    }
    public function reportes(){
        $user = Session::getSession("User");
        if(null != $user ){
            if ($user["Roles"] == "Admin"){
                $this->view->render($this,"reportes", null);
            }else{
                header("Location:".URL."Proveedores/proveedores");
            }
        }
    }
    public function getReporteProveedor(){
        $user = Session::getSession("User");
        if(null != $user ){
            if ($user["Roles"] == "Admin"){
                if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $data = $this->model->getReporteProveedor($_POST["email"]);
                    if (is_array($data)) {
                        echo json_encode( array(
                            "array" =>$data,
                            "data" => 1,
                        ));
                    } else {
                        echo json_encode( array(
                            "data" => 0,
                        ));
                    }
                }else{
                    echo json_encode( array(
                        "data" => 0,
                    ));
                }
            }
        }
    }
    public function setPagos()
    {
        $user = Session::getSession("User");
        if(null != $user ){
            date_default_timezone_set('UTC');
            if ($user["Roles"] == "Admin"){
                $pago = (float) $_POST["pagos"];
                if (is_float($pago) && 0 < $pago) {
                    $pago = number_format($pago);
                   
                    $array =Session::getSession("reportProveedor");
                    $deuda = (float) str_replace("$", "", $array["Deuda"]);
                    if ($deuda == 0) {
                        echo "El sistema no contiene deuda";
                    } else {
                       if ($deuda < $pago) {
                        echo "Sea sobre pasado del pago de la deuda";
                       } else {
                           $deuda = $deuda - $pago;
                           $arrayReport = array(
                            "$".number_format($deuda),
                            date("d/m/Y"),
                            "$".$pago,
                            date("d/m/Y"),
                            $array["Ticket"],
                            $array["IdProveedor"]
                        );
                        $ticket = array(
                            "Proveedor" ,
                            "$".number_format($deuda),
                            date("d/m/Y"),
                            "$".$pago,
                            date("d/m/Y"),
                            $array["Ticket"],
                            $array["Email"]
                        );
                        echo $this->model->setPagos($this->reportProveedores($arrayReport),$this->ticketClass($ticket),$array["IdProveedor"]);
                       }
                       
                    }
                    
                } else {
                    echo "El dato ingresado no es correcto";
                }
                
            }
        }
    }
    public function getTickets(){
        $user = Session::getSession("User");
        if(null != $user ){
            $dataFilter = null;
            $data = $this->model->getTickets($_POST["search"],$_POST["page"],$this->page);
            if (is_array($data)) {
                foreach ($data["results"] as $key => $value) {
                    $dataFilter .= "<tr>" .                    
                        "<td>".$value["Deuda"]."</td>".
                        "<td>".$value["FechaDeuda"]."</td>".
                        "<td>".$value["Pago"]."</td>".
                        "<td>".$value["FechaPago"]."</td>".
                        "<td>".$value["Ticket"]."</td>".
                        "</td>".
                    "</tr>";
                }
                $paginador ="<p>Resultados " .$data["pagi_info"]."</p><p>".$data["pagi_navegacion"]."</p> ";
                echo json_encode( array(
                    "dataFilter" => $dataFilter,
                    "paginador" => $paginador
                ));
            } else {
                return $data;
            }
            
        }
    }
    public function exportarExcel(){
        $user = Session::getSession("User");
        if(null != $user ){
            $archivo = null;
            $title = null;
            $data = null;
            if(1 == $_POST["valor"]){
                $title = "Ticket";
                $archivo = "TicketProveedores.xls";
                $data = $this->model->getTickets($_POST["search"],$_POST["page"],$this->page);
            }else{
                $title = "Proveedores";
                $archivo = "Proveedores.xls";
                $data = $this->model->getProveedores($_POST["search"],$_POST["page"],$this->page);
            }
            
            if(is_array($data)){
                $this->export->exportarExcel($data["results"],$archivo,$title);
            }else{
                return $data;
            }
        }
    }
}

?>