<?php

$server_url=$_SERVER['HTTP_HOST'];
$baseURL='https://ikusa.net/';
$domain='ikusa.net';
$brand='Ikusa';
$business_name="Ikusa LLC";
 
$brand_url='https://ikusa.net';
$brand_email = "contact@ikusa.net";
$ahref_contact = "<a href='mailto:".$brand_email."'>".$brand_email."</a>";
$link_email_contact = "<a href='mailto:".$brand_email."'>".$brand_email."</a>";

$h21='Diseño Gráfico';
$h22='Branding';
$h23='Diseño Web';
$h24='Community Manager';
$h25='SEO';
$h26='Marketing Digital';


$h31='Diseño Gráfico';
$h32='Branding';
$h33='Diseño Web';
$h34='Community Manager';
$h35='SEO';
$h36='Marketing Digital';

$h21 = "Our Services";
$h3211 = "Atlanta Web Design";
$h3212 = "SEO";
$h3213 = "PPC";
$h3214 = "UI and UX";
$h3215 = "Digital and Inbound Marketing";
$h3216 = "Content Writing";


$located='Donostia-San Sebastián';

   

    //define('BASE_URL', 'http://localhost:8888/ikusas/public/');
    $server_url=$_SERVER['HTTP_HOST'];
    
    $domain = "ikusa.net";
    $logo = 'ikusa.svg';
    
    $profile_name='ikusa';
    $color='#ED1C24';
    

    
    $address='Aldamar Kalea, 36';
    $city = 'San Sebastián - Donostia';
    $state = 'Guipuzcoa';
    $country = 'Espan1a';
    $zip = '20003';
    $phone='+34 607 202466';


    $user_facebook = "ikusa.creativestudio";
    $user_instagram = "ikusa.creativestudio";
    $user_youtube = "@ikusa.creativestudio";
    $user_tiktok = "ikusa.creativestudio";
    $user_x = "ikusa.ads";

    function isPageActive($currentPage) {
        global $page; // Usa la variable global $page definida en tu archivo
        if ($currentPage === $page) {
            return ' class="active"';
        } else {
            return ''; 
        }
    }
?>   
  
