<?php $this->layout('admin::models/layout', ['title' => 'Lander Creator']) ?>
<?php $this->start('page') ?>

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

    function variationChange () {
        var wid = $('#website').val();
        $('#smart-variations').find('select').each(function () { $(this).prop('disabled', true) });
        $('.variation-container').hide();
        $('#website-variation-' + wid).show();
        $('.variation-select-' + wid).prop('disabled', false);
    };
    $('#website').change(variationChange);
    variationChange();
});
</script>
<div>
<h3>Create Lander</h3>
<form action="/admin/models/landers" method="POST" id="reachForm" class="pure-form pure-form-aligned">
    <fieldset>
        <legend>Required</legend>
        <div class="pure-control-group">
            <label for="notes">Name</label>
            <input name="notes" id="notes" type="text">
        </div>

        <div class="pure-control-group">
            <label for="website">Website</label>
            <select name="website_id" id="website">
                <?php foreach($websites as $w): ?>
                    <option value="<?= $w->id ?>"><?= $w->name ?></option>
                <?php endforeach ?>
            </select>
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
                        <option value="<?= $p->id ?>"><?= $p->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="pure-control-group">
                <label for="step-2">Step 2</label>
                <select name="product2_id" id="step-2">
                    <?php foreach($products as $p): ?>
                        <option value="<?= $p->id ?>"><?= $p->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <br>

        <legend>Custom URL (Optional)</legend>
        <div class="pure-control-group">
            <label for="route">URL</label>
            <input name="route" id="route" class="pure-input-1-4" type="text" placeholder="Example: /blog/wow-weight-loss">
        </div>

        <br>

        <legend>Variations</legend>
        <div id="smart-variations">
            <?php foreach($variants as $wid => $vs): ?>
                <div id="website-variation-<?= $wid ?>" class="variation-container" style="display: none;">
                    <?php foreach($vs as $name => $versions): ?>
                        <div class="pure-control-group">
                            <label for="variation-select-<?= $wid ?>"><?= $name ?></label>
                            <select name="variants[<?= $name ?>]" class="variation-select-<?= $wid ?>" disabled>
                                    <option value="default">Default</option>
                                <?php foreach($versions as $value): ?>
                                    <option value="<?= $value ?>"><?= $value ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endforeach ?>
        </div>

        <br>
        <legend>Tracking Tags</legend>
        <div class="pure-control-group">
            <label>Tracking</label>
            <input type="checkbox" name="tracking[]" value="googleAnalytics" checked> Google Analytics
            <input type="checkbox" name="tracking[]" value="perfectAudience" checked> Perfect Audience
        </div>

        <br>

        <div class="pure-controls">
            <input type="submit" class="pure-button pure-button-primary">
        </div>

    </fieldset>
</form>
</div>

<br>
<br>

<div>
<h3>Existing Landers</h3>
<?= $this->multiLinkTable($landers, array(
    'admin' => array('ID', '/admin/models/landers/'),
    'link' => array('ID', '/landers/')
)) ?>
</div>
<br>
<br>

<div>
<h3>Existing Routes</h3>
<?= $this->linkTable($routes, 'url', '') ?>
</div>
<br>
<br>

<div>
    <h3>Variations</h3>
    <?= $this->vardump($variants) ?>
</div>
<br>
<br>

<?php $this->stop() ?>
