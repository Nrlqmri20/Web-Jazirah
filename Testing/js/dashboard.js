// Dashboard JavaScript dengan integrasi PHP
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    updateStatistics();
    
    // Setup form submission
    document.getElementById('inputForm').addEventListener('submit', handleFormSubmit);
});

// Load data dari server
function loadDashboardData() {
    fetch('api/get_monitoring_data.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTableData(data.data);
                updateStatistics();
            } else {
                showError('Gagal memuat data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memuat data');
        });
}

// Display data di tabel
function displayTableData(data) {
    const tbody = document.getElementById('dashboardTable');
    
    if (data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Belum ada data</h3>
                    <p>Klik "Tambah Data" untuk menambah data monitoring baru</p>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = data.map((item, index) => `
        <tr>
            <td>${index + 1}</td>
            <td>${item.rencana_kerja}</td>
            <td>${item.rencana_aksi}</td>
            <td>${item.output}</td>
            <td>${item.pjk}</td>
            <td>${formatDate(item.target_bulan)}</td>
            <td>
                ${item.bukti_link ? 
                    `<a href="${item.bukti_link}" target="_blank" class="link-btn">
                        <i class="fas fa-external-link-alt"></i> Lihat
                    </a>` : 
                    '<span class="text-muted">-</span>'
                }
            </td>
            <td>
                <div class="progress-container">
                    <div class="progress-bar" style="width: ${item.progress}%">
                        <div class="progress-text">${item.progress}%</div>
                    </div>
                </div>
            </td>
            <td>
                <button class="btn-aksi" onclick="editData(${item.id})" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-aksi" onclick="deleteData(${item.id})" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// Update statistik
function updateStatistics() {
    fetch('api/get_statistics.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalTasks').textContent = data.stats.total;
                document.getElementById('ongoingTasks').textContent = data.stats.ongoing;
                document.getElementById('completedTasks').textContent = data.stats.completed;
                document.getElementById('avgProgress').textContent = data.stats.avgProgress + '%';
            }
        })
        .catch(error => console.error('Error updating statistics:', error));
}

// Handle form submission
function handleFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const action = formData.get('action');
    
    const submitBtn = document.querySelector('.btn-submit');
    const originalText = submitBtn.innerHTML;
    
    // Show loading
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    submitBtn.disabled = true;
    
    const endpoint = action === 'edit' ? 'api/update_monitoring_data.php' : 'api/add_monitoring_data.php';
    
    fetch(endpoint, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
            closeModal();
            loadDashboardData();
            document.getElementById('inputForm').reset();
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat menyimpan data');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Edit data
function editData(id) {
    fetch(`api/get_monitoring_data.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                const item = data.data[0];
                fillFormForEdit(item);
                openModal();
            } else {
                showError('Data tidak ditemukan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat mengambil data');
        });
}

// Fill form for editing
function fillFormForEdit(item) {
    const form = document.getElementById('inputForm');
    form.querySelector('input[name="action"]').value = 'edit';
    form.querySelector('input[name="id"]').value = item.id;
    form.querySelector('input[name="rencanaKerja"]').value = item.rencana_kerja;
    form.querySelector('textarea[name="rencanaAksi"]').value = item.rencana_aksi;
    form.querySelector('input[name="output"]').value = item.output;
    form.querySelector('input[name="pjk"]').value = item.pjk;
    form.querySelector('input[name="target_bulan"]').value = item.target_bulan;
    form.querySelector('input[name="bukti_link"]').value = item.bukti_link || '';
    form.querySelector('input[name="progress"]').value = item.progress;
    
    // Update modal title
    document.querySelector('.modal-header h3').textContent = 'Edit Data Monitoring';
    document.querySelector('.btn-submit').innerHTML = '<i class="fas fa-save"></i> Update Data';
}

// Delete data
function deleteData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        fetch('api/delete_monitoring_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
                loadDashboardData();
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat menghapus data');
        });
    }
}

// Modal functions
function openModal() {
    document.getElementById('formModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('formModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.getElementById('inputForm').reset();
    document.querySelector('input[name="action"]').value = 'add';
    document.querySelector('input[name="id"]').value = '';
    
    // Reset modal title
    document.querySelector('.modal-header h3').textContent = 'Input Data Monitoring';
    document.querySelector('.btn-submit').innerHTML = '<i class="fas fa-paper-plane"></i> Simpan Data';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('formModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Utility functions
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long' };
    const date = new Date(dateString + '-01');
    return date.toLocaleDateString('id-ID', options);
}

function showSuccess(message) {
    // Simple success notification
    const notification = document.createElement('div');
    notification.className = 'notification success';
    notification.innerHTML = `
        <i class="fas fa-check-circle"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function showError(message) {
    // Simple error notification
    const notification = document.createElement('div');
    notification.className = 'notification error';
    notification.innerHTML = `
        <i class="fas fa-exclamation-circle"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Add notification styles
const style = document.createElement('style');
style.textContent = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideInRight 0.3s ease;
    }
    
    .notification.success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    .notification.error {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);