<html>
<head>
    <title>Womens Dumb Mag</title>
</head>
<body>

<h1>Womens Dumb Magazine</h1>

<h2>Shocking new products get rid of that menopause/over 40/baby/water/period/you just ate 2 donuts/pregnancy weight immediately!
Absolutely NO EFFORT REQUIRED. Doctor's hate it. Oprah's addicted to it, Dr Oz invented it.</h2>
<div>Look at how fat this chick is!<br>[put a fat pic here]</br></div>


<div><h3>Step <?=$this->e($steps[1]->getId())?>:</h3>
Buy this shit! <a href="<?=$this->e($steps[1]->getUrl())?>"><?=$this->e($steps[1]->getName())?></a>!
<br><br>
Holy balls, it looks great!
<img src=<?=$this->e($steps[1]->getImageUrl())?>>
</div>


<div><h3>Step <?=$this->e($steps[2]->getId())?>:</h3>
It only works if you buy some more shit! <a href="<?= $steps[2]->getUrl() ?>"><?=$this->e($steps[2]->getName())?></a>!
<br><br>
<img src=<?=$this->e($steps[2]->getImageUrl())?>>
Damn... Im jelly!
</div>


</body>
</html>
