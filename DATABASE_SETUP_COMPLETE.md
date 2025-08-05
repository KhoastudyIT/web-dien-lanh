# DATABASE SETUP COMPLETE - SHOP ĐIỆN LẠNH

## 📋 Tổng quan

Đã hoàn thành việc tạo lại từ đầu toàn bộ cơ sở dữ liệu cho shop điện lạnh với đầy đủ dữ liệu mockup. File `model/database_complete.sql` đã được cập nhật hoàn toàn với cấu trúc mới và dữ liệu đầy đủ.

## 🗂️ Cấu trúc Database

### Các bảng chính:

1. **`danhmuc`** - Danh mục sản phẩm (5 danh mục)
2. **`hang`** - Hãng sản xuất (6 hãng)
3. **`sanpham`** - Sản phẩm (150 sản phẩm)
4. **`taikhoan`** - Tài khoản người dùng (3 tài khoản mẫu)
5. **`donhang`** - Đơn hàng (3 đơn hàng mẫu)
6. **`dh_chitiet`** - Chi tiết đơn hàng
7. **`lich_su_trang_thai`** - Lịch sử trạng thái đơn hàng
8. **`danh_gia`** - Đánh giá sản phẩm (20 đánh giá mẫu)
9. **`wishlist`** - Danh sách yêu thích (10 sản phẩm mẫu)

## 📊 Dữ liệu Mockup

### Danh mục sản phẩm (5 danh mục):
1. Máy lạnh treo tường
2. Máy lạnh âm trần
3. Máy lạnh đứng
4. Máy lạnh giấu trần
5. Tủ lạnh

### Hãng sản xuất (6 hãng):
1. Daikin
2. Panasonic
3. LG
4. Toshiba
5. Mitsubishi
6. Samsung

### Sản phẩm (150 sản phẩm):
- **Máy lạnh treo tường**: 30 sản phẩm (5 sản phẩm/hãng)
- **Máy lạnh âm trần**: 30 sản phẩm (5 sản phẩm/hãng)
- **Máy lạnh đứng**: 30 sản phẩm (5 sản phẩm/hãng)
- **Máy lạnh giấu trần**: 30 sản phẩm (5 sản phẩm/hãng)
- **Tủ lạnh**: 30 sản phẩm (5 sản phẩm/hãng)

### Tài khoản mẫu:
- **Admin**: admin@dienlanh.com (password: password)
- **User 1**: user1@email.com (password: password)
- **User 2**: user2@email.com (password: password)

## 🔧 Tính năng mới được thêm:

### 1. Bảng đánh giá (`danh_gia`)
- Hỗ trợ đánh giá sản phẩm từ 1-5 sao
- Comment đánh giá
- Ràng buộc unique cho mỗi user chỉ đánh giá 1 lần/sản phẩm
- Tự động cập nhật rating trung bình cho sản phẩm

### 2. Bảng wishlist
- Lưu trữ sản phẩm yêu thích của user
- Ràng buộc unique cho mỗi user chỉ thêm 1 lần/sản phẩm

### 3. Cải tiến bảng sản phẩm
- Thêm cột `rating_trung_binh` để hiển thị đánh giá trung bình
- Thêm indexes để tối ưu hiệu suất truy vấn
- Thêm timestamps cho tracking

### 4. Cải tiến bảng đơn hàng
- Thêm lịch sử trạng thái đơn hàng
- Tracking đầy đủ quá trình xử lý đơn hàng

## 📈 Thống kê dữ liệu:

- **Tổng số sản phẩm**: 150
- **Tổng số danh mục**: 5
- **Tổng số hãng**: 6
- **Tổng số tài khoản**: 3
- **Tổng số đơn hàng**: 3
- **Tổng số đánh giá**: 20
- **Tổng số wishlist**: 10

## 🚀 Cách sử dụng:

### 1. Import database:
```sql
-- Chạy file database_complete.sql trong MySQL
source /path/to/model/database_complete.sql
```

### 2. Kiểm tra dữ liệu:
```sql
-- Xem tất cả sản phẩm
SELECT * FROM sanpham;

-- Xem sản phẩm theo danh mục
SELECT * FROM sanpham WHERE id_danhmuc = 1;

-- Xem sản phẩm theo hãng
SELECT * FROM sanpham WHERE id_hang = 1;

-- Xem đánh giá sản phẩm
SELECT s.Name, d.rating, d.comment, t.fullname 
FROM danh_gia d 
JOIN sanpham s ON d.id_sp = s.id_sp 
JOIN taikhoan t ON d.id_user = t.id_user;
```

## 🔐 Thông tin đăng nhập:

### Admin:
- Username: `admin`
- Password: `password`
- Email: `admin@dienlanh.com`

### User thường:
- Username: `user1` hoặc `user2`
- Password: `password`

## 📝 Ghi chú:

1. **Mật khẩu**: Tất cả tài khoản đều có mật khẩu là `password` (đã được hash bằng bcrypt)
2. **Hình ảnh**: Các đường dẫn hình ảnh đã được cập nhật theo cấu trúc thư mục hiện tại
3. **Giá cả**: Giá sản phẩm được thiết lập theo thị trường thực tế
4. **Đánh giá**: Có 20 đánh giá mẫu với rating từ 4-5 sao
5. **Đơn hàng**: Có 3 đơn hàng mẫu với các trạng thái khác nhau

## ✅ Hoàn thành:

- ✅ Tạo lại toàn bộ cấu trúc database
- ✅ Thêm đầy đủ dữ liệu mockup
- ✅ Thêm các bảng mới (đánh giá, wishlist)
- ✅ Cải tiến cấu trúc bảng hiện có
- ✅ Thêm indexes để tối ưu hiệu suất
- ✅ Thêm dữ liệu mẫu cho tất cả bảng
- ✅ Cập nhật rating trung bình tự động
- ✅ Thêm thống kê tổng quan

Database đã sẵn sàng để sử dụng cho website shop điện lạnh! 