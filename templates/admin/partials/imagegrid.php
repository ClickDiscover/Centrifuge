<h3><?= $title ?></h3>
<?php $slug = str_replace(' ', '-', strtolower($title)) ?>
<?php foreach(array_chunk($data, $width) as $row): ?>
    <div class="pure-g">
        <?php foreach($row as $f): ?>
            <div class="<?= $slug ?> pure-u-1 pure-u-md-1-<?= $width ?>">
                <div class="l-box">
                    <p><?= $f ?></p>
                    <img src="<?= $f ?>"/>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php endforeach ?>
