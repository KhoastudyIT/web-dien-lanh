# Changelog - Điện Lạnh KV Website

## [2.0.0] - 2025-01-XX

### Đã sửa lỗi
- ✅ Sửa lỗi class `DonHang` sử dụng sai tên class database
- ✅ Thêm các method cần thiết cho class `database` (beginTransaction, commit, rollback, prepare, lastInsertId)
- ✅ Cập nhật JWT secret key để bảo mật hơn
- ✅ Sửa lỗi trong model/donhang.php để sử dụng đúng tên class

### Chức năng mới
- ✨ **Trang Profile**: Cho phép người dùng cập nhật thông tin cá nhân và đổi mật khẩu
- ✨ **Hệ thống đánh giá sản phẩm**: Cho phép người dùng đánh giá và bình luận sản phẩm
- ✨ **Wishlist (Danh sách yêu thích)**: Cho phép người dùng lưu sản phẩm yêu thích
- ✨ **API Wishlist**: API để quản lý wishlist (thêm, xóa, kiểm tra)

### Cải tiến giao diện
- 🎨 Cập nhật trang profile với giao diện hiện đại
- 🎨 Thêm trang wishlist với giao diện đẹp
- 🎨 Cập nhật header với link đến các trang mới
- 🎨 Thêm icon wishlist trong header

### Cơ sở dữ liệu
- 🗄️ Thêm bảng `danh_gia` cho hệ thống đánh giá sản phẩm
- 🗄️ Thêm bảng `wishlist` cho danh sách yêu thích
- 🗄️ Thêm cột `rating_trung_binh` vào bảng `sanpham`
- 🗄️ Tạo các index để tối ưu hiệu suất

### Model mới
- 📦 `Review.php`: Quản lý đánh giá sản phẩm
- 📦 `Wishlist.php`: Quản lý danh sách yêu thích

### API mới
- 🔌 `api/wishlist.php`: API để quản lý wishlist

### Hướng dẫn sử dụng

#### 1. Cài đặt database
```sql
-- Chạy các file SQL sau:
model/add_reviews_table.sql
model/add_wishlist_table.sql
```

#### 2. Chức năng Profile
- Truy cập: `/project/index.php?act=profile`
- Cho phép cập nhật thông tin cá nhân
- Cho phép đổi mật khẩu

#### 3. Wishlist
- Truy cập: `/project/index.php?act=wishlist`
- Thêm sản phẩm vào wishlist từ trang chi tiết sản phẩm
- Xóa sản phẩm khỏi wishlist
- Thêm sản phẩm từ wishlist vào giỏ hàng

#### 4. Đánh giá sản phẩm
- Người dùng có thể đánh giá sản phẩm từ 1-5 sao
- Thêm bình luận cho sản phẩm
- Hiển thị rating trung bình của sản phẩm

### Bảo mật
- 🔒 Cập nhật JWT secret key
- 🔒 Kiểm tra quyền truy cập cho các trang mới
- 🔒 Validation dữ liệu đầu vào

### Hiệu suất
- ⚡ Tối ưu database với các index
- ⚡ Lazy loading cho danh sách sản phẩm

## [1.0.0] - 2024-XX-XX

### Chức năng cơ bản
- ✅ Hệ thống đăng nhập/đăng ký với JWT
- ✅ Quản lý sản phẩm và danh mục
- ✅ Giỏ hàng và thanh toán
- ✅ Quản lý đơn hàng
- ✅ Trang admin
- ✅ Tìm kiếm sản phẩm cơ bản

---

## Hướng dẫn phát triển tiếp theo

### Chức năng có thể thêm
1. **So sánh sản phẩm**: Cho phép so sánh nhiều sản phẩm
2. **Mã giảm giá**: Hệ thống coupon và voucher
3. **Thông báo**: Email/SMS thông báo trạng thái đơn hàng
4. **Báo cáo**: Thống kê doanh thu, sản phẩm bán chạy
5. **Đa ngôn ngữ**: Hỗ trợ tiếng Anh
6. **Mobile app**: Ứng dụng di động
7. **Payment gateway**: Tích hợp cổng thanh toán online
8. **SEO**: Tối ưu hóa tìm kiếm
9. **Cache**: Redis cache để tăng hiệu suất
10. **Backup**: Hệ thống sao lưu tự động

### Cải tiến kỹ thuật
1. **API RESTful**: Chuẩn hóa API
2. **Unit Testing**: Viết test cho các chức năng
3. **Docker**: Container hóa ứng dụng
4. **CI/CD**: Tự động hóa deploy
5. **Monitoring**: Giám sát hiệu suất
6. **Security**: Bảo mật nâng cao
7. **Performance**: Tối ưu hóa database và cache
8. **Accessibility**: Hỗ trợ người khuyết tật
9. **PWA**: Progressive Web App
10. **Microservices**: Kiến trúc microservices 