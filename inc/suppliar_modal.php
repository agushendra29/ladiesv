 <!-- The Modal for add new form -->
 <div class="modal fade bd-example-modal-xl suppliarModal" id="suppliarModal">
   <div class="modal-dialog modal-xl">
     <div class="modal-content">

       <!-- Modal Header -->
       <div class="modal-header bg-primary">
         <h4 class="modal-title">Tambah Anggota</h4>
         <button type="button" class="close" data-dismiss="modal">&times;</button>
       </div>

       <!-- Modal body -->
       <div class="modal-body">
         <div class="alert alert-primary alert-dismissible fade show memberFormError" role="alert">
           <span id="cuppliarFormError"></span>
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <form id="adsuppliarForm">
           <div class="row">
             <div class="col-md-6 col-lg-6">
               <div class="form-group">
                 <label for="sup_name">Nama Lengkap *:</label>
                 <input type="text" class="form-control" id="sup_name" placeholder="Member name" name="sup_name">
               </div>
             </div>
             <div class="col-md-6">
               <div class="form-group">
                 <label for="sup_nik">NIK (ID Number)</label>
                 <input type="text" class="form-control" id="sup_nik" name="sup_nik" placeholder="Nomor Kependudukan">
               </div>
             </div>
             <div class="col-md-6">
               <div class="form-group">
                 <label for="sup_rekening">No Rekening (Rekening)</label>
                 <input type="number" class="form-control" id="sup_rekening" name="sup_rekening"
                   placeholder="No Rekening">
               </div>
             </div>

             <div class="col-md-6">
               <div class="form-group">
                 <label for="sup_bank">Nama Bank</label>
                 <input type="text" class="form-control" id="sup_bank" name="sup_bank" placeholder="Nama Bank">
               </div>

             </div>
             <div class="col-md-6 col-lg-6">
               <div class="form-group">
                 <label for="sup_contact">No Kontak :</label>
                 <input type="text" class="form-control" id="sup_contact" placeholder="Contact member"
                   name="sup_contact">
               </div>
             </div>
               <div class="col-md-6 col-lg-6">
               <div class="form-group">
                 <label for="sup_contact">Tanggal Lahir :</label>
                <input type="text" class="form-control datepicker" id="birth_date"  aria-describedby="addon-wrapping" name="birth_date" required>
               </div>
             </div>
             <div class="col-md-6 col-lg-6">
               <div class="form-group">
                 <label for="sup_email">Email:</label>
                 <input type="email" class="form-control" id="sup_email" placeholder="Email optional" name="sup_email">
               </div>
             </div>
              <div class="col-md-6">
              <div class="form-group">
                <label for="sup_role">Level Anggota</label>
                <select class="form-control" id="sup_role" name="sup_role" required>
                  <option value="">-- Pilh Level Anggota --</option>
                  <option value="2">Head Distributor</option>
                  <option value="3">Distributor</option>
                  <option value="4">Agen</option>
                  <option value="5">Reseller</option>
                </select>
              </div>
            </div>
             <div class="col-md-12 col-lg-12">
               <div class="form-group">
                 <label for="supaddress">Alamat (Diisi Sesuai KTP):</label>
                 <textarea rows="3" class="form-control" placeholder="Alamat diisi sesuai ktp" id="supaddressktp"
                   name="supaddressktp"></textarea>
               </div>
             </div>
              <div class="col-md-12 col-lg-12">
               <div class="form-group">
                 <label for="supaddress">Alamat Pengiriman:</label>
                 <textarea rows="3" class="form-control" placeholder="Alamat diisi sesuai tempat domisili" id="supaddress"
                   name="supaddress"></textarea>
               </div>
             </div>
           </div>
           <button type="submit" class="btn btn-primary btn-block rounded-0">Tambah Anggota</button>
         </form>
         <!-- </div> -->
       </div>
       <!-- Modal footer -->
       <!--   <div class="modal-footer">
                        <button type="button" class="btn btn-danger rounded-0 btn-sm" data-dismiss="modal">Close</button>
                      </div> -->
     </div>
   </div>
 </div>