@push('styles')
<style>
.main { padding: 30px; }
.btn-back { background:#e7f1ff; color:#4e73df; border:1px solid #d0e2ff; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
.btn-back:hover { background:#4e73df; color:white; }
.stat-card { color:white; padding:25px; border-radius:12px; margin-bottom:25px; box-shadow:0 5px 15px rgba(0,0,0,0.08); }
.stat-card h2 { font-size:32px; font-weight:700; }
.stat-blue   { background:#02048d; }
.stat-green  { background:#00a806; }
.stat-yellow { background:#f6c23e; color:#000; }
.stat-red    { background:#e74a3b; }
.card { background:white; padding:25px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.05); }
#tabelMahasiswa { width:100%; border-collapse:collapse; margin-top:15px; }
#tabelMahasiswa th, #tabelMahasiswa td { padding:13px; text-align:center; font-size:14px; vertical-align:middle; word-break:break-word; }
#tabelMahasiswa thead { background:#f1f2f6; }
#tabelMahasiswa tbody tr { border-bottom:1px solid #eee; }
#tabelMahasiswa tbody tr:hover { background:#f9fafb; }
#tabelMahasiswa td:nth-child(4) { text-align:left; max-width:200px; }
.badge { padding:5px 12px; border-radius:20px; font-size:12px; display:inline-block; color:white; }
.badge-blue   { background:#02048d; }
.badge-green  { background:#00a806; }
.badge-yellow { background:#f6a500; color:#000; }
.badge-red    { background:#e74a3b; }
.danger-badge { background:#ffe5e5; color:#e74a3b; padding:3px 8px; border-radius:12px; font-size:11px; font-weight:600; display:inline-block; margin-left:4px; }
.action-buttons { display:flex; gap:8px; justify-content:center; }
.btn-icon { border:none; width:34px; height:34px; border-radius:8px; cursor:pointer; color:white; display:inline-flex; align-items:center; justify-content:center; font-size:14px; transition:0.2s; text-decoration:none; }
.btn-view { background:#0dcaf0; }
.btn-remind { background:#e74a3b; }
.btn-icon:hover { transform:scale(1.05); opacity:0.9; }
.table-tools { display:flex; gap:12px; margin-bottom:15px; flex-wrap:wrap; }
.table-tools input, .table-tools select { padding:8px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px; }
</style>
@endpush
