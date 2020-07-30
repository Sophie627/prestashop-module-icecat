<form action="" method="POST" class="form form-horizontal" style="padding-top: 40px;">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <h3 class="card-header">
                    <i class="material-icons">settings</i> Mapping
                </h3>
                <div class="card-block row">
                    <div class="card-text">

                    {if isset($suppliers)}
                        <select id="suppliers" name="suppliers" size="10" multiple style="width: 20%">
                        {foreach from=$suppliers item=supplier}
                            <option value="{$supplier.id_supplier}">{$supplier.meta_title}</option>
                        {/foreach}
                        </select>
                    {/if}

                    {if isset($categories)}
                        <input type="text" id="justAnotherInputBox" placeholder="Type to filter" autocomplete="off" style="width: 20%"/>
                        <input type="hidden" id="id_category">
                        <script>
                            var mapping_ajax_link = "{$mapping_ajax_link}";
                            var SampleJSONData = {$categories};
                            var comboTree2;
                            jQuery(document).ready(function($) {
                                comboTree2 = $('#justAnotherInputBox').comboTree({
                                    source : SampleJSONData,
                                    isMultiple: false
                                });
                                $(".comboTreeItemTitle").click(function() {
                                    $("#id_category").val(this.attributes["data-id"].value);
                                })
                            });
                        </script>
                    {/if}

                        <hr>
                        {if isset($feed_fields)}
                        <div class="form-group row">
                            <label class="form-control-label">Supplier Name</label>
                            <div class="col-sm">
                                <input type="text" id="supplier_meta_title" name="supplier_meta_title" class="form-control" value="{$supplier_meta_title}">
                                <small class="form-text">Define supplier's meta_title.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label">Extension</label>
                            <div class="col-sm">
                                <input type="text" id="extension" name="extension" class="form-control" value="{$extension}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label">Header exists</label>
                            <div class="col-sm">
                                <input type="checkbox" id="header_exists_check" name="header_exists_check" class="form-control" onclick="javascript:set_header(this.checked);" {if $header_exists == "true"}checked{/if}>
                                <input type="hidden" id="header_exists" name="header_exists" class="form-control" value="{$header_exists}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label">Delimiter</label>
                            <div class="col-sm">
                                <input type="text" id="delimiter" name="delimiter" class="form-control" value="{$delimiter}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label">Create table</label>
                            <div class="col-sm">
                                <input type="checkbox" id="table_created_check" name="table_created_check" class="form-control" onclick="javascript:check_header(this.checked);" {if $table_created == "true"}checked{/if}>
                                <input type="hidden" id="table_created" name="table_created" class="form-control" value="{$table_created}">
                            </div>
                        </div>
                        {if isset($field_list)}
                        <datalist class="form-control" id="select_field">
                            {foreach from=$field_list item=field_name}
                            <option value="{$field_name['field_name']}"/>
                            {/foreach}
                        </datalist>
                        {else}
                        <datalist class="form-control" id="select_field">    
                            <option value="Manufacturer"/>
                            <option value="Model"/>
                            <option value="Price"/>
                            <option value="Stock level"/>
                        </datalist>
                        {/if}
                        {if count($feed_fields) > 0}
                        <table class="table" style="display: block; overflow: auto;" id="feed_check_table">
                            <thead>
                            <tr>
                            {foreach from=$feed_fields[0] item=field key=key}
                                <th><input type="text" id="defined_fields[{$key}]" name="defined_fields[{$key}]" value="{if isset($defined_fields[$key])}{$defined_fields[$key]}{/if}" class="form-control" list="select_field" onchange="javascript:document.getElementById('table_created_check').checked=false;document.getElementById('table_created').value=false;"></th>
                            {/foreach}
                            </tr>
                            </thead>
                            <tbody>
                            {foreach from=$feed_fields item=feed_field}
                                <tr>
                                {foreach from=$feed_field item=field key=key}
                                    <td>{$field}</td>
                                {/foreach}
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                        <script>
                            function set_header(checked){
                                document.getElementById('header_exists').value=checked;
                                var headers = document.getElementById("feed_check_table").getElementsByTagName("tbody")[0].children[0].children;
                                if(checked){
                                    for(i=0;i<headers.length;i++){
                                        document.getElementById("defined_fields["+i+"]").value = headers[i].textContent;
                                    }
                                } else {
                                    for(i=0;i<headers.length;i++){
                                        document.getElementById("defined_fields["+i+"]").value = "";
                                    }
                                }
                            }
                            function check_header(checked){
                                var check_header_result = false;
                                if(checked){
                                    check_header_result = true;
                                    var headers = document.getElementById("feed_check_table").getElementsByTagName("thead")[0].children[0].children;
                                    for(i=0;i<headers.length;i++){
                                        document.getElementById("defined_fields["+i+"]").style.border="";
                                    }
                                    for(i=0;i<headers.length;i++){
                                        if(document.getElementById("defined_fields["+i+"]").value.trim() == ""){
                                            document.getElementById("defined_fields["+i+"]").style.border="solid 1px red";
                                            check_header_result = false;
                                        } else {
                                            for(j=i+1;j<headers.length;j++){
                                                if(document.getElementById("defined_fields["+i+"]").value.trim() == document.getElementById("defined_fields["+j+"]").value.trim()){
                                                    document.getElementById("defined_fields["+i+"]").style.border="solid 1px red";
                                                    document.getElementById("defined_fields["+j+"]").style.border="solid 1px red";
                                                    check_header_result = false;
                                                }
                                            }
                                        }
                                    }
                                }
                                document.getElementById('table_created_check').checked=check_header_result;
                                document.getElementById('table_created').value=check_header_result;
                            }
                        </script>
                        {/if}
                        <hr>
                        {/if}
                    </div>
                </div>
                <div class="card-footer">
 
                </div>
            </div>
        </div>
    </div>
</form>