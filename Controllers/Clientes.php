<?php
class Clientes extends Controllers 
{
    private $archivo = null;
    private $tipo = null;
    function __construct()
	{
        parent::__construct();
    }
    public function clientes(){
        if (null != Session::getSession("User")) {
            $this->view->render($this,"clientes",null);
        } else {
            header("Location:".URL);
        }  
    }
    public function getCreditos(){
        $user = Session::getSession("User");
        if(null != $user){
            $data = $this->model->getCreditos();
            if(is_array($data)){
                echo json_encode($data);
            }else{
                echo $data;
            }
        }
    }
    public function registerCliente(){
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Roles"]) {
                if (empty($_POST["nombre"])) {
                    echo "El campo nombre es obligatorios";
                } else {
                    if (empty($_POST["apellido"])) {
                        echo "El campo apellido es obligatorios";
                    } else {
                        if (empty($_POST["nid"])) {
                            echo "El campo nid es obligatorios";
                        } else {
                            if (empty($_POST["telefono"])) {
                                echo "El campo telefono es obligatorios";
                            } else {
                                if (empty($_POST["email"])) {
                                    echo "El campo email es obligatorios";
                                } else {
                                    if (empty($_POST["direccion"])) {
                                        echo "El campo direccion es obligatorios";
                                    } else {
                                        
                                        $array1 = array(
                                            $_POST["nid"],$_POST["nombre"],$_POST["apellido"],$_POST["telefono"],$_POST["email"],$_POST["direccion"],$_POST["creditos"]);
                                        $array2 = array("$0.00","--/--/--","$0.00","--/--/--","000000",0);
                                        $data = $this->model->registerCliente($this->clientesClass($array1),$this->reportClientesClass($array2));
                                        if ($data == 1) {
                                            echo "El email ".$_POST["email"]." ya esta registrado..   ";
                                        } else {
                                            if ($data == 0){
                                                if(isset($_FILES['file'])){
                                                    $this->tipo =  $_FILES['file']["type"];
                                                    $this->archivo =  $_FILES['file']["tmp_name"];
                                                }
                                                $this->image->cargar_imagen($this->tipo,$this->archivo,$_POST["email"],"clientes");
                                                echo 0;
                                            }else{
                                                echo $data;
                                            }
                                            
                                        }
                                        
                                            
                                    }
                                }
                            }
                        }
                    }
                }
                
            } else {
                echo "No tiene autorizacion";
            }
        }
    }
    public function getClientes(){
        $user = Session::getSession("User");
        if (null != $user) {
            $count = 0;
            $dataFilter = null;
            $data = $this->model->getClientes($_POST["search"],$_POST["page"],$this->page);
            if(is_array($data)){
                $array = $data["results"] ;
                foreach ($array as $key => $value) {
                    $dataCliente =json_encode($array[$count]);
                    $urlImage = URL."Resource/images/fotos/clientes/".$value["Email"].".png";
                    $url = URL."Clientes/reportes/?email=".$value["Email"];
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
                   "<td>".$value["Nombre"]."</td>".
                   "<td>".$value["Apellido"]."</td>".
                   "<td>".$botonReporte.
                   "<a href='#modal1' onclick='cliente.dataCliente(".$dataCliente .")'  class='btn modal-trigger'>Edit</a>  ".
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
    public function reportes(){
        $user = Session::getSession("User");
        if(null != $user ){
            if ($user["Roles"] == "Admin"){
               
                $this->view->render($this,"reportes", null);
              
            }else{
                header("Location:".URL."Clientes/clientes");
            }
        }
    }
    public function getReporteCliente(){
        $user = Session::getSession("User");
        if(null != $user ){
            if ($user["Roles"] == "Admin"){
                if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $data = $this->model->getReporteCliente($_POST["email"]);
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
                    
                } else {
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
                    //$array = json_decode($_POST["report"], true);
                    //$array = $array["array"];
                    $array =Session::getSession("reportCliente");
                    $deuda = (float) str_replace("$", "", $array["Deuda"]);
                    if ($deuda == 0) {
                        echo "El cliente no contiene deuda";
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
                            $array["IdClientes"]
                        );
                        $ticket = array(
                            "Cliente" ,
                            "$".number_format($deuda),
                            date("d/mY"),
                            "$".$pago,
                            date("d/m/Y"),
                            $array["Ticket"],
                            $array["Email"]
                        );
                        echo $this->model->setPagos($this->reportClientesClass($arrayReport),$this->ticketClass($ticket),$array["IdReportes"]);
                       }
                       
                    }
                    
                } else {
                    echo "El dato ingresado no es correcto";
                }
                
            }
        }
       
    }
    public function editCliente(){
        $user = Session::getSession("User");
        if(null != $user){
            if ("Admin"== $user["Roles"]) {
                if (empty($_POST["nombre"])) {
                    echo "El campo nombre es obligatorios";
                } else {
                    if (empty($_POST["apellido"])) {
                        echo "El campo apellido es obligatorios";
                    } else {
                        if (empty($_POST["nid"])) {
                            echo "El campo nid es obligatorios";
                        } else {
                            if (empty($_POST["telefono"])) {
                                echo "El campo telefono es obligatorios";
                            } else {
                                if (empty($_POST["email"])) {
                                    echo "El campo email es obligatorios";
                                } else {
                                    if (empty($_POST["direccion"])) {
                                        echo "El campo direccion es obligatorios";
                                    } else {
                                        $array1 = array(
                                            $_POST["nid"],$_POST["nombre"],$_POST["apellido"],$_POST["telefono"],$_POST["email"],$_POST["direccion"],$_POST["creditos"]);
                                            $data = $this->model->editCliente($_POST["idCliente"],$this->clientesClass($array1));
                                            if ($data == 1) {
                                                echo "El email ".$_POST["email"]." ya esta registrado..   ";
                                            } else {
                                                if ($data == 0) {
                                                    if(isset($_FILES['file'])){
                                                        $this->tipo =  $_FILES['file']["type"];
                                                        $this->archivo =  $_FILES['file']["tmp_name"];
                                                    }
                                                    $this->image->cargar_imagen($this->tipo,$this->archivo,$_POST["email"],"clientes");
                                                    echo 0;
                                                } else {
                                                    return $data;
                                                }
                                                
                                                
                                            }

                                            
                                    }
                                }
                            }
                        }
                    }
                }
                
            } else {
                echo "No tiene autorizacion";
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
                $archivo = "TicketClientes.xls";
                $data = $this->model->getTickets($_POST["search"],$_POST["page"],$this->page);
            }else{
                $title = "Clientes";
                $archivo = "Clientes.xls";
                $data = $this->model->getClientes($_POST["search"],$_POST["page"],$this->page);
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