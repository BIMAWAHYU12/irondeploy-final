<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. TANGKAP DATA DARI FORM HTML
    $nama = htmlspecialchars($_POST['nama']);
    $perusahaan = htmlspecialchars($_POST['perusahaan']);
    $email_pengunjung = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $layanan = htmlspecialchars($_POST['layanan']);
    $pesan = htmlspecialchars($_POST['pesan']);

    // --- BAGIAN 1: EMAIL MASUK KE ADMIN (INCOMING TEST) ---
    // Ini membuktikan orang luar bisa kirim ke server kamu
    $to_admin = "irondeploy@irondeploy.my.id"; 
    $subject_admin = "Prospek Baru: $nama ($perusahaan)";
    
    $msg_admin = "Halo Admin IronDeploy,\n\n";
    $msg_admin .= "Ada calon klien baru mengisi form:\n";
    $msg_admin .= "Nama: $nama\n";
    $msg_admin .= "Perusahaan: $perusahaan\n";
    $msg_admin .= "Email: $email_pengunjung\n";
    $msg_admin .= "Minat Layanan: $layanan\n";
    $msg_admin .= "Pesan:\n$pesan\n";
    
    // Header agar terbaca pengirimnya dari website
    $headers_admin = "From: Website Contact Form <admin@irondeploy.my.id>\r\n";
    $headers_admin .= "Reply-To: $email_pengunjung";

    // Kirim ke Admin
    mail($to_admin, $subject_admin, $msg_admin, $headers_admin);

    // --- BAGIAN 2: AUTOREPLY KE PENGUNJUNG (OUTGOING TEST) ---
    // Ini membuktikan server kamu bisa kirim keluar (via Brevo Relay tadi)
    $subject_user = "Terima Kasih telah Menghubungi IronDeploy";
    
    $msg_user = "Halo $nama,\n\n";
    $msg_user .= "Terima kasih telah menghubungi PT IronDeploy Outsourcing Indonesia.\n";
    $msg_user .= "Pesan Anda mengenai layanan '$layanan' telah kami terima.\n";
    $msg_user .= "Tim kami akan segera menghubungi Anda dalam waktu 1x24 jam.\n\n";
    $msg_user .= "Salam Hangat,\nTim IronDeploy\nwww.irondeploy.my.id";

    $headers_user = "From: PT IronDeploy Indonesia <irondeploy@irondeploy.my.id>";

    // Kirim ke Pengunjung
    if(mail($email_pengunjung, $subject_user, $msg_user, $headers_user)) {
        // TAMPILAN SUKSES (Redirect atau Pesan)
        echo "
        <div style='text-align:center; padding:50px; font-family:sans-serif;'>
            <h1 style='color:green;'>Pesan Terkirim! ✅</h1>
            <p>Terima kasih $nama. Cek inbox email Anda ($email_pengunjung) untuk konfirmasi otomatis.</p>
            <p>Admin kami juga sudah menerima data Anda di server lokal.</p>
            <a href='index.html' style='background:#2563eb; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Kembali ke Website</a>
        </div>";
    } else {
        echo "Gagal mengirim pesan. Pastikan server Postfix berjalan.";
    }
}
?>