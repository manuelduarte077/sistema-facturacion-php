<?php

class Conexion
{
    function __construct()
    {
        $this->db = new QueryManager("root", "", "sistem_facturacion");
    }
}

?>