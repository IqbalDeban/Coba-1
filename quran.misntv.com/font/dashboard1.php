<?php
$servername = "localhost";
$username = "misntvco_misntv";
$password = "Nikenredaksi123!@#";
$dbname = "misntvco_quran";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8");

// Pagination settings
$recordsPerPage = 7; // Adjust as needed
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Check if a search term is provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Query to retrieve data with pagination based on the surat column and search term
$query = "SELECT * FROM `arabicquran` WHERE `surat` LIKE '%$searchTerm%' ORDER BY `arabicquran`.`surat`, `arabicquran`.`index` ASC LIMIT $offset, $recordsPerPage";
$result = $conn->query($query);

// Mapping of surah numbers to names and translations
$surahNames = array(
    "1" => array("الفاتحة", "Pembukaan", "Al-Fatihah"),
    "2" => array("البقرة", "Sapi Betina", "Al-Baqarah"),
    "3" => array("آل عمران", "Keluarga Imran", "Ali Imran"),
    "4" => array("النساء", "Wanita", "An-Nisa"),
    "5" => array("المائدة", "Jamuan", "Al-Ma'idah"),
    "6" => array("الأنعام", "Hewan Ternak", "Al-An'am"),
    "7" => array("الأعراف", "Tempat yang Tertinggi", "Al-A'raf"),
    "8" => array("الأنفال", "Harta Rampasan Perang", "Al-Anfal"),
    "9" => array("التوبة", "Pengampunan", "At-Taubah"),
    "10" => array("يونس", "Nabi Yunus", "Yunus"),
    "11" => array("هود", "Nabi Hud", "Hud"),
    "12" => array("يوسف", "Nabi Yusuf", "Yusuf"),
    "13" => array("الرعد", "Guruh", "Ar-Ra'd"),
    "14" => array("إبراهيم", "Nabi Ibrahim", "Ibrahim"),
    "15" => array("الحجر", "Gunung Al Hijr", "Al-Hijr"),
    "16" => array("النحل", "Lebah", "An-Nahl"),
    "17" => array("الإسراء", "Perjalanan Malam", "Al-Isra'"),
    "18" => array("الكهف", "Penghuni-penghuni Gua", "Al-Kahf"),
    "19" => array("مريم", "Maryam", "Maryam"),
    "20" => array("طه", "Ta Ha", "Ta Ha"),
    "21" => array("الأنبياء", "Nabi-Nabi", "Al-Anbiya"),
    "22" => array("الحج", "Haji", "Al-Hajj"),
    "23" => array("المؤمنون", "Orang-orang Mukmin", "Al-Mu'minun"),
    "24" => array("النور", "Cahaya", "An-Nur"),
    "25" => array("الفرقان", "Pembeda", "Al-Furqan"),
    "26" => array("الشعراء", "Penyair", "Asy-Syu'ara'"),
    "27" => array("النمل", "Semut", "An-Naml"),
    "28" => array("القصص", "Kisah-kisah", "Al-Qasas"),
    "29" => array("العنكبوت", "Laba-laba", "Al-'Ankabut"),
    "30" => array("الروم", "Bangsa Romawi", "Ar-Rum"),
    "31" => array("لقمان", "Keluarga Luqman", "Luqman"),
    "32" => array("السجدة", "Sajdah", "As-Sajdah"),
    "33" => array("الأحزاب", "Golongan-golongan yang Bersekutu", "Al-Ahzab"),
    "34" => array("سبإ", "Kaum Saba'", "Saba'"),
    "35" => array("فاطر", "Pencipta", "Fatir"),
    "36" => array("يس", "Ya Sin", "Yaasiin"),
    "37" => array("الصافات", "Barisan-barisan", "As-Saffat"),
    "38" => array("ص", "Shaad", "Sad"),
    "39" => array("الزمر", "Rombongan-rombongan", "Az-Zumar"),
    "40" => array("غافر", "Yang Mengampuni", "Ghafir"),
    "41" => array("فصلت", "Yang Dijelaskan", "Fussilat"),
    "42" => array("الشورى", "Musyawarah", "Asy-Syura"),
    "43" => array("الزخرف", "Perhiasan", "Az-Zukhruf"),
    "44" => array("الدخان", "Kabut", "Ad-Dukhan"),
    "45" => array("الجاثية", "Yang Bertekuk Lutut", "Al-Jasiyah"),
    "46" => array("الأحقاف", "Bukit-bukit Pasir", "Al-Ahqaf"),
    "47" => array("محمد", "Nabi Muhammad", "Muhammad"),
    "48" => array("الفتح", "Kemenangan", "Al-Fath"),
    "49" => array("الحجرات", "Kamar-kamar", "Al-Hujurat"),
    "50" => array("ق", "Qaaf", "Qaf"),
    "51" => array("الذاريات", "Angin yang Menerbangkan", "Az-Zariyat"),
    "52" => array("الطور", "Bukit", "At-Tur"),
    "53" => array("النجم", "Bintang", "An-Najm"),
    "54" => array("القمر", "Bulan", "Al-Qamar"),
    "55" => array("الرحمن", "Yang Maha Pemurah", "Ar-Rahman"),
    "56" => array("الواقعة", "Hari Kiamat", "Al-Waqi'ah"),
    "57" => array("الحديد", "Besi", "Al-Hadid"),
    "58" => array("المجادلة", "Wanita yang Mengajukan Gugatan", "Al-Mujadilah"),
    "59" => array("الحشر", "Pengusiran", "Al-Hasyr"),
    "60" => array("الممتحنة", "Wanita yang Diuji", "Al-Mumtahanah"),
    "61" => array("الصف", "Satu Barisan", "As-Saff"),
    "62" => array("الجمعة", "Hari Jum'at", "Al-Jumu'ah"),
    "63" => array("المنافقون", "Orang-orang yang Munafik", "Al-Munafiqun"),
    "64" => array("التغابن", "Hari Dinampakkan Kesalahan-kesalahan", "At-Tagabun"),
    "65" => array("الطلاق", "Talak", "At-Talaq"),
    "66" => array("التحريم", "Mengharamkan", "At-Tahrim"),
    "67" => array("الملك", "Kerajaan", "Al-Mulk"),
    "68" => array("القلم", "Pena", "Al-Qalam"),
    "69" => array("الحاقة", "Hari Kiamat", "Al-Haqqah"),
    "70" => array("المعارج", "Tempat Naik", "Al-Ma'arij"),
    "71" => array("نوح", "Nabi Nuh", "Nuh"),
    "72" => array("الجن", "Jin", "Al-Jinn"),
    "73" => array("المزمل", "Orang yang Berselimut", "Al-Muzzammil"),
    "74" => array("المدثر", "Orang yang Berkemul", "Al-Muddassir"),
    "75" => array("القيامة", "Kiamat", "Al-Qiyamah"),
    "76" => array("الإنسان", "Manusia", "Al-Insan"),
    "77" => array("المرسلات", "Malaikat-Malaikat Yang Diutus", "Al-Mursalat"),
    "78" => array("النبأ", "Berita Besar", "An-Naba'"),
    "79" => array("النازعات", "Malaikat-Malaikat Yang Mencabut", "An-Nazi'at"),
    "80" => array("عبس", "Ia Bermuka Masam", "'Abasa"),
    "81" => array("التكوير", "Menggulung", "At-Takwir"),
    "82" => array("الإنفطار", "Terbelah", "Al-Infitar"),
    "83" => array("المطففين", "Orang-orang yang Curang", "Al-Tatfif"),
    "84" => array("الإنشقاق", "Terbelah", "Al-Insyiqaq"),
    "85" => array("البروج", "Gugusan Bintang", "Al-Buruj"),
    "86" => array("الطارق", "Yang Datang di Malam Hari", "At-Tariq"),
    "87" => array("الأعلى", "Yang Paling Tinggi", "Al-A'la"),
    "88" => array("الغاشية", "Hari Pembalasan", "Al-Gasyiyah"),
    "89" => array("الفجر", "Fajar", "Al-Fajr"),
    "90" => array("البلد", "Negeri", "Al-Balad"),
    "91" => array("الشمس", "Matahari", "Asy-Syams"),
    "92" => array("الليل", "Malam", "Al-Lail"),
    "93" => array("الضحى", "Waktu Matahari Sepenggalahan Naik", "Ad-Duha"),
    "94" => array("الشرح", "Melapangkan", "Al-Insyirah"),
    "95" => array("التين", "Buah Tin", "At-Tin"),
    "96" => array("العلق", "Segumpal Darah", "Al-'Alaq"),
    "97" => array("القدر", "Kemuliaan", "Al-Qadr"),
    "98" => array("البينة", "Pembuktian", "Al-Bayyinah"),
    "99" => array("الزلزلة", "Kegoncangan", "Az-Zalzalah"),
    "100" => array("العاديات", "Berlari Kencang", "Al-'Adiyat"),
    "101" => array("القارعة", "Hari Kiamat", "Al-Qari'ah"),
    "102" => array("التكاثر", "Bermegah-megahan", "At-Takasur"),
    "103" => array("العصر", "Masa", "Al-'Asr"),
    "104" => array("الهمزة", "Pengumpat", "Al-Humazah"),
    "105" => array("الفيل", "Gajah", "Al-Fil"),
    "106" => array("قريش", "Suku Quraisy", "Quraisy"),
    "107" => array("الماعون", "Barang-barang yang Berguna", "Al-Ma'un"),
    "108" => array("الكوثر", "Nikmat yang Berlimpah", "Al-Kausar"),
    "109" => array("الكافرون", "Orang-orang Kafir", "Al-Kafirun"),
    "110" => array("النصر", "Pertolongan", "An-Nasr"),
    "111" => array("اللهب", "Gejolak Api", "Al-Lahab"),
    "112" => array("الإخلاص", "Ikhlas", "Al-Ikhlas"),
    "113" => array("الفلق", "Waktu Subuh", "Al-Falaq"),
    "114" => array("الناس", "Umat Manusia", "An-Nas"),
);
?>



















                


      
      
      
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Tambahkan link ke Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- Tambahkan link ke jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

    <!-- Tambahkan link ke Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
   

<script>
  window.addEventListener("scroll", function () {
    var button = document.getElementById("back-to-top");
    if (window.pageYOffset > 100) {
      button.classList.add("show");
    } else {
      button.classList.remove("show");
    }
  });

  function scrollToTop() {
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
  }
</script>



<div class="gtranslate_wrapper"></div>
<script>window.gtranslateSettings = {"default_language":"id","languages":["id","en","ar","ja","zh-TW"],"wrapper_selector":".gtranslate_wrapper"}</script>
<script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script>



<script language="JavaScript">
var symbol = String.fromCharCode(169); // Simbol hak cipta
var currentDate = new Date();
var day = currentDate.getDate();
var month = currentDate.toLocaleString('id-ID', { month: 'long' });
var year = currentDate.getFullYear();
var separator = ' , '; // Pemisah antara hak cipta dan tanggal
var customTitle = 'MISNTV AL-QUR\'AN';
var txt = ' | MISNTV Inspirasi Kita Semua ' + symbol + ' ' + year + separator + day + ' ' + month + ' ' + year + ' | ' + customTitle;
var speed = 200;
var refresh = null;

function action() {
document.title = txt;
txt = txt.substring(1) + txt.charAt(0);
refresh = setTimeout(action, speed);
}

action();
</script>






<script>
  // Disable Right click
  document.addEventListener('contextmenu', event => {
    // Allow right-clicking if the target is an input or textarea element
    if (event.target.tagName !== 'INPUT' && event.target.tagName !== 'TEXTAREA') {
      event.preventDefault();
    }
  });

  // Disable keydown
  document.addEventListener('keydown', function(e) {
    // Allow keydown events for input and textarea elements
    if (
      (e.ctrlKey && ['c', 'x', 'v', 'a', 'u'].includes(e.key.toLowerCase())) ||
      e.keyCode === 123
    ) {
      if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
      }
    }
  });


</script>

<style>
  /* Hide the scrollbar */
  ::-webkit-scrollbar {
    display: none;
  }
</style>






<style>
  /* Hide the scrollbar */
  ::-webkit-scrollbar {
    display: none;
  }
</style>


 <style>
#back-to-top {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 50px;
  height: 50px;
  background-color: #3498db; /* Blue background color */
  color: #fff;
  text-align: center;
  line-height: 50px;
  font-size: 24px;
  border-radius: 50%;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Soft shadow effect */
  opacity: 0;
  transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
  z-index: 9999;
}

#back-to-top.show {
  opacity: 1;
  transform: scale(1); /* Scale up on hover for a subtle effect */
}

#back-to-top:hover {
  background-color: #2980b9; /* Darker blue on hover */
  cursor: pointer;
}
  </style>




 <style>

/* Font */
@font-face {
    font-family: 'ArabicFont';
    src: url('font/lpmq.ttf') format('truetype');
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px auto;
    background-color: #f8f9fa; /* Background color for the table */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 15px;
    text-align: center;
    border: 1px solid #ddd;
    font-size: 18px; /* Adjust the font size as needed */
}

td:nth-child(1) {
    width: 75%;
}

th {
    background-color: #333;
    color: #fff;
}

/* Arabic Text Styles */
td.arabic-text {
    direction: rtl;
    font-size: 50px; /* Adjust the font size as needed */
    font-family: 'ArabicFont', sans-serif;
    color: #000000;
    line-height: 3;
  
}

/* Translation Text Styles */
td.translation-text {
    text-align: center;
    color: #0b511b; /* Ubah warna ke #15872f */
    font-weight: bold; /* Tambahkan tebal */
    line-height: 1.5; /* Jarak line spasi 1.5 (atau disesuaikan dengan preferensi Anda) */
    letter-spacing: 1px; /* Kerning 1px */
}


td.verse-number {
  background-color: #e0eef7; /* Soft blue color */
  padding: 8px; /* Padding untuk memberikan ruang di sekitar teks */
}


td.surah-name {
  background-color: #e0eef7; /* Soft blue color */
  padding: 8px; /* Padding untuk memberikan ruang di sekitar teks */
}



/* Surat Header Styles */
.surat-header {
    font-weight: bold;
    font-size: 24px;
    background-color: #333;
    color: #fff;
    padding: 20px;
    border-bottom: 2px solid #343a40;
}

/* Form Styles */
/* Bootstrap classes untuk styling */
input,
select {
    margin-bottom: 1rem;
    font-size: 1rem;
}

/* Tambahkan Bootstrap styling untuk form control */
input,
select,
textarea {
    width: 100%;
    padding: .375rem .75rem;
    border-radius: .25rem;
    border: 1px solid #3498db;
    font-size: 1rem;
}

/* Tambahkan kelas Bootstrap untuk mempercantik tampilan select dropdown */
select {
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="%233498db" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
    background-position: right .75rem center;
    background-repeat: no-repeat;
    background-size: 18px;
    padding-right: 2.5rem;
}

/* Untuk IE 11, Anda dapat menggunakan pseudo-element untuk menampilkan ikon dropdown */
@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
    select {
        &::-ms-expand {
            display: none;
        }

        &::after {
            content: '\25BC';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }
    }
}

button {
    background-color: #4caf50;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button[type="button"] {
    background-color: #ccc;
    margin-left: 10px;
}

button:hover,
button[type="button"]:hover {
    background-color: #45a049;
}

/* Responsive Styles */
@media only screen and (max-width: 600px) {
    form {
        margin: 20px;
    }
    th, td {
        display: block;
        width: 100%;
        box-sizing: border-box;
    }
    th {
        width: 100%;
    }
    td:nth-child(1) {
        width: 100%;
    }
}

/* Audio Player Styles */
.audio-player {
    background-color: #f5f5f5;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    margin: 0 auto;
}

.audio-player audio:playing {
    background-color: #3498db;
    color: #33335;
}

.audio-player audio:not(:playing) {
    background-color: #ecf0f1;
    color: #333;
}

.audio-player audio::-webkit-media-controls-timeline-container {
    background-color: #87CEFA;
    border-radius: 8px;
}

.audio-player audio::-webkit-media-controls-timeline-box {
    background-color: #FF6347;
    border-radius: 8px;
}

.audio-player audio::-webkit-media-controls-timeline::-webkit-media-controls-timeline-outer-container {
    height: 8px;
}

.audio-player audio {
    width: 100%;
    border: none;
    border-radius: 8px;
    margin-top: 15px;
}

@media screen and (max-width: 500px) {
    .audio-player {
        max-width: 100%;
    }
}

/* Pagination Styles */
.pagination-container {
    text-align: center;
    margin-top: 20px;
}

.pagination-link {
    display: inline-block;
    padding: 12px 18px;
    margin: 0 5px;
    text-align: center;
    text-decoration: none;
    color: #fff;
    background-color: #3498db;
    border: 1px solid #3498db;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s, transform 0.3s;
}

.pagination-link:hover {
    background-color: #2980b9;
    color: #fff;
    transform: scale(1.05);
}

@media only screen and (max-width: 600px) {
    .pagination-link {
        padding: 10px 14px;
        font-size: 14px;
    }
}

</style>

    <style>
        /* Add your CSS styles here */
        td.textlatin {
     text-align: center;
    color: #6610f2; /* Ubah warna ke #15872f */
    font-weight: bold; /* Tambahkan tebal */
    line-height: 1.5; /* Jarak line spasi 1.5 (atau disesuaikan dengan preferensi Anda) */
    letter-spacing: 1px; /* Kerning 1px */
        font-size: 30px;
     
        }
    </style>



   <style>
  
    .card-surah {
        width: 100%;
        padding: 34px 0;
        display: flex;
        justify-content: center;
        align-items: center; /* Mengubah align-items ke center */
        flex-direction: column;
        gap: 16px;
        margin-top: 0px;
        background-image: url('garis.png');
        background-repeat: repeat-x;
        background-position: bottom center;
    }

    @media only screen and (max-width: 600px) {
        .card-surah {
            padding: 20px 0;
        }
    }
    
    header {
        background-color: #2c3e50;
        color: #ecf0f1;
        padding: 20px;
        text-align: center;
    }

    header img {
        max-width: 100%;
        height: auto;
        margin-bottom: 10px;
        display: block; /* Mengubah menjadi display: block; */
        margin-left: auto; /* Menengahkan gambar secara horizontal */
        margin-right: auto; /* Menengahkan gambar secara horizontal */
    }

    header h1 {
        margin: 0;
        font-size: 24px;
    }

    @media (max-width: 600px) {
        header {
            padding: 10px;
        }

        header h1 {
            font-size: 18px;
        }
    }
</style>














<!-- Add Bootstrap CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- Add jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Add Bootstrap JS and Popper.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $(".playPauseBtn").click(function() {
            var audio = $(this).prev("audio")[0];
            if (audio.paused) {
                audio.play();
                $(this).text("Pause");
            } else {
                audio.pause();
                $(this).text("Play");
            }
        });

        $("audio").on("timeupdate", function() {
            var audio = $(this)[0];
            var percentage = (audio.currentTime / audio.duration) * 100;
            $(this).prev(".progress").find(".progress-bar").css("width", percentage + "%");
        });
    });
</script>

<header class="card-surah">
    <img src="quran.png" alt="Al-Qur'anul Karim">
    <h1>Al-Qur'anul Karim</h1>
</header>




<body>


<form action="islam" method="GET">

    <!-- Use a hidden input field to store the selected surah number -->
    <input type="hidden" id="selectedSurahNumber" name="selectedSurahNumber" value="<?php echo htmlspecialchars($selectedSurahNumber); ?>">
    <input type="hidden" id="search" name="search" placeholder="Enter Surat" value="<?php echo htmlspecialchars($searchTerm); ?>">

    <!-- Dropdown for Surah names -->
    <label for="surah">Select Surah:</label>
    <select id="surah" name="surah" onchange="updateSearchField()">
        <option value="">All Surahs</option>
        <?php
        foreach ($surahNames as $surahNumber => $surahData) {
            $arabicName = $surahData[0];
            $translation = $surahData[1];
            $transliteration = $surahData[2];
            
            echo "<option value='" . htmlspecialchars($arabicName) . "' data-surah-number='$surahNumber'>$surahNumber. $arabicName - $translation - $transliteration</option>";
        }
        ?>
    </select>

    <button type="submit">Search</button>
    <button type="button" onclick="window.location.href='beranda'">Reset</button>

    <script>
        function updateSearchField() {
            // Get the selected surah number from the dropdown
            var selectedSurahNumber = document.getElementById("surah").options[document.getElementById("surah").selectedIndex].getAttribute("data-surah-number");

            // Update the hidden input field and the visible search input field
            document.getElementById("selectedSurahNumber").value = selectedSurahNumber;
            document.getElementById("search").value = selectedSurahNumber;
        }
    </script>
</form>



<?php
// Fungsi untuk mengonversi angka Latin ke angka Arab
function latinToArabic($number) {
    $arabicNumbers = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
    $arabicNumberString = '';

    // Loop melalui setiap karakter angka dan konversi
    for ($i = 0; $i < strlen($number); $i++) {
        $char = $number[$i];

        // Cek apakah karakter adalah angka
        if (is_numeric($char)) {
            $arabicNumberString .= $arabicNumbers[$char];
        } else {
            // Jika bukan angka, biarkan karakter tersebut tanpa mengubah
            $arabicNumberString .= $char;
        }
    }

    return $arabicNumberString;
}

// Contoh penggunaan pada baris kode Anda

?>








<table>
    <thead>
        <tr>

    
       
        </tr>
    </thead>
    <tbody>
     <?php
$currentSurat = null; // To track the current surat
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        if ($currentSurat !== $row['surat']) {
            // Display a new header row for each unique surat
            $surahNumber = $row['surat'];
            $surahInfo = $surahNames[$surahNumber];
            echo "<tr><td colspan='5' class='surat-header'>$surahNumber. " . $surahInfo[0] . " (" . $surahInfo[1] . ")</td></tr>";
            $currentSurat = $row['surat'];
        }

        echo "<tr>";
        echo "<td class='verse-number' style='color: blue; font-size: 50px;'>" . latinToArabic($row['ayat']) . "</td>";
        echo "<td class='surah-name' style='color: blue; font-size: 50px;'>" . $surahNames[$row['surat']][0] . ' Juz (جزء) ' . latinToArabic($row['juz']) . "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td class='arabic-text'>" . $row["text"] . "</td>";
        echo '<td class="textlatin">' . $row["text_latin"] . '</td>';
        echo "</tr>";

        echo "<tr>";
        echo '<td class="translation-text" colspan="2">' . $row["arti"] . '</td>';
        echo "</tr>";
  echo "<div class='text-center'>";
        echo "<tr>";
        echo "<td colspan='2' class='audio-player'>";
        echo "<div class='container mt-3'>";
        echo "<div class='audio-player'>";
        echo "<div class='progress'>";
        echo "<div class='progress-bar bg-info' role='progressbar' style='width: 0%;' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100'></div>";
        echo "</div>";
        $currentIndex=$row['index'];
        echo "<audio id='audio-player-" . $currentIndex . "' onplay='syncAudio(this)' class='mt-3'>";
        echo "<source src='https://www.quran.misntv.com/audio/" . $currentIndex . ".mp3' type='audio/mp3'>";
        echo "Your browser does not support the audio tag.";
        echo "</audio>";

  echo "<button class='btn btn-custom mt-3 playPauseBtn'>Play</button>";
// Tombol Download dengan membuka tautan dalam tab baru
$downloadUrl = 'https://www.quran.misntv.com/audio/' . $currentIndex . '.mp3';
echo "<a href='$downloadUrl' download='audio.mp3' target='_blank'><button class='btn btn-custom mt-3 ml-2' id='downloadButton'>Download Audio</button></a>";

echo "</div>";
        echo "</div>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No data available</td></tr>";
}
?>
</tbody>
</table>



<script>
  function downloadAudio() {
    const downloadUrl = 'https://www.quran.misntv.com/audio/' . $currentIndex . '.mp3';
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.setAttribute('download', 'audio.mp3');
    link.click();
  }
</script>





    
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var audioElements = document.querySelectorAll('audio');
      document.addEventListener('DOMContentLoaded', function() {
    var textContainers = document.querySelectorAll('.arabic-text-container');

    textContainers.forEach(function(textContainer) {
        textContainer.addEventListener('scroll', function() {
            // Your scroll-related logic here
            console.log('Element scrolled!');
        });
    });
});
        var playButtons = document.querySelectorAll('.play-button');
        var pauseButtons = document.querySelectorAll('.pause-button');
        var currentAudioIndex = 0;
        var currentAudio = null;

        audioElements.forEach(function (audio, index) {
            audio.addEventListener('play', function () {
                currentAudio = audio;
                muteOtherAudio(audio);
                disablePlayPauseButtons();
            });

            audio.addEventListener('ended', function () {
                unmuteAllAudio();
                playNextAudio();
                enablePlayPauseButtons();
            });

            audio.addEventListener('pause', function () {
                currentAudio = null;
                unmuteAllAudio();
                enablePlayPauseButtons();
            });
        });

        function muteOtherAudio(currentAudio) {
            audioElements.forEach(function (audio) {
                if (audio !== currentAudio) {
                    audio.muted = true;
                }
            });
        }

        function unmuteAllAudio() {
            audioElements.forEach(function (audio) {
                audio.muted = false;
            });
        }

        function playNextAudio() {
            currentAudioIndex = (currentAudioIndex + 1) % audioElements.length;

            if (audioElements[currentAudioIndex - 1].ended) {
                audioElements[currentAudioIndex - 1].pause();
                audioElements[currentAudioIndex - 1].currentTime = 0;
            }

            audioElements[currentAudioIndex].play();

            textContainers[currentAudioIndex].scrollTop = audioElements[currentAudioIndex].currentTime * 20;
        }

        function disablePlayPauseButtons() {
            playButtons.forEach(function (button) {
                button.disabled = true;
            });

            pauseButtons.forEach(function (button) {
                button.disabled = true;
            });
        }

        function enablePlayPauseButtons() {
            playButtons.forEach(function (button) {
                button.disabled = false;
            });

            pauseButtons.forEach(function (button) {
                button.disabled = false;
            });
        }
    });
</script>








    
    


<div class="pagination-container">
    <?php
    // Set the fixed total number of pages
    $totalPages = 624;

    // Get the search query from the URL
    $query = isset($_GET['search']) ? $_GET['search'] : '';

    // Mendapatkan nilai halaman saat ini dari parameter URL
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // Menampilkan link previous jika halaman saat ini lebih besar dari 1
    if ($page > 1) {
        echo "<a class='pagination-link' href='?search=" . $query . "&page=" . ($page - 1) . "'>Previous</a>";
    }

    // Menampilkan link next jika halaman saat ini kurang dari total halaman
    if ($page < $totalPages) {
        echo "<a class='pagination-link' href='?search=" . $query . "&page=" . ($page + 1) . "'>Next</a>";
    }
    ?>
</div>





<?php
// Close connection
$conn->close();
?>





    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<br>
<br>
 <button id="back-to-top" onclick="scrollToTop()">
    &uarr;
  </button>
    <script>
    window.addEventListener("scroll", function () {
      var button = document.getElementById("back-to-top");
      if (window.pageYOffset > 100) {
        button.style.display = "block";
      } else {
        button.style.display = "none";
      }
    });

    function scrollToTop() {
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    }
  </script>
<br>
<br>
<footer>
<div class="container">
<center><p>&copy; <span id="currentYear"></span> PT. MAV ENTERTAINMENT MEDIA INSPIRASI SAHABAT NUSANTARA TELEVISI. Hak Cipta Dilindungi</p> </center>
</div>
</footer>

<script>
// Get the current year and update the footer text
document.getElementById("currentYear").innerText = new Date().getFullYear();
    

</script>


</body>
</html>








