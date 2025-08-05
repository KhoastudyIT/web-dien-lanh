-- File cập nhật database cho hệ thống đơn hàng
-- Chạy file này để cập nhật database hiện có trên XAMPP
-- File này sẽ thêm các bảng và cột mới mà không ảnh hưởng đến dữ liệu hiện có

USE dienlanh_shop;

-- 1. Cập nhật bảng taikhoan (thêm cột mới nếu chưa có)
ALTER TABLE taikhoan 
ADD COLUMN IF NOT EXISTS created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
MODIFY COLUMN position VARCHAR(12) NOT NULL DEFAULT 'user';

-- 2. Tạo bảng đơn hàng nếu chưa tồn tại
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

-- 3. Tạo bảng chi tiết đơn hàng nếu chưa tồn tại
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

-- 4. Tạo bảng lịch sử trạng thái đơn hàng nếu chưa tồn tại
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

-- 5. Tạo các index để tối ưu hiệu suất (chỉ tạo nếu chưa tồn tại)
CREATE INDEX IF NOT EXISTS idx_donhang_user ON donhang(id_user);
CREATE INDEX IF NOT EXISTS idx_donhang_status ON donhang(trangthai);
CREATE INDEX IF NOT EXISTS idx_donhang_date ON donhang(ngaydat);
CREATE INDEX IF NOT EXISTS idx_dh_chitiet_order ON dh_chitiet(id_dh);
CREATE INDEX IF NOT EXISTS idx_dh_chitiet_product ON dh_chitiet(id_sp);


-- 9. Kiểm tra và hiển thị kết quả
SELECT 'Cập nhật database thành công!' as message;

-- 10. Hiển thị thông tin các bảng đã tạo
SHOW TABLES LIKE 'donhang';
SHOW TABLES LIKE 'dh_chitiet';
SHOW TABLES LIKE 'lich_su_trang_thai';

-- 11. Hiển thị thông tin tài khoản mẫu
SELECT username, fullname, position FROM taikhoan WHERE username IN ('admin', 'user'); 