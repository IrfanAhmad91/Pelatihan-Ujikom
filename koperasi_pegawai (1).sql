-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 02:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `koperasi_pegawai`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(11) NOT NULL,
  `nama_customer` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `no_telepon` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `nama_customer`, `alamat`, `no_telepon`, `email`, `created_at`) VALUES
(1, 'Budi Santoso', 'Jl. Merdeka No. 10, Jakarta', '081234567890', 'budi@gmail.com', '2025-06-15 04:45:19'),
(2, 'Ani WijayantoooA', 'Jl. Sudirman No. 25, Bandung', '082345678901', 'ani@yahoo.com', '2025-06-15 04:45:19'),
(3, 'Citra Dewi', 'Jl. Gatot Subroto No. 5, Surabaya', '083456789012', 'citra@gmail.com', '2025-06-15 04:45:19'),
(8, 'polo S.Pd L', 'jauh', '0213123123', 'kasir@gmail.com', '2025-06-15 08:27:40'),
(9, 'siapa aja', 'jauh', '12312313', 'polo@gmail.com', '2025-06-15 08:29:38'),
(10, 'siapa aja', 'polo', '12312313', 'polo@gmail.com', '2025-06-15 08:49:05'),
(11, 'polo S.Pd', 'jawa', '0124142112', 'irfanahmad.ia91@gmail.com', '2025-06-15 09:40:21');

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaction`
--

CREATE TABLE `detail_transaction` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_transaction`
--

INSERT INTO `detail_transaction` (`id_detail`, `id_transaksi`, `id_item`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 1, 1, 3000000.00, 3000000.00),
(2, 1, 7, 2, 320000.00, 640000.00),
(3, 1, 10, 1, 280000.00, 280000.00),
(4, 1, 8, 1, 380000.00, 380000.00),
(5, 1, 9, 1, 650000.00, 650000.00),
(6, 1, 4, 1, 2200000.00, 2200000.00),
(7, 2, 2, 1, 5200000.00, 5200000.00),
(8, 3, 3, 1, 3800000.00, 3800000.00),
(9, 3, 6, 1, 4200000.00, 4200000.00),
(10, 3, 5, 1, 3000000.00, 3000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id_item` int(11) NOT NULL,
  `nama_item` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL,
  `harga_jual` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id_item`, `nama_item`, `kategori`, `harga_beli`, `harga_jual`, `stok`, `satuan`, `created_at`) VALUES
(1, 'Televisi LED 32 inch', 'Elektronik', 2500000.00, 3000000.00, 15, 'unit', '2025-06-15 04:45:19'),
(2, 'Kulkas 2 Pintu', 'Elektronik', 4500000.00, 5200000.00, 8, 'unit', '2025-06-15 04:45:19'),
(3, 'Mesin Cuci 8kg', 'Elektronik', 3200000.00, 3800000.00, 10, 'unit', '2025-06-15 04:45:19'),
(4, 'Sofa 3 Seat', 'Furniture', 1800000.00, 2200000.00, 12, 'set', '2025-06-15 04:45:19'),
(5, 'Meja Makan 6 Kursi', 'Furniture', 2500000.00, 3000000.00, 7, 'set', '2025-06-15 04:45:19'),
(6, 'Kasur Springbed', 'Furniture', 3500000.00, 4200000.00, 5, 'unit', '2025-06-15 04:45:19'),
(7, 'Panci Stainless', 'Dapur', 250000.00, 320000.00, 30, 'buah', '2025-06-15 04:45:19'),
(8, 'BlenderrrA', 'Elektronik', 300000.00, 380000.00, 25, 'unit', '2025-06-15 04:45:19'),
(9, 'Kompor Gas', 'Dapur', 500000.00, 650000.00, 18, 'unit', '2025-06-15 04:45:19'),
(10, 'Piring Set', 'Dapur', 200000.00, 280000.00, 40, 'set', '2025-06-15 04:45:19'),
(11, 'sepatuh', 'Elektronik', 20000.00, 35000.00, 20, 'Pcs', '2025-06-15 08:21:20'),
(12, 'polo', 'Elektronik', 2000.00, 20000.00, 20, 'Pcs', '2025-06-15 08:59:48'),
(13, 'polo S.Pd', 'Furniture', 20000.00, 35000.00, 20, 'Pcs', '2025-06-15 09:05:01');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int(11) NOT NULL,
  `nama_petugas` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','petugas') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `username`, `password`, `level`, `created_at`) VALUES
(1, 'Admin Utama', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-06-15 04:45:19'),
(2, 'Petugas 1', 'petugas1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas', '2025-06-15 04:45:19'),
(3, 'Petugas 2', 'petugas2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petugas', '2025-06-15 04:45:19');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id_sales` int(11) NOT NULL,
  `nama_sales` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `no_telepon` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id_sales`, `nama_sales`, `alamat`, `no_telepon`, `email`, `created_at`) VALUES
(1, 'Yu Hermawawannann', 'Jl. Pahlawan No. 12, Jakarta', '086789012345', 'fajar@gmail.com', '2025-06-15 04:45:19'),
(2, 'Gita Nurul', 'Jl. Asia Afrika No. 8, Bandung', '087890123456', 'gita@yahoo.com', '2025-06-15 04:45:19'),
(3, 'Hendra Kurniawan', 'Jl. Pemuda No. 17, Surabaya', '088901234567', 'hendra@gmail.com', '2025-06-15 04:45:19'),
(4, 'Indah Permata', 'Jl. Veteran No. 22, Medan', '089012345678', 'indah@outlook.com', '2025-06-15 04:45:19'),
(5, 'Joko Susilo', 'Jl. Malioboro No. 5, Yogyakarta', '081123456789', 'joko@gmail.com', '2025-06-15 04:45:19'),
(7, 'polo', 'ciseeng', '085155024722', 'irfanahmad.ia7219@gmail.com', '2025-06-15 08:54:34'),
(8, 'polo', 'ciseeng', '085155024722', 'irfanahmad.ia7219@gmail.com', '2025-06-15 08:55:20'),
(9, 'polo', 'ciseeng', '085155024722', 'irfanahmad.ia7219@gmail.com', '2025-06-15 09:04:37'),
(10, 'polo', 'jauh', '085155024722', 'irfanahmad.ia7219@gmail.com', '2025-06-15 09:39:50');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id_transaksi` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `id_sales` int(11) DEFAULT NULL,
  `tanggal_transaksi` date NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `status_pembayaran` enum('lunas','cicilan','belum lunas') NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id_transaksi`, `id_customer`, `id_petugas`, `id_sales`, `tanggal_transaksi`, `total_harga`, `status_pembayaran`, `catatan`, `created_at`) VALUES
(1, 1, 2, 1, '2023-01-15', 6800000.00, 'lunas', 'Pembelian tunai', '2025-06-15 04:45:19'),
(2, 2, 3, 2, '2023-01-20', 5200000.00, 'cicilan', 'DP 50%', '2025-06-15 04:45:19'),
(3, 3, 2, 3, '2023-02-05', 8200000.00, 'belum lunas', 'Belum dibayar', '2025-06-15 04:45:19'),
(5, 1, 2, 1, '2025-06-15', 500000.00, 'lunas', NULL, '2025-06-15 08:52:51'),
(6, 1, 3, 10, '2025-06-15', 50000.00, 'lunas', NULL, '2025-06-15 09:41:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `detail_transaction`
--
ALTER TABLE `detail_transaction`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_item` (`id_item`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id_sales`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_petugas` (`id_petugas`),
  ADD KEY `id_sales` (`id_sales`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `detail_transaction`
--
ALTER TABLE `detail_transaction`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id_sales` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaction`
--
ALTER TABLE `detail_transaction`
  ADD CONSTRAINT `detail_transaction_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaction` (`id_transaksi`),
  ADD CONSTRAINT `detail_transaction_ibfk_2` FOREIGN KEY (`id_item`) REFERENCES `item` (`id_item`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`),
  ADD CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`id_sales`) REFERENCES `sales` (`id_sales`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
