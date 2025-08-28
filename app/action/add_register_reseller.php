<?php
require_once '../init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

try {
    $payload = json_decode($_POST['data'], true);
    if(!is_array($payload)) throw new Exception('Payload JSON tidak valid.');

    $sup_name      = trim($payload['sup_name']);
    $sup_nik       = trim($payload['sup_nik']);
    $sup_rekening  = trim($payload['sup_rekening']);
    $sup_bank      = trim($payload['sup_bank']);
    $sup_contact   = trim($payload['sup_contact']);
    $sup_email     = trim($payload['sup_email']);
    $sup_role      = 5;
    $sup_address   = trim($payload['supaddress']);
    $provinsi   = trim($payload['provinsi']);
     $kota   = trim($payload['kota']);
    $sup_address_ktp = trim($payload['supaddressktp']);
    $sup_akun      = trim($payload['sup_name_bank']);
    $birth_date    = !empty($payload['birth_date']) ? date('Y-m-d', strtotime($payload['birth_date'])) : null;
    $user_id       = $_SESSION['user_id'] ?? null;

    if(!preg_match('/^[0-9]{16}$/', $sup_nik)) throw new Exception('NIK harus 16 digit angka.');
    if(!$sup_name || !$sup_rekening || !$sup_bank || !$birth_date || !$sup_akun || !$sup_contact || !$sup_email || !$sup_role || !$sup_address){
        throw new Exception('Semua field user harus diisi.');
    }

    $name_prefix = substr(preg_replace('/[^A-Za-z]/', '', $sup_name),0,3);
    $dob_format  = date('dmY', strtotime($birth_date));
    $sup_password_plain = strtolower($name_prefix) . $dob_format;

    // Simpan reseller
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
        'kota' => $kota
    ];
    $suppliar_id = $obj->create('suppliar', $sup_data);
    if(!$suppliar_id) throw new Exception('Gagal menambahkan suppliar.');

    $suppliar_code = str_pad($suppliar_id, 6, "0", STR_PAD_LEFT);
    $obj->update('suppliar','id',$suppliar_id,['suppliar_code'=>$suppliar_code]);
    $obj->create('user', [
        'username'=>$sup_email,
        'password'=>$sup_password_plain,
        'role_id'=>$sup_role,
        'suppliar_id'=>$suppliar_id,
        'is_active'=>1,
        'suppliar_code'=>$suppliar_code
    ]);

    // =========================
    // 2) Proses Penjualan
    // =========================
    if(!isset($_SESSION['distributor_id'])) throw new Exception('Session distributor tidak ditemukan.');
    $current_user_id = (int)$_SESSION['distributor_id'];
    $order_date = date('Y-m-d');
    $items = $payload['products'] ?? [];
    if(empty($items)) throw new Exception('Produk wajib diisi.');

    $pdo->beginTransaction();

    $invoice_number = 'INV-' . strtoupper(uniqid());
    $invoiceData = [
        'invoice_number' => $invoice_number,
        'customer_id' => $suppliar_id,
        'customer_name' => $sup_name,
        'order_date' => $order_date,
        'net_total' => 0,
        'return_status' => 0,
        'last_update' => $order_date,
        'suppliar_id' => $current_user_id
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

        // Update stok
        $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id=? AND product_id=? FOR UPDATE");
        $stmt->execute([1,$pid]);
        $fromStock = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$fromStock || (int)$fromStock['stock']<$qty) throw new Exception("Stok {$product->product_name} kurang.");

        $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock=? WHERE id=?");
        $stmt->execute([(int)$fromStock['stock']-$qty, (int)$fromStock['id']]);

        // Tambah stok reseller
        $stmt = $pdo->prepare("SELECT * FROM distributor_stocks WHERE suppliar_id=? AND product_id=? FOR UPDATE");
        $stmt->execute([$suppliar_id,$pid]);
        $toStock = $stmt->fetch(PDO::FETCH_ASSOC);
        if($toStock){
            $stmt = $pdo->prepare("UPDATE distributor_stocks SET stock=? WHERE id=?");
            $stmt->execute([(int)$toStock['stock']+$qty,(int)$toStock['id']]);
        }else{
            $stmt = $pdo->prepare("INSERT INTO distributor_stocks (suppliar_id, product_id, stock, suppliar_name, product_name) VALUES (?,?,?,?,?)");
            $stmt->execute([$suppliar_id,$pid,$qty,$sup_name,$product->product_name]);
        }

        $obj->create('invoice_details',[
            'invoice_no'=>$invoice_id,
            'pid'=>$pid,
            'product_name'=>$product->product_name,
            'price'=>$price,
            'quantity'=>$qty
        ]);

        $grand_total += ($price*$qty);
        $total_qty_seller_points += $qty;

        $stmt = $pdo->prepare("INSERT INTO transaction_histories (suppliar_id,type,product_id,quantity,created_at,customer_id,customer_name,invoice_number) VALUES (?,?,?,?,NOW(),?,?,?)");
        $stmt->execute([$current_user_id,'penjualan',$pid,$qty,$suppliar_id,$sup_name,$invoice_number]);
    }

    // Update invoice & poin
    $stmt = $pdo->prepare("UPDATE invoice SET net_total=?, last_update=? WHERE id=?");
    $stmt->execute([$grand_total,$order_date,$invoice_id]);
    $stmt = $pdo->prepare("UPDATE suppliar SET total_point=total_point+? WHERE id=?");
    $stmt->execute([$total_qty_seller_points,$current_user_id]);
    $stmt = $pdo->prepare("UPDATE suppliar SET total_point=total_point+? WHERE id=?");
    $stmt->execute([$total_qty_seller_points,$suppliar_id]);

    $pdo->commit();
    echo "yes";

} catch(Exception $e){
    if($pdo && $pdo->inTransaction()) $pdo->rollBack();
    echo "❌ ".$e->getMessage();
}
?>
