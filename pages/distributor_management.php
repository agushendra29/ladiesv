<div style="max-width:1200px; margin:auto; margin-top:50px; padding:0 12px;">
  <h2 style="font-size:28px; font-weight:700; color:#1e293b; margin-bottom:32px; text-align:center;">
    📊 Manajemen Distributor
  </h2>

  <div class="form-row" style="display:flex; flex-wrap:wrap; gap:20px; margin-bottom:20px;">
    <div class="form-col" style="flex:1; min-width:250px;">
      <label for="head_distributor" style="font-weight:600;">Pilih Head Distributor</label>
      <select id="head_distributor" style="width:100%; padding:12px; border:1.8px solid #cbd5e1; border-radius:12px;">
        <option value="">-- Pilih Head Distributor --</option>
        <?php
        $heads = $obj->allCondition('suppliar',  'role_id = ?', [2]);
        foreach ($heads as $head) {
            echo "<option value='{$head->id}' data-code='{$head->suppliar_code}'>{$head->name}</option>";
        }
        ?>
      </select>
    </div>

    <div class="form-col" style="flex:1; min-width:250px;">
      <label for="child_distributor" style="font-weight:600;">Pilih Child Distributor</label>
      <select id="child_distributor" style="width:100%; padding:12px; border:1.8px solid #cbd5e1; border-radius:12px;">
        <option value="">-- Pilih Child Distributor --</option>
        <?php
        $childs = $obj->allCondition('suppliar',  'role_id = ? AND parent_id IS NULL', [3]);
        foreach ($childs as $child) {
            echo "<option value='{$child->id}' data-code='{$child->suppliar_code}'>{$child->name}</option>";
        }
        ?>
      </select>
    </div>

    <div class="form-col" style="display:flex; align-items:flex-end; min-width:120px;">
      <button type="button" id="addChildBtn" 
              style="padding:12px 24px; background-color:#2563eb; color:white; border:none; border-radius:12px; cursor:pointer; font-weight:600; width:100%;">
        Add
      </button>
    </div>
  </div>

  <div style="overflow-x:auto;">
    <table id="childDistributorTable" style="width:100%; border-collapse: collapse; font-size:14px; min-width:500px;">
      <thead>
        <tr>
          <th style="border:1px solid #cbd5e1; padding:8px;">Child Distributor</th>
          <th style="border:1px solid #cbd5e1; padding:8px;">Kode</th>
          <th style="border:1px solid #cbd5e1; padding:8px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <!-- Row akan ditambahkan lewat JS -->
      </tbody>
    </table>
  </div>

  <button type="button" id="saveBtn" 
          style="margin-top:20px; padding:12px 32px; background-color:#16a34a; color:white; border:none; border-radius:12px; font-weight:600; cursor:pointer; width:100%; max-width:200px; display:block; margin-left:auto; margin-right:auto;">
    Simpan Semua
  </button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    var mappings = {}; 
    var deletedChilds = []; // untuk simpan yang dihapus

    // Saat pilih head
    $("#head_distributor").change(function(){
        var headId = $(this).val();
        if(!headId){
            $("#childDistributorTable tbody").html('');
            mappings = {};
            deletedChilds = [];
            return;
        }

        $.ajax({
            url:'app/action/get_distributor_data.php',
            type:'POST',
            data:{head_id:headId},
            dataType:'json',
            success:function(res){
                var tbody = '';
                mappings = {};
                deletedChilds = [];
                res.forEach(function(child){
                    mappings[headId] = mappings[headId] || {};
                    mappings[headId][child.id] = {childText:child.name, headText:child.head_name};

                    tbody += `<tr data-head="${headId}" data-child="${child.id}">
                        <td style="border:1px solid #cbd5e1; padding:8px;">${child.name}</td>
                        <td style="border:1px solid #cbd5e1; padding:8px;">${child.suppliar_code}</td>
                        <td style="border:1px solid #cbd5e1; padding:8px;">
                            <button type="button" class="removeChild" 
                                    style="padding:4px 8px; background:#ef4444; color:white; border:none; border-radius:6px; cursor:pointer;">
                                    Hapus
                            </button>
                        </td>
                    </tr>`;
                });
                $("#childDistributorTable tbody").html(tbody);
            },
            error:function(xhr, status, error){
                alert("Gagal mengambil data: " + error);
            }
        });
    });

    // Add child baru
    $("#addChildBtn").click(function(){
        var headId = $("#head_distributor").val();
        var headText = $("#head_distributor option:selected").text();
        var childId = $("#child_distributor").val();
        var childText = $("#child_distributor option:selected").text();
        var childCode = $("#child_distributor option:selected").data('code');

        if(!headId || !childId){
            alert("Pilih Head dan Child Distributor terlebih dahulu!");
            return;
        }

        if(mappings[headId] && mappings[headId][childId]){
            alert("Child ini sudah ditambahkan ke Head yang sama!");
            return;
        }

        if(!mappings[headId]) mappings[headId] = {};
        mappings[headId][childId] = {childText:childText, headText:headText};

        var row = `<tr data-head="${headId}" data-child="${childId}">
            <td style="border:1px solid #cbd5e1; padding:8px;">${childText}</td>
            <td style="border:1px solid #cbd5e1; padding:8px;">${childCode}</td>
            <td style="border:1px solid #cbd5e1; padding:8px;">
              <button type="button" class="removeChild" 
                      style="padding:4px 8px; background:#ef4444; color:white; border:none; border-radius:6px; cursor:pointer;">
                      Hapus
              </button>
            </td>
        </tr>`;
        $("#childDistributorTable tbody").append(row);
        $("#child_distributor").val('');
    });

    // Hapus child
    $(document).on('click', '.removeChild', function(){
        var row = $(this).closest('tr');
        var headId = row.data('head');
        var childId = row.data('child');

        delete mappings[headId][childId];
        if(Object.keys(mappings[headId]).length === 0) delete mappings[headId];

        deletedChilds.push(childId);
        row.remove();
    });

    // Simpan semua
    $("#saveBtn").click(function(){
        $.ajax({
            url:'app/action/distributor_management_data.php',
            type:'POST',
            data:{mappings:mappings, deleted:deletedChilds},
            success:function(res){
                alert(res);
                location.reload();
            },
            error:function(xhr, status, error){
                alert("Terjadi kesalahan: " + error);
            }
        });
    });
});
</script>
