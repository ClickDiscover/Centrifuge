<?php
include dirname(dirname(__DIR__)) . "/landers/vars.php";
?>

<html>
<head>
    <title>Womens Dumb Mag</title>
</head>
<body>

<div>
<h4>Variable dump</h4>
<pre>
<?php
print_r(array(
    'step1_name' => $step1_name,
    'step1_image' => $step1_image,
    'step1_link' => $step1_link,
    'step2_name' => $step2_name,
    'step2_image' => $step2_image,
    'step2_link' => $step2_link
));
?>
</pre>
</div>

<div>
<h4>Steps</h4>
<pre>
<?= print_r($steps, true) ?>
</pre>
</div>

<div>
<h4>Assets</h4>
<p>path: <?= $assets ?></p>
</div>

<div>
<h4>Variants</h4>
<h5>Data</h5>
<pre>
<?= print_r($v, true) ?>
</pre>
<h5>Display</h5>
<?= $this->variant($v, 'headlines') ?>
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


    <div><h3>Step <?=$this->e($steps[1]->getId())?>:</h3>
    Buy this shit! <a href="<?= $step1_link ?>"><?= $step1_name ?></a>!
    <br><br>
    Holy balls, it looks great!
    <img src=<?= $step1_image ?>>
    </div>


    <div><h3>Step <?=$this->e($steps[2]->getId())?>:</h3>
    It only works if you buy some more shit! <a href="<?= $step2_link ?>"><?= $step2_name ?></a>!
    <br><br>
    <img src=<?= $step2_image ?>>
    Damn... Im jelly!
    </div>
</div>

</body>
</html>
