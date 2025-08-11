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
})), $("#purchaseOrderForm").submit(function (e) {
    e.preventDefault();

    var product = $("#product_id").val(),
        qty = $("#quantity").val();

    if ("" != product && null != qty && qty > 0) {
        var formData = $("#purchaseOrderForm").serialize();
        $.ajax({
            type: "POST",
            url: "app/action/add_purchase_order.php",
            data: formData,
            success: function (res) {
                if ($.trim(res) == "yes") {
                    $(".purchaseOrderError-area").show();
                    $("#purchaseOrderError").html("Pemesanan berhasil ditambahkan");
                    $("#purchaseOrderForm")[0].reset();
                } else {
                    $(".purchaseOrderError-area").show();
                    $("#purchaseOrderError").html(res);
                }
            }
        });
    } else {
        $(".purchaseOrderError-area").show();
        $("#purchaseOrderError").html("Silakan isi semua field yang diperlukan");
    }
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
}), $("#salesForm").submit(function (e) {
    e.preventDefault();

    let product = $("#product_id").val(),
        qty = $("#quantity").val(),
        buyerSelect = $("#customer_name").val(),
        buyerInput = $("#buyer").val().trim();

    if ((buyerSelect === "0" || buyerSelect === null) && buyerInput === "") {
        $("#saleErrorArea").show().html("Silakan pilih pembeli dari daftar ATAU ketik nama pembeli secara manual.");
        return;
    }

    if (!product || !qty || qty <= 0) {
        $("#saleErrorArea").show().html("Produk dan jumlah wajib diisi.");
        return;
    }

    let formData = {
        product_id: product,
        quantity: qty,
        buyer: buyerSelect,
        buyerName: buyerInput,
        total_payment: $("#total_payment").val()
    };

    $.ajax({
        type: "POST",
        url: "app/action/add_sell_order.php", // Ganti jika file PHP kamu berbeda
        data: formData,
        success: function (res) {
            if ($.trim(res) === "yes") {
                $("#saleErrorArea").css("color", "#16a34a").show().html("Penjualan berhasil ditambahkan.");
                $("#salesForm")[0].reset();
                $("#total_payment").val('');
            } else {
                $("#saleErrorArea").css("color", "#b91c1c").show().html(res);
            }
        },
        error: function () {
            $("#saleErrorArea").css("color", "#b91c1c").show().html("Terjadi kesalahan saat mengirim data.");
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
        {
            "data": "note"
        }
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
}));

