<?php $this->layout('admin::models/layout', ['title' => 'Product Creator']) ?>
<?php $this->start('page') ?>

<div>
<h3>Existing Products</h3>
<?= $this->table($products) ?>
</div>
<br><br>




<?php $this->stop() ?>
