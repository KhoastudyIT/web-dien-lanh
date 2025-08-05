# Hệ thống Quản lý Đơn hàng - Điện Lạnh KV

## Tổng quan
Hệ thống quản lý đơn hàng hoàn chỉnh cho website bán thiết bị điện lạnh, bao gồm chức năng thanh toán giỏ hàng và quản lý đơn hàng cho admin.

## Tính năng đã phát triển

### 1. Cho người dùng (Customer)

#### 1.1. Thanh toán giỏ hàng
- **Trang checkout** (`/project/index.php?act=checkout`)
  - Form nhập thông tin giao hàng
  - Chọn phương thức thanh toán (Tiền mặt, Chuyển khoản, Ví điện tử)
  - Hiển thị tổng quan đơn hàng
  - Validation dữ liệu đầu vào

#### 1.2. Theo dõi đơn hàng
- **Trang danh sách đơn hàng** (`/project/index.php?act=my_orders`)
  - Hiển thị tất cả đơn hàng của user
  - Thông tin: Mã đơn, ngày đặt, tổng tiền, trạng thái
  - Link đến chi tiết từng đơn hàng
  - Trạng thái trống khi chưa có đơn hàng

- **Trang chi tiết đơn hàng** (`/project/index.php?act=order_detail&id=X`)
  - Thông tin đầy đủ về đơn hàng
  - Chi tiết sản phẩm đã đặt
  - Thông tin giao hàng và thanh toán
  - Chức năng in đơn hàng
  - Breadcrumb navigation

#### 1.3. Navigation và UX
- **Header menu**: Thêm "Đơn hàng của tôi" vào dropdown menu user
- **Giỏ hàng trống**: Thêm link đến trang theo dõi đơn hàng
- **Trang thành công**: Link trực tiếp đến chi tiết đơn hàng vừa đặt

### 2. Cho Admin

#### 2.1. Quản lý đơn hàng
- **Trang quản lý đơn hàng** (`/project/index.php?act=admin_orders`)
  - Dashboard thống kê: Tổng đơn hàng, đơn chờ xác nhận, đang giao, tổng doanh thu
  - Bảng danh sách đơn hàng với thông tin chi tiết
  - Filter theo trạng thái
  - Cập nhật trạng thái qua modal popup
  - Link đến chi tiết từng đơn hàng

- **Trang chi tiết đơn hàng admin** (`/project/index.php?act=admin_order_detail&id=X`)
  - Thông tin đầy đủ về đơn hàng và khách hàng
  - Danh sách sản phẩm đã đặt
  - Form cập nhật trạng thái trực tiếp
  - Chức năng in đơn hàng
  - Tóm tắt đơn hàng

#### 2.2. Cập nhật trạng thái
- **Trạng thái đơn hàng**:
  - Chờ xác nhận (mặc định)
  - Đã xác nhận
  - Đang giao hàng
  - Đã giao hàng
  - Đã hủy

- **Cách cập nhật**:
  - Từ bảng danh sách: Modal popup
  - Từ trang chi tiết: Form inline
  - Validation và thông báo kết quả

### 3. Database Schema

#### 3.1. Bảng `donhang` (cập nhật)
```sql
ALTER TABLE donhang ADD COLUMN ten_nguoi_nhan VARCHAR(250) NOT NULL DEFAULT '';
ALTER TABLE donhang ADD COLUMN sdt_nguoi_nhan VARCHAR(20) NOT NULL DEFAULT '';
ALTER TABLE donhang ADD COLUMN dia_chi_giao TEXT NOT NULL DEFAULT '';
ALTER TABLE donhang ADD COLUMN ghi_chu TEXT;
ALTER TABLE donhang ADD COLUMN phuong_thuc_thanh_toan VARCHAR(50) DEFAULT 'Tiền mặt';
ALTER TABLE donhang ADD COLUMN ngay_cap_nhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
```

#### 3.2. Bảng `dh_chitiet` (cập nhật)
```sql
ALTER TABLE dh_chitiet ADD COLUMN gia_ban INT NOT NULL DEFAULT 0;
```

### 4. Model Classes

#### 4.1. Class `DonHang`
- `createOrder($userId, $total, $shippingInfo)`: Tạo đơn hàng mới
- `getUserOrders($userId)`: Lấy đơn hàng của user
- `getOrderDetails($orderId)`: Lấy chi tiết đơn hàng
- `getAllOrders()`: Lấy tất cả đơn hàng (admin)
- `updateOrderStatus($orderId, $status)`: Cập nhật trạng thái
- `getOrderStats()`: Thống kê đơn hàng
- `getOrdersByStatus($status)`: Lọc đơn hàng theo trạng thái

### 5. Workflow

#### 5.1. Quy trình đặt hàng
1. User thêm sản phẩm vào giỏ hàng
2. Chuyển đến trang checkout
3. Nhập thông tin giao hàng và chọn phương thức thanh toán
4. Xác nhận đặt hàng
5. Hệ thống tạo đơn hàng, cập nhật tồn kho, xóa giỏ hàng
6. Chuyển đến trang thành công
7. User có thể xem chi tiết đơn hàng

#### 5.2. Quy trình quản lý (Admin)
1. Admin xem danh sách đơn hàng
2. Xem thống kê tổng quan
3. Cập nhật trạng thái đơn hàng
4. Theo dõi tiến trình giao hàng
5. Xem chi tiết từng đơn hàng

### 6. Security & Validation

#### 6.1. Authentication
- Kiểm tra đăng nhập cho tất cả trang đơn hàng
- Kiểm tra quyền admin cho trang quản lý
- JWT token validation

#### 6.2. Authorization
- User chỉ xem được đơn hàng của mình
- Admin xem được tất cả đơn hàng
- Validation dữ liệu đầu vào

#### 6.3. Data Protection
- SQL injection prevention với prepared statements
- XSS prevention với htmlspecialchars
- CSRF protection với form validation

### 7. UI/UX Features

#### 7.1. Responsive Design
- Mobile-first approach
- Tailwind CSS styling
- Consistent design language

#### 7.2. User Experience
- Breadcrumb navigation
- Loading states
- Success/error messages
- Modal dialogs
- Print functionality

#### 7.3. Visual Indicators
- Status badges với màu sắc
- Icons cho các action
- Hover effects
- Transition animations

## Files đã tạo/cập nhật

### Files mới
- `view/pages/my_orders.php` - Trang theo dõi đơn hàng user
- `view/pages/order_detail.php` - Trang chi tiết đơn hàng user
- `view/pages/checkout.php` - Trang thanh toán
- `view/pages/order_success.php` - Trang thành công
- `view/pages/admin_orders.php` - Trang quản lý đơn hàng admin
- `view/pages/admin_order_detail.php` - Trang chi tiết đơn hàng admin
- `model/donhang.php` - Model xử lý đơn hàng

### Files cập nhật
- `controller/index.php` - Thêm routes và xử lý logic
- `view/layout/header.php` - Thêm menu "Đơn hàng của tôi"
- `view/pages/cart.php` - Thêm link đến trang đơn hàng
- `view/pages/admin.php` - Cập nhật link quản lý đơn hàng
- `model/database.sql` - Cập nhật schema database

## Hướng dẫn sử dụng

### Cho User
1. Đăng nhập vào hệ thống
2. Thêm sản phẩm vào giỏ hàng
3. Vào giỏ hàng và chọn "Tiến hành thanh toán"
4. Điền thông tin giao hàng và chọn phương thức thanh toán
5. Xác nhận đặt hàng
6. Theo dõi đơn hàng qua menu "Đơn hàng của tôi"

### Cho Admin
1. Đăng nhập với tài khoản admin
2. Vào "Quản lý đơn hàng" từ menu admin
3. Xem thống kê và danh sách đơn hàng
4. Cập nhật trạng thái đơn hàng
5. Xem chi tiết từng đơn hàng

## Tính năng mở rộng có thể thêm

1. **Email notifications** - Gửi email thông báo trạng thái
2. **SMS notifications** - Gửi SMS thông báo
3. **Order tracking** - Mã vận đơn và tracking
4. **Order history** - Lịch sử đơn hàng chi tiết
5. **Order cancellation** - Hủy đơn hàng
6. **Order returns** - Đổi trả sản phẩm
7. **Order reviews** - Đánh giá sản phẩm sau mua
8. **Order analytics** - Phân tích dữ liệu đơn hàng
9. **Bulk operations** - Thao tác hàng loạt
10. **Order templates** - Mẫu đơn hàng 