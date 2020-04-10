<?php
//session_start(); ar trebui in init.php pus probabil

class User
{

    public $name;
    public $email;
    public $address;
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
        $_SESSION['privileges']=$row['privileges'];
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

    if(isset($this->address)) //daca am dat adresa 
    {
        //echo '<script>alert("Welcome to Geeks for Geeks")</script>';
      $query = $con->prepare("INSERT INTO users(name,pw,email,address,privileges) values(?,?,?,?,?)");  //facem prepare, nu dam valoare la id ca e auto_increment
      $query->bind_param("sssss",$this->name,$this->password,$this->email,$this->address,$this->privileges);  //s de la String, bind-uim parametrii
    }
    else
    {
      $query = $con->prepare("INSERT INTO users(name,pw,email,privileges) values(?,?,?,?)");  //facem prepare 
      $query->bind_param("ssss",$this->name,$this->password,$this->email,$this->privileges);  //s de la String, bind-uim parametrii

    }


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