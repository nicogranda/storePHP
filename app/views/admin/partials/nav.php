<?php require 'auth.php';?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Estilo para el menú hamburguesa */
        .burger-menu {
            cursor: pointer;
            display: inline-block;
        }
        .bar {
            width: 25px;
            height: 3px;
            background-color: black; /* Color negro */
            margin: 5px 0;
            transition: 0.3s;
        }
        /* Menú oculto inicialmente */
        .menu {
            display: none;
        }
        /* Menú activo */
        .menu.active {
            display: block;
        }
        /* Lista sin viñetas */
        .no-bullets {
            list-style-type: none;
            padding: 20x 0 0 0;
            margin: 0;
        }
    </style>
</head>

<nav>
<div style="position: absolute; top: 5px; left: 20px; display: flex; align-items: center;">
    <i class="fa fa-user" style="padding-right: 10px;"></i>
    <?php echo " " . $_SESSION['user_name']; ?>
</div>

<div class="burger-menu" onclick="toggleMenu()">
    
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
</div>

<div class="menu" id="menu">
    
    <!--User-->
    <ul class="no-bullets">
        <li><a href="index.php?page=profile">Profile</a></li>
        <li><a href="index.php?page=logout">Logout</a></li>
    </ul>
    <HR>
    <!--Products e Supplies-->
     <ul class="no-bullets">
         <li><a href="index.php?page=categories&action=index">Categories</a></li>  
        <li><a href="index.php?page=products&action=index">Products</a></li>
        <li><a href="index.php?page=supplies&action=index">Supplies</a></li>
        <li><a href="index.php?page=providers&action=index">Providers</a></li>
        <li><a href="index.php?page=clients&action=index">Clients</a></li>
    </ul>
    <HR>
    <!--Clients-->
    <ul class="no-bullets">
        <li><a href="index.php?page=quotes&action=index">Quotes</a></li>
         <li><a href="index.php?page=invoices&action=index">Invoices</a></li>
    </ul>
    <HR>
    <!--Provider-->
    <ul class="no-bullets">
        <li><a href="index.php?page=requests&action=index">RFQ</a></li>
        <li><a href="index.php?page=order&action=index">Order</a></li>
    </ul>
    <HR>

    <!--Sources-->
     <ul class="no-bullets">
        <li>E-mail</li>
        <li><a href="index.php?page=E-mail&action=create">Create</a></li>
        <li><a href="https://ikusa.net/webmail">Read</a></li>
    </ul>
    <!--Sources-->
     <ul class="no-bullets">
        <li>IRS</li>
        <li><a href="index.php?page=irs&action=5472">5472</a></li>
        <li><a href="index.php?page=irs&action=1120">1120</a></li>
    </ul>

</div>

</nav>

<script>
function toggleMenu() {
    document.querySelector('.burger-menu').classList.toggle('active');
    document.getElementById('menu').classList.toggle('active');
}
</script>

