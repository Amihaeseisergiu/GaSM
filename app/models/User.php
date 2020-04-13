<?php
//session_start(); ar trebui in init.php pus probabil

class User
{

    public $name;
    public $email;
    public $country;
    public $city;
    public $password;
    public $privileges;


 public function isValidLogin()
 {

    $con=mysqli_connect("Localhost", "root" ,"", "tw");
     
    $query = $con->prepare("SELECT * from users where name=? and pw=?");  //facem prepare 
    $query->bind_param("ss",$this->name,$this->password);  //$idDat e de tipul String, bind-uim parametrii
    $query->execute(); //executam query-ul

    $result=$query->get_result();
    $row =$result->fetch_assoc();  //va lua primul row daca returneaza mai multe row-uri

    if ($result->num_rows===1)
    {
        $_SESSION['privileges']=$row['privileges'];  //salvam in session-uri user id-ul si privilegiile userului logat
        $_SESSION['userID']=$row['id'];
        $con->close();
        return true;
    }

    $con->close();
    return false;

 }   


public function storeIntoDB() //am sa verific eventual daca exista sau nu numele
{
    $con=mysqli_connect("Localhost", "root" ,"", "tw");

    $this->privileges="user";

    $query = $con->prepare("INSERT INTO users(name,pw,email,country,city,privileges) values(?,?,?,?,?,?)");  //facem prepare, nu dam valoare la id ca e auto_increment
    $query->bind_param("ssssss",$this->name,$this->password,$this->email,$this->country,$this->city,$this->privileges);  //s de la String, bind-uim parametrii


    $query->execute(); //executam query-ul

    $con->close();

}


public function isTheNameAvailable() //sa nu se poata inregistra 2 useri cu acelasi nume
{

    $con=mysqli_connect("Localhost", "root" ,"", "tw");
     
    $query = $con->prepare("SELECT * from users where name=?");  //facem prepare 
    $query->bind_param("s",$this->name);  //$idDat e de tipul String, bind-uim parametrii
    
    $query->execute(); //executam query-ul

    $result=$query->get_result();
    $row =$result->fetch_assoc();  //va lua primul row daca returneaza mai multe row-uri

    if ($result->num_rows===0) //numele NU este deja folosit
    {
        //echo "0 randuri";
        return true;
        $con->close();
        
    }
        return false;
        $con->close();
        

    


}

}    

?>