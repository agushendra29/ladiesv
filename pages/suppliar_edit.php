<!-- Content Wrapper. Contains page content  -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->

  <!-- /.content-header -->
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- /.card-header -->
      <hr>
      <div class="row">
        <div class="col-md-8 col-lg-10 mt-5 mb-5">
          <div class="">
            <?php 
        if (isset($_GET['edit_id'])) {
           $edit_id = $_GET['edit_id'];
           $stmt = $pdo->prepare("
    SELECT s.*, u.role_id
    FROM suppliar s
    LEFT JOIN user u ON s.id = u.suppliar_id
    WHERE s.id = ?
");
$stmt->execute([$edit_id]);
$data = $stmt->fetch(PDO::FETCH_OBJ);

            if ($data) {
              ?>
            <div class="card-header">
              <h6 class="float-left"><b>Distributor id</b> : #<?=$data->serial_code;?></h6>
            </div>
            <div class="card-body">

              <form id="editSuppliarForm">
                <input type="hidden" name="id" value="<?=$edit_id;?>">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">Nama *:</label>
                      <input type="text" class="form-control" id="name" name="name" value="<?=$data->name;?>" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nik">NIK (ID Number) *</label>
                      <input type="text" class="form-control" id="nik" name="nik" value="<?=$data->nik;?>" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="rekening">No Rekening *</label>
                      <input type="number" class="form-control" id="rekening" name="rekening"
                        value="<?=$data->rekening;?>" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="bank">Bank *</label>
                      <input type="text" class="form-control" id="bank" name="bank" value="<?=$data->bank;?>" required>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="address">Alamat *</label>
                      <textarea rows="3" class="form-control" id="address" name="address"
                        required><?=$data->address;?></textarea>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="contact">Nomor Kontak *:</label>
                      <input type="text" class="form-control" id="contact" name="contact" value="<?=$data->con_num;?>"
                        required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="email">Email *</label>
                      <input type="email" class="form-control" id="email" name="email" value="<?=$data->email;?>"
                        required>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="role">Role *</label>
                      <select class="form-control" id="role" name="role" required>
                        <option value="">-- Select Role --</option>
                        <option value="1" <?=$data->role_id == 1 ? 'selected' : ''?>>Head Officer</option>
                        <option value="2" <?=$data->role_id == 2 ? 'selected' : ''?>>Head Distributor</option>
                        <option value="3" <?=$data->role_id == 3 ? 'selected' : ''?>>Distributor</option>
                        <option value="4" <?=$data->role_id == 4 ? 'selected' : ''?>>Agen</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="password">Password baru</label>
                      <input type="password" class="form-control" id="password" name="password">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="old_password">Password lama</label>
                      <input type="password" class="form-control" id="old_password" name="old_password">
                    </div>
                  </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block rounded-0">Update data</button>
              </form>


              <?php 
            }else{
          header("location:index.php?page=error_page");
        }

        }
       ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.card-body -->
    <!-- /.row -->
</div>
<!--/. container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper