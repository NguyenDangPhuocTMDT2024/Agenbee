<?php

class Controller {

    protected function renderView($view, $data = []) {

        extract($data);
        require ROOT_PATH . "/app/views/$view.php";
    }
}
