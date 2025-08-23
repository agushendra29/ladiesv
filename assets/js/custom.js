function editAddNewRow() {
    $.ajax({
        url: "app/ajax/addNewRow.php",
        method: "POST",
        data: {
            getOrderItem: 1
        },
        success: function (a) {
            $("#editInvoiceItem").append(a), $(".select2").select2();
            var t = 0;
            $(".si_number").each((function () {
                $(this).html(++t)
            }))
        }
    })
}
$("#empTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/member_data.php"
    },
    columns: [{
        data: "name"
    }, {
        data: "address"
    }, {
        data: "con_num"
    }, {
        data: "action"
    }]
}), $("#suppliarTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/suppliar_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "name"
    }, {
        data: "address"
    }, {
        data: "con_num"
    }, {
        data: "role_id"
    }, {
        data: "action"
    }]
}), $("#staffTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/staff_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "name"
    }, {
        data: "designation"
    }, {
        data: "con_no"
    }, {
        data: "email"
    }, {
        data: "address"
    }, {
        data: "action"
    }]
}), $("#addCatForm").submit((function (a) {
    a.preventDefault();
    var t = $("#addCatForm").serialize();
    $.ajax({
        type: "POST",
        url: "app/action/add_catagory.php",
        data: t,
        success: function (a) {
            "yes" == $.trim(a) && (alert("Catagory added successfull"), location.reload())
        }
    })
})), $("#catagoryTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/catagory_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "name"
    }, {
        data: "description"
    }, {
        data: "action"
    }]
}), $("#ex_catagoryTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/ex_catagory_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "name"
    }, {
        data: "description"
    }, {
        data: "action"
    }]
}), $("#addProduct").submit(function (e) {
    e.preventDefault();

    var productName = $("#product_name").val().trim(),
        sell_price_hd = $("#sell_price_hd").val().trim(),
        sell_price_d = $("#sell_price_d").val().trim();
    sell_price_a = $("#sell_price_d").val().trim();
    sell_price_r = $("#sell_price_d").val().trim();

    if (productName && sell_price_hd && sell_price_d && sell_price_a && sell_price_r) {
        var formData = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "app/action/add_product.php",
            data: formData,
            success: function (response) {
                $(".addProductError-area").show();

                if ($.trim(response).toLowerCase() === "yes") {
                    $("#addProductError").html("✅ Product added successfully");
                    $("#addProductError").css({
                        "background": "#d4edda",
                        "color": "#155724"
                    });
                    $("#addProduct")[0].reset();
                } else {
                    $("#addProductError").html("❌ " + response);
                    $("#addProductError").css({
                        "background": "#f8d7da",
                        "color": "#721c24"
                    });
                }
            },
            error: function () {
                $(".addProductError-area").show();
                $("#addProductError").html("⚠️ Server error, please try again");
                $("#addProductError").css({
                    "background": "#fff3cd",
                    "color": "#856404"
                });
            }
        });
    } else {
        $(".addProductError-area").show();
        $("#addProductError").html("⚠️ Please fill out all required fields");
        $("#addProductError").css({
            "background": "#fff3cd",
            "color": "#856404"
        });
    }
}), $("#addStockManagement").submit((function (a) {
    a.preventDefault();
    var t = $("#p_product").val(),
        e = $("#p_suppliar").val(),
        d = $("#stock_quantity").val();
    if ("" != t && "" != e && null != d) {
         if (!confirm("Are you sure you want to add/update this stock?")) {
        return; // jika user cancel, hentikan submit
    }
        var r = $("#addStockManagement").serialize();
        $.ajax({
            type: "POST",
            url: "app/action/add_stock_management.php",
            data: r,
            success: function (a) {
                "yes" == $.trim(a) ? ($(".addStockManagementError-area").show(), $("#addStockManagementError").html("Product added successfull"), $("#addStockManagement")[0].reset()) : ($(".addStockManagementError-area").show(), $("#addStockManagementError").html(a))
            }
        })
    } else $(".addStockManagementError-area").show(), $("#addStockManagementError").html("pleasse filled out all required filled")
})), $(document).ready(function () {
  $("#purchaseOrderForm").submit(function (e) {
    e.preventDefault();

    // ambil semua produk & qty
    var products  = $("select[name='product_id[]']").map(function(){ return $(this).val(); }).get();
    var quantities = $("input[name='quantity[]']").map(function(){ return $(this).val(); }).get();

    // cek minimal 1 produk valid
    if (products.length === 0 || products.includes(null) || products.includes("")) {
      $(".purchaseOrderError-area").show();
      $("#purchaseOrderError").html("Silakan pilih minimal satu produk.");
      return;
    }

    if (quantities.some(qty => qty === "" || parseInt(qty) <= 0)) {
      $(".purchaseOrderError-area").show();
      $("#purchaseOrderError").html("Silakan masukkan kuantitas yang valid.");
      return;
    }

    var formData = $(this).serialize();

    $.ajax({
      type: "POST",
      url: "app/action/add_purchase_order.php", // ganti endpoint sesuai form baru
      data: formData,
      beforeSend: function () {
        $(".purchaseOrderError-area").hide();
        $("#purchaseOrderForm button[type=submit]").prop("disabled", true).text("Loading...");
      },
      success: function (res) {
        try {
          let data = JSON.parse(res);

          if (data.status === "success") {
            $(".purchaseOrderError-area").removeClass("alert-danger").addClass("alert-success").show();
            $("#purchaseOrderError").html(data.message || "Pemesanan berhasil ditambahkan ✅");

            // reset form
            $("#purchaseOrderForm")[0].reset();
            $("#grandTotal").val("");
            $("#productWrapper .product-row:gt(0)").remove(); // hapus row selain pertama
            $(".select2").val(null).trigger("change");
          } else {
            $(".purchaseOrderError-area").removeClass("alert-success").addClass("alert-danger").show();
            $("#purchaseOrderError").html(data.message || "Terjadi kesalahan saat menambahkan pemesanan.");
          }
        } catch (e) {
          console.error("Invalid response:", res);
          $(".purchaseOrderError-area").removeClass("alert-success").addClass("alert-danger").show();
          $("#purchaseOrderError").html("Terjadi error di server.");
        }
      },
      error: function (xhr, status, error) {
        $(".purchaseOrderError-area").removeClass("alert-success").addClass("alert-danger").show();
        $("#purchaseOrderError").html("Request gagal: " + error);
      },
      complete: function () {
        $("#purchaseOrderForm button[type=submit]").prop("disabled", false).text("Submit PO");
      }
    });
  });
}),$("#refundForm").submit(function (e) {
    e.preventDefault();

    var invoiceNumber = $("#invoice_number").val();

    if (invoiceNumber && invoiceNumber.trim() !== "") {
        var formData = $("#refundForm").serialize();
        $.ajax({
            type: "POST",
            url: "app/action/process_refund.php",
            data: formData,
            success: function (res) {
                try {
                    var response = JSON.parse(res);
                    if (response.status === "success") {
                        $(".refundError-area").show().css("color", "green");
                        $("#refundError").html(response.message);
                        $("#refundForm")[0].reset();
                    } else {
                        $(".refundError-area").show().css("color", "white");
                        $("#refundError").html(response.message);
                    }
                } catch (err) {
                    $(".refundError-area").show().css("color", "white");
                    $("#refundError").html("Response tidak valid: " + res);
                }
            },
            error: function () {
                $(".refundError-area").show().css("color", "red");
                $("#refundError").html("Terjadi kesalahan saat proses refund.");
            }
        });
    } else {
        $(".refundError-area").show().css("color", "red");
        $("#refundError").html("Invoice number harus diisi.");
    }
}), $("#adsuppliarForm").submit(function (e) {
    e.preventDefault();
    var t = $("#adsuppliarForm").serialize();
    $.ajax({
      type: "POST",
      url: "app/action/add_suppliar.php",
      data: t,
      success: function (e) {
        "yes" == $.trim(e) ?
          (alert("suppliar added successfully."), location.reload()) :
          alert(e);
      },
    });
  }),$("#addNews").submit(function (e) {
    e.preventDefault();

    var title = $("#title").val(),
        category = $("#category").val(),
        publishDate = $("#publish_date").val(),
        content = $("#content").val();

    if (title !== "" && category !== "" && publishDate !== "" && content !== "") {
        var formData = $("#addNews").serialize();
        $.ajax({
            type: "POST",
            url: "app/action/add_news.php",
            data: formData,
            success: function (res) {
                if ($.trim(res) === "Berita berhasil disimpan.") {
                    $("#newsErrorArea").css("border-color", "green").show();
                    $("#newsErrorMessage").html(res).css("color", "green");
                    $("#addNews")[0].reset();
                } else {
                    $("#newsErrorArea").css("border-color", "red").show();
                    $("#newsErrorMessage").html(res).css("color", "red");
                }
            },
            error: function () {
                $("#newsErrorArea").css("border-color", "red").show();
                $("#newsErrorMessage").html("Terjadi kesalahan pada server.").css("color", "red");
            }
        });
    } else {
        $("#newsErrorArea").css("border-color", "red").show();
        $("#newsErrorMessage").html("Silakan isi semua field yang diperlukan").css("color", "red");
    }
}),$("#addReward").submit(function (e) {
    e.preventDefault();

    var nama_reward = $("#nama_reward").val(),
        periode_hadiah_dari = $("#periode_hadiah_dari").val(),
        periode_hadiah_sampai = $("#periode_hadiah_sampai").val(),
        role_id = $("#role_id").val(),
        jumlah_point = $("#jumlah_point").val();

    if (nama_reward !== "" && periode_hadiah_dari !== "" && periode_hadiah_sampai !== "" && role_id !== "" && jumlah_point !== "") {
        var formData = $("#addReward").serialize();
        $.ajax({
            type: "POST",
            url: "app/action/add_reward.php",
            data: formData,
            success: function (res) {
                if ($.trim(res) === "Reward berhasil disimpan.") {
                    $("#rewardErrorArea").css("border-color", "green").show();
                    $("#rewardErrorMessage").html(res).css("color", "green");
                    $("#addReward")[0].reset();
                } else {
                    $("#rewardErrorArea").css("border-color", "red").show();
                    $("#rewardErrorMessage").html(res).css("color", "red");
                }
            },
            error: function () {
                $("#rewardErrorArea").css("border-color", "red").show();
                $("#rewardErrorMessage").html("Terjadi kesalahan pada server.").css("color", "red");
            }
        });
    } else {
        $("#rewardErrorArea").css("border-color", "red").show();
        $("#rewardErrorMessage").html("Silakan isi semua field yang diperlukan").css("color", "red");
    }
}),$("#salesForm").submit(function (e) {
    e.preventDefault();

    let buyerSelect = $("#customer_name").val(),
        buyerInput = $("#buyer").val().trim(),
        selectedBuyer = $("#customer_name option:selected"),
        buyerRole = selectedBuyer.data("role"),
        buyerName = buyerInput || selectedBuyer.data("name");
        buyerCode = selectedBuyer.data("code") || "-"; // ✅ ambil buyer code

    // Validasi pembeli
    if ((buyerSelect === "0" || buyerSelect === null) && buyerInput === "") {
        $("#saleErrorArea").css("color", "#b91c1c").show()
            .html("Silakan pilih pembeli dari daftar ATAU ketik nama pembeli secara manual.");
        return;
    }

    let products = [];
    let errorMsg = "";
    let totalAll = 0;

    $(".product-row").each(function () {
        let prodSelect = $(this).find(".product-select")[0],
            productId = $(prodSelect).val(),
            productName = $(prodSelect).find("option:selected").text(),
            qty = parseInt($(this).find(".quantity-input").val()) || 0,
            price = getPriceByRole(prodSelect.options[prodSelect.selectedIndex], buyerRole),
            subtotal = price * qty;

        if (!productId || productId === "0") {
            errorMsg = "Produk wajib dipilih.";
            return false;
        }
        if (qty <= 0) {
            errorMsg = "Jumlah wajib lebih dari 0.";
            return false;
        }

        products.push({
            product_id: productId,
            name: productName,
            price: price,
            quantity: qty,
            subtotal: subtotal
        });

        totalAll += subtotal;
    });

    if (errorMsg) {
        $("#saleErrorArea").css("color", "#b91c1c").show().html(errorMsg);
        return;
    }

    let formData = {
        buyer: buyerSelect,
        buyerName: buyerName,
        products: products,
        total_payment: totalAll
    };

    // 🔔 Build HTML konfirmasi
   let confirmHtml = `
  <div style="text-align:left; font-size:14px;">
    <p><b>Pembeli:</b> ${buyerName} <br><b>Kode:</b> ${buyerCode}</p>
    <hr style="margin:10px 0;">
    
    <table style="width:100%; border-collapse:collapse; font-size:13px;">
      <thead>
        <tr style="background:#f3f4f6; text-align:left;">
          <th style="padding:6px; border-bottom:1px solid #ddd;">Produk</th>
          <th style="padding:6px; border-bottom:1px solid #ddd;">Qty</th>
          <th style="padding:6px; border-bottom:1px solid #ddd;">Harga</th>
          <th style="padding:6px; border-bottom:1px solid #ddd;">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        ${products.map(p => `
          <tr>
            <td style="padding:6px; border-bottom:1px solid #eee;">${p.name}</td>
            <td style="padding:6px; border-bottom:1px solid #eee;">${p.quantity}</td>
            <td style="padding:6px; border-bottom:1px solid #eee;">Rp ${p.price.toLocaleString()}</td>
            <td style="padding:6px; border-bottom:1px solid #eee;">Rp ${p.subtotal.toLocaleString()}</td>
          </tr>
        `).join("")}
      </tbody>
    </table>
    
    <hr style="margin:10px 0;">
    <p style="font-size:15px; font-weight:bold; margin-bottom:8px;">
      Total: Rp ${totalAll.toLocaleString()}
    </p>
    <p style="color:#b91c1c; font-size:13px; font-weight:600; line-height:1.4;">
      ⚠️ Penjualan yang sudah diproses <u>tidak dapat dibatalkan</u>.<br>
      Pastikan data sudah benar sebelum dilanjutkan.
    </p>
  </div>
`;


    Swal.fire({
        title: "Konfirmasi Penjualan",
        html: confirmHtml,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Submit",
        cancelButtonText: "Batal",
        width: 600
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "app/action/add_sell_order.php",
                data: { data: JSON.stringify(formData) },
                success: function (res) {
                    if ($.trim(res) === "yes") {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: "✅ Penjualan berhasil disubmit!",
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "index.php?page=sell_order";
                        });
                    } else {
                        $("#saleErrorArea").css("color", "#b91c1c").show().html(res);
                    }
                },
                error: function () {
                    $("#saleErrorArea").css("color", "#b91c1c").show()
                        .html("Terjadi kesalahan saat mengirim data.");
                }
            });
        }
    });
}), $("#purchaseOrderTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/purchase_order_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "suppliar_id"
    }, {
        data: "total_amount"
    }, {
        data: "status"
    }, {
        data: "items_summary"
    }, {
        data: "created_at"
    }, {
        data: "approved_at"
    }, {
        data: "action"
    }]
}), $("#sellOrderTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/sell_order_data.php"
    },
    columns: [{
        data: "invoice_number"
    }, {
        data: "distributor_name"
    }, {
        data: "customer_name"
    }, {
        data: "net_total"
    }, {
        data: "order_date"
    }, {
        data: "items_summary"
    }]
}), $("#productTable").DataTable({

    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/product_data.php"
    },
    columns: [{
        data: "product_id"
    }, {
        data: "product_name"
    }, {
        data: "sell_price"
    }, {
        data: "action"
    }]
}), $("#stockManagementTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/stock_management_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "product_name"
    }, {
        data: "suppliar_name"
    }, {
        data: "stock"
    }, {
        data: "action"
    }]
}), $("#stockLogsTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/stock_log_data.php"
    },
    columns: [{
            "data": "suppliar_name"
        },
        {
            "data": "product_name"
        },
        {
            "data": "action_type"
        },
        {
            "data": "old_quantity"
        },
        {
            "data": "new_quantity"
        },
        {
            "data": "changed_by"
        },
        {
            "data": "created_at"
        },
    ]
}),$("#newsTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/news_data.php"  // adjust the path to your PHP backend
    },
    columns: [
        { data: "id" },            // news id
        { data: "title" },         // news title
        {data: "content"},
        { data: "publish_date" },  // formatted publish date
        { data: "created_at" },    // created datetime
        {data: "action"}
    ]
}),$("#rewardListTable").DataTable({
    processing: false,
    serverSide: false,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/reward_list_data.php"  // adjust the path to your PHP backend
    },
    columns: [
        { data: "no" },            // news id
        { data: "nama_reward" },         // news title
        {data: "periode_hadiah"},
        { data: "role_id" },  // formatted publish date
        { data: "jumlah_point" },    
    ]
}), $("#otherProductTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/factoryProduct_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "product_id"
    }, {
        data: "product_name"
    }, {
        data: "brand_name"
    }, {
        data: "catagory_name"
    }, {
        data: "quantity"
    }, {
        data: "product_expense"
    }, {
        data: "sell_price"
    }, {
        data: "action"
    }]
}), $("#purchaseTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/purchase_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "product_name"
    }, {
        data: "purchase_date"
    }, {
        data: "purchase_quantity"
    }, {
        data: "purchase_price"
    }, {
        data: "purchase_sell_price"
    }, {
        data: "purchase_net_total"
    }, {
        data: "purchase_due_bill"
    }, {
        data: "return_status"
    }, {
        data: "action"
    }]
}), $("#purchasereturnTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/purchase_return_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "sell_id"
    }, {
        data: "suppliar_name"
    }, {
        data: "return_date"
    }, {
        data: "product_name"
    }, {
        data: "return_quantity"
    }, {
        data: "subtotal"
    }, {
        data: "discount"
    }, {
        data: "netTotal"
    }]
}), $("#sellTable").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/sell_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "customer_name"
    }, {
        data: "order_date"
    }, {
        data: "sub_total"
    }, {
        data: "prev_due"
    }, {
        data: "net_total"
    }, {
        data: "paid_amount"
    }, {
        data: "due_amount"
    }, {
        data: "return_status"
    }, {
        data: "payment_type"
    }, {
        data: "action"
    }]
}), $("#sell_returnList").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/sell_return_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "customer_name"
    }, {
        data: "invoice_id"
    }, {
        data: "return_date"
    }, {
        data: "amount"
    }]
}), $("#expenseList").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/expense_data.php"
    },
    columns: [{
        data: "id"
    }, {
        data: "ex_date"
    }, {
        data: "expense_for"
    }, {
        data: "amount"
    }, {
        data: "expense_cat"
    }, {
        data: "ex_description"
    }, {
        data: "action"
    }]
}), $(document).ready((function () {
    function a() {
        $.ajax({
            url: "app/ajax/addNewRow.php",
            method: "POST",
            data: {
                getOrderItem: 1
            },
            success: function (a) {
                $("#invoiceItem").append(a), $(".select2").select2();
                var t = 0;
                $(".si_number").each((function () {
                    $(this).html(++t)
                }))
            }
        })
    }

    function t(a) {
        var t = 0,
            e = 0,
            d = parseInt($("#prev_due").val()),
            r = a;
        $(".tprice").each((function () {
            t += 1 * $(this).val(), $("#netTotal").val(e)
        }));
        var n = t / 100 * r;
        $("#s_discount_amount").val(n), e = t - n, e += d, $("#subtotal").val(t), $("#netTotal").val(e)
    }
    a(), $("#addNewRowBtn").on("click", (function (t) {
        t.preventDefault(), a()
    })), $(document).on("click", ".cancelThisItem", (function (a) {
        a.preventDefault(), $(this).parent().parent().remove(), t(0)
    })), $(document).on("change", ".pid", (function (a) {
        a.preventDefault();
        var e = $(this).val(),
            d = $(this).parent().parent();
        $.ajax({
            url: "app/ajax/single_sell_item.php",
            method: "POST",
            dataType: "json",
            data: {
                getSellSingleInfo: 1,
                id: e
            },
            success: function (a) {
                d.find(".qaty").val(a.quantity), d.find(".oqty").val(1), d.find(".price").val(a.sell_price), d.find(".pro_name").val(a.product_name), d.find(".tprice").val(d.find(".oqty").val() * d.find(".price").val()), t(0)
            }
        })
    })), $(document).on("keyup", ".oqty", (function (a) {
        var e = $(this),
            d = $(this).parent().parent();
        e.val() - 0 > d.find(".qaty").val() - 0 ? alert("please enter a valid quantity") : (d.find(".tprice").val(d.find(".oqty").val() * d.find(".price").val()), t(0))
    })), $(document).on("change", "#customer_name", (function (a) {
        a.preventDefault();
        var t = $("#customer_name").val();
        $.ajax({
            url: "app/ajax/find_customer_due.php",
            method: "POST",
            dataType: "json",
            data: {
                getcusTotalDue: 1,
                id: t
            },
            success: function (a) {
                $("#prev_due").val(a.total_due)
            }
        })
    })), $("#discount").on("keyup", (function (a) {
        a.preventDefault(), t($(this).val())
    })), $(document).on("keyup", ".price", (function (a) {
        a.preventDefault();
        var e = $(this).parent().parent(),
            d = $(this).val();
        e.find(".tprice").val(d);
        t(0)
    })), $(document).on("keyup", "#s_discount_amount", (function (a) {
        a.preventDefault();
        var t = $("#s_discount_amount").val(),
            e = $("#subtotal").val() - t;
        $("#netTotal").val(e)
    })), $("#paidBill").on("keyup", (function (a) {
        a.preventDefault();
        var t = $(this).val(),
            e = $("#netTotal").val() - t;
        $("#dueBill").val(e)
    })), $("#sellBtn").on("click", (function (a) {
        a.preventDefault();
        $("#sellForm").serialize();
        var t = $("#customer_name").val(),
            e = $("#payMethode").val();
        null != t && null != e ? $.ajax({
            url: "app/action/sell.php",
            method: "POST",
            data: $("#sellForm").serialize(),
            success: function (a) {
                var t = a;
                1 != isNaN(t) ? window.location.href = "index.php?page=view_sell&&view_id=" + t : alert("Failed to make sell. please try again.")
            }
        }) : alert("You missed some required field")
    }))
})), $(document).on("click", "#editSellBtn", (function (a) {
    a.preventDefault();
    var t = confirm("Are You sure want to edit this sell"),
        e = $("#payMethode").val();
    t ? null != e ? $.ajax({
        url: "app/action/edit_sell.php",
        method: "POST",
        data: $("#editSellForm").serialize(),
        success: function (a) {
            var t = a;
            1 != isNaN(t) ? window.location.href = "index.php?page=view_sell&&view_id=" + t : alert(a)
        }
    }) : alert("please select a payment methode") : alert("Your data are save")
})), $("#customer_blance_report_data").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/customer_blance_report_data.php"
    },
    columns: [{
        data: "member_id"
    }, {
        data: "member_name"
    }, {
        data: "company"
    }, {
        data: "phone_number"
    }, {
        data: "cus_total_transaction"
    }, {
        data: "cus_paid_total"
    }, {
        data: "cus_due_toal"
    }]
}), $("#suppliar_blance_report_data").DataTable({
    processing: !0,
    serverSide: !0,
    serverMethod: "post",
    ajax: {
        url: "app/ajax/suppliar_blance_report_data.php"
    },
    columns: [{
        data: "supplier_id"
    }, {
        data: "supplier_name"
    }, {
        data: "company"
    }, {
        data: "phone_number"
    }, {
        data: "net_total"
    }, {
        data: "paid_bill"
    }, {
        data: "due_bill"
    }]
}), $(document).on("keyup", ".returnQty", (function (a) {
    var t = $(this),
        e = $(this).parent().parent();
    t.val() - 0 > e.find(".orderQty").val() - 0 && alert("Return quantity must not getter than order quantity")
})), $("#returnSellBtn").on("click", (function (a) {
    a.preventDefault();
    $(".orderQty").val(), $(".returnQty").val();
    confirm("Are You sure want to edit this sell") ? $.ajax({
        url: "app/action/sell_return.php",
        method: "POST",
        data: $("#returnSell").serialize(),
        success: function (a) {
            "yes" == $.trim(a) ? alert("Product return successfull") : alet(a)
        }
    }) : alert("Your data are save")
})), $("#EditaddNewRowBtn").on("click", (function (a) {
    a.preventDefault(), editAddNewRow()
})), $('#combinedForm').on('submit', function(e){
      e.preventDefault();

      const payload = {};
      $('#combinedForm').find('input, select, textarea').each(function(){
          const name = $(this).attr('name');
          if(name && !name.endsWith("[]")){ 
              payload[name] = $(this).val().trim();
          }
      });

      payload.products = [];
      $('.product-row').each(function(){
          const pid = $(this).find('.product-select').val();
          const qty = $(this).find('.quantity-input').val();
          if(pid && qty){
              payload.products.push({
                  product_id: pid,
                  quantity: parseInt(qty)
              });
          }
      });

      // Validasi minimal 2 produk
      let totalQty = payload.products.reduce((sum, p) => sum + p.quantity, 0);
      if(totalQty < 2){
          $('#formErrorArea').text("pendaftaran reseller harus membeli minimal 2 produk").show();
          return;
      }
      $('#formErrorArea').hide();

      // Pesan konfirmasi
      let confirmHtml = `
        <div style="text-align:left">
          <p><b>Nama:</b> ${payload.sup_name}</p>
          <p><b>NIK:</b> ${payload.sup_nik}</p>
          <p><b>Tgl Lahir:</b> ${payload.birth_date}</p>
          <p><b>No HP:</b> ${payload.sup_contact}</p>
          <p><b>Alamat KTP:</b> ${payload.supaddressktp}</p>
          <p><b>Alamat Domisili:</b> ${payload.supaddress}</p>
          <p><b>Bank:</b> ${payload.sup_bank}</p>
          <p><b>Nama pada Bank:</b> ${payload.sup_name_bank}</p>
          <p><b>No. Rekening:</b> ${payload.sup_rekening}</p>
          <hr>
          <p style="color:red; font-weight:bold;">
            PENDAFTARAN YANG SUDAH DIPROSES TIDAK DAPAT DIBATALKAN.
          </p>
          <p>Jika data ini sudah benar, silahkan dilanjutkan.</p>
        </div>
      `;

      Swal.fire({
        title: 'Konfirmasi Data',
        html: confirmHtml,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Lanjutkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        width: 600
      }).then((result) => {
        if (result.isConfirmed) {
          // Submit via AJAX
          $.ajax({
              url: 'app/action/add_register_reseller.php',
              type: 'POST',
              data: { data: JSON.stringify(payload) },
              dataType: 'text',
              success: function(res){
                  res = res.trim();
                  if(res === 'yes'){
                      Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Reseller berhasil didaftarkan dan penjualan berhasil.',
                        timer: 2000,
                        showConfirmButton: false
                      }).then(() => {
                      });
                      window.location.href = 'index.php?page=add_register_reseller';
                  } else {
                      Swal.fire('Error', res.message || 'Terjadi kesalahan server.', 'error');
                  }
              },
              error: function(xhr, status, error){
                console.error(error);
                $('#formErrorArea').text('Terjadi error server.').show();
            }
          });
        }
      });

  });




