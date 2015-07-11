<?php
include "master.php";


$url = click_url(1);

?>

<html>
<head>

</head>
<body>

<h1>Hello</h1>
<p><?= $url ?></p>
<p><?= click_url(2) ?></p>
<p><?= $step1_link ?></p>
<p><?= $step2_link ?></p>


<h3>Server shit</h3>


<?php
print_r($_SERVER);

include "tracking.php";
?>
</body>
</html>
