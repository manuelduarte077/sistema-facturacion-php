<?php

class Views
{
    function render($controller, $view, $models)
    {
        $controllers = get_class($controller);
        require VIEWS . DFT . "head.html";
        if ($models == null) {
            require VIEWS . $controllers . '/' . $view . '.html';
        } else {
            require VIEWS . $controllers . '/' . $view . '.php';
        }


        require VIEWS . DFT . "footer.html";
    }
}


?>