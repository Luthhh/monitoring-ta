function openLogModal(btn) {
    try {
        if (!btn) return;
        
        let rawData = btn.getAttribute('data-log');
        let jsonStr = decodeURIComponent(escape(window.atob(rawData)));
        let data = JSON.parse(jsonStr);
        
        document.getElementById('log-kegiatan').innerText = data.kegiatan || '-';
        document.getElementById('log-bimbingan').innerText = data.tgl_bimbingan || '-';
        document.getElementById('log-tempat').innerText = data.tempat || '-';
        document.getElementById('log-durasi').innerText = data.durasi || '-';
        document.getElementById('log-topik').innerText = data.topik || '-';
        
        let badgeStatus = (data.status === 'Disetujui' || data.status === 'Selesai') ? 'done' : 'pending';
        let statusHtml = '<span class="status-badge ' + badgeStatus + '">' + (data.status || '-') + '</span>';
        
        document.getElementById('log-status').innerHTML = statusHtml;
        
        document.getElementById('logModal').style.display = 'flex';
    } catch (error) {
        console.error('Error parsing log data:', error);
        alert('Terjadi kesalahan saat memuat data. Mohon refresh halaman.');
    }
}

function closeLogModal() {
    document.getElementById('logModal').style.display = 'none';
}

// Klik luar modal untuk close
window.addEventListener('click', function(e) {
    const modal = document.getElementById('logModal');
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});
