<?php
include 'template_header.php';
require_once '../config.php';

$is_edit = false;
$event_data = ['id_event' => '', 'nama_event' => '', 'tanggal_mulai' => '', 'tanggal_selesai' => '', 'keterangan' => '', 'warna_event' => 'biru'];

if (isset($_GET['id'])) {
    $is_edit = true;
    $id_event = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM kalender_akademik WHERE id_event = ?");
    $stmt->bind_param("i", $id_event);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $event_data = $result->fetch_assoc();
    }
    $stmt->close();
}
?>

<div class="admin-content-header">
    <h2><?php echo $is_edit ? 'Edit Acara/Kegiatan' : 'Tambah Acara/Kegiatan Baru'; ?></h2>
</div>

<div class="admin-content-body">
    <div class="form-container">
        <form action="proses_kalender.php" method="POST">
            <?php if ($is_edit): ?>
                <input type="hidden" name="id_event" value="<?php echo $event_data['id_event']; ?>">
            <?php endif; ?>
            <input type="hidden" name="id_guru" value="<?php echo $_SESSION['guru_id']; ?>">

            <div class="form-group">
                <label for="nama_event">Nama Acara / Hari Libur</label>
                <input type="text" id="nama_event" name="nama_event" value="<?php echo htmlspecialchars($event_data['nama_event']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo $event_data['tanggal_mulai']; ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal_selesai">Tanggal Selesai (Opsional, untuk acara lebih dari 1 hari)</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="<?php echo $event_data['tanggal_selesai']; ?>">
            </div>
            <div class="form-group">
                <label for="warna_event">Tandai Sebagai (Warna)</label>
                <select id="warna_event" name="warna_event" required>
                    <option value="biru" <?php echo ($event_data['warna_event'] == 'biru') ? 'selected' : ''; ?>>Acara Sekolah (Biru)</option>
                    <option value="merah" <?php echo ($event_data['warna_event'] == 'merah') ? 'selected' : ''; ?>>Hari Libur (Merah)</option>
                    <option value="hijau" <?php echo ($event_data['warna_event'] == 'hijau') ? 'selected' : ''; ?>>Kegiatan Khusus (Hijau)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan (Opsional)</label>
                <textarea id="keterangan" name="keterangan" rows="4"><?php echo htmlspecialchars($event_data['keterangan']); ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" name="simpan_event" class="btn-aksi btn-simpan">Simpan</button>
                <a href="manajemen_kalender.php" class="btn-aksi btn-hapus">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include 'template_footer.php'; ?>