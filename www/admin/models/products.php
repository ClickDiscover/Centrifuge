<?php $this->layout('admin::models/layout', ['title' => 'Product Creator']) ?>

<?php $this->start('style') ?>
.l-box {
    margin-bottom: 2em;
    text-align:center;
    width: 24em;
    max-width: 24em;
    height: 28em;
    max-height: 28em;
    border: 1px solid black;
    display: inline-block;
    cursor: pointer;
}

.l-box img {
    max-width: 75%;
    max-height: 75%;
}

<?php $this->stop() ?>
<?php $this->start('page') ?>

<script>
$(function() {
    $('.unused-products').click(function () {
        var url = $(this).text().trim();
        var url = url.split('/').reverse()[0];
        $('#image_url').val(url);
    });
});
</script>

<div>
<h3>Add product</h3>
<form action="/admin/models/products" method="POST" class="pure-form pure-form-aligned">
    <fieldset>
        <div class="pure-control-group">
            <label for="name">Name</label>
            <input name="name" id="name" type="text" autofocus>
        </div>
        <div class="pure-control-group">
            <label for="image_url">Image File</label>
            <input name="image_url" id="image_url" type="text">
        </div>

        <div class="pure-controls">
            <input type="submit" class="pure-button pure-button-primary">
        </div>
    </fieldset>
</form>
</div>



<br><br>

<div>
<h3>Existing Products</h3>
<?= $this->table($products) ?>
</div>
<br><br>

<div>
<?= $this->fetch('admin::partials/imagegrid', [
    'title' => 'Unused Products',
    'width' => 5,
    'data' => $unused
]);?>
</div>
<br>
<br>
<br>
<br>
<br>
<br>

<div>
<?= $this->fetch('admin::partials/imagegrid', [
    'title' => 'Used Products',
    'width' => 5,
    'data' => $existing
]);?>
</div>

<?php $this->stop() ?>
