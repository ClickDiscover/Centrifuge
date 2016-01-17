<?php $this->layout('admin::models/layout', ['title' => 'Server Control']) ?>
<?php $this->start('page') ?>

<script>
$(function() {

    function postButton(url) {
        return function () {
            $.post(url, function (res) {
                alertify.success("Success: " + res);
            });
        };
    }

    $('#cache-button').click(postButton("/admin/control/cache/clear"));
    $('#session-button').click(postButton("/admin/control/session/clear"));

});
</script>

<div id="messages"></div>

<div>
<h3>Version</h3>
<p>Centrifuge: <?= $centrifuge_version ?></p>
<p>Landers: <?= $lander_version ?></p>

</div>
<br>
<br>
<br>


<div>
<h3>Centrifuge Last Commit</h3>
<?= $this->vardump($centrifuge_lc) ?>
</div>
<br>
<br>
<br>


<div>
<h3>Landers Last Commit</h3>
<?= $this->vardump($landers_lc) ?>
</div>
<br>
<br>
<br>

<div>
<h3>Cache</h3>
<button type="button" id="cache-button" class="pure-button pure-button-primary" >Clear Cache</button>
<button type="button" id="session-button" class="pure-button pure-button-primary" >Clear Session</button>
</div>
<br>
<br>
<br>

<div>
<h3>Config</h3>
<?= $this->vardump($config) ?>
</div>
<br>
<br>
<br>


<?php $this->stop() ?>
