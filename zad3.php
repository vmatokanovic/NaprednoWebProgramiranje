<?php 
/*Expat parser*/

//Funkcija koja upravlja oznakom za početak
function handle_open_element($p, $element, $attributes) {

    //Ovisno o oznaci radi sljedeće
    switch ($element) {    
        //Oznake su address: book, title, author, year, chapter, and pages!    
        case 'RECORD': //Za knjigu stvori div
            echo '<div>';
            break;
            
        case 'IME': //Za poglavlje stvori p
            echo "<p>Ime: ";
            break;

        case 'PREZIME': //Za poglavlje stvori p
            echo "<p>Prezime: ";
            break;

        case 'EMAIL': //Za poglavlje stvori p
            echo "<p>E-MAIL: ";
            break;

        case 'SPOL': //Za poglavlje stvori p
            echo "<p>SPOL: ";
            break;

        case 'ZIVOTOPIS': //Za poglavlje stvori p
            echo "<p>ZIVOTOPIS: ";
            break;
        
        case 'SLIKA': //Pokaži sliku  
            echo "<img src=";
            break;
            
        case 'ID': //Naslovi su h2
            echo '<h2>';
            break;
            
        //Ostalo samo ispiši
            
    } //Kraj switch
    
}

//Funkcija za rukovanje oznakom za kraj
function handle_close_element($p, $element) {

    //Ovisno o oznaci radi sljedeće
    switch ($element) {         
        //Zatvori HTML oznake        
        case 'RECORD': 
            echo '</div>';
            break;
            
        case 'IME':
            echo '</p>';
            break;

        case 'PREZIME':
            echo '</p>';
            break;

        case 'EMAIL':
            echo '</p>';
            break;

        case 'SPOL':
            echo '</p>';
            break;

        case 'ZIVOTOPIS':
            echo '</p>';
            break;

        case 'SLIKA': //Pokaži sliku  
            echo "border=\"0\"><br>";
            break;

        case 'ID':
            echo '</h2>';
            break;

    } //Kraj switch
    
}

//Ispiši sadržaj
function handle_character_data($p, $cdata) {
    echo $cdata;
}


//Stvori parser   korak 1.
$p = xml_parser_create();

//Postavi funkcije za rukovanje korak 2.
//Funkcije koje se pokreću na početak i kraj XML oznake
xml_set_element_handler($p, 'handle_open_element', 'handle_close_element');
xml_set_character_data_handler($p, 'handle_character_data');

//Pročitaj datoteku korak 3.
$file = 'LV2.xml';
$fp = @fopen($file, 'r') or die("<p>Ne možemo otvoriti datoteku '$file'.</p></body></html>");
while ($data = fread($fp, 4096)) {
    xml_parse($p, $data, feof($fp));
}

//Zatvori parser korak 4.
xml_parser_free($p);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LV2-ZAD3</title>
    <link rel="stylesheet" href="zad3.css">
</head>
<body>
    
</body>
</html>