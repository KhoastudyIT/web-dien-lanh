CREATE DATABASE IF NOT EXISTS abc CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE abc;

-- Bảng danh mục
CREATE TABLE danhmuc (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

-- Bảng hãng sản xuất
CREATE TABLE hang (
  id_hang INT AUTO_INCREMENT PRIMARY KEY,
  ten_hang VARCHAR(100) NOT NULL,
  logo_hang VARCHAR(250) DEFAULT NULL,
  mo_ta TEXT DEFAULT NULL,
  quoc_gia VARCHAR(50) DEFAULT NULL
);

-- Bảng sản phẩm
CREATE TABLE sanpham (
  id_sp INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(250) NOT NULL,
  Price INT NOT NULL,
  Date_import DATETIME NOT NULL,
  Viewsp INT DEFAULT NULL,
  Decribe LONGTEXT DEFAULT NULL,
  Mount INT NOT NULL,
  Sale INT DEFAULT NULL,
  image VARCHAR(250) DEFAULT NULL,
  id_danhmuc INT NOT NULL,
  id_hang INT NOT NULL,
  FOREIGN KEY (id_danhmuc) REFERENCES danhmuc(id),
  FOREIGN KEY (id_hang) REFERENCES hang(id_hang)
);

-- Bảng tài khoản
CREATE TABLE taikhoan (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(20) NOT NULL,
  password VARCHAR(255) NOT NULL,
  fullname VARCHAR(250) NOT NULL,
  email VARCHAR(250) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  address VARCHAR(250) NOT NULL,
  position VARCHAR(12) NOT NULL
);

-- Thêm index cho username và email để tăng hiệu suất
ALTER TABLE taikhoan ADD UNIQUE INDEX idx_username (username);
ALTER TABLE taikhoan ADD UNIQUE INDEX idx_email (email);

-- Bảng đơn hàng
CREATE TABLE donhang (
  id_dh INT AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  tongdh INT NOT NULL,
  ngaydat DATETIME NOT NULL,
  trangthai VARCHAR(30),
  FOREIGN KEY (id_user) REFERENCES taikhoan(id_user)
);

-- Bảng chi tiết đơn hàng
CREATE TABLE dh_chitiet (
  id_chitiet INT AUTO_INCREMENT PRIMARY KEY,
  id_sp INT NOT NULL,
  id_dh INT NOT NULL,
  soluong INT NOT NULL,
  tong_dh INT NOT NULL,
  FOREIGN KEY (id_sp) REFERENCES sanpham(id_sp),
  FOREIGN KEY (id_dh) REFERENCES donhang(id_dh)
);
