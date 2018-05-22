-- phpMyAdmin SQL Dump
-- version 4.7.8
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-05-22 16:36:53
-- 服务器版本： 5.7.20-log
-- PHP Version: 7.1.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itboye_tbk`
--

-- --------------------------------------------------------

--
-- 表的结构 `common_security_code`
--

CREATE TABLE `common_security_code` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` char(30) NOT NULL DEFAULT '' COMMENT '验证码/其它',
  `accepter` varchar(50) NOT NULL DEFAULT '' COMMENT '手机或邮箱',
  `create_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `expired_time` bigint(20) NOT NULL DEFAULT '0' COMMENT '到期时间',
  `ip` bigint(20) NOT NULL DEFAULT '0' COMMENT 'ip',
  `client_id` varchar(80) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '是否验证通过',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '验证码用途1注册，2用户更新',
  `update_time` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='验证表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `common_security_code`
--
ALTER TABLE `common_security_code`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `common_security_code`
--
ALTER TABLE `common_security_code`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
