<?php

class Controller
{

    public function model($modelName)
    {
        require_once '../app/models/'   .   $modelName . '.php';    //$model e numele modelului, si-l include din folderul models
        require_once __DIR__ . '../../config/Database.php';

        $database = new Database();
        $db = $database->connect();
        return new $modelName($db);           //returneaza un obiect de tipul ala 
    }


    public function view($view,$data=[])   //$data e empty array pt ca uneori vrem sa parsam date la view uneori nu(ca sa putem apela cu un singur argument dintr-un controller )
    {
        require_once '../app/views/'  .  $view  .   '.php';

    }

}


?>