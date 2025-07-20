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

}
