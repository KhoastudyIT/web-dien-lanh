# Hướng Dẫn Truy Cập Admin Panel

## Vấn Đề
Không thấy link để vào trang quản lý admin.

## Giải Pháp Đã Áp Dụng

### 1. Thêm Link Admin vào Header
- Đã thêm link "Quản lý Admin" vào menu dropdown của user
- Link chỉ hiển thị khi user có quyền admin (`position = 'admin'`)

### 2. Cách Truy Cập Admin

#### Bước 1: Đăng Nhập Admin
```
URL: http://localhost/project/index.php?act=login
Email: admin
Password: password
```

#### Bước 2: Truy Cập Admin Panel
Sau khi đăng nhập, bạn sẽ thấy tên user ở góc phải header. Hãy:

1. **Hover chuột** vào tên user (ví dụ: "Admin User")
2. **Menu dropdown** sẽ xuất hiện
3. **Click vào "Quản lý Admin"** (màu xanh)

#### Bước 3: Hoặc Truy Cập Trực Tiếp
```
URL: http://localhost/project/index.php?act=admin
```

### 3. Các Chức Năng Admin Có Sẵn

#### Menu Chính trong Admin Panel:
- **Bảng điều khiển** - Dashboard tổng quan
- **Quản lý sản phẩm** - Thêm, sửa, xóa sản phẩm
- **Quản lý danh mục** - Quản lý danh mục sản phẩm
- **Quản lý đơn hàng** - Xem và cập nhật đơn hàng
- **Quản lý tồn kho** - Quản lý số lượng sản phẩm
- **Quản lý người dùng** - Quản lý tài khoản
- **Báo cáo doanh thu** - Thống kê doanh thu
- **Cài đặt hệ thống** - Cấu hình hệ thống

### 4. Kiểm Tra Nếu Không Thấy Link

#### Truy cập file test:
```
URL: http://localhost/project/test_admin_link.php
```

File này sẽ hiển thị:
- Thông tin user hiện tại
- Quyền admin
- Danh sách admin trong database
- Các link admin trực tiếp

### 5. Nếu Vẫn Không Thấy Link

#### Kiểm tra 1: Đăng nhập đúng tài khoản admin
```sql
SELECT * FROM user WHERE position = 'admin';
```

#### Kiểm tra 2: Session và quyền
- Đảm bảo đã đăng nhập thành công
- Kiểm tra `position = 'admin'` trong database

#### Kiểm tra 3: Cache browser
- Xóa cache browser (Ctrl+F5)
- Hoặc mở tab ẩn danh

### 6. Các URL Admin Trực Tiếp

Nếu menu dropdown không hoạt động, có thể truy cập trực tiếp:

- **Admin Dashboard**: `http://localhost/project/index.php?act=admin&action=dashboard`
- **Quản lý Sản phẩm**: `http://localhost/project/index.php?act=admin&action=products`
- **Quản lý Danh mục**: `http://localhost/project/index.php?act=admin&action=categories`
- **Quản lý Đơn hàng**: `http://localhost/project/index.php?act=admin_orders`
- **Quản lý Tồn kho**: `http://localhost/project/index.php?act=admin_product_management`
- **Quản lý Người dùng**: `http://localhost/project/index.php?act=admin&action=users`
- **Báo cáo**: `http://localhost/project/index.php?act=admin&action=reports`
- **Cài đặt**: `http://localhost/project/index.php?act=admin&action=settings`

### 7. Lưu Ý Quan Trọng

- Link admin chỉ hiển thị cho user có `position = 'admin'`
- Nếu đăng nhập user thường, sẽ không thấy link admin
- Cần đăng xuất và đăng nhập lại tài khoản admin
- Đảm bảo database có user admin với `position = 'admin'`

## Troubleshooting

### Nếu không thấy menu dropdown:
1. Hover chuột vào tên user
2. Kiểm tra CSS có bị lỗi không
3. Mở Developer Tools (F12) kiểm tra console

### Nếu link admin không hoạt động:
1. Kiểm tra quyền admin trong database
2. Đăng xuất và đăng nhập lại
3. Xóa cache browser 