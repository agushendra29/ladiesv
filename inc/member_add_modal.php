 <!-- The Modal for add new form -->
 <div class="modal fade bd-example-modal-xl myModal" id="myModal">
   <div class="modal-dialog modal-xl">
     <div class="modal-content">

       <!-- Modal Header -->
       <div class="modal-header bg-primary">
         <h4 class="modal-title">Tambah Agen</h4>
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
         <form id="adMemberForm">
           <div class="row">
             <div class="col-md-6 col-lg-6">
               <div class="form-group">
                 <label for="sup_name">Name *:</label>
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
                 <label for="sup_contact">Contact number :</label>
                 <input type="text" class="form-control" id="sup_contact" placeholder="Contact member"
                   name="sup_contact">
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
                 <label for="sup_parent_id">Pilih Parent (Distributor/Head)</label>
                 <div class="row col-md-12">
                  <select name="p_supliar" id="p_supliar" class="form-control select2" style="width:100%;">
                        <option selected disabled>Select a Supplier </option>
                        <?php 
                          $all_supplier = $obj->allCondition('suppliar', 'role_id = ? OR role_id = ?', [2,3]);
                          foreach ($all_supplier as $supplier) {
                            ?>
                             <option value="<?=$supplier->id?>"><?=$supplier->name?></option>

                            <?php 
                          }
                         ?>
                      </select>
                        </div>
               </div>
             </div>
             <div class="col-md-6">
               <div class="form-group">
                 <label for="sup_password">Password</label>
                 <input type="password" class="form-control" id="sup_password" name="sup_password" required>
               </div>
             </div>
             <div class="col-md-12 col-lg-12">
               <div class="form-group">
                 <label for="supaddress">Address:</label>
                 <textarea rows="3" class="form-control" placeholder="Member complect Address" id="supaddress"
                   name="supaddress"></textarea>
               </div>
             </div>
           </div>
           <button type="submit" class="btn btn-primary btn-block rounded-0">tambah agen</button>
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