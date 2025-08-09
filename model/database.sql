
-- DATABASE HOÀN CHỈNH CHO SHOP ĐIỆN LẠNH

-- Tạo database
DROP DATABASE IF EXISTS dienlanh_shop;
CREATE DATABASE dienlanh_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE dienlanh_shop;

-- =====================================================
-- TẠO CÁC BẢNG
-- =====================================================

-- Bảng danh mục sản phẩm
CREATE TABLE danhmuc (
  id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng hãng sản xuất
CREATE TABLE hang (
  id_hang INT AUTO_INCREMENT PRIMARY KEY,
    ten_hang VARCHAR(100) NOT NULL UNIQUE,
    logo_hang VARCHAR(250) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng sản phẩm
CREATE TABLE sanpham (
  id_sp INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(250) NOT NULL,
  Price INT NOT NULL,
  Price_old INT DEFAULT NULL,
  Date_import DATETIME NOT NULL,
  Viewsp INT DEFAULT 0,
  Decribe LONGTEXT DEFAULT NULL,
    Mount INT NOT NULL DEFAULT 0,
  Sale INT DEFAULT 0,
  image VARCHAR(250) DEFAULT NULL,
  id_danhmuc INT NOT NULL,
  id_hang INT NOT NULL,
    rating_trung_binh DECIMAL(3,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_danhmuc) REFERENCES danhmuc(id) ON DELETE CASCADE,
    FOREIGN KEY (id_hang) REFERENCES hang(id_hang) ON DELETE CASCADE,
    INDEX idx_danhmuc (id_danhmuc),
    INDEX idx_hang (id_hang),
    INDEX idx_price (Price),
    INDEX idx_sale (Sale)
);

-- Bảng tài khoản người dùng
CREATE TABLE taikhoan (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  fullname VARCHAR(250) NOT NULL,
    email VARCHAR(250) NOT NULL UNIQUE,
  phone VARCHAR(20) NOT NULL,
  address VARCHAR(250) NOT NULL,
  position VARCHAR(12) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_position (position)
);

-- Bảng đơn hàng
CREATE TABLE donhang (
  id_dh INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  tongdh INT NOT NULL,
  ngaydat DATETIME NOT NULL,
  trangthai VARCHAR(30) DEFAULT 'Chờ xác nhận',
  ten_nguoi_nhan VARCHAR(250) NOT NULL,
  sdt_nguoi_nhan VARCHAR(20) NOT NULL,
  dia_chi_giao TEXT NOT NULL,
  ghi_chu TEXT,
  phuong_thuc_thanh_toan VARCHAR(50) DEFAULT 'Tiền mặt',
    ngay_cap_nhat TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES taikhoan(id_user) ON DELETE CASCADE,
    INDEX idx_user (id_user),
    INDEX idx_trangthai (trangthai),
    INDEX idx_ngaydat (ngaydat)
);

-- Bảng chi tiết đơn hàng
CREATE TABLE dh_chitiet (
  id_chitiet INT AUTO_INCREMENT PRIMARY KEY,
  id_sp INT NOT NULL,
  id_dh INT NOT NULL,
  soluong INT NOT NULL,
  tong_dh INT NOT NULL,
  gia_ban INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_sp) REFERENCES sanpham(id_sp) ON DELETE CASCADE,
    FOREIGN KEY (id_dh) REFERENCES donhang(id_dh) ON DELETE CASCADE,
    INDEX idx_sp (id_sp),
    INDEX idx_dh (id_dh)
);

-- Bảng lịch sử trạng thái đơn hàng
CREATE TABLE lich_su_trang_thai (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_dh INT NOT NULL,
  trang_thai_cu VARCHAR(30),
  trang_thai_moi VARCHAR(30) NOT NULL,
  ghi_chu TEXT,
    ngay_cap_nhat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  nguoi_cap_nhat INT,
  FOREIGN KEY (id_dh) REFERENCES donhang(id_dh) ON DELETE CASCADE,
    FOREIGN KEY (nguoi_cap_nhat) REFERENCES taikhoan(id_user),
    INDEX idx_dh (id_dh),
    INDEX idx_ngay_cap_nhat (ngay_cap_nhat)
);

-- Bảng đánh giá sản phẩm
CREATE TABLE danh_gia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_sp INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    ngay_danh_gia TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ngay_cap_nhat TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES taikhoan(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_sp) REFERENCES sanpham(id_sp) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (id_user, id_sp),
    INDEX idx_sp (id_sp),
    INDEX idx_user (id_user),
    INDEX idx_rating (rating)
);

-- Bảng wishlist (danh sách yêu thích)
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_sp INT NOT NULL,
    ngay_them TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES taikhoan(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_sp) REFERENCES sanpham(id_sp) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (id_user, id_sp),
    INDEX idx_user (id_user),
    INDEX idx_sp (id_sp)
);

-- =====================================================
-- THÊM DỮ LIỆU MẪU
-- =====================================================

-- Thêm danh mục sản phẩm
INSERT INTO danhmuc (name) VALUES 
('Máy lạnh treo tường'),
('Máy lạnh âm trần'),
('Máy lạnh đứng'),
('Máy lạnh giấu trần'),
('Tủ lạnh');

-- Thêm hãng sản xuất
INSERT INTO hang (ten_hang, logo_hang) VALUES 
('Daikin', 'logoDaikin.jpg'),
('Panasonic', 'logoPanasonic.jpg'),
('LG', 'logoLG.jpg'),
('Toshiba', 'logoToshiba.jpg'),
('Mitsubishi', 'logoMitsubishi.jpg'),
('Samsung', 'logosamsung.jpg');

-- Thêm tài khoản admin và user mẫu
INSERT INTO taikhoan (username, password, fullname, email, phone, address, position) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@dienlanh.com', '0123456789', '123 Đường ABC, Quận 1, TP.HCM', 'admin'),
('user1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn A', 'user1@email.com', '0987654321', '456 Đường XYZ, Quận 2, TP.HCM', 'user'),
('user2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trần Thị B', 'user2@email.com', '0123456788', '789 Đường DEF, Quận 3, TP.HCM', 'user');

-- =====================================================
-- DỮ LIỆU SẢN PHẨM MẪU
-- =====================================================

-- Máy lạnh treo tường (id_danhmuc = 1)
-- Daikin
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Daikin FTKA25UAVMV', 8500000, 9500000, NOW(), 50, 'Máy lạnh Daikin 1.0Hp inverter, tiết kiệm điện, làm lạnh nhanh.', 10, 10, 'sanPhamDaikin/MaylanhtreotuongDaikin/FTKA25UAVMV.png', 1, 1),
('Máy lạnh Daikin FTKA35UAVMV', 9500000, 10500000, NOW(), 48, 'Máy lạnh Daikin 1.5Hp inverter, công nghệ Inverter tiên tiến.', 10, 11, 'sanPhamDaikin/MaylanhtreotuongDaikin/FTKA35UAVMV.png', 1, 1),
('Máy lạnh Daikin FTKA50UAVMV', 12500000, 13500000, NOW(), 46, 'Máy lạnh Daikin 2.0Hp inverter, phù hợp phòng lớn.', 10, 12, 'sanPhamDaikin/MaylanhtreotuongDaikin/FTKA50UAVMV.png', 1, 1),
('Máy lạnh Daikin FTKA60UAVMV', 14500000, 15500000, NOW(), 44, 'Máy lạnh Daikin 2.5Hp inverter, làm lạnh mạnh mẽ.', 10, 13, 'sanPhamDaikin/MaylanhtreotuongDaikin/FTKA60UAVMV.png', 1, 1),
('Máy lạnh Daikin FTKA71UAVMV', 17500000, 18500000, NOW(), 42, 'Máy lạnh Daikin 3.0Hp inverter, cho không gian rộng.', 10, 14, 'sanPhamDaikin/MaylanhtreotuongDaikin/FTKA71UAVMV.png', 1, 1);

-- Panasonic
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Panasonic CU/CS-XPU9WKH-8', 9000000, 10000000, NOW(), 40, 'Máy lạnh Panasonic 1.0Hp inverter, công nghệ nanoe-G.', 10, 10, 'sanPhamPanasonic/MaylanhtreotuongPanasonic/CU-CS-XPU9WKH-8.png', 1, 2),
('Máy lạnh Panasonic CU/CS-XPU12WKH-8', 10500000, 11500000, NOW(), 38, 'Máy lạnh Panasonic 1.5Hp inverter, làm sạch không khí.', 10, 11, 'sanPhamPanasonic/MaylanhtreotuongPanasonic/CU-CS-XPU12WKH-8.png', 1, 2),
('Máy lạnh Panasonic CU/CS-XPU18WKH-8', 14500000, 15500000, NOW(), 36, 'Máy lạnh Panasonic 2.0Hp inverter, tiết kiệm điện.', 10, 12, 'sanPhamPanasonic/MaylanhtreotuongPanasonic/CU-CS-XPU18WKH-8.png', 1, 2),
('Máy lạnh Panasonic CU/CS-XPU24WKH-8', 17500000, 18500000, NOW(), 34, 'Máy lạnh Panasonic 2.5Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamPanasonic/MaylanhtreotuongPanasonic/CU-CS-XPU24WKH-8.png', 1, 2),
('Máy lạnh Panasonic CU/CS-XPU28WKH-8', 20500000, 21500000, NOW(), 32, 'Máy lạnh Panasonic 3.0Hp inverter, công suất cao.', 10, 14, 'sanPhamPanasonic/MaylanhtreotuongPanasonic/CU-CS-XPU28WKH-8.png', 1, 2);

-- LG
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh LG V10APF', 8200000, 9200000, NOW(), 30, 'Máy lạnh LG 1.0Hp inverter, thiết kế hiện đại.', 10, 10, 'sanPhamLG/MaylanhtreotuongLG/LG-V10APF.png', 1, 3),
('Máy lạnh LG V13APF', 9500000, 10500000, NOW(), 28, 'Máy lạnh LG 1.5Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamLG/MaylanhtreotuongLG/LG-V13APF.png', 1, 3),
('Máy lạnh LG V18APF', 13500000, 14500000, NOW(), 26, 'Máy lạnh LG 2.0Hp inverter, làm lạnh nhanh.', 10, 12, 'sanPhamLG/MaylanhtreotuongLG/LG-V18APF.png', 1, 3),
('Máy lạnh LG V24APF', 16500000, 17500000, NOW(), 24, 'Máy lạnh LG 2.5Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamLG/MaylanhtreotuongLG/LG-V24APF.png', 1, 3),
('Máy lạnh LG V27APF', 19500000, 20500000, NOW(), 22, 'Máy lạnh LG 3.0Hp inverter, công suất cao.', 10, 14, 'sanPhamLG/MaylanhtreotuongLG/LG-V27APF.png', 1, 3);

-- Toshiba
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Toshiba RAS-H10C4KCVG-V', 8300000, 9300000, NOW(), 20, 'Máy lạnh Toshiba 1.0Hp inverter, tiết kiệm điện.', 10, 10, 'sanPhamToshiba/MaylanhtreotuongToshiba/RAS-H10C4KCVG-V.png', 1, 4),
('Máy lạnh Toshiba RAS-H13C4KCVG-V', 9500000, 10500000, NOW(), 18, 'Máy lạnh Toshiba 1.5Hp inverter, làm lạnh nhanh.', 10, 11, 'sanPhamToshiba/MaylanhtreotuongToshiba/RAS-H13C4KCVG-V.png', 1, 4),
('Máy lạnh Toshiba RAS-H18C4KCVG-V', 13500000, 14500000, NOW(), 16, 'Máy lạnh Toshiba 2.0Hp inverter, phù hợp phòng lớn.', 10, 12, 'sanPhamToshiba/MaylanhtreotuongToshiba/RAS-H18C4KCVG-V.png', 1, 4),
('Máy lạnh Toshiba RAS-H24C4KCVG-V', 16500000, 17500000, NOW(), 14, 'Máy lạnh Toshiba 2.5Hp inverter, công suất cao.', 10, 13, 'sanPhamToshiba/MaylanhtreotuongToshiba/RAS-H24C4KCVG-V.png', 1, 4),
('Máy lạnh Toshiba RAS-H27C4KCVG-V', 19500000, 20500000, NOW(), 12, 'Máy lạnh Toshiba 3.0Hp inverter, tiết kiệm điện.', 10, 14, 'sanPhamToshiba/MaylanhtreotuongToshiba/RAS-H27C4KCVG-V.png', 1, 4);

-- Mitsubishi
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Mitsubishi SRK/SRC10YN-S5', 8700000, 9700000, NOW(), 10, 'Máy lạnh Mitsubishi 1.0Hp inverter, tiết kiệm điện.', 10, 10, 'sanPhamMitsubishi/MaylanhtreotuongMitsubishi/SRK-SRC10YN-S5.png', 1, 5),
('Máy lạnh Mitsubishi SRK/SRC13YN-S5', 9900000, 10900000, NOW(), 8, 'Máy lạnh Mitsubishi 1.5Hp inverter, làm lạnh nhanh.', 10, 11, 'sanPhamMitsubishi/MaylanhtreotuongMitsubishi/SRK-SRC13YN-S5.png', 1, 5),
('Máy lạnh Mitsubishi SRK/SRC18YN-S5', 13900000, 14900000, NOW(), 6, 'Máy lạnh Mitsubishi 2.0Hp inverter, phù hợp phòng lớn.', 10, 12, 'sanPhamMitsubishi/MaylanhtreotuongMitsubishi/SRK-SRC18YN-S5.png', 1, 5),
('Máy lạnh Mitsubishi SRK/SRC24YN-S5', 16900000, 17900000, NOW(), 4, 'Máy lạnh Mitsubishi 2.5Hp inverter, công suất cao.', 10, 13, 'sanPhamMitsubishi/MaylanhtreotuongMitsubishi/SRK-SRC24YN-S5.png', 1, 5),
('Máy lạnh Mitsubishi SRK/SRC27YN-S5', 19900000, 20900000, NOW(), 2, 'Máy lạnh Mitsubishi 3.0Hp inverter, tiết kiệm điện.', 10, 14, 'sanPhamMitsubishi/MaylanhtreotuongMitsubishi/SRK-SRC27YN-S5.png', 1, 5);

-- Samsung
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Samsung AR10TYHQBWKNSV', 8500000, 9500000, NOW(), 15, 'Máy lạnh Samsung 1.0Hp inverter, thiết kế hiện đại.', 10, 10, 'sanPhamSamsung/MaylanhtreotuongSamsung/AR10TYHQBWKNSV.png', 1, 6),
('Máy lạnh Samsung AR13TYHQBWKNSV', 9800000, 10800000, NOW(), 13, 'Máy lạnh Samsung 1.5Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamSamsung/MaylanhtreotuongSamsung/AR13TYHQBWKNSV.png', 1, 6),
('Máy lạnh Samsung AR18TYHQBWKNSV', 13800000, 14800000, NOW(), 11, 'Máy lạnh Samsung 2.0Hp inverter, làm lạnh nhanh.', 10, 12, 'sanPhamSamsung/MaylanhtreotuongSamsung/AR18TYHQBWKNSV.png', 1, 6),
('Máy lạnh Samsung AR24TYHQBWKNSV', 16800000, 17800000, NOW(), 9, 'Máy lạnh Samsung 2.5Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamSamsung/MaylanhtreotuongSamsung/AR24TYHQBWKNSV.png', 1, 6),
('Máy lạnh Samsung AR28TYHQBWKNSV', 19800000, 20800000, NOW(), 7, 'Máy lạnh Samsung 3.0Hp inverter, công suất cao.', 10, 14, 'sanPhamSamsung/MaylanhtreotuongSamsung/AR28TYHQBWKNSV.png', 1, 6);

-- Máy lạnh âm trần (id_danhmuc = 2)
-- Daikin
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần Daikin FCFC50DVM', 25000000, 28000000, NOW(), 60, 'Máy lạnh âm trần Daikin 2.0Hp inverter, thiết kế âm trần.', 10, 11, 'sanPhamDaikin/MaylanhamtranDaikin/FCFC50DVM.png', 2, 1),
('Máy lạnh âm trần Daikin FCFC60DVM', 27000000, 30000000, NOW(), 55, 'Máy lạnh âm trần Daikin 2.5Hp inverter, tiết kiệm điện.', 10, 10, 'sanPhamDaikin/MaylanhamtranDaikin/FCFC60DVM.png', 2, 1),
('Máy lạnh âm trần Daikin FCFC71DVM', 32000000, 35000000, NOW(), 50, 'Máy lạnh âm trần Daikin 3.0Hp inverter, làm lạnh nhanh.', 10, 9, 'sanPhamDaikin/MaylanhamtranDaikin/FCFC71DVM.png', 2, 1),
('Máy lạnh âm trần Daikin FCFC85DVM', 35000000, 38000000, NOW(), 45, 'Máy lạnh âm trần Daikin 3.5Hp inverter, phù hợp phòng lớn.', 10, 8, 'sanPhamDaikin/MaylanhamtranDaikin/FCFC85DVM.png', 2, 1),
('Máy lạnh âm trần Daikin FCFC100DVM', 39000000, 42000000, NOW(), 40, 'Máy lạnh âm trần Daikin 4.0Hp inverter, công suất cao.', 10, 7, 'sanPhamDaikin/MaylanhamtranDaikin/FCFC100DVM.png', 2, 1);

-- Panasonic
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần Panasonic S-21PU1H5B', 29150000, 30800000, NOW(), 38, 'Máy lạnh âm trần Panasonic 2.0Hp inverter, công nghệ nanoe-G.', 10, 11, 'sanPhamPanasonic/MaylanhamtranPanasonic/S-21PU1H5B.png', 2, 2),
('Máy lạnh âm trần Panasonic S-24PB3H5', 32500000, 34500000, NOW(), 36, 'Máy lạnh âm trần Panasonic 2.5Hp inverter, tiết kiệm điện.', 10, 10, 'sanPhamPanasonic/MaylanhamtranPanasonic/S-24PB3H5.png', 2, 2),
('Máy lạnh âm trần Panasonic S-30PU1H5B', 26850000, 28500000, NOW(), 34, 'Máy lạnh âm trần Panasonic 3.0Hp inverter, làm lạnh nhanh.', 10, 9, 'sanPhamPanasonic/MaylanhamtranPanasonic/S-30PU1H5B.png', 2, 2),
('Máy lạnh âm trần Panasonic S-36PU1H5B', 30500000, 32500000, NOW(), 32, 'Máy lạnh âm trần Panasonic 3.5Hp inverter, phù hợp phòng lớn.', 10, 8, 'sanPhamPanasonic/MaylanhamtranPanasonic/S-36PU1H5B.png', 2, 2),
('Máy lạnh âm trần Panasonic S-50PU1H5B', 35600000, 36500000, NOW(), 30, 'Máy lạnh âm trần Panasonic 5.0Hp inverter, công suất cao.', 10, 7, 'sanPhamPanasonic/MaylanhamtranPanasonic/S-50PU1H5B.png', 2, 2);

-- LG
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần LG ATNQ18GPLE7', 21000000, 23000000, NOW(), 28, 'Máy lạnh âm trần LG 2.0Hp inverter, thiết kế hiện đại.', 10, 11, 'sanPhamLG/MaylanhamtranLG/ATNQ18GPLE7.png', 2, 3),
('Máy lạnh âm trần LG ATNQ24GPLE7', 25000000, 27000000, NOW(), 26, 'Máy lạnh âm trần LG 2.5Hp inverter, tiết kiệm điện.', 10, 10, 'sanPhamLG/MaylanhamtranLG/ATNQ24GPLE7.png', 2, 3),
('Máy lạnh âm trần LG ATNQ30GPLE7', 29000000, 31000000, NOW(), 24, 'Máy lạnh âm trần LG 3.0Hp inverter, làm lạnh nhanh.', 10, 9, 'sanPhamLG/MaylanhamtranLG/ATNQ30GPLE7.png', 2, 3),
('Máy lạnh âm trần LG ATNQ36GPLE7', 33000000, 35000000, NOW(), 22, 'Máy lạnh âm trần LG 3.5Hp inverter, phù hợp phòng lớn.', 10, 8, 'sanPhamLG/MaylanhamtranLG/ATNQ36GPLE7.png', 2, 3),
('Máy lạnh âm trần LG ATNQ48GPLE7', 37000000, 39000000, NOW(), 20, 'Máy lạnh âm trần LG 5.0Hp inverter, công suất cao.', 10, 7, 'sanPhamLG/MaylanhamtranLG/ATNQ48GPLE7.png', 2, 3);

-- Toshiba
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần Toshiba RAV-SE561CTP-E', 26000000, 29000000, NOW(), 18, 'Máy lạnh âm trần Toshiba 2.0Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamToshiba/MaylanhamtranToshiba/RAV-SE561CTP-E.png', 2, 4),
('Máy lạnh âm trần Toshiba RAV-SE801CTP-E', 31000000, 34000000, NOW(), 16, 'Máy lạnh âm trần Toshiba 3.0Hp inverter, làm lạnh nhanh.', 10, 10, 'sanPhamToshiba/MaylanhamtranToshiba/RAV-SE801CTP-E.png', 2, 4),
('Máy lạnh âm trần Toshiba RAV-SE1001CTP-E', 35000000, 38000000, NOW(), 14, 'Máy lạnh âm trần Toshiba 4.0Hp inverter, phù hợp phòng lớn.', 10, 9, 'sanPhamToshiba/MaylanhamtranToshiba/RAV-SE1001CTP-E.png', 2, 4),
('Máy lạnh âm trần Toshiba RAV-SE1401CTP-E', 40000000, 43000000, NOW(), 12, 'Máy lạnh âm trần Toshiba 5.0Hp inverter, công suất cao.', 10, 8, 'sanPhamToshiba/MaylanhamtranToshiba/RAV-SE1401CTP-E.png', 2, 4),
('Máy lạnh âm trần Toshiba RAV-SE1801CTP-E', 45000000, 48000000, NOW(), 10, 'Máy lạnh âm trần Toshiba 6.0Hp inverter, tiết kiệm điện.', 10, 7, 'sanPhamToshiba/MaylanhamtranToshiba/RAV-SE1801CTP-E.png', 2, 4);

-- Mitsubishi
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần Mitsubishi PLA-RP50BA', 25500000, 28500000, NOW(), 8, 'Máy lạnh âm trần Mitsubishi 2.0Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamMitsubishi/MaylanhamtranMitsubishi/PLA-RP50BA.png', 2, 5),
('Máy lạnh âm trần Mitsubishi PLA-RP60BA', 27500000, 30500000, NOW(), 7, 'Máy lạnh âm trần Mitsubishi 2.5Hp inverter, làm lạnh nhanh.', 10, 10, 'sanPhamMitsubishi/MaylanhamtranMitsubishi/PLA-RP60BA.png', 2, 5),
('Máy lạnh âm trần Mitsubishi PLA-RP71BA', 32500000, 35500000, NOW(), 6, 'Máy lạnh âm trần Mitsubishi 3.0Hp inverter, phù hợp phòng lớn.', 10, 9, 'sanPhamMitsubishi/MaylanhamtranMitsubishi/PLA-RP71BA.png', 2, 5),
('Máy lạnh âm trần Mitsubishi PLA-RP100BA', 37500000, 40500000, NOW(), 5, 'Máy lạnh âm trần Mitsubishi 4.0Hp inverter, công suất cao.', 10, 8, 'sanPhamMitsubishi/MaylanhamtranMitsubishi/PLA-RP100BA.png', 2, 5),
('Máy lạnh âm trần Mitsubishi PLA-RP125BA', 42500000, 45500000, NOW(), 4, 'Máy lạnh âm trần Mitsubishi 5.0Hp inverter, tiết kiệm điện.', 10, 7, 'sanPhamMitsubishi/MaylanhamtranMitsubishi/PLA-RP125BA.png', 2, 5);

-- Samsung
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần Samsung AC100MN4PKC/EA', 32000000, 34000000, NOW(), 20, 'Máy lạnh âm trần Samsung 4.0Hp inverter, thiết kế hiện đại.', 10, 10, 'sanPhamSamsung/MaylanhamtranSamsung/AC100MN4PKC-EA.png', 2, 6),
('Máy lạnh âm trần Samsung AC120MN4PKC/EA', 35000000, 37000000, NOW(), 18, 'Máy lạnh âm trần Samsung 5.0Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamSamsung/MaylanhamtranSamsung/AC120MN4PKC-EA.png', 2, 6),
('Máy lạnh âm trần Samsung AC140MN4PKC/EA', 38000000, 40000000, NOW(), 16, 'Máy lạnh âm trần Samsung 6.0Hp inverter, làm lạnh nhanh.', 10, 12, 'sanPhamSamsung/MaylanhamtranSamsung/AC140MN4PKC-EA.png', 2, 6),
('Máy lạnh âm trần Samsung AC160MN4PKC/EA', 41000000, 43000000, NOW(), 14, 'Máy lạnh âm trần Samsung 7.0Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamSamsung/MaylanhamtranSamsung/AC160MN4PKC-EA.png', 2, 6),
('Máy lạnh âm trần Samsung AC180MN4PKC/EA', 44000000, 46000000, NOW(), 12, 'Máy lạnh âm trần Samsung 8.0Hp inverter, công suất cao.', 10, 14, 'sanPhamSamsung/MaylanhamtranSamsung/AC180MN4PKC-EA.png', 2, 6);

-- Máy lạnh đứng (id_danhmuc = 3)
-- Daikin
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng Daikin FVGR10NV1', 32000000, 35000000, NOW(), 30, 'Máy lạnh đứng Daikin 4.0Hp inverter, thiết kế đứng.', 10, 10, 'sanPhamDaikin/MaylanhdungDaikin/FVGR10NV1.png', 3, 1),
('Máy lạnh đứng Daikin FVGR13NV1', 37000000, 40000000, NOW(), 28, 'Máy lạnh đứng Daikin 5.0Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamDaikin/MaylanhdungDaikin/FVGR13NV1.png', 3, 1),
('Máy lạnh đứng Daikin FVGR16NV1', 42000000, 45000000, NOW(), 26, 'Máy lạnh đứng Daikin 6.0Hp inverter, làm lạnh nhanh.', 10, 12, 'sanPhamDaikin/MaylanhdungDaikin/FVGR16NV1.png', 3, 1),
('Máy lạnh đứng Daikin FVGR20NV1', 47000000, 50000000, NOW(), 24, 'Máy lạnh đứng Daikin 8.0Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamDaikin/MaylanhdungDaikin/FVGR20NV1.png', 3, 1),
('Máy lạnh đứng Daikin FVGR24NV1', 52000000, 55000000, NOW(), 22, 'Máy lạnh đứng Daikin 10.0Hp inverter, công suất cao.', 10, 14, 'sanPhamDaikin/MaylanhdungDaikin/FVGR24NV1.png', 3, 1);

-- Panasonic
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng Panasonic PAU-N24VKH', 29500000, 32000000, NOW(), 25, 'Máy lạnh đứng Panasonic 2.5Hp inverter, công nghệ nanoe-G.', 10, 10, 'sanPhamPanasonic/MaylanhdungPanasonic/PAU-N24VKH.png', 3, 2),
('Máy lạnh đứng Panasonic PAU-N28VKH', 33500000, 36000000, NOW(), 23, 'Máy lạnh đứng Panasonic 3.0Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamPanasonic/MaylanhdungPanasonic/PAU-N28VKH.png', 3, 2),
('Máy lạnh đứng Panasonic PAU-N36VKH', 37500000, 40000000, NOW(), 21, 'Máy lạnh đứng Panasonic 3.5Hp inverter, làm lạnh nhanh.', 10, 12, 'sanPhamPanasonic/MaylanhdungPanasonic/PAU-N36VKH.png', 3, 2),
('Máy lạnh đứng Panasonic PAU-N45VKH', 41500000, 44000000, NOW(), 19, 'Máy lạnh đứng Panasonic 4.5Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamPanasonic/MaylanhdungPanasonic/PAU-N45VKH.png', 3, 2),
('Máy lạnh đứng Panasonic PAU-N50VKH', 45500000, 48000000, NOW(), 17, 'Máy lạnh đứng Panasonic 5.0Hp inverter, công suất cao.', 10, 14, 'sanPhamPanasonic/MaylanhdungPanasonic/PAU-N50VKH.png', 3, 2);

-- LG
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng LG APNQ24GR1A3', 31000000, 34000000, NOW(), 20, 'Máy lạnh đứng LG 2.5Hp inverter, thiết kế hiện đại.', 10, 10, 'sanPhamLG/MaylanhdungLG/APNQ24GR1A3.png', 3, 3),
('Máy lạnh đứng LG APNQ30GR1A3', 35000000, 38000000, NOW(), 18, 'Máy lạnh đứng LG 3.0Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamLG/MaylanhdungLG/APNQ30GR1A3.png', 3, 3),
('Máy lạnh đứng LG APNQ36GR1A3', 39000000, 42000000, NOW(), 16, 'Máy lạnh đứng LG 3.5Hp inverter, làm lạnh nhanh.', 10, 12, 'sanPhamLG/MaylanhdungLG/APNQ36GR1A3.png', 3, 3),
('Máy lạnh đứng LG APNQ45GR1A3', 43000000, 46000000, NOW(), 14, 'Máy lạnh đứng LG 4.5Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamLG/MaylanhdungLG/APNQ45GR1A3.png', 3, 3),
('Máy lạnh đứng LG APNQ50GR1A3', 47000000, 50000000, NOW(), 12, 'Máy lạnh đứng LG 5.0Hp inverter, công suất cao.', 10, 14, 'sanPhamLG/MaylanhdungLG/APNQ50GR1A3.png', 3, 3);

-- Toshiba
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng Toshiba RAV-GP241ATP-E', 30500000, 33500000, NOW(), 22, 'Máy lạnh đứng Toshiba 2.5Hp inverter, tiết kiệm điện.', 10, 10, 'sanPhamToshiba/MaylanhdungToshiba/RAV-GP241ATP-E.png', 3, 4),
('Máy lạnh đứng Toshiba RAV-GP301ATP-E', 34500000, 37500000, NOW(), 20, 'Máy lạnh đứng Toshiba 3.0Hp inverter, làm lạnh nhanh.', 10, 11, 'sanPhamToshiba/MaylanhdungToshiba/RAV-GP301ATP-E.png', 3, 4),
('Máy lạnh đứng Toshiba RAV-GP361ATP-E', 38500000, 41500000, NOW(), 18, 'Máy lạnh đứng Toshiba 3.5Hp inverter, phù hợp phòng lớn.', 10, 12, 'sanPhamToshiba/MaylanhdungToshiba/RAV-GP361ATP-E.png', 3, 4),
('Máy lạnh đứng Toshiba RAV-GP451ATP-E', 42500000, 45500000, NOW(), 16, 'Máy lạnh đứng Toshiba 4.5Hp inverter, công suất cao.', 10, 13, 'sanPhamToshiba/MaylanhdungToshiba/RAV-GP451ATP-E.png', 3, 4),
('Máy lạnh đứng Toshiba RAV-GP501ATP-E', 46500000, 49500000, NOW(), 14, 'Máy lạnh đứng Toshiba 5.0Hp inverter, tiết kiệm điện.', 10, 14, 'sanPhamToshiba/MaylanhdungToshiba/RAV-GP501ATP-E.png', 3, 4);

-- Mitsubishi
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng Mitsubishi FDF71CR-S5', 31500000, 34500000, NOW(), 15, 'Máy lạnh đứng Mitsubishi 2.5Hp inverter, tiết kiệm điện.', 10, 10, 'sanPhamMitsubishi/MaylanhdungMitsubishi/FDF71CR-S5.png', 3, 5),
('Máy lạnh đứng Mitsubishi FDF100CR-S5', 35500000, 38500000, NOW(), 13, 'Máy lạnh đứng Mitsubishi 3.0Hp inverter, làm lạnh nhanh.', 10, 11, 'sanPhamMitsubishi/MaylanhdungMitsubishi/FDF100CR-S5.png', 3, 5),
('Máy lạnh đứng Mitsubishi FDF125CR-S5', 39500000, 42500000, NOW(), 11, 'Máy lạnh đứng Mitsubishi 3.5Hp inverter, phù hợp phòng lớn.', 10, 12, 'sanPhamMitsubishi/MaylanhdungMitsubishi/FDF125CR-S5.png', 3, 5),
('Máy lạnh đứng Mitsubishi FDF140CR-S5', 43500000, 46500000, NOW(), 9, 'Máy lạnh đứng Mitsubishi 4.5Hp inverter, công suất cao.', 10, 13, 'sanPhamMitsubishi/MaylanhdungMitsubishi/FDF140CR-S5.png', 3, 5),
('Máy lạnh đứng Mitsubishi FDF160CR-S5', 47500000, 50500000, NOW(), 7, 'Máy lạnh đứng Mitsubishi 5.0Hp inverter, tiết kiệm điện.', 10, 14, 'sanPhamMitsubishi/MaylanhdungMitsubishi/FDF160CR-S5.png', 3, 5);

-- Samsung
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng Samsung AF28FV1D', 29500000, 31500000, NOW(), 20, 'Máy lạnh đứng Samsung 3.0Hp inverter, thiết kế hiện đại.', 10, 10, 'sanPhamSamsung/MaylanhdungSamsung/AF28FV1D.png', 3, 6),
('Máy lạnh đứng Samsung AF36FV1D', 33500000, 35500000, NOW(), 18, 'Máy lạnh đứng Samsung 4.0Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamSamsung/MaylanhdungSamsung/AF36FV1D.png', 3, 6),
('Máy lạnh đứng Samsung AF48FV1D', 37500000, 39500000, NOW(), 16, 'Máy lạnh đứng Samsung 5.0Hp inverter, làm lạnh nhanh.', 10, 12, 'sanPhamSamsung/MaylanhdungSamsung/AF48FV1D.png', 3, 6),
('Máy lạnh đứng Samsung AF56FV1D', 41500000, 43500000, NOW(), 14, 'Máy lạnh đứng Samsung 6.0Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamSamsung/MaylanhdungSamsung/AF56FV1D.png', 3, 6),
('Máy lạnh đứng Samsung AF60FV1D', 45500000, 47500000, NOW(), 12, 'Máy lạnh đứng Samsung 7.0Hp inverter, công suất cao.', 10, 14, 'sanPhamSamsung/MaylanhdungSamsung/AF60FV1D.png', 3, 6);

-- Máy lạnh giấu trần (id_danhmuc = 4)
-- Daikin
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần Daikin FDR10NV1', 33000000, 36000000, NOW(), 25, 'Máy lạnh giấu trần Daikin 4.0Hp inverter, thiết kế giấu trần.', 10, 10, 'sanPhamDaikin/MaylanhgiautranDaikin/FDR10NV1.png', 4, 1),
('Máy lạnh giấu trần Daikin FDR13NV1', 38000000, 41000000, NOW(), 23, 'Máy lạnh giấu trần Daikin 5.0Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamDaikin/MaylanhgiautranDaikin/FDR13NV1.png', 4, 1),
('Máy lạnh giấu trần Daikin FDR16NV1', 43000000, 46000000, NOW(), 21, 'Máy lạnh giấu trần Daikin 6.0Hp inverter, làm lạnh nhanh.', 10, 12, 'sanPhamDaikin/MaylanhgiautranDaikin/FDR16NV1.png', 4, 1),
('Máy lạnh giấu trần Daikin FDR20NV1', 48000000, 51000000, NOW(), 19, 'Máy lạnh giấu trần Daikin 8.0Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamDaikin/MaylanhgiautranDaikin/FDR20NV1.png', 4, 1),
('Máy lạnh giấu trần Daikin FDR24NV1', 53000000, 56000000, NOW(), 17, 'Máy lạnh giấu trần Daikin 10.0Hp inverter, công suất cao.', 10, 14, 'sanPhamDaikin/MaylanhgiautranDaikin/FDR24NV1.png', 4, 1);

-- Panasonic
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần Panasonic S-21PU1H5B', 34000000, 37000000, NOW(), 22, 'Máy lạnh giấu trần Panasonic 2.0Hp inverter, công nghệ nanoe-G.', 10, 10, 'sanPhamPanasonic/MaylanhgiautranPanasonic/S-21PU1H5B.png', 4, 2),
('Máy lạnh giấu trần Panasonic S-24PB3H5', 38000000, 41000000, NOW(), 20, 'Máy lạnh giấu trần Panasonic 2.5Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamPanasonic/MaylanhgiautranPanasonic/S-24PB3H5.png', 4, 2),
('Máy lạnh giấu trần Panasonic S-30PU1H5B', 42000000, 45000000, NOW(), 18, 'Máy lạnh giấu trần Panasonic 3.0Hp inverter, làm lạnh nhanh.', 10, 12, 'sanPhamPanasonic/MaylanhgiautranPanasonic/S-30PU1H5B.png', 4, 2),
('Máy lạnh giấu trần Panasonic S-36PU1H5B', 46000000, 49000000, NOW(), 16, 'Máy lạnh giấu trần Panasonic 3.5Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamPanasonic/MaylanhgiautranPanasonic/S-36PU1H5B.png', 4, 2),
('Máy lạnh giấu trần Panasonic S-50PU1H5B', 50000000, 53000000, NOW(), 14, 'Máy lạnh giấu trần Panasonic 5.0Hp inverter, công suất cao.', 10, 14, 'sanPhamPanasonic/MaylanhgiautranPanasonic/S-50PU1H5B.png', 4, 2);

-- LG
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần LG ABNQ18GLAA0', 32000000, 35000000, NOW(), 21, 'Máy lạnh giấu trần LG 2.0Hp inverter, thiết kế hiện đại.', 10, 10, 'sanPhamLG/MaylanhgiautranLG/ABNQ18GLAA0.png', 4, 3),
('Máy lạnh giấu trần LG ABNQ24GLAA0', 36000000, 39000000, NOW(), 19, 'Máy lạnh giấu trần LG 2.5Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamLG/MaylanhgiautranLG/ABNQ24GLAA0.png', 4, 3),
('Máy lạnh giấu trần LG ABNQ30GLAA0', 40000000, 43000000, NOW(), 17, 'Máy lạnh giấu trần LG 3.0Hp inverter, làm lạnh nhanh.', 10, 12, 'sanPhamLG/MaylanhgiautranLG/ABNQ30GLAA0.png', 4, 3),
('Máy lạnh giấu trần LG ABNQ36GLAA0', 44000000, 47000000, NOW(), 15, 'Máy lạnh giấu trần LG 3.5Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamLG/MaylanhgiautranLG/ABNQ36GLAA0.png', 4, 3),
('Máy lạnh giấu trần LG ABNQ48GLAA0', 48000000, 51000000, NOW(), 13, 'Máy lạnh giấu trần LG 5.0Hp inverter, công suất cao.', 10, 14, 'sanPhamLG/MaylanhgiautranLG/ABNQ48GLAA0.png', 4, 3);

-- Toshiba
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần Toshiba RAV-GP241ATP-E', 33500000, 36500000, NOW(), 18, 'Máy lạnh giấu trần Toshiba 2.5Hp inverter, tiết kiệm điện.', 10, 10, 'sanPhamToshiba/MaylanhgiautranToshiba/RAV-GP241ATP-E.png', 4, 4),
('Máy lạnh giấu trần Toshiba RAV-GP301ATP-E', 37500000, 40500000, NOW(), 16, 'Máy lạnh giấu trần Toshiba 3.0Hp inverter, làm lạnh nhanh.', 10, 11, 'sanPhamToshiba/MaylanhgiautranToshiba/RAV-GP301ATP-E.png', 4, 4),
('Máy lạnh giấu trần Toshiba RAV-GP361ATP-E', 41500000, 44500000, NOW(), 14, 'Máy lạnh giấu trần Toshiba 3.5Hp inverter, phù hợp phòng lớn.', 10, 12, 'sanPhamToshiba/MaylanhgiautranToshiba/RAV-GP361ATP-E.png', 4, 4),
('Máy lạnh giấu trần Toshiba RAV-GP451ATP-E', 45500000, 48500000, NOW(), 12, 'Máy lạnh giấu trần Toshiba 4.5Hp inverter, công suất cao.', 10, 13, 'sanPhamToshiba/MaylanhgiautranToshiba/RAV-GP451ATP-E.png', 4, 4),
('Máy lạnh giấu trần Toshiba RAV-GP501ATP-E', 49500000, 52500000, NOW(), 10, 'Máy lạnh giấu trần Toshiba 5.0Hp inverter, tiết kiệm điện.', 10, 14, 'sanPhamToshiba/MaylanhgiautranToshiba/RAV-GP501ATP-E.png', 4, 4);

-- Mitsubishi
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần Mitsubishi FDF71CR-S5', 34500000, 37500000, NOW(), 15, 'Máy lạnh giấu trần Mitsubishi 2.5Hp inverter, tiết kiệm điện.', 10, 10, 'sanPhamMitsubishi/MaylanhgiautranMitsubishi/FDF71CR-S5.png', 4, 5),
('Máy lạnh giấu trần Mitsubishi FDF100CR-S5', 38500000, 41500000, NOW(), 13, 'Máy lạnh giấu trần Mitsubishi 3.0Hp inverter, làm lạnh nhanh.', 10, 11, 'sanPhamMitsubishi/MaylanhgiautranMitsubishi/FDF100CR-S5.png', 4, 5),
('Máy lạnh giấu trần Mitsubishi FDF125CR-S5', 42500000, 45500000, NOW(), 11, 'Máy lạnh giấu trần Mitsubishi 3.5Hp inverter, phù hợp phòng lớn.', 10, 12, 'sanPhamMitsubishi/MaylanhgiautranMitsubishi/FDF125CR-S5.png', 4, 5),
('Máy lạnh giấu trần Mitsubishi FDF140CR-S5', 46500000, 49500000, NOW(), 9, 'Máy lạnh giấu trần Mitsubishi 4.5Hp inverter, công suất cao.', 10, 13, 'sanPhamMitsubishi/MaylanhgiautranMitsubishi/FDF140CR-S5.png', 4, 5),
('Máy lạnh giấu trần Mitsubishi FDF160CR-S5', 50500000, 53500000, NOW(), 7, 'Máy lạnh giấu trần Mitsubishi 5.0Hp inverter, tiết kiệm điện.', 10, 14, 'sanPhamMitsubishi/MaylanhgiautranMitsubishi/FDF160CR-S5.png', 4, 5);

-- Samsung
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần Samsung AC071MN4PKC/EA', 32000000, 34000000, NOW(), 20, 'Máy lạnh giấu trần Samsung 2.5Hp inverter, thiết kế hiện đại.', 10, 10, 'sanPhamSamsung/MaylanhgiautranSamsung/AC071MN4PKC-EA.png', 4, 6),
('Máy lạnh giấu trần Samsung AC100MN4PKC/EA', 35000000, 37000000, NOW(), 18, 'Máy lạnh giấu trần Samsung 4.0Hp inverter, tiết kiệm điện.', 10, 11, 'sanPhamSamsung/MaylanhgiautranSamsung/AC100MN4PKC-EA.png', 4, 6),
('Máy lạnh giấu trần Samsung AC120MN4PKC/EA', 38000000, 40000000, NOW(), 16, 'Máy lạnh giấu trần Samsung 5.0Hp inverter, làm lạnh nhanh.', 10, 12, 'sanPhamSamsung/MaylanhgiautranSamsung/AC120MN4PKC-EA.png', 4, 6),
('Máy lạnh giấu trần Samsung AC140MN4PKC/EA', 41000000, 43000000, NOW(), 14, 'Máy lạnh giấu trần Samsung 6.0Hp inverter, phù hợp phòng lớn.', 10, 13, 'sanPhamSamsung/MaylanhgiautranSamsung/AC140MN4PKC-EA.png', 4, 6),
('Máy lạnh giấu trần Samsung AC160MN4PKC/EA', 44000000, 46000000, NOW(), 12, 'Máy lạnh giấu trần Samsung 7.0Hp inverter, công suất cao.', 10, 14, 'sanPhamSamsung/MaylanhgiautranSamsung/AC160MN4PKC-EA.png', 4, 6);

-- Tủ lạnh (id_danhmuc = 5)
-- Samsung
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Samsung Inverter 460L RT47CB66868ASV', 14350000, 15350000, NOW(), 30, 'Tủ lạnh Samsung Inverter 460 lít, tiết kiệm điện.', 10, 10, 'sanPhamSamsung/TuLanhSamsung/RT47CB66868A-SV.jpg', 5, 6),
('Tủ lạnh Samsung Inverter 488L RF48A4000B4/SV', 14500000, 15500000, NOW(), 28, 'Tủ lạnh Samsung Inverter 488 lít, thiết kế hiện đại.', 10, 11, 'sanPhamSamsung/TuLanhSamsung/RF48A4000B4-SV.jpg', 5, 6),
('Tủ lạnh Samsung Inverter 488L RF48A4010M9/SV', 15900000, 16900000, NOW(), 26, 'Tủ lạnh Samsung Inverter 488 lít, nhiều tiện ích.', 10, 12, 'sanPhamSamsung/TuLanhSamsung/RF48A4010M9-SV.jpg', 5, 6),
('Tủ lạnh Samsung Inverter 488L RF48A4010B4/SV', 16790000, 17790000, NOW(), 24, 'Tủ lạnh Samsung Inverter 488 lít, sang trọng.', 10, 13, 'sanPhamSamsung/TuLanhSamsung/RF48A4010B4-SV.jpg', 5, 6),
('Tủ lạnh Samsung Inverter 649L RF59C700ES9/SV', 24990000, 25990000, NOW(), 22, 'Tủ lạnh Samsung Inverter 649 lít, dung tích lớn.', 10, 14, 'sanPhamSamsung/TuLanhSamsung/RF59C700ES9-SV.jpg', 5, 6);

-- Panasonic
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Panasonic Inverter 550L NR-DZ601VGKV', 25490000, 26490000, NOW(), 20, 'Tủ lạnh Panasonic Inverter 550 lít, tiết kiệm điện.', 10, 10, 'sanPhamPanasonic/TuLanhPanasonic/550L-NR-DZ601VGKV.jpg', 5, 2),
('Tủ lạnh Panasonic Inverter 405L NR-BX471GPKV', 15900000, 16900000, NOW(), 18, 'Tủ lạnh Panasonic Inverter 405 lít, thiết kế sang trọng.', 10, 11, 'sanPhamPanasonic/TuLanhPanasonic/405L-NR-BX471GPKV.jpg', 5, 2),
('Tủ lạnh Panasonic Inverter 325L NR-BL351GKVN', 10900000, 11900000, NOW(), 16, 'Tủ lạnh Panasonic Inverter 325 lít, nhỏ gọn.', 10, 12, 'sanPhamPanasonic/TuLanhPanasonic/325L-NR-BL351GKVN.jpg', 5, 2),
('Tủ lạnh Panasonic Inverter 255L NR-BV280QSVN', 8900000, 9900000, NOW(), 14, 'Tủ lạnh Panasonic Inverter 255 lít, phù hợp gia đình nhỏ.', 10, 13, 'sanPhamPanasonic/TuLanhPanasonic/255L-NR-BV280QSVN.jpg', 5, 2),
('Tủ lạnh Panasonic Inverter 188L NR-BA228PKVN', 5900000, 6900000, NOW(), 12, 'Tủ lạnh Panasonic Inverter 188 lít, tiết kiệm không gian.', 10, 14, 'sanPhamPanasonic/TuLanhPanasonic/188L-NR-BA228PKVN.jpg', 5, 2);

-- LG
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh LG Inverter 635L GR-X257MC', 29900000, 30900000, NOW(), 10, 'Tủ lạnh LG Inverter 635 lít, cửa InstaView Door-in-Door.', 10, 10, 'sanPhamLG/TuLanhLG/635L-GR-X257MC.png', 5, 3),
('Tủ lạnh LG Inverter 516L GR-B256BL', 18900000, 19900000, NOW(), 8, 'Tủ lạnh LG Inverter 516 lít, tiết kiệm điện.', 10, 11, 'sanPhamLG/TuLanhLG/516L-GR-B256BL.png', 5, 3),
('Tủ lạnh LG Inverter 393L GN-D392BL', 12900000, 13900000, NOW(), 6, 'Tủ lạnh LG Inverter 393 lít, thiết kế hiện đại.', 10, 12, 'sanPhamLG/TuLanhLG/393L-GN-D392BL.png', 5, 3),
('Tủ lạnh LG Inverter 315L GN-M315PS', 9900000, 10900000, NOW(), 4, 'Tủ lạnh LG Inverter 315 lít, nhỏ gọn.', 10, 13, 'sanPhamLG/TuLanhLG/315L-GN-M315PS.png', 5, 3),
('Tủ lạnh LG Inverter 187L GN-L208PS', 5900000, 6900000, NOW(), 2, 'Tủ lạnh LG Inverter 187 lít, phù hợp gia đình nhỏ.', 10, 14, 'sanPhamLG/TuLanhLG/187L-GN-L208PS.png', 5, 3);

-- Toshiba
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Toshiba Inverter 555L GR-RF610WE-PGV(37)-MG', 21900000, 22900000, NOW(), 10, 'Tủ lạnh Toshiba Inverter 555 lít, thiết kế sang trọng.', 10, 10, 'sanPhamToshiba/TuLanhToshiba/555L-GR-RF610WE-PGV(37)-MG.png', 5, 4),
('Tủ lạnh Toshiba Inverter 511L GR-RF532WE-PGV(37)-MG', 19900000, 20900000, NOW(), 8, 'Tủ lạnh Toshiba Inverter 511 lít, tiết kiệm điện.', 10, 11, 'sanPhamToshiba/TuLanhToshiba/511L-GR-RF532WE-PGV(37)-MG.png', 5, 4),
('Tủ lạnh Toshiba Inverter 409L GR-AG46VPDZ(GBK)', 13900000, 14900000, NOW(), 6, 'Tủ lạnh Toshiba Inverter 409 lít, nhiều tiện ích.', 10, 12, 'sanPhamToshiba/TuLanhToshiba/409L-GR-AG46VPDZ(GBK).png', 5, 4),
('Tủ lạnh Toshiba Inverter 305L GR-B31VU UKG', 9900000, 10900000, NOW(), 4, 'Tủ lạnh Toshiba Inverter 305 lít, nhỏ gọn.', 10, 13, 'sanPhamToshiba/TuLanhToshiba/305L-GR-B31VU-UKG.png', 5, 4),
('Tủ lạnh Toshiba Inverter 186L GR-B22VU UKG', 5900000, 6900000, NOW(), 2, 'Tủ lạnh Toshiba Inverter 186 lít, phù hợp gia đình nhỏ.', 10, 14, 'sanPhamToshiba/TuLanhToshiba/186L-GR-B22VU-UKG.png', 5, 4);

-- Mitsubishi
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Mitsubishi Electric MR-L72EN-GSL', 25900000, 26900000, NOW(), 10, 'Tủ lạnh Mitsubishi Electric 580 lít, tiết kiệm điện.', 10, 10, 'sanPhamMitsubishi/TuLanhMitsubishi/MR-L72EN-GSL.png', 5, 5),
('Tủ lạnh Mitsubishi Electric MR-CX46EJ-BRW', 18900000, 19900000, NOW(), 8, 'Tủ lạnh Mitsubishi Electric 406 lít, thiết kế sang trọng.', 10, 11, 'sanPhamMitsubishi/TuLanhMitsubishi/MR-CX46EJ-BRW.png', 5, 5),
('Tủ lạnh Mitsubishi Electric MR-FV24EM-OB', 9900000, 10900000, NOW(), 6, 'Tủ lạnh Mitsubishi Electric 231 lít, nhỏ gọn.', 10, 12, 'sanPhamMitsubishi/TuLanhMitsubishi/MR-FV24EM-OB.png', 5, 5),
('Tủ lạnh Mitsubishi Electric MR-FV28EM-OB', 11900000, 12900000, NOW(), 4, 'Tủ lạnh Mitsubishi Electric 274 lít, phù hợp gia đình nhỏ.', 10, 13, 'sanPhamMitsubishi/TuLanhMitsubishi/MR-FV28EM-OB.png', 5, 5),
('Tủ lạnh Mitsubishi Electric MR-FV32EM-OB', 13900000, 14900000, NOW(), 2, 'Tủ lạnh Mitsubishi Electric 326 lít, tiết kiệm điện.', 10, 14, 'sanPhamMitsubishi/TuLanhMitsubishi/MR-FV32EM-OB.png', 5, 5);

-- =====================================================
-- DỮ LIỆU MẪU CHO ĐÁNH GIÁ, WISHLIST VÀ ĐƠN HÀNG
-- =====================================================

-- Thêm dữ liệu mẫu cho đánh giá sản phẩm
INSERT INTO danh_gia (id_user, id_sp, rating, comment, ngay_danh_gia) VALUES
(2, 1, 5, 'Sản phẩm rất tốt, làm lạnh nhanh và tiết kiệm điện!', NOW()),
(3, 1, 4, 'Chất lượng tốt, giá cả hợp lý.', NOW()),
(2, 5, 5, 'Máy lạnh Daikin chất lượng cao, đáng mua.', NOW()),
(3, 10, 4, 'Panasonic không làm tôi thất vọng.', NOW()),
(2, 15, 5, 'LG luôn là lựa chọn tốt.', NOW()),
(3, 20, 4, 'Toshiba giá cả phải chăng.', NOW()),
(2, 25, 5, 'Mitsubishi chất lượng Nhật Bản.', NOW()),
(3, 30, 4, 'Samsung thiết kế đẹp.', NOW()),
(2, 35, 5, 'Máy lạnh âm trần rất phù hợp.', NOW()),
(3, 40, 4, 'Giá hơi cao nhưng chất lượng tốt.', NOW()),
(2, 45, 5, 'Máy lạnh đứng phù hợp văn phòng.', NOW()),
(3, 50, 4, 'Công suất mạnh, làm lạnh nhanh.', NOW()),
(2, 55, 5, 'Máy lạnh giấu trần tiết kiệm không gian.', NOW()),
(3, 60, 4, 'Thiết kế hiện đại.', NOW()),
(2, 65, 5, 'Tủ lạnh Samsung dung tích lớn.', NOW()),
(3, 70, 4, 'Tủ lạnh Panasonic tiết kiệm điện.', NOW()),
(2, 75, 5, 'LG InstaView rất tiện lợi.', NOW()),
(3, 80, 4, 'Toshiba giá tốt.', NOW()),
(2, 85, 5, 'Mitsubishi chất lượng cao.', NOW()),
(3, 90, 4, 'Đa dạng sản phẩm.', NOW());

-- Thêm dữ liệu mẫu cho wishlist
INSERT INTO wishlist (id_user, id_sp, ngay_them) VALUES
(2, 1, NOW()),
(2, 5, NOW()),
(2, 10, NOW()),
(2, 15, NOW()),
(2, 20, NOW()),
(3, 25, NOW()),
(3, 30, NOW()),
(3, 35, NOW()),
(3, 40, NOW()),
(3, 45, NOW());

-- Thêm dữ liệu mẫu cho đơn hàng
INSERT INTO donhang (id_user, tongdh, ngaydat, trangthai, ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao, ghi_chu, phuong_thuc_thanh_toan) VALUES
(2, 8500000, NOW(), 'Đã giao hàng', 'Nguyễn Văn A', '0987654321', '456 Đường XYZ, Quận 2, TP.HCM', 'Giao giờ hành chính', 'Tiền mặt'),
(3, 12500000, NOW(), 'Đang giao hàng', 'Trần Thị B', '0123456788', '789 Đường DEF, Quận 3, TP.HCM', 'Gọi trước khi giao', 'Chuyển khoản'),
(2, 9500000, NOW(), 'Chờ xác nhận', 'Nguyễn Văn A', '0987654321', '456 Đường XYZ, Quận 2, TP.HCM', '', 'Tiền mặt');

-- Thêm dữ liệu mẫu cho chi tiết đơn hàng
INSERT INTO dh_chitiet (id_sp, id_dh, soluong, tong_dh, gia_ban) VALUES
(1, 1, 1, 8500000, 8500000),
(5, 2, 1, 12500000, 12500000),
(10, 3, 1, 9500000, 9500000);

-- Thêm dữ liệu mẫu cho lịch sử trạng thái đơn hàng
INSERT INTO lich_su_trang_thai (id_dh, trang_thai_cu, trang_thai_moi, ghi_chu, nguoi_cap_nhat) VALUES
(1, NULL, 'Chờ xác nhận', 'Đơn hàng mới được tạo', 1),
(1, 'Chờ xác nhận', 'Đã xác nhận', 'Đơn hàng đã được xác nhận', 1),
(1, 'Đã xác nhận', 'Đang giao hàng', 'Đơn hàng đang được giao', 1),
(1, 'Đang giao hàng', 'Đã giao hàng', 'Đơn hàng đã được giao thành công', 1),
(2, NULL, 'Chờ xác nhận', 'Đơn hàng mới được tạo', 1),
(2, 'Chờ xác nhận', 'Đã xác nhận', 'Đơn hàng đã được xác nhận', 1),
(2, 'Đã xác nhận', 'Đang giao hàng', 'Đơn hàng đang được giao', 1),
(3, NULL, 'Chờ xác nhận', 'Đơn hàng mới được tạo', 1);

-- Cập nhật rating trung bình cho sản phẩm
UPDATE sanpham SET rating_trung_binh = (
    SELECT AVG(rating) 
    FROM danh_gia 
    WHERE danh_gia.id_sp = sanpham.id_sp
);

-- =====================================================
-- HOÀN THÀNH CƠ SỞ DỮ LIỆU
-- =====================================================

-- Thống kê tổng quan
SELECT 
    'Tổng số sản phẩm' as Thong_ke,
    COUNT(*) as So_luong
FROM sanpham
UNION ALL
SELECT 
    'Tổng số danh mục',
    COUNT(*)
FROM danhmuc
UNION ALL
SELECT 
    'Tổng số hãng',
    COUNT(*)
FROM hang
UNION ALL
SELECT 
    'Tổng số tài khoản',
    COUNT(*)
FROM taikhoan
UNION ALL
SELECT 
    'Tổng số đơn hàng',
    COUNT(*)
FROM donhang
UNION ALL


