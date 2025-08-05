-- File import database cho hệ thống đơn hàng
-- Chạy file này để tạo các bảng cần thiết cho quản lý đơn hàng

USE dienlanh_shop;

-- Tạo bảng đơn hàng nếu chưa tồn tại
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

-- Tạo bảng chi tiết đơn hàng nếu chưa tồn tại
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

-- Tạo bảng lịch sử trạng thái đơn hàng nếu chưa tồn tại
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

-- Tạo các index để tối ưu hiệu suất
CREATE INDEX IF NOT EXISTS idx_donhang_user ON donhang(id_user);
CREATE INDEX IF NOT EXISTS idx_donhang_status ON donhang(trangthai);
CREATE INDEX IF NOT EXISTS idx_donhang_date ON donhang(ngaydat);
CREATE INDEX IF NOT EXISTS idx_dh_chitiet_order ON dh_chitiet(id_dh);
CREATE INDEX IF NOT EXISTS idx_dh_chitiet_product ON dh_chitiet(id_sp);

-- Thêm dữ liệu mẫu cho đơn hàng (nếu cần test)
-- INSERT INTO donhang (id_user, tongdh, ngaydat, trangthai, ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao, phuong_thuc_thanh_toan) VALUES 
-- (2, 15000000, NOW(), 'Chờ xác nhận', 'Nguyễn Văn A', '0987654321', '123 Đường ABC, Quận 1, TP.HCM', 'Tiền mặt');

-- INSERT INTO dh_chitiet (id_sp, id_dh, soluong, tong_dh, gia_ban) VALUES 
-- (1, 1, 1, 15000000, 15000000);

-- Thông báo hoàn thành
SELECT 'Database đơn hàng đã được tạo thành công!' as message; 