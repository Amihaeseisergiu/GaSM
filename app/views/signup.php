<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-Up Page</title>
    <link rel="stylesheet" type = "text/css" href="http://localhost:80/proiect/GaSM/app/css/upperPage.css">
    <link rel="stylesheet" type = "text/css" href="http://localhost:80/proiect/GaSM/app/css/signup.css">
    <link rel="stylesheet" type = "text/css" href="http://localhost:80/proiect/GaSM/app/css/inputButtons.css">
</head>
<body>
    <div class="top">      <!--top part-->
        <div>
            <img src="http://localhost:80/proiect/GaSM/app/images/logo.jpg" alt="Logo">
            <p>
                GaSM
            </p>
        </div>

        <div>
            
                <button onclick= "location.href = 'http://localhost:80/proiect/GaSM/public/'" class="D3Button" >  <!--merge redirectarea-->
                    Home
                </button>
            
                
           
                <button onclick= "location.href = 'http://localhost/proiect/GaSM/app/views/map.php'" class="D3Button">
                    Map
                </button>
            

            
                <button onclick= "location.href = 'Statistics'" class="D3Button">
                    Statistics
                </button>
            
        </div>
    </div>

<div class="greenContainer"> <!--signupPart-->


  <form  action="http://localhost/proiect/GaSM/public/" method="post" class="greyContainer">

    <h1 class="textH">Sign-Up</h1>

    <input required class="inputBox" type="text" id="name" name="Name" placeholder="Name" >
    <input required class="inputBox" type="email" id="email" name="Email" placeholder="Email" >
    <input required class="inputBox" type="password" id="pw" name="Password" placeholder="Password" >
    <input class="inputBox" type="text" id="address" name="Address" placeholder="Address">

    
    <button   class="submitButton" type="submit" id="submitB" name="SubmitButton">Submit</button>


  </form>


</div>


</body>
</html>