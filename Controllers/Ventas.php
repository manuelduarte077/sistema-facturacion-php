<?php

class Ventas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function ventas()
    {
        if (null != Session::getSession("User")) {
            $this->view->render($this, "ventas", null);
        } else {
            header("Location:" . URL);
        }

    }

    public function SearchProducto()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            echo $this->model->getProducto($_POST["searchCode"], $this->TTempo_ventas(array()));
        }
    }

    public function GetProductos()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            $dataFilter = null;
            $importe = 0;
            $data = $this->model->GetProductos($_POST["page"], $this->page);
            if (is_array($data)) {

                foreach ($data["results"] as $key => $value) {
                    $dataFilter .= "<tr>" .
                        "<td>" . $value["Descripcion"] . "</td>" .
                        "<td>" . $value["Cantidad"] . "</td>" .
                        "<td>" . $value["Precio"] . "</td>" .
                        "</tr>";
                    $importe += $value["Importe"];
                }
                $paginador = "<p>Resultados " . $data["pagi_info"] . "</p><p>" . $data["pagi_navegacion"] . "</p> ";
                echo json_encode(array(
                    "productos" => array(
                        "importe" => $importe
                    ),
                    "dataFilter" => $dataFilter,
                    "paginador" => $paginador
                ));
            } else {
                echo $data;
            }
        }
    }
}


?>