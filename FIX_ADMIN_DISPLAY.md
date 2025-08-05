# Hướng Dẫn Sửa Lỗi Hiển Thị Admin Panel

## Vấn Đề
Sau khi đăng nhập admin, không thấy menu chức năng admin hiển thị.

## Giải Pháp Đã Áp Dụng

### 1. Tạo File Admin Đơn Giản
- Đã tạo file `view/pages/admin_simple.php` với giao diện đơn giản và rõ ràng
- File này không phụ thuộc vào layout phức tạp
- Sử dụng Tailwind CSS trực tiếp từ CDN

### 2. Cập Nhật Controller
- Đã cập nhật `controller/index.php` để sử dụng file admin đơn giản
- Thay đổi từ `admin.php` sang `admin_simple.php`

### 3. Các Chức Năng Admin Có Sẵn

#### Menu Chính:
- **Bảng điều khiển** - Dashboard tổng quan
- **Quản lý sản phẩm** - Thêm, sửa, xóa sản phẩm
- **Quản lý danh mục** - Quản lý các danh mục sản phẩm
- **Quản lý đơn hàng** - Xem và cập nhật trạng thái đơn hàng
- **Quản lý tồn kho** - Quản lý số lượng sản phẩm
- **Quản lý người dùng** - Quản lý tài khoản người dùng
- **Báo cáo doanh thu** - Thống kê doanh thu
- **Cài đặt hệ thống** - Cấu hình hệ thống

## Cách Kiểm Tra

### 1. Đăng Nhập Admin
```
URL: http://localhost/project/index.php?act=login
Email: admin
Password: password
```

### 2. Truy Cập Admin Panel
```
URL: http://localhost/project/index.php?act=admin
```

### 3. Kiểm Tra Các Chức Năng
- **Dashboard**: `http://localhost/project/index.php?act=admin&action=dashboard`
- **Products**: `http://localhost/project/index.php?act=admin&action=products`
- **Categories**: `http://localhost/project/index.php?act=admin&action=categories`
- **Orders**: `http://localhost/project/index.php?act=admin_orders`
- **Product Management**: `http://localhost/project/index.php?act=admin_product_management`
- **Users**: `http://localhost/project/index.php?act=admin&action=users`
- **Reports**: `http://localhost/project/index.php?act=admin&action=reports`
- **Settings**: `http://localhost/project/index.php?act=admin&action=settings`

## Nếu Vẫn Không Hiển Thị

### 1. Kiểm Tra Session
- Đảm bảo đã đăng nhập thành công
- Kiểm tra quyền admin trong database

### 2. Kiểm Tra Database
```sql
SELECT * FROM user WHERE position = 'admin';
```

### 3. Kiểm Tra File Test
Truy cập: `http://localhost/project/test_admin_display.php`
- File này sẽ hiển thị thông tin session và user
- Giúp xác định vấn đề

### 4. Kiểm Tra Console Browser
- Mở Developer Tools (F12)
- Kiểm tra tab Console có lỗi JavaScript không
- Kiểm tra tab Network có lỗi tải file không

## Khôi Phục File Admin Gốc

Nếu muốn sử dụng file admin gốc, thay đổi lại trong `controller/index.php`:

```php
case 'admin':
    include "../view/pages/admin.php";  // Thay vì admin_simple.php
    break;
```

## Lưu Ý
- File `admin_simple.php` là phiên bản đơn giản để test
- Nếu hoạt động tốt, có thể cải thiện thêm
- Nếu không hoạt động, vấn đề có thể ở session hoặc database 