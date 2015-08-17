<?php $this->layout('admin::models/layout', ['title' => 'All Data']) ?>
<?php $this->start('page') ?>

<?= $this->fetch('admin::partials/adexchange') ?>

<div>
<h3>Ad Exchange Parameters</h3>
<?= $this->table($aeParams) ?>
</div>
<br>
<br>


<div>
<h3>Landers</h3>
<?= $this->multiLinkTable($landers, array(
    'admin' => array('id', '/admin/models/landers/'),
    'link' => array('id', '/landers/')
)) ?>
</div>
<br>

<div>
<h3>Products</h3>
<?= $this->table($products) ?>
</div>
<br><br>
<br>

<div>
<h3>Websites</h3>
<?= $this->table($websites) ?>
</div>
<br>
<br>

<div>
<h3>Routes</h3>
<?= $this->linkTable($routes, 'url', '') ?>
</div>
<br>
<br>



<div>
<h3>Config</h3>
<?= $this->vardump($config) ?>
</div>
<br>
<br>
<br>
<br>


<?php $this->stop() ?>
