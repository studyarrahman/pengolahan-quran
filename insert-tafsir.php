<h2>Tambah Data Tafsir</h2>

<form action="" method="post">
    <table>
        <tr>
            <td>Surah</td>
            <td><select name="id_chapter" id="surahDropdown" required>
                <option>Pilih Surah</option>
                <?php
                include "koneksi.php";
                $query = mysqli_query($koneksi,"SELECT * FROM chapters") or die (mysqli_error($koneksi));
                while($data = mysqli_fetch_array($query)){
                    echo "<option value=$data[number_chapter]> $data[name] </option>";
                }
                ?>
                </select>
        </tr>
        <tr>
            <td>Ayat</td>
            <td><select name="id_verse" id="ayatDropdown" required>
                <option>Pilih Ayat</option>
                <?php
                include "koneksi.php";
                $query = mysqli_query($koneksi,"SELECT * FROM verses") or die (mysqli_error($koneksi));
                while($data = mysqli_fetch_array($query)){
                    echo "<option value=$data[id]> $data[id] </option>";
                }
                ?>
                </select>
        </tr>
        <tr>
            <td>Kitab Tafsir</td>
            <td><select name="id_tafsir" required>
                <option>Pilih Kitab Tafsir</option>
                <?php
                include "koneksi.php";
                $query = mysqli_query($koneksi,"SELECT * FROM tafsirs") or die (mysqli_error($koneksi));
                while($data = mysqli_fetch_array($query)){
                    echo "<option value=$data[id]> $data[name] </option>";
                }
                ?>
                </select>
        </tr>
        <tr>
            <td>Bab</td>
            <td><select name="id_bab" optional>
        <option value="NULL">Tidak Memilih Bab</option>

                <?php
                include "koneksi.php";
                $query = mysqli_query($koneksi,"SELECT * FROM bab") or die (mysqli_error($koneksi));
                while($data = mysqli_fetch_array($query)){
                    echo "<option value=$data[id]> $data[name] </option>";
                }
                ?>
                </select>
        </tr>
        <tr>
            <td>Isi Tafsir</td>
            <td>
                <textarea name="text" placeholder="Masukkan isi tafsir" required></textarea>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Simpan" name="proses"></td>
        </tr>
    </table>
</form>

<?php
include "koneksi.php";

if(isset($_POST['proses'])){
    // Gunakan prepared statement untuk mencegah SQL Injection
    $text = mysqli_real_escape_string($koneksi, $_POST['text']);
    $id_verse = mysqli_real_escape_string($koneksi, $_POST['id_verse']);
    $id_tafsir = mysqli_real_escape_string($koneksi, $_POST['id_tafsir']);
    $id_bab = isset($_POST['id_bab']) && $_POST['id_bab'] !== "NULL" ? mysqli_real_escape_string($koneksi, $_POST['id_bab']) : NULL;

    // Gunakan prepared statement untuk mencegah SQL Injection
    $query = "INSERT INTO verse_tafsirs SET text=?, id_verse=?, id_tafsir=?, id_bab=?";
    $stmt = mysqli_prepare($koneksi, $query);

    // Bind parameter ke prepared statement
    mysqli_stmt_bind_param($stmt, "siii", $text, $id_verse, $id_tafsir, $id_bab);

    // Eksekusi statement
    mysqli_stmt_execute($stmt);

    // Periksa apakah penyimpanan berhasil
    if(mysqli_affected_rows($koneksi) > 0){
        echo "<script>alert('Data telah tersimpan')</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data')</script>";
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
}
?>

<!-- Tambahkan jQuery (pastikan untuk mendownload atau menggunakan CDN) -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function () {
        // Ambil elemen dropdown Surah dan Ayat
        var surahDropdown = $('#surahDropdown');
        var ayatDropdown = $('#ayatDropdown');

        // Event listener untuk memanggil fungsi saat Surah dipilih
        surahDropdown.change(function () {
            // Dapatkan nilai Surah yang dipilih
            var selectedSurah = surahDropdown.val();

            // Bersihkan opsi Ayat saat ini
            ayatDropdown.empty().append('<option>Pilih Ayat</option>');

            // Ambil data Ayat dari database dengan AJAX
            $.ajax({
                type: 'POST',
                url: 'get_ayat.php', // Ganti dengan nama file atau endpoint yang sesuai
                data: { surah: selectedSurah },
                dataType: 'json',
                success: function (data) {
                    // Tambahkan opsi Ayat berdasarkan data dari database
                    $.each(data, function (index, value) {
                        ayatDropdown.append('<option value="' + value.id + '">' + value.number + '</option>');
                        console.log(data)
                    });
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ' + status + ' - ' + error);
                }
            });
        });
    });
</script>
