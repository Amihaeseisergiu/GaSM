<?php

class Campaign extends Controller
{

    public function index($idCampanie='')
    {
        $aCampaign=$this->model('CampaignModel');
        

        if(isset($_POST['ViewAllCampaigns']))
        {
            $arrayCampaignsTable=$aCampaign->getAllCampaignsFromIndexOnwards($idCampanie);
            
             if(!empty($arrayCampaignsTable))
             {
                 array_push($arrayCampaignsTable,$idCampanie);
                 $this->view('allcampaigns',$arrayCampaignsTable);    
            }
             else $this->view('nocampaignsyet'); //inseamna ca nu exista campanii inca in bd
        }
        
        else if(isset($_POST['StartCampaign']))
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

            $arrayCampaignsTable=$aCampaign->getAllCampaignsFromIndexOnwards($idCampanie-4);
            array_push($arrayCampaignsTable,$idCampanie-4);
            //print_r($arrayCampaignsTable);
            $this->view('allcampaigns',$arrayCampaignsTable);
            
        }
                                 // trebuie verificat daca exista acest index
        else if($idCampanie!='') //daca se da manual url-ul si se ajunge in metoda index cu id-ul 3, va afisa pagina cu campania cu id 3 si urm campanie de dupa daca exista
        {
            if($aCampaign->doesItExist($idCampanie))
            {
               $arrayCampaignsTable=$aCampaign->getAllCampaignsFromIndexOnwards($idCampanie-1);
               array_push($arrayCampaignsTable,$idCampanie-1);
            
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

         if (isset($_POST['Like']))
        {
            $aCampaign->addLike($idCampanie);
            
            //if($idCampanie)
            $arrayCampaignsTable=$aCampaign->getAllCampaigns();
            $this->view('allcampaigns',$arrayCampaignsTable);
        }
        else //nu s-a apasat butonul dar s-a ajuns aici prin link 
        {
            $arrayCampaignsTable=$aCampaign->getAllCampaigns();
            $this->view('allcampaigns',$arrayCampaignsTable);
            
        }
    }

    public function comment($idCampanie='')
    {
        $aCampaign=$this->model('CampaignModel');

        if (isset($_POST['Comment']))
        {
            $commentContent=$_POST['CommentContent'];

            if($aCampaign->isCommentValid($commentContent))
            {
                if($_SESSION['loggedIn'])
               {
                  $aCampaign->addComment($idCampanie, $commentContent);
    
                  $arrayCampaignsTable=$aCampaign->getAllCampaigns();
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
        else //nu s-a apasat butonul dar s-a ajuns aici prin link 
        {
            $arrayCampaignsTable=$aCampaign->getAllCampaigns();
            $this->view('allcampaigns',$arrayCampaignsTable);
            
        }
    }

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