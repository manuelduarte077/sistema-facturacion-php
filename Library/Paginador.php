<?php
class Paginador extends Conexion
{
    public function __construct(){
        parent::__construct();
    }
    public function paginador($columns,$table,$method,$page,$where,$array){
        $_pagi_enlace = null; 
        //cantidad de resultados por página 
        $_pagi_cuantos = 6; 
        //cantidad de enlaces que se mostrarán como máximo en la barra de navegación 
        $_pagi_nav_num_enlaces = 10; 
        //Decidimos si queremos que se muestren los errores de mysql 
        $_pagi_mostrar_errores = false; 
        //definimos qué irá en el enlace a la página anterior 
        $_pagi_nav_anterior = " &laquo; Anterior ";// podría ir un tag <img> o lo que sea 
        //definimos qué irá en el enlace a la página siguiente 
        $_pagi_nav_siguiente = " Siguiente &raquo; ";// podría ir un tag <img> o lo que sea 

        //definimos qué irá en el enlace a la página siguiente 
        if(!isset($_pagi_nav_primera)){
            $_pagi_nav_primera = " &laquo; Primero "; 
        } 
        if(!isset($_pagi_nav_ultima)){ 
            $_pagi_nav_ultima = " Último &raquo; "; 
        } 
        if (empty($page)){
            // Si no se ha hecho click a ninguna página específica 
            // O sea si es la primera vez que se ejecuta el script 
            // $_pagi_actual es la página actual-->será por defecto la primera. 
            $_pagi_actual = 1; 
        }else{
            // Si se "pidió" una página específica: 
            // La página actual será la que se pidió. 
            $_pagi_actual = $page; 
        }
        //Consulta a la base de datos  y la tabla 
        $response = $this->db->select1($columns,$table,$where,$array);
        if (is_array($response)) {
            $_pagi_result = $response["results"];
        } else {
            return $response;
        }
        $_pagi_totalReg = count($_pagi_result);
         // Calculamos el número de páginas (saldrá un decimal) 
        // con ceil() redondeamos y $_pagi_totalPags será el número total (entero) de páginas que tendremos 
        $_pagi_totalPags = ceil($_pagi_totalReg / $_pagi_cuantos); 
        // La variable $_pagi_navegacion contendrá los enlaces a las páginas. 
        $_pagi_navegacion_temporal = array(); 
        if ($_pagi_actual != 1) {
           // Si no estamos en la página 1. Ponemos el enlace "primera" 
           $_pagi_url = 1; //será el número de página al que enlazamos 
           $_pagi_navegacion_temporal[] = "<a id='paginas1'href='#' onclick='"."get".$method."(".$_pagi_url.")'>$_pagi_nav_primera</a>"; 

           // Si no estamos en la página 1. Ponemos el enlace "anterior" 
           $_pagi_url = $_pagi_actual - 1; //será el número de página al que enlazamos 
           $_pagi_navegacion_temporal[] = "<a id='paginas1'href='#' onclick='"."get".$method."(".$_pagi_url.")'>$_pagi_nav_anterior</a>"; 
        }
        // La variable $_pagi_nav_num_enlaces sirve para definir cuántos enlaces con  números de página se mostrarán como máximo
        if (!isset($_pagi_nav_num_enlaces)) {
           // Si no se definió la variable $_pagi_nav_num_enlaces 
            // Se asume que se mostrarán todos los números de página en los enlaces. 
            $_pagi_nav_desde = 1;//Desde la primera 
            $_pagi_nav_hasta = $_pagi_totalPags;//hasta la última 
        } else {
            // Si se definió la variable $_pagi_nav_num_enlaces 
            // Calculamos el intervalo para restar y sumar a partir de la página actual 
            $_pagi_nav_intervalo = ceil($_pagi_nav_num_enlaces/2) - 1;
            // Calculamos desde qué número de página se mostrará 
            $_pagi_nav_desde = $_pagi_actual - $_pagi_nav_intervalo; 
            // Calculamos hasta qué número de página se mostrará 
            $_pagi_nav_hasta = $_pagi_actual + $_pagi_nav_intervalo; 
            // Ajustamos los valores anteriores en caso sean resultados no válidos 
            
            // Si $_pagi_nav_desde es un número negativo 
            if($_pagi_nav_desde < 1){
                // Le sumamos la cantidad sobrante al final para mantener el número de enlaces que se quiere mostrar.  
                $_pagi_nav_hasta -= ($_pagi_nav_desde - 1); 
                // Establecemos $_pagi_nav_desde como 1. 
                $_pagi_nav_desde = 1; 
            }
             // Si $_pagi_nav_hasta es un número mayor que el total de páginas 
             if($_pagi_nav_hasta > $_pagi_totalPags){
                 // Le restamos la cantidad excedida al comienzo para mantener el número de enlaces que se quiere mostrar. 
                $_pagi_nav_desde -= ($_pagi_nav_hasta - $_pagi_totalPags); 
                // Establecemos $_pagi_nav_hasta como el total de páginas. 
                $_pagi_nav_hasta = $_pagi_totalPags; 
                // Hacemos el último ajuste verificando que al cambiar $_pagi_nav_desde no haya quedado con un valor no válido. 
                if($_pagi_nav_desde < 1){ 
                    $_pagi_nav_desde = 1; 
                } 
             }
        }
        for ($_pagi_i = $_pagi_nav_desde; $_pagi_i<=$_pagi_nav_hasta; $_pagi_i++){
            //Desde página 1 hasta última página ($_pagi_totalPags) 
            if ($_pagi_i == $_pagi_actual) {
                 // Si el número de página es la actual ($_pagi_actual). Se escribe el número, pero sin enlace y en negrita. 
                 $_pagi_navegacion_temporal[] = "<span id='paginas2'>$_pagi_i</span>"; 
            }else{
                // Si es cualquier otro. Se escribe el enlace a dicho número de página. 
                $_pagi_navegacion_temporal[] = "<a id='paginas1' href='#' onclick='"."get".$method."(".$_pagi_i.")'> ".$_pagi_i." </a>"; 
            }

        }
        if ($_pagi_actual < $_pagi_totalPags) {
            // Si no estamos en la última página. Ponemos el enlace "Siguiente" 
            $_pagi_url = $_pagi_actual + 1; //será el número de página al que enlazamos 
            $_pagi_navegacion_temporal[] = "<a id='paginas1'href='#' onclick='"."get".$method."(".$_pagi_url.")'>$_pagi_nav_siguiente</a>"; 

             // Si no estamos en la última página. Ponemos el enlace "Última" 
             $_pagi_url = $_pagi_totalPags; //será el número de página al que enlazamos 
             $_pagi_navegacion_temporal[] = "<a class=' waves-effect' href='#' onclick='"."get".$method."(".$_pagi_url.")'>$_pagi_nav_ultima</a>"; 
        }
         /* 
        * Obtención de los registros que se mostrarán en la página actual. 
        *------------------------------------------------------------------------ 
        */ 
        $_pagi_navegacion = implode($_pagi_navegacion_temporal);
         // Calculamos desde qué registro se mostrará en esta página 
        // Recordemos que el conteo empieza desde CERO. 
        $_pagi_inicial = ($_pagi_actual-1) * $_pagi_cuantos;
        // Consulta SQL. Devuelve $cantidad registros empezando desde $_pagi_inicial
        $response = $this->db->select2($columns,$table,$_pagi_inicial,$_pagi_cuantos,$where, $array); 
        if (is_array($response)) {
            $_pagi_result2 = $response["results"];
        } else {
            return $response;
        }
        /* 
        * Generación de la información sobre los registros mostrados. 
        *------------------------------------------------------------------------ 
        */ 
        // Número del primer registro de la página actual 
        $_pagi_desde = $_pagi_inicial + 1; 
        // Número del último registro de la página actual 
        $_pagi_hasta = $_pagi_inicial + $_pagi_cuantos; 
        if ($_pagi_hasta > $_pagi_totalReg) {
            // Si estamos en la última página 
            // El último registro de la página actual será igual al número de registros. 
            $_pagi_hasta = $_pagi_totalReg; 
        }
        $_pagi_info = " del <b>$_pagi_desde</b> al <b>$_pagi_hasta</b> de <b>$_pagi_totalReg</b>"; 
        return array(
            "results" => $_pagi_result2,
            "pagi_navegacion" => $_pagi_navegacion,
            "pagi_info" => $_pagi_info
        );
    }
}


?>