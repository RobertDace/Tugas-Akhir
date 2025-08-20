document.addEventListener('DOMContentLoaded', function() {
    
    const form = document.getElementById('form-pendaftaran');

    form.addEventListener('submit', function(event) {
        
        // Ambil nilai dari setiap input
        const namaLengkap = document.getElementById('nama_lengkap').value.trim();
        const tempatLahir = document.getElementById('tempat_lahir').value.trim();
        const tanggalLahir = document.getElementById('tanggal_lahir').value.trim();
        const namaOrangTua = document.getElementById('nama_orang_tua').value.trim();
        const nomorTelepon = document.getElementById('nomor_telepon').value.trim();
        const alamat = document.getElementById('alamat').value.trim();

        // Cek jika ada kolom wajib yang kosong
        if (namaLengkap === '' || tempatLahir === '' || tanggalLahir === '' || namaOrangTua === '' || nomorTelepon === '' || alamat === '') {
            
            // Hentikan pengiriman form
            event.preventDefault(); 
            
            // Tampilkan pesan peringatan
            alert('Mohon lengkapi semua kolom yang wajib diisi.');
        }
    });

});