<form action="" method="POST" class="form form-horizontal" style="padding-top: 40px;">
    <div class="justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <h3 class="card-header">
                    <i class="material-icons">settings</i> Mapping
                </h3>
                <div class="card-block">
                    <div class="card-text">

                    {if isset($suppliers)}
                        <div>
                        <h1>Suppliers</h1>
                        <select id="suppliers" name="suppliers"  style="width: 33.33%; margin-bottom: 30px;" multiple size="5">
                        {foreach from=$suppliers item=supplier}
                            <option value="{$supplier.id_supplier}">{$supplier.meta_title}</option>
                        {/foreach}
                        </select>
                        </div>
                    {/if}
                    <div class="row">
                    {if isset($brands)}
                        <div class="col-md-4">
                            <h1>Icecat brands</h1>
                            <div id="list-left" class="list-group col" onmousedown="on_mousedown(event)">
                            {foreach from=$brands item=brand key=key}
                                <div class="list-group-item" data-id="{$key}" data-label="{$brand}" draggable="true">{$brand}</div>
                            {/foreach}
                            </div>
                        </div>
                    {/if}
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <h1>Feed brands</h1>
                            <div id="list-right" class="list-group col" ondrop="on_drop(event);" ondragover="on_dragover(event)" ondragleave="on_dragleave(event)">
                            </div>
                        </div>
                        </div>
                        <hr>
                    </div>
                </div>
                <div class="card-footer">
 
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