<?php

class Campaign extends Controller
{

    public function index($idCampanie='')
    {
        $aCampaign=$this->model('CampaignModel');
        

       if(isset($_POST['StartCampaign']))
        {
            $aCampaign->name=$_POST['Name'];
            $aCampaign->location=$_POST['Location'];
            $aCampaign->description=$_POST['Description'];
           if($aCampaign->isCampaignDataValid()) 
           {

              if($aCampaign->isTheNameAvailable())
               {
                    $aCampaign->storeIntoDB();
                    

                    $arrayCampaignsTable=$aCampaign->getAllCampaigns();

                    $this->view('allcampaigns',$arrayCampaignsTable);
               }
               else  //daca numele dat mai exista la alta campanie
               {
                 
                $this->view('campaign');
                echo '<script>alert("Numele mai exista deja!")</script>';

               }

           }
           else  //daca datele introduse nu sunt valide
           {

            $this->view('campaign');
            echo '<script>alert("Date invalide!")</script>';

           }
           
        }


        else if(isset($_POST['NextPage']))
        {

            $arrayCampaignsTable=$aCampaign->getAllCampaignsFromIndexOnwards($idCampanie);
            array_push($arrayCampaignsTable,$idCampanie);
            //print_r($arrayCampaignsTable);
            $this->view('allcampaigns',$arrayCampaignsTable);
            
        }
        else if(isset($_POST['PreviousPage']))
        {

            $arrayCampaignsTable=$aCampaign->getAllCampaignsFromIndexOnwards($idCampanie);
            array_push($arrayCampaignsTable,$idCampanie);
            //print_r($arrayCampaignsTable);
            $this->view('allcampaigns',$arrayCampaignsTable);
            
        }
                                 // trebuie verificat daca exista acest index
        else if($idCampanie!='') //daca se da manual url-ul si se ajunge in metoda index cu id-ul 2, va afisa pagina cu campania cu id 3 si urm campanie de dupa daca exista(cea cu idul 4)
        {
            if($aCampaign->doesItExist($idCampanie+1))
            {
               $arrayCampaignsTable=$aCampaign->getAllCampaignsFromIndexOnwards($idCampanie);
               array_push($arrayCampaignsTable,$idCampanie);
            
               $this->view('allcampaigns',$arrayCampaignsTable);
            }
            else
            {
                $this->view('campaignNotFound');  //daca se da un url cu un index inexistent
            }
        }
        else  //daca nu am dat click pe nimic
           {
            $this->view('campaign');
           }
    }

    public function like($idCampanie='')
    {
        $aCampaign=$this->model('CampaignModel');

        if($_SESSION['loggedIn'])
         $aCampaign->addLike($idCampanie);

        else  echo '<script>alert("Nu sunteti logat!")</script>';
            
        //if($idCampanie)

        $arrayCampaignsTable=$aCampaign->getAllCampaigns();
      
    }

    public function comment($idCampanie='')
    {
        $aCampaign=$this->model('CampaignModel');
        
            $commentContent=$_POST['CommentContent'];

            if($aCampaign->isCommentValid($commentContent))
            {
                if($_SESSION['loggedIn'])
               {
                  $aCampaign->addComment($idCampanie, $commentContent);
    
                  if($idCampanie%2==1)
                    {
                        $arrayCampaignsTable=$aCampaign->getAllCampaignsFromIndexOnwards($idCampanie-1);
                        array_push($arrayCampaignsTable,$idCampanie-1);
                    }    
                    else {
                           $arrayCampaignsTable=$aCampaign->getAllCampaignsFromIndexOnwards($idCampanie-2);
                           array_push($arrayCampaignsTable,$idCampanie-2);
                         }  
                  $this->view('allcampaigns',$arrayCampaignsTable);
               }
                else  //n-are voie sa puna comentarii daca nu e logat
               {
                
                  $arrayCampaignsTable=$aCampaign->getAllCampaigns();
                  $this->view('allcampaigns',$arrayCampaignsTable);
                  echo '<script>alert("Nu sunteti logat!")</script>';
               }
            }
            else 
            {
                $arrayCampaignsTable=$aCampaign->getAllCampaigns();
                $this->view('allcampaigns',$arrayCampaignsTable);
                echo '<script>alert("Continut invalid al comentariului!")</script>';
            }
            
        }
    
        //var_dump($_POST);
    

    public function details($idCampanie='')
    {
        $aCampaign=$this->model('CampaignModel');

        if(isset($_POST['Details']))
        {

            $fullDetails=$aCampaign->getFullDetailsOfACampaign($idCampanie);
            $allComments=$aCampaign->getCommentsOfACampaign($idCampanie);
            $resultArray=array();
            array_push($resultArray,$fullDetails);
            array_push($resultArray,$allComments);
            //print_r($resultArray);

            $this->view('individualCampaign',$resultArray); //voi trimite toate atributele campaniei cu id-ul ala din tabelul campaigns

            //echo '<script>alert("pagina campanie individuala!")</script>';
        }
        else //nu s-a apasat butonul dar s-a ajuns aici prin link 
        {
            if($aCampaign->doesItExist($idCampanie))
            {
                $fullDetails=$aCampaign->getFullDetailsOfACampaign($idCampanie);
                $allComments=$aCampaign->getCommentsOfACampaign($idCampanie);
                $resultArray=array();
                array_push($resultArray,$fullDetails);
                array_push($resultArray,$allComments);
                //print_r($resultArray);
    
                $this->view('individualCampaign',$resultArray); //voi trimite toate atributele campaniei cu id-ul ala din tabelul campaigns
    
            }
            else
            {
                $this->view('campaignNotFound');  //daca se da un url cu un index inexistent
            }

            
        }
    }
    
}

?>