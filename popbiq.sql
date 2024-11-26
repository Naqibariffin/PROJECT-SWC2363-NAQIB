-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 06:14 PM
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
-- Database: `popbiq`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'ADMIN', 'admin@gmail.com', '1234', '2024-11-17 14:04:48');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `email`, `phone`, `address`, `city`, `zip`, `total_price`, `status`, `payment_method`, `created_at`, `product_id`, `quantity`, `price`) VALUES
(47, 'ss', 'kl2307013890@student.uptm.edu.my', '231', 'aas', 'ass', '333', 130.80, 'Shipped', 'Cash On Delivery', '2024-11-22 01:50:26', 10, 1, 130.80),
(48, 'Naqib', 'ali@gmail.com', '0123456789', '1846,Jalan Tkp 4', 'Alor Gajah', '78000', 149.40, 'Pending', 'Cash On Delivery', '2024-11-22 01:59:18', 9, 3, 49.80),
(49, 'Mikael', 'mikaelmika@gmail.com', '0123456789', '1846,Jalan TKP 4,Taman Kelemak Perdana', 'Alor Gajah', '78000', 149.40, 'Pending', 'Cash On Delivery', '2024-11-25 06:51:46', 14, 1, 49.80),
(50, 'Mikael', 'mikaelmika@gmail.com', '0123456789', '1846,Jalan TKP 4,Taman Kelemak Perdana', 'Alor Gajah', '78000', 149.40, 'Pending', 'Cash On Delivery', '2024-11-25 06:51:46', 17, 2, 49.80);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `category`, `price`, `image`, `created_at`) VALUES
(8, 'Hirono×Le Petit Prince Series Figures', 'Size: Height about 8cm-12cm\r\n\r\nMaterial: PVC', 'Hirono', 49.80, 'uploads/hirono1.jpg', '2024-11-19 09:12:55'),
(9, 'Hirono Shelter Series Figures', 'Size: Height about 6-10cm\r\nMaterial: PVC', 'Hirono', 49.80, 'uploads/20240715_143422_831856____1_____1200x1200.jpg', '2024-11-19 09:13:52'),
(10, 'Hirono x Keith Haring Figure', 'Size: Height about 10cm\r\nMaterial: PVC', 'Hirono', 130.80, 'uploads/20240717_142644_114823____1_____1200x1200.jpg', '2024-11-19 09:18:38'),
(11, 'THE MONSTERS - Tasty Macarons Vinyl Face', 'Size: Height about 17cm\r\nMaterial: 45% Polyester, 55% PVC', 'The Monsters', 70.90, 'uploads/20231026_101051_200156__1200x1200.jpg', '2024-11-19 09:19:52'),
(12, 'THE MONSTERS - FLIP WITH ME Vinyl Plush Doll', 'Size:Height about 40cm\r\nMaterial:63% Cotton,37%PVC', 'The Monsters', 350.00, 'uploads/20240708_145940_156429____1-1_____1200x1200.jpg', '2024-11-19 09:27:07'),
(13, 'DIMOO Weaving Wonders Series Figures', 'Size: Height about 7-11cm\r\nMaterial: PVC', 'Dimoo', 49.80, 'uploads/20240930_151942_982117____1_____1200x1200.jpg', '2024-11-19 09:28:00'),
(14, 'DIMOO：No One\'s Gonna Sleep Tonight Series Figures', 'Material: PVC/ABS\r\n\r\nSize: Height about 6-9cm', 'Dimoo', 49.80, 'uploads/1(1)_2aD8GUyfVR_1200x1200.jpg', '2024-11-19 09:28:31'),
(15, 'SKULLPANDA Everyday Wonderland', 'Size:Height about 6 - 9 cm\r\nMaterial:PVC/ABS', 'SkullPanda', 49.80, 'uploads/1_WqzMZxBwVK_1200x1200.jpg', '2024-11-19 09:29:15'),
(16, 'SKULLPANDA Bunny or Doggy Figure', 'PRODUCT SIZE: Height about 12cm\r\nMaterial:PVC/ABS/Nylon', 'SkullPanda', 83.80, 'uploads/20240924_180613_502726____1_____1200x1200.jpg', '2024-11-19 09:39:41'),
(17, 'CRYBABY × Powerpuff Girls Series ', 'Size: Height about 6-9cm\r\nMaterial: PVC/ABS', 'Crybaby', 49.80, 'uploads/20240304_164111_296715__1200x1200.jpg', '2024-11-19 09:40:22'),
(18, 'Crybaby Crying Parade', 'Size: Height about 4.6-10.5 cm\r\nMaterial: ABS/PVC', 'Crybaby', 49.80, 'uploads/1_ufATosiWVI_1200x1200.jpg', '2024-11-19 09:41:24'),
(21, 'Hirono Simper Figurine', 'Product Size:High about 15.5cm\r\n\r\nAge：15+', 'Hirono', 60.80, 'uploads/20231031_152142_506430__1200x1200.jpg', '2024-11-25 07:48:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(9, 'kl2307013890', '$2y$10$D58akF7Fih7zzRQ6B29jX.rNb5kmdI97SZbmro3p2jnM.n84i8UZO', 'kl2307013890@student.uptm.edu.my', '2024-11-19 07:44:04'),
(10, 'AM2307013890', '$2y$10$MLdTWhzTsYW3tVnLDcRJXenf8/nNYaKmqqsiLimlioPJLIjc4NTZi', 'naqibasyraaf5@gmail.com', '2024-11-22 01:44:47'),
(11, 'mikael', '$2y$10$qn9bxHJ.UvLL5Gq8PEwgvOIaNIPNr6O2zhfXKMBoCeoHI3s35A/zy', 'mikaelmika@gmail.com', '2024-11-25 06:26:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emailfk` (`email`),
  ADD KEY `FK_product` (`product_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
