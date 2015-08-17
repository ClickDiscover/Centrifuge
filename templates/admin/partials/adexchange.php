<div>
<h3>Add Ad Exchange</h3>
<form action="/admin/models/adexchange" method="POST" class="pure-form pure-form-aligned">
    <fieldset>
        <div class="pure-control-group">
            <label for="name">Name</label>
            <input name="name" id="name" type="text">
        </div>
        <div class="pure-control-group">
            <label for="affiliate_id">Affiliate ID</label>
            <input name="affiliate_id" id="affiliate_id" type="number" >
        </div>
        <div class="pure-control-group">
            <label for="vertical">Vertical</label>
            <input name="vertical" id="vertical" type="text">
        </div>
        <div class="pure-control-group">
            <label for="country">Country</label>
            <input name="country" id="country" type="text" value="US">
        </div>

        <div class="pure-controls">
            <input type="submit" class="pure-button pure-button-primary">
        </div>
    </fieldset>
</form>
</div>

