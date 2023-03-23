<?php

// Postavi upload direktorij i dopustene tipove datoteka
$target_dir = "uploads/";
$allowed_types = array("jpg", "png", "pdf");

// Nadji sve datoteke u direktoriju
$files = scandir($target_dir);

echo "Linkovi za preuzimanje datoteka: <br>";

foreach($files as $file) {
    // Provjera je li datoteka kriptirana
    if(strpos($file, "encrypted_") === 0) {
        // Dekriptiranje datoteke koristeci OpenSSL biblioteku
        $encrypted_file = $target_dir . $file;
        $decrypted_file = $target_dir . str_replace("encrypted_", "decrypted_", $file);
        $file_contents = file_get_contents($encrypted_file);
        $iv = substr($file_contents, 0, openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted_data = substr($file_contents, openssl_cipher_iv_length('aes-256-cbc'));
        $key = md5('jed4n j4k0 v3l1k1 kljuc');
        $decrypted_data = openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        // Spremanje dekriptiranog fajla na server
        file_put_contents($decrypted_file, $decrypted_data);

        // Kreiranje linka za preuzimanje datoteke
        $file_type = strtolower(pathinfo($decrypted_file, PATHINFO_EXTENSION));
        if(in_array($file_type, $allowed_types)) {
            echo "<a href='" . $decrypted_file . "'>" . $decrypted_file . "</a><br>";
        }
    }
}

?>
