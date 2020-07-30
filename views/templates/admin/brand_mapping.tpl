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
                                <select id="suppliers" name="suppliers"  style="width: 30%; margin-bottom: 30px;" multiple size="5">
                                    {foreach from=$suppliers item=supplier}
                                        <option class="list-group-item" value="{$supplier.id_supplier}">
                                            {$supplier.meta_title}
                                        </option>
                                    {/foreach}
                                </select>
                            </div>
                        {/if}
                        <div class="row">
                            <div class="col-md-4">
                                <h1>Icecat brands</h1>
                                <div id="scrollArea" class="clusterize-scroll list-group col">
                                    <div id="contentArea" class="clusterize-content">
                                        <div class="clusterize-no-data">Loading data…</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <h1>Feed brands</h1>
                                <div id="list-right" class="list-group col" ondrop="on_feed_drop(event);" ondragover="on_feed_dragover(event)" ondragleave="on_feed_dragleave(event)" onmousedown="on_feed_mousedown(event)">
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