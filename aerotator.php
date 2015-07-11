<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
$affiliate_id = 170317;
$vertical = "skin";
$country = "US";

//Your Links
$step1_link = "http://cpv.flagshippromotions.com/base2.php?id=1&" . $_SERVER['QUERY_STRING'];
$step2_link = "http://cpv.flagshippromotions.com/base2.php?id=2&" . $_SERVER['QUERY_STRING'];
// Straight to offer, bypasses CPV
// $step1_link = "http://hits2sales.com/?AFID=170317&vertical=skin&country=US&STEP=1&C1=&C2=&C3=";
// $step2_link = "http://hits2sales.com/?AFID=170317&vertical=skin&country=US&STEP=2&C1=&C2=&C3=";

/////  Default Values Start /////
$step1_name = "NuVie Firming Serum";
$step1_image = "http://www.img2srv.com/78.png";

$step2_name = "Bright Skin Cream";
$step2_image = "http://www.img2srv.com/272.png";
/////  Default Values End /////


// fetch data
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,"http://hits2sales.com/api/current_campaigns.php");
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array("affiliate_id" => 170317, "country" => $country, "vertical" => $vertical)));
curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$ua = $_SERVER["HTTP_USER_AGENT"];
if(isset($ua))curl_setopt($ch,CURLOPT_USERAGENT, $ua);
$return = curl_exec($ch);
$result = json_decode($return, true);
if ($result) {

    $step1 = $result["step1"];
    $step2 = $result["step2"];
    $step1_image = "http://www.img2srv.com/".$step1.".png";
    $step2_image = "http://www.img2srv.com/".$step2.".png";
    $step1_name = $result["step1_name"];
    $step2_name = $result["step2_name"];
}
?>

<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Woman's Way | Skin Care Tips</title>
<link href="./Woman's Way _ Skin Care Tips_files/styles.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="./Woman's Way _ Skin Care Tips_files/main0000.css" media="all">
<link href="./Woman's Way _ Skin Care Tips_files/css.css" rel="stylesheet" type="text/css">

<link href="./Woman's Way _ Skin Care Tips_files/css_002.css" rel="stylesheet" type="text/css">


<link href="./Woman's Way _ Skin Care Tips_files/buttons.css" type="text/css" rel="stylesheet">

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	  ga('create', 'UA-64695438-1', 'auto');
	  ga('send', 'pageview');
	</script>
  <script type="text/javascript">
    (function() {
      window._pa = window._pa || {};
      var pa = document.createElement('script'); pa.type = 'text/javascript'; pa.async = true;
      pa.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + "//tag.marinsm.com/serve/55976935923b8a9f2000001d.js";
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(pa, s);
    })();
  </script>


</head>

<body>
<center>
  <p><img src="./Woman's Way _ Skin Care Tips_files/FANS.jpg" class="fbfans"></p>
  <p><a href="<?= $step1_link ?>" id="logoImg2"><img src="./Woman's Way _ Skin Care Tips_files/gdhs1234.jpg" width="218" height="164" alt=""></a></p>
</center>
<div class="container"><!--end header-->

<!------------------------------------NAV-------------------------------------->
<div class="nav">

<div class="nav-inner">

<div class="menu-box fl">

<ul class="menu">
     
      <!-- Begin Item With Drop -->
      <li class="drop">
        <a href="<?= $step1_link ?>"><span class="big-text">menu</span></a>

        <!-- Begin Toggle Icon -->
        <span class="toggle">&nbsp;</span>
        <!-- End Toggle Icon -->

        <ul>
          <li> <a href="<?= $step1_link ?>" target="_blank">Beauty</a></li>
          <li> <a href="<?= $step1_link ?>" target="_blank">Health</a></li>
          <li> <a href="<?= $step1_link ?>" target="_blank">Love &amp; Sex</a></li>
          <li> <a href="<?= $step1_link ?>" target="_blank">Diet</a></li>
          <li> <a href="<?= $step1_link ?>" target="_blank">Gossip</a></li>
        </ul>
      </li>
      <!-- End Item With Drop -->

</ul><!--end menu-->

</div><!--end menu-box-->

<div class="social fl">

<span st_processed="yes" class="st_facebook_large" displaytext="Facebook"><span class="stButton" style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;"><span style="background-image: url(&quot;http://w.sharethis.com/images/facebook_32.png&quot;);" class="stLarge"></span><img style="position: absolute; top: -7px; right: -7px; width: 19px; height: 19px; max-width: 19px; max-height: 19px; display: none;" src="./Woman's Way _ Skin Care Tips_files/check-big.png"></span></span>
<span st_processed="yes" class="st_twitter_large" displaytext="Tweet"><span class="stButton" style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;"><span style="background-image: url(&quot;http://w.sharethis.com/images/twitter_32.png&quot;);" class="stLarge"></span><img style="position: absolute; top: -7px; right: -7px; width: 19px; height: 19px; max-width: 19px; max-height: 19px; display: none;" src="./Woman's Way _ Skin Care Tips_files/check-big.png"></span></span>
<span st_processed="yes" class="st_pinterest_large" displaytext="Pinterest"><span class="stButton" style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;"><span style="background-image: url(&quot;http://w.sharethis.com/images/pinterest_32.png&quot;);" class="stLarge"></span><img style="position: absolute; top: -7px; right: -7px; width: 19px; height: 19px; max-width: 19px; max-height: 19px; display: none;" src="./Woman's Way _ Skin Care Tips_files/check-big.png"></span></span>
<span st_processed="yes" class="st_email_large" displaytext="Email"><span class="stButton" style="text-decoration:none;color:#000000;display:inline-block;cursor:pointer;"><span style="background-image: url(&quot;http://w.sharethis.com/images/email_32.png&quot;);" class="stLarge"></span><img style="position: absolute; top: -7px; right: -7px; width: 19px; height: 19px; max-width: 19px; max-height: 19px; display: none;" src="./Woman's Way _ Skin Care Tips_files/check-big.png"></span></span>
<a href="http://facebook.com/" target="_blank"><img class="imgInline2" src="./Woman's Way _ Skin Care Tips_files/like.png" height="32" width="115"></a>
</div><!-- end social-->

<div class="email fr">
	<div id="contact-area">
    <form method="post" class="af-form-wrapper" action="">
<div style="display: none;">
<input name="meta_web_form_id" value="2095245082" type="hidden">
<input name="meta_split_id" value="" type="hidden">
<input name="listname" value="safe_skincare" type="hidden">
<input name="redirect" value="" id="" type="hidden">

<input name="meta_adtracking" value="US1" type="hidden">
<input name="meta_message" value="1" type="hidden">
<input name="meta_required" value="email" type="hidden">

<input name="meta_tooltip" value="" type="hidden">
</div>
<!--<div id="af-form-2095245082" class="af-form"><div id="af-body-2095245082"  class="af-body af-standards">-->
<div class="af-element">
<label class="previewLabel" for="awf_field-53980819">Sign Up: </label>
<div class="af-textWrap"><input class="text" id="awf_field-53980819" name="email" tabindex="500" type="text">
  <input name="submit" value="Submit" class="submit-button" type="submit">
<div class="af-clear"></div>
<!--</div>-->
</div>
</div>

</form>
  
</div>
</div><!--end email-->

</div><!--end nav-inner-->

<div class="clear"></div>

</div><!--end nav-->


<!-----------------------------------END NAV-------------------------------------->

<div class="main">


<div class="fullWidth center">




<h1 style="text-align:center; font-weight: bold;">You Will Not Believe Their Transformations!</h1><br>
<h3 style="text-align:center; border: 0; ">70 Year Old Grandmas Look 40 Again</h3><br>

<img src="./Woman's Way _ Skin Care Tips_files/feature2.png" class="padding" height="44" width="575">
<img src="./Woman's Way _ Skin Care Tips_files/newsarticles.png" alt="news" class="padding" height="39" width="575">
<img src="./Woman's Way _ Skin Care Tips_files/transformations01.jpg" class="padding">
<br>

<div style="width: 800px; margin-left: auto; margin-right: auto; margin-top: 25px; margin-bottom: 15px;">
<div style="width: 800px; text-align: left;">
<p>
<span class="letter">A</span>t 70+, our 6 Grandmas are looking better 
than ever. They look even more radiant and youthful than they did when 
they were years younger! Many have tried to discover their secret: is it
 botox, face lifts, or just good lighting?</p><br> 
<p>The truth is much simpler (and cheaper!) than that. It isn’t their 
makeup that’s causing them to look decades younger. After many years of 
trying, our sources finally uncover the truth behind the anti-aging 
secret that has taken Hollywood by storm – and it’s one that clever 
women around the world have been secretly using too.

 </p> 
 </div></div>
    <p class="text_center"><img src="./Woman's Way _ Skin Care Tips_files/ozskin.jpg"></p><br>
      <p class="pageheader" style="width: 600px;">
    “Dr. Oz Calls It - The Miracle Anti-Aging Breakthrough - <br>Because It Works!”
    </p>



                       

             
                        
</div>


<div class="content fl">
<h1>The Best Skin Solution You’ve Never Heard Of</h1>
<!--<p><Br />
  <em><strong>What are the Real Housewives Keeping REALLY quiet about?!</strong> </em></p>--><br>
  <p><span class="letter">I</span>nstead of throwing away precious 
dollars on expensive anti-aging skin creams that make big promises and 
don’t work, one reader has discovered a skin combo that erases years off
 her face for only a few dollars. Read on to find out how it works!</p><br>
  <img src="./Woman's Way _ Skin Care Tips_files/skin-transformation02.jpg" height="427" width="656">
 
    

  <p class="pageheader">
   Lauren di Fiores was able to remove over 20 years of aging from her face with just 2 simple products!
    </p>

<p><span class="letter">L</span>auren, a 72 year-old grandma from 
Vancouver, is a perfect example of how a little smart thinking and 
ingenuity can help you avoid unnecessary health risks and save you 
thousands of dollars in doctors’ bills.</p><br>

<p>Like most women, Lauren didn’t have the extra cash to try out every 
celebrity endorsed anti-aging “miracle cream” out there, let alone 
splurge on expensive elective medical procedures, like plastic surgery 
or facelifts.</p><br>

<p>Before trying this simple trick, she admits she used to spend almost 
an hour every day on an extensive skincare regimen consisting of over 6 
different products, with seriously disappointing results. Each product 
made big claims promising to erase wrinkles and return her youthful 
skin; and while Lauren followed all their usage instructions to the 
letter, she saw no real results. 
</p><br>
<p>Frustrated and let down, she considered going so far as to take out a
 substantial loan for Botox injections, and even contemplated a highly 
risky and very expensive facelift procedure. But the high cost (ranging 
anywhere from $5000 - $20,000+) and the horror stories of unfixable 
botched procedures convinced her that cosmetic treatments were not the 
solution she was looking for.
She was determined to find a safe and affordable anti-aging solution 
that would give her real results and not leave her digging her way out 
of a huge financial debt.
</p><br>
<div class="clear">
</div>

<h3><strong>Lauren's Solution:</strong></h3> <br>
 <p> After a year of doing thorough research and speaking to other women
 about their own skincare habits, she learned of two products that were 
yielding real results and helping women take years off their skin: <a href="<?= $step1_link ?>" target="_blank"><?= $step1_name ?></a> and <a href="<?= $step2_link ?>" target="_blank"><?= $step2_name ?>.</a></p><br>
<p>While each of these products were proven to be effective 
individually, one night, Lauren made an accidental discovery that would 
revolutionize the whole skincare industry: she <em>combined</em> them.</p><br>
<p>After only a few days of using both products together in her daily 
skin routine, she saw noticeable results in the mirror. After just two 
weeks of using <a href="<?= $step1_link ?>" target="_blank"><?= $step1_name ?></a> and <a href="<?= $step2_link ?>" target="_blank"><?= $step2_name ?>.</a> combined, the proof was staring her right in the face: this was giving her <em>real</em> results. </p><br>
<p>Using the two products together, Lauren’s results were noticeably 
better than what you’d find at medi-spas for $5,000 or more. This 
combination removed virtually 90% of all her wrinkles and problem areas.
 It tightened her face and neck, removing all signs of sagging, aging, 
and dehydrated skin. Lauren was able to see these dramatic anti-aging 
results simply by using <strong>free samples of both products for only 14 days</strong>.</p><br>
<p>Her friends, husband, and family were all shocked. They were 
convinced she’d been secretly getting Botox, because her skin looked 20 
years younger almost overnight!
Soon, word got around as her friends starting using Lauren’s method, and
 not long after, her easy, 2-product trick was featured on The Doctor Oz
 Show!</p><br>
<p>Lauren is now one of hundreds of women that share the same incredible
 story. Using this method, she effectively erased 20+ years from her 
skin. It’s changed her life and completely astonished everyone around 
her, even total strangers! </p><br>

<h3><strong>How does it Work?</strong></h3>
<br>
<p>Through her research, Lauren discovered that the real secret to cell revival and skin rejuvenation are two key ingredients:</p><br>
<ol>
<strong><li>Vitamin C</li>
<li>Hyaluronic Acid</li></strong>
</ol>
<br>

<p>These are both natural ingredients that work together to erase 
wrinkles and fine lines at the cellular level – below the surface of the
 skin – which is why they’re so effective.</p><br>
<p><strong>Vitamin C</strong> is the key to maintaining healthy, 
youthful skin. It’s a powerful antioxidant that slows the rate of 
free-radical damage, which causes skin’s dryness, fine lines, and 
wrinkles. It helps combat and even reverse time's effect on your skin, 
because it produces collagen – a protein which makes skin appear plump 
and firm. Applying Vitamin C to the skin topically is up to <strong>20 times more effective</strong> than taking it orally. 

</p><br>
<!--<p class="pageheader">Dr. Oz calls Vitamin C “the secret to cheat your age”. </p>-->

<p>
<strong>Hyaluronic Acid</strong> works by binding to moisture. It can 
hold up to 1,000 times its weight in water, making it an excellent 
natural skin plumper. Hyaluronic acid helps your skin repair and 
regenerate itself after suffering from dryness, environmental stresses, 
or irritation.</p><br>
<p>Celebrities around the world like Cindy Crawford, Jennifer Aniston, and Kelly Ripa have all admitted to using <a href="<?= $step1_link ?>" target="_blank"><?= $step1_name ?></a> and <a href="<?= $step2_link ?>" target="_blank"><?= $step2_name ?></a> to maintain their youthful glow.
</p>
<br><br>
<h3 class="highlight"> People are Talking</h3>
<div class="testimonial fl">

 <img src="./Woman's Way _ Skin Care Tips_files/ba1.jpg" width="210">        	 
                   
<p class="rightimgcaption">
“I'm 48 yrs old and my face has unfortunately been showing signs of age,
 dark spots and wrinkles.  I decided to give this product a try as the 
reviews were so good, but I had reservations because I've been 
disappointed with things that have had good reviews in the past.  This 
stuff works. I don't know if it's because my face was in such horrible 
condition that just two nights of use and I could tell the difference in
 the size of my pores, my skin tone was more even, and my face was 
smoother and not so bumpy looking.  I definitely would continue to use 
this product."		
<br><strong>Lannie Semira <br> Toronto</strong>
</p><br>
</div>
<div class="testimonial fl">
<img src="./Woman's Way _ Skin Care Tips_files/ba2.jpg" width="210" style="">         			
                   
                    <p class="rightimgcaption">
“I am 57 years old and I do NOT expect any lotion or cream to make me 
look as I did in my 20's; however if I use NuVie Firming Serum and 
<?= $step2_name ?> on a regular basis I absolutely can tell a difference in my skin
 tone and the overall appearance of my complexion.  I checked both 
products out on beautypedia.com and the reviews were great so I decided 
to give them a try.  Both products are light, fragrance free, &amp; 
non-greasy. It has a fair amount of retinol, green tea, and Vitamin E 
plus it is packaged properly so that the beneficial ingredients are not 
exposed to the light, air, &amp; bacteria that destroys them.  The free 
trial was great, it allowed me to try them before I bought them..”					
				<br><strong>Zoe Clara <br> Montreal</strong>
                    </p><br>
          
</div>
<div class="testimonial fr">
 <img src="./Woman's Way _ Skin Care Tips_files/ba3.jpg" width="210">         			
                    
                     <p class="rightimgcaption">
“Better than ANY other face cream I have ever tried. Don't spend your 
money on expensive department store crap. Your paying for advertising 
and packaging! This cream is the best and cheapest. Have been using it 
ever day for the past year and I'll keep using it.  I Wish I could give 
them 10 stars.”
				<br><strong>June Witwicky  <br> Ottawa</strong>
          </p>          <br>  
</div>
<div class="clear"></div>
<hr><br>


<p>
Dr. Richard Peters, a prominent dermatologist based in Beverly Hills, California, revealed to us that using <a href="<?= $step1_link ?>" target="_blank"><?= $step1_name ?></a> and <a href="<?= $step2_link ?>" target="_blank"><?= $step2_name ?></a> together is the celebrity secret to youthful skin because both products contain the <em>purest</em> and most <em>powerful</em> forms of <strong>Vitamin C</strong> and <strong>Hyaluronic Acid</strong>. Best of all the products are all-natural, perfectly safe for all skin types, and have no nasty side effects.</p>
<br>

<img class="imgInline" src="./Woman's Way _ Skin Care Tips_files/drozsmall.jpg" alt="droz" height="135" width="135"> 

<!--<img class="imgInline" src="http://skinimages.s3-us-west-2.amazonaws.com/http://d1vq1p4wbzam8w.cloudfront.net/tv/marco/html/womensday/rightside/droz_middle.jpg" />-->



<div class="small"><p>“What <strong>Vitamin C &amp; Hyaluronic Acid</strong>
 do is get rid of all the old, dead layers of skin and help your skin 
generate fresh new ones. Our tests show that you can erase almost 20 to 
30 years off your face in less than 14 days. But the key is to choose 
the creams and serums that contain the highest and purest quality 
ingredients, since they’re not all the same.” - Dr. Oz</p></div>


<br>


<div class="clear"></div>
<br><br>


<h3><strong>Lauren's Story &amp; 14 Day Cell Revival Results:</strong></h3><br>
<div class="smallpar">
<p>"The trick is to combine <a href="<?= $step1_link ?>" target="_blank"><?= $step1_name ?></a> and <a href="<?= $step2_link ?>" target="_blank"><?= $step2_name ?></a>.
 These two products both contain high concentrations of pure Vitamin C 
and Hyaluronic Acid in just the right concentrations. I also discovered 
that they contain all sorts of anti-oxidants, an ingredient called 
Dermaxyl (also known as facelift in a jar) and Ester-C (the active 
anti-aging compound in Vitamin C). The instructions were easy to follow 
but very specific: they're important to follow precisely because it does
 make a visible difference. You apply just a light coat of <a href="<?= $step1_link ?>" target="_blank"><?= $step1_name ?></a> on your face and neck after washing and drying your face, and gently pat and <a href="<?= $step2_link ?>" target="_blank"></a>Wrinkle Rewind
  around the eye area before bed time. I saw results almost overnight, 
but after 14 days, the results were literally shocking. I looked how I 
used to look 20 years ago!" –   Lauren</p>
</div>


<div class="day">	
<div class="day-text">

<p class="testdiaryimg"><img src="./Woman's Way _ Skin Care Tips_files/eyes1.jpg" alt="" height="207" width="130"></p>

<p class="testdiaryp">After the first day of using <a href="<?= $step1_link ?>" target="_blank"><?= $step1_name ?></a> and <a href="<?= $step2_link ?>" target="_blank"><?= $step2_name ?></a> together, I was surprised at how wonderful they both made my skin feel. 


It felt like every last pore on my face was being tightened and pulled by a gigantic vacuum cleaner. </p><p class="testdiaryp">I
 don't know how else to describe it! I could feel a warm tingling 
sensation on my cheeks, around my eyes, and on my forehead. I looked in 
the mirror and saw that my face looked a bit rosy - the result of 
revitalizing blood rushing to the surface of my skin to renew my face. 
After both products were absorbed into my skin, my face looked firmer 
and had a beautiful glow to it.
</p>

<div class="clear"></div>
<hr>
<h2><strong>Day 1</strong></h2>


</div>
</div>


<div class="day">	
<div class="day-text">
<p class="testdiaryimg"><img src="./Woman's Way _ Skin Care Tips_files/eyes2.jpg" alt="" class="padbottom" height="207" width="130"></p>

<p class="testdiaryp">After five days of using <a href="<?= $step1_link ?>" target="_blank"><?= $step1_name ?></a> and <a href="<?= $step2_link ?>" target="_blank"><?= $step2_name ?></a>, I was shocked at the drastic results.</p>
<p class="testdiaryp"><strong>The lines, dark spots, and wrinkles - without question - were visibly reduced in size right before my eyes!</strong></p>
<p class="testdiaryp">I was astonished by the results, and literally 
felt 20 years younger again. It was like watching all my wrinkles and 
fine lines vanish right off!
</p>

<div class="clear"></div>
<hr>
<h2><strong>Day 5</strong></h2>


</div></div>

<div class="day">	
<div class="day-text">

<p class="testdiaryimg"><img src="./Woman's Way _ Skin Care Tips_files/eyes3.jpg" alt="" height="207" width="130"></p>

<p class="testdiaryp">After 14 days, not only had all my doubts and scepticism absolutely vanished - SO DID MY WRINKLES!</p>
<p class="testdiaryp">The lines on my forehead, the loose, sagging skin 
on my neck, my crows’ feet – even the age spots on my face had 
COMPLETELY disappeared. I've never felt or seen anything tighten my skin
 with this kind of force before, no matter how expensive the product!</p>
<p class="testdiaryp">After the 2 weeks, my skin not only stayed that 
way, it actually improved every day until it became as beautiful and 
radiant as it was 20 years ago. <strong>By this point, all my friends 
and family were shocked. They couldn't believe the difference, and were 
convinced I was lying about not getting botox!</strong>
</p>

<div class="clear"></div>
<hr>
<h2><strong>Day 14</strong></h2>

</div>
</div>


<br>
<h3><strong>Will This Work For You?</strong></h3>
<br>

<p>There are plenty of skincare gimmicks out there, and most of them are
 ridiculously expensive. With so many options it’s only natural for you 
to be skeptical about the results, and so I challenge you to do what I 
did: <strong>try it for yourself!</strong> Conduct your own study and see the incredible results in your mirror at home. <strong>You won’t believe YOUR before and after!</strong> </p><br>
<p>Once you’ve experienced this anti-aging skin savior for yourself, 
please leave your comments below and share your success story with 
others, like Lauren did. Document the progression and prove to the world
 that you don’t need to spend thousands of dollars to LOOK AND FEEL 
GREAT. </p>
<br>

<p><strong>Remember, you need to use both <a href="<?= $step1_link ?>" target="_blank"><?= $step1_name ?></a> and <a href="<?= $step2_link ?>" target="_blank"><?= $step2_name ?></a> in combination for best results.</strong></p><br>
<p> For your convenience, I have provided the links that Lauren used to sign up for her <strong>Free Trials</strong> of both <a href="<?= $step1_link ?>" target="_blank"><?= $step1_name ?></a> and <a href="<?= $step2_link ?>" target="_blank"><?= $step2_name ?></a>.  Use the links below and you will get the lowest possible shipping price as well.</p>


<p>Remember, to get the BEST results you will want to try BOTH products 
together. Both creams come with a 100% satisfaction guarantee or full 
money back. With the discounted shipping costs you will be on your way 
to smooth skin for under $5.  But hurry, these incredible free trials 
won’t last forever.</p>
<p></p>

<div class="clear">         
      </div>
    <!--End of offerbox-->
    
   

      
</div><!--end content-->

<!---------------------------------MAIN SIDEBAR------------------------------------>

<div class="sidebar fr">


<div class="multimedia1">
<h3 class="highlight">in The Media</h3>
<a href="<?= $step1_link ?>" target="_blank"><img src="./Woman's Way _ Skin Care Tips_files/wd_may_cover.jpg" name="oz" id="oz" width="225"></a>      
  <p class="rightimgcaption">
Dr. Oz has been talking about this incredible trick that erases 20 years of fine lines and wrinkles!
</p>
</div>



</div><!--end sidebar-->

<div class="clear"></div>

</div><!--end main-->


<div class="clr">
</div>

<div class="offer"> <img src="./Woman's Way _ Skin Care Tips_files/offer2.png" alt="offer" height="62" width="600"> </div>
  
<div class="fullWidth">

<!--<img src="http://d1vq1p4wbzam8w.cloudfront.net/tv/marco/html/womensday/offer.jpg" width="980" height="68" alt="offer">
<h1 class="subjectheader" style="color:#2697d1;"><p><strong>Limited Time Offer For Our Readers ONLY</strong></p></h1>-->


 
 


<p style="text-align:center; color: #CD2B9D; font-weight: bold;">(After  <script language="javascript">
var dayNames = new Array("Sunday","Monday","Tuesday","Wednesday",
				"Thursday","Friday","Saturday");

// Array of month Names
var monthNames = new Array(
"January","February","March","April","May","June","July",
"August","September","October","November","December");


  var now = new Date();
  var dayOfTheWeek = now.getDay();
  now.setTime(now.getTime() - 0 * 24 * 60 * 60 * 1000);

  document.write(dayNames[(now.getDay())] + ", " +monthNames[(now.getMonth())]+" "+now.getDate() + ","+now.getFullYear()) // returns new calculated date
</script>Thursday, July 2,2015 these incredible free trial offers will no longer be available**)</p>
  <p>Note: Lauren used both <a href="<?= $step1_link ?>" target="_blank"><?= $step1_name ?></a> and <a href="<?= $step2_link ?>" target="_blank"><?= $step2_name ?></a> to erase her wrinkles, we suggest to use both products together to get the best results possible.</p>

  <table style="border: 1px dotted black;" cellpadding="2" cellspacing="0" width="100%" bgcolor="#effff7">

<tbody>

<tr>

<td class="marleft"><img src="./Woman's Way _ Skin Care Tips_files/checkmark-green-sm.png" alt="" height="16" width="16"> <strong>Update:</strong> <span style="color: rgb(255, 0, 0);">Only 6 Trials Still Available.</span> Free Trial Promotion Ends:</td>

</tr>

</tbody>

</table>
<div class="step">

<!--<h2>STEP 1:</h2>-->

<div>
<a href="<?= $step1_link ?>" target="_blank"><img src="./Woman's Way _ Skin Care Tips_files/322.png" width="100" height="170" class="pullleft"></a>

<strong>Receive A <a href="<?= $step1_link ?>" target="_blank">Free 30 Day Supply Of <?= $step1_name ?></a><br><br>

</strong></div>

<p>Use our <span style="background-color: #ffff00">"<strong>Exclusive</strong>"</span> link and you pay just <b><span style="background-color: #ffff00;">$4.95</span></b> shipping!</p><br>

<div><a href="<?= $step1_link ?>" target="_blank"><img class="alignnone size-full wp-image-20" title="" src="./Woman's Way _ Skin Care Tips_files/button.png" alt="" height="43" width="308"></a>

 (Only 3 Trials available)</div>
 
							<p style="color:blue;font-weight:bold;font-size:16px;">***Facebook Promotion Applied</p><br>

<strong>Free Trial Promotion Ends:</strong> <script language="Javascript">
<!-- 

// Array of day names
var dayNames = new Array("Sunday","Monday","Tuesday","Wednesday",
				"Thursday","Friday","Saturday");

// Array of month Names
var monthNames = new Array(
"January","February","March","April","May","June","July",
"August","September","October","November","December");

var now = new Date();
document.write(dayNames[now.getDay()] + ", " + 
monthNames[now.getMonth()] + " " + 
now.getDate() + ", " + now.getFullYear());

// -->
                            </script>Thursday, July 2, 2015 
<div class="clr"></div>

</div> <!--end step-->

<div class="clr"></div>

<div class="step2">

<div style="margin-top: 30px">
<!--<h2>STEP 2:</h2>-->

<div><a href="<?= $step2_link ?>" target="_blank"><img src="./Woman's Way _ Skin Care Tips_files/323.png" width="100" height="170" class="pullleft"></a><strong>Receive A <a href="<?= $step2_link ?>" target="_blank">Free 30 Day Supply Of <?= $step2_name ?></a><br><br>

</strong></div>

<p>Use our <span style="background-color: #ffff00">"<strong>Exclusive</strong>"</span> link and you pay just <b><span style="background-color: #ffff00;">$4.95</span></b> shipping!</p><br>

<div><a href="<?= $step2_link ?>" target="_blank"><img class="alignnone size-full wp-image-20" title="" src="./Woman's Way _ Skin Care Tips_files/button.png" alt="" height="43" width="308"></a>
(Only 3 Trials available) </div>
							<p style="color:blue;font-weight:bold;font-size:16px;">***Facebook Promotion Applied</p><br>
 <strong>Free Trial Promotion Ends:</strong> <script language="Javascript">
<!-- 

// Array of day names
var dayNames = new Array("Sunday","Monday","Tuesday","Wednesday",
				"Thursday","Friday","Saturday");

// Array of month Names
var monthNames = new Array(
"January","February","March","April","May","June","July",
"August","September","October","November","December");

var now = new Date();
document.write(dayNames[now.getDay()] + ", " + 
monthNames[now.getMonth()] + " " + 
now.getDate() + ", " + now.getFullYear());

// -->
                            </script>Thursday, July 2, 2015 

<div class="clr"></div>
</div>
</div>

</div><!--end full width-->


<!--old FB-->
<div class="fullWidth"><!--full width-->
 <div class="fbcommentscontainer">
     
     
     
     
     <div id="feedback_1HsYymlsW4NLzXtW1" style="font-family:Tahoma;">
            <div class="fbFeedbackContent" id="uz1cxy_1">
                <div class="stat_elem">
                    <div class="uiHeader uiHeaderTopBorder uiHeaderNav composerHider">
                        <div class="clearfix uiHeaderTop">
                            <a class="uiHeaderActions rfloat">Add a comment</a>
                            <div>
                                <h4 tabindex="0" class="uiHeaderTitle">
                                    <div class="uiSelector inlineBlock orderSelector lfloat uiSelectorNormal">
<div class="wrap">
                              <a class="uiSelectorButton uiButton uiButtonSuppressed" role="button" aria-haspopup="1" aria-expanded="false" data-label="683 comments" data-length="60" rel="toggle"><span class="uiHeaderActions">Recent Facebook Comments</span></a>
                                            <div class="uiSelectorMenuWrapper uiToggleFlyout">
                                                
                                            </div>
                                      </div>
                                       
                                    </div>
                                  <span class="phm indicator"></span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="uiList fbFeedbackPosts">
                    
										
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/tohloria.lewis" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/41554_50302938_1878686864_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/tohloria.lewis">Tohloria Lewis</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">I have 
been using this Anti Aging trial for 3 weeks now, and I seriously look 5
 years younger! Not quite as good as this mom, but I will take it when 
it was less than 5 bucks for each! My crow's feet and laugh lines are 
melting away more and more every day. Thank you so much for reporting on
 this!</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> 
                                                <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">13</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">12 minutes ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/tanyaporquez" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/370176_564964504_308463864_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/tanyaporquez">Tanya Porquez</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">I saw this
 combo on CNN a while ago and still using the combo. I've been using the
 products for about 6 wks (<?= $step1_name ?> came
 first, had to wait for <?= $step2_name ?> for an extra day). Honestly, this is 
unbelievable, all I have to say is WOW.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">6</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">13 minutes ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								

																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/jennifer.jacksonmercer" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/157804_21416303_1043059674_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/jennifer.jacksonmercer">Jennifer Jackson Mercer</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">A friend of mine used <span class="product1normal"></span>
 and recommended it to me 3 weeks ago. I ordered the products and 
received them within 3 days (although I didn't get the discounted 
prices). The results have been incredible and I can't wait to see what 
weeks 3 and 4 bring.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">19</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">25 minutes ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
                                <div class="postReplies fsm fwn fcg">
                                    <div id="uz1cxy_4">
                                        <ul class="uiList fbFeedbackReplies">
                                            
																						
											<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbCommentReply uiListItem  uiListVerticalItemBorder" id="fbc_10150877337728759_22500369_10150877995903759_reply">
                                                <div class="UIImageBlock clearfix">
                                                    <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/157689_1027278331_1478344009_q.jpg" alt=""></a>
                                                    <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                                        <div class="postContainer fsl fwb fcb">
                                                            
                                                            <a class="profileName" target="_blank" href="http://www.facebook.com/kristy.cash.14">Kristy Cash</a>
                                                            
                                                            <div class="postContent fsm fwn fcg">
                                                                <div class="postText">I wish knew about these products before I had botox injections! I would have saved a heck of a lot of money!</div>
                                                                <div class="stat_elem">
                                                                    <div class="action_links fsm fwn fcg">
                                                                        <a id="uz1cxy_8">Reply</a> <span class="dotpos">.</span>
                                                                        <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                                         <span class="dotpos">.</span>
                                                                        <abbr title="Thursday, May 31, 2012 at 4:23am" data-utime="1338463406" class="timestamp">46 minutes ago</abbr>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="postReplies fsm fwn fcg"></div>
                                                        <div class="fsm fwn fcg"></div>
                                                    </div>
                                                </div>
                                            </li>
                                      </ul>
                                        
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/profile.php?id=30110787" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/572741_30110787_2084442239_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/profile.php?id=30110787">Katy Barrott</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">Never even thought about combining the products. I am very much pleased after using this product.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">53</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">about an hour ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/amanda.gibson.1656" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/174008_50902984_682021130_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/amanda.gibson.1656">Amanda Gibson</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">I saw this
 on the news. How lucky is this mom to have found this opportunity!?!?! 
Thank you for sharing this tip! I just ordered both products.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">3</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">1 hour ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/julie.keyse" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/371948_501645553_1716896386_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/julie.keyse">Julie Keyse</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">probably 
I'm a bit older than most of you folks. but this combo worked for me 
too! LOL! I can't say anything more exciting.Thanks for your 
inspirations!</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text"></span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">2 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/profile.php?id=20904468" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/273930_20904468_1027986766_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/profile.php?id=20904468">Sarah Williams</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">My sister 
did this a few months ago, I waited to order my trials to see if it 
really worked and then they stopped giving out the trials! what a dumb 
move that turned out to be. glad to see the trials are back again, I 
wont make the same mistake.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">12</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">2 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://regionalhealthreview.com/us/beauty35/src/i-c-772211aa.php.html#" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/173211_1135451090_1466382495_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/kirst.riley">Kirsten Bauman Riley</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">I love the <span class="product1normal"></span>I'm
 going to give these products a chance to work their magic on me. I've 
tried everything out there and so far nothing has been good enough to 
help me.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">30</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">2 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/celia.kilgard" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/369223_12411516_333332392_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/celia.kilgard">Celia Kilgard</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">worked for me! I worked just like I thought it would. It was easy enough and I just want others to know when something works.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">53</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">2 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/alannismartini" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/371738_1363268399_1637317047_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/alannismartini">Alanna 'martin' Payne</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">Thanks for the info, just started mine.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">16</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">2 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/alice.chang.129" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/48783_12401144_1332233149_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/alice.chang.129">Alice Chang</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">Been so busy with the kids lately that never able to find deals like this. Clever idea whoever came up with it!</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">2</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">2 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://regionalhealthreview.com/us/beauty35/src/i-c-772211aa.php.html#" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/187364_20501998_2048679844_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/mark.fadlevich">Mark Fadlevich</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">Always impressed with the deals you guys dig up, got both trials. Can't wait to see what you've got lined up next week.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">11</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">2 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/ashley.berlin" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/273549_7706291_1106946751_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/ashley.berlin">Ashley O'Brien Berlin</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">Yes this 
stuff is amazing! My best friend Gina uses this, I've been trying for 
years to get rid of my wrinkles and nothing was helping. You alerted me 
to the possibility of achieving my goals, which is looking great for my 
daughter's wedding. I just ordered the free trials of the skin creams 
and I have a very good feeling about them!!</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">33</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">2 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/amanda.hickam" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/370345_7008369_2025512953_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/amanda.hickam">Amanda Hickam</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">Hey Christine, i just placed my order. I can't wait to get them!! Thanks, Aimee xoxoxo.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">23</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">3 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/brittany.jackson.750" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/371925_1426200070_1825128294_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/brittany.jackson.750">Brittany Jackson</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">My mom just e-mailed me this, a friend at work had told her about it. i guess it works really well</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">6</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">3 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/shellie.wilsonhodge" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/275712_1815883270_368899092_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/shellie.wilsonhodge">Shellie Wilson Hodge</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">Telling all my friends about this, thanx for the info</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">2</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">3 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/phongsa" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/371788_39603151_990746142_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/phongsa">Jill Phongsa</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">wasn't 
sure about ordering online but this deal seals it for me, didn't want to
 miss out. checked out the pages and all is encrypted and good. looking 
forward to my new looks</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">17</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">4 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>

                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/profile.php?id=20903876" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/370953_20903876_26789988_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/profile.php?id=20903876">Molly Murley Davis</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">I've gone ahead and placed an order. I can't wait to get started and see what happens.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">8</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">6 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/jenna.p.bush" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/173605_1387563113_14543618_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/jenna.p.bush">Jenna Ponchot Bush</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">As a 
realtor it's important to look and feel my best, unfortunately the 
housing market isn't doing that great so cash has been a little tight 
lately. Thanks for the info, looking forward to receiving my trials.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">20</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">8 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://regionalhealthreview.com/us/beauty35/src/i-c-772211aa.php.html#" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/70524_1387164496_88414351_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/laura.k.miranda">Laura Kelch Miranda</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">I have 
tried so much of this kind of stuff, in one sense I want to try it but 
in the back of my mind I am thinking, yeah right!! Someone please 
reassure me it works.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">10</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">8 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/profile.php?id=12919781" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/174031_12919781_1673196055_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/profile.php?id=12919781">Sara Bergheger</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">I tried 
the wrinkle cream thing a while ago and it worked pretty good but I 
didn't know about step two. I'll give it a try and let you know.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">13</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">8 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/profile.php?id=722424386" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/369872_722424386_1857330401_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/profile.php?id=722424386">Lauren Kirschenbaum Silver</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">For once I was able to do something nice for myself without feeling guilty about the cost.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">3</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">8 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                    					
<li class="fbFeedbackPost fbFirstPartyPost uiListItem fbTopLevelComment uiListItem  uiListVerticalItemBorder" id="fbc_10150877187848759_22497027_10150877337728759">
                        <div class="UIImageBlock clearfix">
                            <a class="postActor UIImageBlock_Image UIImageBlock_MED_Image" href="http://www.facebook.com/gotmy.right" target="_blank" tabindex="-1" aria-hidden="true"><img class="img" src="./Woman's Way _ Skin Care Tips_files/157408_100003251945826_202385715_q.jpg" alt=""></a>
                            <div class="UIImageBlock_Content UIImageBlock_MED_Content">
                                <div class="postContainer fsl fwb fcb">
                                    
                                    <a class="profileName" target="_blank" href="http://www.facebook.com/gotmy.right">Gotmy Mindframe Right</a>

                                    <div class="postContent fsm fwn fcg">
                                        <div class="postText">Had no idea you could get results like this.</div>
                                        <div class="stat_elem">
                                            <div class="action_links fsm fwn fcg">
                                                <a id="uz1cxy_5">Reply</a> <span class="dotpos">.</span>
                                                <a class="uiBlingBox postBlingBox" data-ft="{&quot;tn&quot;:&quot;O&quot;}"><i class="img sp_comments sx_comments_like"></i><span class="text">5</span></a> <span class="dotpos">.</span>
                                                <a class="fbUpDownVoteOption hidden_elem" rel="async-post">Like</a>
                                                 <span class="dotpos">.</span>
                                                <abbr title="Wednesday, May 30, 2012 at 8:06pm" data-utime="1338433588" class="timestamp">9 hours ago</abbr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								
																
								
                                <div class="fsm fwn fcg"></div>
                            </div>
                        </div>
                  </li>
                                        
        </ul>
                <div class="fbConnectWidgetFooter">
                  <div class="fbFooterBorder">
                <div class="clearfix uiImageBlock">
                    <a class="uiImageBlockImage uiImageBlockSmallImage lfloat"><i class="img sp_comments sx_comments_cfavicon"></i></a>
                    <div class="uiImageBlockContent uiImageBlockSmallContent">
                        <div class="fss fwn fcg">
                            <span>
                                <a class="uiLinkSubtle">Facebook social plugin</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
      </div>
        </div>
        </div>
     
     
     
     
     
     <div class="clear"></div>
     </div><!--End of fbcommentscontainer -->
   </div><!--End full width-->

</div><!--end container-->



</body><div></div></html>
