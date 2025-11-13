-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250905.4c34850c0b
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 13, 2025 at 03:21 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tencof`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddStokKeluar` (IN `p_bahan_baku_id` BIGINT, IN `p_jumlah` DECIMAL(8,2), IN `p_user_id` BIGINT)   BEGIN
    START TRANSACTION;

    -- Tambah ke history
    INSERT INTO stock_histories (bahan_baku_id, type, quantity, created_by, created_at)
    VALUES (p_bahan_baku_id, 'out', p_jumlah, p_user_id, NOW());

    -- Kurangi jumlah stok
    UPDATE stocks 
    SET quantity = quantity - p_jumlah, updated_at = NOW()
    WHERE bahan_baku_id = p_bahan_baku_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddStokMasuk` (IN `p_bahan_baku_id` BIGINT, IN `p_jumlah` DECIMAL(8,2), IN `p_user_id` BIGINT)   BEGIN
    START TRANSACTION;

    -- Tambah ke history
    INSERT INTO stock_histories (bahan_baku_id, type, quantity, created_by, created_at)
    VALUES (p_bahan_baku_id, 'in', p_jumlah, p_user_id, NOW());

    -- Update jumlah stok
    UPDATE stocks 
    SET quantity = quantity + p_jumlah, updated_at = NOW()
    WHERE bahan_baku_id = p_bahan_baku_id;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_supplier` (IN `p_name` VARCHAR(255), IN `p_phone` VARCHAR(255), IN `p_address` TEXT)   BEGIN
    DECLARE v_exists INT DEFAULT 0;

    -- 1️⃣ Cek apakah supplier dengan nama atau nomor telepon yang sama sudah ada
    SELECT COUNT(*) INTO v_exists
    FROM suppliers
    WHERE name = p_name OR phone = p_phone;

    IF v_exists > 0 THEN
        -- 2️⃣ Kalau sudah ada, hentikan proses dan munculkan pesan error
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Supplier sudah terdaftar (nama atau nomor telepon sama)!';
    ELSE
        -- 3️⃣ Mulai transaksi (biar aman kalau nanti ada error)
        START TRANSACTION;

        -- 4️⃣ Masukkan data supplier baru
        INSERT INTO suppliers (name, phone, address, created_at)
        VALUES (p_name, p_phone, p_address, NOW());

        -- 5️⃣ Simpan perubahan permanen
        COMMIT;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetGrafikStok30Hari` ()   BEGIN
    SELECT 
        DATE(sh.created_at) AS tanggal,
        SUM(CASE WHEN sh.type = 'in' THEN sh.quantity ELSE 0 END) AS stok_masuk,
        SUM(CASE WHEN sh.type = 'out' THEN sh.quantity ELSE 0 END) AS stok_keluar
    FROM stock_histories sh
    WHERE sh.created_at >= CURDATE() - INTERVAL 30 DAY
    GROUP BY DATE(sh.created_at)
    ORDER BY tanggal ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetLaporanStok` (IN `startDate` DATE, IN `endDate` DATE, IN `kategoriParam` VARCHAR(50))   BEGIN
    SELECT 
        bb.name AS nama_bahan,
        s.quantity AS stok_sekarang,
        SUM(CASE WHEN sh.type = 'in' THEN sh.quantity ELSE 0 END) AS total_stok_masuk,
        SUM(CASE WHEN sh.type = 'out' THEN sh.quantity ELSE 0 END) AS total_stok_keluar,
        (s.quantity) AS stok_tersedia
    FROM bahan_bakus bb
    LEFT JOIN stocks s ON s.bahan_baku_id = bb.id
    LEFT JOIN stock_histories sh ON sh.bahan_baku_id = bb.id 
        AND DATE(sh.created_at) BETWEEN startDate AND endDate
    WHERE (kategoriParam = 'Semua' OR bb.name LIKE CONCAT('%', kategoriParam, '%'))
    GROUP BY bb.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetStokMinimum` ()   BEGIN
    SELECT 
        bb.name AS nama_bahan,
        s.quantity AS stok_sekarang,
        bb.stok_minimum,
        CASE 
            WHEN s.quantity <= bb.stok_minimum / 2 THEN 'Kritis'
            WHEN s.quantity <= bb.stok_minimum THEN 'Rendah'
            ELSE 'Aman'
        END AS status_stok
    FROM bahan_bakus bb
    JOIN stocks s ON s.bahan_baku_id = bb.id
    WHERE s.quantity <= bb.stok_minimum
    ORDER BY s.quantity ASC;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bahan_bakus`
--

CREATE TABLE `bahan_bakus` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stok_minimum` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bahan_bakus`
--

INSERT INTO `bahan_bakus` (`id`, `name`, `satuan_id`, `created_by`, `created_at`, `updated_at`, `stok_minimum`) VALUES
(1, 'Kopi Arabika', 1, 1, '2025-11-03 15:30:28', NULL, 0),
(2, 'Susu UHT', 3, 1, '2025-11-03 15:30:28', NULL, 0),
(3, 'Gula Pasir', 2, 1, '2025-11-03 15:30:28', NULL, 0),
(4, 'Cup Plastik', 4, 1, '2025-11-03 15:30:28', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int NOT NULL,
  `id_transaksi` int DEFAULT NULL,
  `id_bahan` bigint UNSIGNED DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga` decimal(12,2) DEFAULT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS ((`jumlah` * `harga`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_21_165526_create_satuans_table', 1),
(5, '2025_10_21_165609_create_suppliers_table', 1),
(6, '2025_10_21_165659_create_bahan_bakus_table', 1),
(7, '2025_10_21_165944_create_stocks_table', 1),
(8, '2025_10_21_170219_create_stock_histories_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `satuans`
--

CREATE TABLE `satuans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `satuans`
--

INSERT INTO `satuans` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Kilogram', '2025-11-03 15:29:27', NULL),
(2, 'Gram', '2025-11-03 15:29:27', NULL),
(3, 'Liter', '2025-11-03 15:29:27', NULL),
(4, 'Botol', '2025-11-03 15:29:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('N29ynFWvfTUjTdFZSKH7k5l2r9u2GGWrf4BllOyS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY3k5VWxRMVA1YVNFczUxS0lhRWZKc1l0Vmx0VmF4RERwOGJ0QmlJTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTg6Imh0dHA6Ly90ZW5jb2YudGVzdCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762479375);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `bahan_baku_id` bigint UNSIGNED NOT NULL,
  `quantity` bigint NOT NULL DEFAULT '0',
  `unit_price` decimal(8,2) NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `bahan_baku_id`, `quantity`, `unit_price`, `supplier_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 150, 150000.00, 1, 1, '2025-11-03 15:30:52', NULL),
(2, 2, 200, 12000.00, 2, 1, '2025-11-03 15:30:52', NULL),
(3, 3, 500, 8000.00, 1, 1, '2025-11-03 15:30:52', NULL),
(4, 4, 300, 500.00, 2, 1, '2025-11-03 15:30:52', NULL);

--
-- Triggers `stocks`
--
DELIMITER $$
CREATE TRIGGER `after_stok_in` AFTER UPDATE ON `stocks` FOR EACH ROW BEGIN
    IF NEW.quantity > OLD.quantity THEN
        INSERT INTO stock_histories (bahan_baku_id, type, quantity, created_by, created_at)
        VALUES (NEW.bahan_baku_id, 'in', NEW.quantity - OLD.quantity, NEW.created_by, NOW());
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_stok_out` AFTER UPDATE ON `stocks` FOR EACH ROW BEGIN
    IF NEW.quantity < OLD.quantity THEN
        INSERT INTO stock_histories (bahan_baku_id, type, quantity, created_by, created_at)
        VALUES (NEW.bahan_baku_id, 'out', OLD.quantity - NEW.quantity, NEW.created_by, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `stock_histories`
--

CREATE TABLE `stock_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `bahan_baku_id` bigint UNSIGNED NOT NULL,
  `type` enum('in','out') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_histories`
--

INSERT INTO `stock_histories` (`id`, `bahan_baku_id`, `type`, `quantity`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'in', 50.00, 1, '2025-11-03 15:31:08', NULL),
(2, 2, 'in', 100.00, 1, '2025-11-03 15:31:08', NULL),
(3, 3, 'out', 30.00, 2, '2025-11-03 15:31:08', NULL),
(4, 4, 'in', 70.00, 2, '2025-11-03 15:31:08', NULL),
(5, 1, 'in', 50.00, 1, '2025-11-03 15:34:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'CV Maju Jaya', '081234567890', 'Jl. Raya Bandung No. 10', '2025-11-03 15:30:18', NULL),
(2, 'PT Sumber Sejahtera', '081298765432', 'Jl. Pahlawan No. 22', '2025-11-03 15:30:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `id_user` bigint UNSIGNED DEFAULT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(12,2) DEFAULT '0.00',
  `jenis` enum('pembelian','penjualan','pemakaian') DEFAULT 'penjualan',
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','kasir') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(5, 'Admin Utama', 'admin', 'admin@tencof.com', NULL, '123456', NULL, '2025-11-03 15:28:55', NULL),
(6, 'Kasir 1', 'kasir', 'kasir1@tencof.com', NULL, '123456', NULL, '2025-11-03 15:28:55', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_riwayat_stok`
-- (See below for the actual view)
--
CREATE TABLE `view_riwayat_stok` (
`history_id` bigint unsigned
,`nama_bahan` varchar(255)
,`jenis_perubahan` enum('in','out')
,`jumlah` decimal(8,2)
,`user_aksi` varchar(255)
,`waktu_aksi` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_stok_bahan`
-- (See below for the actual view)
--
CREATE TABLE `view_stok_bahan` (
`bahan_id` bigint unsigned
,`nama_bahan` varchar(255)
,`satuan` varchar(255)
,`jumlah_stok` bigint
,`harga_satuan` decimal(8,2)
,`total_nilai_stok` decimal(27,2)
,`supplier` varchar(255)
,`dibuat_oleh` varchar(255)
,`tanggal_input` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_total_stok_supplier`
-- (See below for the actual view)
--
CREATE TABLE `view_total_stok_supplier` (
`supplier` varchar(255)
,`total_jenis_bahan` bigint
,`total_unit` decimal(41,0)
,`total_nilai` decimal(49,2)
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bahan_bakus`
--
ALTER TABLE `bahan_bakus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bahan_bakus_name_unique` (`name`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_bahan` (`id_bahan`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `satuans`
--
ALTER TABLE `satuans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_histories`
--
ALTER TABLE `stock_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_name_unique` (`name`),
  ADD UNIQUE KEY `suppliers_phone_unique` (`phone`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bahan_bakus`
--
ALTER TABLE `bahan_bakus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `satuans`
--
ALTER TABLE `satuans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_histories`
--
ALTER TABLE `stock_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

-- --------------------------------------------------------

--
-- Structure for view `view_riwayat_stok`
--
DROP TABLE IF EXISTS `view_riwayat_stok`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_riwayat_stok`  AS SELECT `sh`.`id` AS `history_id`, `b`.`name` AS `nama_bahan`, `sh`.`type` AS `jenis_perubahan`, `sh`.`quantity` AS `jumlah`, `u`.`name` AS `user_aksi`, `sh`.`created_at` AS `waktu_aksi` FROM ((`stock_histories` `sh` join `bahan_bakus` `b` on((`sh`.`bahan_baku_id` = `b`.`id`))) join `users` `u` on((`sh`.`created_by` = `u`.`id`))) ORDER BY `sh`.`created_at` DESC ;

-- --------------------------------------------------------

--
-- Structure for view `view_stok_bahan`
--
DROP TABLE IF EXISTS `view_stok_bahan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_stok_bahan`  AS SELECT `b`.`id` AS `bahan_id`, `b`.`name` AS `nama_bahan`, `s`.`name` AS `satuan`, `st`.`quantity` AS `jumlah_stok`, `st`.`unit_price` AS `harga_satuan`, (`st`.`quantity` * `st`.`unit_price`) AS `total_nilai_stok`, `sp`.`name` AS `supplier`, `u`.`name` AS `dibuat_oleh`, `st`.`created_at` AS `tanggal_input` FROM ((((`stocks` `st` join `bahan_bakus` `b` on((`st`.`bahan_baku_id` = `b`.`id`))) join `satuans` `s` on((`b`.`satuan_id` = `s`.`id`))) join `suppliers` `sp` on((`st`.`supplier_id` = `sp`.`id`))) join `users` `u` on((`st`.`created_by` = `u`.`id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_total_stok_supplier`
--
DROP TABLE IF EXISTS `view_total_stok_supplier`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_total_stok_supplier`  AS SELECT `sp`.`name` AS `supplier`, count(`st`.`id`) AS `total_jenis_bahan`, sum(`st`.`quantity`) AS `total_unit`, sum((`st`.`quantity` * `st`.`unit_price`)) AS `total_nilai` FROM (`stocks` `st` join `suppliers` `sp` on((`st`.`supplier_id` = `sp`.`id`))) GROUP BY `sp`.`name` ORDER BY `total_nilai` DESC ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_bahan`) REFERENCES `bahan_bakus` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
