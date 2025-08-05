# SỬA LỖI HIỂN THỊ ẢNH SẢN PHẨM VÀ LOGO HÃNG

## 🔧 Vấn đề đã được khắc phục

Sau khi import file `database_complete.sql`, các ảnh sản phẩm và logo hãng hiển thị sai (hiển thị logo website thay vì ảnh thực tế).

## 🎯 Nguyên nhân

Đường dẫn ảnh trong database không khớp với cách hiển thị trong code PHP:
- **Database**: Lưu đường dẫn `view/image/...`
- **Code PHP**: Hiển thị `/project/view/image/` + đường dẫn từ database
- **Kết quả**: Đường dẫn kép `/project/view/image/view/image/...` → Ảnh không tìm thấy

## ✅ Giải pháp đã áp dụng

1. **Sửa đường dẫn trong database**: Đảm bảo tất cả đường dẫn ảnh trong `database_complete.sql` có định dạng đúng
2. **Giữ nguyên code PHP**: Không cần thay đổi code hiển thị

## 📋 Hướng dẫn import lại database

### Bước 1: Xóa database cũ
```sql
DROP DATABASE IF EXISTS dienlanh_shop;
```

### Bước 2: Import database mới
Import file `model/database_complete.sql` vào MySQL/phpMyAdmin

### Bước 3: Kiểm tra kết quả
- Truy cập trang chủ: `http://localhost/project/`
- Kiểm tra ảnh sản phẩm hiển thị đúng
- Kiểm tra logo hãng hiển thị đúng

## 🔍 Các đường dẫn đã được sửa

### Logo hãng (chỉ tên file):
- `logoDaikin.jpg`
- `logoPanasonic.jpg`
- `logoLG.jpg`
- `logoToshiba.jpg`
- `logoMitsubishi.jpg`
- `logosamsung.jpg`

### Ảnh sản phẩm (đường dẫn tương đối):
- Tất cả 150 sản phẩm đã được cập nhật đường dẫn đúng
- Ví dụ: `sanPhamDaikin/MaylanhtreotuongDaikin/FTKA25UAVMV.png`

## 🎉 Kết quả mong đợi

Sau khi import lại database:
- ✅ Ảnh sản phẩm hiển thị đúng
- ✅ Logo hãng hiển thị đúng
- ✅ Không còn hiển thị logo website thay thế
- ✅ Tất cả chức năng hoạt động bình thường

## 📞 Hỗ trợ

Nếu vẫn gặp vấn đề sau khi import lại database, vui lòng:
1. Kiểm tra file ảnh có tồn tại trong thư mục `view/image/` không
2. Kiểm tra quyền truy cập file ảnh
3. Xóa cache trình duyệt và thử lại 