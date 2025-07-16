/**
 * Array data kosong - tidak ada data contoh yang disertakan
 * @type {Array}
 */
let dashboardData = [];

/**
 * Membuka modal input dan mengunci scroll halaman.
 * Mengatur tampilan modal "formModal" menjadi terlihat,
 * mengunci overflow halaman agar tidak dapat di-scroll,
 * dan mengatur fokus ke input pertama.
 */
function openModal() {
    document.getElementById("formModal").style.display = "block";
    document.body.style.overflow = "hidden";
}

/**
 * Menutup modal input dan mengembalikan scroll halaman.
 * Mengatur tampilan modal "formModal" menjadi tidak terlihat,
 * mengembalikan overflow halaman menjadi normal, dan mereset form input.
 */

function closeModal() {
    document.getElementById("formModal").style.display = "none";
    document.body.style.overflow = "auto";
    document.getElementById("inputForm").reset();
}

/**
 * Menutup modal ketika mengklik di luar area modal.
 * Fungsi ini akan menutup modal input jika pengguna mengklik di luar area modal.
 * @param {Event} event - Event klik yang terjadi.
 */
window.onclick = function (event) {
    const modal = document.getElementById("formModal");
    if (event.target === modal) {
        closeModal();
    }
}

/**
 * Menutup modal ketika tombol Esc di tekan.
 * Fungsi ini akan menutup modal input jika pengguna menekan tombol Esc.
 * @param {KeyboardEvent} event - Event tekanan tombol yang terjadi.
 */
document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

/**
 * Menghasilkan kode HTML untuk progress bar berdasarkan nilai progress.
 * @param {string} progress - Nilai progress yang diharapkan.
 * @returns {string} Kode HTML untuk progress bar.
 */
function getProgressBarHTML(progress) {
    const percentage = parseInt(progress) || 0;
    return `
                <div class="progress-container">
                    <div class="progress-bar" style="width: ${percentage}%">
                        <span class="progress-text">${percentage}%</span>
                    </div>
                </div>
            `;
}

/**
 * Menghasilkan kode HTML untuk status badge berdasarkan nilai progress.
 * Status badge akan berwarna dan berlabel berbeda-beda berdasarkan nilai progress:
 * - 0: Pending (merah)
 * - 1-99: Progress (kuning)
 * - 100: Selesai (hijau)
 * @param {string} progress - Nilai progress yang diharapkan.
 * @returns {string} Kode HTML untuk status badge.
 */
function getStatusBadge(progress) {
    const percentage = parseInt(progress) || 0;
    if (percentage === 0) {
        return '<span class="status-badge status-pending">Pending</span>';
    } else if (percentage < 100) {
        return '<span class="status-badge status-progress">Progress</span>';
    } else {
        return '<span class="status-badge status-completed">Selesai</span>';
    }
}

/**
 * Menghasilkan tanggal dalam format bahasa Indonesia berdasarkan nilai bulan dalam format "YYYY-MM".
 * Jika nilai bulan tidak ada, maka akan mengembalikan nilai "-".
 * @param {string} monthValue - Nilai bulan dalam format "YYYY-MM".
 * @returns {string} Tanggal dalam format bahasa Indonesia.
 */
function formatMonth(monthValue) {
    if (!monthValue) return '-';
    const date = new Date(monthValue + '-01');
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long'
    });
}

/**
 * Mengupdate nilai statistic di halaman dashboard berdasarkan data yang tersedia.
 * Fungsi ini akan menghitung jumlah total kegiatan, kegiatan yang sedang berlangsung, dan kegiatan yang sudah selesai.
 * Kemudian, fungsi ini akan mengupdate nilai statistic di halaman dashboard dengan nilai yang dihitung.
 */
function updateStats() {
    const totalTasks = dashboardData.length;
    const completedTasks = dashboardData.filter(item => parseInt(item.progress) === 100).length;
    const ongoingTasks = dashboardData.filter(item => parseInt(item.progress) > 0 && parseInt(item.progress) < 100).length;
    const avgProgress = totalTasks > 0 ?
        Math.round(dashboardData.reduce((sum, item) => sum + parseInt(item.progress || 0), 0) / totalTasks) : 0;

    document.getElementById('totalTasks').textContent = totalTasks;
    document.getElementById('completedTasks').textContent = completedTasks;
    document.getElementById('ongoingTasks').textContent = ongoingTasks;
    document.getElementById('avgProgress').textContent = avgProgress + '%';
}

/**
 * Mengupdate isi tabel dashboard dengan data yang tersedia.
 * Fungsi ini akan menampilkan data kegiatan yang tersedia di dalam tabel dashboard.
 * Jika tidak ada data, maka akan menampilkan pesan "Belum ada data".
 * Fungsi ini juga akan mengupdate nilai statistic di halaman dashboard setelah selesai mengupdate isi tabel.
 * @returns {void}
 */
function loadData() {
    setTimeout(() => {
        const table = document.getElementById("dashboardTable");

        if (dashboardData.length === 0) {
            table.innerHTML = `
                        <tr>
                            <td colspan="9" class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <h3>Belum ada data</h3>
                                <p>Klik tombol "Tambah Data" untuk menambahkan kegiatan baru</p>
                            </td>
                        </tr>
                    `;
            return;
        }

        table.innerHTML = "";
        dashboardData.forEach((row, index) => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td><strong>${row.rencanakerja}</strong></td>
                        <td>${row.rencanaaksi}</td>
                        <td>${row.output}</td>
                        <td>${row.pjk}</td>
                        <td>${formatMonth(row.targetbulan)}</td>
                        <td>${row.linkBukti ? `<a href="${row.linkBukti}" target="_blank" class="link-btn"><i class="fas fa-external-link-alt"></i> Lihat</a>` : '-'}</td>
                        <td>${getProgressBarHTML(row.progress)}</td>
                        <td>${getStatusBadge(row.progress)}</td>
                    `;
            table.appendChild(tr);
        });

        updateStats();
    }, 500);
}

/**
 * Mengelola pengiriman form.
 * Fungsi ini mencegah pengiriman form secara default, mengubah tombol submit menjadi status "menyimpan",
 * mengambil data form, menambahkannya ke dalam array data dashboard, dan memuat ulang data.
 * Setelah selesai, fungsi ini akan mengembalikan tombol submit ke status semula.
 */
document.getElementById("inputForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const submitBtn = document.querySelector('.btn-submit');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    submitBtn.disabled = true;

    const formData = new FormData(this);
    const data = {
        rencanakerja: formData.get("rencanaKerja"),
        rencanaaksi: formData.get("rencanaAksi"),
        output: formData.get("output"),
        pjk: formData.get("pjk"),
        targetbulan: formData.get("target_bulan"),
        linkBukti: formData.get("bukti_link"),
        progress: formData.get("progress")
    };

    setTimeout(() => {
        dashboardData.push(data);
        alert("Data berhasil disimpan!");
        closeModal();
        loadData();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 1500);
});

/**
 * Memuat data awal.
 * Fungsi ini akan memuat data dashboard secara awal.
 */
loadData();