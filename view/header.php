<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Myweb</title>
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: { primary: "#2563eb", secondary: "#3b82f6" },
            borderRadius: {
              none: "0px",
              sm: "4px",
              DEFAULT: "8px",
              md: "12px",
              lg: "16px",
              xl: "20px",
              "2xl": "24px",
              "3xl": "32px",
              full: "9999px",
              button: "8px",
            },
          },
        },
      };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="view/css/custom.css">
    <style>
      /* Additional custom CSS goes here if needed */
    </style>
</head>
<body>

    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <img src="images/Neel.png" alt="logo" style="width:40px;">
                </div>

                <div class="flex-1 max-w-lg mx-8">
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Tìm kiếm sản phẩm..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                        />
                        <div
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 flex items-center justify-center"
                        >
                            <i class="ri-search-line text-gray-400 text-sm"></i>
                        </div>
                    </div>
                </div>

                <nav class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-primary font-medium">Trang chủ</a>
                    <a href="index.php?act=danhmuc" class="text-gray-700 hover:text-primary font-medium">Danh mục</a>
                    <a href="#" class="text-gray-700 hover:text-primary font-medium">Sản phẩm</a>
                    <a href="#" class="text-gray-700 hover:text-primary font-medium">Liên hệ</a>
                </nav>

                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <div class="text-xs text-gray-700">Hotline</div>
                        <div class="text-sm font-semibold text-primary">1900 6789</div>
                    </div>
                    <div class="w-8 h-8 flex items-center justify-center">
                        <i class="ri-shopping-cart-line text-gray-600 text-lg"></i>
                    </div>
                    <?php
                    // Include JWT helper để kiểm tra user
                    if (file_exists("../helpers/jwt_helper.php")) {
                        include_once "../helpers/jwt_helper.php";
                        include_once "../model/user.php";
                        $currentUser = getCurrentUser();
                    } else {
                        $currentUser = null;
                    }
                    
                    if ($currentUser): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-primary">
                                <i class="ri-user-line text-gray-600 text-lg"></i>
                                <span class="text-sm font-medium"><?= htmlspecialchars($currentUser['username']) ?></span>
                                <i class="ri-arrow-down-s-line"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                                <a href="index.php?act=profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="ri-user-line mr-2"></i>Thông tin tài khoản
                                </a>
                                <a href="index.php?act=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100" 
                                   onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">
                                    <i class="ri-logout-box-line mr-2"></i>Đăng xuất
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="index.php?act=login" class="flex items-center space-x-2 text-gray-700 hover:text-primary">
                            <i class="ri-user-line text-gray-600 text-lg"></i>
                            <span class="text-sm font-medium">Đăng nhập</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>