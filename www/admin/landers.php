<?php


?>

<html>
<head>
<title>Lander Creator</title>
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

<script>
$(function() {
    function toggleNetworkOptions() {
        var ntype = $('#network-type').val();
        if(ntype == "adexchange") {
            $('#ae-params').show();
            $('#network-params').hide();
        } else {
            $('#ae-params').hide();
            $('#network-params').show();
        }
    };
    $('#network-type').change(toggleNetworkOptions);
    toggleNetworkOptions();
});
</script>

</head>
<body>

<div>
<h3>Create Lander</h3>
<form action="/admin/create" method="POST" id="reachForm" class="pure-form pure-form-aligned">
    <fieldset>

        <div class="pure-control-group">
            <label for="notes">Name</label>
            <input name="notes" id="notes" type="text">
        </div>

        <div class="pure-control-group">
            <label for="website">Website</label>
            <select name="website_id" id="website">
                <?php foreach($websites as $w): ?>
                    <option value="<?= $w['id'] ?>"><?= $w['name'] ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="pure-control-group">
            <label for="variants">Variants</label>
            <input name="variants" id="variants" type="text" value="{}">
        </div>

        <div class="pure-control-group">
            <label>Tracking</label>
            <input type="checkbox" name="tracking[]" value="googleAnalytics" checked> Google Analytics
            <input type="checkbox" name="tracking[]" value="perfectAudience" checked> Perfect Audience
        </div>


        <div class="pure-control-group">
            <label for="network-type">Network Type</label>
            <select name="offer" id="network-type">
                <option value="adexchange">Ad Exchange</option>
                <option value="network">Other Network</option>
            </select>
        </div>

        <div id="ae-params">
            <div class="pure-control-group">
                <label for="ae-params">Ad Exchange Parameters</label>
                <select name="param_id" id="ae-params">
                    <?php foreach($aeParams as $ae): ?>
                        <option value="<?= $ae['id'] ?>"><?= $ae['name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <div id="network-params">
            <div class="pure-control-group">
                <label for="step-1">Step 1</label>
                <select name="product1_id" id="step-1">
                    <?php foreach($products as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="pure-control-group">
                <label for="step-2">Step 2</label>
                <select name="product2_id" id="step-2">
                    <?php foreach($products as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <div class="pure-controls">
            <input type="submit" class="pure-button pure-button-primary">
            <button onclick="test()">Debug</button>
        </div>

    </fieldset>
</form>
</div>


<br>
<br>

<div>
<h3>Existing Landers</h3>
<?= $this->table($landers) ?>
</div>
<br>
<br>

<div>
<h3>Existing Websites</h3>
<?= $this->table($websites) ?>
</div>
<br>
<br>

<div>
<h3>Existing Products</h3>
<?= $this->table($products) ?>
</div>
<br>
<br>

<div>
<h3>Existing Routes</h3>
<?= $this->table($routes) ?>
</div>
<br>
<br>

</body>
</html>
