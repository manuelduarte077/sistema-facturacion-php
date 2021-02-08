<?php
class Index extends Controllers
{
    public function __construct() {
        parent::__construct();
    }
    public function index(){
        $user = $_SESSION["User"] ?? null;

        if (null != $user) {
            header("Location:".URL."Principal/principal");
        } else {
            $this->view->render($this,"index",null);
        }   
       
    }
    public function userLogin(){
        if(isset($_POST["email"])){
            if (!empty($_POST["password"])) {
               if (6 <= strlen($_POST["password"])) {
                    $data =  $this->model->userLogin(
                        $_POST["email"],
                        $_POST["password"],
                        $this->TCajas_registros(array()),
                        $this->TCajas(array())
                    );
                    if (is_array($data)) {
                        echo json_encode($data);
                    } else {
                        echo $data;
                    }
               } else {
                  echo 2;
               }   
            } else {
                echo 1;
            }
            
         /*  // echo password_hash($_POST["password"], PASSWORD_DEFAULT);
            */
            
        }
    }
}


?>