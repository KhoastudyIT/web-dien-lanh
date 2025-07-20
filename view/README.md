# Cấu trúc thư mục View

Thư mục `view` đã được tái cấu trúc để tách biệt layout và pages cho dễ quản lý hơn.

## 📁 Cấu trúc thư mục

```
view/
├── layout/           # Layout và components chung
│   ├── header.php    # Header chung cho tất cả trang
│   └── layout.php    # Layout template chính
├── pages/            # Các trang nội dung
│   ├── home.php      # Trang chủ
│   ├── login.php     # Trang đăng nhập
│   ├── register.php  # Trang đăng ký
│   ├── profile.php   # Trang thông tin cá nhân
│   ├── danhmuc.php   # Trang quản lý danh mục
│   ├── sanpham.php   # Trang danh sách sản phẩm
│   └── chitiet.php   # Trang chi tiết sản phẩm
├── css/              # File CSS
│   ├── style.css     # CSS chính với variables và utilities
│   └── custom.css    # CSS tùy chỉnh cho components
├── image/            # Thư mục chứa hình ảnh
└── upload/           # Thư mục upload file
```

## 🔧 Cách sử dụng

### 1. Import Layout trong Pages
Tất cả các file trong thư mục `pages/` phải import layout như sau:
```php
<?php
include "../layout/layout.php";
// Code trang ở đây
?>
```

### 2. Import Model
Khi cần sử dụng model, import như sau:
```php
include_once "../model/sanpham.php";
include_once "../model/danhmuc.php";
```

### 3. Controller Routing
Controller đã được cập nhật để trỏ đến đúng đường dẫn:
```php
// Ví dụ trong controller/index.php
case 'home':
    include "../view/pages/home.php";
    break;
case 'login':
    include "../view/pages/login.php";
    break;
```

## 🎨 CSS Structure

### style.css
- CSS variables (primary, secondary colors)
- Utility classes
- Responsive utilities
- Animation classes
- Custom scrollbar

### custom.css
- Component-specific styles
- Button styles
- Form styles
- Card styles
- Alert styles

## 📱 Responsive Design

Tất cả các trang đều hỗ trợ responsive với:
- Mobile-first approach
- Tailwind CSS utilities
- Custom breakpoints
- Touch-friendly interactions

## 🔒 Security

- Tất cả output đều được escape với `htmlspecialchars()`
- JWT authentication cho các trang cần bảo mật
- CSRF protection cho forms
- Input validation

## 🚀 Performance

- CSS và JS được minified
- Images được optimized
- Lazy loading cho images
- Caching headers

## 📝 Notes

- Tất cả đường dẫn đã được cập nhật để phù hợp với cấu trúc mới
- Layout và pages được tách biệt để dễ maintain
- CSS được tổ chức theo module
- Responsive design được ưu tiên 