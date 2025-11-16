<style>
    a .fa {
        color: white;
    }
    .rrss-footer i {
        color: white; 
        font-size:20; 
        padding: 5px;
    }
</style>

<footer>   
	<section class='info'>
		<article class="information"> 
			<span class="info-title">Contáctanos<br></span>
			<span class="info-address">
				<?php echo $address;?><br>
				<?php echo $city.', '.$state.', '.$zip;?><br>
				<?php echo $country;?><br><br>
				<a href="tel:+<?php echo $phone;?>" style="color:white; text-decoration: none;">
                    <?php echo $phone;?>
                </a><br><br>
                </span>      
		</article>		
		
		<article class="information">
			
            <span class="info-title">Legal</span>
			<ul  class='list-items'>
                <li><a href='index.php?page=legal-notice'>Aviso Legal</a></li>
                <li><a href='index.php?page=cookies-policy'>Política de Cookies</a></li>
                <li><a href='index.php?page=data-security-policy'>Protección de Datos</a></li>
			</ul>	
		</article>
		
        <article class="information">
            <span class="info-title">Servicios</span>
            <ul class='list-items'>
                <li> <a href="<?php echo $baseURL.'logotypes';?>"><h3 class='info-service'>Logotypes</h3></a></li>
                <li> <a href="graphic-design"><h3 class='info-service'><?php echo $h31;?></h3></a></li>
                <li> <a href="web-design"><h3 class='info-service'><?php echo $h32;?></h3></a></li>
                <li> <a href="#"><h3 class='info-service'><?php echo $h33;?></h3></a></li>
                <li> <a href="#"><h3 class='info-service'><?php echo $h34;?></h3></a></li>
                <li> <a href="#"><h3 class='info-service'><?php echo $h35;?></h3></a></li>
                <li> <a href="#"><h3 class='info-service'><?php echo $h36;?></h3></a></li>
                <li> <a href="/scraper"><h3 class='info-service'>Scraper</h3></a></li>
                <li> <a href="/sitemap"><h3 class='info-service'>Sitemap</h3></a></li>
            </ul>  	
		</article>



		<div class="information">
            <span class="info-title">About</span>
            <p class='business-type'><a href="index.php?page=admin&action=auth" class='intranet'>Creative Studio</a></p>
     
            <section class="rrss-footer">
                <a href="https://facebook.com/<?php echo $user_facebook; ?>" target="_blank" title="facebook.com/<?php echo $user_facebook; ?>">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="https://instagram.com/<?php echo $user_instagram; ?>" target="_blank" title="instagram.com/<?php echo $user_instagram; ?>">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://youtube.com/<?php echo $user_youtube; ?>" target="_blank" title="youtube.com/<?php echo $user_youtube; ?>">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="https://tiktok.com/@<?php echo $user_tiktok; ?>" target="_blank" title="tiktok.com/@<?php echo $user_tiktok; ?>">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="https://linkedin.com/company/ikusacreativestudio" target="_blank" title="linkedin.com/company/ikusacreativestudio">
                    <i class="fab fa-linkedin"></i>
                </a>
                <a href="https://x.com/ikusa_ads" target="_blank" title="x.com/ikusa_ads">
                    <i class="fab fa-x-twitter"></i>
                </a>
            </section>
            
		</div>
	</section>
		<div id='copyright'>&copy;<?php echo date('Y');?></div>
		<div class='brand'>Ikusa LLC&reg;</div><br>
		<div class='developer'>Diseño Web 
		    <a href='https://ikusa.net' target='blank'>
		        <img src="<?php echo $baseURL.'images/web-designer.svg';?>" alt='Web Designer' title='Web Designer' class="agency" /></a>
		</div>

</footer>
<script>
    // Header (por ejemplo)
const header = document.querySelector('header');
console.log('Header height:', header.getBoundingClientRect().height);

// Footer
const footer = document.querySelector('footer');
console.log('Footer height:', footer.getBoundingClientRect().height);

</script>
