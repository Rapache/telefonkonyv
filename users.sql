-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2016. Sze 30. 14:48
-- Kiszolgáló verziója: 10.1.13-MariaDB
-- PHP verzió: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `records`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `birthday` varchar(255) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8 NOT NULL,
  `zipstate` varchar(255) CHARACTER SET utf8 NOT NULL,
  `adress` varchar(255) CHARACTER SET utf8 NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `name`, `birthday`, `phone`, `zipstate`, `adress`, `created`, `modified`, `status`) VALUES
(19, 'Patonai BalÃ¡zs', '07/08/1991', '+36-30-925-3004', '7720, PÃ©csvÃ¡rad', 'Baranya, PÃ©csvÃ¡rad, GesztenyÃ©si Ãºt 14, MagyarorszÃ¡g', '2016-09-30 14:12:05', '2016-09-30 14:12:05', '1'),
(20, 'MÃ©zga AladÃ¡r', '01/01/1968', '+36-10-100-1000', '1069, Budapest', 'Magyar TelevÃ­ziÃ³ Zrt., Budapest, SzabadsÃ¡g tÃ©r, MagyarorszÃ¡g', '2016-09-30 14:17:02', '2016-09-30 14:17:02', '1');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
