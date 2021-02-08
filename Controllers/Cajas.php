<?php

class Cajas extends Controllers
{
    function __construct()
    {
        parent::__construct();
    }

    public function cajas()
    {
        if (null != Session::getSession("User")) {
            $this->view->render($this, "cajas", null);
        } else {
            header("Location:" . URL);
        }
    }

    public function setCaja()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            if ("Admin" == $user["Roles"]) {
                $model = $this->TCajas(array(
                    $_POST["caja"],
                    true,
                    false,
                    "Sin usuario",
                ));
                echo $this->model->setCaja($model);
            }
        }
    }

    public function getCajas()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            $dataFilter = null;
            $data = $this->model->getCajas($_POST["page"], $this->page);
            if (is_array($data)) {
                $array = $data["results"];
                foreach ($array as $key => $value) {
                    if ($value["Asignada"]) {
                        $botonEstado = "<a  class='waves-effect waves-light btn-small green accent-4'>On</a> ";
                    } else {
                        if ($value["Estado"]) {
                            $botonEstado = "<a onclick='new Cajas().updateState(" . $value["IdCaja"] . "," . $value["Estado"] . ")' class='waves-effect waves-light btn-small green accent-4'>On</a> ";
                        } else {
                            $botonEstado = "<a onclick='new Cajas().updateState(" . $value["IdCaja"] . "," . $value["Estado"] . ")' class='waves-effect waves-light btn-small red darken-4'>Off</a> ";
                        }
                    }

                    if ($value["Asignada"]) {
                        $botonAsignada = "<a  class='waves-effect waves-light btn-small green accent-4'>On</a> ";
                    } else {
                        $botonAsignada = "<a  class='waves-effect waves-light btn-small red darken-4'>Off</a> ";
                    }
                    $dataFilter .= "<tr>" .
                        "<td>" . $value["Caja"] . "</td>" .
                        "<td>" . $botonEstado . "</td>" .
                        "<td>" . $value["Usuario"] . "</td>" .
                        "<td>" . $botonAsignada . "</td>" .
                        "</tr>";

                }
                $paginador = "<p>Resultados " . $data["pagi_info"] . "</p><p>" . $data["pagi_navegacion"] . "</p> ";
                echo json_encode(array(
                    "dataFilter" => $dataFilter,
                    "paginador" => $paginador
                ));
            } else {
                echo $data;
            }
        }
    }

    public function updateState()
    {
        $data = $this->model->updateState($_POST["idcaja"], $_POST["state"]);
        if (is_bool($data)) {
            echo 0;
        } else {
            echo $data;
        }
    }
}

?>