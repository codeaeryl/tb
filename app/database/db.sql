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
    FOREIGN KEY (kode_promo) REFERENCES promo(kode_promo)
);

CREATE TABLE maintenance (
    -- format: M-YYYYMMDDXXXX (XXXX is a 4-digit number)
    id_maintenance VARCHAR(13) PRIMARY KEY,
    nomor_kamar VARCHAR(4) NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    deskripsi_maintenance TEXT NOT NULL,
    status_maintenance ENUM('Scheduled', 'In Progress', 'Completed') NOT NULL,
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

CREATE TABLE diskon_reservasi_awal (
    -- jumlah_hari: number of days in advance to book
    jumlah_hari INT PRIMARY KEY,
    -- besar_diskon: discount percentage
    besar_diskon DECIMAL(5,2) NOT NULL
);

CREATE TABLE transaksi (
    -- format: T-YYYYMMDDXXXX (XXXX is a 4-digit number)
    id_transaksi VARCHAR(13) PRIMARY KEY,
    id_reservasi VARCHAR(13) NOT NULL,
    tipe_transaksi ENUM('Reservasi', 'Biaya Tambahan') NOT NULL,
    tipe_pembayaran ENUM('Kredit', 'Debit', 'Transfer', 'QRIS') NOT NULL,
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

-- =============================================
-- Data Initialization
-- =============================================
-- Staff (Using your 13XXXX format)
INSERT INTO staff (staff_id, username, password_hash, email, nama_staff, posisi, status_akun) VALUES
-- eko pwd = Manager#1234, siti pwd = Staff#1234
                                                                                                ('130001', 'eko', '$2y$10$izDbv2rB5fMmfrm/7SzmbeP0uRX3ZhffauggIVM.ifRxG6jXMGZzu', 'eko@hotel.com', 'Eko Susilo', 'Manager', 'Active'),
                                                                                                ('130002', 'siti', '$2y$10$RRSmxEa0o4vDCKaV0Rxc0.l/X6mvr6/JwCahVhk0S66iTykFvb4i2', 'siti@hotel.com', 'Siti Aminah', 'Staff', 'Active');

-- ==========================================
-- VIEWS
-- ==========================================
DELIMITER //

CREATE FUNCTION fn_get_next_staff_id() 
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

DELIMITER ;

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
    SET next_id = fn_get_next_staff_id();

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

DELIMITER ;

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
DELIMITER ;