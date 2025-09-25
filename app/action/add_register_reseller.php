<?php
require_once '../init.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok'=>false,'message'=>'Method Not Allowed']);
    exit;
}

try {
    $payload = json_decode($_POST['data'] ?? '', true);
    if(!is_array($payload)) throw new Exception('Payload JSON tidak valid.');

    $sup_name      = trim($payload['sup_name']);
    $sup_nik       = trim($payload['sup_nik']);
    $sup_npwp       = trim($payload['sup_npwp']);
    $sup_rekening  = trim($payload['sup_rekening']);
    $sup_bank      = trim($payload['sup_bank']);
    $sup_contact   = trim($payload['sup_contact']);
    $sup_email     = trim($payload['sup_email']);
    $sup_role      = 5;
    $sup_address   = trim($payload['supaddress']);
    $provinsi      = trim($payload['provinsi']);
    $kota          = trim($payload['kota']);
    $kecamatan     = trim($payload['kecamatan']);
    $sup_address_ktp = trim($payload['supaddressktp']);
    $sup_akun      = trim($payload['sup_name_bank']);
    $birth_input   = trim($payload['dob'] ?? '');
    $referal_code  = trim($payload['kode_referal']);
    $user_id       = $_SESSION['distributor_id'] ?? null;
    $role_id       = $_SESSION['role_id'] ?? null;

    // ----------------- Validasi tanggal lahir -----------------
    $birth_date = null;
    if(!empty($birth_input)){
        if(preg_match('/^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-(\d{4})$/', $birth_input, $m)){
            $birth_date = "{$m[3]}-{$m[2]}-{$m[1]}";
        } else {
            throw new Exception('Format tanggal salah! Gunakan dd-mm-yyyy.');
        }
    }

    // ----------------- Validasi data wajib -----------------
    if(!preg_match('/^[0-9]{16}$/', $sup_nik)) throw new Exception('NIK harus 16 digit angka.');

    if (!empty($sup_npwp)) {            // cek hanya jika diisi
        if (!preg_match('/^[0-9]{15}$/', $sup_npwp)) {
            echo "NPWP harus berupa 15 digit angka.";
            exit;
        }
    }

    if(!$sup_name || !$sup_rekening || !$sup_bank || !$birth_date || !$sup_akun || !$sup_contact || !$sup_email || !$sup_role || !$sup_address){
        throw new Exception('Semua field user harus diisi.');
    }
    

    // ----------------- Cek kode referal -----------------
    $parent_id = null;
    $parent_code_db = null;
    if ($referal_code !== '') {
        $stmt = $pdo->prepare("SELECT id, suppliar_code FROM suppliar WHERE suppliar_code = :kode LIMIT 1");
        $stmt->execute([':kode' => $referal_code]);
        $ref = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$ref) throw new Exception('Kode referal tidak ditemukan.');
        $parent_id      = (int)$ref['id'];
        $parent_code_db = $ref['suppliar_code'];
    }

    // ----------------- Generate password default -----------------
    $name_prefix = substr(preg_replace('/[^A-Za-z]/', '', $sup_name),0,3);
    $dob_format  = date('dmY', strtotime($birth_date));
    $sup_password_plain = strtolower($name_prefix) . $dob_format;

    // ----------------- Simpan reseller -----------------
    $sup_data = [
        'name' => $sup_name,
        'address' => $sup_address,
        'con_num' => $sup_contact,
        'email' => $sup_email,
        'nik' => $sup_nik,
        'bank' => $sup_bank,
        'rekening' => $sup_rekening,
        'role_id' => $sup_role,
        'update_by' => $user_id,
        'address_ktp' => $sup_address_ktp,
        'date_of_birth' => $birth_date,
        'nama_rekening' => $sup_akun,
        'is_active' => 1,
        'provinsi' => $provinsi,
        'kota' => $kota,
        'kecamatan' => $kecamatan,
        'parent_id' => $parent_id,
        'parent_id_code' => $parent_code_db,
        'npwp' => $sup_npwp,
        'create_at' => date('Y-m-d H:i:s')   
    ];
    $suppliar_id = $obj->create('suppliar', $sup_data);
    if(!$suppliar_id) throw new Exception('Gagal menambahkan suppliar.');

    $suppliar_code = '1' . str_pad($suppliar_id, 5, "0", STR_PAD_LEFT);
    
    $obj->update('suppliar','id',$suppliar_id,['suppliar_code'=>$suppliar_code]);
    $obj->create('user', [
        'username'=>$sup_email,
        'password'=>$sup_password_plain,
        'role_id'=>$sup_role,
        'suppliar_id'=>$suppliar_id,
        'is_active'=>1,
        'suppliar_code'=>$suppliar_code
    ]);

    // ----------------- Proses Penjualan -----------------
    if(!isset($_SESSION['distributor_id'])) throw new Exception('Session distributor tidak ditemukan.');
    $current_user_id = (int)$_SESSION['distributor_id'];
    $order_date = date('Y-m-d');
    $items = $payload['products'] ?? [];
    if(empty($items)) throw new Exception('Produk wajib diisi.');

    $pdo->beginTransaction();

    $invoice_number = 'INV-' . strtoupper(uniqid());
    $invoiceData = [
        'invoice_number' => $invoice_number,
        'customer_id'    => $suppliar_id,
        'customer_name'  => $sup_name,
        'order_date'     => $order_date,
        'net_total'      => 0,
        'return_status'  => 0,
        'last_update'    => $order_date,
        'suppliar_id'    => $current_user_id
    ];
    $invoice_id = $obj->create('invoice',$invoiceData);

    $grand_total = 0;
    $total_qty_seller_points = 0;

    foreach($items as $p){
        $pid = (int)$p['product_id'];
        $qty = (int)$p['quantity'];
        if($pid<=0 || $qty<=0) continue;

        $product = $obj->find('products','id',$pid);
        if(!$product) throw new Exception("Produk ID {$pid} tidak ditemukan.");
        $price = (float)$product->sell_price_r;

        // sumber stok: jika role_id 1/10 -> pusat (id=1), selain itu distributor login
        $source_suppliar_id = ($role_id == 1 || $role_id == 10) ? 1 : (int)$user_id;

        $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id=? AND product_id=? FOR UPDATE");
        $stmt->execute([$source_suppliar_id,$pid]);
        $fromStock = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$fromStock || (int)$fromStock['stock']<$qty)
            throw new Exception("Stok {$product->product_name} kurang.");

        // kurangi stok sumber
        $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock=? WHERE id=?");
        $stmt->execute([(int)$fromStock['stock']-$qty, (int)$fromStock['id']]);

        // tambah stok tujuan
        $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id=? AND product_id=? FOR UPDATE");
        $stmt->execute([$suppliar_id,$pid]);
        $toStock = $stmt->fetch(PDO::FETCH_ASSOC);
        if($toStock){
            $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock=? WHERE id=?");
            $stmt->execute([(int)$toStock['stock']+$qty,(int)$toStock['id']]);
        }else{
            $stmt = $pdo->prepare("INSERT INTO distributor_stocks (suppliar_id, product_id, stock, suppliar_name, product_name)
                                   VALUES (?,?,?,?,?)");
            $stmt->execute([$suppliar_id,$pid,$qty,$sup_name,$product->product_name]);
        }

        $obj->create('invoice_details',[
            'invoice_no'   => $invoice_id,
            'pid'          => $pid,
            'product_name' => $product->product_name,
            'price'        => $price,
            'quantity'     => $qty
        ]);

        $grand_total += ($price*$qty);
        $total_qty_seller_points += $qty;

        $now = date('Y-m-d H:i:s');   

        $stmt = $pdo->prepare("INSERT INTO transaction_histories 
            (suppliar_id,type,product_id,quantity,created_at,customer_id,customer_name,invoice_number)
            VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$current_user_id,'penjualan',$pid,$qty,$now,$suppliar_id,$sup_name,$invoice_number]);

        $stmt = $pdo->prepare("INSERT INTO transaction_histories 
            (suppliar_id,type,product_id,quantity,created_at,customer_id,customer_name,invoice_number)
            VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$current_user_id, 'pembelian', $pid,$qty,$now,$suppliar_id,$sup_name,$invoice_number]);

           $invoice_number_reseller = 'INV-R' . strtoupper(uniqid());
            $invoiceData = [
            'invoice_number' => $invoice_number_reseller,
            'customer_id'    => 0,
            'customer_name'  => "penjualan pribadi",
            'order_date'     => $order_date,
            'net_total'      => 0,
            'return_status'  => 0,
            'last_update'    => $order_date,
            'suppliar_id'    => $suppliar_id
            ];

        $stmt = $pdo->prepare("INSERT INTO transaction_histories 
            (suppliar_id,type,product_id,quantity,created_at,customer_id,customer_name,invoice_number)
            VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$suppliar_id, 'penjualan', $pid,$qty,$now, 0 ,"penjualan pribadi",$invoice_number_reseller]);
    }

    // Update invoice & point
    $stmt = $pdo->prepare("UPDATE invoice SET net_total=?, last_update=? WHERE id=?");
    $stmt->execute([$grand_total,$order_date,$invoice_id]);

    $stmt = $pdo->prepare("UPDATE suppliar SET total_point=total_point+? WHERE id=?");
    $stmt->execute([$total_qty_seller_points,$current_user_id]);

    $stmt = $pdo->prepare("UPDATE suppliar SET total_point=total_point+? WHERE id=?");
    $stmt->execute([$total_qty_seller_points,$suppliar_id]);

    $pdo->commit();

    echo json_encode(['ok'=>true,'message'=>'Data berhasil disimpan','invoice'=>$invoice_number]);

} catch(Exception $e){
    if(isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(['ok'=>false,'message'=>$e->getMessage()]);
}
