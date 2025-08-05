<?php
include_once __DIR__ . '/../../helpers/jwt_helper.php';
$currentUser = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Điện Lạnh KV - Chuyên cung cấp thiết bị điện lạnh chất lượng cao</title>
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
    <link rel="stylesheet" href="/project/view/css/style.css">
    <link rel="stylesheet" href="/project/view/css/custom.css">
    <style>
      /* CSS tùy chỉnh bổ sung nếu cần */
    </style>
</head>
<body>

    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="/project/index.php" class="flex items-center">
                        <img src="/project/view/image/logodienlanh.png" alt="Điện Lạnh KV" style="width:40px;">
                    </a>
                </div>

                <div class="flex-1 max-w-lg mx-8">
                    <div class="relative">
                        <form method="GET" action="/project/index.php" class="relative">
                            <input type="hidden" name="act" value="sanpham">
                            <input
                                type="text"
                                id="searchInput"
                                name="search"
                                placeholder="Tìm kiếm sản phẩm, hãng..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                autocomplete="off"
                            />
                            <div
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 flex items-center justify-center"
                            >
                                <i class="ri-search-line text-gray-400 text-sm"></i>
                            </div>
                            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2">
                                <i class="ri-arrow-right-line text-gray-400 hover:text-primary transition-colors"></i>
                            </button>
                        </form>
                        
                        <!-- Search Suggestions Dropdown -->
                        <div id="searchSuggestions" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 z-50 hidden max-h-96 overflow-y-auto">
                            <!-- Suggestions will be populated here -->
                        </div>
                    </div>
                </div>

                <nav class="hidden md:flex items-center space-x-8">
                    <a href="/project/index.php" class="text-gray-700 hover:text-primary font-medium">Trang chủ</a>
                    <a href="/project/index.php?act=sanpham" class="text-gray-700 hover:text-primary font-medium">Sản phẩm</a>
                    <a href="/project/index.php?act=danhmuc" class="text-gray-700 hover:text-primary font-medium">Danh mục</a>
                    <a href="/project/index.php?act=lienhe" class="text-gray-700 hover:text-primary font-medium">Liên hệ</a>
                </nav>

                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <div class="text-xs text-gray-700">Hotline</div>
                        <div class="text-sm font-semibold text-primary">1900 6789</div>
                    </div>
                    <!-- Giỏ hàng -->
                    <div class="w-8 h-8 flex items-center justify-center relative">
                        <a href="/project/index.php?act=cart" class="hover:text-primary transition-colors">
                            <i class="ri-shopping-cart-line text-gray-600 text-lg"></i>
                        </a>
                    </div>
                    <?php if ($currentUser): ?>
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-primary">
                                <i class="ri-user-line text-gray-600 text-lg"></i>
                                <span class="text-sm font-medium"><?php echo htmlspecialchars($currentUser['fullname']); ?></span>
                                <i class="ri-arrow-down-s-line"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded shadow-lg py-2 hidden group-hover:block z-50">
                                <a href="/project/index.php?act=profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="ri-user-settings-line mr-2"></i>Thông tin tài khoản
                                </a>
                                <a href="/project/index.php?act=my_orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="ri-shopping-bag-3-line mr-2"></i>Đơn hàng của tôi
                                </a>
                                <a href="/project/index.php?act=logout" class="block px-4 py-2 text-red-600 hover:bg-gray-100" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">
                                    <i class="ri-logout-box-line mr-2"></i>Đăng xuất
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/project/index.php?act=login" class="flex items-center space-x-2 text-gray-700 hover:text-primary">
                            <i class="ri-user-line text-gray-600 text-lg"></i>
                            <span class="text-sm font-medium">Đăng nhập</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <script>
    // Search Autocomplete Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchSuggestions = document.getElementById('searchSuggestions');
        let searchTimeout;

        // Debounced search function
        function debounceSearch(query) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (query.length >= 2) {
                    fetchSearchSuggestions(query);
                } else {
                    hideSuggestions();
                }
            }, 300);
        }

        // Fetch search suggestions from API
        async function fetchSearchSuggestions(query) {
            try {
                const response = await fetch(`/project/api/search_suggestions.php?q=${encodeURIComponent(query)}&limit=5`);
                const suggestions = await response.json();
                displaySuggestions(suggestions);
            } catch (error) {
                console.error('Error fetching suggestions:', error);
                hideSuggestions();
            }
        }

        // Display suggestions in dropdown
        function displaySuggestions(suggestions) {
            if (suggestions.length === 0) {
                hideSuggestions();
                return;
            }

            const suggestionsHTML = suggestions.map(item => {
                const saleBadge = item.sale > 0 ? `<span class="bg-red-500 text-white text-xs px-2 py-1 rounded ml-2">-${item.sale}%</span>` : '';
                return `
                    <a href="${item.url}" class="flex items-center p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors">
                        <div class="flex-shrink-0 w-12 h-12 mr-3">
                            <img src="/project/view/image/${item.image}" alt="${item.name}" class="w-full h-full object-cover rounded" onerror="this.src='/project/view/image/logodienlanh.png'">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-900 truncate">${item.name}</h4>
                                ${saleBadge}
                            </div>
                            <div class="flex items-center mt-1">
                                <img src="/project/view/image/${item.logo}" alt="${item.brand}" class="w-4 h-4 mr-2" onerror="this.src='/project/view/image/logodienlanh.png'">
                                <span class="text-xs text-gray-500">${item.brand}</span>
                                <span class="text-xs text-gray-400 mx-2">•</span>
                                <span class="text-xs text-gray-500">${item.category}</span>
                            </div>
                            <div class="text-sm font-semibold text-primary mt-1">${item.price}</div>
                        </div>
                    </a>
                `;
            }).join('');

            searchSuggestions.innerHTML = suggestionsHTML;
            searchSuggestions.classList.remove('hidden');
        }

        // Hide suggestions dropdown
        function hideSuggestions() {
            searchSuggestions.classList.add('hidden');
        }

        // Event listeners
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            debounceSearch(query);
        });

        searchInput.addEventListener('focus', function() {
            const query = this.value.trim();
            if (query.length >= 2) {
                debounceSearch(query);
            }
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                hideSuggestions();
            }
        });

        // Handle keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            const suggestions = searchSuggestions.querySelectorAll('a');
            const currentIndex = Array.from(suggestions).findIndex(el => el.classList.contains('bg-gray-100'));

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const nextIndex = currentIndex < suggestions.length - 1 ? currentIndex + 1 : 0;
                suggestions.forEach(el => el.classList.remove('bg-gray-100'));
                if (suggestions[nextIndex]) {
                    suggestions[nextIndex].classList.add('bg-gray-100');
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prevIndex = currentIndex > 0 ? currentIndex - 1 : suggestions.length - 1;
                suggestions.forEach(el => el.classList.remove('bg-gray-100'));
                if (suggestions[prevIndex]) {
                    suggestions[prevIndex].classList.add('bg-gray-100');
                }
            } else if (e.key === 'Enter') {
                const activeSuggestion = searchSuggestions.querySelector('a.bg-gray-100');
                if (activeSuggestion) {
                    e.preventDefault();
                    window.location.href = activeSuggestion.href;
                }
            } else if (e.key === 'Escape') {
                hideSuggestions();
            }
        });
    });
    </script>