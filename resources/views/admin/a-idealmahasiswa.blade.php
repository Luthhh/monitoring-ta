<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mahasiswa Ahead- Dosen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            background: #f4f6fb;
        }

        /* Sidebar */
        .sidebar {
            width: 80px;
            height: 100vh;
            background: #ffffff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            padding-top: 20px;
        }

        .sidebar ul {
            list-style: none;
            text-align: center;
        }

        .sidebar ul li {
            padding: 20px 0;
            cursor: pointer;
        }

        .sidebar ul li:hover {
            background: #f1f2f6;
        }

        /* Main */
        .main {
            flex: 1;
            padding: 30px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .stat-card {
            background: #ffd900;
            color: black;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .stat-card h2 {
            font-size: 32px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            padding: 14px;
            text-align: left;
            font-size: 14px;
        }

        table thead {
            background: #f1f2f6;
        }

        table tbody tr {
            border-bottom: 1px solid #eee;
        }

        .badge {
            background: #ffd900;
            color: black;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
        }

        .aksi button {
            border: none;
            padding: 8px 10px;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 5px;
        }

        .btn-view {
            background: #4b7bec;
            color: white;
        }

        .btn-alert {
            background: #f39c12;
            color: white;
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li>🏠</li>
            <li>📄</li>
            <li>👨‍🎓</li>
            <li>⚙</li>
        </ul>
    </div>

    <!-- Main -->
    <div class="main">
        <h1>Daftar Mahasiswa</h1>

        <!-- Statistik -->
        <div class="stat-card">
            <h2>30</h2>
            <p>Mahasiswa Bimbingan Aktif</p>
        </div>

        <!-- Tabel -->
        <div class="card">
            <h3>Pengajuan Bimbingan</h3>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Masuk</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Topik Tugas Akhir</th>
                        <th>Status Progress</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>2020/2021</td>
                        <td>Luthfi Dika Chandra</td>
                        <td>J0403221143</td>
                        <td>Makan areng</td>
                        <td><span class="badge">Penetapan Komisi Pembimbing</span></td>
                        <td class="aksi">
                            <button class="btn-view">👁</button>
                            <button class="btn-alert">🔔</button>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>2020/2021</td>
                        <td>Luthfi Dika Chandra</td>
                        <td>J0403221143</td>
                        <td>Makan areng</td>
                        <td><span class="badge">Penetapan Komisi Pembimbing</span></td>
                        <td class="aksi">
                            <button class="btn-view">👁</button>
                            <button class="btn-alert">🔔</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
