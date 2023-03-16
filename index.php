<?php 

interface iRadovi {
    public function create($data);
    public function read();
    public function save();
}
class DiplomskiRadovi implements iRadovi {    
    private $id = NULL;
    private $naziv_rada = NULL;
    private $tekst_rada = NULL;
    private $link_rada = NULL;
    private $oib_tvrtke = NULL;

    function __construct($data) {
        $this->id = uniqid();
        $this->naziv_rada = $data['naziv_rada'];
        $this->tekst_rada = $data['tekst_rada'];
        $this->link_rada = $data['link_rada'];
        $this->oib_tvrtke = $data['oib_tvrtke'];
    }

    function create($data) {
        self::__construct($data);
    }
    
    function readData() {
        return array('id' => $this->id, 'naziv_rada' => $this->naziv_rada, 'tekst_rada' => $this->tekst_rada, 'link_rada' => $this->link_rada, 'oib_tvrtke' => $this->oib_tvrtke);
    } 

    function read() {
        //connect to database
        $conn = mysqli_connect('localhost', 'walla', 'test1234', 'radovi');
        // check connection
        if(!$conn){
            echo 'Connection error: ' . mysqli_connect_error();
        }
        //write query for all diplomski_radovi
        $sql = 'SELECT * FROM diplomski_radovi';

        //make query & get result
        $result = mysqli_query($conn, $sql);

        // fetch the resulting rows as an array
        $dipl_radovi = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_close($conn);
        print_r($dipl_radovi);
    } 

    function save() {
        //connect todatabase
        $conn = mysqli_connect('localhost', 'walla', 'test1234', 'radovi');
        // check connection
        if(!$conn){
            echo 'Connection error: ' . mysqli_connect_error();
        }

        $id = $this->id;
        $naziv = $this->naziv_rada;
        $tekst = $this->tekst_rada;
        $link = $this->link_rada;
        $oib = $this->oib_tvrtke;

        $sql = "INSERT INTO `diplomski_radovi` (`id`, `naziv_rada`, `tekst_rada`, `link_rada`, `oib_tvrtke`) VALUES ('$id', '$naziv', '$tekst', '$link', '$oib')";
        mysqli_query($conn, $sql);
        mysqli_close($conn);
    }
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://stup.ferit.hr/index.php/zavrsni-radovi/page/2");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$html = curl_exec($ch);

$dom = new DOMDocument();
@ $dom->loadHTML($html);

$xpath = new DOMXpath($dom);

$headings = $xpath->query("//h2[contains(@class,'blog-shortcode-post-title')]");
$links = $xpath->query("//h2[contains(@class,'blog-shortcode-post-title')]/a");
$oibs = $xpath->query("//article[contains(@class,'fusion-post-medium')]//img");

$count = $headings->length;

$naziv_rada_array = array();
$tekst_rada_array = array();
$link_rada_array = array();
$oib_tvrtke_array = array();

foreach($headings as $heading){
    $title_text = $heading->textContent;
    $naziv_rada_array[] = $title_text;
    //echo $title_text . '<br>';
}

foreach($links as $link){
    $href = $link->getAttribute("href");
    $link_rada_array[] = $href;
    //echo '<br>' . $href . '<br>';

    $chTekst = curl_init();
    curl_setopt($chTekst, CURLOPT_URL, $href);
    curl_setopt($chTekst, CURLOPT_RETURNTRANSFER, true);

    $htmlTekst = curl_exec($chTekst);

    $domTekst = new DOMDocument();
    @ $domTekst->loadHTML($htmlTekst);

    $diplomski_tekst = '';

    $paragraphs = $domTekst->getElementsByTagName('p');
    foreach($paragraphs as $paragraph){
        $diplomski_tekst .= $paragraph->textContent;
    }
    //echo $diplomski_tekst . "<br><br>";
    $tekst_rada_array[] = $diplomski_tekst;

}

foreach($oibs as $oib){
    $src = $oib->getAttribute("src");
    $filename = basename($src); // "05128411490.png"
    $extension = pathinfo($filename, PATHINFO_EXTENSION); // "png"
    $oib_without_extension = pathinfo($filename, PATHINFO_FILENAME); // "05128411490"

    //echo $filename_without_extension . '<br>'; // Output: 05128411490
    $oib_tvrtke_array[] = $oib_without_extension;
}

for($i = 0; $i < $count; $i++) {
    $rad = array(
        'naziv_rada' => $naziv_rada_array[$i], 
        'tekst_rada' => $tekst_rada_array[$i], 
        'link_rada' => $link_rada_array[$i], 
        'oib_tvrtke' => $oib_tvrtke_array[$i]
    );
    $novi_rad = new DiplomskiRadovi($rad);

    $infoRad = $novi_rad->readData();
    echo "<p>ID: {$infoRad['id']}.</p>";
    echo "<p>NAZIV RADA: {$infoRad['naziv_rada']}.</p>";
    echo "<p>TEKST RADA: {$infoRad['tekst_rada']}.</p>";
    echo "<p>LINK RADA: {$infoRad['link_rada']}.</p>";
    echo "<p>OIB TVRTKE: {$infoRad['oib_tvrtke']}.</p>";
    $novi_rad->save();
    echo "RAD JE DODAN U BAZU PODATAKA!";
    echo "<p>____________________________</p>";
    echo "<br>";
}

//echo '<br>' . "No. of headings " . $count;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Napredni Web - LV1</title>
</head>
<body>
    <h3>
    <?php 
        echo "<br><br>" . "Ispis tablice diplomski_radovi u obliku array:" . "<br>";
        $novi_rad->read();
    ?>
    </h3>
</body>
</html>