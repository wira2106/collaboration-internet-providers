-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 02 Feb 2021 pada 09.26
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jinter_testing`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bank`
--

CREATE TABLE `bank` (
  `bank_id` int(11) NOT NULL,
  `namaBank` varchar(255) DEFAULT NULL,
  `logo` varchar(100) NOT NULL,
  `caraBayar` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `bank`
--

INSERT INTO `bank` (`bank_id`, `namaBank`, `logo`, `caraBayar`,`created_at`,`created_by`) VALUES
(1, 'BCA', 'bank_bca.jpg', '<b>Melalui ATM BCA</b><br>\n1 - Memilih menu \'Transaksi Lainnya\'<br>\n2 - Memilih menu \'Transfer\'<br>\n3 - Memilih menu \'Ke Rek BCA Virtual Account\'<br>\n4 - Memasukkan nomor BCA Virtual Account yang dituju<br>\n5 - Mereview pembayaran dan selesai<br>\n6 - Menyimpan bukti pembayaran<br>\n<br>\n<b>Melalui KlikBCA</b><br>\n1 - Memilih menu \'Transfer Dana\'<br>\n2 - Memilih menu \'Transfer ke BCA Virtual Account\'<br>\n3 - Memasukkan nomor BCA Virtual Account yang dituju<br>\n4 - Mereview pembayaran Anda lalu klik \'lanjutkan\'<br>\n5 - Memasukkan respons dari KeyBCA apply 1 dan klik \'Kirim\'<br>\n6 - Menyimpan bukti pembayaran<br>\n<br>\n<b>Melalui m-BCA</b><br>\n1 - Memilih menu \'m-Transfer\'<br>\n2 - Memilih menu \'BCA Virtual Account\'<br>\n3 - Memilih menu untuk memasukkan nomer virtual account<br>\n4 - Memasukkan nomer virtual account<br>\n5 - Mereview nomer virtual account & rekening Anda, lalu klik \'Send\'<br>\n6 - Mereview pembayaran Anda &, lalu klik \'Ok\'<br>\n7 - Memasukkan PIN m-BCA Anda<br>\n8 - Menyimpan bukti pembayaran<br>','2020-02-04 00:00:00',1),
(2, 'Mandiri', 'bank_mandiri.png', '','2020-02-04 00:00:00',1),
(3, 'BNI', 'bank_bni.png', '','2020-02-04 00:00:00',1),
(4, 'BRI', 'bank_bri.png', '','2020-02-04 00:00:00',1),
(5, 'BRI Syariah', 'bank_bri_syariah.png', '','2020-02-04 00:00:00',1),
(6, 'Syariah Mandiri', 'bank_mandiri_syariah.png', '','2020-02-04 00:00:00',1),
(7, 'Permata', 'bank_permata.jpg', '','2020-02-04 00:00:00',1),
(8, 'BPD KALSEL', '', '','2020-02-04 00:00:00',1),
(9, 'Nagari', 'bank_nagari.png', '','2020-02-04 00:00:00',1),
(10, 'Danamon', 'bank_danamon.png', '','2020-02-04 00:00:00',1),
(11, 'CIMB Niaga', 'bank_cimb_niaga.png', '','2020-02-04 00:00:00',1),
(12, 'Maybank', 'bank_maybank.png', '','2020-02-04 00:00:00',1),
(13, 'BANK RIAU KEPRI', '', '','2020-02-04 00:00:00',1),
(14, 'BPD BALI', '', '','2020-02-04 00:00:00',1),
(15, 'OVO', '', '','2020-02-04 00:00:00',1),
(16, 'Bank Jatim', '', '','2020-02-04 00:00:00',1),
(17, 'BANK BJB', '', '','2020-02-04 00:00:00',1),
(18, 'DANA', '', '','2020-02-04 00:00:00',1),
(19, 'SIMPEDES', '', '','2020-02-04 00:00:00',1),
(20, 'Bank BTPN', '', '','2020-02-04 00:00:00',1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`bank_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bank`
--
ALTER TABLE `bank`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
