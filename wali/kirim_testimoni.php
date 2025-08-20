<?php
include 'template_header.php';
require_once '../config.php';

$id_wali = $_SESSION['wali_id'];
$testimoni_sebelumnya = '';

// Cek apakah wali ini sudah pernah mengirim testimoni
$stmt = $conn->prepare("SELECT isi_testimoni FROM testimoni WHERE id_wali = ?");
$stmt->bind_param("i", $id_wali);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $testimoni_sebelumnya = $result->fetch_assoc()['isi_testimoni'];
}
$stmt->close();
?>

<div class="admin-content-header">
    <h2>Kirim Testimoni</h2>
    <p>Bagikan pengalaman berharga Anda selama ananda bersekolah di TK Cahaya Hati.</p>
</div>

<div class="admin-content-body">
    <div class="form-container">
        <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
            <p style="color: green; font-weight: bold; text-align: center;">Terima kasih! Testimoni Anda telah berhasil disimpan.</p>
        <?php endif; ?>

        <form action="proses_testimoni.php" method="POST">
            <div class="form-group">
                <label for="isi_testimoni">Testimoni Anda</label>
                <textarea id="isi_testimoni" name="isi_testimoni" rows="8" required><?php echo htmlspecialchars($testimoni_sebelumnya); ?></textarea>
                <small>Testimoni Anda akan ditinjau oleh Admin sebelum ditampilkan di halaman utama.</small>
            </div>
            <div class="form-actions">
                <button type="submit" name="simpan_testimoni" class="btn-aksi btn-simpan">Kirim Testimoni</button>
            </div>
        </form>
    </div>
</div>

<?php include 'template_footer.php'; ?>