<?php
session_start();

$koneksi= mysqli_connect("localhost","root","","kasir");



if (isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = mysqli_query($koneksi, "SELECT * FROM user WHERE username ='$username' AND password= '$password'");
    $hitung = mysqli_num_rows($check);

    if ($hitung > 0){
        $_SESSION['login'] = true;
        header('location:index.php');
    }else{
        echo '<script>alert("username atau password salah")
        window.location.href="login.php"</script>'; 
    }
    
}
if(isset($_POST['tambahproduk'])){
// deskripsi inisial variabel
    $nama_produk = $_POST['nama_produk'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $stock = $_POST['stock'];
    
    $insertproduk = mysqli_query($koneksi, "INSERT INTO produk (nama_produk, deskripsi, harga, stock) VALUES ('$nama_produk','$deskripsi','$harga','$stock')");

    if($insertproduk){
        header('location:stock.php');
    }else{
        echo '<script>alert("Penambahan barang gagal")
        window.location.href="stock.php"</script>'; 
    }

}
if(isset($_POST['tambahpelanggan'])){
// deskripsi inisial variabel
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];
    
    $insertpelanggan = mysqli_query($koneksi, "INSERT INTO pelanggan (nama_pelanggan, notelp, alamat) VALUES ('$nama_pelanggan','$notelp','$alamat')");

    if($insertpelanggan){
        header('location:pelanggan.php');
    }else{
        echo '<script>alert("Gagal Tambah Pelanggan")
        window.location.href="pelanggan.php"</script>'; 
    }

}
if(isset($_POST['tambahpesanan'])){
    // deskripsi inisial variabel
    $id_pelanggan = $_POST['id_pelanggan'];
    
    $insertpesanan = mysqli_query($koneksi, "INSERT INTO pesanan (id_pelanggan) VALUES ('$id_pelanggan')");
    
    if($insertpesanan){
        header('location:index.php');
    }else{
        echo '<script>alert("Gagal Tambah Pesanan")
        window.location.href="index.php"</script>'; 
    }
    
}

if(isset($_POST['addproduk'])){
// deskripsi inisial variabel
    $idp = $_POST['idp'];
    $id_produk = $_POST['id_produk'];
    $qty = $_POST['qty'];
    


    // hitung stok sekarang

    $hitung1 = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$id_produk'");
    $hitung2 = mysqli_fetch_array($hitung1);  
    $stocksekarang =  $hitung2['stock'];

    if($stocksekarang >=$qty){
        // kurangin stoknya dengan jumlah yang akan dikeluarkan
        $selisih = $stocksekarang - $qty;

        // stoknya cukup
        $insert = mysqli_query($koneksi, "INSERT INTO detail_pesanan (id_pesanan, id_produk, qty) VALUES ('$idp','$id_produk','$qty')");
        $update = mysqli_query($koneksi, "UPDATE produk SET stock='$selisih' WHERE id_produk='$id_produk'");
    
        if($insert && $update){
            header('location:view.php?idp=' . $idp);
        }else{
            echo '<script>alert("Gagal Tambah Produk")
            window.location.href="view.php'. $idp . '"</script>'; 
        }
    }else{
        // stock tidak cukup
        echo '<script>alert("Stock Tidak Cukup ")
            window.location.href="view.php'. $idp . '"</script>'; 
    }
}
//tambah barang masuk
if(isset($_POST['barangmasuk'])){
    $id_produk = $_POST['id_produk'];
    $qty= $_POST['qty'];

    $insertbar = mysqli_query($koneksi, "INSERT INTO masuk (id_produk, qty) VALUES ('$id_produk', '$qty')");

    if($insertbar){
        header('location:masuk.php');
    }else{
        echo '<script>alert("Gagal")
            window.location.href="masuk.php"</script>';  
    }
}

if(isset($_POST['hapusprodukpesanan'])){
    $iddetail = $_POST['iddetail'];
    $idpr = $_POST['idpr'];
    $idp = $_POST['idp'];
    
    // cek qty sekarnag
    $cek1 = mysqli_query($koneksi, "SELECT * FROM detail_pesanan WHERE id_detailpesanan='$iddetail'");
    $cek2 = mysqli_fetch_array($cek1);
    $qtysekarang = $cek2['qty'];
    
    
    // cek stock sekarnag
    $cek3 = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk='$idpr'");
    $cek4 = mysqli_fetch_array($cek3);
    $stocksekarang = $cek4['stock'];

    $hitung = $stocksekarang + $qtysekarang;

    $update = mysqli_query($koneksi, "UPDATE produk SET stock='$hitung' WHERE id_produk='$idpr'");//update stock
    $hapus = mysqli_query($koneksi, "DELETE FROM detail_pesanan WHERE id_produk='$idpr' AND id_detailpesanan='$iddetail'");

    if($update && $hapus){
        header('location:view.php?idp=' . $idp);

    }else{
        echo '<script>alert("Stock Tidak Cukup ")
            window.location.href="view.php'. $idp . '"</script>';
    }
}
?>