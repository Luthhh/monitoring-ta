document.addEventListener("DOMContentLoaded", function () {
    // Data passed from Blade (should be defined as window.dashboardData)
    if (!window.dashboardData) {
        console.error("Dashboard data not found!");
        return;
    }

    const { milestoneLabels, milestoneSudah, milestoneBelum, statsByYear, chartBimbingan } = window.dashboardData;

    const ctx1 = document.getElementById('barChart');
    if (ctx1) {
        let chart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: milestoneLabels,
                datasets: [
                    {
                        label: 'Belum / Proses',
                        data: milestoneBelum,
                        backgroundColor: '#f3f4f6',
                        borderColor: '#94a3b8',
                        borderWidth: 1,
                        borderRadius: 6,
                        hoverBackgroundColor: '#e5e7eb'
                    },
                    {
                        label: 'Sudah Disetujui',
                        data: milestoneSudah,
                        backgroundColor: '#10b981',
                        borderColor: '#059669',
                        borderWidth: 1,
                        borderRadius: 6,
                        hoverBackgroundColor: '#059669'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { family: "'Inter', sans-serif", size: 12, weight: '500' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function (ctx) {
                                return ` ${ctx.dataset.label}: ${ctx.parsed.y} mahasiswa`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: "'Inter', sans-serif", weight: '500' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            stepSize: 1,
                            font: { family: "'Inter', sans-serif" }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                }
            }
        });

        // Filter angkatan
        const filterTahun = document.getElementById('filterTahun');
        if (filterTahun) {
            filterTahun.addEventListener('change', function () {
                const year = this.value;
                let pLabels = milestoneLabels;
                let pSudah = milestoneSudah;
                let pBelum = milestoneBelum;

                if (year !== 'all' && statsByYear[year]) {
                    const mData = statsByYear[year].milestones;
                    pSudah = pLabels.map(lbl => mData[lbl] ? mData[lbl].sudah : 0);
                    pBelum = pLabels.map(lbl => mData[lbl] ? mData[lbl].belum : 0);
                }

                chart.data.datasets[0].data = pBelum;
                chart.data.datasets[1].data = pSudah;
                chart.update();
            });
        }
    }

    // Chart Ringkasan Bimbingan (Grouped Bar Chart)
    const bimbinganCtx = document.getElementById('bimbinganChart');
    if (bimbinganCtx && chartBimbingan) {
        new Chart(bimbinganCtx, {
            type: 'bar',
            data: {
                labels: chartBimbingan.labels,
                datasets: [
                    {
                        label: 'Rencana Bimbingan',
                        data: chartBimbingan.rencana,
                        backgroundColor: '#4e73df',
                        borderColor: '#2e59d9',
                        borderWidth: 1,
                        borderRadius: 6,
                        hoverBackgroundColor: '#2e59d9'
                    },
                    {
                        label: 'Terlaksana',
                        data: chartBimbingan.terlaksana,
                        backgroundColor: '#1cc88a',
                        borderColor: '#17a673',
                        borderWidth: 1,
                        borderRadius: 6,
                        hoverBackgroundColor: '#17a673'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { family: "'Inter', sans-serif", size: 12, weight: '500' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: "'Inter', sans-serif", weight: '500' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { stepSize: 1, font: { family: "'Inter', sans-serif" } }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                }
            }
        });
    }
});

// ─── State modal ───────────────────────────────────────
let currentType = null; // 'bimbingan' | 'milestone'
let currentId = null;

function openPengajuanModal(nim, nama, tglPengajuan, rencana, topik, catatan) {
    document.getElementById('pm-nim').textContent = nim;
    document.getElementById('pm-nama').textContent = nama;
    document.getElementById('pm-tgl-pengajuan').textContent = tglPengajuan;
    document.getElementById('pm-rencana').textContent = rencana;
    document.getElementById('pm-topik').textContent = topik;
    document.getElementById('pm-catatan').textContent = catatan;
    openModal('pengajuanModal');
}

function openBimbinganDetailModal(id, mhsNama, tgl, waktu, tempat, progres, hasil, catatanMhs, filePath) {
    document.getElementById('bd-nama').textContent = mhsNama;
    document.getElementById('bd-tgl').textContent = tgl;
    document.getElementById('bd-waktu').textContent = waktu;
    document.getElementById('bd-tempat').textContent = tempat;
    document.getElementById('bd-progres').textContent = progres || '-';
    document.getElementById('bd-hasil').textContent = hasil || '-';
    document.getElementById('bd-catatan-mhs').textContent = catatanMhs || '-';

    const fileArea = document.getElementById('bd-file-area');
    if (filePath) {
        fileArea.innerHTML = `<a href="${filePath}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-file-download pe-1"></i> Unduh Berkas Bukti</a>`;
    } else {
        fileArea.innerHTML = '<span class="text-muted">Tidak ada berkas diunggah</span>';
    }

    openModal('bimbinganDetailModal');
}

function openVerifikasiModal(nim, nama, jenis, tgl, bukti, catatan) {
    document.getElementById('vm-nim').textContent = nim;
    document.getElementById('vm-nama').textContent = nama;
    document.getElementById('vm-jenis').textContent = jenis;
    document.getElementById('vm-tgl').textContent = tgl;
    document.getElementById('vm-catatan').textContent = catatan;

    const buktiEl = document.getElementById('vm-bukti');
    let buktiHtml = '';

    if (typeof bukti === 'object' && bukti !== null) {
        for (let key in bukti) {
            buktiHtml += `<a href="/storage/${bukti[key]}" target="_blank" class="badge bg-secondary text-decoration-none d-block mb-1" style="color:white; padding: 6px 12px; border-radius: 6px;">📄 ${key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')}</a>`;
        }
    } else if (bukti) {
        buktiHtml = `<a href="/storage/${bukti}" target="_blank" class="btn-proof text-decoration-none">📄 Lihat Bukti</a>`;
    } else {
        buktiHtml = '<span style="color:#aaa">-</span>';
    }
    buktiEl.innerHTML = buktiHtml;

    openModal('verifikasiModal');
}

function openVerifikasiBimbinganModal(nim, nama, jadwal, namaDok, catatan, berkas, link) {
    document.getElementById('vbm-nim').textContent = nim;
    document.getElementById('vbm-nama').textContent = nama;
    document.getElementById('vbm-jadwal').textContent = jadwal;
    document.getElementById('vbm-nama-dok').textContent = namaDok;
    document.getElementById('vbm-catatan').textContent = catatan;

    let berkasHtml = '';
    if (berkas) {
        berkasHtml += `<a href="${berkas}" target="_blank" class="btn-proof text-decoration-none">📄 Lihat Berkas</a>`;
    }
    if (link) {
        berkasHtml += `<a href="${link}" target="_blank" style="margin-left:10px; font-size:13px;">🔗 Link External</a>`;
    }
    if (!berkas && !link) {
        berkasHtml = '<span style="color:#aaa">-</span>';
    }
    document.getElementById('vbm-berkas').innerHTML = berkasHtml;

    openModal('verifikasiBimbinganModal');
}

function openApproveModal(type, id) {
    currentType = type;
    currentId = id;
    document.getElementById('approve-catatan').value = '';
    openModal('approveModal');
}

function openRejectModal(type, id) {
    currentType = type;
    currentId = id;
    document.getElementById('reject-catatan').value = '';
    openModal('rejectModal');
}

function submitAction(status) {
    const catatan = status === 'disetujui' || status === 'selesai'
        ? document.getElementById('approve-catatan').value
        : document.getElementById('reject-catatan').value;

    let finalStatus = status;
    let url = '';

    if (currentType === 'bimbingan') {
        // Pengajuan bimbingan baru: 'disetujui' atau 'ditolak'
        url = `/dosen/update-bimbingan-status/${currentId}`;
    } else if (currentType === 'bimbingan_verifikasi') {
        // Verifikasi bukti bimbingan:
        // Setujui -> 'selesai'
        // Tolak   -> 'disetujui' (kembali agar mhs bisa up ulang)
        url = `/dosen/update-bimbingan-status/${currentId}`;
        if (status === 'disetujui') finalStatus = 'selesai';
        if (status === 'ditolak') finalStatus = 'disetujui';
    } else if (currentType === 'milestone') {
        url = `/dosen/update-milestone-status/${currentId}`;
    }

    const form = document.getElementById('actionForm');
    form.action = url;
    document.getElementById('action-status').value = finalStatus;
    document.getElementById('action-catatan').value = catatan;
    form.submit();
}

function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = "flex";
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = "none";
}

// Klik di luar modal → tutup
window.addEventListener('click', function (e) {
    ['approveModal', 'rejectModal', 'pengajuanModal', 'verifikasiModal', 'verifikasiBimbinganModal', 'bimbinganDetailModal'].forEach(function (id) {
        const modal = document.getElementById(id);
        if (e.target === modal) modal.style.display = 'none';
    });
});
