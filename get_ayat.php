<?php
include "koneksi.php";

if (isset($_POST['surah'])) {
    $selectedSurah = $_POST['surah'];

    // Query database untuk mendapatkan data Ayat berdasarkan Surah
    $query = mysqli_query($koneksi, "SELECT number,id FROM verses WHERE id_chapter = $selectedSurah");

    $ayatList = array();
while ($data = mysqli_fetch_array($query)) {
    $ayatList[] = array(
        'number' => $data['number'],
        'id'     => $data['id']
    );
}

// Mengembalikan data Ayat dalam format JSON
echo json_encode($ayatList);

} else {
    // Jika tidak ada Surah yang dipilih, kembalikan data kosong
    echo json_encode(array());
}
?>
