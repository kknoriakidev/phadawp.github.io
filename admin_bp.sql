-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Апр 27 2020 г., 02:57
-- Версия сервера: 10.1.41-MariaDB-0+deb9u1
-- Версия PHP: 7.0.33-0+deb9u6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `admin_bp`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bp_admins`
--

CREATE TABLE `bp_admins` (
  `id` int(11) NOT NULL,
  `auth` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `access` varchar(25) NOT NULL,
  `flags` varchar(10) NOT NULL,
  `servpass` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `skype` varchar(32) NOT NULL,
  `server_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `utime` int(11) DEFAULT NULL,
  `hash` text NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `all` int(1) NOT NULL DEFAULT '0',
  `click` tinyint(1) NOT NULL DEFAULT '0',
  `last_reset` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_auth_count`
--

CREATE TABLE `bp_auth_count` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_chat_mess`
--

CREATE TABLE `bp_chat_mess` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_donaters`
--

CREATE TABLE `bp_donaters` (
  `id` int(11) NOT NULL,
  `nick` varchar(32) NOT NULL,
  `summ` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_donate_history`
--

CREATE TABLE `bp_donate_history` (
  `id` int(11) NOT NULL,
  `nick` varchar(32) NOT NULL,
  `summ` int(11) NOT NULL,
  `sign` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_keys`
--

CREATE TABLE `bp_keys` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `server_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_keys_content`
--

CREATE TABLE `bp_keys_content` (
  `id` int(11) NOT NULL,
  `key_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `percent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_keys_users`
--

CREATE TABLE `bp_keys_users` (
  `id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(32) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `hash` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_keys_wins`
--

CREATE TABLE `bp_keys_wins` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `serviceId` int(11) NOT NULL,
  `serverId` int(11) NOT NULL,
  `taked` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_payment_history`
--

CREATE TABLE `bp_payment_history` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `server_id` int(11) NOT NULL DEFAULT '0',
  `service_id` int(11) NOT NULL DEFAULT '0',
  `service_time` int(11) NOT NULL DEFAULT '0',
  `unban` varchar(128) NOT NULL DEFAULT '0',
  `change` int(11) NOT NULL DEFAULT '0',
  `price` varchar(16) NOT NULL,
  `method` int(11) NOT NULL DEFAULT '2',
  `session` varchar(32) DEFAULT NULL,
  `timestamp` int(11) NOT NULL,
  `is_play` int(11) NOT NULL DEFAULT '0',
  `is_paid` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_servers`
--

CREATE TABLE `bp_servers` (
  `id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `hostname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `login` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `rules` text COLLATE utf8_unicode_ci,
  `skins` int(1) NOT NULL DEFAULT '0',
  `skins_file` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '/cstrike/addons/amxmodx/configs/cm/custom_models.ini',
  `skins_save` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `playerlist` longtext CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_settings`
--

CREATE TABLE `bp_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(16) DEFAULT NULL,
  `discount` varchar(5) DEFAULT NULL,
  `colour` varchar(10) NOT NULL DEFAULT 'green',
  `num_page` varchar(4) NOT NULL DEFAULT '15',
  `avatar` varchar(300) NOT NULL DEFAULT 'https://sun9-45.userapi.com/c206716/v206716088/5750f/RR4M7gAsZX0.jpg',
  `email` varchar(35) DEFAULT NULL,
  `sozdatel` varchar(30) DEFAULT 'https://vk.com/',
  `gruppa` varchar(11) NOT NULL DEFAULT '185154874',
  `lp_on` int(11) NOT NULL,
  `lp_pbkey` varchar(100) DEFAULT NULL,
  `lp_prkey` varchar(100) DEFAULT NULL,
  `robo_on` int(11) NOT NULL,
  `robo_login` varchar(100) DEFAULT NULL,
  `robo_pass1` varchar(100) DEFAULT NULL,
  `robo_pass2` varchar(100) DEFAULT NULL,
  `curr` varchar(7) NOT NULL DEFAULT 'руб.',
  `wmr_on` int(11) NOT NULL,
  `wmr_purse` varchar(100) DEFAULT NULL,
  `wmr_secret_key` varchar(100) DEFAULT NULL,
  `uni_on` int(11) NOT NULL,
  `uni_purse` varchar(100) DEFAULT NULL,
  `uni_secret_key` varchar(100) DEFAULT NULL,
  `cron` varchar(20) NOT NULL DEFAULT '1234567',
  `free_on` int(11) NOT NULL,
  `free_login` varchar(100) DEFAULT NULL,
  `free_pass1` varchar(100) DEFAULT NULL,
  `free_pass2` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bp_settings`
--

INSERT INTO `bp_settings` (`id`, `site_name`, `discount`, `colour`, `num_page`, `avatar`, `email`, `sozdatel`, `gruppa`, `lp_on`, `lp_pbkey`, `lp_prkey`, `robo_on`, `robo_login`, `robo_pass1`, `robo_pass2`, `curr`, `wmr_on`, `wmr_purse`, `wmr_secret_key`, `uni_on`, `uni_purse`, `uni_secret_key`, `cron`, `free_on`, `free_login`, `free_pass1`, `free_pass2`) VALUES
(1, 'BuyPrivileges', '30%', 'red', '15', 'https://sun9-45.userapi.com/c206716/v206716088/5750f/RR4M7gAsZX0.jpg', '', '', '', 0, '', '', 0, '', '', '', 'руб.', 0, '', '', 0, '', '', '1234567', 0, '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `bp_skin`
--

CREATE TABLE `bp_skin` (
  `id` int(11) NOT NULL,
  `nick` varchar(32) NOT NULL,
  `server_id` int(11) NOT NULL,
  `skin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_skins`
--

CREATE TABLE `bp_skins` (
  `id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL DEFAULT '0',
  `model_name` varchar(32) NOT NULL,
  `name_tt` varchar(32) NOT NULL,
  `name_ct` varchar(32) NOT NULL,
  `image` varchar(256) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_tarifs`
--

CREATE TABLE `bp_tarifs` (
  `id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `access` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci,
  `binds` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_tarif_time`
--

CREATE TABLE `bp_tarif_time` (
  `id` int(11) NOT NULL,
  `tarif_id` int(11) NOT NULL,
  `server_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_temp_adm`
--

CREATE TABLE `bp_temp_adm` (
  `id` int(11) NOT NULL,
  `auth` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `access` varchar(25) NOT NULL,
  `flags` text NOT NULL,
  `server_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `utime` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `all` int(11) NOT NULL DEFAULT '0',
  `how` int(1) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_temp_skins`
--

CREATE TABLE `bp_temp_skins` (
  `id` int(11) NOT NULL,
  `nick` varchar(32) NOT NULL,
  `server_id` int(11) NOT NULL,
  `skin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bp_updates`
--

CREATE TABLE `bp_updates` (
  `id` int(11) NOT NULL,
  `version` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bp_updates`
--

INSERT INTO `bp_updates` (`id`, `version`) VALUES
(1, '3.1.3');

-- --------------------------------------------------------

--
-- Структура таблицы `data_base`
--

CREATE TABLE `data_base` (
  `SteamID` varchar(32) CHARACTER SET cp1250 NOT NULL,
  `Level` int(11) NOT NULL,
  `Exp` int(11) NOT NULL,
  `Money` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bp_admins`
--
ALTER TABLE `bp_admins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_auth_count`
--
ALTER TABLE `bp_auth_count`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_chat_mess`
--
ALTER TABLE `bp_chat_mess`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_donaters`
--
ALTER TABLE `bp_donaters`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_donate_history`
--
ALTER TABLE `bp_donate_history`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_keys`
--
ALTER TABLE `bp_keys`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_keys_content`
--
ALTER TABLE `bp_keys_content`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_keys_users`
--
ALTER TABLE `bp_keys_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_keys_wins`
--
ALTER TABLE `bp_keys_wins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_payment_history`
--
ALTER TABLE `bp_payment_history`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_servers`
--
ALTER TABLE `bp_servers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_settings`
--
ALTER TABLE `bp_settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_skin`
--
ALTER TABLE `bp_skin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_skins`
--
ALTER TABLE `bp_skins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_tarifs`
--
ALTER TABLE `bp_tarifs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_tarif_time`
--
ALTER TABLE `bp_tarif_time`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_temp_adm`
--
ALTER TABLE `bp_temp_adm`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_temp_skins`
--
ALTER TABLE `bp_temp_skins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bp_updates`
--
ALTER TABLE `bp_updates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bp_admins`
--
ALTER TABLE `bp_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `bp_auth_count`
--
ALTER TABLE `bp_auth_count`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `bp_chat_mess`
--
ALTER TABLE `bp_chat_mess`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT для таблицы `bp_donaters`
--
ALTER TABLE `bp_donaters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `bp_donate_history`
--
ALTER TABLE `bp_donate_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `bp_keys`
--
ALTER TABLE `bp_keys`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `bp_keys_content`
--
ALTER TABLE `bp_keys_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `bp_keys_users`
--
ALTER TABLE `bp_keys_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=482;
--
-- AUTO_INCREMENT для таблицы `bp_keys_wins`
--
ALTER TABLE `bp_keys_wins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `bp_payment_history`
--
ALTER TABLE `bp_payment_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `bp_servers`
--
ALTER TABLE `bp_servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `bp_settings`
--
ALTER TABLE `bp_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `bp_skin`
--
ALTER TABLE `bp_skin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT для таблицы `bp_skins`
--
ALTER TABLE `bp_skins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `bp_tarifs`
--
ALTER TABLE `bp_tarifs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT для таблицы `bp_tarif_time`
--
ALTER TABLE `bp_tarif_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `bp_temp_adm`
--
ALTER TABLE `bp_temp_adm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `bp_updates`
--
ALTER TABLE `bp_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
