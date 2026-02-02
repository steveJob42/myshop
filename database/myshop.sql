-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2026 at 05:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(30) NOT NULL DEFAULT 'paid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `full_name`, `email`, `phone`, `address`, `total_amount`, `status`, `created_at`) VALUES
(1, 9, 'pongthon watthungyai', 'pongthon@hotmail.com', '0948332556', 'ทดสอบกรอกข้อมูล', 3579.00, 'paid', '2026-02-02 16:08:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `line_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `qty`, `line_total`, `created_at`) VALUES
(1, 1, 92, 'ทดสอบเพิ่มรายการ', 1800.00, 1, 1800.00, '2026-02-02 16:08:27'),
(2, 1, 91, 'Blemish Control Cleanser', 290.00, 1, 290.00, '2026-02-02 16:08:27'),
(3, 1, 88, 'Vitamin C Serum 30g', 999.00, 1, 999.00, '2026-02-02 16:08:27'),
(4, 1, 86, 'CeraVe Creme Reparatrice', 490.00, 1, 490.00, '2026-02-02 16:08:27');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `component` longtext DEFAULT NULL,
  `rating` decimal(2,1) NOT NULL DEFAULT 0.0,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `discount_percent` int(11) DEFAULT NULL,
  `flashsale` tinyint(1) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `discountlogo` varchar(1000) NOT NULL,
  `star` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image_url`, `description`, `component`, `rating`, `discount_price`, `discount_percent`, `flashsale`, `stock`, `created_at`, `discountlogo`, `star`) VALUES
(79, 'CeraVe Hydrating Cleanser', 390.00, './assets/img/products/p_20260202_082710_5077a7dd.jpeg', 'เซราวี ไฮเดรติ้ง ครีม-ทู-โฟม คลีนเซอร์ ผลิตภัณฑ์ทำความสะอาดและล้างเครื่องสำอางบนผิวหน้าในขั้นตอนเดียว จากเซราวี ช่วยให้ผิวไม่รู้สึกแห้งตึง พร้อมฟื้นบำรุงปราการผิวให้ดูสุขภาพดี ด้วยส่วนผสมสำคัญอย่าง เซราไมด์จำเป็นต่อผิว 3 ชนิด ไฮยาลูรอนิค แอซิด และอะมิโน แอซิด ผสานกับเทคโนโลยี MVE ลิขสิทธ์เฉพาะเซราวี เพื่อผิวสะอาด ชุ่มชื้น ไม่แห้งตึง - ทำความสะอาดสิ่งสกปรกและเครื่องสำอางได้อย่างหมดจด ไม่แห้งตึง - ผ่านการทดสอบภายใต้การดูแลของผู้เชี่ยวชาญทางผิวหนัง - ปราศจากสบู่ และน้ำหอม - ไม่ก่อให้เกิดการอุดตัน และค่า pH ใกล้เคียงกับผิว อ่อนโยนต่อผิว', 'AQUA/WATER, GLYCERIN, SODIUM METHYL COCOYL TAURATE, COCO-BETAINE, SODIUM COCOYL ISETHIONATE, SODIUM CHLORIDE, PCA, PPG-5-CETETH-20, PEG-100 STEARATE, PEG-150 PENTAERYTHRITYL TETRASTEARATE, PEG-6 CAPRYLIC/CAPRIC GLYCERIDES, PEG-30 DIPOLYHYDROXYSTEARATE, CI 77891/TITANIUM DIOXIDE, ASPARTIC ACID, CERAMIDE NP, CERAMIDE AP, CERAMIDE EOP, SORBITAN ISOSTEARATE, CARBOMER, GLYCOL DISTEARATE, GLYCERYL STEARATE, GLYCERYL OLEATE, GLYCINE, TRIDECETH-6, CETEARYL ALCOHOL, BEHENTRIMONIUM METHOSULFATE, THREONINE, SODIUM HYDROXIDE, SALICYLIC ACID, SODIUM PCA, SODIUM LACTATE, ARGININE, SODIUM LAUROYL LACTYLATE, SERINE, SODIUM BENZOATE, VALINE, SODIUM HYALURONATE, PROLINE, ISOLEUCINE, CHOLESTEROL, PHENOXYETHANOL, ALANINE, PHENYLALANINE, COCONUT ACID, COCO-GLUCOSIDE, CHLORPHENESIN, DISODIUM EDTA, HYDROXYETHYL UREA, CITRIC ACID, HYDROXYETHYL ACRYLATE/SODIUM ACRYLOYLDIMETHYL TAURATE COPOLYMER, CAPRYLYL GLYCOL, PHYTOSPHINGOSINE, XANTHAN GUM, HISTIDINE, ACRYLATES/C10-30 ALKYL ACRYLATE CROSSPOLYMER, POLYQUATERNIUM-53, POLYQUATERNIUM-39, POLYSORBATE 60, ETHYLHEXYLGLYCERIN, BENZOIC ACID', 4.0, NULL, NULL, 0, 50, '2025-11-25 16:10:31', '', '<div class=\"p-0\"><span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span><span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span><span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span><span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span><span class=\"text-muted\"><i class=\"bi bi-star\"></i></span><span class=\"ms-2 text-dark\">4.0</span></div>'),
(80, 'CeraVe Moisturising Lotion', 654.00, './assets/img/2.jpg', 'มลภาวะที่ต้องเผชิญในแต่ละวัน รวมทั้งรังสียูวี ล้วนเป็นตัวการทำร้ายผิว เซราวี เฟเชียล มอยซ์เจอไรซิ่ง โลชั่น เอสพีเอฟ 50 ผลิตภัณฑ์บำรุงผิวหน้าผสมสารป้องกันแสงแดด สูตรสำหรับผิวธรรมดาถึงผิวแห้ง มาพร้อมค่าปกป้องแสงแดด SPF 50 สูตรปราศจากน้ำหอม สูตรไม่ก่อให้เกิดการอุดตัน ไม่เหนียวเหนอะหนะ และผลิตภัณฑ์ได้รับการทดสอบบนผิวที่บอบบางระคายเคืองง่าย', 'AQUA/WATER, GLYCERIN, ISOPROPYL PALMITATE, BIS-ETHYLHEXYLOXYPHENOL METHOXYPHENYL TRIAZINE, ETHYLHEXYL SALICYLATE, NIACINAMIDE, PENTYLENE GLYCOL, BUTYL METHOXYDIBENZOYLMETHANE, ETHYLHEXYL TRIAZONE, PROPANEDIOL, ZEA MAYS STARCH/CORN STARCH, POTASSIUM CETYL PHOSPHATE, DIISOPROPYL SEBACATE, ORYZA SATIVA CERA/RICE BRAN WAX, STEARIC ACID, CERAMIDE NP, CERAMIDE AP, CERAMIDE EOP, CARBOMER, GLYCERYL STEARATE, CETEARYL ALCOHOL, TRIETHANOLAMINE, BEHENTRIMONIUM METHOSULFATE, TRIETHYL CITRATE, SODIUM HYALURONATE, SODIUM POLYACRYLATE, SODIUM LAUROYL LACTYLATE, MYRISTIC ACID, CHOLESTEROL, PALMITIC ACID, TOCOPHEROL, CAPRYLYL GLYCOL, TRISODIUM ETHYLENEDIAMINE DISUCCINATE, XANTHAN GUM, PHYTOSPHINGOSINE, ACRYLATES/C10-30 ALKYL ACRYLATE CROSSPOLYMER, BUTYROSPERMUM PARKII BUTTER/SHEA BUTTER, BENZOIC ACID, PEG-100 STEARATE.', 4.0, NULL, NULL, 0, 3, '2025-11-25 16:10:31', '', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-muted\"><i class=\"bi bi-star\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">4.0</span>\r\n                </div>'),
(81, 'CeraVe Hydrating Face Wash', 778.00, './assets/img/4.webp', 'ผลิตภัณฑ์ทำความสะอาดผิวหน้าและผิวกาย สูตรสำหรับผิวธรรมดาถึงผิวแห้ง ช่วยทำความสะอาดผิวได้อย่างหมดจด อ่อนโยน ยังช่วยกักเก็บความชุ่มชื้นให้ผิวได้อย่างยาวนาน พร้อมฟื้นปราการผิวให้แข็งแรงด้วยคุณค่าจากเซราไมด์ อุดมด้วยเซราไมด์ที่จำเป็นต่อผิว 3 ชนิดผสานไฮยาลูรอนิกแอซิด ทำความสะอาดหมดจดอย่างอ่อนโยน ไม่ทำให้ผิวแห้งตึงสามารถใช้ทำความสะอาดผิวหน้าและผิวกาย ...', 'Ceramide 1,3,6-II, Hyaluronic Acid, Glycerin', 5.0, NULL, NULL, 0, 42, '2025-11-25 16:10:31', '', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">5.0</span>\r\n                </div>'),
(82, 'CeraVe Oil Control', 590.00, './assets/img/5.avif', 'เซราวี ออยล์ คอนโทรล มอยซ์เจอไรซิ่ง เจล ครีม ผลิตภัณฑ์บำรุงผิวหน้า สูตรสำหรับผิวผสมถึงผิวมัน\r\n\r\nบางครั้งความชุ่มชื้นและความมันบนผิวที่ไม่สมดุล ทำให้ผิวแห้งหรือ มันเยิ้มระหว่างวัน ซึ่งเป็นสาเหตุหนึ่งของการเกิดสิว คงจะดีถ้ามีผลิตภัณฑ์ที่ช่วยฟื้นบำรุงสมดุลความชุ่มชื้น ความมันของผิว ที่ออกแบบมา เพื่อผิวผสม-ผิวมัน พร้อมเนื้อสัมผัสบางเบา ซึมไว รู้สึกสดชื่นสบายผิว ไม่เหนียวเหนอะหนะ\r\n\r\nเซราวีผสานเซราไมด์ที่จำเป็นต่อผิว 3 ชนิด ** พร้อมอนุพันธ์ไฮยาลูรอนิก แอซิด เพื่อช่วยเติมความชุ่มชื้น และเซราไมด์ให้ผิวรวมทั้งเสริมปราการปกป้องผิว\r\nผลลัพธ์ที่ได้คือผิวชุ่มชื้น ดูเนียนนุ่ม คุมมัน’ กันสิว++ ปราการผิวแข็งแรง', 'AQUA/WATER, NIACINAMIDE, GLYCERIN, CETEARYL ISONONANOATE, C14-22 ALCOHOLS, ISOPROPYL MYRISTATE, ZEA MAYS STARCH/CORN STARCH, CERAMIDE NP, CERAMIDE AP, CERAMIDE EOP, CARBOMER, CETEARYL ALCOHOL, BEHENTRIMONIUM METHOSULFATE, TRIETHYL CITRATE, SILICA, SODIUM HYDROXIDE, SODIUM HYALURONATE, SODIUM LAUROYL LACTYLATE, CHOLESTEROL, PHENOXYETHANOL, CITRIC ACID, CAPRYLYL GLYCOL, TRISODIUM ETHYLENEDIAMINE DISUCCINATE, XANTHAN GUM, PHYTOSPHINGOSINE, POLYACRYLATE CROSSPOLYMER-6, BENZOIC ACID, C12-20 ALKYL GLUCOSIDE.', 4.0, NULL, NULL, 0, 50, '2025-11-25 16:10:31', '', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-muted\"><i class=\"bi bi-star\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">4.0</span>\r\n                </div>'),
(83, 'CeraVe Moisturising Cream', 390.00, './assets/img/6.avif', 'เซราวี มอยส์เจอร์ไรซิ่ง ครีม 50 มล. ครีมบำรุงผิวให้ความชุ่มชื้นตลอดวันและช่วยฟื้นฟูปราการปกป้องผิวหน้าและกาย สำหรับผิวแห้ง แห้งมาก เติมความชุ่มชื้นและช่วยฟื้นฟูปราการปกป้องผิวด้วยเซราไมด์ที่จำเป็นต่อผิว 3 ชนิด และ MVE Technology ผิวชุ่มชื้นตลอดวัน เนื้อสัมผัสบางเบา ปราศจากน้ำมัน คิดค้นขึ้นโดยความร่วมมือของแพทย์ผู้เชี่ยวชาญด้านผิวหนัง ผ่านการรับรองโดย National Eczema Association และ CeraVe Hydrating Facial Cleanser ผลิตภัณฑ์ทำความสะอาด ล้างหน้าอย่างอ่อนโยนด้วยส่วนผสม เช่น เซราไมด์ และกรดไฮยาลูโรนิก ขจัดสิ่งสกปรก ความมัน และเครื่องสำอางอย่างอ่อนโยน โดยไม่ทำให้ผิวตึงหรือแห้ง', 'Aqua / Water / Eau, Glycerin, Cetearyl Alcohol, Caprylic/Capric Triglyceride, Cetyl Alcohol, Ceteareth-20, Petrolatum, Potassium Phosphate, Ceramide NP, Ceramide AP, Ceramide EOP, Carbomer, Dimethicone, Behentrimonium Methosulfate, Sodium Lauroyl Lactylate, Sodium Hyaluronate, Cholesterol, Phenoxyethanol, Disodium EDTA, Dipotassium Phosphate, Tocopherol, Phytosphingosine, Xanthan Gum, Ethylhexylglycerin', 3.5, NULL, NULL, 0, 50, '2025-11-25 16:10:31', '', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-half\"></i></span>\r\n                    <span class=\"text-muted\"><i class=\"bi bi-star\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">3.5</span>\r\n                </div>'),
(84, 'CeraVe SA Smoothing Cleanser', 390.00, './assets/img/8.avif', 'เซราวี เอสเอ สมูทติ้ง คลีนเซอร์ คือผลิตภัณฑ์ทำความสะอาดที่ประกอบไปด้วยกรดซาลิไซลิกที่อ่อนโยน ช่วยผลัดเซลล์ผิวที่ตายแล้วและสิ่งสกปรกที่อุดตันรูขุมขนให้หลุดออก โดยไม่ทำให้ผิวระคายเคือง ทั้งยังปราศจากไมโครบีดที่อาจทำร้ายปราการผิว ผสานด้วยพลังเซราไมด์ที่จำเป็นต่อผิวถึง 3 ชนิด และยังมีส่วนประกอบไฮยาลูรอนิค เอซิดและไนอาซินาไมด์ ช่วยเผยผิวเรียบเนียน ชุ่มชื้น สุขภาพดี สูตรปราศจากน้ำหอม แอลกอฮอล์ และอุดมไปด้วยสารต้นอนุมูลอิสระวิตามินดี ไม่ก่อให้เกิดการอุดตัน ทดสอบแล้วบนผิวแพ้ง่าย ไม่ทำให้ผิวแห้งกร้านหลังล้างทำความสะอาด', 'Salicylic acid, Ceramides, Niacinamide, Gluconolactone', 5.0, NULL, NULL, 0, 50, '2025-11-25 16:10:31', '', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">5.0</span>\r\n                </div>'),
(85, 'CeraVe Retinol Serum', 1290.00, './assets/img/2556947.avif', 'Cerave เซราวี รีเซอร์เฟส เรตินอล เซรั่มบำรุงผิวหน้าสำหรับลดรอยสิว ช่วยผลัดเซลล์ผิวอย่างอ่อนโยน โดยไม่ทำร้ายปราการผิว สีผิวดูสม่ำเสมอขึ้นใน 4 สัปดาห์ พร้อมเสริมปราการผิวแข็งแรงด้วยพลังเซราไมด์ที่จำเป็นต่อผิว 3 ชนิด ลดรอยจากสิวและผลัดเซลล์ผิว อย่างอ่อนโยนด้วย Encapsulated Retinol พร้อม Licorice root extract ช่วยให้ผิวกระจ่างใส และ Niacinamide ช่วยปลอบประโลมผิว เนื้อซึมไวไม่เหนอะ อ่อนโยนต่อผิว และปราศจากน้ำหอม', 'AQUA/WATER, PROPANEDIOL, DIMETHICONE, CETEARYL ETHYLHEXANOATE, NIACINAMIDE, AMMONIUM POLYACRYLOYLDIMETHYLTAURATE, DIPOTASSIUM GLYCYRRHIZATE, HYDROGENATED LECITHIN, POTASSIUM PHOSPHATE, CERAMIDE NP, CERAMIDE AP, CERAMIDE EOP, CARBOMER, CETEARYL ALCOHOL, BEHENTRIMONIUM METHOSULFATE, DIMETHICONOL, LECITHIN, SODIUM CITRATE, RETINOL, SODIUM HYALURONATE, SODIUM LAUROYL LACTYLATE, CHOLESTEROL, PHENOXYETHANOL, ALCOHOL, ISOPROPYL MYRISTATE, CAPRYLYL GLYCOL, CITRIC ACID, TRISODIUM ETHYLENEDIAMINE DISUCCINATE, PENTYLENE GLYCOL, PHYTOSPHINGOSINE, XANTHAN GUM, POLYSORBATE 20, ETHYLHEXYLGLYCERIN.', 4.5, NULL, NULL, 0, 50, '2025-11-25 16:10:31', '', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-half\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">4.5</span>\r\n                </div>'),
(86, 'CeraVe Creme Reparatrice', 490.00, './assets/img/3.png', 'ครีมบำรุงผิวรอบดวงตา ลดเลือนความหมองคล้ำ และปลอบประโลมผิวรอยคล้ำรอบดวงตาเป็นปัญหาผิวบริเวณรอบดวงตาที่พบบ่อยที่สุด แต่ครีมฟื้นบำรุงดวงตาสามารถช่วยเพิ่มความเรียบเนียนและสว่างใสให้แก่ผิวบอบบางในบริเวณนี้ได้ เพื่อผลลัพธ์สูงสุดที่ก่อให้เกิดการระคายเคืองน้อยที่สุด ควรเลือกครีมบำรุงดวงตาที่มีส่วนผสมของเซราไมด์ซึ่งช่วยฟื้นบำรุงปราการปกป้องผิวตามธรรมชาติ และไฮยาลูโรนิค แอซิด ช่วยเติมเต็มริ้วรอยเล็กๆ เสริมความชุ่มชื้นเป็นพิเศษและปลอบประโลมผิวด้วยไนอาซินาไมด์ อีกทั้งยังควรเลือกผลิตภัณฑ์ซึ่งผ่านการตรวจสอบโดยจักษุแพทย์ อย่างเช่นครีมบำรุงรอบดวงตาของเซราวี ที่ผ่านการรับรองแล้วว่าอ่อนโยนสำหรับบริเวณรอบดวงตา', 'Aqua / Water / Eau, Niacinamide, Cetyl Alcohol, Caprylic/Capric Triglyceride, Glycerin, Propanediol, Isononyl Isononanoate, Jojoba Esters, Peg-20 Methyl Glucose Sesquistearate, Cetearyl Alcohol, Dimethicone, Methyl Glucose Sesquistearate, Asparagopsis Armata Extract, Ceramide Np, Ceramide Ap, Potassium Sorbate, Ceramide Eop, Sorbitol, Carbomer, Zinc Citrate, Behentrimonium Methosulfate, Triethanolamine, Aloe Barbadensis Leaf Extract, Sodium Lauroyl Lactylate, Sodium Hydroxide, Equisetum Arvense Extract, Sodium Hyaluronate, Cholesterol, Phenoxyethanol, Prunus Amygdalus Dulcis Oil, Tocopherol, Ascophyllum Nodosum Extract, Laureth-4, Hydrogenated Vegetable Oil, Tetrasodium Edta, Maltodextrin, Phytosphingosine, Xanthan Gum, Butylene Glycol, Ethylhexylglycerin, Chrysanthellum Indicum Extract', 4.0, NULL, NULL, 0, 49, '2025-11-25 16:10:31', '', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-muted\"><i class=\"bi bi-star\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">4.0</span>\r\n                </div>'),
(87, 'Moisturizing Lotion 88ml', 160.00, './assets/img/img-flashsale/Mois88ml.webp', 'เซราวี มอยซ์เจอร์ไรซิ่ง โลชั่น  ผลิตภัณฑ์บำรุงผิวหน้าและผิวกาย สูตรสำหรับผิวแห้งถึงแห้งมากผิวที่มีปัญหาแห้ง แดง ไม่สบายผิว คือผิวที่จำนวนเซราไมด์ลดลงมากกว่าปกติ อันเป็นสาเหตุของปราการผิวอ่อนแอลง คงจะดีถ้ามีผลิตภัณฑ์ที่จะเติมเซราไมด์และความชุ่มชื้นที่ให้ผลลัพธ์ที่ยาวนานนวัตกรรมการคืนเซราไมด์ให้ผิวเซราวีประกอบด้วยเซราไมด์ที่จำเป็นต่อผิว 3 ชนิด โดยสกัดจากพืชธรรมชาติ พร้อมผสานด้วยไฮยาลูรอนิกแอซิด เพื่อช่วยชดเชยความชุ่มชื้น, เซราไมด์ที่ขาดหายไป และเสริมสร้างปราการปกป้องผิว สัมผัสกับเทคโนโลยี MVE เพื่อผลลัพธ์ที่ยาวนานเทคโนโลยีลิขสิทธิ์เฉพาะของเซราวี ที่จะนำพาเซราไมด์เข้าฟื้นบำรุงผิวอย่างยาวนานและล้ำลึก ผลลัพธ์ที่ได้คือ ปราการปกป้องผิวที่ดีขึ้น เพื่อผิวที่เนียนนุ่ม ชุ่มชื้นยาวนานกว่า 24 ชั่วโมง เมื่อใช้เป็นประจำอย่างต่อเนื่อง จากผลทดสอบในกลุ่มตัวอย่างผู้หญิง 10 คน อายุ 36-56 ปี โดย PRACS Dermatology, California ประเทศสหรัฐอเมริกา ปี 2548สูตรปราศจากน้ำหอม สูตรไม่ก่อให้เกิดการอุดตัน (Non Comedogenic) ไม่เหนียวเหนอะหนะสูตรไฮโปอัลเลอจีนิค ผลิตภัณฑ์ผ่านการทดสอบบนผิวที่บอบบางระคายเคืองง่าย ภายใต้การควบคุมดูแลโดยแพทย์ผู้เชี่ยวชาญทางด้านผิวหนัง และพัฒนาวิจัยค้นคว้าร่วมกับแพทย์ผิวหนัง', 'Ceramide 1,3,6-II, Cholesterol , Glycerin', 5.0, 320.00, 50, 1, 38, '2025-11-25 16:10:31', '<span class=\"discount-badge-item my-3 \">-50%</span>', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">5.0</span>\r\n                </div>'),
(88, 'Vitamin C Serum 30g', 879.00, './assets/img/img-flashsale/vitC.webp', 'Cerave Skin Renewing Vitamin C Serum เซราวี สกิน รีนิววิ่ง วิตามินซี เซรั่ม เซรั่มบำรุงผิวหน้า เซรั่มที่มีวิตามินซีบริสุทธิ์เข้มข้น 10 (แอลแอสคอร์บิก แอซิด) เซรั่มผสานเซราไมด์ 3 ชนิดที่จำเป็นต่อผิว เสริมเกราะปกป้องผิวแข็งแรง ใช้แล้วรู้สึกสบายผิว พร้อมเติมความชุ่มชื้นให้แก่ผิวด้วยอนุพันธ์ไฮยาลูโรนิก แอซิด และวิตามินบี 5 พร้อมด้วยเทคโนโลยีลิขสิทธิ์ของเซราวี MVE ที่จะค่อยๆ ปลดปล่อยสารสำคัญให้ผิวชุ่มชื้นอย่างต่อเนื่องยาวนาน เพื่อผิวที่ชุ่มชื้นดูสุขภาพดี ด้วยเนื้อสัมผัสที่บางเบา ซึมไว ไม่เหนียวเหนอะหนะ เหมาะสำหรับทุกสภาพผิว รวมถึงผิวบอบบางมีแนวโน้มแพ้ง่าย สูตรปราศจากน้ำหอม สูตรไม่ก่อให้เกิดการอุดตัน (noncomedogenic) สูตรปราศจากสารกันเสียพาราเบน และผ่านการทดสอบบนผิวที่บอบบางมีแนวโน้มระคายเคืองง่าย ภายใต้การควบคุมดูแลของแพทย์ผู้เชี่ยวชาญด้านผิวหนัง vitamin c', 'วิตามินซีบริสุทธิ์เข้มข้น 10 เซราไมด์ ไฮยาลูโรนิก แอซิด วิตามินบี 5', 5.0, 999.00, 12, 1, 49, '2025-11-25 16:10:31', '<span class=\"discount-badge-item my-3 \">-12%</span>', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">5.0</span>\r\n                </div>'),
(89, 'Moisturizing Cream 50ml', 193.00, './assets/img/img-flashsale/Mois50.webp', 'ใครที่มีปัญหาผิวหน้าและผิวกายแห้งมากควรรู้จักกับเซราวี มอยส์เจอไรซิ่งครีมCerave moisturizing creamผลิตภัณฑ์บำรุงผิวหน้าและผิวกาย สูตรสำหรับผิวแห้งถึงแห้งมาก ซึ่งผิวที่มีปัญหาแห้ง แดง ไม่สบายผิว คือผิวที่จำนวนเซราไมด์ลดลงมากกว่าปกติ อันเป็นสาเหตุของปราการผิวอ่อนแอลง Cerave moisturizing creamจากเซราวีประกอบด้วยเซราไมด์ที่จำเป็นต่อผิว 3 ชนิด โดยสกัดจากพืชธรรมชาติ พร้อมผสานด้วยไฮยาลูรอนิกแอซิด เพื่อช่วยชดเชยความชุ่มชื้น,เซราไมด์ที่ขาดหายไป และเสริมสร้างปราการปกป้องผิว สัมผัสกับเทคโนโลยีMVEเพื่อผลลัพธ์ที่ยาวนานMVEเป็นเทคโนโลยีลิขสิทธิ์เฉพาะของเซราวี ที่จะนำพาเซราไมด์เข้าฟื้นบำรุงผิวอย่างยาวนานและล้ำลึก ผลลัพธ์ที่ได้คือ ปราการปกป้องผิวที่ดีขึ้น เพื่อผิวที่เนียนนุ่ม ชุ่มชื้นยาวนานกว่า24 ชั่วโมงโดยCerave moisturizing creamรวมถึงผลิตภัณฑ์อื่นๆ จากเซราวีเป็นผลิตภัณฑ์ที่อ่อนโยน สูตรปราศจากน้ำหอม สูตรไม่ก่อให้เกิดการอุดตัน (Non Comedogenic)ไม่เหนียวเหนอะหนะ และเป็นสูตรไฮโปอัลเลอจีนิค ผลิตภัณฑ์ผ่านการทดสอบบนผิวที่บอบบางระคายเคืองง่าย ภายใต้การควบคุมดูแลโดยแพทย์ผู้เชี่ยวชาญทางด้านผิวหนัง และพัฒนาวิจัยค้นคว้าร่วมกับแพทย์ผิวหนัง บรรจุภัณฑ์ -บรรจุภัณฑ์สามารถนำไปรีไซเคิลได้', 'Ceramide 1,3,6-II, Cholesterol , Glycerin', 5.0, 225.00, 14, 1, 50, '2025-11-25 16:10:31', '<span class=\"discount-badge-item my-3 \">-14%</span>', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">5.0</span>\r\n                </div>'),
(90, 'Blemish Control Gel', 261.00, './assets/img/img-flashsale/blemish.avif', 'เซราวี เบลมมิช คอนโทรล เจล มอยส์เจอร์ไรเซอร์บำรุงผิวหน้าสำหรับผิวที่เป็นสิวง่าย ดูแลผิวเป็นสิว ช่วยให้ผิวดูเนียนใสขึ้นอย่างเห็นได้ชัด ผสานพลังเซราไมด์ที่จำเป็นต่อผิว 3 ชนิดและผลัดเซลล์ผิวอย่างอ่อนโยนด้วย AHA และ BHA อย่าง Glycolic Acid, Lactic Acid amp; 2เปอร์เซ็นต์ Salicylic Acid เพื่อขจัดสิวพร้อมปกป้องเกราะปกป้องผิวให้แข็งแรง', 'AQUA/WATER, GLYCERIN, SODIUM HYDROXIDE, GLYCOLIC ACID, LACTIC ACID, SALICYLIC ACID, NIACINAMIDE, CERAMIDE NP, CERAMIDE AP, CERAMIDE EOP, CARBOMER, CETEARYL ALCOHOL, BEHENTRIMONIUM METHOSULFATE, TRIETHYL CITRATE, SODIUM HYALURONATE, SODIUM LAUROYL LACTYLATE, CHOLESTEROL, CHLORPHENESIN, DISODIUM EDTA, HYDROXYPROPYL GUAR, CAPRYLYL GLYCOL, XANTHAN GUM, PHYTOSPHINGOSINE, BENZOIC ACID', 5.0, 290.00, 10, 1, 50, '2025-11-25 16:10:31', '<span class=\"discount-badge-item my-3 \">-10%</span>', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">5.0</span>\r\n                </div>'),
(91, 'Blemish Control Cleanser', 275.00, './assets/img/img-flashsale/blemishCleanser.avif', 'เซราวี เบลมมิช คลีนเซอร์ เจลโฟมทำความสะอาดผิวหน้าสำหรับผิวที่เป็นสิวง่าย ช่วยดูแลผิวเป็นสิวให้ผิวเนียนนุ่มและเรียบเนียน สูตรอ่อนโยนช่วยขจัดสิ่งสกปรก และความมันส่วนเกินโดยไม่ทำร้ายปราการผิว ปราศจากน้ำหอม พาราเบน ไม่ก่อให้เกิดการอุดตัน ทดสอบแล้วบนผิวแพ้ง่าย แพทย์ผิวหนังแนะนำ', 'AQUA/WATER, SODIUM LAUROYL SARCOSINATE, COCAMIDOPROPYL HYDROXYSULTAINE, GLYCERIN, NIACINAMIDE, SALICYLIC ACID, GLUCONOLACTONE, SODIUM METHYL COCOYL TAURATE, PEG-150 PENTAERYTHRITYL TETRASTEARATE, CERAMIDE NP, CERAMIDE AP, CERAMIDE EOP, CARBOMER, CALCIUM GLUCONATE, TRIETHYL CITRATE, SODIUM BENZOATE, SODIUM HYDROXIDE, SODIUM LAUROYL LACTYLATE, CHOLESTEROL, TETRASODIUM EDTA, CAPRYLYL GLYCOL, HYDROLYZED HYALURONIC ACID, TRISODIUM ETHYLENEDIAMINE DISUCCINATE, XANTHAN GUM, HECTORITE, PHYTOSPHINGOSINE, BENZOIC ACID.', 5.0, 290.00, 5, 1, 49, '2025-11-25 16:10:31', '<span class=\"discount-badge-item my-3 \">-5%</span>', '<div class=\"p-0\">\r\n        <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span>\r\n                    <span class=\"ms-2 text-dark\">5.0</span>\r\n                </div>'),
(92, 'ทดสอบเพิ่มรายการ', 900.00, './assets/img/products/p_20260202_130800_77a74f14.jpg', 'test', 'test', 5.0, 1800.00, 50, 1, 1, '2026-02-02 12:01:16', '<span class=\"discount-badge-item my-3 \">-50%</span>', '<div class=\"p-0\"><span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span><span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span><span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span><span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span><span class=\"text-warning\"><i class=\"bi bi-star-fill\"></i></span><span class=\"ms-2 text-dark\">5.0</span></div>');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `is_admin`, `created_at`) VALUES
(9, 'pongthon', 'watthungyai', 'pongthon@hotmail.com', '$2y$10$DcKgKKqW3sLNqQWGoLvUA.qQ.V0.ZhIAwfJDa5GZs8tfrNDEaSkuK', 0, '2026-02-01 16:01:13'),
(11, 'admin', 'user01', 'admin@hotmail.com', '$2y$10$taxOxZ.olPq9aq/l7.0jruZK9H1ynSiv8q9.zq2Qg3Dtqn7.xNVvq', 1, '2026-02-02 11:57:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
