<?php

class Usuarios extends Controllers
{
    function __construct()
    {
        parent::__construct();

    }

    public function usuarios()
    {
        if (null != Session::getSession("User")) {
            $this->view->render($this, "usuarios", null);
        } else {
            header("Location:" . URL);
        }
    }

    function getRoles()
    {

        $data = $this->model->getRoles();
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
    }

    public function registerUser()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            if ("Admin" == $user["Roles"]) {
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
                                if (empty($_POST["password"])) {
                                    echo "El campo password es obligatorios";
                                } else {
                                    if (6 <= strlen($_POST["password"])) {
                                        if (empty($_POST["usuario"])) {
                                            echo "El campo usuario es obligatorios";
                                        } else {
                                            if (strcmp("Seleccione un role", $_POST["role"]) === 0) {
                                                echo "Seleccione un role";
                                            } else {
                                                $archivo = null;
                                                $tipo = null;

                                                $array = array(
                                                    $_POST["nid"], $_POST["nombre"], $_POST["apellido"], $_POST["telefono"],
                                                    $_POST["email"], password_hash($_POST["password"], PASSWORD_DEFAULT),
                                                    $_POST["usuario"], $_POST["role"], $_POST["email"] . ".png"
                                                );
                                                $data = $this->model->registerUser($this->userClass($array));
                                                if ($data == 1) {
                                                    echo "El email ya esta registrado..   ";
                                                } else {
                                                    if ($data == 0) {
                                                        if (isset($_FILES['file'])) {
                                                            $tipo = $_FILES['file']["type"];
                                                            $archivo = $_FILES['file']["tmp_name"];

                                                        }
                                                        $this->image->cargar_imagen($tipo, $archivo, $_POST["email"], "usuarios");
                                                        echo 0;
                                                    } else {
                                                        echo $data;
                                                    }


                                                }
                                            }

                                        }

                                    } else {
                                        echo "Ingrese una contraseña de 6 digitos o mas";
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

    public function getUsers()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            $count = 0;
            $dataFilter = null;
            $data = $this->model->getUsers($_POST["filter"], $_POST["page"], $this->page);
            if (is_array($data)) {
                $array = $data["results"];
                foreach ($array as $key => $value) {
                    $dataUser = json_encode($array[$count]);
                    $urlImage = URL . "Resource/images/fotos/usuarios/" . $value["Imagen"];
                    $dataFilter .= "<tr>" .
                        "<td >" .
                        "<ul class='collection'>" .
                        "<li class='collection-item avatar'>" .
                        "<img class='responsive circle' src='" . $urlImage . "'/>
                                </li>
                            </ul>
                        </td>" .
                        "<td>" . $value["Nombre"] . "</td>" .
                        "<td>" . $value["Usuario"] . "</td>" .
                        "<td>" . $value["Roles"] . "</td>" .
                        "<td>" .
                        "<a href='#modal1' onclick='dataUser(" . $dataUser . ")'  class='btn modal-trigger'>Edit</a> | " .

                        "<a href='#modal2' onclick='deleteUser(" . $dataUser . ")' class='btn red lighten-1 modal-trigger'>Delete</a>" .
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

    public function editUser()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            if ("Admin" == $user["Roles"]) {
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
                                if (empty($_POST["password"])) {
                                    echo "El campo password es obligatorios";
                                } else {
                                    if (6 <= strlen($_POST["password"])) {
                                        if (empty($_POST["usuario"])) {
                                            echo "El campo usuario es obligatorios";
                                        } else {
                                            if (strcmp("Seleccione un role", $_POST["role"]) === 0) {
                                                echo "Seleccione un role";
                                            } else {
                                                $archivo = null;
                                                $tipo = null;
                                                $imagen = null;
                                                if (isset($_FILES['file'])) {
                                                    $tipo = $_FILES['file']["type"];
                                                    $archivo = $_FILES['file']["tmp_name"];
                                                    $imagen = $this->image->cargar_imagen($tipo, $archivo, $_POST["email"], "usuarios");
                                                } else {
                                                    if (isset($_POST['imagen'])) {
                                                        $archivo = $_POST['imagen'];
                                                        $imagen = $this->image->cargar_imagen($tipo, $archivo, $_POST["email"], "usuarios");
                                                        if ($_POST['imagen'] != $_POST["email"] . ".png") {
                                                            $archivo = RQ . "images/fotos/usuarios/" . $archivo;
                                                            unlink($archivo);
                                                            $archivo = null;
                                                        }
                                                    }

                                                }
                                                $response = $this->model->getUser($_POST["idUsuario"]);
                                                if (is_array($response)) {
                                                    $array = array(
                                                        $_POST["nid"], $_POST["nombre"], $_POST["apellido"], $_POST["telefono"],
                                                        $_POST["email"], $response[0]['Password'], $_POST["usuario"], $_POST["role"], $imagen
                                                    );
                                                    echo $this->model->editUser($this->userClass($array), $_POST["idUsuario"]);
                                                } else {
                                                    echo $response;
                                                }
                                            }
                                        }

                                    } else {
                                        echo "Ingrese una contraseña de 6 digitos o mas";
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

    public function deleteUser()
    {
        $user = Session::getSession("User");
        if (null != $user) {
            if ("Admin" == $user["Roles"]) {
                echo $this->model->deleteUser($_POST["idUsuario"], $_POST["email"]);
            } else {
                echo "No tiene autorizacion";
            }
        }
    }

    public function destroySession()
    {
        $user = Session::getSession("User");
        $this->model->resetCaja($user["IdCaja"]);
        Session::destroy();
        header("Location:" . URL);
    }
}


?>