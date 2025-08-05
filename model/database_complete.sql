-- Tạo database hoàn chỉnh cho shop điện lạnh
CREATE DATABASE IF NOT EXISTS dienlanh_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE dienlanh_shop;

-- Bảng danh mục
CREATE TABLE IF NOT EXISTS danhmuc (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

-- Bảng hãng sản xuất
CREATE TABLE IF NOT EXISTS hang (
  id_hang INT AUTO_INCREMENT PRIMARY KEY,
  ten_hang VARCHAR(100) NOT NULL,
  logo_hang VARCHAR(250) DEFAULT NULL
);

-- Bảng sản phẩm
CREATE TABLE IF NOT EXISTS sanpham (
  id_sp INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(250) NOT NULL,
  Price INT NOT NULL,
  Price_old INT DEFAULT NULL,
  Date_import DATETIME NOT NULL,
  Viewsp INT DEFAULT 0,
  Decribe LONGTEXT DEFAULT NULL,
  Mount INT NOT NULL,
  Sale INT DEFAULT 0,
  image VARCHAR(250) DEFAULT NULL,
  id_danhmuc INT NOT NULL,
  id_hang INT NOT NULL,
  FOREIGN KEY (id_danhmuc) REFERENCES danhmuc(id),
  FOREIGN KEY (id_hang) REFERENCES hang(id_hang)
);

-- Bảng tài khoản
CREATE TABLE IF NOT EXISTS taikhoan (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(20) NOT NULL,
  password VARCHAR(255) NOT NULL,
  fullname VARCHAR(250) NOT NULL,
  email VARCHAR(250) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  address VARCHAR(250) NOT NULL,
  position VARCHAR(12) NOT NULL DEFAULT 'user',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE INDEX idx_username (username),
  UNIQUE INDEX idx_email (email)
);

-- Bảng đơn hàng
CREATE TABLE IF NOT EXISTS donhang (
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
  ngay_cap_nhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES taikhoan(id_user) ON DELETE CASCADE
);

-- Bảng chi tiết đơn hàng
CREATE TABLE IF NOT EXISTS dh_chitiet (
  id_chitiet INT AUTO_INCREMENT PRIMARY KEY,
  id_sp INT NOT NULL,
  id_dh INT NOT NULL,
  soluong INT NOT NULL,
  tong_dh INT NOT NULL,
  gia_ban INT NOT NULL,
  FOREIGN KEY (id_sp) REFERENCES sanpham(id_sp) ON DELETE CASCADE,
  FOREIGN KEY (id_dh) REFERENCES donhang(id_dh) ON DELETE CASCADE
);

-- Bảng lịch sử trạng thái đơn hàng
CREATE TABLE IF NOT EXISTS lich_su_trang_thai (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_dh INT NOT NULL,
  trang_thai_cu VARCHAR(30),
  trang_thai_moi VARCHAR(30) NOT NULL,
  ghi_chu TEXT,
  ngay_cap_nhat DATETIME DEFAULT CURRENT_TIMESTAMP,
  nguoi_cap_nhat INT,
  FOREIGN KEY (id_dh) REFERENCES donhang(id_dh) ON DELETE CASCADE,
  FOREIGN KEY (nguoi_cap_nhat) REFERENCES taikhoan(id_user)
);

-- Thêm dữ liệu mẫu cho danh mục
INSERT INTO danhmuc (name) VALUES 
('Máy lạnh âm trần'),
('Máy lạnh đứng'),
('Máy lạnh giấu trần'),
('Máy lạnh treo tường'),
('Tủ lạnh');

-- Thêm dữ liệu mẫu cho hãng
INSERT INTO hang (ten_hang, logo_hang) VALUES 
('Daikin', 'view/image/logoDaikin.jpg'),
('LG', 'view/image/logoLG.jpg'),
('Mitsubishi', 'view/image/logoMitsubishi.jpg'),
('Panasonic', 'view/image/logoPanasonic.jpg'),
('Samsung', 'view/image/logosamsung.jpg'),
('Toshiba', 'view/image/logoToshiba.jpg');

-- Dữ liệu mockup Tủ lạnh
-- Chỉ gồm các lệnh INSERT sản phẩm cho danh mục Tủ lạnh (id_danhmuc=5)

-- Tủ lạnh - Samsung
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Samsung Inverter 460L RT47CB66868ASV', 14350000, 15350000, NOW(), 30, 'Tủ lạnh Samsung Inverter 460 lít, tiết kiệm điện.', 10, 10, 'view/image/sanPhamSamsung/TuLanhSamsung/RT47CB66868A-SV.jpg', 5, 6),
('Tủ lạnh Samsung Inverter 488L RF48A4000B4/SV', 14500000, 15500000, NOW(), 28, 'Tủ lạnh Samsung Inverter 488 lít, thiết kế hiện đại.', 10, 11, 'view/image/sanPhamSamsung/TuLanhSamsung/RF48A4000B4-SV.jpg', 5, 6),
('Tủ lạnh Samsung Inverter 488L RF48A4010M9/SV', 15900000, 16900000, NOW(), 26, 'Tủ lạnh Samsung Inverter 488 lít, nhiều tiện ích.', 10, 12, 'view/image/sanPhamSamsung/TuLanhSamsung/RF48A4010M9-SV.jpg', 5, 6),
('Tủ lạnh Samsung Inverter 488L RF48A4010B4/SV', 16790000, 17790000, NOW(), 24, 'Tủ lạnh Samsung Inverter 488 lít, sang trọng.', 10, 13, 'view/image/sanPhamSamsung/TuLanhSamsung/RF48A4010B4-SV.jpg', 5, 6),
('Tủ lạnh Samsung Inverter 649L RF59C700ES9/SV', 24990000, 25990000, NOW(), 22, 'Tủ lạnh Samsung Inverter 649 lít, dung tích lớn.', 10, 14, 'view/image/sanPhamSamsung/TuLanhSamsung/RF59C700ES9-SV.jpg', 5, 6);

-- Tủ lạnh - Panasonic
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Panasonic Inverter 550L NR-DZ601VGKV', 25490000, 26490000, NOW(), 20, 'Tủ lạnh Panasonic Inverter 550 lít, tiết kiệm điện.', 10, 10, 'view/image/sanPhamPanasonic/TuLanhPanasonic/550L-NR-DZ601VGKV.jpg', 5, 2),
('Tủ lạnh Panasonic Inverter 405L NR-BX471GPKV', 15900000, 16900000, NOW(), 18, 'Tủ lạnh Panasonic Inverter 405 lít, thiết kế sang trọng.', 10, 11, 'view/image/sanPhamPanasonic/TuLanhPanasonic/405L-NR-BX471GPKV.jpg', 5, 2),
('Tủ lạnh Panasonic Inverter 325L NR-BL351GKVN', 10900000, 11900000, NOW(), 16, 'Tủ lạnh Panasonic Inverter 325 lít, nhỏ gọn.', 10, 12, 'view/image/sanPhamPanasonic/TuLanhPanasonic/325L-NR-BL351GKVN.jpg', 5, 2),
('Tủ lạnh Panasonic Inverter 255L NR-BV280QSVN', 8900000, 9900000, NOW(), 14, 'Tủ lạnh Panasonic Inverter 255 lít, phù hợp gia đình nhỏ.', 10, 13, 'view/image/sanPhamPanasonic/TuLanhPanasonic/255L-NR-BV280QSVN.jpg', 5, 2),
('Tủ lạnh Panasonic Inverter 188L NR-BA228PKVN', 5900000, 6900000, NOW(), 12, 'Tủ lạnh Panasonic Inverter 188 lít, tiết kiệm không gian.', 10, 14, 'view/image/sanPhamPanasonic/TuLanhPanasonic/188L-NR-BA228PKVN.jpg', 5, 2);

-- Tủ lạnh - LG
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh LG Inverter 635L GR-X257MC', 29900000, 30900000, NOW(), 10, 'Tủ lạnh LG Inverter 635 lít, cửa InstaView Door-in-Door.', 10, 10, 'view/image/sanPhamLG/TuLanhLG/635L-GR-X257MC.png', 5, 3),
('Tủ lạnh LG Inverter 516L GR-B256BL', 18900000, 19900000, NOW(), 8, 'Tủ lạnh LG Inverter 516 lít, tiết kiệm điện.', 10, 11, 'view/image/sanPhamLG/TuLanhLG/516L-GR-B256BL.png', 5, 3),
('Tủ lạnh LG Inverter 393L GN-D392BL', 12900000, 13900000, NOW(), 6, 'Tủ lạnh LG Inverter 393 lít, thiết kế hiện đại.', 10, 12, 'view/image/sanPhamLG/TuLanhLG/393L-GN-D392BL.png', 5, 3),
('Tủ lạnh LG Inverter 315L GN-M315PS', 9900000, 10900000, NOW(), 4, 'Tủ lạnh LG Inverter 315 lít, nhỏ gọn.', 10, 13, 'view/image/sanPhamLG/TuLanhLG/315L-GN-M315PS.png', 5, 3),
('Tủ lạnh LG Inverter 187L GN-L208PS', 5900000, 6900000, NOW(), 2, 'Tủ lạnh LG Inverter 187 lít, phù hợp gia đình nhỏ.', 10, 14, 'view/image/sanPhamLG/TuLanhLG/187L-GN-L208PS.png', 5, 3);

-- Tủ lạnh - Toshiba
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Toshiba Inverter 555L GR-RF610WE-PGV(37)-MG', 21900000, 22900000, NOW(), 10, 'Tủ lạnh Toshiba Inverter 555 lít, thiết kế sang trọng.', 10, 10, 'view/image/sanPhamToshiba/TuLanhToshiba/555L-GR-RF610WE-PGV(37)-MG.png', 5, 4),
('Tủ lạnh Toshiba Inverter 511L GR-RF532WE-PGV(37)-MG', 19900000, 20900000, NOW(), 8, 'Tủ lạnh Toshiba Inverter 511 lít, tiết kiệm điện.', 10, 11, 'view/image/sanPhamToshiba/TuLanhToshiba/511L-GR-RF532WE-PGV(37)-MG.png', 5, 4),
('Tủ lạnh Toshiba Inverter 409L GR-AG46VPDZ(GBK)', 13900000, 14900000, NOW(), 6, 'Tủ lạnh Toshiba Inverter 409 lít, nhiều tiện ích.', 10, 12, 'view/image/sanPhamToshiba/TuLanhToshiba/409L-GR-AG46VPDZ(GBK).png', 5, 4),
('Tủ lạnh Toshiba Inverter 305L GR-B31VU UKG', 9900000, 10900000, NOW(), 4, 'Tủ lạnh Toshiba Inverter 305 lít, nhỏ gọn.', 10, 13, 'view/image/sanPhamToshiba/TuLanhToshiba/305L-GR-B31VU-UKG.png', 5, 4),
('Tủ lạnh Toshiba Inverter 186L GR-B22VU UKG', 5900000, 6900000, NOW(), 2, 'Tủ lạnh Toshiba Inverter 186 lít, phù hợp gia đình nhỏ.', 10, 14, 'view/image/sanPhamToshiba/TuLanhToshiba/186L-GR-B22VU-UKG.png', 5, 4);

-- Tủ lạnh - Mitsubishi
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Mitsubishi Electric MR-L72EN-GSL', 25900000, 26900000, NOW(), 10, 'Tủ lạnh Mitsubishi Electric 580 lít, tiết kiệm điện.', 10, 10, 'view/image/sanPhamMitsubishi/TuLanhMitsubishi/MR-L72EN-GSL.png', 5, 5),
('Tủ lạnh Mitsubishi Electric MR-CX46EJ-BRW', 18900000, 19900000, NOW(), 8, 'Tủ lạnh Mitsubishi Electric 406 lít, thiết kế sang trọng.', 10, 11, 'view/image/sanPhamMitsubishi/TuLanhMitsubishi/MR-CX46EJ-BRW.png', 5, 5),
('Tủ lạnh Mitsubishi Electric MR-FV24EM-OB', 9900000, 10900000, NOW(), 6, 'Tủ lạnh Mitsubishi Electric 231 lít, nhỏ gọn.', 10, 12, 'view/image/sanPhamMitsubishi/TuLanhMitsubishi/MR-FV24EM-OB.png', 5, 5),
('Tủ lạnh Mitsubishi Electric MR-FV28EM-OB', 11900000, 12900000, NOW(), 4, 'Tủ lạnh Mitsubishi Electric 274 lít, phù hợp gia đình nhỏ.', 10, 13, 'view/image/sanPhamMitsubishi/TuLanhMitsubishi/MR-FV28EM-OB.png', 5, 5),
('Tủ lạnh Mitsubishi Electric MR-FV32EM-OB', 13900000, 14900000, NOW(), 2, 'Tủ lạnh Mitsubishi Electric 326 lít, tiết kiệm điện.', 10, 14, 'view/image/sanPhamMitsubishi/TuLanhMitsubishi/MR-FV32EM-OB.png', 5, 5);

-- Dữ liệu mockup Máy lạnh treo tường
-- Chỉ gồm các lệnh INSERT sản phẩm cho danh mục Máy lạnh treo tường (id_danhmuc=1)

-- Máy lạnh treo tường - Daikin
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Daikin FTKA25UAVMV', 8500000, 9500000, NOW(), 50, 'Máy lạnh Daikin 1.0Hp inverter.', 10, 10, 'view/image/sanPhamDaikin/MaylanhtreotuongDaikin/FTKA25UAVMV.png', 1, 1),
('Máy lạnh Daikin FTKA35UAVMV', 9500000, 10500000, NOW(), 48, 'Máy lạnh Daikin 1.5Hp inverter.', 10, 11, 'view/image/sanPhamDaikin/MaylanhtreotuongDaikin/FTKA35UAVMV.png', 1, 1),
('Máy lạnh Daikin FTKA50UAVMV', 12500000, 13500000, NOW(), 46, 'Máy lạnh Daikin 2.0Hp inverter.', 10, 12, 'view/image/sanPhamDaikin/MaylanhtreotuongDaikin/FTKA50UAVMV.png', 1, 1),
('Máy lạnh Daikin FTKA60UAVMV', 14500000, 15500000, NOW(), 44, 'Máy lạnh Daikin 2.5Hp inverter.', 10, 13, 'view/image/sanPhamDaikin/MaylanhtreotuongDaikin/FTKA60UAVMV.png', 1, 1),
('Máy lạnh Daikin FTKA71UAVMV', 17500000, 18500000, NOW(), 42, 'Máy lạnh Daikin 3.0Hp inverter.', 10, 14, 'view/image/sanPhamDaikin/MaylanhtreotuongDaikin/FTKA71UAVMV.png', 1, 1);

-- Máy lạnh treo tường - Panasonic
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Panasonic CU/CS-XPU9WKH-8', 9000000, 10000000, NOW(), 40, 'Máy lạnh Panasonic 1.0Hp inverter.', 10, 10, 'view/image/sanPhamPanasonic/MaylanhtreotuongPanasonic/CU-CS-XPU9WKH-8.png', 1, 2),
('Máy lạnh Panasonic CU/CS-XPU12WKH-8', 10500000, 11500000, NOW(), 38, 'Máy lạnh Panasonic 1.5Hp inverter.', 10, 11, 'view/image/sanPhamPanasonic/MaylanhtreotuongPanasonic/CU-CS-XPU12WKH-8.png', 1, 2),
('Máy lạnh Panasonic CU/CS-XPU18WKH-8', 14500000, 15500000, NOW(), 36, 'Máy lạnh Panasonic 2.0Hp inverter.', 10, 12, 'view/image/sanPhamPanasonic/MaylanhtreotuongPanasonic/CU-CS-XPU18WKH-8.png', 1, 2),
('Máy lạnh Panasonic CU/CS-XPU24WKH-8', 17500000, 18500000, NOW(), 34, 'Máy lạnh Panasonic 2.5Hp inverter.', 10, 13, 'view/image/sanPhamPanasonic/MaylanhtreotuongPanasonic/CU-CS-XPU24WKH-8.png', 1, 2),
('Máy lạnh Panasonic CU/CS-XPU28WKH-8', 20500000, 21500000, NOW(), 32, 'Máy lạnh Panasonic 3.0Hp inverter.', 10, 14, 'view/image/sanPhamPanasonic/MaylanhtreotuongPanasonic/CU-CS-XPU28WKH-8.png', 1, 2);

-- Máy lạnh treo tường - LG
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh LG V10APF', 8200000, 9200000, NOW(), 30, 'Máy lạnh LG 1.0Hp inverter.', 10, 10, 'view/image/sanPhamLG/MaylanhtreotuongLG/LG-V10APF.png', 1, 3),
('Máy lạnh LG V13APF', 9500000, 10500000, NOW(), 28, 'Máy lạnh LG 1.5Hp inverter.', 10, 11, 'view/image/sanPhamLG/MaylanhtreotuongLG/LG-V13APF.png', 1, 3),
('Máy lạnh LG V18APF', 13500000, 14500000, NOW(), 26, 'Máy lạnh LG 2.0Hp inverter.', 10, 12, 'view/image/sanPhamLG/MaylanhtreotuongLG/LG-V18APF.png', 1, 3),
('Máy lạnh LG V24APF', 16500000, 17500000, NOW(), 24, 'Máy lạnh LG 2.5Hp inverter.', 10, 13, 'view/image/sanPhamLG/MaylanhtreotuongLG/LG-V24APF.png', 1, 3),
('Máy lạnh LG V27APF', 19500000, 20500000, NOW(), 22, 'Máy lạnh LG 3.0Hp inverter.', 10, 14, 'view/image/sanPhamLG/MaylanhtreotuongLG/LG-V27APF.png', 1, 3);

-- Máy lạnh treo tường - Toshiba
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Toshiba RAS-H10C4KCVG-V', 8300000, 9300000, NOW(), 20, 'Máy lạnh Toshiba 1.0Hp inverter.', 10, 10, 'view/image/sanPhamToshiba/MaylanhtreotuongToshiba/RAS-H10C4KCVG-V.png', 1, 4),
('Máy lạnh Toshiba RAS-H13C4KCVG-V', 9500000, 10500000, NOW(), 18, 'Máy lạnh Toshiba 1.5Hp inverter.', 10, 11, 'view/image/sanPhamToshiba/MaylanhtreotuongToshiba/RAS-H13C4KCVG-V.png', 1, 4),
('Máy lạnh Toshiba RAS-H18C4KCVG-V', 13500000, 14500000, NOW(), 16, 'Máy lạnh Toshiba 2.0Hp inverter.', 10, 12, 'view/image/sanPhamToshiba/MaylanhtreotuongToshiba/RAS-H18C4KCVG-V.png', 1, 4),
('Máy lạnh Toshiba RAS-H24C4KCVG-V', 16500000, 17500000, NOW(), 14, 'Máy lạnh Toshiba 2.5Hp inverter.', 10, 13, 'view/image/sanPhamToshiba/MaylanhtreotuongToshiba/RAS-H24C4KCVG-V.png', 1, 4),
('Máy lạnh Toshiba RAS-H27C4KCVG-V', 19500000, 20500000, NOW(), 12, 'Máy lạnh Toshiba 3.0Hp inverter.', 10, 14, 'view/image/sanPhamToshiba/MaylanhtreotuongToshiba/RAS-H27C4KCVG-V.png', 1, 4);

-- Máy lạnh treo tường - Mitsubishi
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Mitsubishi SRK/SRC10YN-S5', 8700000, 9700000, NOW(), 10, 'Máy lạnh Mitsubishi 1.0Hp inverter.', 10, 10, 'view/image/sanPhamMitsubishi/MaylanhtreotuongMitsubishi/SRK-SRC10YN-S5.png', 1, 5),
('Máy lạnh Mitsubishi SRK/SRC13YN-S5', 9900000, 10900000, NOW(), 8, 'Máy lạnh Mitsubishi 1.5Hp inverter.', 10, 11, 'view/image/sanPhamMitsubishi/MaylanhtreotuongMitsubishi/SRK-SRC13YN-S5.png', 1, 5),
('Máy lạnh Mitsubishi SRK/SRC18YN-S5', 13900000, 14900000, NOW(), 6, 'Máy lạnh Mitsubishi 2.0Hp inverter.', 10, 12, 'view/image/sanPhamMitsubishi/MaylanhtreotuongMitsubishi/SRK-SRC18YN-S5.png', 1, 5),
('Máy lạnh Mitsubishi SRK/SRC24YN-S5', 16900000, 17900000, NOW(), 4, 'Máy lạnh Mitsubishi 2.5Hp inverter.', 10, 13, 'view/image/sanPhamMitsubishi/MaylanhtreotuongMitsubishi/SRK-SRC24YN-S5.png', 1, 5),
('Máy lạnh Mitsubishi SRK/SRC27YN-S5', 19900000, 20900000, NOW(), 2, 'Máy lạnh Mitsubishi 3.0Hp inverter.', 10, 14, 'view/image/sanPhamMitsubishi/MaylanhtreotuongMitsubishi/SRK-SRC27YN-S5.png', 1, 5);

-- Máy lạnh treo tường - Samsung
-- (Nếu có sản phẩm Samsung, thêm ở đây với id_hang=6)

-- Dữ liệu mockup Máy lạnh giấu trần
-- Chỉ gồm các lệnh INSERT sản phẩm cho danh mục Máy lạnh giấu trần (id_danhmuc=4)

-- Máy lạnh giấu trần - Daikin
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần Daikin FDR10NV1', 33000000, 36000000, NOW(), 25, 'Máy lạnh giấu trần Daikin 4.0Hp inverter.', 10, 10, 'view/image/sanPhamDaikin/MaylanhgiautranDaikin/FDR10NV1.png', 4, 1),
('Máy lạnh giấu trần Daikin FDR13NV1', 38000000, 41000000, NOW(), 23, 'Máy lạnh giấu trần Daikin 5.0Hp inverter.', 10, 11, 'view/image/sanPhamDaikin/MaylanhgiautranDaikin/FDR13NV1.png', 4, 1),
('Máy lạnh giấu trần Daikin FDR16NV1', 43000000, 46000000, NOW(), 21, 'Máy lạnh giấu trần Daikin 6.0Hp inverter.', 10, 12, 'view/image/sanPhamDaikin/MaylanhgiautranDaikin/FDR16NV1.png', 4, 1),
('Máy lạnh giấu trần Daikin FDR20NV1', 48000000, 51000000, NOW(), 19, 'Máy lạnh giấu trần Daikin 8.0Hp inverter.', 10, 13, 'view/image/sanPhamDaikin/MaylanhgiautranDaikin/FDR20NV1.png', 4, 1),
('Máy lạnh giấu trần Daikin FDR24NV1', 53000000, 56000000, NOW(), 17, 'Máy lạnh giấu trần Daikin 10.0Hp inverter.', 10, 14, 'view/image/sanPhamDaikin/MaylanhgiautranDaikin/FDR24NV1.png', 4, 1);

-- Máy lạnh giấu trần - Panasonic
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần Panasonic S-21PU1H5B', 34000000, 37000000, NOW(), 22, 'Máy lạnh giấu trần Panasonic 2.0Hp inverter.', 10, 10, 'view/image/sanPhamPanasonic/MaylanhgiautranPanasonic/S-21PU1H5B.png', 4, 2),
('Máy lạnh giấu trần Panasonic S-24PB3H5', 38000000, 41000000, NOW(), 20, 'Máy lạnh giấu trần Panasonic 2.5Hp inverter.', 10, 11, 'view/image/sanPhamPanasonic/MaylanhgiautranPanasonic/S-24PB3H5.png', 4, 2),
('Máy lạnh giấu trần Panasonic S-30PU1H5B', 42000000, 45000000, NOW(), 18, 'Máy lạnh giấu trần Panasonic 3.0Hp inverter.', 10, 12, 'view/image/sanPhamPanasonic/MaylanhgiautranPanasonic/S-30PU1H5B.png', 4, 2),
('Máy lạnh giấu trần Panasonic S-36PU1H5B', 46000000, 49000000, NOW(), 16, 'Máy lạnh giấu trần Panasonic 3.5Hp inverter.', 10, 13, 'view/image/sanPhamPanasonic/MaylanhgiautranPanasonic/S-36PU1H5B.png', 4, 2),
('Máy lạnh giấu trần Panasonic S-50PU1H5B', 50000000, 53000000, NOW(), 14, 'Máy lạnh giấu trần Panasonic 5.0Hp inverter.', 10, 14, 'view/image/sanPhamPanasonic/MaylanhgiautranPanasonic/S-50PU1H5B.png', 4, 2);

-- Máy lạnh giấu trần - LG
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần LG ABNQ18GLAA0', 32000000, 35000000, NOW(), 21, 'Máy lạnh giấu trần LG 2.0Hp inverter.', 10, 10, 'view/image/sanPhamLG/MaylanhgiautranLG/ABNQ18GLAA0.png', 4, 3),
('Máy lạnh giấu trần LG ABNQ24GLAA0', 36000000, 39000000, NOW(), 19, 'Máy lạnh giấu trần LG 2.5Hp inverter.', 10, 11, 'view/image/sanPhamLG/MaylanhgiautranLG/ABNQ24GLAA0.png', 4, 3),
('Máy lạnh giấu trần LG ABNQ30GLAA0', 40000000, 43000000, NOW(), 17, 'Máy lạnh giấu trần LG 3.0Hp inverter.', 10, 12, 'view/image/sanPhamLG/MaylanhgiautranLG/ABNQ30GLAA0.png', 4, 3),
('Máy lạnh giấu trần LG ABNQ36GLAA0', 44000000, 47000000, NOW(), 15, 'Máy lạnh giấu trần LG 3.5Hp inverter.', 10, 13, 'view/image/sanPhamLG/MaylanhgiautranLG/ABNQ36GLAA0.png', 4, 3),
('Máy lạnh giấu trần LG ABNQ48GLAA0', 48000000, 51000000, NOW(), 13, 'Máy lạnh giấu trần LG 5.0Hp inverter.', 10, 14, 'view/image/sanPhamLG/MaylanhgiautranLG/ABNQ48GLAA0.png', 4, 3);

-- Máy lạnh giấu trần - Toshiba
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần Toshiba RAV-GP241ATP-E', 33500000, 36500000, NOW(), 18, 'Máy lạnh giấu trần Toshiba 2.5Hp inverter.', 10, 10, 'view/image/sanPhamToshiba/MaylanhgiautranToshiba/RAV-GP241ATP-E.png', 4, 4),
('Máy lạnh giấu trần Toshiba RAV-GP301ATP-E', 37500000, 40500000, NOW(), 16, 'Máy lạnh giấu trần Toshiba 3.0Hp inverter.', 10, 11, 'view/image/sanPhamToshiba/MaylanhgiautranToshiba/RAV-GP301ATP-E.png', 4, 4),
('Máy lạnh giấu trần Toshiba RAV-GP361ATP-E', 41500000, 44500000, NOW(), 14, 'Máy lạnh giấu trần Toshiba 3.5Hp inverter.', 10, 12, 'view/image/sanPhamToshiba/MaylanhgiautranToshiba/RAV-GP361ATP-E.png', 4, 4),
('Máy lạnh giấu trần Toshiba RAV-GP451ATP-E', 45500000, 48500000, NOW(), 12, 'Máy lạnh giấu trần Toshiba 4.5Hp inverter.', 10, 13, 'view/image/sanPhamToshiba/MaylanhgiautranToshiba/RAV-GP451ATP-E.png', 4, 4),
('Máy lạnh giấu trần Toshiba RAV-GP501ATP-E', 49500000, 52500000, NOW(), 10, 'Máy lạnh giấu trần Toshiba 5.0Hp inverter.', 10, 14, 'view/image/sanPhamToshiba/MaylanhgiautranToshiba/RAV-GP501ATP-E.png', 4, 4);

-- Máy lạnh giấu trần - Mitsubishi
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần Mitsubishi FDF71CR-S5', 34500000, 37500000, NOW(), 15, 'Máy lạnh giấu trần Mitsubishi 2.5Hp inverter.', 10, 10, 'view/image/sanPhamMitsubishi/MaylanhgiautranMitsubishi/FDF71CR-S5.png', 4, 5),
('Máy lạnh giấu trần Mitsubishi FDF100CR-S5', 38500000, 41500000, NOW(), 13, 'Máy lạnh giấu trần Mitsubishi 3.0Hp inverter.', 10, 11, 'view/image/sanPhamMitsubishi/MaylanhgiautranMitsubishi/FDF100CR-S5.png', 4, 5),
('Máy lạnh giấu trần Mitsubishi FDF125CR-S5', 42500000, 45500000, NOW(), 11, 'Máy lạnh giấu trần Mitsubishi 3.5Hp inverter.', 10, 12, 'view/image/sanPhamMitsubishi/MaylanhgiautranMitsubishi/FDF125CR-S5.png', 4, 5),
('Máy lạnh giấu trần Mitsubishi FDF140CR-S5', 46500000, 49500000, NOW(), 9, 'Máy lạnh giấu trần Mitsubishi 4.5Hp inverter.', 10, 13, 'view/image/sanPhamMitsubishi/MaylanhgiautranMitsubishi/FDF140CR-S5.png', 4, 5),
('Máy lạnh giấu trần Mitsubishi FDF160CR-S5', 50500000, 53500000, NOW(), 7, 'Máy lạnh giấu trần Mitsubishi 5.0Hp inverter.', 10, 14, 'view/image/sanPhamMitsubishi/MaylanhgiautranMitsubishi/FDF160CR-S5.png', 4, 5); 

-- Máy lạnh giấu trần - Samsung
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh giấu trần Samsung AC071MN4PKC/EA', 32000000, 34000000, NOW(), 20, 'Máy lạnh giấu trần Samsung 2.5Hp inverter.', 10, 10, 'view/image/sanPhamSamsung/MaylanhgiautranSamsung/AC071MN4PKC-EA.png', 4, 6),
('Máy lạnh giấu trần Samsung AC100MN4PKC/EA', 35000000, 37000000, NOW(), 18, 'Máy lạnh giấu trần Samsung 4.0Hp inverter.', 10, 11, 'view/image/sanPhamSamsung/MaylanhgiautranSamsung/AC100MN4PKC-EA.png', 4, 6),
('Máy lạnh giấu trần Samsung AC120MN4PKC/EA', 38000000, 40000000, NOW(), 16, 'Máy lạnh giấu trần Samsung 5.0Hp inverter.', 10, 12, 'view/image/sanPhamSamsung/MaylanhgiautranSamsung/AC120MN4PKC-EA.png', 4, 6),
('Máy lạnh giấu trần Samsung AC140MN4PKC/EA', 41000000, 43000000, NOW(), 14, 'Máy lạnh giấu trần Samsung 6.0Hp inverter.', 10, 13, 'view/image/sanPhamSamsung/MaylanhgiautranSamsung/AC140MN4PKC-EA.png', 4, 6),
('Máy lạnh giấu trần Samsung AC160MN4PKC/EA', 44000000, 46000000, NOW(), 12, 'Máy lạnh giấu trần Samsung 7.0Hp inverter.', 10, 14, 'view/image/sanPhamSamsung/MaylanhgiautranSamsung/AC160MN4PKC-EA.png', 4, 6); 
-- Dữ liệu mockup Máy lạnh đứng
-- Chỉ gồm các lệnh INSERT sản phẩm cho danh mục Máy lạnh đứng (id_danhmuc=3)

-- Máy lạnh đứng - Daikin
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng Daikin FVGR10NV1', 32000000, 35000000, NOW(), 30, 'Máy lạnh đứng Daikin 4.0Hp inverter.', 10, 10, 'view/image/sanPhamDaikin/MaylanhdungDaikin/FVGR10NV1.png', 3, 1),
('Máy lạnh đứng Daikin FVGR13NV1', 37000000, 40000000, NOW(), 28, 'Máy lạnh đứng Daikin 5.0Hp inverter.', 10, 11, 'view/image/sanPhamDaikin/MaylanhdungDaikin/FVGR13NV1.png', 3, 1),
('Máy lạnh đứng Daikin FVGR16NV1', 42000000, 45000000, NOW(), 26, 'Máy lạnh đứng Daikin 6.0Hp inverter.', 10, 12, 'view/image/sanPhamDaikin/MaylanhdungDaikin/FVGR16NV1.png', 3, 1),
('Máy lạnh đứng Daikin FVGR20NV1', 47000000, 50000000, NOW(), 24, 'Máy lạnh đứng Daikin 8.0Hp inverter.', 10, 13, 'view/image/sanPhamDaikin/MaylanhdungDaikin/FVGR20NV1.png', 3, 1),
('Máy lạnh đứng Daikin FVGR24NV1', 52000000, 55000000, NOW(), 22, 'Máy lạnh đứng Daikin 10.0Hp inverter.', 10, 14, 'view/image/sanPhamDaikin/MaylanhdungDaikin/FVGR24NV1.png', 3, 1);

-- Máy lạnh đứng - Panasonic
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng Panasonic PAU-N24VKH', 29500000, 32000000, NOW(), 25, 'Máy lạnh đứng Panasonic 2.5Hp inverter.', 10, 10, 'view/image/sanPhamPanasonic/MaylanhdungPanasonic/PAU-N24VKH.png', 3, 2),
('Máy lạnh đứng Panasonic PAU-N28VKH', 33500000, 36000000, NOW(), 23, 'Máy lạnh đứng Panasonic 3.0Hp inverter.', 10, 11, 'view/image/sanPhamPanasonic/MaylanhdungPanasonic/PAU-N28VKH.png', 3, 2),
('Máy lạnh đứng Panasonic PAU-N36VKH', 37500000, 40000000, NOW(), 21, 'Máy lạnh đứng Panasonic 3.5Hp inverter.', 10, 12, 'view/image/sanPhamPanasonic/MaylanhdungPanasonic/PAU-N36VKH.png', 3, 2),
('Máy lạnh đứng Panasonic PAU-N45VKH', 41500000, 44000000, NOW(), 19, 'Máy lạnh đứng Panasonic 4.5Hp inverter.', 10, 13, 'view/image/sanPhamPanasonic/MaylanhdungPanasonic/PAU-N45VKH.png', 3, 2),
('Máy lạnh đứng Panasonic PAU-N50VKH', 45500000, 48000000, NOW(), 17, 'Máy lạnh đứng Panasonic 5.0Hp inverter.', 10, 14, 'view/image/sanPhamPanasonic/MaylanhdungPanasonic/PAU-N50VKH.png', 3, 2);

-- Máy lạnh đứng - LG
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng LG APNQ24GR1A3', 31000000, 34000000, NOW(), 20, 'Máy lạnh đứng LG 2.5Hp inverter.', 10, 10, 'view/image/sanPhamLG/MaylanhdungLG/APNQ24GR1A3.png', 3, 3),
('Máy lạnh đứng LG APNQ30GR1A3', 35000000, 38000000, NOW(), 18, 'Máy lạnh đứng LG 3.0Hp inverter.', 10, 11, 'view/image/sanPhamLG/MaylanhdungLG/APNQ30GR1A3.png', 3, 3),
('Máy lạnh đứng LG APNQ36GR1A3', 39000000, 42000000, NOW(), 16, 'Máy lạnh đứng LG 3.5Hp inverter.', 10, 12, 'view/image/sanPhamLG/MaylanhdungLG/APNQ36GR1A3.png', 3, 3),
('Máy lạnh đứng LG APNQ45GR1A3', 43000000, 46000000, NOW(), 14, 'Máy lạnh đứng LG 4.5Hp inverter.', 10, 13, 'view/image/sanPhamLG/MaylanhdungLG/APNQ45GR1A3.png', 3, 3),
('Máy lạnh đứng LG APNQ50GR1A3', 47000000, 50000000, NOW(), 12, 'Máy lạnh đứng LG 5.0Hp inverter.', 10, 14, 'view/image/sanPhamLG/MaylanhdungLG/APNQ50GR1A3.png', 3, 3);

-- Máy lạnh đứng - Toshiba
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng Toshiba RAV-GP241ATP-E', 30500000, 33500000, NOW(), 22, 'Máy lạnh đứng Toshiba 2.5Hp inverter.', 10, 10, 'view/image/sanPhamToshiba/MaylanhdungToshiba/RAV-GP241ATP-E.png', 3, 4),
('Máy lạnh đứng Toshiba RAV-GP301ATP-E', 34500000, 37500000, NOW(), 20, 'Máy lạnh đứng Toshiba 3.0Hp inverter.', 10, 11, 'view/image/sanPhamToshiba/MaylanhdungToshiba/RAV-GP301ATP-E.png', 3, 4),
('Máy lạnh đứng Toshiba RAV-GP361ATP-E', 38500000, 41500000, NOW(), 18, 'Máy lạnh đứng Toshiba 3.5Hp inverter.', 10, 12, 'view/image/sanPhamToshiba/MaylanhdungToshiba/RAV-GP361ATP-E.png', 3, 4),
('Máy lạnh đứng Toshiba RAV-GP451ATP-E', 42500000, 45500000, NOW(), 16, 'Máy lạnh đứng Toshiba 4.5Hp inverter.', 10, 13, 'view/image/sanPhamToshiba/MaylanhdungToshiba/RAV-GP451ATP-E.png', 3, 4),
('Máy lạnh đứng Toshiba RAV-GP501ATP-E', 46500000, 49500000, NOW(), 14, 'Máy lạnh đứng Toshiba 5.0Hp inverter.', 10, 14, 'view/image/sanPhamToshiba/MaylanhdungToshiba/RAV-GP501ATP-E.png', 3, 4);

-- Máy lạnh đứng - Mitsubishi
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng Mitsubishi FDF71CR-S5', 31500000, 34500000, NOW(), 15, 'Máy lạnh đứng Mitsubishi 2.5Hp inverter.', 10, 10, 'view/image/sanPhamMitsubishi/MaylanhdungMitsubishi/FDF71CR-S5.png', 3, 5),
('Máy lạnh đứng Mitsubishi FDF100CR-S5', 35500000, 38500000, NOW(), 13, 'Máy lạnh đứng Mitsubishi 3.0Hp inverter.', 10, 11, 'view/image/sanPhamMitsubishi/MaylanhdungMitsubishi/FDF100CR-S5.png', 3, 5),
('Máy lạnh đứng Mitsubishi FDF125CR-S5', 39500000, 42500000, NOW(), 11, 'Máy lạnh đứng Mitsubishi 3.5Hp inverter.', 10, 12, 'view/image/sanPhamMitsubishi/MaylanhdungMitsubishi/FDF125CR-S5.png', 3, 5),
('Máy lạnh đứng Mitsubishi FDF140CR-S5', 43500000, 46500000, NOW(), 9, 'Máy lạnh đứng Mitsubishi 4.5Hp inverter.', 10, 13, 'view/image/sanPhamMitsubishi/MaylanhdungMitsubishi/FDF140CR-S5.png', 3, 5),
('Máy lạnh đứng Mitsubishi FDF160CR-S5', 47500000, 50500000, NOW(), 7, 'Máy lạnh đứng Mitsubishi 5.0Hp inverter.', 10, 14, 'view/image/sanPhamMitsubishi/MaylanhdungMitsubishi/FDF160CR-S5.png', 3, 5);

-- Máy lạnh đứng - Samsung
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh đứng Samsung AF28FV1D', 29500000, 31500000, NOW(), 20, 'Máy lạnh đứng Samsung 3.0Hp inverter.', 10, 10, 'view/image/sanPhamSamsung/MaylanhdungSamsung/AF28FV1D.png', 3, 6),
('Máy lạnh đứng Samsung AF36FV1D', 33500000, 35500000, NOW(), 18, 'Máy lạnh đứng Samsung 4.0Hp inverter.', 10, 11, 'view/image/sanPhamSamsung/MaylanhdungSamsung/AF36FV1D.png', 3, 6),
('Máy lạnh đứng Samsung AF48FV1D', 37500000, 39500000, NOW(), 16, 'Máy lạnh đứng Samsung 5.0Hp inverter.', 10, 12, 'view/image/sanPhamSamsung/MaylanhdungSamsung/AF48FV1D.png', 3, 6),
('Máy lạnh đứng Samsung AF56FV1D', 41500000, 43500000, NOW(), 14, 'Máy lạnh đứng Samsung 6.0Hp inverter.', 10, 13, 'view/image/sanPhamSamsung/MaylanhdungSamsung/AF56FV1D.png', 3, 6),
('Máy lạnh đứng Samsung AF60FV1D', 45500000, 47500000, NOW(), 12, 'Máy lạnh đứng Samsung 7.0Hp inverter.', 10, 14, 'view/image/sanPhamSamsung/MaylanhdungSamsung/AF60FV1D.png', 3, 6);

-- Dữ liệu mockup Máy lạnh âm trần
-- Chỉ gồm các lệnh INSERT sản phẩm cho danh mục Máy lạnh âm trần (id_danhmuc=2)

-- Máy lạnh âm trần - Daikin
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần Daikin FCFC50DVM', 25000000, 28000000, NOW(), 60, 'Máy lạnh âm trần Daikin 2.0Hp inverter.', 10, 11, 'view/image/sanPhamDaikin/MaylanhamtranDaikin/FCFC50DVM.png', 2, 1),
('Máy lạnh âm trần Daikin FCFC60DVM', 27000000, 30000000, NOW(), 55, 'Máy lạnh âm trần Daikin 2.5Hp inverter.', 10, 10, 'view/image/sanPhamDaikin/MaylanhamtranDaikin/FCFC60DVM.png', 2, 1),
('Máy lạnh âm trần Daikin FCFC71DVM', 32000000, 35000000, NOW(), 50, 'Máy lạnh âm trần Daikin 3.0Hp inverter.', 10, 9, 'view/image/sanPhamDaikin/MaylanhamtranDaikin/FCFC71DVM.png', 2, 1),
('Máy lạnh âm trần Daikin FCFC85DVM', 35000000, 38000000, NOW(), 45, 'Máy lạnh âm trần Daikin 3.5Hp inverter.', 10, 8, 'view/image/sanPhamDaikin/MaylanhamtranDaikin/FCFC85DVM.png', 2, 1),
('Máy lạnh âm trần Daikin FCFC100DVM', 39000000, 42000000, NOW(), 40, 'Máy lạnh âm trần Daikin 4.0Hp inverter.', 10, 7, 'view/image/sanPhamDaikin/MaylanhamtranDaikin/FCFC100DVM.png', 2, 1);

-- Máy lạnh âm trần - Panasonic
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần Panasonic S-21PU1H5B', 29150000, 30800000, NOW(), 38, 'Máy lạnh âm trần Panasonic 2.0Hp inverter.', 10, 11, 'view/image/sanPhamPanasonic/MaylanhamtranPanasonic/S-21PU1H5B.png', 2, 2),
('Máy lạnh âm trần Panasonic S-24PB3H5', 32500000, 34500000, NOW(), 36, 'Máy lạnh âm trần Panasonic 2.5Hp inverter.', 10, 10, 'view/image/sanPhamPanasonic/MaylanhamtranPanasonic/S-24PB3H5.png', 2, 2),
('Máy lạnh âm trần Panasonic S-30PU1H5B', 26850000, 28500000, NOW(), 34, 'Máy lạnh âm trần Panasonic 3.0Hp inverter.', 10, 9, 'view/image/sanPhamPanasonic/MaylanhamtranPanasonic/S-30PU1H5B.png', 2, 2),
('Máy lạnh âm trần Panasonic S-36PU1H5B', 30500000, 32500000, NOW(), 32, 'Máy lạnh âm trần Panasonic 3.5Hp inverter.', 10, 8, 'view/image/sanPhamPanasonic/MaylanhamtranPanasonic/S-36PU1H5B.png', 2, 2),
('Máy lạnh âm trần Panasonic S-50PU1H5B', 35600000, 36500000, NOW(), 30, 'Máy lạnh âm trần Panasonic 5.0Hp inverter.', 10, 7, 'view/image/sanPhamPanasonic/MaylanhamtranPanasonic/S-50PU1H5B.png', 2, 2);

-- Máy lạnh âm trần - LG
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần LG ATNQ18GPLE7', 21000000, 23000000, NOW(), 28, 'Máy lạnh âm trần LG 2.0Hp inverter.', 10, 11, 'view/image/sanPhamLG/MaylanhamtranLG/ATNQ18GPLE7.png', 2, 3),
('Máy lạnh âm trần LG ATNQ24GPLE7', 25000000, 27000000, NOW(), 26, 'Máy lạnh âm trần LG 2.5Hp inverter.', 10, 10, 'view/image/sanPhamLG/MaylanhamtranLG/ATNQ24GPLE7.png', 2, 3),
('Máy lạnh âm trần LG ATNQ30GPLE7', 29000000, 31000000, NOW(), 24, 'Máy lạnh âm trần LG 3.0Hp inverter.', 10, 9, 'view/image/sanPhamLG/MaylanhamtranLG/ATNQ30GPLE7.png', 2, 3),
('Máy lạnh âm trần LG ATNQ36GPLE7', 33000000, 35000000, NOW(), 22, 'Máy lạnh âm trần LG 3.5Hp inverter.', 10, 8, 'view/image/sanPhamLG/MaylanhamtranLG/ATNQ36GPLE7.png', 2, 3),
('Máy lạnh âm trần LG ATNQ48GPLE7', 37000000, 39000000, NOW(), 20, 'Máy lạnh âm trần LG 5.0Hp inverter.', 10, 7, 'view/image/sanPhamLG/MaylanhamtranLG/ATNQ48GPLE7.png', 2, 3);

-- Máy lạnh âm trần - Toshiba
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần Toshiba RAV-SE561CTP-E', 26000000, 29000000, NOW(), 18, 'Máy lạnh âm trần Toshiba 2.0Hp inverter.', 10, 11, 'view/image/sanPhamToshiba/MaylanhamtranToshiba/RAV-SE561CTP-E.png', 2, 4),
('Máy lạnh âm trần Toshiba RAV-SE801CTP-E', 31000000, 34000000, NOW(), 16, 'Máy lạnh âm trần Toshiba 3.0Hp inverter.', 10, 10, 'view/image/sanPhamToshiba/MaylanhamtranToshiba/RAV-SE801CTP-E.png', 2, 4),
('Máy lạnh âm trần Toshiba RAV-SE1001CTP-E', 35000000, 38000000, NOW(), 14, 'Máy lạnh âm trần Toshiba 4.0Hp inverter.', 10, 9, 'view/image/sanPhamToshiba/MaylanhamtranToshiba/RAV-SE1001CTP-E.png', 2, 4),
('Máy lạnh âm trần Toshiba RAV-SE1401CTP-E', 40000000, 43000000, NOW(), 12, 'Máy lạnh âm trần Toshiba 5.0Hp inverter.', 10, 8, 'view/image/sanPhamToshiba/MaylanhamtranToshiba/RAV-SE1401CTP-E.png', 2, 4),
('Máy lạnh âm trần Toshiba RAV-SE1801CTP-E', 45000000, 48000000, NOW(), 10, 'Máy lạnh âm trần Toshiba 6.0Hp inverter.', 10, 7, 'view/image/sanPhamToshiba/MaylanhamtranToshiba/RAV-SE1801CTP-E.png', 2, 4);

-- Máy lạnh âm trần - Mitsubishi
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần Mitsubishi PLA-RP50BA', 25500000, 28500000, NOW(), 8, 'Máy lạnh âm trần Mitsubishi 2.0Hp inverter.', 10, 11, 'view/image/sanPhamMitsubishi/MaylanhamtranMitsubishi/PLA-RP50BA.png', 2, 5),
('Máy lạnh âm trần Mitsubishi PLA-RP60BA', 27500000, 30500000, NOW(), 7, 'Máy lạnh âm trần Mitsubishi 2.5Hp inverter.', 10, 10, 'view/image/sanPhamMitsubishi/MaylanhamtranMitsubishi/PLA-RP60BA.png', 2, 5),
('Máy lạnh âm trần Mitsubishi PLA-RP71BA', 32500000, 35500000, NOW(), 6, 'Máy lạnh âm trần Mitsubishi 3.0Hp inverter.', 10, 9, 'view/image/sanPhamMitsubishi/MaylanhamtranMitsubishi/PLA-RP71BA.png', 2, 5),
('Máy lạnh âm trần Mitsubishi PLA-RP100BA', 37500000, 40500000, NOW(), 5, 'Máy lạnh âm trần Mitsubishi 4.0Hp inverter.', 10, 8, 'view/image/sanPhamMitsubishi/MaylanhamtranMitsubishi/PLA-RP100BA.png', 2, 5),
('Máy lạnh âm trần Mitsubishi PLA-RP125BA', 42500000, 45500000, NOW(), 4, 'Máy lạnh âm trần Mitsubishi 5.0Hp inverter.', 10, 7, 'view/image/sanPhamMitsubishi/MaylanhamtranMitsubishi/PLA-RP125BA.png', 2, 5);

-- Máy lạnh âm trần - Samsung
INSERT INTO sanpham (Name, Price, Price_old, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh âm trần Samsung AC100MN4PKC/EA', 32000000, 34000000, NOW(), 20, 'Máy lạnh âm trần Samsung 4.0Hp inverter.', 10, 10, 'view/image/sanPhamSamsung/MaylanhamtranSamsung/AC100MN4PKC-EA.png', 2, 6),
('Máy lạnh âm trần Samsung AC120MN4PKC/EA', 35000000, 37000000, NOW(), 18, 'Máy lạnh âm trần Samsung 5.0Hp inverter.', 10, 11, 'view/image/sanPhamSamsung/MaylanhamtranSamsung/AC120MN4PKC-EA.png', 2, 6),
('Máy lạnh âm trần Samsung AC140MN4PKC/EA', 38000000, 40000000, NOW(), 16, 'Máy lạnh âm trần Samsung 6.0Hp inverter.', 10, 12, 'view/image/sanPhamSamsung/MaylanhamtranSamsung/AC140MN4PKC-EA.png', 2, 6),
('Máy lạnh âm trần Samsung AC160MN4PKC/EA', 41000000, 43000000, NOW(), 14, 'Máy lạnh âm trần Samsung 7.0Hp inverter.', 10, 13, 'view/image/sanPhamSamsung/MaylanhamtranSamsung/AC160MN4PKC-EA.png', 2, 6),
('Máy lạnh âm trần Samsung AC180MN4PKC/EA', 44000000, 46000000, NOW(), 12, 'Máy lạnh âm trần Samsung 8.0Hp inverter.', 10, 14, 'view/image/sanPhamSamsung/MaylanhamtranSamsung/AC180MN4PKC-EA.png', 2, 6);


