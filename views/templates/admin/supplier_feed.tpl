<form action="" method="POST" class="form form-horizontal">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <h3 class="card-header">
                    <i class="material-icons">settings</i> Supplier Feeds
                </h3>
                <div class="card-block row">
                    <div class="card-text">
                    {if $cronjobs}
                        <table class="table" style="width: 100%;">
                            <thead>
                                <th style="width: 10%;">Supplier Name</th>
                                <th style="width: 50%;">Url</th>
                                <th style="width: 15%;">Interval</th>
                                <th style="width: 15%;">Last execution</th>
                                <th style="width: 10%;">Action</th>
                            </thead>
                            <tbody>
                            {foreach from=$cronjobs item=cronjob key=key}
                                <tr>
                                    <td>{$cronjob.meta_title}</td>
                                    <td>{$cronjob.url}</td>
                                    <td>{$cronjob.interval}</td>
                                    <td>{$cronjob.last_execution}</td>
                                    <td><a href="{$self_link}&delete={$cronjob.id_supplier}"><i class="material-icons">delete</i></a></td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                        <hr>
                    {/if}
                        <div class="form-group row">
                            <label class="form-control-label">Url</label>
                            <div class="col-sm">
                                <input type="text" id="cron_url" name="cron_url" required="required" class="form-control" size="64" value="{if isset($cron_url)}{$cron_url}{/if}">
                                <small class="form-text">Input a new cron job Url.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label">Username</label>
                            <div class="col-sm">
                                <input type="text" id="username" name="username" class="form-control" size="64" value="{if isset($username)}{$username}{/if}">
                                <small class="form-text">Input username.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label">Password</label>
                            <div class="col-sm">
                                <input type="text" id="password" name="password" class="form-control" size="64" value="{if isset($password)}{$password}{/if}">
                                <small class="form-text">Input password.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-control-label">Interval</label>
                            <div class="col-sm">
                                <input type="text" id="cron_interval" name="cron_interval" class="form-control" size="20" value="{if isset($cron_interval)}{$cron_interval}{/if}">
                                <small class="form-text">Input a new cron job interval.</small>
                            </div>
                        </div>
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
                    <div class="d-flex justify-content-end">
                        <button id="check" name="check" type="submit" class="btn btn-primary">Check</button>
                        <button id="submit" name="submit" type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>