<?php
require('../../database.php');

$user_id=$_POST['userID'];
if ($message_id=="13"){ //To buys
    if ($brands = $mysqli->query("SELECT * FROM providers WHERE email='$mailerTo'")) {
        while ( $row = $brands->fetch_assoc() ){ 
            $id=$row["id"]; 
            $provider=$row["name"]; 
            $mailerToToo=$row["email1"];

            $user_id=$row["user_id"];
            $destination= $provider;
        }
    }
} else { //To Sales
    if ($brands = $mysqli->query("SELECT * FROM brands WHERE email='$mailerTo'")) {
        while ( $row1 = $brands->fetch_assoc() ){     
            $id=$row1["id"]; 
            $brand=$row1["name"]; 
            $mailerToToo='ikusa.ads@gmail.com';
            $user_id=$row1["user_id"];
            $destination= $brand;    
        }
    }
}

if ($users = $mysqli->query("SELECT * FROM users WHERE id='$user_id'")) {
    while ( $row = $users->fetch_assoc() ){  
        $executive_user=$row["name"].' '.$row["lastname"];  
        $executive_cell_phone=$row["cell_phone"]; 
        $position_id=$row["position_id"]; 
        $executive_email=$row["email"]; 
        
            if ($positions = $mysqli->query("SELECT * FROM positions WHERE id='$position_id'")) {
                while ( $row = $positions->fetch_assoc() ){  
                    $executive_position=$row["name"];  
        
                }    
            }    

    }
     
}   

?>