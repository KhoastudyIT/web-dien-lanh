<?php
// Sử dụng đường dẫn tuyệt đối từ root của project
$project_root = dirname(dirname(__DIR__));
include $project_root . "/view/layout/layout.php";

$content = '
<div class="space-y-8">
    <!-- Form thêm danh mục -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Thêm danh mục mới</h2>
        <form action="index.php?act=xl_themDM" method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên danh mục:</label>
                <input type="text" name="name" id="name" value="" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                       placeholder="Nhập tên danh mục...">
            </div>
            <button type="submit" class="bg-primary hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                Thêm danh mục
            </button>
        </form>
    </div>

    <!-- Danh sách danh mục -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Danh sách danh mục</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">';

if(isset($danhmuc) && count($danhmuc) > 0) {
    for($i=0; $i<count($danhmuc); $i++){
        $rc = $danhmuc[$i];
        $content .= '
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">'.$rc["id"].'</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">'.$rc["name"].'</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="index.php?act=xoadm&id_dm='.$rc["id"].'" 
                               class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded-md transition duration-200"
                               onclick="return confirm(\'Bạn có chắc chắn muốn xóa danh mục này?\')">
                                Xóa
                            </a>
                        </td>
                    </tr>';
    }
} else {
    $content .= '
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                            Chưa có danh mục nào được tạo
                        </td>
                    </tr>';
}

$content .= '
                </tbody>
            </table>
        </div>
    </div>
</div>';

renderPage("Quản lý danh mục", $content);
?>