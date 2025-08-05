# Hướng dẫn cài đặt nhanh - Điện Lạnh KV Website

## Yêu cầu hệ thống

- **Web Server**: XAMPP, WAMP, hoặc LAMP
- **PHP**: 7.4 trở lên
- **MySQL**: 5.7 trở lên
- **Browser**: Chrome, Firefox, Safari, Edge

## Bước 1: Cài đặt XAMPP

1. Tải XAMPP từ: https://www.apachefriends.org/
2. Cài đặt XAMPP với các module: Apache, MySQL, PHP
3. Khởi động Apache và MySQL services

## Bước 2: Cài đặt project

1. Copy toàn bộ thư mục `project` vào `htdocs` của XAMPP
2. Đường dẫn sẽ là: `C:\xampp\htdocs\project\`

## Bước 3: Tạo database

1. Mở phpMyAdmin: http://localhost/phpmyadmin
2. Tạo database mới tên: `dienlanh_shop`
3. Import file: `model/database_complete.sql`
4. Import file: `model/add_reviews_table.sql`
5. Import file: `model/add_wishlist_table.sql`

## Bước 4: Cấu hình database

1. Mở file: `model/database.php`
2. Kiểm tra thông tin kết nối:
   ```php
   private $servername = "localhost";
   private $username = "root";
   private $password = "";
   private $databasename = "dienlanh_shop";
   ```

## Bước 5: Tạo tài khoản admin

1. Truy cập: http://localhost/project/
2. Đăng ký tài khoản mới
3. Vào phpMyAdmin, tìm bảng `taikhoan`
4. Cập nhật `position` thành `admin` cho tài khoản vừa tạo

## Bước 6: Kiểm tra cài đặt

1. Truy cập: http://localhost/project/
2. Kiểm tra các chức năng:
   - Đăng nhập/đăng ký
   - Xem sản phẩm
   - Thêm vào giỏ hàng
   - Wishlist
   - Profile

## Cấu trúc thư mục

```
project/
├── api/                    # API endpoints
├── controller/             # Controller logic
├── helpers/               # Helper functions
├── model/                 # Database models
├── view/                  # View templates
│   ├── css/              # Stylesheets
│   ├── image/            # Product images
│   ├── layout/           # Layout templates
│   └── pages/            # Page templates
├── index.php             # Main entry point
└── README.md             # Documentation
```

## Chức năng chính

### 1. Trang chủ
- Hiển thị banner và sản phẩm nổi bật
- Menu navigation
- Tìm kiếm sản phẩm

### 2. Sản phẩm
- Danh sách sản phẩm với bộ lọc
- Chi tiết sản phẩm
- Thêm vào giỏ hàng/wishlist

### 3. Giỏ hàng
- Thêm/xóa sản phẩm
- Cập nhật số lượng
- Thanh toán

### 4. Wishlist
- Lưu sản phẩm yêu thích
- Quản lý danh sách
- URL: `/project/index.php?act=wishlist`

### 5. Profile
- Cập nhật thông tin cá nhân
- Đổi mật khẩu
- URL: `/project/index.php?act=profile`

### 6. Đơn hàng
- Xem lịch sử đơn hàng
- Chi tiết đơn hàng
- URL: `/project/index.php?act=my_orders`

### 7. Admin
- Quản lý sản phẩm
- Quản lý đơn hàng
- Thống kê
- URL: `/project/index.php?act=admin`

## API Endpoints

### Wishlist API
- `POST /project/api/wishlist.php` - Thêm/xóa sản phẩm khỏi wishlist
- `GET /project/api/wishlist.php?action=count` - Đếm số sản phẩm trong wishlist
- `GET /project/api/wishlist.php?action=list` - Lấy danh sách wishlist

### Search API
- `GET /project/api/search_suggestions.php` - Gợi ý tìm kiếm

## Troubleshooting

### Lỗi kết nối database
1. Kiểm tra MySQL service đã chạy chưa
2. Kiểm tra thông tin kết nối trong `model/database.php`
3. Kiểm tra database `dienlanh_shop` đã tạo chưa

### Lỗi 404
1. Kiểm tra Apache service đã chạy chưa
2. Kiểm tra file `.htaccess` (nếu có)
3. Kiểm tra đường dẫn project

### Lỗi permission
1. Kiểm tra quyền ghi cho thư mục `view/image/`
2. Kiểm tra quyền ghi cho file log

### Lỗi JWT
1. Kiểm tra cookie settings
2. Kiểm tra JWT secret key trong `helpers/jwt_helper.php`

## Bảo mật

### Cấu hình bảo mật
1. Thay đổi JWT secret key trong production
2. Cấu hình HTTPS
3. Bật error reporting = 0 trong production
4. Cấu hình firewall

### Database bảo mật
1. Tạo user database riêng (không dùng root)
2. Giới hạn quyền truy cập database
3. Backup database định kỳ

## Performance

### Tối ưu hiệu suất
1. Bật OPcache cho PHP
2. Cấu hình MySQL query cache
3. Sử dụng CDN cho static files
4. Nén CSS/JS files

### Monitoring
1. Kiểm tra error logs
2. Monitor database performance
3. Kiểm tra memory usage

## Backup & Restore

### Backup
```bash
# Backup database
mysqldump -u root -p dienlanh_shop > backup.sql

# Backup files
zip -r project_backup.zip project/
```

### Restore
```bash
# Restore database
mysql -u root -p dienlanh_shop < backup.sql

# Restore files
unzip project_backup.zip
```

## Support

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra error logs
2. Kiểm tra hướng dẫn troubleshooting
3. Liên hệ support team

---

**Lưu ý**: Đây là phiên bản development. Trước khi deploy production, vui lòng cấu hình bảo mật và tối ưu hiệu suất. 