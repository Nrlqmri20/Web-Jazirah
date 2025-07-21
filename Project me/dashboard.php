<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring ZI Aceh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <header>
        <div class="header-content">
            <div class="header-left">
                <i class="fas fa-chart-line"></i>
                <div>
                    <div class="header-title">MONITORING ZI ACEH</div>
                    <div class="header-subtitle">Sistem Monitoring Zona Integritas</div>
                </div>
            </div>
            <div class="header-right">
                <div class="logo-container">
                    <img src="LOGO BPS.png" alt="" style="width: 55px;">
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-number" id="totalTasks">0</div>
                <div class="stat-label">Total Kegiatan</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number" id="ongoingTasks">0</div>
                <div class="stat-label">Sedang Berjalan</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number" id="completedTasks">0</div>
                <div class="stat-label">Selesai</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-number" id="avgProgress">0%</div>
                <div class="stat-label">Rata-rata Progress</div>
            </div>
        </div>

        <div class="top-bar">
            <div class="page-title">Data Monitoring</div>
            <button class="btn" onclick="openModal()">
                <i class="fas fa-plus-circle"></i> Tambah Data
            </button>
        </div>

        <div class="table-container">
            <div class="table-header">
                <i class="fas fa-table"></i>
                Data Kegiatan Zona Integritas
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Rencana Kerja</th>
                            <th>Rencana Aksi</th>
                            <th>Output</th>
                            <th>PJK</th>
                            <th>Target Bulan</th>
                            <th>Link Bukti</th>
                            <th>Progress</th>
                            <th>aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dashboardTable">
                        <tr>
                            <td colspan="9" class="loading">
                                <div class="spinner"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Pop-up -->
    <div class="modal" id="formModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Input Data Monitoring</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>

            <div class="modal-body">
                <form id="inputForm">
                    <div class="form-group">
                        <label><i class="fas fa-clipboard-list input-icon"></i> Rencana Kerja</label>
                        <input type="text" name="rencanaKerja" placeholder="Masukkan rencana kerja..." required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-tasks input-icon"></i> Rencana Aksi</label>
                        <textarea name="rencanaAksi" placeholder="Masukkan rencana aksi..." required
                            rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-bullseye input-icon"></i> Output</label>
                        <input type="text" name="output" placeholder="Masukkan output yang diharapkan..." required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user-tie input-icon"></i> PJK (Penanggung Jawab Kegiatan)</label>
                        <input type="text" name="pjk" placeholder="Masukkan nama PJK..." required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt input-icon"></i> Target Bulan</label>
                        <input type="month" name="target_bulan" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-link input-icon"></i> Link Bukti</label>
                        <input type="url" name="bukti_link" placeholder="https://example.com/bukti">
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-chart-line input-icon"></i> Progress (%)</label>
                        <input type="number" name="progress" min="0" max="100" value="0" placeholder="0-100" required>
                    </div>
                </form>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" form="inputForm" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Simpan Data
                </button>
            </div>
        </div>
    </div>
</body>
    <script src="js/dashboard.js"></script>
</html>