-- File cập nhật nhanh database cho đơn hàng
-- Chạy file này trong phpMyAdmin để cập nhật database hiện có

USE dienlanh_shop;

-- Tạo bảng đơn hàng
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

-- Tạo bảng chi tiết đơn hàng
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

-- Tạo bảng lịch sử trạng thái
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

-- Thêm tài khoản admin (nếu chưa có)
INSERT IGNORE INTO taikhoan (username, password, fullname, email, phone, address, position) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@dienlanh.com', '0123456789', '123 Đường ABC, Quận 1, TP.HCM', 'admin');

-- Thông báo hoàn thành
SELECT 'Cập nhật thành công!' as message; 