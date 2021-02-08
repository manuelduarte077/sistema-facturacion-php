<?php
class Uploadimage 
{
    function cargar_imagen($tipo,$imagen,$email,$carpeta){
        $destino ="./Resource/images/fotos/".$carpeta."/".$email.".png";
        if (strstr($tipo,"image")) {
            move_uploaded_file($imagen, $destino);
           
        }else{
            if(null == $imagen){
                $archivo = RQ."images/fotos/".$carpeta."/default.png";
            }else{
                $archivo = RQ."images/fotos/".$carpeta."/".$imagen;
            }
           
            copy ( $archivo  ,$destino );
        }
        return $email.".png";
    }
}



?>