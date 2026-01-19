DROP DATABASE IF EXISTS db_hotel;
CREATE DATABASE db_hotel;
USE db_hotel;

-- ==========================================
-- TABLES
-- ==========================================

CREATE TABLE staff (
    -- format: 13XXXX (XXXX is a 4-digit number)
    staff_id VARCHAR(6) PRIMARY KEY,
    username VARCHAR(32) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    nama_staff VARCHAR(100) NOT NULL,
    posisi ENUM('Manager', 'Staff') NOT NULL,
    status_akun ENUM('Active', 'Not Active') NOT NULL
);

CREATE TABLE tipe_kamar (
    -- format: XX (2 uppercase characters)
    id_tipe_kamar VARCHAR(2) PRIMARY KEY,
    nama_tipe_kamar VARCHAR(50) NOT NULL,
    desc_tipe_kamar TEXT NOT NULL,
    path_gambar VARCHAR(255),
    harga_dasar DECIMAL(19,4),
    kapasitas_maksimum INT
);

CREATE TABLE kamar (
    -- format: XXXX (4 digit number)
    nomor_kamar VARCHAR(4) PRIMARY KEY,
    id_tipe_kamar VARCHAR(2) NOT NULL,
    FOREIGN KEY (id_tipe_kamar) REFERENCES tipe_kamar(id_tipe_kamar)
);

CREATE TABLE promo (
    -- no format
    kode_promo VARCHAR(20) PRIMARY KEY,
    desc_promo TEXT NOT NULL,
    besar_diskon DECIMAL(5,2) NOT NULL,
    tanggal_berlaku DATE NOT NULL,
    tanggal_kedaluwarsa DATE NOT NULL
);

CREATE TABLE reservasi (
    -- format: R-YYYYMMDDXXXX (XXXX is a 4-digit number)
    id_reservasi VARCHAR(13) PRIMARY KEY,
    kode_promo VARCHAR(20),
    nama_tamu VARCHAR(100) NOT NULL,
    no_hp VARCHAR(14) NOT NULL,
    email VARCHAR(50) NOT NULL,
    tanggal_reservasi DATETIME NOT NULL,
    tanggal_checkin DATE NOT NULL,
    tanggal_checkout DATE NOT NULL,
    total_harga DECIMAL(19,4) NOT NULL,
    status_reservasi ENUM('Pending', 'Confirmed', 'Cancelled', 'Checked In', 'Checked Out') NOT NULL,
    metode_pembayaran VARCHAR(50) DEFAULT NULL,
    FOREIGN KEY (kode_promo) REFERENCES promo(kode_promo)
);

CREATE TABLE maintenance (
    -- format: M-YYYYMMDDXXXX (XXXX is a 4-digit number)
    id_maintenance VARCHAR(13) PRIMARY KEY,
    nomor_kamar VARCHAR(4) NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    deskripsi_maintenance TEXT NOT NULL,
    status_maintenance ENUM('Scheduled', 'Completed') NOT NULL,
    FOREIGN KEY (nomor_kamar) REFERENCES kamar(nomor_kamar)
);

CREATE TABLE detail_reservasi (
    id_reservasi VARCHAR(13) NOT NULL,
    nomor_kamar VARCHAR(4) NOT NULL,
    jumlah_tamu INT NOT NULL,
    CONSTRAINT pk_detail_reservasi PRIMARY KEY (id_reservasi, nomor_kamar),
    FOREIGN KEY (id_reservasi) REFERENCES reservasi(id_reservasi),
    FOREIGN KEY (nomor_kamar) REFERENCES kamar(nomor_kamar)
);

CREATE TABLE transaksi (
    -- format: T-YYYYMMDDXXXX (XXXX is a 4-digit number)
    id_transaksi VARCHAR(13) PRIMARY KEY,
    ref_number VARCHAR(50), -- Ditambahkan agar sesuai dengan Data Initialization
    id_reservasi VARCHAR(13) NOT NULL,
    tipe_transaksi ENUM('Reservasi', 'Biaya Tambahan') NOT NULL, -- Update ENUM sesuai data dummy
    tipe_pembayaran ENUM('Kredit', 'Debit', 'Transfer', 'QRIS', 'Cash') NOT NULL, -- Update ENUM sesuai data dummy
    jumlah_transaksi DECIMAL(19, 4) NOT NULL,
    tanggal_transaksi DATETIME NOT NULL,
    status_transaksi ENUM('Belum Dibayar', 'Dibayar', 'Expired') NOT NULL,
    FOREIGN KEY (id_reservasi) REFERENCES reservasi(id_reservasi)
);

CREATE TABLE log_staff_activity (
    -- is automatically incremented
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_staff VARCHAR(50) NOT NULL,
    activity_desc TEXT NOT NULL,
    activity_timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- FUNCTIONS
-- ==========================================
DELIMITER //

CREATE FUNCTION GenerateStaffID() 
RETURNS VARCHAR(6)
READS SQL DATA
BEGIN
    DECLARE last_id VARCHAR(6);
    DECLARE next_val INT;

    -- Get the current maximum ID
    SELECT MAX(staff_id) INTO last_id FROM staff;

    IF last_id IS NULL THEN
        SET next_val = 1;
    ELSE
        -- Extract the last 4 digits and increment
        SET next_val = CAST(SUBSTRING(last_id, 3) AS UNSIGNED) + 1;
    END IF;

    -- Format back to '13' + 4-digit number (e.g., 130005)
    RETURN CONCAT('13', LPAD(next_val, 4, '0'));
END //

-- Function Generate ID Reservasi (R-YYYYMMDDXXXX)
CREATE FUNCTION GenerateReservasiID() 
RETURNS VARCHAR(13)
READS SQL DATA
BEGIN
    DECLARE today_str VARCHAR(8);
    DECLARE last_id VARCHAR(13);
    DECLARE next_seq INT DEFAULT 1;
    
    SET today_str = DATE_FORMAT(NOW(), '%Y%m%d');
    
    SELECT MAX(id_reservasi) INTO last_id 
    FROM reservasi 
    WHERE id_reservasi LIKE CONCAT('R-', today_str, '%');
    
    IF last_id IS NOT NULL THEN
        SET next_seq = CAST(RIGHT(last_id, 4) AS UNSIGNED) + 1;
    END IF;
    
    RETURN CONCAT('R-', today_str, LPAD(next_seq, 4, '0'));
END //

-- Function Generate ID Maintenance (M-YYYYMMDDXXXX)
CREATE FUNCTION GenerateMaintenanceID() 
RETURNS VARCHAR(13)
READS SQL DATA
BEGIN
    DECLARE today_str VARCHAR(8);
    DECLARE last_id VARCHAR(13);
    DECLARE next_seq INT DEFAULT 1;
    
    SET today_str = DATE_FORMAT(NOW(), '%Y%m%d');
    
    SELECT MAX(id_maintenance) INTO last_id 
    FROM maintenance 
    WHERE id_maintenance LIKE CONCAT('M-', today_str, '%');
    
    IF last_id IS NOT NULL THEN
        SET next_seq = CAST(RIGHT(last_id, 4) AS UNSIGNED) + 1;
    END IF;
    
    RETURN CONCAT('M-', today_str, LPAD(next_seq, 4, '0'));
END //

-- Function Get One Available Room
CREATE FUNCTION GetOneAvailableRoom(p_checkin DATE, p_checkout DATE, p_tipe VARCHAR(2))
RETURNS VARCHAR(4)
READS SQL DATA
BEGIN
    DECLARE v_room VARCHAR(4);
    
    SELECT k.nomor_kamar INTO v_room
    FROM kamar k
    WHERE k.id_tipe_kamar = p_tipe
    AND k.nomor_kamar NOT IN (
        -- Cek bentrok dengan reservasi lain
        SELECT dr.nomor_kamar 
        FROM detail_reservasi dr
        JOIN reservasi r ON dr.id_reservasi = r.id_reservasi
        WHERE r.status_reservasi NOT IN ('Cancelled', 'Checked Out')
        AND (
            (p_checkin BETWEEN r.tanggal_checkin AND r.tanggal_checkout) OR
            (p_checkout BETWEEN r.tanggal_checkin AND r.tanggal_checkout) OR
            (r.tanggal_checkin BETWEEN p_checkin AND p_checkout)
        )
    )
    AND k.nomor_kamar NOT IN (
        -- Cek bentrok dengan maintenance
        SELECT m.nomor_kamar
        FROM maintenance m
        WHERE m.status_maintenance != 'Completed'
        AND (
            (p_checkin BETWEEN m.tanggal_mulai AND m.tanggal_selesai) OR
            (p_checkout BETWEEN m.tanggal_mulai AND m.tanggal_selesai) OR
            (m.tanggal_mulai BETWEEN p_checkin AND p_checkout)
        )
    )
    LIMIT 1;
    
    RETURN v_room;
END //

CREATE FUNCTION CalculateMonthlyBOR(p_year INT, p_month INT) 
RETURNS DECIMAL(5,2)
READS SQL DATA
BEGIN
    DECLARE v_total_capacity INT;
    DECLARE v_days_in_month INT;
    DECLARE v_occupied_bed_nights DECIMAL(10,2);
    DECLARE v_bor DECIMAL(5,2);

    -- 1. Total Capacity (Beds)
    SELECT SUM(tk.kapasitas_maksimum) INTO v_total_capacity
    FROM kamar k 
    JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id_tipe_kamar;

    -- 2. Days in Month
    SET v_days_in_month = DAY(LAST_DAY(CONCAT(p_year, '-', p_month, '-01')));

    -- 3. Occupied Bed Nights
    -- Logic: Sum of (Guests * Nights) for reservations made in this month
    SELECT SUM(dr.jumlah_tamu * DATEDIFF(r.tanggal_checkout, r.tanggal_checkin)) INTO v_occupied_bed_nights
    FROM reservasi r
    JOIN detail_reservasi dr ON r.id_reservasi = dr.id_reservasi
    WHERE YEAR(r.tanggal_reservasi) = p_year 
      AND MONTH(r.tanggal_reservasi) = p_month
      AND r.status_reservasi IN ('Confirmed', 'Checked In', 'Checked Out');
      
    IF v_occupied_bed_nights IS NULL THEN
        SET v_occupied_bed_nights = 0;
    END IF;

    -- 4. Calculate BOR
    IF v_total_capacity > 0 AND v_days_in_month > 0 THEN
        SET v_bor = (v_occupied_bed_nights / (v_total_capacity * v_days_in_month)) * 100;
    ELSE
        SET v_bor = 0;
    END IF;

    RETURN v_bor;
END //

DELIMITER ;
-- ==========================================
-- STORED PROCEDURES
-- ==========================================
DELIMITER //

CREATE PROCEDURE sp_staff_insert(
    IN p_username VARCHAR(32),
    IN p_password_hash VARCHAR(255),
    IN p_email VARCHAR(50),
    IN p_nama_staff VARCHAR(100),
    IN p_posisi ENUM('Manager', 'Staff'),
    IN p_status_akun ENUM('Active', 'Not Active'),
    IN p_user VARCHAR(32)
)
BEGIN
    DECLARE next_id VARCHAR(6);

    SET @current_conn_user = p_user;
    SET next_id = GenerateStaffID();

    INSERT INTO staff (staff_id, username, password_hash, email, nama_staff, posisi, status_akun)
    VALUES (next_id, p_username, p_password_hash, p_email, p_nama_staff, p_posisi, p_status_akun);
END //

CREATE PROCEDURE sp_staff_update (
    IN p_staff_id VARCHAR(6),
    IN p_username VARCHAR(32),
    IN p_password_hash VARCHAR(255),
    IN p_email VARCHAR(50),
    IN p_nama_staff VARCHAR(100),
    IN p_posisi ENUM('Manager', 'Staff'),
    IN p_status_akun ENUM('Active', 'Not Active'),
    IN p_user VARCHAR(32)
)
BEGIN
    SET @current_conn_user = p_user;

    UPDATE staff 
    SET username = p_username,
        password_hash = p_password_hash,
        email = p_email,
        nama_staff = p_nama_staff,
        posisi = p_posisi,
        status_akun = p_status_akun
    WHERE staff_id = p_staff_id;
END //

CREATE PROCEDURE sp_staff_delete (
    IN p_staff_id VARCHAR(6),
    IN p_user VARCHAR(32)
)
BEGIN
    DECLARE v_username VARCHAR(32);
    DECLARE activity_count INT;

    SET @current_conn_user = p_user;

    SELECT username INTO v_username FROM staff WHERE staff_id = p_staff_id;

    SELECT COUNT(*) INTO activity_count 
    FROM log_staff_activity 
    WHERE user_staff = v_username;

    IF activity_count = 0 THEN
        DELETE FROM staff WHERE staff_id = p_staff_id;
    END IF;
END //

CREATE PROCEDURE sp_maintenance_insert (
    IN p_nomor_kamar VARCHAR(4),
    IN p_tanggal_mulai DATE,
    IN p_tanggal_selesai DATE,
    IN p_deskripsi_maintenance TEXT,
    IN p_user VARCHAR(32)
)
BEGIN
    DECLARE new_id VARCHAR(13);

    SET @current_conn_user = p_user;
    SET new_id = GenerateMaintenanceID();

    INSERT INTO maintenance (id_maintenance, nomor_kamar, tanggal_mulai, tanggal_selesai, deskripsi_maintenance, status_maintenance)
    VALUES (new_id, p_nomor_kamar, p_tanggal_mulai, p_tanggal_selesai, p_deskripsi_maintenance, 'Scheduled');
END //

CREATE PROCEDURE sp_maintenance_update (
    IN p_id_maintenance VARCHAR(13),
    IN p_nomor_kamar VARCHAR(4),
    IN p_tanggal_mulai DATE,
    IN p_tanggal_selesai DATE,
    IN p_deskripsi_maintenance TEXT,
    IN p_status_maintenance ENUM('Scheduled', 'Completed'),
    IN p_user VARCHAR(32)
)
BEGIN
    SET @current_conn_user = p_user;
    UPDATE maintenance
    SET nomor_kamar = p_nomor_kamar,
        tanggal_mulai = p_tanggal_mulai,
        tanggal_selesai = p_tanggal_selesai,
        deskripsi_maintenance = p_deskripsi_maintenance,
        status_maintenance = p_status_maintenance
    WHERE id_maintenance = p_id_maintenance;
END //

CREATE PROCEDURE sp_maintenance_delete (
    IN p_id_maintenance VARCHAR(13),
    IN p_user VARCHAR(32)
)
BEGIN
    SET @current_conn_user = p_user;
    DELETE FROM maintenance WHERE id_maintenance = p_id_maintenance;
END //

CREATE PROCEDURE sp_reservasi_checkin (
    IN p_id_reservasi VARCHAR(13),
    IN p_user VARCHAR(32)
)
BEGIN
    SET @current_conn_user = p_user;
    UPDATE reservasi 
    SET status_reservasi = 'Checked In' 
    WHERE id_reservasi = p_id_reservasi;
END //

CREATE PROCEDURE sp_reservasi_checkout (
    IN p_id_reservasi VARCHAR(13),
    IN p_user VARCHAR(32)
)
BEGIN
    SET @current_conn_user = p_user;
    UPDATE reservasi 
    SET status_reservasi = 'Checked Out' 
    WHERE id_reservasi = p_id_reservasi;
END //

CREATE PROCEDURE sp_reservasi_cancel (
    IN p_id_reservasi VARCHAR(13),
    IN p_user VARCHAR(32)
)
BEGIN
    SET @current_conn_user = p_user;
    UPDATE reservasi 
    SET status_reservasi = 'Cancelled' 
    WHERE id_reservasi = p_id_reservasi;
END //

DELIMITER ;
-- ==========================================
-- TRIGGERS
-- ==========================================
DELIMITER //
CREATE TRIGGER trg_log_insert_staff
AFTER INSERT ON staff
FOR EACH ROW
BEGIN
    IF @current_conn_user is NULL THEN
        SET @current_conn_user = 'SYSTEM';
    END IF;
    INSERT INTO log_staff_activity (user_staff, activity_desc)
    VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('Staff account created for ', NEW.staff_id, " with username ", NEW.username, " and email ", NEW.email));
END //

CREATE TRIGGER trg_log_update_staff
AFTER UPDATE ON staff
FOR EACH ROW
BEGIN
    INSERT INTO log_staff_activity (user_staff, activity_desc)
    VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('Staff account updated for ', OLD.staff_id, " with username ", OLD.username, " and email ", OLD.email, " to ", NEW.username, " and ", NEW.email, " respectively"));
END //

CREATE TRIGGER trg_log_delete_staff
AFTER DELETE ON staff
FOR EACH ROW
BEGIN
    INSERT INTO log_staff_activity (user_staff, activity_desc)
    VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('Staff account deleted for ', OLD.staff_id, " with username ", OLD.username, " and email ", OLD.email));
END //

CREATE TRIGGER trg_log_insert_maintenance
AFTER INSERT ON maintenance
FOR EACH ROW
BEGIN
    INSERT INTO log_staff_activity (user_staff, activity_desc)
    VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('Maintenance scheduled for room ', NEW.nomor_kamar, ' (', NEW.id_maintenance, ')'));
END //

CREATE TRIGGER trg_log_update_maintenance
AFTER UPDATE ON maintenance
FOR EACH ROW
BEGIN
    INSERT INTO log_staff_activity (user_staff, activity_desc)
    VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('Maintenance updated for ', NEW.id_maintenance, ' room ', NEW.nomor_kamar));
END //

CREATE TRIGGER trg_log_delete_maintenance
AFTER DELETE ON maintenance
FOR EACH ROW
BEGIN
    INSERT INTO log_staff_activity (user_staff, activity_desc)
    VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('Maintenance deleted for ', OLD.id_maintenance, ' room ', OLD.nomor_kamar));
END //

CREATE TRIGGER trg_log_insert_reservasi
AFTER INSERT ON reservasi
FOR EACH ROW
BEGIN
    INSERT INTO log_staff_activity (user_staff, activity_desc)
    VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('New reservation ', NEW.id_reservasi, ' created for ', NEW.nama_tamu));
END //

CREATE TRIGGER trg_log_update_reservasi
AFTER UPDATE ON reservasi
FOR EACH ROW
BEGIN
    IF NEW.status_reservasi = 'Cancelled' AND OLD.status_reservasi != 'Cancelled' THEN
        INSERT INTO log_staff_activity (user_staff, activity_desc)
        VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('Reservation ', NEW.id_reservasi, ' cancelled'));
    ELSEIF NEW.status_reservasi = 'Checked In' AND OLD.status_reservasi != 'Checked In' THEN
        INSERT INTO log_staff_activity (user_staff, activity_desc)
        VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('Reservation ', NEW.id_reservasi, ' checked in'));
    ELSEIF NEW.status_reservasi = 'Checked Out' AND OLD.status_reservasi != 'Checked Out' THEN
        INSERT INTO log_staff_activity (user_staff, activity_desc)
        VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('Reservation ', NEW.id_reservasi, ' checked out'));
    ELSEIF NEW.status_reservasi = 'Confirmed' AND OLD.status_reservasi != 'Confirmed' THEN
        INSERT INTO log_staff_activity (user_staff, activity_desc)
        VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('Reservation ', NEW.id_reservasi, ' confirmed'));
    END IF;
END //

CREATE TRIGGER trg_log_insert_transaksi
AFTER INSERT ON transaksi
FOR EACH ROW
BEGIN
    INSERT INTO log_staff_activity (user_staff, activity_desc)
    VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('New transaction ', NEW.id_transaksi, ' created for reservation ', NEW.id_reservasi));
END //

CREATE TRIGGER trg_log_update_transaksi
AFTER UPDATE ON transaksi
FOR EACH ROW
BEGIN
    IF NEW.status_transaksi != OLD.status_transaksi THEN
        INSERT INTO log_staff_activity (user_staff, activity_desc)
        VALUES (CAST(COALESCE(@current_conn_user, 'SYSTEM') AS VARCHAR(32)), CONCAT('Transaction ', NEW.id_transaksi, ' status changed to ', NEW.status_transaksi));
    END IF;
END //
DELIMITER ;

-- ==========================================
-- VIEWS
-- ==========================================
CREATE OR REPLACE VIEW ViewRoomOccupancyRateBulanan AS
SELECT
    DATE_FORMAT(r.tanggal_reservasi, '%Y-%M') AS Periode,
    CalculateMonthlyBOR(YEAR(r.tanggal_reservasi), MONTH(r.tanggal_reservasi)) AS Bed_Occupancy_Rate
FROM reservasi r
WHERE r.status_reservasi IN ('Confirmed', 'Checked In', 'Checked Out')
GROUP BY DATE_FORMAT(r.tanggal_reservasi, '%Y-%M'), YEAR(r.tanggal_reservasi), MONTH(r.tanggal_reservasi)
ORDER BY YEAR(r.tanggal_reservasi) DESC, MONTH(r.tanggal_reservasi) DESC;

CREATE OR REPLACE VIEW ViewPendapatanBulanan AS
SELECT
    DATE_FORMAT(t.tanggal_transaksi, '%Y-%M') AS Periode,
    SUM(CASE WHEN t.tipe_transaksi = 'Reservasi' OR t.tipe_transaksi = 'Biaya Tambahan' THEN t.jumlah_transaksi ELSE 0 END) AS Total_Pendapatan,
    COUNT(t.id_transaksi) AS Jumlah_Transaksi
FROM transaksi t
WHERE t.status_transaksi = 'Dibayar' AND t.tipe_transaksi != 'Refund'
GROUP BY DATE_FORMAT(t.tanggal_transaksi, '%Y-%M')
ORDER BY Periode DESC;

CREATE OR REPLACE VIEW ViewTipeKamarPopulerBulanan AS
SELECT
    DATE_FORMAT(r.tanggal_reservasi, '%Y-%M') AS periode_pemesanan,
    tk.nama_tipe_kamar AS tipe_kamar,
    COUNT(dr.nomor_kamar) AS jumlah_pemesanan
FROM detail_reservasi dr
JOIN kamar k ON dr.nomor_kamar = k.nomor_kamar
JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id_tipe_kamar
JOIN reservasi r ON dr.id_reservasi = r.id_reservasi
WHERE r.status_reservasi IN ('Confirmed', 'Checked In', 'Checked Out')
GROUP BY DATE_FORMAT(r.tanggal_reservasi, '%Y-%M'), YEAR(r.tanggal_reservasi), MONTH(r.tanggal_reservasi), tk.nama_tipe_kamar
ORDER BY YEAR(r.tanggal_reservasi) DESC, MONTH(r.tanggal_reservasi) DESC, jumlah_pemesanan DESC;

CREATE OR REPLACE VIEW ViewJadwalCheckinHariIni AS
SELECT
    r.id_reservasi AS "ID Reservasi",
    dr.nomor_kamar AS "Nomor Kamar",
    r.nama_tamu AS "Nama Tamu",
    tk.nama_tipe_kamar AS "Tipe Kamar"
FROM reservasi r
JOIN detail_reservasi dr ON r.id_reservasi = dr.id_reservasi
JOIN kamar k ON dr.nomor_kamar = k.nomor_kamar
JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id_tipe_kamar
WHERE r.tanggal_checkin = CURDATE() AND r.status_reservasi = 'Confirmed';

CREATE OR REPLACE VIEW ViewJadwalCheckoutHariIni AS
SELECT
    r.id_reservasi AS "ID Reservasi",
    dr.nomor_kamar AS "Nomor Kamar",
    r.nama_tamu AS "Nama Tamu",
    tk.nama_tipe_kamar AS "Tipe Kamar"
FROM reservasi r
JOIN detail_reservasi dr ON r.id_reservasi = dr.id_reservasi
JOIN kamar k ON dr.nomor_kamar = k.nomor_kamar
JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id_tipe_kamar
WHERE r.tanggal_checkout = CURDATE() AND r.status_reservasi = 'Checked In';

CREATE OR REPLACE VIEW ViewKamarDalamMaintenanceHariIni AS
SELECT
    m.id_maintenance AS "ID Maintenance",
    m.nomor_kamar AS "Nomor Kamar",
    tk.nama_tipe_kamar AS "Tipe Kamar",
    m.deskripsi_maintenance AS "Deskripsi Maintenance",
    m.status_maintenance AS "Status Maintenance"
FROM maintenance m
JOIN kamar k ON m.nomor_kamar = k.nomor_kamar
JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id_tipe_kamar
WHERE CURDATE() BETWEEN m.tanggal_mulai AND m.tanggal_selesai;

CREATE OR REPLACE VIEW ViewPerformaPromo AS
SELECT
    p.kode_promo,
    p.desc_promo,
    COUNT(r.id_reservasi) AS jumlah_pemakaian,
    COALESCE(SUM(r.total_harga), 0) AS total_pendapatan
FROM promo p
LEFT JOIN reservasi r ON p.kode_promo = r.kode_promo AND r.status_reservasi IN ('Confirmed', 'Checked In', 'Checked Out')
GROUP BY p.kode_promo, p.desc_promo;

CREATE OR REPLACE VIEW ViewReservasiList AS
SELECT
    r.id_reservasi,
    r.kode_promo,
    r.nama_tamu,
    r.no_hp,
    r.email,
    r.tanggal_reservasi,
    r.tanggal_checkin,
    r.tanggal_checkout,
    r.total_harga,
    r.status_reservasi,
    r.metode_pembayaran,
    GROUP_CONCAT(DISTINCT dr.nomor_kamar ORDER BY dr.nomor_kamar SEPARATOR ', ') AS nomor_kamar,
    GROUP_CONCAT(DISTINCT tk.nama_tipe_kamar ORDER BY tk.nama_tipe_kamar SEPARATOR ', ') AS nama_tipe_kamar
FROM reservasi r
LEFT JOIN detail_reservasi dr ON r.id_reservasi = dr.id_reservasi
LEFT JOIN kamar k ON dr.nomor_kamar = k.nomor_kamar
LEFT JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id_tipe_kamar
GROUP BY r.id_reservasi
ORDER BY r.tanggal_reservasi DESC;

CREATE OR REPLACE VIEW ViewKamarList AS
SELECT
    k.nomor_kamar,
    tk.nama_tipe_kamar,
    tk.desc_tipe_kamar,
    tk.harga_dasar,
    tk.kapasitas_maksimum,
    (tk.harga_dasar / NULLIF(tk.kapasitas_maksimum, 0)) AS harga_per_orang
FROM kamar k
JOIN tipe_kamar tk ON k.id_tipe_kamar = tk.id_tipe_kamar;

-- =============================================
-- Data Initialization Staff
-- =============================================
-- Staff (Using your 13XXXX format)
INSERT INTO staff (staff_id, username, password_hash, email, nama_staff, posisi, status_akun) VALUES
-- eko pwd = Manager#1234, siti pwd = Staff#1234
('130001', 'eko', '$2y$10$izDbv2rB5fMmfrm/7SzmbeP0uRX3ZhffauggIVM.ifRxG6jXMGZzu', 'eko@hotel.com', 'Eko Susilo', 'Manager', 'Active'),
('130002', 'siti', '$2y$10$RRSmxEa0o4vDCKaV0Rxc0.l/X6mvr6/JwCahVhk0S66iTykFvb4i2', 'siti@hotel.com', 'Siti Aminah', 'Staff', 'Active'),
('130003', 'eko_inactive', '$2y$10$izDbv2rB5fMmfrm/7SzmbeP0uRX3ZhffauggIVM.ifRxG6jXMGZzu', 'eko@hotel_inactive.com', 'Eko Susilo', 'Manager', 'Not Active'),
('130004', 'siti_inactive', '$2y$10$RRSmxEa0o4vDCKaV0Rxc0.l/X6mvr6/JwCahVhk0S66iTykFvb4i2', 'siti@hotel_inactive.com', 'Siti Aminah', 'Staff', 'Not Active');

-- 2. TIPE KAMAR
INSERT INTO tipe_kamar (id_tipe_kamar, nama_tipe_kamar, desc_tipe_kamar, path_gambar, harga_dasar, kapasitas_maksimum) VALUES
('ST', 'Standard Room', 'Kamar standar yang nyaman untuk istirahat.', NULL, 500000.00, 2),
('DL', 'Deluxe Room', 'Kamar deluxe dengan pemandangan kota.', NULL, 1000000.00, 2),
('SU', 'Suite Room', 'Kamar suite mewah dengan ruang tamu terpisah.', NULL, 2000000.00, 4),
('FM', 'Family Room', 'Kamar luas cocok untuk keluarga kecil.', NULL, 1500000.00, 3);

-- 3. KAMAR (10 Data - Untuk mencegah bentrok reservasi)
INSERT INTO kamar (nomor_kamar, id_tipe_kamar) VALUES
('101', 'ST'), ('102', 'ST'), ('103', 'ST'), ('104', 'ST'),
('201', 'DL'), ('202', 'DL'), ('203', 'DL'),
('301', 'SU'), ('302', 'SU'),
('401', 'FM');

-- 4. PROMO (3 Data)
INSERT INTO promo (kode_promo, desc_promo, besar_diskon, tanggal_berlaku, tanggal_kedaluwarsa) VALUES
('NEWYEAR26', 'Diskon Spesial Tahun Baru 2026', 10.00, '2025-12-25', '2026-01-31'),
('WEEKEND', 'Diskon Akhir Pekan', 5.00, '2026-01-01', '2026-12-31'),
('FLASH26', 'Flash Sale Januari', 20.00, '2026-01-15', '2026-01-20');

-- 5. MAINTENANCE (5 Data)
-- M-20260118001: Sedang Maintenance HARI INI (ViewKamarDalamMaintenanceHariIni)
-- M-20260118002: Sedang Maintenance HARI INI (ViewKamarDalamMaintenanceHariIni)
INSERT INTO maintenance (id_maintenance, nomor_kamar, tanggal_mulai, tanggal_selesai, deskripsi_maintenance, status_maintenance) VALUES
('M-20251201001', '101', '2025-12-01', '2025-12-03', 'Perbaikan Pipa Air', 'Completed'),
('M-20260118001', '103', '2026-01-18', '2026-01-20', 'Perbaikan AC Bocor', 'Scheduled'), 
('M-20260118002', '203', '2026-01-19', '2026-01-21', 'Penggantian Wallpaper', 'Scheduled'),
('M-20260125001', '302', '2026-01-25', '2026-01-26', 'Deep Cleaning', 'Scheduled'),
('M-20260201001', '401', '2026-02-01', '2026-02-05', 'Renovasi Kamar Mandi', 'Scheduled');

-- 6. RESERVASI (10 Data - Mencakup berbagai status dan tanggal)
-- Mengasumsikan hari ini: 19 Januari 2026

-- A. Historical Data (Desember 2025) - Untuk ViewRoomOccupancyRateBulanan & Pendapatan
INSERT INTO reservasi VALUES
('R-20251220001', NULL, 'Budi Santoso', '081111', 'budi@mail.com', '2025-12-20 10:00:00', '2025-12-24', '2025-12-26', 1000000.00, 'Checked Out', 'Transfer'),
('R-20251225001', 'NEWYEAR26', 'Sarah Lee', '082222', 'sarah@mail.com', '2025-12-25 12:00:00', '2025-12-30', '2026-01-02', 3600000.00, 'Checked Out', 'Kredit');

-- B. Checkout Hari Ini (19 Jan 2026) - Untuk ViewJadwalCheckoutHariIni
INSERT INTO reservasi VALUES
('R-20260115001', 'NEWYEAR26', 'Ani Wijaya', '083333', 'ani@mail.com', '2026-01-10 14:00:00', '2026-01-15', '2026-01-19', 1800000.00, 'Checked In', 'Kredit'),
('R-20260116001', NULL, 'Joko Anwar', '084444', 'joko@mail.com', '2026-01-16 09:00:00', '2026-01-16', '2026-01-19', 1500000.00, 'Checked In', 'Cash');

-- C. Checkin Hari Ini (19 Jan 2026) - Untuk ViewJadwalCheckinHariIni
INSERT INTO reservasi VALUES
('R-20260118001', NULL, 'Citra Lestari', '085555', 'citra@mail.com', '2026-01-18 09:00:00', '2026-01-19', '2026-01-21', 2000000.00, 'Confirmed', 'Debit'),
('R-20260118002', 'WEEKEND', 'Dedi Corbuz', '086666', 'dedi@mail.com', '2026-01-18 10:00:00', '2026-01-19', '2026-01-20', 475000.00, 'Confirmed', 'Transfer');

-- D. Sedang Menginap (Overlap Hari Ini) - Untuk Occupancy Rate
INSERT INTO reservasi VALUES
('R-20260117001', NULL, 'Doni Pratama', '087777', 'doni@mail.com', '2026-01-17 11:00:00', '2026-01-17', '2026-01-22', 5000000.00, 'Checked In', 'Cash');

-- E. Future Reservation
INSERT INTO reservasi VALUES
('R-20260119003', NULL, 'Rina Nose', '088888', 'rina@mail.com', '2026-01-19 15:00:00', '2026-02-01', '2026-02-03', 1000000.00, 'Pending', NULL);

-- F. Cancelled Reservation
INSERT INTO reservasi VALUES
('R-20260105001', NULL, 'Batal Man', '089999', 'batal@mail.com', '2026-01-05 08:00:00', '2026-01-10', '2026-01-12', 1000000.00, 'Cancelled', 'Transfer');

-- 7. DETAIL RESERVASI
INSERT INTO detail_reservasi (id_reservasi, nomor_kamar, jumlah_tamu) VALUES
('R-20251220001', '101', 2), -- Budi
('R-20251225001', '201', 2), -- Sarah
('R-20260115001', '201', 2), -- Ani (Checkout Hari ini)
('R-20260116001', '102', 1), -- Joko (Checkout Hari ini)
('R-20260118001', '202', 2), -- Citra (Checkin Hari ini)
('R-20260118002', '104', 2), -- Dedi (Checkin Hari ini)
('R-20260117001', '301', 4), -- Doni (Stay)
('R-20260119003', '101', 2), -- Rina (Future)
('R-20260105001', '101', 1); -- Batal

-- 8. TRANSAKSI (10 Data - Untuk ViewPendapatanBulanan)
INSERT INTO transaksi (id_transaksi, ref_number, id_reservasi, tipe_transaksi, tipe_pembayaran, jumlah_transaksi, tanggal_transaksi, status_transaksi) VALUES
-- Desember Revenue
('T-20251220001', 'REF001', 'R-20251220001', 'Reservasi', 'Transfer', 1000000.00, '2025-12-20 10:05:00', 'Dibayar'),
('T-20251225001', 'REF002', 'R-20251225001', 'Reservasi', 'Kredit', 3600000.00, '2025-12-25 12:05:00', 'Dibayar'),
-- Januari Revenue
('T-20260110001', 'REF003', 'R-20260115001', 'Reservasi', 'Kredit', 1800000.00, '2026-01-10 14:05:00', 'Dibayar'),
('T-20260116001', 'REF004', 'R-20260116001', 'Reservasi', 'Cash', 1500000.00, '2026-01-16 09:05:00', 'Dibayar'),
('T-20260118001', 'REF005', 'R-20260118001', 'Reservasi', 'Debit', 2000000.00, '2026-01-18 09:05:00', 'Dibayar'),
('T-20260118002', 'REF006', 'R-20260118002', 'Reservasi', 'Transfer', 475000.00, '2026-01-18 10:05:00', 'Dibayar'),
('T-20260117001', 'REF007', 'R-20260117001', 'Reservasi', 'Cash', 5000000.00, '2026-01-17 11:05:00', 'Dibayar'),
-- Biaya Tambahan (Extra Charge)
('T-20260119001', 'REF008', 'R-20260115001', 'Biaya Tambahan', 'Cash', 150000.00, '2026-01-19 08:00:00', 'Dibayar'), -- Ani beli makanan
('T-20260119002', 'REF009', 'R-20260116001', 'Biaya Tambahan', 'Cash', 50000.00, '2026-01-19 08:30:00', 'Dibayar'),  -- Joko laundry
('T-20260105001', 'REF010', 'R-20260105001', 'Reservasi', 'Transfer', 1000000.00, '2026-01-05 08:05:00', 'Expired'); -- Transaksi gagal/expired