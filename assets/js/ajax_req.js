let currentPO = null;
$("#editCatForm").submit(function (e) {
    e.preventDefault();
    var t = $("#editCatForm").serialize();
    $.ajax({
      type: "POST",
      url: "app/action/edit_cat.php",
      data: t,
      success: function (e) {
        alert(e);
      },
    });
  }),
  $(document).on("click", "#catagoryDelete_btn", function (e) {
    e.preventDefault(),
      ($delete_id = $(this).data("id")),
      confirm("Are You sure want to delete this item?") &&
      $.post(
        "app/action/delete_cat.php", {
          delete_id: $delete_id,
          delete_data: "delete_data",
        },
        function (e) {
          "true" == $.trim(e) ?
            (alert("data deleted successfull"), location.reload()) :
            alert("faild to delete data");
        }
      );
  }),
  $("#adMemberForm").submit(function (e) {
    e.preventDefault();
    var t = $("#adMemberForm").serialize();
    $.ajax({
      type: "POST",
      url: "app/action/add_member.php",
      data: t,
      success: function (e) {
        "yes" == $.trim(e) ?
          (alert("member added successfully"), location.reload()) :
          alert(e);
      },
    });
  }),
  $("#editMemberForm").submit(function (e) {
    e.preventDefault();
    var t = $("#editMemberForm").serialize();
    confirm("Are You sure want to edit data") ?
      $.ajax({
        type: "POST",
        url: "app/action/edit_member.php",
        data: t,
        success: function (e) {
          alert(e);
        },
      }) :
      alert("your data are save");
  }),
  $(document).on("click", "#memberDelete_btn", function (e) {
    e.preventDefault(),
      ($delete_id = $(this).data("id")),
      confirm("Are You sure want to delete this item?") &&
      $.post(
        "app/action/delete_member.php", {
          delete_id: $delete_id,
          delete_data: "delete_data",
        },
        function (e) {
          "true" == e
            ?
            (alert("data deleted successfull"), location.reload()) :
            alert(e);
        }
      );
  }),
 
  $("#editSuppliarForm").submit(function (e) {
    e.preventDefault();
    var password = $("#password").val();
    var confirmPassword = $("#confirm_password").val();

    // Validasi konfirmasi password
    if (password !== "" && password !== confirmPassword) {
        alert("Password baru dan konfirmasi password tidak sama!");
        return false; // hentikan submit
    }
    var t = $("#editSuppliarForm").serialize();
    $.ajax({
      type: "POST",
      url: "app/action/edit_suppliar.php",
      data: t,
      success: function (e) {
        alert(e);
      },
    });
  }),
  $(document).on("click", "#suppliarDelete_btn", function (e) {
    e.preventDefault(),
      ($delete_id = $(this).data("id")),
      confirm("Are You sure want to delete this item?") &&
      $.post(
        "app/action/delete_suppliar.php", {
          delete_id: $delete_id,
          delete_data: "delete_data",
        },
        function (e) {
          "true" == e
            ?
            (alert("data deleted successfull"), location.reload()) :
            alert(e);
        }
      );
  }),
   $(document).on("click", "#suppliarActive_btn", function (e) {
  e.preventDefault();
  let delete_id = $(this).data("id");
  let current_status = $(this).data("status"); // ambil status aktif/tidak
  let new_status = current_status == 1 ? 0 : 1; // toggle status

  let confirmMsg = current_status == 1 
    ? "Are you sure you want to suspend this item?" 
    : "Are you sure you want to activate this item?";

  if (confirm(confirmMsg)) {
    $.post(
      "app/action/active_suppliar.php",
      {
        delete_id: delete_id,
        active_data: "active_data",
        new_status: new_status
      },
      function (res) {
        if (res === "true") {
          alert(current_status == 1 ? "Data suspended successfully" : "Data activated successfully");
          location.reload();
        } else {
          alert(res);
        }
      }
    );
  }
}),
$(document).on("click", "#newsDelete_btn", function (e) {
    e.preventDefault(),
      ($delete_id = $(this).data("id")),
      confirm("Are You sure want to delete this item?") &&
      $.post(
        "app/action/delete_news.php", {
          delete_id: $delete_id,
             delete_data: "delete_data",
        },
        function (e) {
          "true" == e
            ?
            (alert("data deleted successfull"), location.reload()) :
            alert(e);
        }
      );
  }),
  $(document).on("click", "#productDelete_btn", function (e) {
    e.preventDefault(),
      ($delete_id = $(this).data("id")),
      confirm("Are You sure want to delete this item?") &&
      $.post(
        "app/action/delete_product.php", {
          delete_id: $delete_id,
          delete_data: "delete_data",
        },
        function (e) {
          "true" == e
            ?
            (alert("data deleted successfull"), location.reload()) :
            alert(e);
        }
      );
  }),
  $(document).on("click", "#stockManagementDelete_btn", function (e) {
    e.preventDefault(),
      ($delete_id = $(this).data("id")),
      confirm("Are You sure want to delete this item?") &&
      $.post(
        "app/action/delete_stock_management.php", {
          delete_id: $delete_id,
          delete_data: "delete_data",
        },
        function (e) {
          "true" == e
            ?
            (alert("data deleted successfull"), location.reload()) :
            alert(e);
        }
      );
  }),
  $(document).on("click", "#ex_catagoryDelete_btn", function (e) {
    e.preventDefault(),
      ($delete_id = $(this).data("id")),
      confirm("Are You sure want to delete this item?") &&
      $.post(
        "app/action/delete_exCaragroy.php", {
          delete_id: $delete_id,
          delete_data: "delete_data",
        },
        function (e) {
          "true" == e
            ?
            (alert("data deleted successfull"), location.reload()) :
            alert(e);
        }
      );
  }),
  $(document).on("click", "#expenseDelete_btn", function (e) {
    e.preventDefault(),
      ($delete_id = $(this).data("id")),
      confirm("Are You sure want to delete this item?") &&
      $.post(
        "app/action/delete_expense.php", {
          delete_id: $delete_id,
          delete_data: "delete_data",
        },
        function (e) {
          "true" == e
            ?
            (alert("data deleted successfull"), location.reload()) :
            alert(e);
        }
      );
  }),
  $("#editProduct").submit(function (e) {
    e.preventDefault();
    var t = $("#editProduct").serialize();
    confirm("Are You sure want to edit data") ?
      $.ajax({
        type: "POST",
        url: "app/action/edit_product.php",
        data: t,
        success: function (e) {
          alert(e);
        },
      }) :
      alert("your data are save");
  }),
  $("#addexpenseCat").submit(function (e) {
    e.preventDefault();
    var t = $("#addexpenseCat").serialize();
    $.ajax({
      type: "POST",
      url: "app/action/addexpense_cat.php",
      data: t,
      success: function (e) {
        "yes" == $.trim(e) ?
          (alert("Expense catagory added successfylly"), location.reload()) :
          alert(e);
      },
    });
  }),
  $("#addExpenseForm").submit(function (e) {
    e.preventDefault();
    var t = $("#addExpenseForm").serialize();
    $.ajax({
      type: "POST",
      url: "app/action/add_expense.php",
      data: t,
      success: function (e) {
        alert(e);
      },
    });
  }),
  $("#editExpenseForm").submit(function (e) {
    e.preventDefault();
    var t = $("#editExpenseForm").serialize();
    $.ajax({
      type: "POST",
      url: "app/action/edit_expense.php",
      data: t,
      success: function (e) {
        alert(e);
      },
    });
  }),
  $("#adstaffForm").submit(function (e) {
    e.preventDefault();
    var t = $("#adstaffForm").serialize();
    $.ajax({
      type: "POST",
      url: "app/action/add_staff.php",
      data: t,
      success: function (e) {
        "yes" == $.trim(e) ?
          (alert("Staff added successfully"), $("#adstaffForm")[0].reset()) :
          alert(e);
      },
    });
  }),
  $("#editstaffForm").submit(function (e) {
    e.preventDefault();
    var t = $("#editstaffForm").serialize();
    $.ajax({
      type: "POST",
      url: "app/action/edit_staff.php",
      data: t,
      success: function (e) {
        alert(e);
      },
    });
  }),
  $("#update_userForm").submit(function (e) {
    e.preventDefault();
    var t = $("#update_userForm").serialize();
    $.ajax({
      type: "POST",
      url: "app/action/edit_update.php",
      data: t,
      success: function (e) {
        "yes" == $.trim(e) ?
          (window.location.href = "app/action/logout.php") :
          alert(e);
      },
    });
  }),
  $(document).on("click", "#staff_delete_btn", function (e) {
    e.preventDefault(),
      ($delete_id = $(this).data("id")),
      confirm("Are You sure want to delete this item?") &&
      $.post(
        "app/action/delete_staff.php", {
          delete_id: $delete_id,
          delete_data: "delete_data",
        },
        function (e) {
          "true" == $.trim(e) ?
            (alert("data deleted successfull"), location.reload()) :
            alert("faild to delete data");
        }
      );
  }),
  $("#sendSmsForm").submit(function (e) {
    e.preventDefault();
    var t = $("#sms_number").val(),
      a = $("#sms_message").val(),
      d = $("#sendSmsForm").serialize();
    "" != t && "" != a ?
      $.ajax({
        type: "POST",
        url: "app/action/send_sms.php",
        data: d,
        success: function (e) {
          alert(e);
        },
      }) :
      alert("All field must be filled out");
  }),
  $("#addFactoryProduct").submit(function (e) {
    e.preventDefault();
    var t = $("#addFactoryProduct").serialize();
    $.ajax({
      type: "POST",
      url: "app/action/add_factoryProduct.php",
      data: t,
      success: function (e) {
        alert(e);
      },
    });
  }),
  $("#editFactoryProduct").submit(function (e) {
    e.preventDefault();
    var t = $("#editFactoryProduct").serialize();
    confirm("Are You sure want to edit data") ?
      $.ajax({
        type: "POST",
        url: "app/action/edit_factoryProduct.php",
        data: t,
        success: function (e) {
          alert(e);
        },
      }) :
      alert("your data are save");
  }),
  $(document).on("click", "#factoryProductDelete_btn", function (e) {
    e.preventDefault(),
      ($delete_id = $(this).data("id")),
      confirm("Are You sure want to delete this item?") &&
      $.post(
        "app/action/delete_factoryProduct.php", {
          delete_id: $delete_id,
          delete_data: "delete_data",
        },
        function (e) {
          "true" == $.trim(e) ?
            (alert("data deleted successfull"), location.reload()) :
            alert("faild to delete data");
        }
      );
  }),
  $("#approveForm").on("submit", function (e) {
    e.preventDefault();

    // tampilkan konfirmasi SweetAlert
    Swal.fire({
        title: "Approve Purchase Order?",
      html: `
  <div style="text-align:left; font-size:14px; line-height:1.6; font-family:Arial, sans-serif;">
    <table style="width:100%; border-collapse:collapse;">
      <tr>
        <td style="font-weight:bold; padding:4px 8px; width:120px;">Invoice</td>
        <td style="padding:4px 8px;">${currentPO?.invoice_number ?? "-"}</td>
      </tr>
      <tr>
        <td style="font-weight:bold; padding:4px 8px;">Nama Pemesan</td>
        <td style="padding:4px 8px;">${currentPO?.suppliar_name ?? "-"}</td>
      </tr>
      <tr>
        <td style="font-weight:bold; padding:4px 8px;">Total</td>
        <td style="padding:4px 8px;">Rp ${parseInt(currentPO?.total_amount).toLocaleString('de-DE') ?? "0"}</td>
      </tr>
    </table>

    <div style="margin-top:12px; font-weight:bold;">Produk Dipesan:</div>
    <ul style="margin:6px 0 0 20px; padding:0;">
      ${
        currentPO?.items_summary
          ? currentPO.items_summary
              .split(",")
              .map(item => `<li style="margin:3px 0;">${item.trim()}</li>`)
              .join("")
          : "<li>-</li>"
      }
    </ul>
  </div>
`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Ya, Approve",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "app/action/approve_purchase_order.php",
                data: $("#approveForm").serialize(),
                success: function (response) {
                    if (response.status === true) {
                        Swal.fire("Berhasil!", "Purchase Order berhasil di-approve.", "success");
                        $("#approveModal").modal("hide");
                        $("#approveForm")[0].reset();
                        $("#purchaseOrderTable").DataTable().ajax.reload();
                    } else {
                        Swal.fire("Gagal!", response.message, "error");
                    }
                },
                error: function () {
                    Swal.fire("Error!", "Terjadi kesalahan saat menghubungi server.", "error");
                },
            });
        }
    });
}),
  $(document).on("click", ".btn-approve", function () {
    const orderId = $(this).data("id");
    const suppliarId = $(this).data("suppliar-id");
    if (confirm("Apakah Anda yakin ingin menyetujui pesanan ini?")) {
      $.ajax({
        type: "POST",
        url: "app/action/approve_purchase_order.php",
        data: {
          order_id: orderId,
          suppliar_id: suppliarId
        },
        success: function (response) {
          if (response.status === true) {
            alert("Purchase Order berhasil di-approve.");
            $("#purchaseOrderTable").DataTable().ajax.reload(); // reload datatable
          } else {
            alert("Gagal meng-approve: " + response.message);
          }
        },
        error: function () {
          alert("Terjadi kesalahan saat menghubungi server.");
        },
      });
    }
  }),
   $(document).on('click', '.btn-open-form', function() {
    let poId = $(this).data('id');
    let invoice = $(this).data('invoice');
    let obj = $(this).data('object');
     if (typeof obj === "string") {
        try {
            obj = JSON.parse(obj);
        } catch (e) {
            console.error("Data-object bukan JSON valid:", obj);
            obj = {};
        }
    }

    currentPO = obj; // simpan object ke global variable
    console.log("Current PO:", currentPO); // cek di console
    $('#approveModal #approve_po_id').val(poId);
    $('#approveModal #invoice_number').text(invoice);
    $('#approveModal').modal('show');
}),
  $(document).on("click", ".btn-reject", function () {
    const orderId = $(this).data('id');
    if (confirm("Apakah Anda yakin ingin menolak pesanan ini?")) {
      $.ajax({
        url: 'app/action/reject_purchase_order.php',
        type: 'POST',
        data: {
          order_id: orderId
        },
        success: function (response) {
          if (response.status) {
            alert(response.message);
            $('#purchaseOrderTable').DataTable().ajax.reload();
          } else {
            alert("Gagal menolak: " + response.message);
          }
        },
        error: function () {
          alert("Terjadi kesalahan jaringan.");
        }
      });
    }
  }), $(document).on('click', '.stock-apply-add', function() {
    const id = $(this).data('id');
    const input = $('.stock-input[data-id="' + id + '"]');
    const val = parseInt(input.val()) || 0;

    if (val <= 0) {
        alert('Masukkan nilai lebih besar dari 0.');
        return;
    }

    $.ajax({
        url: 'app/action/edit_stock_management.php', // ganti dengan path PHP kamu
        method: 'POST',
        data: { id: id, change: val },
        success: function(res) {
      if (res.status) {
        alert('Success: ' + res.message);
        input.val(0);
          $('#stockManagementTable').DataTable().ajax.reload();
        // reload table or update UI here if needed
      } else {
        alert('Error: ' + res.message);
      }
    },
    error: function() {
      alert('Terjadi kesalahan pada server.');
    }
    });
}), $(document).on('click', '.stock-apply-reduce', function() {
    const id = $(this).data('id');
    const input = $('.stock-input[data-id="' + id + '"]');
    const val = parseInt(input.val()) || 0;

    if (val <= 0) {
        alert('Masukkan nilai lebih besar dari 0.');
        return;
    }

    $.ajax({
        url: 'app/action/edit_stock_management.php', // ganti dengan path PHP kamu
        method: 'POST',
        data: { id: id, change: -val },
        success: function(res) {
      if (res.status) {
        alert('Success: ' + res.message);
        input.val(0);
          $('#stockManagementTable').DataTable().ajax.reload();
        // reload table or update UI here if needed
      } else {
        alert('Error: ' + res.message);
      }
    },
    error: function() {
      alert('Terjadi kesalahan pada server.');
    }
    });
}),$(document).on('click', '.newsTogglePublish_btn', function() {
    var id = $(this).data('id');
    var action = $(this).data('action');

    // Pesan konfirmasi dinamis
    var confirmMsg = (action === 'publish') 
        ? "Apakah kamu yakin ingin mempublish berita ini?" 
        : "Apakah kamu yakin ingin menyembunyikan berita ini?";

    if (!confirm(confirmMsg)) {
        return; // batal
    }

    $.ajax({
        url: 'app/action/toogle_isactive.php',
        type: 'POST',
        data: { id: id, action: action },
        success: function(response) {
            var res = JSON.parse(response);
            if (res.status === 'success') {
                alert('Status berhasil diupdate!');
                $('#newsTable').DataTable().ajax.reload(null, false); 
            } else {
                alert('Gagal update status');
            }
        },
        error: function() {
            alert('Terjadi kesalahan pada server.');
        }
    });
});
