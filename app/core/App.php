<?php

class App
{

    protected $controller = 'Home';  //metoda si controller-ul default pe care le rulam cand ne boostrapuim aplicatia

    protected $method = 'index';

    protected $params = [];

    public function  __construct()
    {

        $url = $this->parseUrl();


        // aici o sa vina gata separate chestiile din url, si se va verifica daca prima chestie e controller
        // daca da, atunci el va fi inclus, si dupa se verifica daca a 2-a chestie este metoda
        // daca da, atunci ea va fi apelata avand ca parametrii chestiile separate urmatoare
        //print_r() displays information about a variable in a way that's readable by humans.


        if (isset($url[0])) {
            if (file_exists('../app/controllers/' .   $url[0]   . '.php'))  //daca exista respectivul controller
            {

                $this->controller = $url[0];   //setam controllerul
                unset($url[0]); //sterge din array elementul cu indexul 0, dar restul elementelor isi pastreaza indecsii
            }
        }

        require_once '../app/controllers/'   .    $this->controller   . '.php';    //asta e un fel de include sau import din java, includem controllerul gasit aici


        $this->controller = new $this->controller;


        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }



        if ($url)                                         //aici nu stiu de ce imi tot ramane index.php in $url, tipului din tutorial nu ii ramanea, si am verificat fila cu fila si era la fel
            $this->params = array_values($url);



        call_user_func_array([$this->controller, $this->method,], $this->params);  // asta o sa apeleze respectiva metoda cu respectivii parametri
        // by default o sa apeleze metoda index() din controllerul products.php fara param 


    }

    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));  // rtrim sterge spatiile albe din dreapta dar sterge si ultimul /
        }                                                                                      // explode= Split a string by a string
    }
}
