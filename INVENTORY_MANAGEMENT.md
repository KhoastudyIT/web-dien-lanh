# QUẢN LÝ TỒN KHO - SHOP ĐIỆN LẠNH

## 📋 Tổng quan

Hệ thống quản lý tồn kho đã được phát triển để tự động cập nhật và quản lý số lượng sản phẩm sau khi thanh toán. Chức năng này đảm bảo tính chính xác của tồn kho và ngăn chặn việc bán sản phẩm khi hết hàng.

## 🎯 Các tính năng chính

### 1. **Tự động cập nhật tồn kho khi thanh toán**
- Kiểm tra tồn kho trước khi đặt hàng
- Tự động trừ số lượng sản phẩm sau khi thanh toán thành công
- Xử lý trường hợp hết hàng (Mount = 0)

### 2. **Khôi phục tồn kho khi hủy đơn hàng**
- Tự động cộng lại số lượng sản phẩm khi hủy đơn hàng
- Đảm bảo tính nhất quán của dữ liệu

### 3. **Quản lý tồn kho cho Admin**
- Xem danh sách sản phẩm hết hàng
- Xem danh sách sản phẩm sắp hết hàng (dưới 5 sản phẩm)
- Cập nhật số lượng tồn kho thủ công
- Thống kê tồn kho

## 🔧 Các file đã được cập nhật

### 1. **model/donhang.php**
- **Method `createOrder()`**: Cải thiện logic cập nhật tồn kho
- **Method `updateOrderStatus()`**: Thêm xử lý khôi phục tồn kho khi hủy đơn hàng
- **Method `checkInventory()`**: Kiểm tra tồn kho trước khi đặt hàng
- **Method `restoreInventory()`**: Khôi phục tồn kho khi hủy đơn hàng
- **Method `getOutOfStockProducts()`**: Lấy danh sách sản phẩm hết hàng
- **Method `getLowStockProducts()`**: Lấy danh sách sản phẩm sắp hết hàng
- **Method `updateProductStock()`**: Cập nhật số lượng tồn kho

### 2. **controller/index.php**
- **Case `process_checkout`**: Thêm kiểm tra tồn kho trước khi đặt hàng
- **Case `admin_inventory`**: Thêm routing cho trang quản lý tồn kho

### 3. **view/pages/admin_inventory.php** (Mới)
- Trang quản lý tồn kho cho admin
- Hiển thị sản phẩm hết hàng và sắp hết hàng
- Form cập nhật tồn kho
- Thống kê tồn kho

### 4. **view/pages/admin.php**
- Thêm link "Quản lý tồn kho" trong menu admin

## 📊 Luồng hoạt động

### Khi khách hàng thanh toán:
1. **Kiểm tra tồn kho**: Hệ thống kiểm tra số lượng tồn kho của từng sản phẩm
2. **Báo lỗi nếu hết hàng**: Hiển thị thông báo nếu sản phẩm không đủ số lượng
3. **Tạo đơn hàng**: Nếu đủ hàng, tạo đơn hàng và chi tiết đơn hàng
4. **Cập nhật tồn kho**: Trừ số lượng sản phẩm đã bán
5. **Xóa giỏ hàng**: Xóa sản phẩm khỏi giỏ hàng

### Khi admin hủy đơn hàng:
1. **Cập nhật trạng thái**: Đổi trạng thái đơn hàng thành "Đã hủy"
2. **Khôi phục tồn kho**: Cộng lại số lượng sản phẩm vào tồn kho
3. **Lưu lịch sử**: Ghi lại lịch sử thay đổi trạng thái

## 🎛️ Hướng dẫn sử dụng

### Cho Admin:

#### 1. Truy cập trang quản lý tồn kho:
```
http://localhost/project/index.php?act=admin_inventory
```

#### 2. Xem thống kê tồn kho:
- **Hết hàng**: Số sản phẩm có Mount = 0
- **Sắp hết hàng**: Số sản phẩm có Mount ≤ 5
- **Tổng sản phẩm**: Tổng số sản phẩm trong hệ thống

#### 3. Cập nhật tồn kho:
1. Click "Cập nhật tồn kho" bên cạnh sản phẩm
2. Nhập số lượng mới trong modal
3. Click "Cập nhật" để lưu thay đổi

### Cho Khách hàng:

#### 1. Kiểm tra tồn kho:
- Hệ thống tự động kiểm tra khi thêm vào giỏ hàng
- Hiển thị thông báo nếu sản phẩm hết hàng

#### 2. Thanh toán:
- Hệ thống kiểm tra lại tồn kho trước khi đặt hàng
- Báo lỗi nếu không đủ số lượng

## ⚠️ Lưu ý quan trọng

### 1. **Kiểm tra tồn kho kép**:
- Kiểm tra khi thêm vào giỏ hàng
- Kiểm tra lại khi thanh toán

### 2. **Xử lý đồng thời**:
- Sử dụng transaction để đảm bảo tính nhất quán
- Rollback nếu có lỗi xảy ra

### 3. **Khôi phục tồn kho**:
- Chỉ khôi phục khi hủy đơn hàng
- Không khôi phục khi đơn hàng đã giao

### 4. **Cập nhật thủ công**:
- Admin có thể cập nhật tồn kho thủ công
- Hệ thống không tự động cập nhật khi nhập hàng

## 🔍 Kiểm tra và test

### 1. **Test thanh toán với sản phẩm hết hàng**:
1. Thêm sản phẩm hết hàng vào giỏ hàng
2. Thử thanh toán
3. Kiểm tra thông báo lỗi

### 2. **Test cập nhật tồn kho**:
1. Đăng nhập admin
2. Truy cập trang quản lý tồn kho
3. Cập nhật số lượng sản phẩm
4. Kiểm tra thay đổi

### 3. **Test hủy đơn hàng**:
1. Tạo đơn hàng
2. Hủy đơn hàng
3. Kiểm tra tồn kho được khôi phục

## 🎉 Kết quả mong đợi

Sau khi triển khai chức năng quản lý tồn kho:

- ✅ **Tự động cập nhật tồn kho** khi thanh toán
- ✅ **Ngăn chặn bán hàng** khi hết hàng
- ✅ **Khôi phục tồn kho** khi hủy đơn hàng
- ✅ **Quản lý tồn kho** cho admin
- ✅ **Thống kê tồn kho** real-time
- ✅ **Giao diện thân thiện** và dễ sử dụng

## 📞 Hỗ trợ

Nếu gặp vấn đề:
1. Kiểm tra database connection
2. Kiểm tra quyền admin
3. Kiểm tra log lỗi PHP
4. Đảm bảo đã import database mới nhất 