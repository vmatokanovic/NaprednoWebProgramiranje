<?php

// Postavi upload direktorij i dopustene tipove datoteka
$target_dir = "uploads/";
$allowed_types = array("jpg", "png", "pdf");

// Provjera je li datoteka uploadana
if(isset($_FILES["fileToUpload"])) {
    $file_name = $_FILES["fileToUpload"]["name"];
    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Provjera je li tip datoteke dopusten
    if(in_array($file_type, $allowed_types)) {
        $target_file = $target_dir . basename($file_name);

        // Upload datoteke na server
        if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "Datoteka " . $file_name . " je uploadana i kriptirana. ";

            // Kriptiranje datoteke koristeci OpenSSL biblioteku
            $encrypted_file = $target_dir . "encrypted_" . $file_name;
            $key = md5('jed4n j4k0 v3l1k1 kljuc');
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
            $encrypted_data = openssl_encrypt(file_get_contents($target_file), 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

            file_put_contents($encrypted_file, $iv . $encrypted_data);

            echo "Datoteka je kriptirana i spremljena kao " . $encrypted_file;
        } else {
            echo "GRESKA prilikom uploada datoteke.";
        }
    } else {
        echo "Samo JPG, PNG, i PDF datoteke su dopustene.";
    }
}

?>

<!DOCTYPE html>
<html>
<body>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    Odaberi datoteku za upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload" name="submit">
</form>

</body>
</html>
