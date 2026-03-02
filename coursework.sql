-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4
-- Время создания: Мар 02 2026 г., 22:04
-- Версия сервера: 8.4.6
-- Версия PHP: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `coursework`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id_category` int NOT NULL,
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id_category`, `category_name`) VALUES
(1, 'Блоки питания'),
(2, 'Видеокарты'),
(3, 'Жесткие диски HDD'),
(4, 'Корпуса'),
(5, 'Материнские платы'),
(6, 'Мониторы'),
(7, 'Оперативная память'),
(8, 'Охлаждение компьютера'),
(9, 'Периферия'),
(10, 'Процессоры'),
(11, 'Твердотельные накопители SSD');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id_orders` int NOT NULL,
  `user_id` int NOT NULL,
  `total_price` float NOT NULL,
  `status` varchar(16) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id_products` int NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text,
  `price` float NOT NULL,
  `image` varchar(255) DEFAULT 'no-image.jpg',
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id_products`, `name`, `description`, `price`, `image`, `category_id`) VALUES
(1, 'Intel Core i5-13400F', '10 ядер, 16 потоков, L3 20МБ, LGA1700', 15399, 'Intel_Core_i5-13400F_OEM.webp', 10),
(2, 'AMD Ryzen 7 7800X3D', '8 ядер, 16 потоков, огромный кэш L3 96МБ, AM5', 31799, 'AMD_Ryzen_7_7800X3D.webp', 10),
(3, 'Intel Core i9-14900K', '24 ядра, 32 потока, до 6.0 ГГц, LGA1700', 47499, 'Intel_Core_i9-14900K.webp', 10),
(4, 'NVIDIA GeForce RTX 4060 Ti', '8 ГБ GDDR6, DLSS 3.0, идеальна для 1080p', 31999, 'NVIDIA_GeForce_RTX_4060_Ti.webp', 2),
(5, 'NVIDIA GeForce RTX 4070 Super', '12 ГБ GDDR6X, высокая производительность в 2K', 54999, 'NVIDIA_GeForce_RTX_4070_Super.webp', 2),
(6, 'AMD Radeon RX 7900 XTX', '24 ГБ GDDR6, мощное решение от AMD для 4K', 106999, 'AMD_Radeon_RX_7900_XTX.webp', 2),
(7, 'ASUS ROG STRIX B760-F', 'LGA1700, DDR5, Wi-Fi 6E, отличный звук', 23199, 'ASUS_ROG_STRIX_B760-F.webp', 5),
(8, 'MSI MAG B650 TOMAHAWK', 'AM5, DDR5, надежное питание для Ryzen 7000', 18299, 'MSI_MAG_B650_TOMAHAWK.webp', 5),
(9, 'GIGABYTE Z790 AORUS ELITE', 'Чипсет Z790, поддержка разгона Intel 13/14 gen', 22299, 'GIGABYTE_Z790_AORUS_ELITE.webp', 5),
(10, 'Kingston FURY Beast 16GB', 'DDR5, 5200 МГц, комплект 2x8ГБ', 21599, 'Kingston_FURY_Beast_16GB.webp', 7),
(11, 'G.Skill TRIDENT Z5 RGB 32GB', 'DDR5, 6000 МГц, комплект 2x16ГБ', 43999, 'G.Skill_TRIDENT_Z5_RGB_32GB.webp', 7),
(12, 'Corsair Vengeance LPX 16GB', 'DDR4, 3200 МГц, надежная классика', 28799, 'Corsair_Vengeance_LPX_16GB.webp', 7),
(13, 'Samsung 980 Pro 1TB', 'M.2 NVMe, чтение до 7000 МБ/с, PCIe 4.0', 22699, 'Samsung_980_Pro_1TB.webp', 11),
(14, 'Kingston NV2 500GB', 'Бюджетный M.2 NVMe, PCIe 4.0', 9799, 'Kingston_NV2_500GB.webp', 11),
(15, 'Western Digital Black SN850X 2TB', 'Топовый SSD для геймеров и профи', 28499, 'Western_Digital_SN850X_2TB.webp', 11),
(16, 'Deepcool DQ750', '750W, сертификат 80+ Gold, модульный', 11299, 'Deepcool_DQ750.webp', 1),
(17, 'be quiet! Straight Power 11 850W', '850W, Platinum, бесшумный вентилятор', 27999, 'be_quiet!_Straight_Power_11_850W.webp', 1),
(18, 'Corsair RM1000x', '1000W, Gold, для мощных систем с RTX 4090', 16599, 'Corsair_RM1000x.webp', 1),
(19, 'LG UltraGear 27GP850-B', '27 дюймов, Nano IPS, 180 Гц, 2K разрешение', 31999, 'LG_UltraGear_27GP850-B.webp', 6),
(20, 'GIGABYTE M32U', '32 дюйма, IPS, 144 Гц, 4K, KVM-переключатель', 43499, 'GIGABYTE_M32U.webp', 6),
(21, 'AOC Gaming C24G2AE/BK', '24 дюйма, VA, 165 Гц, изогнутый Full HD', 14999, 'AOC_Gaming_C24G2AE_BK.webp', 6),
(22, 'Logitech G Pro X Superlight', 'Беспроводная игровая мышь, вес 63г', 9299, 'Logitech_G_Pro_X_Superlight.webp', 9),
(23, 'Keychron Q1 Pro', 'Механическая клавиатура, алюминиевый корпус', 17499, 'Keychron_Q1_Pro.webp', 9),
(24, 'HyperX Cloud II', 'Легендарная игровая гарнитура с 7.1 звуком', 7199, 'HyperX_Cloud_II.webp', 9),
(25, 'LIAN LI PC-O11 Dynamic', 'Панорамный аквариум для топовых сборок', 10699, 'LIAN_LI_PC-O11_Dynamic.webp', 4),
(26, 'Deepcool CC560', 'Отличный продув, 4 встроенных вентилятора', 4599, 'Deepcool_CC560.webp', 4),
(27, 'Fractal Design Meshify 2', 'Строгий дизайн и лучшая эргономика', 25399, 'Fractal_Design_Meshify_2.webp', 4),
(28, 'Deepcool AK620', 'Двухбашенный кулер, тянет даже i9', 4599, 'Deepcool_AK620.webp', 8),
(29, 'Arctic Liquid Freezer II 360', 'СЖО (водянка) с лучшим охлаждением VRM', 3099, 'Arctic_Liquid_Freezer_II_360.webp', 8),
(30, 'ID-COOLING SE-224-XTS', 'Народный кулер для i5 и Ryzen 5', 2050, 'ID-COOLING_SE-224-XTS.webp', 8),
(31, 'Seagate Barracuda 2TB', '7200 rpm, надежное хранилище файлов', 15199, 'Seagate_Barracuda_2TB.webp', 3),
(32, 'WD Blue 1TB', 'Классический жесткий диск для дома', 12399, 'WD_Blue_1TB.webp', 3),
(33, 'Toshiba X300 4TB', 'Высокая скорость и большой объем', 10499, 'Toshiba_X300_4TB.webp', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`, `role`) VALUES
(1, 'nikitadmin', '$2y$12$qtuNHMlCo9TpUX3c1y3Jme16G7o05OV73C0xiuXyHlk.Yv5Oo3rVS', 'n1k1takosenko83@gmail.com', 'admin'),
(3, 'nikitauser', '$2y$12$GnUmvoLZ1kE9sFi7eNmLIOByzvjWugOOweM.Bk67jb.H8j86Zk1Sm', 'n1k1takosenko82@gmail.com', 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_orders`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_products`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id_products` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
