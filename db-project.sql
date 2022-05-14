-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2022-05-14 18:42:33
-- 伺服器版本： 10.4.24-MariaDB
-- PHP 版本： 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `db-project`
--

-- --------------------------------------------------------

--
-- 資料表結構 `items`
--

CREATE TABLE `items` (
  `OID` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `OID` int(32) NOT NULL,
  `UID` int(32) NOT NULL,
  `SID` int(32) NOT NULL,
  `status` varchar(16) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `finish_time` timestamp NULL DEFAULT NULL,
  `distance` double NOT NULL,
  `total_price` int(32) NOT NULL,
  `type` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `product`
--

CREATE TABLE `product` (
  `PID` int(11) NOT NULL,
  `SID` int(11) NOT NULL,
  `product_name` varchar(32) NOT NULL,
  `price` int(11) NOT NULL,
  `picture` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `shop`
--

CREATE TABLE `shop` (
  `SID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `shopname` varchar(32) NOT NULL,
  `location_longitude` double NOT NULL,
  `location_latitude` double NOT NULL,
  `phone_number` text NOT NULL,
  `category` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `transaction`
--

CREATE TABLE `transaction` (
  `TID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `type` varchar(16) NOT NULL,
  `value` int(11) NOT NULL,
  `time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `UID` int(11) NOT NULL,
  `account` varchar(16) NOT NULL,
  `password` char(64) NOT NULL,
  `salt` char(4) NOT NULL,
  `name` varchar(32) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 0,
  `location_longitude` double NOT NULL,
  `location_latitude` double NOT NULL,
  `phone_number` text NOT NULL,
  `balance` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`UID`, `account`, `password`, `salt`, `name`, `role`, `location_longitude`, `location_latitude`, `phone_number`, `balance`) VALUES
(1, 'pkid0anpig', '5e9abe7a56edadad8b08480359c4e8ba20e92cd1b118aa8c77f835d35550eb4f', '2163', 'anpig', 0, 123, 23, '0933288551', 0),
(2, 'qwe', 'e94d804fc147e85ebd5b963928ae0bf4648661d720a76a488d37262b6863ae3d', '9935', 'qwe', 0, 124, 34, '1231231231', 0),
(3, 'asd', '8c01fc1b4ad7d356cd463d662e7b78a35d14c0e902cdb44f192392707ccd0c89', '7932', 'asd', 0, 125, 34, '2342342342', 0);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`OID`,`PID`);

--
-- 資料表索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`PID`),
  ADD UNIQUE KEY `SID_product_name` (`SID`,`product_name`),
  ADD UNIQUE KEY `picture` (`picture`);

--
-- 資料表索引 `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`SID`),
  ADD UNIQUE KEY `UID` (`UID`),
  ADD UNIQUE KEY `phone_number` (`phone_number`) USING HASH;

--
-- 資料表索引 `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`TID`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UID`),
  ADD UNIQUE KEY `username` (`account`),
  ADD UNIQUE KEY `phone_number` (`phone_number`) USING HASH;

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `product`
--
ALTER TABLE `product`
  MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `shop`
--
ALTER TABLE `shop`
  MODIFY `SID` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `transaction`
--
ALTER TABLE `transaction`
  MODIFY `TID` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
