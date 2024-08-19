-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 02 Mar 2021 pada 02.57
-- Versi server: 5.7.24
-- Versi PHP: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `openaccess`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Super Admin', '{\"company.companies.index\":true,\"company.companies.create\":true,\"company.companies.edit\":true,\"company.companies.destroy\":true,\"company.companies.detail\":true,\"company.paketberlangganans.index\":true,\"company.paketberlangganans.create\":true,\"company.paketberlangganans.edit\":true,\"company.paketberlangganans.destroy\":true,\"company.biaya_kabel.index\":true,\"company.biaya_kabel.edit\":true,\"company.slot_instalasi.index\":true,\"company.slot_instalasi.edit\":true,\"configuration.configurations.index\":true,\"configuration.configurations.edit\":true,\"configuration.bank.index\":true,\"configuration.bank.create\":true,\"configuration.bank.edit\":true,\"configuration.bank.destroy\":true,\"core.sidebar.group\":true,\"dashboard.index\":true,\"dashboard.update\":true,\"dashboard.reset\":true,\"invoice.invoices.index\":true,\"media.medias.index\":true,\"media.medias.create\":true,\"media.medias.edit\":true,\"media.medias.destroy\":true,\"media.folders.index\":true,\"media.folders.create\":true,\"media.folders.edit\":true,\"media.folders.destroy\":true,\"menu.menus.index\":true,\"menu.menus.create\":true,\"menu.menus.edit\":true,\"menu.menus.destroy\":true,\"menu.menuitems.index\":true,\"menu.menuitems.create\":true,\"menu.menuitems.edit\":true,\"menu.menuitems.destroy\":true,\"page.pages.index\":true,\"page.pages.create\":true,\"page.pages.edit\":true,\"page.pages.destroy\":true,\"pelanggan.pelanggans.index\":true,\"pelanggan.pelanggans.create\":true,\"pelanggan.pelanggans.edit\":true,\"pelanggan.salesorders.index\":true,\"pelanggan.salesorders.create\":true,\"pelanggan.salesorders.edit\":true,\"pelanggan.salesorders.destroy\":true,\"pelanggan.surveys.index\":true,\"pelanggan.surveys.create\":true,\"pelanggan.surveys.edit\":true,\"pelanggan.installations.index\":true,\"pelanggan.installations.create\":true,\"pelanggan.installations.edit\":true,\"pelanggan.activations.index\":true,\"pelanggan.activations.create\":true,\"pelanggan.activations.edit\":true,\"peralatan.alats.index\":true,\"peralatan.alats.create\":true,\"peralatan.alats.edit\":true,\"peralatan.alats.destroy\":true,\"peralatan.perangkats.index\":true,\"peralatan.perangkats.create\":true,\"peralatan.perangkats.edit\":true,\"peralatan.perangkats.destroy\":true,\"peralatan.barangs.index\":true,\"peralatan.barangs.create\":true,\"peralatan.barangs.edit\":true,\"peralatan.barangs.destroy\":true,\"presale.presales.index\":true,\"presale.presales.create\":true,\"presale.presales.edit\":true,\"presale.presales.delete\":true,\"presale.presales.confirm\":true,\"presale.endpoints.index\":true,\"presale.endpoints.create\":true,\"presale.endpoints.edit\":true,\"presale.endpoints.delete\":true,\"saldo.topups\":true,\"saldo.withdraws\":true,\"saldo.mutasi\":true,\"ticket.tickets.index\":true,\"ticket.tickets.create\":true,\"ticket.tickets.edit\":true,\"ticket.tickets.destroy\":true,\"user.users.index\":false,\"user.users.create\":true,\"user.users.edit\":true,\"user.users.destroy\":true,\"user.staff.index\":true,\"user.staff.create\":true,\"user.staff.edit\":true,\"user.staff.destroy\":true,\"user.roles.index\":true,\"user.roles.create\":true,\"user.roles.edit\":true,\"user.roles.destroy\":true,\"account.api-keys.index\":true,\"account.api-keys.create\":true,\"account.api-keys.destroy\":true,\"wilayah.wilayahs.index\":true,\"wilayah.wilayahs.create\":true,\"wilayah.wilayahs.update\":true,\"wilayah.wilayahs.destroy\":true,\"wilayah.pengajuans.index\":true,\"wilayah.pengajuans.create\":true,\"wilayah.pengajuans.update\":true,\"wilayah.pengajuans.destroy\":true,\"wilayah.pengajuans.confirm\":true,\"wilayah.pengajuans.approve\":true}', '2021-01-16 01:36:41', '2021-02-04 18:04:56'),
(2, 'admin osp', 'Admin OSP', '{\"company.companies.index\":false,\"company.companies.create\":false,\"company.companies.edit\":true,\"company.companies.destroy\":false,\"company.companies.detail\":true,\"company.paketberlangganans.index\":true,\"company.paketberlangganans.create\":true,\"company.paketberlangganans.edit\":true,\"company.paketberlangganans.destroy\":true,\"company.biaya_kabel.index\":true,\"company.biaya_kabel.edit\":true,\"company.slot_instalasi.index\":true,\"company.slot_instalasi.edit\":true,\"configuration.configurations.index\":false,\"configuration.configurations.edit\":false,\"configuration.bank.index\":false,\"configuration.bank.create\":false,\"configuration.bank.edit\":false,\"configuration.bank.destroy\":false,\"core.sidebar.group\":true,\"dashboard.index\":true,\"dashboard.update\":true,\"dashboard.reset\":true,\"invoice.invoices.index\":true,\"media.medias.index\":true,\"media.medias.create\":true,\"media.medias.edit\":true,\"media.medias.destroy\":true,\"media.folders.index\":true,\"media.folders.create\":true,\"media.folders.edit\":true,\"media.folders.destroy\":true,\"menu.menus.index\":true,\"menu.menus.create\":true,\"menu.menus.edit\":true,\"menu.menus.destroy\":true,\"menu.menuitems.index\":true,\"menu.menuitems.create\":true,\"menu.menuitems.edit\":true,\"menu.menuitems.destroy\":true,\"page.pages.index\":true,\"page.pages.create\":true,\"page.pages.edit\":true,\"page.pages.destroy\":true,\"pelanggan.pelanggans.index\":true,\"pelanggan.pelanggans.create\":false,\"pelanggan.pelanggans.edit\":false,\"pelanggan.salesorders.index\":true,\"pelanggan.salesorders.create\":true,\"pelanggan.salesorders.edit\":true,\"pelanggan.salesorders.destroy\":true,\"pelanggan.surveys.index\":true,\"pelanggan.surveys.create\":true,\"pelanggan.surveys.edit\":true,\"pelanggan.installations.index\":true,\"pelanggan.installations.create\":true,\"pelanggan.installations.edit\":true,\"pelanggan.activations.index\":true,\"pelanggan.activations.create\":true,\"pelanggan.activations.edit\":true,\"peralatan.alats.index\":false,\"peralatan.alats.create\":true,\"peralatan.alats.edit\":false,\"peralatan.alats.destroy\":false,\"peralatan.perangkats.index\":false,\"peralatan.perangkats.create\":true,\"peralatan.perangkats.edit\":false,\"peralatan.perangkats.destroy\":false,\"peralatan.barangs.index\":false,\"peralatan.barangs.create\":true,\"peralatan.barangs.edit\":false,\"peralatan.barangs.destroy\":false,\"presale.presales.index\":true,\"presale.presales.create\":false,\"presale.presales.edit\":false,\"presale.presales.delete\":false,\"presale.presales.confirm\":true,\"presale.endpoints.index\":true,\"presale.endpoints.create\":true,\"presale.endpoints.edit\":true,\"presale.endpoints.delete\":true,\"saldo.topups\":true,\"saldo.withdraws\":true,\"saldo.mutasi\":true,\"ticket.tickets.index\":true,\"ticket.tickets.create\":true,\"ticket.tickets.edit\":true,\"ticket.tickets.destroy\":true,\"user.users.index\":false,\"user.users.create\":true,\"user.users.edit\":true,\"user.users.destroy\":true,\"user.staff.index\":true,\"user.staff.create\":true,\"user.staff.edit\":true,\"user.staff.destroy\":true,\"user.roles.index\":false,\"user.roles.create\":false,\"user.roles.edit\":false,\"user.roles.destroy\":false,\"account.api-keys.index\":true,\"account.api-keys.create\":true,\"account.api-keys.destroy\":true,\"wilayah.wilayahs.index\":true,\"wilayah.wilayahs.create\":true,\"wilayah.wilayahs.update\":true,\"wilayah.wilayahs.destroy\":true,\"wilayah.pengajuans.index\":true,\"wilayah.pengajuans.create\":false,\"wilayah.pengajuans.update\":true,\"wilayah.pengajuans.destroy\":false,\"wilayah.pengajuans.confirm\":false,\"wilayah.pengajuans.approve\":true}', '2021-01-20 20:38:19', '2021-03-01 18:16:11'),
(3, 'admin isp', 'Admin ISP', '{\"company.companies.index\":false,\"company.companies.create\":false,\"company.companies.edit\":true,\"company.companies.destroy\":false,\"company.companies.detail\":true,\"company.paketberlangganans.index\":false,\"company.paketberlangganans.create\":false,\"company.paketberlangganans.edit\":false,\"company.paketberlangganans.destroy\":false,\"company.biaya_kabel.index\":false,\"company.biaya_kabel.edit\":false,\"company.slot_instalasi.index\":false,\"company.slot_instalasi.edit\":false,\"configuration.configurations.index\":false,\"configuration.configurations.edit\":false,\"configuration.bank.index\":false,\"configuration.bank.create\":false,\"configuration.bank.edit\":false,\"configuration.bank.destroy\":false,\"core.sidebar.group\":false,\"dashboard.index\":true,\"dashboard.update\":true,\"dashboard.reset\":true,\"invoice.invoices.index\":true,\"media.medias.index\":false,\"media.medias.create\":false,\"media.medias.edit\":false,\"media.medias.destroy\":false,\"media.folders.index\":false,\"media.folders.create\":false,\"media.folders.edit\":false,\"media.folders.destroy\":false,\"menu.menus.index\":false,\"menu.menus.create\":false,\"menu.menus.edit\":false,\"menu.menus.destroy\":false,\"menu.menuitems.index\":false,\"menu.menuitems.create\":false,\"menu.menuitems.edit\":false,\"menu.menuitems.destroy\":false,\"page.pages.index\":false,\"page.pages.create\":false,\"page.pages.edit\":false,\"page.pages.destroy\":false,\"pelanggan.pelanggans.index\":true,\"pelanggan.pelanggans.create\":true,\"pelanggan.pelanggans.edit\":true,\"pelanggan.salesorders.index\":true,\"pelanggan.salesorders.create\":true,\"pelanggan.salesorders.edit\":true,\"pelanggan.salesorders.destroy\":true,\"pelanggan.surveys.index\":true,\"pelanggan.surveys.create\":true,\"pelanggan.surveys.edit\":true,\"pelanggan.installations.index\":true,\"pelanggan.installations.create\":true,\"pelanggan.installations.edit\":true,\"pelanggan.activations.index\":true,\"pelanggan.activations.create\":true,\"pelanggan.activations.edit\":true,\"peralatan.alats.index\":false,\"peralatan.alats.create\":false,\"peralatan.alats.edit\":false,\"peralatan.alats.destroy\":false,\"peralatan.perangkats.index\":false,\"peralatan.perangkats.create\":false,\"peralatan.perangkats.edit\":false,\"peralatan.perangkats.destroy\":false,\"peralatan.barangs.index\":false,\"peralatan.barangs.create\":false,\"peralatan.barangs.edit\":false,\"peralatan.barangs.destroy\":false,\"presale.presales.index\":true,\"presale.presales.create\":false,\"presale.presales.edit\":false,\"presale.presales.delete\":false,\"presale.presales.confirm\":false,\"presale.endpoints.index\":true,\"presale.endpoints.create\":false,\"presale.endpoints.edit\":false,\"presale.endpoints.delete\":false,\"saldo.topups\":true,\"saldo.withdraws\":true,\"saldo.mutasi\":true,\"ticket.tickets.index\":true,\"ticket.tickets.create\":true,\"ticket.tickets.edit\":true,\"ticket.tickets.destroy\":true,\"user.users.index\":false,\"user.users.create\":true,\"user.users.edit\":true,\"user.users.destroy\":true,\"user.staff.index\":true,\"user.staff.create\":true,\"user.staff.edit\":true,\"user.staff.destroy\":true,\"user.roles.index\":false,\"user.roles.create\":false,\"user.roles.edit\":false,\"user.roles.destroy\":false,\"account.api-keys.index\":true,\"account.api-keys.create\":true,\"account.api-keys.destroy\":true,\"wilayah.wilayahs.index\":false,\"wilayah.wilayahs.create\":false,\"wilayah.wilayahs.update\":false,\"wilayah.wilayahs.destroy\":false,\"wilayah.pengajuans.index\":true,\"wilayah.pengajuans.create\":true,\"wilayah.pengajuans.update\":true,\"wilayah.pengajuans.destroy\":true,\"wilayah.pengajuans.confirm\":false,\"wilayah.pengajuans.approve\":false}', '2021-01-20 23:13:46', '2021-03-01 18:06:22');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
