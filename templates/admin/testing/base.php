<?php include dirname(dirname(__DIR__)) . "/landers/vars.php"; ?>
<?php $this->layout('admin::models/layout', ['title' => 'Womens Dumb Mag']) ?>
<?php $this->start('page') ?>


<div>
<h4>Variable dump</h4>
<?= $this->vardump(array(
    'step1_name' => $step1_name,
    'step1_image' => $step1_image,
    'step1_link' => $step1_link,
    'step2_name' => $step2_name,
    'step2_image' => $step2_image,
    'step2_link' => $step2_link
));
?>
</div>


<div>
<h4>Lander dump</h4>
<pre>
<?php
// $web = $lander->website->toArray();

?>
</pre>
</div>

<div>
<h4>Assets</h4>
<p>path: <?= $assets ?></p>
</div>



<div>
<h4>Geo</h4>
<pre>
<?php
foreach(\Punic\Language::getAll() as $code => $lang) {
    // echo $code . " => " . $geo->pronoun() . PHP_EOL;
}


?>
<?php var_dump($geo) ?>
</pre>
</div>



<div>
<h1><strong class="text-pink">Breaking:</strong> Controversial <?= $geo->money(5000) ?> 'Skinny Pill' Hits The <?= $geo->pronoun() ?> Market.</h1>
<h4>Think Diet Pills Don't Work? Here's One That Doctors Say May Actually Deliver.</h4>

<h5>
a study published in the journal Lipids in Health & Disease, subjects taking Simply Garcinia lost an average of <?= $geo->weight(19.2) ?>
</h5>


</div>

<div>
<h4>Variants</h4>
<h5>Data</h5>
<pre>
<?= $this->vardump($v, true) ?>
</pre>
<h5>Display</h5>
</div>



<br>
<br>
<br>
<h4>Example Lander</h4>

<div>
    <h1>Womens Dumb Magazine</h1>

    <h2>Shocking new products get rid of that menopause/over 40/baby/water/period/you just ate 2 donuts/pregnancy weight immediately!
    Absolutely NO EFFORT REQUIRED. Doctor's hate it, Oprah's addicted to it, Dr Oz invented it.</h2>
    <div>Look at how fat this chick is!<br>[put a fat pic here]</br></div>


    <div><h3>Step <?=$this->e($steps[1]->getStepNumber())?>:</h3>
    Buy this shit! <a href="<?= $step1_link ?>"><?= $step1_name ?></a>!
    <br><br>
    Holy balls, it looks great!
    <img src=<?= $step1_image ?>>
    </div>


    <div><h3>Step <?=$this->e($steps[2]->getStepNumber())?>:</h3>
    It only works if you buy some more shit! <a href="<?= $step2_link ?>"><?= $step2_name ?></a>!
    <br><br>
    <img src=<?= $step2_image ?>>
    Damn... Im jelly!
    </div>
</div>



<div>
<h4>Request Shit</h4>
_SERVER
<?= $this->vardump($_SERVER); ?>
_GET
<?= $this->vardump($_GET); ?>
</div>



<?php $this->stop() ?>

