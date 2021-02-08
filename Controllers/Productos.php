<?php

class Productos extends Controllers
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
        if (null != $user) {
            if ("Admin" == $user["Roles"]) {
                $this->view->render($this, "productos", null);
            } else {
                header("Location:" . URL . "Principal/principal");
            }
        }
    }

    public function getCompras()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            $count = 0;
            $dataFilter = null;
            $data = $this->model->getCompras($_POST["search"], $_POST["page"], $this->page);
            if (is_array($data)) {
                $array = $data["results"];
                foreach ($array as $key => $value) {
                    $dataCompra = json_encode($array[$count]);
                    $urlImage = URL . "Resource/images/fotos/compras/" . $value["Codigo"] . ".png";
                    $dataFilter .= "<tr>" .
                        "<td >" .
                        "<ul class='collection'>" .
                        "<li class='collection-item avatar'>" .
                        "<img class='responsive circle' src='" . $urlImage . "'/>
                             </li>
                         </ul>
                     </td>" .
                        "<td>" . $value["Descripcion"] . "</td>" .
                        "<td>" . $value["Precio"] . "</td>" .
                        "<td>" .
                        "<a href='" . URL . "/Productos/registrar?IdTemp=" . $value["IdTemp"] . "' class='btn '>Registrar</a>  " .
                        "</td>" .
                        "</tr>";
                    $count++;
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

    public function registrar()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            if ("Admin" == $user["Roles"]) {
                $this->view->render($this, "registrar", null);
            } else {
                header("Location:" . URL . "Principal/principal");
            }
        }
    }

    public function getProducto()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            $data = $this->model->getProducto($_POST["idTemp"]);
            if (is_array($data)) {
                echo json_encode($data);
            } else {
                echo $data;
            }
        }
    }

    public function registrarProducto()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            if ("Admin" == $user["Roles"]) {
                if (empty($_POST["Descripcion"])) {
                    echo "El campo descripcion es obligatorios";
                } else {
                    if (empty($_POST["Precio"])) {
                        echo "El campo precio es obligatorios";
                    } else {
                        if (empty($_POST["Departamento"])) {
                            echo "El campo departamento es obligatorios";
                        } else {
                            if (empty($_POST["Categoria"])) {
                                echo "El campo categoria es obligatorios";
                            } else {

                                $compras_temp = Session::getSession("compras_temp");
                                $precio1 = (float)str_replace("$", "", str_replace(",", "", $compras_temp["Precio"]));
                                $precio2 = (float)$_POST["Precio"];
                                if ($precio2 > $precio1) {
                                    $img = file_get_contents(RQ . "images/fotos/compras/" . $compras_temp["Codigo"] . ".png");
                                    $codigoBarra = $this->model->getCodigo();
                                    $model1 = $this->TProductos(array(
                                        $codigoBarra,
                                        $_POST["Descripcion"],
                                        "$" . number_format($precio2),
                                        $_POST["Departamento"],
                                        $_POST["Categoria"],
                                        "%0.00",
                                        date("d"),
                                        date("m"),
                                        date("Y"),
                                        date("d/m/Y"),
                                        $compras_temp["Codigo"],
                                        base64_encode($img)
                                    ));
                                    $model2 = $this->TBodega(array(
                                        $codigoBarra,
                                        $compras_temp["Cantidad"],
                                        date("d"),
                                        date("m"),
                                        date("Y"),
                                        date("d/m/Y")
                                    ));
                                    // echo var_dump($model);
                                    echo $this->model->registrarProducto($model1, $model2);
                                } else {
                                    echo "El precio debe ser mayor al precio de compra";
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

    public function inventario()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            if ("Admin" == $user["Roles"] || "User" == $user["Roles"]) {
                $this->view->render($this, "inventario", null);
            } else {
                header("Location:" . URL . "Principal/principal");
            }
        }
    }

    public function getInventario()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            if ("Admin" == $user["Roles"] || "User" == $user["Roles"]) {
                $count = 0;
                $dataFilter = null;
                $data = $this->model->getInventario($_POST["search"], $_POST["page"], $this->page);
                if (is_array($data)) {
                    $array = $data["results"];
                    foreach ($array as $key => $value) {
                        $dataFilter .= "<div class='col s6 m2'>
                            <div class='card  darken-1'>
                                <div class='card-content blue-grey-text'>
                                    <center>
                                        <span>" . $value['Descripcion'] . "</span>
                                        <a href='" . URL . "Productos/detalles?Id=" . $value["ID"] . "'>
                                            <img class='imgProducto' src='data:image/jpeg;base64," . $value['Image'] . "' />
                                        </a>
                                    </center>
                                </div>
                                <div class='card-action'>
                                    <table class='tables'>
                                        <thead>
                                            <tr>
                                                <td>
                                                    Precio
                                                    " . $value['Precio'] . "
                                                </td>
                                                <td>
                                                    Descuento
                                                    " . $value['Descuento'] . "
                                                </td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>";
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
    }

    public function detalles()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            if ("Admin" == $user["Roles"] || "User" == $user["Roles"]) {
                $this->view->render($this, "detalles", null);
            } else {
                header("Location:" . URL . "Productos/inventario");
            }
        }
    }

    public function getDetalles()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            if ("Admin" == $user["Roles"] || "User" == $user["Roles"]) {
                $data = $this->model->getDetalles($_POST["Id"]);
                if (is_array($data)) {
                    echo json_encode($data);
                } else {
                    echo $data;
                }
            } else {
                header("Location:" . URL . "Productos/inventario");
            }
        }
    }

    public function actualizarProducto()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            if ("Admin" == $user["Roles"]) {
                if (empty($_POST["Descripcion"])) {
                    echo "El campo descripcion es obligatorios";
                } else {
                    if (empty($_POST["Precio"])) {
                        echo "El campo precio es obligatorios";
                    } else {
                        if (empty($_POST["Descuento"])) {
                            echo "El campo descuento es obligatorios";
                        } else {
                            if (empty($_POST["Departamento"])) {
                                echo "El campo departamento es obligatorios";
                            } else {
                                if (empty($_POST["Categoria"])) {
                                    echo "El campo categoria es obligatorios";
                                } else {
                                    $image = null;
                                    $detalles = Session::getSession("detalles");
                                    $precio = (float)str_replace(",", "", $_POST["Precio"]);
                                    if (isset($_FILES['file'])) {
                                        $archivo = $_FILES['file']["tmp_name"];
                                        $contents = file_get_contents($archivo);
                                        $image = base64_encode($contents);
                                    } else {
                                        $image = $detalles["Image"];
                                    }
                                    $model = $this->TProductos(array(
                                        $detalles["Codigo"],
                                        $_POST["Descripcion"],
                                        "$" . number_format($precio),
                                        $_POST["Departamento"],
                                        $_POST["Categoria"],
                                        "%" . $_POST["Descuento"],
                                        $detalles["Dia"],
                                        $detalles["Mes"],
                                        $detalles["Year"],
                                        $detalles["Fecha"],
                                        $detalles["Compra"],
                                        $image
                                    ));
                                    echo $this->model->actualizarProducto($model, $detalles["ID"]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function exportarInventarios()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            $data = $this->model->getInventario($_POST["search"], $_POST["page"], $this->page);
            if (is_array($data)) {
                if (0 < count($data["results"])) {
                    $this->export->exportarExcel($data["results"], "Inventarios.xls", "Inventarios");
                }

            } else {
                return $data;
            }
        }
    }
}


?>