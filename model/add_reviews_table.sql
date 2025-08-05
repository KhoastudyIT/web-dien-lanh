-- Thêm bảng đánh giá sản phẩm
CREATE TABLE IF NOT EXISTS danh_gia (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_sp INT NOT NULL,
  rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
  comment TEXT,
  ngay_danh_gia DATETIME DEFAULT CURRENT_TIMESTAMP,
  ngay_cap_nhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES taikhoan(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_sp) REFERENCES sanpham(id_sp) ON DELETE CASCADE,
  UNIQUE KEY unique_user_product (id_user, id_sp)
);

-- Thêm cột rating_trung_binh vào bảng sanpham
ALTER TABLE sanpham ADD COLUMN rating_trung_binh DECIMAL(3,2) DEFAULT 0.00;

-- Tạo index để tối ưu hiệu suất
CREATE INDEX idx_danh_gia_product ON danh_gia(id_sp);
CREATE INDEX idx_danh_gia_user ON danh_gia(id_user);
CREATE INDEX idx_danh_gia_date ON danh_gia(ngay_danh_gia);

-- Thêm dữ liệu mẫu cho đánh giá
INSERT INTO danh_gia (id_user, id_sp, rating, comment) VALUES 
(1, 1, 5, 'Sản phẩm chất lượng tốt, làm lạnh nhanh và tiết kiệm điện'),
(1, 2, 4, 'Máy lạnh hoạt động ổn định, âm thanh nhỏ'),
(2, 1, 5, 'Rất hài lòng với sản phẩm này'),
(2, 3, 3, 'Chất lượng tạm được, giá cả hợp lý'),
(3, 2, 4, 'Máy lạnh tốt, lắp đặt chuyên nghiệp'),
(3, 4, 5, 'Sản phẩm xuất sắc, đúng như quảng cáo'),
(4, 1, 4, 'Máy lạnh hoạt động tốt, tiết kiệm điện'),
(4, 5, 5, 'Chất lượng cao, dịch vụ tốt'),
(5, 3, 4, 'Sản phẩm đáng tin cậy'),
(5, 6, 5, 'Máy lạnh tuyệt vời, làm lạnh nhanh');

-- Cập nhật rating trung bình cho các sản phẩm
UPDATE sanpham SET rating_trung_binh = (
    SELECT AVG(rating) FROM danh_gia WHERE id_sp = sanpham.id_sp
) WHERE id_sp IN (SELECT DISTINCT id_sp FROM danh_gia); 