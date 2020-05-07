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
                echo '  <script>
                fetch(\'http://localhost/proiect/GaSM/public/api/campaigns/add\', {
                method: \'POST\',
                headers: {\'Content-Type\':\'application/x-www-form-urlencoded\'}, 
                body: \'name=' . $aCampaign->name . '&location=' . $aCampaign->location . '&description=' . $aCampaign->description .'\'                       });
                        </script>  ';
                    

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


    

    public function details($idCampanie='')
    {
        $aCampaign=$this->model('CampaignModel');

        if(isset($_POST['Details']))
        {

            $this->view('individualCampaign',array('id'=>$idCampanie)); //voi trimite toate atributele campaniei cu id-ul ala din tabelul campaigns
            //print_r(array('id'=>$idCampanie));

        }
        else //nu s-a apasat butonul dar s-a ajuns aici prin link 
        {
            if($aCampaign->doesItExist($idCampanie))
            {
                
                $this->view('individualCampaign',array('id'=>$idCampanie)); //voi trimite toate atributele campaniei cu id-ul ala din tabelul campaigns
    
            }
            else
            {
                $this->view('campaignNotFound');  //daca se da un url cu un index inexistent
            }

            
        }
    }
    
}

?>