# Hệ thống Quản lý Đơn hàng

## Tổng quan
Hệ thống quản lý đơn hàng cho website bán máy lạnh, bao gồm các chức năng:
- Đặt hàng và thanh toán
- Quản lý đơn hàng cho admin
- Theo dõi trạng thái đơn hàng
- Lịch sử thay đổi trạng thái
- Thống kê doanh thu

## Cấu trúc Database

### Bảng chính:

1. **donhang** - Bảng đơn hàng chính
   - `id_dh`: ID đơn hàng (Primary Key)
   - `id_user`: ID người dùng đặt hàng
   - `tongdh`: Tổng tiền đơn hàng
   - `ngaydat`: Ngày đặt hàng
   - `trangthai`: Trạng thái đơn hàng
   - `ten_nguoi_nhan`: Tên người nhận
   - `sdt_nguoi_nhan`: Số điện thoại người nhận
   - `dia_chi_giao`: Địa chỉ giao hàng
   - `ghi_chu`: Ghi chú đơn hàng
   - `phuong_thuc_thanh_toan`: Phương thức thanh toán
   - `ngay_cap_nhat`: Ngày cập nhật cuối

2. **dh_chitiet** - Chi tiết đơn hàng
   - `id_chitiet`: ID chi tiết (Primary Key)
   - `id_sp`: ID sản phẩm
   - `id_dh`: ID đơn hàng
   - `soluong`: Số lượng
   - `tong_dh`: Tổng tiền cho sản phẩm này
   - `gia_ban`: Giá bán tại thời điểm đặt hàng

3. **lich_su_trang_thai** - Lịch sử thay đổi trạng thái
   - `id`: ID lịch sử (Primary Key)
   - `id_dh`: ID đơn hàng
   - `trang_thai_cu`: Trạng thái cũ
   - `trang_thai_moi`: Trạng thái mới
   - `ghi_chu`: Ghi chú thay đổi
   - `ngay_cap_nhat`: Ngày cập nhật
   - `nguoi_cap_nhat`: ID người cập nhật

## Cài đặt

### 1. Import Database
```sql
-- Chạy file model/database.sql để tạo toàn bộ database
-- Hoặc chạy file model/import_orders.sql để chỉ tạo các bảng đơn hàng
```

### 2. Tài khoản mẫu
- **Admin**: username: `admin`, password: `admin123`
- **User**: username: `user`, password: `user123`

## Các trang chính

### Cho người dùng:
1. **Giỏ hàng** (`/project/index.php?act=cart`)
2. **Thanh toán** (`/project/index.php?act=checkout`)
3. **Đơn hàng của tôi** (`/project/index.php?act=my_orders`)
4. **Chi tiết đơn hàng** (`/project/index.php?act=order_detail&id=X`)

### Cho admin:
1. **Quản lý đơn hàng** (`/project/index.php?act=admin_orders`)
2. **Chi tiết đơn hàng** (`/project/index.php?act=admin_order_detail&id=X`)

## Trạng thái đơn hàng

1. **Chờ xác nhận** - Đơn hàng mới đặt
2. **Đã xác nhận** - Admin đã xác nhận đơn hàng
3. **Đang giao hàng** - Đơn hàng đang được giao
4. **Đã giao hàng** - Đơn hàng đã giao thành công
5. **Đã hủy** - Đơn hàng bị hủy

## API Methods (DonHang class)

### Cơ bản:
- `createOrder($userId, $total, $shippingInfo)` - Tạo đơn hàng mới
- `getUserOrders($userId)` - Lấy đơn hàng của user
- `getOrderDetails($orderId)` - Lấy chi tiết đơn hàng
- `getAllOrders()` - Lấy tất cả đơn hàng (admin)
- `updateOrderStatus($orderId, $status, $userId, $ghiChu)` - Cập nhật trạng thái

### Nâng cao:
- `getOrderStatusHistory($orderId)` - Lấy lịch sử trạng thái
- `searchOrders($keyword, $status, $dateFrom, $dateTo)` - Tìm kiếm đơn hàng
- `getMonthlyRevenue($year)` - Thống kê doanh thu theo tháng
- `getTopSellingProducts($limit)` - Top sản phẩm bán chạy
- `deleteOrder($orderId)` - Xóa đơn hàng (admin)

## Luồng hoạt động

### 1. Đặt hàng:
1. User thêm sản phẩm vào giỏ hàng
2. Vào trang giỏ hàng kiểm tra
3. Chuyển đến trang thanh toán
4. Điền thông tin giao hàng
5. Xác nhận đặt hàng
6. Hệ thống tạo đơn hàng và xóa giỏ hàng
7. Chuyển đến trang thành công

### 2. Quản lý đơn hàng (Admin):
1. Xem danh sách tất cả đơn hàng
2. Lọc theo trạng thái, ngày, tìm kiếm
3. Xem chi tiết đơn hàng
4. Cập nhật trạng thái
5. Theo dõi lịch sử thay đổi

## Bảo mật

- Tất cả các trang đều kiểm tra quyền truy cập
- Admin có quyền quản lý tất cả đơn hàng
- User chỉ xem được đơn hàng của mình
- Sử dụng JWT để xác thực
- Có lịch sử thay đổi trạng thái để audit

## Tối ưu hiệu suất

- Có các index trên các cột thường query
- Sử dụng transaction để đảm bảo tính toàn vẹn dữ liệu
- Có cascade delete để tự động xóa dữ liệu liên quan

## Troubleshooting

### Lỗi thường gặp:
1. **Class "DonHang" not found**: Kiểm tra include file model/donhang.php
2. **Cart không xóa sau checkout**: Kiểm tra session_start() và Cart::clear()
3. **Trang đơn hàng redirect login**: Kiểm tra JWT token và getCurrentUser()

### Debug:
- Kiểm tra error log của PHP
- Kiểm tra database connection
- Kiểm tra quyền truy cập file và thư mục 