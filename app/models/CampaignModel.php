<?php

class CampaignModel
{
  public $name;
  public $location;
  public $description;
   

  public function isCampaignDataValid()
  {

    //"/^[A-Za-z0-9 \.\!\?\:\"\(\)\-\+]+$/" pt descriere

     if(!preg_match("/^[a-zA-Z\d ]+$/",$this->name))
     {return false;echo "numele";}  

     if(!preg_match("/^[a-zA-Z\d ]+$/",$this->location))
     return false;

     if(!preg_match("/^[A-Za-z0-9 \.\,\!\?\:\"\(\)\-\+]+$/",$this->description))
     return false;   

     if(strlen($this->name)>25)
     return false; 

     if(strlen($this->location)>50)
     return false;

     if(strlen($this->description)>250)
     return false;

     return true;
  }

  public function isCommentValid($comment)
  {
    if(!preg_match("/^[A-Za-z0-9 \.\,\!\?\:\"\(\)\-\+]+$/",$comment))
     return false; 

     return true;

  }

  public function isTheNameAvailable() //sa nu se poata inregistra 2 useri cu acelasi nume
{

    $con=mysqli_connect("Localhost", "root" ,"", "tw");
     
    $query = $con->prepare("SELECT * from campaigns where name=?");  //facem prepare 
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

public function doesItExist($idCampaign)
{

  $con=mysqli_connect("Localhost", "root" ,"", "tw");
     
    $query = $con->prepare("SELECT * from campaigns where id=?");  //facem prepare 
    $query->bind_param("i",$idCampaign);  //$idDat e de tipul String, bind-uim parametrii
    
    $query->execute(); //executam query-ul

    $result=$query->get_result();
    $row =$result->fetch_assoc();  //va lua primul row daca returneaza mai multe row-uri

    if ($result->num_rows===1) //s-a gasit 1 rand
    {
        //echo "0 randuri";
        return true;
        $con->close();
        
    }
        return false;
        $con->close();
        

}

  public function storeIntoDB()
  {

    $con=mysqli_connect("Localhost", "root" ,"", "tw");

    

    $query = $con->prepare("INSERT INTO campaigns(name,location,description) values(?,?,?)");  //facem prepare, nu dam valoare la id ca e auto_increment
    $query->bind_param("sss",$this->name,$this->location,$this->description);  //s de la String, bind-uim parametrii


    $query->execute(); //executam query-ul

    $con->close();
  }

  public function getAllCampaigns()
  {

    $con=mysqli_connect("Localhost", "root" ,"", "tw");

    $query = $con->prepare("SELECT * from campaigns order by id asc");
    $query->execute(); //nu mai avem de bind-uit parametri

    $result=$query->get_result();

    $con->close();
    $array = array();
    while($row=$result->fetch_assoc())
    {
        array_push($array,$row);
    }
    return $array;

        
  }

  public function getAllCampaignsFromIndexOnwards($idCampaign)
  {
    $con=mysqli_connect("Localhost", "root" ,"", "tw");

    $query = $con->prepare("SELECT * from campaigns where id>?");
    $query->bind_param("i",$idCampaign);
    $query->execute(); //nu mai avem de bind-uit parametri

    $result=$query->get_result();

    $con->close();

    $array = array();
    while($row=$result->fetch_assoc())
    {
        array_push($array,$row);
    }
    return $array;
    
  }

  public function addLike($idCampaign)
  {
    $con=mysqli_connect("Localhost", "root" ,"", "tw");

    $query = $con->prepare("UPDATE CAMPAIGNS SET likes=likes+1 where id=?");

    $query->bind_param("i",$idCampaign);

    $query->execute(); //nu mai avem de bind-uit parametri
  }

  public function addComment($idCampaign,$content)
  {
    $con=mysqli_connect("Localhost", "root" ,"", "tw");

    $query = $con->prepare(" INSERT INTO comments(id_campaign,id_user,comment) values(?,?,?)");

    
    $query->bind_param("iis",$idCampaign,$_SESSION['userID'],$content);

    $query->execute(); //nu mai avem de bind-uit parametri
  }


  public function getFullDetailsOfACampaign($idCampaign)
  {
    $con=mysqli_connect("Localhost", "root" ,"", "tw");

    $query = $con->prepare("SELECT * from campaigns where id=?");
    $query->bind_param("i",$idCampaign);

    $query->execute(); //nu mai avem de bind-uit parametri

    $result=$query->get_result();

    $con->close();
    $row=$result->fetch_assoc();
    return $row;  //1 singur row

  }

  public function getCommentsOfACampaign($idCampaign)
  {
    $con=mysqli_connect("Localhost", "root" ,"", "tw");

    $query = $con->prepare("SELECT * from comments join users on comments.id_user=users.id where id_campaign=? order by time desc");
    $query->bind_param("i",$idCampaign);

    $query->execute(); //nu mai avem de bind-uit parametri

    $result=$query->get_result();

    $con->close();

    $array = array();
    while($row=$result->fetch_assoc())
    {
        array_push($array,$row['comment']);
        array_push($array,$row['name']);
        array_push($array,$row['time']);
    }
    return $array;

  }

}


?>