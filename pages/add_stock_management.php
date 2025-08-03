<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="m-0 text-dark">
                        <!-- Dashboard v2 -->
                    </h1>
                </div><!-- /.col -->
                <div class="col-md-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- .row -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><b>Tambah produk</b></h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary alert-dismissible fade show addStockManagementError-area" role="alert">
                        <span id="addStockManagementError"></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addStockManagement">
                        
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="p_product">Produk * :</label>

                                    <div class="form-group">
                                        <select name="p_product" id="p_product" class="form-control select2">
                                            <option disabled selected>Select product</option>
                                            <?php 
                                    $all_catgory = $obj->all('products');
                                    foreach ($all_catgory as $catagory) {
                                      ?>
                                            <option value="<?=$catagory->id;?>"><?=$catagory->product_name;?>
                                            </option>
                                            <?php 
                                    }
                                   ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                             <div class="form-group">
                            <label for="stock_quantity">Harga* :</label>
                            <input type="number" class="form-control" id="stock_quantity" placeholder="Jumlah Stock" name="stock_quantity">
                          </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="p_suppliar">Distributor/Agen * :</label>
                                    <div class="form-group">
                                        <select name="p_suppliar" id="p_suppliar" class="form-control select2">
                                            <option disabled selected>Select Distributor</option>
                                            <?php 
            $all_catgory = $obj->all('suppliar');
            foreach ($all_catgory as $catagory) {
                // Konversi role_id menjadi teks
                $roleName = '';
                if ($catagory->role_id == 3) {
                    $roleName = 'Distributor';
                } elseif ($catagory->role_id == 4) {
                    $roleName = 'Agen';
                } elseif ($catagory->role_id == 2) {
                    $roleName = 'Head Distributor';
                }else {
                    $roleName = 'HO';
                }
        ?>
                                            <option value="<?= $catagory->id; ?>">
                                                <?= $roleName; ?> - <?= $catagory->name; ?> 
                                            </option>
                                            <?php 
            }
        ?>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                       </div>

                       
                        
                </div>
            </div>
            <div class="row text-center  buttons">
                <div class="col-md-6 offset-md-3 col-lg-6 offset-lg-3">
                    <input type="reset" title="Reset form" class="btn btn-danger pl-5 pr-5 rounded-0">
                    <button type="submit" title="Save data" class="btn btn-primary pl-5 pr-5  rounded-0">Submit</button>
                </div>
            </div>
            </form>
        </div>

</div>

<!-- /.row -->
</div>
<!--/. container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper