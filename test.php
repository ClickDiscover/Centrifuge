<?php
include "master.php";
require "product.php";


$url = click_url(1);
$affiliate_id = 170317;
$vertical = "skin";
$country = "US";

$res = Product::fetchFromAdExchange($affiliate_id, $vertical, $country);


?>

<html>
<head>

</head>
<body>

<h1>Hello</h1>
<p><?= $url ?></p>
<p><?= click_url(2) ?></p>
<h3>Server shit</h3>


<?php
    echo "<pre>";
    print_r($_SERVER);
    echo "</pre>";
?>


<div>
<?php
    foreach ($res as $r) {
        echo $r->getName();
        echo '<img src="'.$r->getImageUrl().'"/>';
    }
?>
</div>

<?php
include "tracking.php";
?>

<?php phpinfo(); ?>

</body>
</html>
