-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 01 2018 г., 14:15
-- Версия сервера: 5.7.20
-- Версия PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ouch_news`
--

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `header` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `full_news` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `header`, `description`, `full_news`, `date`) VALUES
(1, '		    			Заголовок 1 новости 1\r\n		    	', '		    			это зрада \r\n		    	', 'День відкритих дверей в Харківській школі архітектури (далі — ХША) відбудеться 2 червня. Захід матиме кілька блоків. В рамках першої частини буде презентована нова програма бакалаврату, а також гості зможуть отримати консультації. У другій частині заходу організатори підготували серію лекцій про архітектурну освіту. Крім того, куратори Школи архітектури детально розкажуть, що та як студенти будуть вивчати в перший рік навчання.<br>\r\n\r\n-Наш день відкритих дверей спрямований на те, щоб дати відчуття майбутнім студентам того, що взагалі представляє собою наша школа, як вона працює, і які у нас плани на найближчі роки, — зазначила Олександра Нарижна, перша проректорка Харківської архітектурної школи.<br>\r\n\r\nЗа словами засновника ХША Олега Дроздова, головна проблема харківських ВНЗ, які готують майбутніх архітекторів — це відсутність взаємозв’язку між предметами, що викладаються. Тому в процесі навчання студенти більшість часу будуть проводити в студії. Саме тут всі отримані знання можна відразу застосувати на практиці. Як зазначив Олег Дроздов, в навчальній програмі зроблений акцент на вивченні проблем міста.\r\n<br>\r\n-Школа – це та реакція на кризу міст, яка до сих не знайшла, можливо, кінцеву стратегію, як трансформувати ось те радянське місто в місто нашого майбутнього – розумне, справедливе, щасливе місто. Власне, ці аспекти будуть в фокусі школи. Вона повинна озброїти молодих професіоналів, які здатні будуть вирішити весь комплекс, всю сукупність цих проблем та задач, — розповів Олег Дроздов.\r\n\r\nВсі деталі – програма, викладачі та дисципліни – можна дізнатися 2 червня під час Днів відкритих дверей. Захід відбудеться за адресою Конторська, 5.\r\n		    	', '2018-05-30'),
(2, 'Заголовок 2 новости', 'это зрада 2', 'эта зрада 2 состоит в том ', '2018-05-30'),
(3, 'Заголовок 2 новости', 'это перемога 2', 'эта перемога 2 состоит в том ', '2018-05-10'),
(4, 'Заголовок 3 новости', 'это перемога 3', 'эта перемога 3 состоит в том ', '2018-05-14'),
(5, 'Заголовок 3 новости', 'это перемога 3', 'эта перемога 3 состоит в том ', '2018-05-14'),
(6, 'Заголовок 3 новости', 'это перемога 3', 'эта перемога 3 состоит в том ', '2018-05-14'),
(7, 'Заголовок 3 новости', 'это перемога 3', 'эта перемога 3 состоит в том ', '2018-05-14'),
(8, 'Заголовок 3 новости', 'это перемога 3', 'эта перемога 3 состоит в том ', '2018-05-14'),
(9, 'это зрада ', 'это зрада для зрадофилов', 'эти зрадофилы будут довольны от того что зрада зрадная', '2018-05-03'),
(10, '111', '123', '324', '2018-06-17'),
(11, '12345', '2133454352', '`213q', '2018-06-23'),
(12, '12345', '2133454352', '`213q', '2018-06-23'),
(13, '12345', '2133454352', '`213q', '2018-06-23'),
(14, '12345', '2133454352', '`213q', '2018-06-23'),
(15, '12345', '2133454352', '`213q', '2018-06-23'),
(16, '12345', '2133454352', '`213q', '2018-06-23'),
(17, '12345', '2133454352', '`213q', '2018-06-23'),
(18, '12345', '2133454352', '`213q', '2018-06-23'),
(19, '12345', '2133454352', '`213q', '2018-06-23'),
(20, '12345', '2133454352', '`213q', '2018-06-23'),
(21, '12345', '2133454352', '`213q', '2018-06-23');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` char(32) NOT NULL DEFAULT '202cb962ac59075b964b07152d234b70',
  `access` enum('read','full') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'read'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `access`) VALUES
(1, 'first', '202cb962ac59075b964b07152d234b70', 'full'),
(2, 'second', '202cb962ac59075b964b07152d234b70', 'read'),
(3, 'third', '202cb962ac59075b964b07152d234b70', 'read'),
(4, 'fourth', '202cb962ac59075b964b07152d234b70', 'read'),
(5, 'fifth', '202cb962ac59075b964b07152d234b70', 'read'),
(6, 'sixth', '202cb962ac59075b964b07152d234b70', 'read'),
(7, 'seventh', '202cb962ac59075b964b07152d234b70', 'read'),
(8, 'eichth', '202cb962ac59075b964b07152d234b70', 'read'),
(9, 'nineth', '202cb962ac59075b964b07152d234b70', 'read'),
(10, 'tenth', '202cb962ac59075b964b07152d234b70', 'read'),
(11, 'eleventh', '202cb962ac59075b964b07152d234b70', 'read'),
(12, 'twelfth', '202cb962ac59075b964b07152d234b70', 'read'),
(13, 'Guest', 'd41d8cd98f00b204e9800998ecf8427e', 'read');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;