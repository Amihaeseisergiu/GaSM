<?php
class Home extends Controller
{

    public function index()  //if-uri si else if-uri pt a decide ce view arat pt ca altfel o sa puna unul dupa altul view-urile si sa dai scroll in jos
    {

        $anUser=$this->model('User');
        

        if (isset($_POST['SubmitButton']))  //daca un user ajunge aici din signup datele sale vor fi puse in db
         {
        
             $anUser->name=$_POST['Name'];
             $anUser->email=$_POST['Email'];
             $anUser->password=$_POST['Password'];

             if(isset($_POST['Address']))
             $anUser->address=$_POST['Address'];

             if($anUser->isTheNameAvailable()) //daca numele e disponibil vom fi redirectati pe homepage logged out si vor fi stocate info in BD
              {
                  $anUser->storeIntoDB();  //vom stoca toate astea in DB
                  $this->view('index'); //sunt redirectat aici dupa ce apas sign up din signup.php (on form action=), versiunea logged out
              }
              else
              {
                $this->view('signup');
                echo '<script>alert("Numele dat este deja folosit!")</script>';  //daca sunt gresite datele introduse la  login
              }

         }


         else if(isset($_POST['LoginButton']))  //daca userul face log in ar trebui verificate datele
         {
            $anUser->password=$_POST['Password'];
            $anUser->name=$_POST['Username'];

            if($anUser->isValidLogin()) //verificam daca e valid loginul
            {
                $_SESSION['loggedIn']="true";   //login cu succes
                $this->view('indexLoggedIn');
            }
            else 
            {
                $this->view('index');
                echo '<script>alert("Parola sau username gresite!")</script>';  //daca sunt gresite datele introduse la  login

            }    
            
         }
         else if(isset($_POST['LogoutButton']))
            {
                $_SESSION['loggedIn']=false;
                $this->view('index');
            }
           else if($_SESSION['loggedIn']==="true")  $this->view('indexLoggedIn');  //daca vin de pe alta pagina si m-am logat deja, sa imi afiseze versiunea loggedIn a homepage-ului
              else $this->view('index');

         var_dump($_POST); // pt debugging, va arata ce valori s-au primit la $_POST
         echo $_SESSION['loggedIn'];
    }  

}

?>
