-- Membuat database
CREATE DATABASE IF NOT EXISTS monitoring_zi_aceh;
USE monitoring_zi_aceh;

-- Tabel users untuk sistem login
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel untuk data monitoring
CREATE TABLE monitoring_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rencana_kerja TEXT NOT NULL,
    rencana_aksi TEXT NOT NULL,
    output VARCHAR(255) NOT NULL,
    pjk VARCHAR(100) NOT NULL,
    target_bulan DATE NOT NULL,
    bukti_link VARCHAR(500),
    progress INT DEFAULT 0 CHECK (progress >= 0 AND progress <= 100),
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert user admin default
INSERT INTO users (username, password, nama_lengkap, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@bps.go.id', 'admin');
-- Password untuk user admin adalah: password

-- Insert beberapa data sample
INSERT INTO users (username, password, nama_lengkap, email, role) VALUES 
('user1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pengguna Satu', 'user1@bps.go.id', 'user'),
('user2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pengguna Dua', 'user2@bps.go.id', 'user');

-- Insert data monitoring sample
INSERT INTO monitoring_data (rencana_kerja, rencana_aksi, output, pjk, target_bulan, bukti_link, progress, status, created_by) VALUES 
('Implementasi Sistem Zona Integritas', 'Melakukan sosialisasi dan pelatihan kepada seluruh pegawai', '100% pegawai memahami konsep ZI', 'John Doe', '2025-08-01', 'https://example.com/bukti1', 75, 'in_progress', 1),
('Evaluasi Kinerja Bulanan', 'Mengumpulkan data kinerja dari setiap unit kerja', 'Laporan evaluasi kinerja', 'Jane Smith', '2025-07-31', 'https://example.com/bukti2', 50, 'in_progress', 1),
('Peningkatan Pelayanan Publik', 'Renovasi ruang pelayanan dan digitalisasi proses', 'Ruang pelayanan yang nyaman dan proses yang efisien', 'Bob Johnson', '2025-09-15', '', 25, 'pending', 2);

-- Index untuk optimasi query
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_status ON users(status);
CREATE INDEX idx_monitoring_status ON monitoring_data(status);
CREATE INDEX idx_monitoring_progress ON monitoring_data(progress);
CREATE INDEX idx_monitoring_target ON monitoring_data(target_bulan);