# Thay Đổi Logic Tính Doanh Thu

## Tổng Quan
Đã cập nhật logic tính doanh thu để chỉ tính khi đơn hàng chuyển sang trạng thái "Đã giao".

## Các Thay Đổi Chính

### 1. Model/DonHang.php
- **getOrderStats()**: Chỉ tính doanh thu cho đơn hàng "Đã giao"
- **getMonthlyRevenue()**: Chỉ tính doanh thu cho đơn hàng "Đã giao"  
- **getTopSellingProducts()**: Chỉ tính doanh thu cho đơn hàng "Đã giao"
- **Hàm mới**: getActualRevenue() và getTotalRevenue()

### 2. View Files
- **admin_orders.php**: Hiển thị cả doanh thu thực tế và tổng
- **admin_reports.php**: Cập nhật tiêu đề và thêm thẻ thống kê mới

## Lý Do Thay Đổi
- Doanh thu chỉ nên tính khi đơn hàng hoàn thành
- Giúp admin có cái nhìn chính xác về doanh thu thực tế
- Phân biệt giữa doanh thu tiềm năng và thực tế

## Kiểm Tra
Sử dụng file `test_revenue.php` để test các thay đổi.
