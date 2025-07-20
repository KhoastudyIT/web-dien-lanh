-- Dữ liệu mockup cho hệ thống điện lạnh
USE abc;

-- Thêm danh mục sản phẩm
INSERT INTO danhmuc (name) VALUES 
('Tủ lạnh'),
('Máy lạnh'),
('Máy giặt'),
('Tủ đông'),
('Bình nóng lạnh');

-- Thêm các hãng sản xuất
INSERT INTO hang (ten_hang, logo_hang, mo_ta, quoc_gia) VALUES
('Samsung', 'https://dienlanh.com/images/brands/samsung-logo.png', 'Hãng điện tử hàng đầu Hàn Quốc', 'Hàn Quốc'),
('LG', 'https://dienlanh.com/images/brands/lg-logo.png', 'Tập đoàn LG Electronics', 'Hàn Quốc'),
('Panasonic', 'https://dienlanh.com/images/brands/panasonic-logo.png', 'Công nghệ Nhật Bản', 'Nhật Bản'),
('Sharp', 'https://dienlanh.com/images/brands/sharp-logo.png', 'Chất lượng Nhật Bản', 'Nhật Bản'),
('Toshiba', 'https://dienlanh.com/images/brands/toshiba-logo.png', 'Công nghệ tiên tiến', 'Nhật Bản'),
('Electrolux', 'https://dienlanh.com/images/brands/electrolux-logo.png', 'Thiết kế Thụy Điển', 'Thụy Điển'),
('Beko', 'https://dienlanh.com/images/brands/beko-logo.png', 'Chất lượng châu Âu', 'Thổ Nhĩ Kỳ'),
('AQUA', 'https://dienlanh.com/images/brands/aqua-logo.png', 'Thương hiệu Việt Nam', 'Việt Nam'),
('Hitachi', 'https://dienlanh.com/images/brands/hitachi-logo.png', 'Công nghệ Nhật Bản', 'Nhật Bản'),
('Daikin', 'https://dienlanh.com/images/brands/daikin-logo.png', 'Chuyên gia điều hòa', 'Nhật Bản'),
('Ariston', 'https://dienlanh.com/images/brands/ariston-logo.png', 'Thiết bị gia dụng Ý', 'Ý'),
('Ferroli', 'https://dienlanh.com/images/brands/ferroli-logo.png', 'Công nghệ châu Âu', 'Ý'),
('Rossi', 'https://dienlanh.com/images/brands/rossi-logo.png', 'Thương hiệu Việt Nam', 'Việt Nam');

-- Thêm tài khoản admin
INSERT INTO taikhoan (username, password, fullname, email, phone, address, position) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@dienlanhkv.vn', '084346868288', '60/15/3, P. Thủ Đức, TP.HCM', 'admin');


-- Thêm sản phẩm

-- Dữ liệu sản phẩm Máy lạnh
-- Daikin Máy lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Daikin FTKC35UAVMV', 8990000, NOW(), 200, 'Máy lạnh 1 chiều 12000BTU, công nghệ Inverter', 60, 10, 'https://dienlanh.com/images/products/daikin-ftkc35uavmv.jpg', 2, 10),
('Máy lạnh Daikin FTKC50UAVMV', 12990000, NOW(), 180, 'Máy lạnh 1 chiều 18000BTU, công nghệ Inverter', 50, 12, 'https://dienlanh.com/images/products/daikin-ftkc50uavmv.jpg', 2, 10),
('Máy lạnh Daikin FTKC60UAVMV', 15990000, NOW(), 160, 'Máy lạnh 1 chiều 22000BTU, công nghệ Inverter', 45, 15, 'https://dienlanh.com/images/products/daikin-ftkc60uavmv.jpg', 2, 10),
('Máy lạnh Daikin FTKC25UAVMV', 6990000, NOW(), 220, 'Máy lạnh 1 chiều 9000BTU, công nghệ Inverter', 65, 8, 'https://dienlanh.com/images/products/daikin-ftkc25uavmv.jpg', 2, 10),
('Máy lạnh Daikin FTKC70UAVMV', 18990000, NOW(), 140, 'Máy lạnh 1 chiều 24000BTU, công nghệ Inverter', 40, 18, 'https://dienlanh.com/images/products/daikin-ftkc70uavmv.jpg', 2, 10);

-- Panasonic Máy lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Panasonic CU/CS-XU9XKH-8', 7990000, NOW(), 190, 'Máy lạnh 1 chiều 9000BTU, công nghệ Inverter', 55, 12, 'https://dienlanh.com/images/products/panasonic-cu-cs-xu9xkh-8.jpg', 2, 3),
('Máy lạnh Panasonic CU/CS-XU12XKH-8', 10990000, NOW(), 170, 'Máy lạnh 1 chiều 12000BTU, công nghệ Inverter', 50, 15, 'https://dienlanh.com/images/products/panasonic-cu-cs-xu12xkh-8.jpg', 2, 3),
('Máy lạnh Panasonic CU/CS-XU18XKH-8', 14990000, NOW(), 150, 'Máy lạnh 1 chiều 18000BTU, công nghệ Inverter', 45, 18, 'https://dienlanh.com/images/products/panasonic-cu-cs-xu18xkh-8.jpg', 2, 3),
('Máy lạnh Panasonic CU/CS-XU24XKH-8', 18990000, NOW(), 130, 'Máy lạnh 1 chiều 24000BTU, công nghệ Inverter', 40, 20, 'https://dienlanh.com/images/products/panasonic-cu-cs-xu24xkh-8.jpg', 2, 3),
('Máy lạnh Panasonic CU/CS-XU36XKH-8', 25990000, NOW(), 110, 'Máy lạnh 1 chiều 36000BTU, công nghệ Inverter', 35, 25, 'https://dienlanh.com/images/products/panasonic-cu-cs-xu36xkh-8.jpg', 2, 3);

-- Samsung Máy lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Samsung AR09TGHQSBUENSLV', 6990000, NOW(), 180, 'Máy lạnh 1 chiều 9000BTU, công nghệ Digital Inverter', 60, 10, 'https://dienlanh.com/images/products/samsung-ar09tghqsbue.jpg', 2, 1),
('Máy lạnh Samsung AR12TGHQSBUENSLV', 9990000, NOW(), 160, 'Máy lạnh 1 chiều 12000BTU, công nghệ Digital Inverter', 55, 12, 'https://dienlanh.com/images/products/samsung-ar12tghqsbue.jpg', 2, 1),
('Máy lạnh Samsung AR18TGHQSBUENSLV', 13990000, NOW(), 140, 'Máy lạnh 1 chiều 18000BTU, công nghệ Digital Inverter', 50, 15, 'https://dienlanh.com/images/products/samsung-ar18tghqsbue.jpg', 2, 1),
('Máy lạnh Samsung AR24TGHQSBUENSLV', 17990000, NOW(), 120, 'Máy lạnh 1 chiều 24000BTU, công nghệ Digital Inverter', 45, 18, 'https://dienlanh.com/images/products/samsung-ar24tghqsbue.jpg', 2, 1),
('Máy lạnh Samsung AR36TGHQSBUENSLV', 24990000, NOW(), 100, 'Máy lạnh 1 chiều 36000BTU, công nghệ Digital Inverter', 40, 20, 'https://dienlanh.com/images/products/samsung-ar36tghqsbue.jpg', 2, 1);

-- LG Máy lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh LG V10ENW', 7990000, NOW(), 170, 'Máy lạnh 1 chiều 9000BTU, công nghệ Inverter', 58, 12, 'https://dienlanh.com/images/products/lg-v10enw.jpg', 2, 2),
('Máy lạnh LG V13ENW', 10990000, NOW(), 150, 'Máy lạnh 1 chiều 12000BTU, công nghệ Inverter', 52, 15, 'https://dienlanh.com/images/products/lg-v13enw.jpg', 2, 2),
('Máy lạnh LG V18ENW', 14990000, NOW(), 130, 'Máy lạnh 1 chiều 18000BTU, công nghệ Inverter', 48, 18, 'https://dienlanh.com/images/products/lg-v18enw.jpg', 2, 2),
('Máy lạnh LG V24ENW', 18990000, NOW(), 110, 'Máy lạnh 1 chiều 24000BTU, công nghệ Inverter', 42, 20, 'https://dienlanh.com/images/products/lg-v24enw.jpg', 2, 2),
('Máy lạnh LG V36ENW', 25990000, NOW(), 90, 'Máy lạnh 1 chiều 36000BTU, công nghệ Inverter', 38, 25, 'https://dienlanh.com/images/products/lg-v36enw.jpg', 2, 2);

-- Hitachi Máy lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy lạnh Hitachi RAS-09UKZ', 8990000, NOW(), 160, 'Máy lạnh 1 chiều 9000BTU, công nghệ Inverter', 55, 10, 'https://dienlanh.com/images/products/hitachi-ras-09ukz.jpg', 2, 9),
('Máy lạnh Hitachi RAS-12UKZ', 11990000, NOW(), 140, 'Máy lạnh 1 chiều 12000BTU, công nghệ Inverter', 50, 12, 'https://dienlanh.com/images/products/hitachi-ras-12ukz.jpg', 2, 9),
('Máy lạnh Hitachi RAS-18UKZ', 15990000, NOW(), 120, 'Máy lạnh 1 chiều 18000BTU, công nghệ Inverter', 45, 15, 'https://dienlanh.com/images/products/hitachi-ras-18ukz.jpg', 2, 9),
('Máy lạnh Hitachi RAS-24UKZ', 19990000, NOW(), 100, 'Máy lạnh 1 chiều 24000BTU, công nghệ Inverter', 40, 18, 'https://dienlanh.com/images/products/hitachi-ras-24ukz.jpg', 2, 9),
('Máy lạnh Hitachi RAS-36UKZ', 26990000, NOW(), 80, 'Máy lạnh 1 chiều 36000BTU, công nghệ Inverter', 35, 20, 'https://dienlanh.com/images/products/hitachi-ras-36ukz.jpg', 2, 9); 

-- Dữ liệu sản phẩm Tủ lạnh
-- Samsung Tủ lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Samsung RT38K501J8E/XV', 15990000, NOW(), 150, 'Tủ lạnh Side by Side 608L, công nghệ Twin Cooling Plus', 50, 10, 'https://dienlanh.com/images/products/samsung-rt38k501j8e.jpg', 1, 1),
('Tủ lạnh Samsung RB37J5000SA/SV', 8990000, NOW(), 200, 'Tủ lạnh Multi Door 367L, thiết kế hiện đại', 45, 15, 'https://dienlanh.com/images/products/samsung-rb37j5000sa.jpg', 1, 1),
('Tủ lạnh Samsung RT25K501J8E/XV', 12990000, NOW(), 180, 'Tủ lạnh Side by Side 501L, tiết kiệm điện', 40, 12, 'https://dienlanh.com/images/products/samsung-rt25k501j8e.jpg', 1, 1),
('Tủ lạnh Samsung RB30J3000SA/SV', 6990000, NOW(), 220, 'Tủ lạnh Multi Door 301L, giá tốt', 60, 8, 'https://dienlanh.com/images/products/samsung-rb30j3000sa.jpg', 1, 1),
('Tủ lạnh Samsung RT22K501J8E/XV', 10990000, NOW(), 160, 'Tủ lạnh Side by Side 401L, công nghệ mới', 35, 18, 'https://dienlanh.com/images/products/samsung-rt22k501j8e.jpg', 1, 1);

-- LG Tủ lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh LG GR-B247JDS', 8990000, NOW(), 170, 'Tủ lạnh Multi Door 668L, công nghệ Inverter', 55, 12, 'https://dienlanh.com/images/products/lg-gr-b247jds.jpg', 1, 2),
('Tủ lạnh LG GR-S24FS', 6990000, NOW(), 190, 'Tủ lạnh Side by Side 601L, thiết kế đẹp', 48, 15, 'https://dienlanh.com/images/products/lg-gr-s24fs.jpg', 1, 2),
('Tủ lạnh LG GR-B247JS', 7990000, NOW(), 140, 'Tủ lạnh Multi Door 668L, tiết kiệm điện', 42, 10, 'https://dienlanh.com/images/products/lg-gr-b247js.jpg', 1, 2),
('Tủ lạnh LG GR-S24DS', 5990000, NOW(), 210, 'Tủ lạnh Side by Side 601L, giá rẻ', 65, 20, 'https://dienlanh.com/images/products/lg-gr-s24ds.jpg', 1, 2),
('Tủ lạnh LG GR-B247JD', 8490000, NOW(), 130, 'Tủ lạnh Multi Door 668L, công nghệ mới', 38, 14, 'https://dienlanh.com/images/products/lg-gr-b247jd.jpg', 1, 2);

-- Panasonic Tủ lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Panasonic NR-BL267PSVN', 7990000, NOW(), 160, 'Tủ lạnh Multi Door 605L, công nghệ Inverter', 50, 12, 'https://dienlanh.com/images/products/panasonic-nr-bl267psvn.jpg', 1, 3),
('Tủ lạnh Panasonic NR-BL267PKVN', 7490000, NOW(), 145, 'Tủ lạnh Multi Door 605L, màu đen', 45, 15, 'https://dienlanh.com/images/products/panasonic-nr-bl267pkvn.jpg', 1, 3),
('Tủ lạnh Panasonic NR-BL267PSVN', 6990000, NOW(), 175, 'Tủ lạnh Multi Door 605L, tiết kiệm điện', 52, 10, 'https://dienlanh.com/images/products/panasonic-nr-bl267psvn-2.jpg', 1, 3),
('Tủ lạnh Panasonic NR-BL267PKVN', 6490000, NOW(), 155, 'Tủ lạnh Multi Door 605L, giá tốt', 48, 18, 'https://dienlanh.com/images/products/panasonic-nr-bl267pkvn-2.jpg', 1, 3),
('Tủ lạnh Panasonic NR-BL267PSVN', 5990000, NOW(), 165, 'Tủ lạnh Multi Door 605L, thiết kế đẹp', 40, 22, 'https://dienlanh.com/images/products/panasonic-nr-bl267psvn-3.jpg', 1, 3);

-- Sharp Tủ lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Sharp SJ-X257E-SL', 8990000, NOW(), 140, 'Tủ lạnh Side by Side 601L, công nghệ J-Tech Inverter', 55, 12, 'https://dienlanh.com/images/products/sharp-sj-x257e-sl.jpg', 1, 4),
('Tủ lạnh Sharp SJ-X257E-SV', 8490000, NOW(), 150, 'Tủ lạnh Side by Side 601L, màu bạc', 50, 15, 'https://dienlanh.com/images/products/sharp-sj-x257e-sv.jpg', 1, 4),
('Tủ lạnh Sharp SJ-X257E-SB', 7990000, NOW(), 135, 'Tủ lạnh Side by Side 601L, màu đen', 45, 18, 'https://dienlanh.com/images/products/sharp-sj-x257e-sb.jpg', 1, 4),
('Tủ lạnh Sharp SJ-X257E-SW', 7490000, NOW(), 145, 'Tủ lạnh Side by Side 601L, màu trắng', 48, 20, 'https://dienlanh.com/images/products/sharp-sj-x257e-sw.jpg', 1, 4),
('Tủ lạnh Sharp SJ-X257E-SG', 6990000, NOW(), 155, 'Tủ lạnh Side by Side 601L, màu xám', 42, 25, 'https://dienlanh.com/images/products/sharp-sj-x257e-sg.jpg', 1, 4);

-- Toshiba Tủ lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ lạnh Toshiba GR-B247JDS', 7990000, NOW(), 130, 'Tủ lạnh Multi Door 668L, công nghệ Inverter', 50, 12, 'https://dienlanh.com/images/products/toshiba-gr-b247jds.jpg', 1, 5),
('Tủ lạnh Toshiba GR-S24FS', 6990000, NOW(), 140, 'Tủ lạnh Side by Side 601L, thiết kế đẹp', 45, 15, 'https://dienlanh.com/images/products/toshiba-gr-s24fs.jpg', 1, 5),
('Tủ lạnh Toshiba GR-B247JS', 7490000, NOW(), 125, 'Tủ lạnh Multi Door 668L, tiết kiệm điện', 48, 18, 'https://dienlanh.com/images/products/toshiba-gr-b247js.jpg', 1, 5),
('Tủ lạnh Toshiba GR-S24DS', 5990000, NOW(), 150, 'Tủ lạnh Side by Side 601L, giá rẻ', 55, 22, 'https://dienlanh.com/images/products/toshiba-gr-s24ds.jpg', 1, 5),
('Tủ lạnh Toshiba GR-B247JD', 6990000, NOW(), 135, 'Tủ lạnh Multi Door 668L, công nghệ mới', 42, 20, 'https://dienlanh.com/images/products/toshiba-gr-b247jd.jpg', 1, 5); 

-- Dữ liệu sản phẩm Máy giặt
-- Samsung Máy giặt
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy giặt Samsung WW90T554DAW/SV', 8990000, NOW(), 180, 'Máy giặt lồng đôi 9kg, công nghệ EcoBubble', 50, 12, 'https://dienlanh.com/images/products/samsung-ww90t554daw.jpg', 3, 1),
('Máy giặt Samsung WW80T554DAW/SV', 7990000, NOW(), 200, 'Máy giặt lồng đôi 8kg, công nghệ EcoBubble', 55, 15, 'https://dienlanh.com/images/products/samsung-ww80t554daw.jpg', 3, 1),
('Máy giặt Samsung WW70T554DAW/SV', 6990000, NOW(), 190, 'Máy giặt lồng đôi 7kg, công nghệ EcoBubble', 60, 18, 'https://dienlanh.com/images/products/samsung-ww70t554daw.jpg', 3, 1),
('Máy giặt Samsung WW65T554DAW/SV', 5990000, NOW(), 210, 'Máy giặt lồng đôi 6.5kg, công nghệ EcoBubble', 65, 20, 'https://dienlanh.com/images/products/samsung-ww65t554daw.jpg', 3, 1),
('Máy giặt Samsung WW60T554DAW/SV', 4990000, NOW(), 220, 'Máy giặt lồng đôi 6kg, công nghệ EcoBubble', 70, 25, 'https://dienlanh.com/images/products/samsung-ww60t554daw.jpg', 3, 1);

-- LG Máy giặt
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy giặt LG FHT1408ZWL', 8990000, NOW(), 170, 'Máy giặt lồng đôi 10.5kg, công nghệ TurboWash', 52, 12, 'https://dienlanh.com/images/products/lg-fht1408zwl.jpg', 3, 2),
('Máy giặt LG FHT1208ZWL', 7990000, NOW(), 180, 'Máy giặt lồng đôi 9kg, công nghệ TurboWash', 55, 15, 'https://dienlanh.com/images/products/lg-fht1208zwl.jpg', 3, 2),
('Máy giặt LG FHT1008ZWL', 6990000, NOW(), 190, 'Máy giặt lồng đôi 8kg, công nghệ TurboWash', 58, 18, 'https://dienlanh.com/images/products/lg-fht1008zwl.jpg', 3, 2),
('Máy giặt LG FHT808ZWL', 5990000, NOW(), 200, 'Máy giặt lồng đôi 7kg, công nghệ TurboWash', 62, 20, 'https://dienlanh.com/images/products/lg-fht808zwl.jpg', 3, 2),
('Máy giặt LG FHT608ZWL', 4990000, NOW(), 210, 'Máy giặt lồng đôi 6kg, công nghệ TurboWash', 65, 25, 'https://dienlanh.com/images/products/lg-fht608zwl.jpg', 3, 2);

-- Panasonic Máy giặt
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy giặt Panasonic NA-FS12X7LRV', 8990000, NOW(), 160, 'Máy giặt lồng đôi 12kg, công nghệ ActiveFoam', 50, 12, 'https://dienlanh.com/images/products/panasonic-na-fs12x7lrv.jpg', 3, 3),
('Máy giặt Panasonic NA-FS10X7LRV', 7990000, NOW(), 170, 'Máy giặt lồng đôi 10kg, công nghệ ActiveFoam', 55, 15, 'https://dienlanh.com/images/products/panasonic-na-fs10x7lrv.jpg', 3, 3),
('Máy giặt Panasonic NA-FS8X7LRV', 6990000, NOW(), 180, 'Máy giặt lồng đôi 8kg, công nghệ ActiveFoam', 60, 18, 'https://dienlanh.com/images/products/panasonic-na-fs8x7lrv.jpg', 3, 3),
('Máy giặt Panasonic NA-FS6X7LRV', 5990000, NOW(), 190, 'Máy giặt lồng đôi 6kg, công nghệ ActiveFoam', 65, 20, 'https://dienlanh.com/images/products/panasonic-na-fs6x7lrv.jpg', 3, 3),
('Máy giặt Panasonic NA-FS5X7LRV', 4990000, NOW(), 200, 'Máy giặt lồng đôi 5kg, công nghệ ActiveFoam', 70, 25, 'https://dienlanh.com/images/products/panasonic-na-fs5x7lrv.jpg', 3, 3);

-- Electrolux Máy giặt
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy giặt Electrolux EWF1408S3WB', 8990000, NOW(), 150, 'Máy giặt lồng đôi 10kg, công nghệ Vapour Refresh', 52, 12, 'https://dienlanh.com/images/products/electrolux-ewf1408s3wb.jpg', 3, 6),
('Máy giặt Electrolux EWF1208S3WB', 7990000, NOW(), 160, 'Máy giặt lồng đôi 8kg, công nghệ Vapour Refresh', 55, 15, 'https://dienlanh.com/images/products/electrolux-ewf1208s3wb.jpg', 3, 6),
('Máy giặt Electrolux EWF1008S3WB', 6990000, NOW(), 170, 'Máy giặt lồng đôi 7kg, công nghệ Vapour Refresh', 58, 18, 'https://dienlanh.com/images/products/electrolux-ewf1008s3wb.jpg', 3, 6),
('Máy giặt Electrolux EWF808S3WB', 5990000, NOW(), 180, 'Máy giặt lồng đôi 6kg, công nghệ Vapour Refresh', 62, 20, 'https://dienlanh.com/images/products/electrolux-ewf808s3wb.jpg', 3, 6),
('Máy giặt Electrolux EWF608S3WB', 4990000, NOW(), 190, 'Máy giặt lồng đôi 5kg, công nghệ Vapour Refresh', 65, 25, 'https://dienlanh.com/images/products/electrolux-ewf608s3wb.jpg', 3, 6);

-- Beko Máy giặt
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Máy giặt Beko WTV8733XW', 7990000, NOW(), 140, 'Máy giặt lồng đôi 10kg, công nghệ SteamCure', 50, 12, 'https://dienlanh.com/images/products/beko-wtv8733xw.jpg', 3, 7),
('Máy giặt Beko WTV8533XW', 6990000, NOW(), 150, 'Máy giặt lồng đôi 8kg, công nghệ SteamCure', 55, 15, 'https://dienlanh.com/images/products/beko-wtv8533xw.jpg', 3, 7),
('Máy giặt Beko WTV8333XW', 5990000, NOW(), 160, 'Máy giặt lồng đôi 7kg, công nghệ SteamCure', 60, 18, 'https://dienlanh.com/images/products/beko-wtv8333xw.jpg', 3, 7),
('Máy giặt Beko WTV8133XW', 4990000, NOW(), 170, 'Máy giặt lồng đôi 6kg, công nghệ SteamCure', 65, 20, 'https://dienlanh.com/images/products/beko-wtv8133xw.jpg', 3, 7),
('Máy giặt Beko WTV7933XW', 3990000, NOW(), 180, 'Máy giặt lồng đôi 5kg, công nghệ SteamCure', 70, 25, 'https://dienlanh.com/images/products/beko-wtv7933xw.jpg', 3, 7); 

-- Dữ liệu sản phẩm Tủ đông
-- Samsung Tủ đông
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ đông Samsung RT38K501J8E/XV', 8990000, NOW(), 120, 'Tủ đông Side by Side 608L, công nghệ Twin Cooling Plus', 40, 15, 'https://dienlanh.com/images/products/samsung-freezer-rt38k501j8e.jpg', 4, 1),
('Tủ đông Samsung RB37J5000SA/SV', 6990000, NOW(), 140, 'Tủ đông Multi Door 367L, thiết kế hiện đại', 45, 18, 'https://dienlanh.com/images/products/samsung-freezer-rb37j5000sa.jpg', 4, 1),
('Tủ đông Samsung RT25K501J8E/XV', 7990000, NOW(), 130, 'Tủ đông Side by Side 501L, tiết kiệm điện', 50, 12, 'https://dienlanh.com/images/products/samsung-freezer-rt25k501j8e.jpg', 4, 1),
('Tủ đông Samsung RB30J3000SA/SV', 5990000, NOW(), 150, 'Tủ đông Multi Door 301L, giá tốt', 55, 20, 'https://dienlanh.com/images/products/samsung-freezer-rb30j3000sa.jpg', 4, 1),
('Tủ đông Samsung RT22K501J8E/XV', 6990000, NOW(), 125, 'Tủ đông Side by Side 401L, công nghệ mới', 48, 16, 'https://dienlanh.com/images/products/samsung-freezer-rt22k501j8e.jpg', 4, 1);

-- LG Tủ đông
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ đông LG GR-B247JDS', 7990000, NOW(), 135, 'Tủ đông Multi Door 668L, công nghệ Inverter', 42, 15, 'https://dienlanh.com/images/products/lg-freezer-gr-b247jds.jpg', 4, 2),
('Tủ đông LG GR-S24FS', 6990000, NOW(), 145, 'Tủ đông Side by Side 601L, thiết kế đẹp', 45, 18, 'https://dienlanh.com/images/products/lg-freezer-gr-s24fs.jpg', 4, 2),
('Tủ đông LG GR-B247JS', 7490000, NOW(), 130, 'Tủ đông Multi Door 668L, tiết kiệm điện', 48, 12, 'https://dienlanh.com/images/products/lg-freezer-gr-b247js.jpg', 4, 2),
('Tủ đông LG GR-S24DS', 5990000, NOW(), 155, 'Tủ đông Side by Side 601L, giá rẻ', 52, 22, 'https://dienlanh.com/images/products/lg-freezer-gr-s24ds.jpg', 4, 2),
('Tủ đông LG GR-B247JD', 6990000, NOW(), 140, 'Tủ đông Multi Door 668L, công nghệ mới', 46, 16, 'https://dienlanh.com/images/products/lg-freezer-gr-b247jd.jpg', 4, 2);

-- Panasonic Tủ đông
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ đông Panasonic NR-BL267PSVN', 7990000, NOW(), 125, 'Tủ đông Multi Door 605L, công nghệ Inverter', 44, 15, 'https://dienlanh.com/images/products/panasonic-freezer-nr-bl267psvn.jpg', 4, 3),
('Tủ đông Panasonic NR-BL267PKVN', 7490000, NOW(), 135, 'Tủ đông Multi Door 605L, màu đen', 46, 18, 'https://dienlanh.com/images/products/panasonic-freezer-nr-bl267pkvn.jpg', 4, 3),
('Tủ đông Panasonic NR-BL267PSVN', 6990000, NOW(), 140, 'Tủ đông Multi Door 605L, tiết kiệm điện', 48, 12, 'https://dienlanh.com/images/products/panasonic-freezer-nr-bl267psvn-2.jpg', 4, 3),
('Tủ đông Panasonic NR-BL267PKVN', 6490000, NOW(), 130, 'Tủ đông Multi Door 605L, giá tốt', 50, 20, 'https://dienlanh.com/images/products/panasonic-freezer-nr-bl267pkvn-2.jpg', 4, 3),
('Tủ đông Panasonic NR-BL267PSVN', 5990000, NOW(), 145, 'Tủ đông Multi Door 605L, thiết kế đẹp', 52, 16, 'https://dienlanh.com/images/products/panasonic-freezer-nr-bl267psvn-3.jpg', 4, 3);

-- Sharp Tủ đông
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ đông Sharp SJ-X257E-SL', 7990000, NOW(), 120, 'Tủ đông Side by Side 601L, công nghệ J-Tech Inverter', 45, 15, 'https://dienlanh.com/images/products/sharp-freezer-sj-x257e-sl.jpg', 4, 4),
('Tủ đông Sharp SJ-X257E-SV', 7490000, NOW(), 130, 'Tủ đông Side by Side 601L, màu bạc', 47, 18, 'https://dienlanh.com/images/products/sharp-freezer-sj-x257e-sv.jpg', 4, 4),
('Tủ đông Sharp SJ-X257E-SB', 6990000, NOW(), 125, 'Tủ đông Side by Side 601L, màu đen', 49, 12, 'https://dienlanh.com/images/products/sharp-freezer-sj-x257e-sb.jpg', 4, 4),
('Tủ đông Sharp SJ-X257E-SW', 6490000, NOW(), 135, 'Tủ đông Side by Side 601L, màu trắng', 51, 20, 'https://dienlanh.com/images/products/sharp-freezer-sj-x257e-sw.jpg', 4, 4),
('Tủ đông Sharp SJ-X257E-SG', 5990000, NOW(), 140, 'Tủ đông Side by Side 601L, màu xám', 53, 16, 'https://dienlanh.com/images/products/sharp-freezer-sj-x257e-sg.jpg', 4, 4);

-- Toshiba Tủ đông
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Tủ đông Toshiba GR-B247JDS', 7990000, NOW(), 115, 'Tủ đông Multi Door 668L, công nghệ Inverter', 43, 15, 'https://dienlanh.com/images/products/toshiba-freezer-gr-b247jds.jpg', 4, 5),
('Tủ đông Toshiba GR-S24FS', 6990000, NOW(), 125, 'Tủ đông Side by Side 601L, thiết kế đẹp', 45, 18, 'https://dienlanh.com/images/products/toshiba-freezer-gr-s24fs.jpg', 4, 5),
('Tủ đông Toshiba GR-B247JS', 7490000, NOW(), 120, 'Tủ đông Multi Door 668L, tiết kiệm điện', 47, 12, 'https://dienlanh.com/images/products/toshiba-freezer-gr-b247js.jpg', 4, 5),
('Tủ đông Toshiba GR-S24DS', 5990000, NOW(), 135, 'Tủ đông Side by Side 601L, giá rẻ', 49, 22, 'https://dienlanh.com/images/products/toshiba-freezer-gr-s24ds.jpg', 4, 5),
('Tủ đông Toshiba GR-B247JD', 6990000, NOW(), 130, 'Tủ đông Multi Door 668L, công nghệ mới', 51, 16, 'https://dienlanh.com/images/products/toshiba-freezer-gr-b247jd.jpg', 4, 5); 

-- Dữ liệu sản phẩm Bình nóng lạnh
-- Ariston Bình nóng lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Bình nóng lạnh Ariston Velis Evo 30L', 2990000, NOW(), 180, 'Bình nóng lạnh 30L, công nghệ ABS, chống giật', 60, 15, 'https://dienlanh.com/images/products/ariston-velis-evo-30l.jpg', 5, 11),
('Bình nóng lạnh Ariston Velis Evo 50L', 3990000, NOW(), 160, 'Bình nóng lạnh 50L, công nghệ ABS, chống giật', 55, 12, 'https://dienlanh.com/images/products/ariston-velis-evo-50l.jpg', 5, 11),
('Bình nóng lạnh Ariston Velis Evo 80L', 5990000, NOW(), 140, 'Bình nóng lạnh 80L, công nghệ ABS, chống giật', 50, 18, 'https://dienlanh.com/images/products/ariston-velis-evo-80l.jpg', 5, 11),
('Bình nóng lạnh Ariston Velis Evo 100L', 6990000, NOW(), 120, 'Bình nóng lạnh 100L, công nghệ ABS, chống giật', 45, 20, 'https://dienlanh.com/images/products/ariston-velis-evo-100l.jpg', 5, 11),
('Bình nóng lạnh Ariston Velis Evo 150L', 8990000, NOW(), 100, 'Bình nóng lạnh 150L, công nghệ ABS, chống giật', 40, 25, 'https://dienlanh.com/images/products/ariston-velis-evo-150l.jpg', 5, 11);

-- Ferroli Bình nóng lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Bình nóng lạnh Ferroli Optimax 30L', 2790000, NOW(), 170, 'Bình nóng lạnh 30L, thiết kế hiện đại, tiết kiệm điện', 58, 15, 'https://dienlanh.com/images/products/ferroli-optimax-30l.jpg', 5, 12),
('Bình nóng lạnh Ferroli Optimax 50L', 3790000, NOW(), 150, 'Bình nóng lạnh 50L, thiết kế hiện đại, tiết kiệm điện', 53, 12, 'https://dienlanh.com/images/products/ferroli-optimax-50l.jpg', 5, 12),
('Bình nóng lạnh Ferroli Optimax 80L', 5790000, NOW(), 130, 'Bình nóng lạnh 80L, thiết kế hiện đại, tiết kiệm điện', 48, 18, 'https://dienlanh.com/images/products/ferroli-optimax-80l.jpg', 5, 12),
('Bình nóng lạnh Ferroli Optimax 100L', 6790000, NOW(), 110, 'Bình nóng lạnh 100L, thiết kế hiện đại, tiết kiệm điện', 43, 20, 'https://dienlanh.com/images/products/ferroli-optimax-100l.jpg', 5, 12),
('Bình nóng lạnh Ferroli Optimax 150L', 8790000, NOW(), 90, 'Bình nóng lạnh 150L, thiết kế hiện đại, tiết kiệm điện', 38, 25, 'https://dienlanh.com/images/products/ferroli-optimax-150l.jpg', 5, 12);

-- Rossi Bình nóng lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Bình nóng lạnh Rossi 30L', 2590000, NOW(), 165, 'Bình nóng lạnh 30L, giá rẻ, chất lượng tốt', 62, 18, 'https://dienlanh.com/images/products/rossi-30l.jpg', 5, 13),
('Bình nóng lạnh Rossi 50L', 3590000, NOW(), 145, 'Bình nóng lạnh 50L, giá rẻ, chất lượng tốt', 57, 15, 'https://dienlanh.com/images/products/rossi-50l.jpg', 5, 13),
('Bình nóng lạnh Rossi 80L', 5590000, NOW(), 125, 'Bình nóng lạnh 80L, giá rẻ, chất lượng tốt', 52, 20, 'https://dienlanh.com/images/products/rossi-80l.jpg', 5, 13),
('Bình nóng lạnh Rossi 100L', 6590000, NOW(), 105, 'Bình nóng lạnh 100L, giá rẻ, chất lượng tốt', 47, 22, 'https://dienlanh.com/images/products/rossi-100l.jpg', 5, 13),
('Bình nóng lạnh Rossi 150L', 8590000, NOW(), 85, 'Bình nóng lạnh 150L, giá rẻ, chất lượng tốt', 42, 25, 'https://dienlanh.com/images/products/rossi-150l.jpg', 5, 13);

-- Panasonic Bình nóng lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Bình nóng lạnh Panasonic DH-3HE1', 3290000, NOW(), 175, 'Bình nóng lạnh 30L, công nghệ Nhật Bản', 56, 12, 'https://dienlanh.com/images/products/panasonic-dh-3he1.jpg', 5, 3),
('Bình nóng lạnh Panasonic DH-5HE1', 4290000, NOW(), 155, 'Bình nóng lạnh 50L, công nghệ Nhật Bản', 51, 15, 'https://dienlanh.com/images/products/panasonic-dh-5he1.jpg', 5, 3),
('Bình nóng lạnh Panasonic DH-8HE1', 6290000, NOW(), 135, 'Bình nóng lạnh 80L, công nghệ Nhật Bản', 46, 18, 'https://dienlanh.com/images/products/panasonic-dh-8he1.jpg', 5, 3),
('Bình nóng lạnh Panasonic DH-10HE1', 7290000, NOW(), 115, 'Bình nóng lạnh 100L, công nghệ Nhật Bản', 41, 20, 'https://dienlanh.com/images/products/panasonic-dh-10he1.jpg', 5, 3),
('Bình nóng lạnh Panasonic DH-15HE1', 9290000, NOW(), 95, 'Bình nóng lạnh 150L, công nghệ Nhật Bản', 36, 25, 'https://dienlanh.com/images/products/panasonic-dh-15he1.jpg', 5, 3);

-- AQUA Bình nóng lạnh
INSERT INTO sanpham (Name, Price, Date_import, Viewsp, Decribe, Mount, Sale, image, id_danhmuc, id_hang) VALUES
('Bình nóng lạnh AQUA AHE-30L', 2890000, NOW(), 160, 'Bình nóng lạnh 30L, thương hiệu Việt Nam', 59, 15, 'https://dienlanh.com/images/products/aqua-ahe-30l.jpg', 5, 8),
('Bình nóng lạnh AQUA AHE-50L', 3890000, NOW(), 140, 'Bình nóng lạnh 50L, thương hiệu Việt Nam', 54, 12, 'https://dienlanh.com/images/products/aqua-ahe-50l.jpg', 5, 8),
('Bình nóng lạnh AQUA AHE-80L', 5890000, NOW(), 120, 'Bình nóng lạnh 80L, thương hiệu Việt Nam', 49, 18, 'https://dienlanh.com/images/products/aqua-ahe-80l.jpg', 5, 8),
('Bình nóng lạnh AQUA AHE-100L', 6890000, NOW(), 100, 'Bình nóng lạnh 100L, thương hiệu Việt Nam', 44, 20, 'https://dienlanh.com/images/products/aqua-ahe-100l.jpg', 5, 8),
('Bình nóng lạnh AQUA AHE-150L', 8890000, NOW(), 80, 'Bình nóng lạnh 150L, thương hiệu Việt Nam', 39, 25, 'https://dienlanh.com/images/products/aqua-ahe-150l.jpg', 5, 8); 