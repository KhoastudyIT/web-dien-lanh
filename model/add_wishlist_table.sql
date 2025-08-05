-- Thêm bảng wishlist (danh sách yêu thích)
CREATE TABLE IF NOT EXISTS wishlist (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_sp INT NOT NULL,
  ngay_them DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user) REFERENCES taikhoan(id_user) ON DELETE CASCADE,
  FOREIGN KEY (id_sp) REFERENCES sanpham(id_sp) ON DELETE CASCADE,
  UNIQUE KEY unique_user_product (id_user, id_sp)
);

-- Tạo index để tối ưu hiệu suất
CREATE INDEX idx_wishlist_user ON wishlist(id_user);
CREATE INDEX idx_wishlist_product ON wishlist(id_sp);
CREATE INDEX idx_wishlist_date ON wishlist(ngay_them);

-- Thêm dữ liệu mẫu cho wishlist
INSERT INTO wishlist (id_user, id_sp) VALUES 
(1, 1),
(1, 3),
(1, 5),
(2, 2),
(2, 4),
(2, 6),
(3, 1),
(3, 2),
(4, 3),
(4, 5),
(5, 1),
(5, 4); 