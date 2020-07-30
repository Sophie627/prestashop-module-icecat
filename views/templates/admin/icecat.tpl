<form action="" method="POST" class="form form-horizontal">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <h3 class="card-header">
                    <i class="material-icons">settings</i> Icecat settings
                </h3>
                <div class="card-block row">
                    <div class="card-text">
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
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <button id="submit" name="submit" type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>