<?php
include_once "master.php";
require_once "product.php";
require_once "defines.php";


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


<div>
<?php
    foreach ($res as $r) {
        echo $r->getName();
        echo '<img src="'.$r->getImageUrl().'"/>';
    }
?>
</div>


<?php

$db = new PDO(PDO_URL);
$query = $db->query('SELECT * FROM offers');

foreach ($query as $q) {
    // print_r($q);
    print_r($q['id']);
    echo "<br>";
    print_r($q['name']);
    echo "<br>";
    print_r($q['parameters']);
    echo "<br>";
}


?>



<?php
include "tracking.php";
?>


</body>
</html>
