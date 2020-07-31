<form action="" method="POST" class="form form-horizontal" style="padding-top: 40px;">
    <div class="justify-content-center row">
        <div class="col-xl-10">
            <div class="card">
                <h3 class="card-header">
                    <i class="material-icons">settings</i> Mapping
                </h3>
                <div class="card-block">
                    <div class="card-text" style="max-width: 100%">
                    <div class="row" style="padding: 10px; border-bottom: 2px solid #DBE6E9;">
                        <div class="col-md-9" style="border-right: 2px solid #DBE6E9;">
                            <div class="row">
                                <div class="col-md-6">
                                    {if isset($suppliers)}
                                        <h1>Suppliers</h1>
                                        <select id="suppliers" name="suppliers"  style="width: 100%; margin-bottom: 30px;" multiple size="5">
                                        {foreach from=$suppliers item=supplier}
                                            <option class="list-group-item" value="{$supplier.id_supplier}">{$supplier.meta_title}</option>
                                        {/foreach}
                                        </select>
                                    {/if}
                                </div>
                                <div class="col-md-6">
                                    {if isset($suppliers)}
                                        <h1>Brands</h1>
                                        <select id="suppliers" name="suppliers"  style="width: 100%; margin-bottom: 30px;" multiple size="5">
                                        {foreach from=$suppliers item=supplier}
                                            <option class="list-group-item" value="{$supplier.id_supplier}">{$supplier.meta_title}</option>
                                        {/foreach}
                                        </select>
                                    {/if}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    {if isset($suppliers)}
                                        <h1>Categories</h1>
                                        <select id="suppliers" name="suppliers"  style="width: 100%; margin-bottom: 30px;" multiple size="5">
                                        {foreach from=$suppliers item=supplier}
                                            <option class="list-group-item" value="{$supplier.id_supplier}">{$supplier.meta_title}</option>
                                        {/foreach}
                                        </select>
                                    {/if}
                                </div>
                                <div class="col-md-6">
                                    {if isset($suppliers)}
                                        <h1>Models</h1>
                                        <select id="suppliers" name="suppliers"  style="width: 100%; margin-bottom: 30px;" multiple size="5">
                                        {foreach from=$suppliers item=supplier}
                                            <option class="list-group-item" value="{$supplier.id_supplier}">{$supplier.meta_title}</option>
                                        {/foreach}
                                        </select>
                                    {/if}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4>Price Range</h4>
                            <div style="display: flex;padding: 5px 10px 20px;">
                                <p>$</p>
                                <input type="text" style="margin: 0 10px; width: 100px;">
                                <p>~</p>
                                <input type="text" style="margin: 0 10px; width: 100px;">
                            </div>
                            <h4>Rule</h4>
                            <div style="padding: 5px 10px 20px;">
                                <div class="btn-group">
                                    <button type="button" name="submitDateDay" class="btn btn-default submitDateDay active">
                                        Fix Price
                                    </button>
                                    <button type="button" name="submitDateMonth" class="btn btn-default submitDateMonth">
                                        Percentage
                                    </button>
                                </div>
                                <div style="display: flex; margin-top: 10px;">
                                    <input type="text" style="margin: 0 10px 0 0; width: 100px;">
                                    <p>$</p>
                                </div>
                            </div>
                            <hr>
                            <div style="padding: 5px 10px 20px;">
                                <div class="checkbox">
                                    <label class="" style="text-align: left;"><input type="checkbox" value="cheapest" checked="checked">Cheapest Product</label>
                                </div>
                                <div class="checkbox">
                                    <label class="" style="text-align: left;"><input type="checkbox" value="next_cheapest" checked="checked">Next Cheapest Product</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding: 30px 10px;">
                    {if isset($brands)}
                        <div class="col-md-12">
                            <h1>Products</h1>
                            <div class="choice-table table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ProductID</th>
                                            <th>Image</th>
                                            <th class="text-center">Manufacturer</th>
                                            <th class="text-center">Model</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    {/if}
                        <hr>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
                <script>
                    var mapping_ajax_link = "{$mapping_ajax_link}";
                    var supplier_brand_mapping = [];
                    var is_changed = false;
                </script>
            </div>
        </div>
    </div>
</form>