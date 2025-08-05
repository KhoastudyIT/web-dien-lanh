<?php
include "../model/danhmuc.php";
class controller{

    public function hienthidm(){
        $dm = new danhmuc();
        return $dm->getDS_Danhmuc();
    }
    public function themdm(danhmuc $dm){
        // Gọi thêm danh mục trong class danhmuc trong model
        $dm->themDM($dm);
    }
    public function xoadm(danhmuc $dm){
        $dm->xoadm($dm);
    }

    // Lấy tất cả hãng
    public function getAllBrands() {
        $xl = new xl_data();
        return $xl->getAllBrands();
    }

    // Lấy sản phẩm nổi bật theo danh mục
    public function getFeaturedProductsByCategory($categoryId, $limit = 4) {
        include_once dirname(__DIR__) . '/model/sanpham.php';
        $sp = new sanpham();
        $products = $sp->getDS_SanphamByDanhmuc($categoryId);
        // Lọc sản phẩm nổi bật (Sale > 0), lấy tối đa $limit sản phẩm
        $featured = array_filter($products, function($item) {
            return isset($item['Sale']) && $item['Sale'] > 0;
        });
        return array_slice($featured, 0, $limit);
    }

    // Lấy search suggestions
    public function getSearchSuggestions($query, $limit = 5) {
        include_once dirname(__DIR__) . '/model/sanpham.php';
        $sp = new sanpham();
        return $sp->getSearchSuggestions($query, $limit);
    }
}
