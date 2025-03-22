-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-03-2025 a las 23:54:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `infy_crm`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(191) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(191) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(191) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` text DEFAULT NULL,
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'New Country created.', 'Honduras Country created.', 'App\\Models\\Country', 10, 'App\\Models\\User', 1, '[]', NULL, '2025-03-17 04:01:07', '2025-03-17 04:01:07'),
(2, 'New Expense created.', 'internet Expense created.', 'App\\Models\\Expense', 1, 'App\\Models\\User', 1, '[]', NULL, '2025-03-17 04:11:37', '2025-03-17 04:11:37'),
(3, 'Contract updated.', 'Contrato de Hospital Contract updated.', 'App\\Models\\Contract', 4, 'App\\Models\\User', 1, '[]', NULL, '2025-03-17 04:13:04', '2025-03-17 04:13:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `addresses`
--

CREATE TABLE `addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `owner_type` varchar(191) DEFAULT NULL,
  `street` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `zip` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `announcements`
--

CREATE TABLE `announcements` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(191) NOT NULL,
  `date` datetime NOT NULL,
  `message` text DEFAULT NULL,
  `show_to_clients` tinyint(1) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articles`
--

CREATE TABLE `articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(191) NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL,
  `internal_article` tinyint(1) DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `article_groups`
--

CREATE TABLE `article_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `group_name` varchar(191) NOT NULL,
  `color` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` varchar(191) NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `comments`
--

INSERT INTO `comments` (`id`, `description`, `owner_id`, `owner_type`, `added_by`, `created_at`, `updated_at`) VALUES
(1, 'El diseño está en revisión.', 1, 'App\\Models\\Project', 1, '2025-03-16 21:56:18', '2025-03-16 21:56:18'),
(2, 'La excavación está en progreso.', 2, 'App\\Models\\Project', 2, '2025-03-16 21:56:18', '2025-03-16 21:56:18'),
(3, 'La instalación eléctrica está casi lista.', 3, 'App\\Models\\Project', 3, '2025-03-16 21:56:18', '2025-03-16 21:56:18'),
(4, 'La fontanería está en revisión.', 4, 'App\\Models\\Project', 4, '2025-03-16 21:56:18', '2025-03-16 21:56:18'),
(5, 'La pintura está en proceso.', 5, 'App\\Models\\Project', 5, '2025-03-16 21:56:18', '2025-03-16 21:56:18'),
(6, 'Las ventanas están siendo instaladas.', 6, 'App\\Models\\Project', 6, '2025-03-16 21:56:18', '2025-03-16 21:56:18'),
(7, 'Los pisos están en proceso de colocación.', 7, 'App\\Models\\Project', 7, '2025-03-16 21:56:18', '2025-03-16 21:56:18'),
(8, 'Los ascensores están siendo instalados.', 8, 'App\\Models\\Project', 8, '2025-03-16 21:56:18', '2025-03-16 21:56:18'),
(9, 'El paisajismo está en progreso.', 9, 'App\\Models\\Project', 9, '2025-03-16 21:56:18', '2025-03-16 21:56:18'),
(10, 'La entrega final está en proceso.', 10, 'App\\Models\\Project', 10, '2025-03-16 21:56:18', '2025-03-16 21:56:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `position` varchar(191) DEFAULT NULL,
  `primary_contact` tinyint(1) NOT NULL DEFAULT 0,
  `send_welcome_email` tinyint(1) NOT NULL DEFAULT 0,
  `send_password_email` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contacts`
--

INSERT INTO `contacts` (`id`, `customer_id`, `user_id`, `position`, `primary_contact`, `send_welcome_email`, `send_password_email`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Gerente de Proyectos', 1, 0, 0, NULL, NULL),
(2, 2, NULL, 'Administradora', 1, 0, 0, NULL, NULL),
(3, 3, NULL, 'Ingeniero Residente', 1, 0, 0, NULL, NULL),
(4, 4, NULL, 'Directora de Construcción', 1, 0, 0, NULL, NULL),
(5, 5, NULL, 'Arquitecto', 1, 0, 0, NULL, NULL),
(6, 6, NULL, 'Jefa de Compras', 1, 0, 0, NULL, NULL),
(7, 7, NULL, 'Director Financiero', 1, 0, 0, NULL, NULL),
(8, 8, NULL, 'Coordinadora de Obras', 1, 0, 0, NULL, NULL),
(9, 9, NULL, 'Técnico en Construcción', 1, 0, 0, NULL, NULL),
(10, 10, NULL, 'Supervisora de Campo', 1, 0, 0, NULL, NULL),
(11, 1, NULL, 'Gerente General', 1, 1, 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(12, 2, NULL, 'Ingeniero Civil', 1, 1, 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(13, 3, NULL, 'Arquitecto', 1, 1, 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(14, 4, NULL, 'Supervisor de Obra', 1, 1, 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(15, 5, NULL, 'Gerente de Proyectos', 1, 1, 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(16, 6, NULL, 'Ingeniero Estructural', 1, 1, 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(17, 7, NULL, 'Diseñador', 1, 1, 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(18, 8, NULL, 'Coordinador de Obra', 1, 1, 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(19, 9, NULL, 'Especialista en Sostenibilidad', 1, 1, 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(20, 10, NULL, 'Gerente de Ventas', 1, 1, 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact_email_notifications`
--

CREATE TABLE `contact_email_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `contact_id` int(10) UNSIGNED NOT NULL,
  `email_notification_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contracts`
--

CREATE TABLE `contracts` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `contract_value` double DEFAULT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `contract_type_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contracts`
--

INSERT INTO `contracts` (`id`, `subject`, `description`, `start_date`, `end_date`, `contract_value`, `customer_id`, `contract_type_id`, `created_at`, `updated_at`) VALUES
(1, 'Contrato de Diseño', 'Contrato para el diseño de un edificio residencial.', '2023-01-01 00:00:00', '2023-12-31 00:00:00', 50000, 1, 1, '2025-03-16 21:51:38', '2025-03-16 21:51:38'),
(2, 'Contrato de Construcción', 'Contrato para la construcción de un centro comercial.', '2023-02-01 00:00:00', '2024-02-01 00:00:00', 100000, 2, 2, '2025-03-16 21:51:38', '2025-03-16 21:51:38'),
(3, 'Contrato de Puente', 'Contrato para la construcción de un puente.', '2023-03-01 00:00:00', '2023-11-30 00:00:00', 75000, 3, 3, '2025-03-16 21:51:38', '2025-03-16 21:51:38'),
(4, 'Contrato de Hospital', 'Contrato para la construcción de un hospital.', '2023-04-01 00:00:00', '2024-04-01 00:00:00', 150000, 4, 4, '2025-03-16 21:51:38', '2025-03-16 21:51:38'),
(5, 'Contrato de Escuela', 'Contrato para la construcción de una escuela.', '2023-05-01 00:00:00', '2023-12-31 00:00:00', 60000, 5, 5, '2025-03-16 21:51:38', '2025-03-16 21:51:38'),
(6, 'Contrato de Oficinas', 'Contrato para la construcción de un edificio de oficinas.', '2023-06-01 00:00:00', '2024-06-01 00:00:00', 120000, 6, 6, '2025-03-16 21:51:38', '2025-03-16 21:51:38'),
(7, 'Contrato de Parque Industrial', 'Contrato para la construcción de un parque industrial.', '2023-07-01 00:00:00', '2023-12-31 00:00:00', 90000, 7, 7, '2025-03-16 21:51:38', '2025-03-16 21:51:38'),
(8, 'Contrato de Hotel', 'Contrato para la construcción de un hotel.', '2023-08-01 00:00:00', '2024-08-01 00:00:00', 180000, 8, 8, '2025-03-16 21:51:38', '2025-03-16 21:51:38'),
(9, 'Contrato de Residencial Ecológico', 'Contrato para la construcción de un residencial ecológico.', '2023-09-01 00:00:00', '2023-12-31 00:00:00', 65000, 9, 9, '2025-03-16 21:51:38', '2025-03-16 21:51:38'),
(10, 'Contrato de Condominio', 'Contrato para la construcción de un condominio de lujo.', '2023-10-01 00:00:00', '2024-10-01 00:00:00', 200000, 10, 1, '2025-03-16 21:51:38', '2025-03-16 21:51:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contract_types`
--

CREATE TABLE `contract_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contract_types`
--

INSERT INTO `contract_types` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Contract under Seal', NULL, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'Express Contracts', NULL, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'Implied Contracts', NULL, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'Executed and Executory Contracts', NULL, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'Bilateral and Unilateral Contracts', NULL, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'Unconscionable Contracts', NULL, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(7, 'Adhesion Contracts', NULL, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(8, 'Aleatory Contracts', NULL, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(9, 'Void and Voidable Contracts', NULL, '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `countries`
--

CREATE TABLE `countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `countries`
--

INSERT INTO `countries` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'India', '2023-01-11 22:50:14', '2023-01-11 22:50:14'),
(2, 'Canada', '2023-01-11 22:50:14', '2023-01-11 22:50:14'),
(3, 'USA', '2023-01-11 22:50:14', '2023-01-11 22:50:14'),
(4, 'Germany', '2023-01-11 22:50:14', '2023-01-11 22:50:14'),
(5, 'Russia', '2023-01-11 22:50:14', '2023-01-11 22:50:14'),
(6, 'England', '2023-01-11 22:50:14', '2023-01-11 22:50:14'),
(7, 'UAE', '2023-01-11 22:50:14', '2023-01-11 22:50:14'),
(8, 'China', '2023-01-11 22:50:14', '2023-01-11 22:50:14'),
(9, 'Turkey', '2023-01-11 22:50:14', '2023-01-11 22:50:14'),
(10, 'Honduras', '2025-03-17 04:01:07', '2025-03-17 04:01:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credit_notes`
--

CREATE TABLE `credit_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `credit_note_number` varchar(191) NOT NULL,
  `credit_note_date` datetime NOT NULL,
  `currency` int(11) NOT NULL,
  `discount_type` int(11) DEFAULT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `admin_text` text DEFAULT NULL,
  `unit` int(11) NOT NULL,
  `client_note` text DEFAULT NULL,
  `term_conditions` text DEFAULT NULL,
  `sub_total` double DEFAULT NULL,
  `adjustment` varchar(191) NOT NULL DEFAULT '0',
  `total_amount` double DEFAULT NULL,
  `payment_status` int(11) DEFAULT NULL,
  `discount_symbol` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credit_note_addresses`
--

CREATE TABLE `credit_note_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `credit_note_id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL,
  `street` text DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `zip_code` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_name` varchar(191) NOT NULL,
  `vat_number` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `website` varchar(191) DEFAULT NULL,
  `currency` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `default_language` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `customers`
--

INSERT INTO `customers` (`id`, `company_name`, `vat_number`, `phone`, `website`, `currency`, `country`, `default_language`, `created_at`, `updated_at`) VALUES
(1, 'Constructora Alpha', NULL, '+50498765432', NULL, NULL, 'Honduras', NULL, '2025-03-16 21:33:34', NULL),
(2, 'Beta Construcciones', NULL, '+50487654321', NULL, NULL, 'Honduras', NULL, '2025-03-16 21:33:34', NULL),
(3, 'Gamma Edificaciones', NULL, '+50476543210', NULL, NULL, 'Honduras', NULL, '2025-03-16 21:33:34', NULL),
(4, 'Delta Proyectos', NULL, '+50465432109', NULL, NULL, 'Honduras', NULL, '2025-03-16 21:33:34', NULL),
(5, 'Epsilon Infraestructura', NULL, '+50454321098', NULL, NULL, 'Honduras', NULL, '2025-03-16 21:33:34', NULL),
(6, 'Zeta Construcciones', NULL, '+50443210987', NULL, NULL, 'Honduras', NULL, '2025-03-16 21:33:34', NULL),
(7, 'Omega Desarrollos', NULL, '+50432109876', NULL, NULL, 'Honduras', NULL, '2025-03-16 21:33:34', NULL),
(8, 'Lambda Obras', NULL, '+50421098765', NULL, NULL, 'Honduras', NULL, '2025-03-16 21:33:34', NULL),
(9, 'Sigma Ingeniería', NULL, '+50410987654', NULL, NULL, 'Honduras', NULL, '2025-03-16 21:33:34', NULL),
(10, 'Theta Estructuras', NULL, '+50409876543', NULL, NULL, 'Honduras', NULL, '2025-03-16 21:33:34', NULL),
(11, 'Constructora Martinez', '123456789', '+50498765432', 'www.martinez.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:42:34', '2025-03-16 21:42:34'),
(12, 'Ingeniería Civil S.A.', '987654321', '+50498765431', 'www.ingenieriacivil.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:42:34', '2025-03-16 21:42:34'),
(13, 'Construcciones Modernas', '456789123', '+50498765433', 'www.construccionesmodernas.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:42:34', '2025-03-16 21:42:34'),
(14, 'Edificaciones Rápidas', '789123456', '+50498765434', 'www.edificacionesrapidas.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:42:34', '2025-03-16 21:42:34'),
(15, 'Proyectos Urbanos', '321654987', '+50498765435', 'www.proyectosurbanos.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:42:34', '2025-03-16 21:42:34'),
(16, 'Construcciones Sólidas', '654987321', '+50498765436', 'www.construccionessolidas.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:42:34', '2025-03-16 21:42:34'),
(17, 'Diseños Arquitectónicos', '987321654', '+50498765437', 'www.disenosarquitectonicos.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:42:34', '2025-03-16 21:42:34'),
(18, 'Infraestructuras Globales', '123789456', '+50498765438', 'www.infraestructurasglobales.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:42:34', '2025-03-16 21:42:34'),
(19, 'Construcciones Ecológicas', '456123789', '+50498765439', 'www.construccionecologicas.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:42:34', '2025-03-16 21:42:34'),
(20, 'Proyectos Residenciales', '789456123', '+50498765430', 'www.proyectosresidenciales.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:42:34', '2025-03-16 21:42:34'),
(21, 'Constructora Martinez', '123456789', '+50498765432', 'www.martinez.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(22, 'Ingeniería Civil S.A.', '987654321', '+50498765431', 'www.ingenieriacivil.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(23, 'Construcciones Modernas', '456789123', '+50498765433', 'www.construccionesmodernas.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(24, 'Edificaciones Rápidas', '789123456', '+50498765434', 'www.edificacionesrapidas.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(25, 'Proyectos Urbanos', '321654987', '+50498765435', 'www.proyectosurbanos.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(26, 'Construcciones Sólidas', '654987321', '+50498765436', 'www.construccionessolidas.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(27, 'Diseños Arquitectónicos', '987321654', '+50498765437', 'www.disenosarquitectonicos.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(28, 'Infraestructuras Globales', '123789456', '+50498765438', 'www.infraestructurasglobales.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(29, 'Construcciones Ecológicas', '456123789', '+50498765439', 'www.construccionecologicas.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(30, 'Proyectos Residenciales', '789456123', '+50498765430', 'www.proyectosresidenciales.com', 'HNL', 'Honduras', 'es', '2025-03-16 21:47:29', '2025-03-16 21:47:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_groups`
--

CREATE TABLE `customer_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `customer_groups`
--

INSERT INTO `customer_groups` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'High Budget', 'This is High Budget', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'Wholesaler', 'This is Wholesaler', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'VIP', 'This is VIP', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'Low Budget', 'This is Low Budget', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'Wisoky-Robel', 'This is Wisoky-Robel', '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customer_to_customer_groups`
--

CREATE TABLE `customer_to_customer_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `customer_group_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Marketing Department', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'Operations Department', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'Finance Department', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'Sales Department', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'Human Resource Department', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'Purchase Department', '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email_notifications`
--

CREATE TABLE `email_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `template_name` varchar(191) NOT NULL,
  `template_type` int(11) NOT NULL,
  `subject` varchar(191) DEFAULT NULL,
  `from_name` varchar(191) NOT NULL,
  `send_plain_text` tinyint(1) NOT NULL DEFAULT 0,
  `disabled` tinyint(1) NOT NULL DEFAULT 0,
  `email_message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estimates`
--

CREATE TABLE `estimates` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL,
  `currency` int(11) NOT NULL,
  `estimate_number` varchar(191) NOT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `sales_agent_id` int(10) UNSIGNED DEFAULT NULL,
  `discount_type` int(11) DEFAULT NULL,
  `estimate_date` datetime NOT NULL,
  `estimate_expiry_date` datetime DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `unit` int(11) NOT NULL,
  `sub_total` double DEFAULT NULL,
  `adjustment` varchar(191) NOT NULL DEFAULT '0',
  `client_note` text DEFAULT NULL,
  `term_conditions` text DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `discount_symbol` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hsn_tax` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estimates`
--

INSERT INTO `estimates` (`id`, `title`, `customer_id`, `status`, `currency`, `estimate_number`, `reference`, `sales_agent_id`, `discount_type`, `estimate_date`, `estimate_expiry_date`, `admin_note`, `discount`, `unit`, `sub_total`, `adjustment`, `client_note`, `term_conditions`, `total_amount`, `discount_symbol`, `created_at`, `updated_at`, `hsn_tax`) VALUES
(1, 'Presupuesto 001', 1, 1, 1, 'EST-001', 'REF-001', 1, 1, '2023-01-01 00:00:00', '2023-01-15 00:00:00', 'Presupuesto para diseño de estructura.', 0, 1, 5000, '0', 'Gracias por su confianza.', 'Términos y condiciones aplicables.', 5000, 1, '2025-03-16 21:51:26', '2025-03-16 21:51:26', NULL),
(2, 'Presupuesto 002', 2, 1, 1, 'EST-002', 'REF-002', 2, 1, '2023-02-01 00:00:00', '2023-02-15 00:00:00', 'Presupuesto para excavación.', 0, 1, 8000, '0', 'Gracias por su confianza.', 'Términos y condiciones aplicables.', 8000, 1, '2025-03-16 21:51:26', '2025-03-16 21:51:26', NULL),
(3, 'Presupuesto 003', 3, 1, 1, 'EST-003', 'REF-003', 3, 1, '2023-03-01 00:00:00', '2023-03-15 00:00:00', 'Presupuesto para instalación eléctrica.', 0, 1, 12000, '0', 'Gracias por su confianza.', 'Términos y condiciones aplicables.', 12000, 1, '2025-03-16 21:51:26', '2025-03-16 21:51:26', NULL),
(4, 'Presupuesto 004', 4, 1, 1, 'EST-004', 'REF-004', 4, 1, '2023-04-01 00:00:00', '2023-04-15 00:00:00', 'Presupuesto para instalación de fontanería.', 0, 1, 9000, '0', 'Gracias por su confianza.', 'Términos y condiciones aplicables.', 9000, 1, '2025-03-16 21:51:26', '2025-03-16 21:51:26', NULL),
(5, 'Presupuesto 005', 5, 1, 1, 'EST-005', 'REF-005', 5, 1, '2023-05-01 00:00:00', '2023-05-15 00:00:00', 'Presupuesto para pintura.', 0, 1, 3000, '0', 'Gracias por su confianza.', 'Términos y condiciones aplicables.', 3000, 1, '2025-03-16 21:51:26', '2025-03-16 21:51:26', NULL),
(6, 'Presupuesto 006', 6, 1, 1, 'EST-006', 'REF-006', 6, 1, '2023-06-01 00:00:00', '2023-06-15 00:00:00', 'Presupuesto para instalación de ventanas.', 0, 1, 7000, '0', 'Gracias por su confianza.', 'Términos y condiciones aplicables.', 7000, 1, '2025-03-16 21:51:26', '2025-03-16 21:51:26', NULL),
(7, 'Presupuesto 007', 7, 1, 1, 'EST-007', 'REF-007', 7, 1, '2023-07-01 00:00:00', '2023-07-15 00:00:00', 'Presupuesto para colocación de pisos.', 0, 1, 10000, '0', 'Gracias por su confianza.', 'Términos y condiciones aplicables.', 10000, 1, '2025-03-16 21:51:26', '2025-03-16 21:51:26', NULL),
(8, 'Presupuesto 008', 8, 1, 1, 'EST-008', 'REF-008', 8, 1, '2023-08-01 00:00:00', '2023-08-15 00:00:00', 'Presupuesto para instalación de ascensores.', 0, 1, 15000, '0', 'Gracias por su confianza.', 'Términos y condiciones aplicables.', 15000, 1, '2025-03-16 21:51:26', '2025-03-16 21:51:26', NULL),
(9, 'Presupuesto 009', 9, 1, 1, 'EST-009', 'REF-009', 9, 1, '2023-09-01 00:00:00', '2023-09-15 00:00:00', 'Presupuesto para landscaping.', 0, 1, 4000, '0', 'Gracias por su confianza.', 'Términos y condiciones aplicables.', 4000, 1, '2025-03-16 21:51:26', '2025-03-16 21:51:26', NULL),
(10, 'Presupuesto 010', 10, 1, 1, 'EST-010', 'REF-010', 10, 1, '2023-10-01 00:00:00', '2023-10-15 00:00:00', 'Presupuesto para entrega final.', 0, 1, 20000, '0', 'Gracias por su confianza.', 'Términos y condiciones aplicables.', 20000, 1, '2025-03-16 21:51:26', '2025-03-16 21:51:26', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estimate_addresses`
--

CREATE TABLE `estimate_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `estimate_id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL,
  `street` text DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `zip_code` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expenses`
--

CREATE TABLE `expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `note` text DEFAULT NULL,
  `expense_category_id` int(10) UNSIGNED NOT NULL,
  `expense_date` datetime NOT NULL,
  `amount` double NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `currency` int(11) NOT NULL,
  `tax_applied` tinyint(1) NOT NULL DEFAULT 0,
  `tax_1_id` int(10) UNSIGNED DEFAULT NULL,
  `tax_2_id` int(10) UNSIGNED DEFAULT NULL,
  `tax_rate` double DEFAULT NULL,
  `payment_mode_id` int(10) UNSIGNED DEFAULT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `billable` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `expenses`
--

INSERT INTO `expenses` (`id`, `name`, `note`, `expense_category_id`, `expense_date`, `amount`, `customer_id`, `currency`, `tax_applied`, `tax_1_id`, `tax_2_id`, `tax_rate`, `payment_mode_id`, `reference`, `billable`, `created_at`, `updated_at`) VALUES
(1, 'internet', '<p>gasto de contrato de internet</p>', 12, '2025-03-31 00:00:00', 1470, NULL, 2, 1, 1, 1, 1500, 3, 'ach', 0, '2025-03-17 04:11:37', '2025-03-17 04:11:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Advertising', 'Advertising', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'Contractors', 'Contractors', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'Education and Training', 'Education and Training', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'Employee Benefits', 'Employee Benefits', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'Office Expenses & Postage', 'Office Expenses & Postage', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'Other Expenses', 'Other Expenses', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(7, 'Personal', 'Personal', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(8, 'Rent or Lease', 'Rent or Lease', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(9, 'Professional Services', 'Professional Services', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(10, 'Supplies', 'Supplies', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(11, 'Travel', 'Travel', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(12, 'Utilities', 'Utilities', '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) DEFAULT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `goals`
--

CREATE TABLE `goals` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `goal_type` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `achievement` double DEFAULT NULL,
  `is_notify` tinyint(1) DEFAULT NULL,
  `is_not_notify` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `goal_members`
--

CREATE TABLE `goal_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `goal_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `goal_types`
--

CREATE TABLE `goal_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE `invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `invoice_number` varchar(191) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `sales_agent_id` int(10) UNSIGNED DEFAULT NULL,
  `currency` int(11) NOT NULL,
  `discount_type` int(11) DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `admin_text` text DEFAULT NULL,
  `unit` int(11) NOT NULL,
  `client_note` text DEFAULT NULL,
  `term_conditions` text DEFAULT NULL,
  `sub_total` double DEFAULT NULL,
  `adjustment` varchar(191) NOT NULL DEFAULT '0',
  `total_amount` double DEFAULT NULL,
  `payment_status` int(11) DEFAULT NULL,
  `discount_symbol` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hsn_tax` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `invoices`
--

INSERT INTO `invoices` (`id`, `title`, `customer_id`, `invoice_number`, `invoice_date`, `due_date`, `sales_agent_id`, `currency`, `discount_type`, `discount`, `admin_text`, `unit`, `client_note`, `term_conditions`, `sub_total`, `adjustment`, `total_amount`, `payment_status`, `discount_symbol`, `created_at`, `updated_at`, `hsn_tax`) VALUES
(1, 'Factura 001', 1, 'INV-001', '2023-01-01', '2023-01-15', 1, 1, 1, 0, 'Pago por diseño de estructura.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 5000, '0', 5000, 1, 1, '2025-03-16 21:51:15', '2025-03-16 21:51:15', NULL),
(2, 'Factura 002', 2, 'INV-002', '2023-02-01', '2023-02-15', 2, 1, 1, 0, 'Pago por excavación.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 8000, '0', 8000, 1, 1, '2025-03-16 21:51:15', '2025-03-16 21:51:15', NULL),
(3, 'Factura 003', 3, 'INV-003', '2023-03-01', '2023-03-15', 3, 1, 1, 0, 'Pago por instalación eléctrica.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 12000, '0', 12000, 1, 1, '2025-03-16 21:51:15', '2025-03-16 21:51:15', NULL),
(4, 'Factura 004', 4, 'INV-004', '2023-04-01', '2023-04-15', 4, 1, 1, 0, 'Pago por instalación de fontanería.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 9000, '0', 9000, 1, 1, '2025-03-16 21:51:15', '2025-03-16 21:51:15', NULL),
(5, 'Factura 005', 5, 'INV-005', '2023-05-01', '2023-05-15', 5, 1, 1, 0, 'Pago por pintura.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 3000, '0', 3000, 1, 1, '2025-03-16 21:51:15', '2025-03-16 21:51:15', NULL),
(6, 'Factura 006', 6, 'INV-006', '2023-06-01', '2023-06-15', 6, 1, 1, 0, 'Pago por instalación de ventanas.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 7000, '0', 7000, 1, 1, '2025-03-16 21:51:15', '2025-03-16 21:51:15', NULL),
(7, 'Factura 007', 7, 'INV-007', '2023-07-01', '2023-07-15', 7, 1, 1, 0, 'Pago por colocación de pisos.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 10000, '0', 10000, 1, 1, '2025-03-16 21:51:15', '2025-03-16 21:51:15', NULL),
(8, 'Factura 008', 8, 'INV-008', '2023-08-01', '2023-08-15', 8, 1, 1, 0, 'Pago por instalación de ascensores.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 15000, '0', 15000, 1, 1, '2025-03-16 21:51:15', '2025-03-16 21:51:15', NULL),
(9, 'Factura 009', 9, 'INV-009', '2023-09-01', '2023-09-15', 9, 1, 1, 0, 'Pago por landscaping.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 4000, '0', 4000, 1, 1, '2025-03-16 21:51:15', '2025-03-16 21:51:15', NULL),
(10, 'Factura 010', 10, 'INV-010', '2023-10-01', '2023-10-15', 10, 1, 1, 0, 'Pago por entrega final.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 20000, '0', 20000, 1, 1, '2025-03-16 21:51:15', '2025-03-16 21:51:15', NULL),
(11, 'Factura 011 - Pagada', 1, 'INV-011', '2023-11-01', '2023-11-15', 1, 1, 1, 0, 'Pago por diseño de estructura.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 5000, '0', 5000, 1, 1, '2025-03-16 22:02:57', '2025-03-16 22:02:57', NULL),
(12, 'Factura 012 - Pagada', 2, 'INV-012', '2023-11-02', '2023-11-16', 2, 1, 1, 0, 'Pago por excavación.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 8000, '0', 8000, 1, 1, '2025-03-16 22:02:57', '2025-03-16 22:02:57', NULL),
(13, 'Factura 013 - Pagada', 3, 'INV-013', '2023-11-03', '2023-11-17', 3, 1, 1, 0, 'Pago por instalación eléctrica.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 12000, '0', 12000, 1, 1, '2025-03-16 22:02:57', '2025-03-16 22:02:57', NULL),
(14, 'Factura 014 - Pagada', 4, 'INV-014', '2023-11-04', '2023-11-18', 4, 1, 1, 0, 'Pago por instalación de fontanería.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 9000, '0', 9000, 1, 1, '2025-03-16 22:02:57', '2025-03-16 22:02:57', NULL),
(15, 'Factura 015 - Pagada', 5, 'INV-015', '2023-11-05', '2023-11-19', 5, 1, 1, 0, 'Pago por pintura.', 1, 'Gracias por su pago.', 'Términos y condiciones aplicables.', 3000, '0', 3000, 1, 1, '2025-03-16 22:02:57', '2025-03-16 22:02:57', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_addresses`
--

CREATE TABLE `invoice_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL,
  `street` text DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `zip_code` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `invoice_addresses`
--

INSERT INTO `invoice_addresses` (`id`, `invoice_id`, `type`, `street`, `city`, `state`, `zip_code`, `country`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Calle Principal 123', 'Tegucigalpa', 'Francisco Morazán', '11101', 'Honduras', '2025-03-16 21:53:53', '2025-03-16 21:53:53'),
(2, 2, 1, 'Avenida Central 456', 'San Pedro Sula', 'Cortés', '21102', 'Honduras', '2025-03-16 21:53:53', '2025-03-16 21:53:53'),
(3, 3, 1, 'Boulevard Los Próceres 789', 'La Ceiba', 'Atlántida', '31103', 'Honduras', '2025-03-16 21:53:53', '2025-03-16 21:53:53'),
(4, 4, 1, 'Calle Los Pinos 101', 'Comayagua', 'Comayagua', '41104', 'Honduras', '2025-03-16 21:53:53', '2025-03-16 21:53:53'),
(5, 5, 1, 'Avenida La Paz 202', 'Choluteca', 'Choluteca', '51105', 'Honduras', '2025-03-16 21:53:53', '2025-03-16 21:53:53'),
(6, 6, 1, 'Calle El Roble 303', 'Santa Rosa de Copán', 'Copán', '61106', 'Honduras', '2025-03-16 21:53:53', '2025-03-16 21:53:53'),
(7, 7, 1, 'Boulevard Las Flores 404', 'El Progreso', 'Yoro', '71107', 'Honduras', '2025-03-16 21:53:53', '2025-03-16 21:53:53'),
(8, 8, 1, 'Avenida Las Palmas 505', 'Tela', 'Atlántida', '81108', 'Honduras', '2025-03-16 21:53:53', '2025-03-16 21:53:53'),
(9, 9, 1, 'Calle Los Almendros 606', 'Danlí', 'El Paraíso', '91109', 'Honduras', '2025-03-16 21:53:53', '2025-03-16 21:53:53'),
(10, 10, 1, 'Boulevard Los Laureles 707', 'Juticalpa', 'Olancho', '10110', 'Honduras', '2025-03-16 21:53:53', '2025-03-16 21:53:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_payment_modes`
--

CREATE TABLE `invoice_payment_modes` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_mode_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `invoice_payment_modes`
--

INSERT INTO `invoice_payment_modes` (`id`, `payment_mode_id`, `invoice_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-03-16 21:54:10', '2025-03-16 21:54:10'),
(2, 2, 2, '2025-03-16 21:54:10', '2025-03-16 21:54:10'),
(3, 3, 3, '2025-03-16 21:54:10', '2025-03-16 21:54:10'),
(4, 1, 4, '2025-03-16 21:54:10', '2025-03-16 21:54:10'),
(5, 2, 5, '2025-03-16 21:54:10', '2025-03-16 21:54:10'),
(6, 3, 6, '2025-03-16 21:54:10', '2025-03-16 21:54:10'),
(7, 1, 7, '2025-03-16 21:54:10', '2025-03-16 21:54:10'),
(8, 2, 8, '2025-03-16 21:54:10', '2025-03-16 21:54:10'),
(9, 3, 9, '2025-03-16 21:54:10', '2025-03-16 21:54:10'),
(10, 1, 10, '2025-03-16 21:54:10', '2025-03-16 21:54:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `rate` double NOT NULL,
  `tax_1_id` int(11) DEFAULT NULL,
  `tax_2_id` int(11) DEFAULT NULL,
  `item_group_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_groups`
--

CREATE TABLE `item_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `item_groups`
--

INSERT INTO `item_groups` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Consultant Services', 'Pain find that follow. I feel more than that, but that\'s dishonor, with a grief and a lot. It is extremely quite right that that.', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'LCD TV', 'Born to those who discovered it. Present suffering is nothing more than that. It is the pleasure of him who is willing, or.', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'MacBook Pro', 'The distinction, however, is easier, to the accepted indeed. Seeks to provide for them.', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'Marketing Services', 'Thus was born and will never interfere either. And to explain how he desires.', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'SEO Optimization', 'He who does not, therefore, the body itself in. Or they are rejecting it.', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'USB Stick', 'All but one reason. We regard any who are in a assumenda that he would consent. And it is because of it.', '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `languages`
--

INSERT INTO `languages` (`id`, `name`, `description`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'en', 'English', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'es', 'Spanish', 0, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'fr', 'French', 0, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'de', 'German', 0, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'ru', 'Russian', 0, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'pt', 'Portuguese', 0, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(7, 'ar', 'Arabic', 0, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(8, 'zh', 'Chinese', 0, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(9, 'tr', 'Turkish', 0, '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `leads`
--

CREATE TABLE `leads` (
  `id` int(10) UNSIGNED NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL,
  `source_id` int(10) UNSIGNED NOT NULL,
  `assign_to` int(10) UNSIGNED DEFAULT NULL,
  `company_name` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `position` varchar(191) DEFAULT NULL,
  `website` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `estimate_budget` double DEFAULT NULL,
  `default_language` varchar(191) DEFAULT NULL,
  `public` int(11) DEFAULT NULL,
  `lead_convert_customer` tinyint(1) NOT NULL DEFAULT 0,
  `lead_convert_date` date DEFAULT NULL,
  `contacted_today` int(11) DEFAULT NULL,
  `date_contacted` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lead_sources`
--

CREATE TABLE `lead_sources` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `lead_sources`
--

INSERT INTO `lead_sources` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Google AdWords', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'Other Search Engines', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'Google (organic)', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'Social Media (Facebook, Twitter etc)', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'Cold Calling/Telemarketing', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'Advertising', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(7, 'Custom Referral', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(8, 'Expo/Seminar', '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lead_statuses`
--

CREATE TABLE `lead_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `color` varchar(191) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `lead_statuses`
--

INSERT INTO `lead_statuses` (`id`, `name`, `color`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Attempted', '#ff2d42', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'Not Attempted', '#84c529', 2, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'Contacted', '#0000ff', 3, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'New Opportunity', '#c0c0c0', 4, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'Additional Contact', '#03a9f4', 5, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'In Progress', '#9C27B0', 5, '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  `collection_name` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `file_name` varchar(191) NOT NULL,
  `mime_type` varchar(191) DEFAULT NULL,
  `disk` varchar(191) NOT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `manipulations` text NOT NULL,
  `custom_properties` text NOT NULL,
  `responsive_images` text NOT NULL,
  `order_column` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `conversions_disk` varchar(191) DEFAULT NULL,
  `uuid` char(36) DEFAULT NULL,
  `generated_conversions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_05_03_000001_create_customer_columns', 1),
(4, '2019_05_03_000002_create_subscriptions_table', 1),
(5, '2019_05_03_000003_create_subscription_items_table', 1),
(6, '2019_08_19_000000_create_failed_jobs_table', 1),
(7, '2020_03_30_113645_create_languages_table', 1),
(8, '2020_03_31_072201_create_tags_table', 1),
(9, '2020_03_31_101748_create_customer_groups_table', 1),
(10, '2020_04_02_120049_create_permission_tables', 1),
(11, '2020_04_03_042555_create_article_groups_table', 1),
(12, '2020_04_03_045459_create_predefined_replies_table', 1),
(13, '2020_04_03_063710_create_customers_table', 1),
(14, '2020_04_03_064745_create_address_table', 1),
(15, '2020_04_03_080033_create_ticket_priorities_table', 1),
(16, '2020_04_03_091117_create_expense_categories_table', 1),
(17, '2020_04_03_113351_create_customer_to_customer_groups_table', 1),
(18, '2020_04_03_123515_create_services_table', 1),
(19, '2020_04_04_035019_create_ticket_statuses_table', 1),
(20, '2020_04_06_040305_create_lead_statuses_table', 1),
(21, '2020_04_06_054337_create_lead_sources_table', 1),
(22, '2020_04_06_055647_create_item_groups_table', 1),
(23, '2020_04_06_064803_create_tax_rates_table', 1),
(24, '2020_04_06_065009_create_announcements_table', 1),
(25, '2020_04_06_083033_create_articles_table', 1),
(26, '2020_04_06_095554_create_payment_modes_table', 1),
(27, '2020_04_07_042816_create_items_table', 1),
(28, '2020_04_07_055247_create_contacts_table', 1),
(29, '2020_04_08_042058_create_contract_types_table', 1),
(30, '2020_04_08_060610_create_departments_table', 1),
(31, '2020_04_08_061359_create_tickets_table', 1),
(32, '2020_04_08_094756_add_type_column_into_permissions_table', 1),
(33, '2020_04_08_113224_create_invoices_table', 1),
(34, '2020_04_09_052358_create_invoice_addresses_table', 1),
(35, '2020_04_09_084032_create_taggables_table', 1),
(36, '2020_04_09_095706_create_invoice_payment_modes_table', 1),
(37, '2020_04_09_114622_create_sales_items_table', 1),
(38, '2020_04_10_043112_create_media_table', 1),
(39, '2020_04_10_070725_create_email_notifications_table', 1),
(40, '2020_04_10_103613_create_user_departments_table', 1),
(41, '2020_04_14_063726_create_notes_table', 1),
(42, '2020_04_14_065429_create_contact_email_notifications_table', 1),
(43, '2020_04_15_092420_create_reminders_table', 1),
(44, '2020_04_15_112744_create_sales_items_taxes_table', 1),
(45, '2020_04_16_054536_create_projects_table', 1),
(46, '2020_04_16_075039_create_sales_taxes_table', 1),
(47, '2020_04_17_101231_create_project_members_table', 1),
(48, '2020_04_20_051641_create_expenses_table', 1),
(49, '2020_04_20_082756_create_comments_table', 1),
(50, '2020_04_20_090457_add_goal_types_table', 1),
(51, '2020_04_20_111756_add_goals_table', 1),
(52, '2020_04_20_124358_create_leads_table', 1),
(53, '2020_04_21_114258_add_contracts_table', 1),
(54, '2020_04_22_082049_create_payments_table', 1),
(55, '2020_04_22_085449_add_settings_table', 1),
(56, '2020_04_23_060014_create_credit_notes_table', 1),
(57, '2020_04_23_060243_create_credit_note_addresses_table', 1),
(58, '2020_04_24_054022_create_email_templates_table', 1),
(59, '2020_04_27_045332_create_proposals_table', 1),
(60, '2020_04_27_061619_create_estimates_table', 1),
(61, '2020_04_27_100038_create_estimate_addresses_table', 1),
(62, '2020_04_28_122023_create_proposal_addresses_table', 1),
(63, '2020_07_06_045925_add_new_fields_into_users_table', 1),
(64, '2020_07_14_134831_create_tasks_table', 1),
(65, '2020_07_31_095218_add_image_field_in_articles_table', 1),
(66, '2020_08_24_052215_create_project_contacts_table', 1),
(67, '2020_09_02_130829_create_goal_members_table', 1),
(68, '2020_12_10_062217_add_status_field_to_announcements_table', 1),
(69, '2020_12_10_114422_add_status_filed_to_reminders_table', 1),
(70, '2020_12_19_061159_add_country_to_leads_table', 1),
(71, '2020_12_25_074509_drop_predefine_relation_on_tickets_table', 1),
(72, '2020_12_25_093030_drop_department_relation_on_tickets_table', 1),
(73, '2020_12_25_111608_drop_foreign_key_to_invoices_table', 1),
(74, '2020_12_25_111700_drop_foreign_key_to_estimates_table', 1),
(75, '2020_12_26_045434_drop_member_id_foreign_key_on_tasks_table', 1),
(76, '2021_01_04_090933_add_stripe_id_and_meta_fields_in_payments_table', 1),
(77, '2021_01_19_124232_make_start_date_nullable_in_tasks_table', 1),
(78, '2021_01_20_050318_make_priority_and_service_field_nullable_in_tickets_table', 1),
(79, '2021_03_10_054614_create_activity_log_table', 1),
(80, '2021_05_10_112220_create_notifications_table', 1),
(81, '2021_07_05_121647_change_customer_foreign_key_table_name_in_expenses_table', 1),
(82, '2021_07_22_082312_create_countries_table', 1),
(83, '2021_09_03_000000_add_uuid_to_failed_jobs_table', 1),
(84, '2021_09_11_113710_add_conversions_disk_column_in_media_table', 1),
(85, '2022_04_27_062115_add_is_admin_field_in_users_table', 1),
(86, '2022_05_24_073300_change_properties_field_type_in_activity_log_table', 1),
(87, '2022_07_27_055736_add_hsn_tax_field_in_invoices_and_proposals_and_estimates', 1),
(88, '2022_09_13_045308_run_default_country_code_seeder', 1),
(89, '2022_09_13_124102_add_is_default_field_in_languages_table', 1),
(90, '2022_09_19_055209_run_default_languages_seeder_table', 1),
(91, '2022_10_06_124122_run_set_default_language_seeder', 1),
(92, '2022_11_08_115827_run_ticket_permission_in_seeder', 1),
(93, '2022_11_14_092357_create_ticket_replies_table', 1),
(94, '2022_12_19_120935_add_batch_uuid_column_to_activity_log_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 1),
(5, 'App\\Models\\User', 1),
(6, 'App\\Models\\User', 1),
(7, 'App\\Models\\User', 1),
(8, 'App\\Models\\User', 1),
(9, 'App\\Models\\User', 1),
(10, 'App\\Models\\User', 1),
(11, 'App\\Models\\User', 1),
(12, 'App\\Models\\User', 1),
(13, 'App\\Models\\User', 1),
(14, 'App\\Models\\User', 1),
(15, 'App\\Models\\User', 1),
(16, 'App\\Models\\User', 1),
(17, 'App\\Models\\User', 1),
(18, 'App\\Models\\User', 1),
(19, 'App\\Models\\User', 1),
(20, 'App\\Models\\User', 1),
(21, 'App\\Models\\User', 1),
(22, 'App\\Models\\User', 1),
(23, 'App\\Models\\User', 1),
(24, 'App\\Models\\User', 1),
(25, 'App\\Models\\User', 1),
(26, 'App\\Models\\User', 1),
(27, 'App\\Models\\User', 1),
(28, 'App\\Models\\User', 1),
(29, 'App\\Models\\User', 1),
(30, 'App\\Models\\User', 1),
(31, 'App\\Models\\User', 1),
(32, 'App\\Models\\User', 1),
(33, 'App\\Models\\User', 1),
(34, 'App\\Models\\User', 1),
(35, 'App\\Models\\User', 1),
(36, 'App\\Models\\User', 1),
(37, 'App\\Models\\User', 1),
(38, 'App\\Models\\User', 1),
(39, 'App\\Models\\User', 1),
(40, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notes`
--

CREATE TABLE `notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` varchar(191) NOT NULL,
  `note` text DEFAULT NULL,
  `added_by` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` varchar(191) NOT NULL,
  `amount_received` double NOT NULL,
  `payment_date` datetime NOT NULL,
  `payment_mode` int(10) UNSIGNED NOT NULL,
  `transaction_id` varchar(191) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `send_mail_to_customer_contacts` tinyint(1) DEFAULT NULL,
  `stripe_id` varchar(191) DEFAULT NULL,
  `meta` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `payments`
--

INSERT INTO `payments` (`id`, `owner_id`, `owner_type`, `amount_received`, `payment_date`, `payment_mode`, `transaction_id`, `note`, `send_mail_to_customer_contacts`, `stripe_id`, `meta`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Invoice', 5000, '2023-11-15 00:00:00', 1, 'TX001', 'Pago completo por diseño de estructura.', 1, NULL, NULL, '2025-03-16 22:06:38', '2025-03-16 22:06:38'),
(2, 2, 'App\\Models\\Invoice', 8000, '2023-11-16 00:00:00', 2, 'TX002', 'Pago completo por excavación.', 1, NULL, NULL, '2025-03-16 22:06:38', '2025-03-16 22:06:38'),
(3, 3, 'App\\Models\\Invoice', 12000, '2023-11-17 00:00:00', 3, 'TX003', 'Pago completo por instalación eléctrica.', 1, NULL, NULL, '2025-03-16 22:06:38', '2025-03-16 22:06:38'),
(4, 4, 'App\\Models\\Invoice', 9000, '2023-11-18 00:00:00', 1, 'TX004', 'Pago completo por instalación de fontanería.', 1, NULL, NULL, '2025-03-16 22:06:38', '2025-03-16 22:06:38'),
(5, 5, 'App\\Models\\Invoice', 3000, '2023-11-19 00:00:00', 2, 'TX005', 'Pago completo por pintura.', 1, NULL, NULL, '2025-03-16 22:06:38', '2025-03-16 22:06:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_modes`
--

CREATE TABLE `payment_modes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `payment_modes`
--

INSERT INTO `payment_modes` (`id`, `name`, `description`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Bank', NULL, 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'Gold', NULL, 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'Stripe', NULL, 1, '2023-01-11 22:50:14', '2023-01-11 22:50:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `display_name` varchar(191) DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `type`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'contact_tickets', 'Contact Tickets', NULL, 'Contacts', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'manage_customer_groups', 'Manage Customer Groups', NULL, 'Customers', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'manage_customers', 'Manage Customers', NULL, 'Customers', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'manage_staff_member', 'Manage Staff Member', NULL, 'Members', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'manage_article_groups', 'Manage Article Groups', NULL, 'Articles', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'manage_articles', 'Manage Articles', NULL, 'Articles', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(7, 'manage_tags', 'Manage Tags', NULL, 'Tags', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(8, 'manage_leads', 'Manage Leads', NULL, 'Leads', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(9, 'manage_lead_status', 'Manage Lead Status', NULL, 'Leads', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(10, 'manage_tasks', 'Manage Tasks', NULL, 'Tasks', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(11, 'manage_ticket_priority', 'Manage Ticket Priority', NULL, 'Tickets', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(12, 'manage_ticket_statuses', 'Manage Ticket Statuses', NULL, 'Tickets', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(13, 'manage_tickets', 'Manage Tickets', NULL, 'Tickets', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(14, 'manage_invoices', 'Manage Invoices', NULL, 'Invoices', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(15, 'manage_payments', 'Manage Payments', NULL, 'Payments', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(16, 'manage_payment_mode', 'Manage Payment Mode', NULL, 'Payments', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(17, 'manage_credit_notes', 'Manage Credit Note', NULL, 'Credit Note', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(18, 'manage_proposals', 'Manage Proposals', NULL, 'Proposals', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(19, 'manage_estimates', 'Manage Estimates', NULL, 'Estimates', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(20, 'manage_departments', 'Manage Departments', NULL, 'Departments', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(21, 'manage_predefined_replies', 'Manage Predefined Replies', NULL, 'Predefined Replies', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(22, 'manage_expense_category', 'Manage Expense Category', NULL, 'Expenses', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(23, 'manage_expenses', 'Manage Expenses', NULL, 'Expenses', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(24, 'manage_services', 'Manage Services', NULL, 'Services', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(25, 'manage_items', 'Manage Items', NULL, 'Items', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(26, 'manage_items_groups', 'Manage Items Groups', NULL, 'Items', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(27, 'manage_tax_rates', 'Manage Tax Rates', NULL, 'TaxRate', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(28, 'manage_announcements', 'Manage Announcements', NULL, 'Announcements', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(29, 'manage_calenders', 'Manage Calenders', NULL, 'Calenders', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(30, 'manage_lead_sources', 'Manage Lead Sources', NULL, 'Leads', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(31, 'manage_contracts_types', 'Manage Contract Types', NULL, 'Contracts', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(32, 'manage_contracts', 'Manage Contracts', NULL, 'Contracts', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(33, 'manage_projects', 'Manage Projects', NULL, 'Projects', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(34, 'manage_goals', 'Manage Goals', NULL, 'Goals', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(35, 'manage_settings', 'Manage Settings', NULL, 'Settings', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(36, 'contact_projects', 'Contact Projects', NULL, 'Contacts', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(37, 'contact_invoices', 'Contact Invoices', NULL, 'Contacts', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(38, 'contact_proposals', 'Contact Proposals', NULL, 'Contacts', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(39, 'contact_contracts', 'Contact Contracts', NULL, 'Contacts', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(40, 'contact_estimates', 'Contact Estimates', NULL, 'Contacts', 'web', '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `predefined_replies`
--

CREATE TABLE `predefined_replies` (
  `id` int(10) UNSIGNED NOT NULL,
  `reply_name` varchar(191) NOT NULL,
  `body` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `projects`
--

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `project_name` varchar(191) NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `calculate_progress_through_tasks` tinyint(1) DEFAULT NULL,
  `progress` varchar(191) DEFAULT NULL,
  `billing_type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `estimated_hours` varchar(191) DEFAULT NULL,
  `start_date` date NOT NULL,
  `deadline` date NOT NULL,
  `description` text DEFAULT NULL,
  `send_email` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `customer_id`, `calculate_progress_through_tasks`, `progress`, `billing_type`, `status`, `estimated_hours`, `start_date`, `deadline`, `description`, `send_email`, `created_at`, `updated_at`) VALUES
(1, 'Edificio Residencial A', 1, 1, '50', 1, 1, '1000', '2023-01-01', '2023-12-31', 'Construcción de un edificio residencial de 10 pisos.', 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(2, 'Centro Comercial B', 2, 1, '30', 2, 2, '2000', '2023-02-01', '2024-02-01', 'Construcción de un centro comercial con 50 locales.', 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(3, 'Puente C', 3, 1, '70', 1, 1, '1500', '2023-03-01', '2023-11-30', 'Construcción de un puente de 200 metros.', 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(4, 'Hospital D', 4, 1, '40', 2, 2, '3000', '2023-04-01', '2024-04-01', 'Construcción de un hospital con 100 camas.', 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(5, 'Escuela E', 5, 1, '60', 1, 1, '1200', '2023-05-01', '2023-12-31', 'Construcción de una escuela con 20 aulas.', 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(6, 'Edificio de Oficinas F', 6, 1, '80', 2, 2, '2500', '2023-06-01', '2024-06-01', 'Construcción de un edificio de oficinas de 15 pisos.', 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(7, 'Parque Industrial G', 7, 1, '90', 1, 1, '1800', '2023-07-01', '2023-12-31', 'Construcción de un parque industrial con 10 naves.', 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(8, 'Hotel H', 8, 1, '20', 2, 2, '2200', '2023-08-01', '2024-08-01', 'Construcción de un hotel de 5 estrellas.', 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(9, 'Residencial Ecológico I', 9, 1, '50', 1, 1, '1300', '2023-09-01', '2023-12-31', 'Construcción de un residencial ecológico.', 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29'),
(10, 'Condominio J', 10, 1, '70', 2, 2, '2400', '2023-10-01', '2024-10-01', 'Construcción de un condominio de lujo.', 1, '2025-03-16 21:47:29', '2025-03-16 21:47:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_contacts`
--

CREATE TABLE `project_contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `contact_id` int(10) UNSIGNED NOT NULL,
  `project_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_members`
--

CREATE TABLE `project_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` varchar(191) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `project_members`
--

INSERT INTO `project_members` (`id`, `owner_id`, `owner_type`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Project', 1, '2025-03-16 21:55:16', '2025-03-16 21:55:16'),
(2, 2, 'App\\Models\\Project', 2, '2025-03-16 21:55:16', '2025-03-16 21:55:16'),
(3, 3, 'App\\Models\\Project', 3, '2025-03-16 21:55:16', '2025-03-16 21:55:16'),
(4, 4, 'App\\Models\\Project', 4, '2025-03-16 21:55:16', '2025-03-16 21:55:16'),
(5, 5, 'App\\Models\\Project', 5, '2025-03-16 21:55:16', '2025-03-16 21:55:16'),
(6, 6, 'App\\Models\\Project', 6, '2025-03-16 21:55:16', '2025-03-16 21:55:16'),
(7, 7, 'App\\Models\\Project', 7, '2025-03-16 21:55:16', '2025-03-16 21:55:16'),
(8, 8, 'App\\Models\\Project', 8, '2025-03-16 21:55:16', '2025-03-16 21:55:16'),
(9, 9, 'App\\Models\\Project', 9, '2025-03-16 21:55:16', '2025-03-16 21:55:16'),
(10, 10, 'App\\Models\\Project', 10, '2025-03-16 21:55:16', '2025-03-16 21:55:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proposals`
--

CREATE TABLE `proposals` (
  `id` int(10) UNSIGNED NOT NULL,
  `proposal_number` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `related_to` varchar(191) DEFAULT NULL,
  `date` datetime NOT NULL,
  `open_till` datetime DEFAULT NULL,
  `currency` int(11) NOT NULL,
  `discount_type` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `assigned_user_id` int(11) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `unit` int(11) NOT NULL,
  `sub_total` double DEFAULT NULL,
  `adjustment` varchar(191) NOT NULL DEFAULT '0',
  `total_amount` double DEFAULT NULL,
  `payment_status` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `owner_type` varchar(191) DEFAULT NULL,
  `discount_symbol` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hsn_tax` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proposal_addresses`
--

CREATE TABLE `proposal_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `proposal_id` int(10) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL,
  `street` text DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `zip_code` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reminders`
--

CREATE TABLE `reminders` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` varchar(191) NOT NULL,
  `notified_date` datetime NOT NULL,
  `reminder_to` int(10) UNSIGNED NOT NULL,
  `added_by` int(11) NOT NULL,
  `description` text NOT NULL,
  `is_notified` tinyint(1) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reminders`
--

INSERT INTO `reminders` (`id`, `owner_id`, `owner_type`, `notified_date`, `reminder_to`, `added_by`, `description`, `is_notified`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Ticket', '2023-01-01 00:00:00', 1, 1, 'Recordatorio para revisar el diseño.', 1, 1, '2025-03-16 21:55:07', '2025-03-16 21:55:07'),
(2, 2, 'App\\Models\\Ticket', '2023-02-01 00:00:00', 2, 2, 'Recordatorio para revisar la excavación.', 1, 1, '2025-03-16 21:55:07', '2025-03-16 21:55:07'),
(3, 3, 'App\\Models\\Ticket', '2023-03-01 00:00:00', 3, 3, 'Recordatorio para revisar la instalación eléctrica.', 1, 1, '2025-03-16 21:55:07', '2025-03-16 21:55:07'),
(4, 4, 'App\\Models\\Ticket', '2023-04-01 00:00:00', 4, 4, 'Recordatorio para revisar la fontanería.', 1, 1, '2025-03-16 21:55:07', '2025-03-16 21:55:07'),
(5, 5, 'App\\Models\\Ticket', '2023-05-01 00:00:00', 5, 5, 'Recordatorio para revisar la pintura.', 1, 1, '2025-03-16 21:55:07', '2025-03-16 21:55:07'),
(6, 6, 'App\\Models\\Ticket', '2023-06-01 00:00:00', 6, 6, 'Recordatorio para revisar las ventanas.', 1, 1, '2025-03-16 21:55:07', '2025-03-16 21:55:07'),
(7, 7, 'App\\Models\\Ticket', '2023-07-01 00:00:00', 7, 7, 'Recordatorio para revisar los pisos.', 1, 1, '2025-03-16 21:55:07', '2025-03-16 21:55:07'),
(8, 8, 'App\\Models\\Ticket', '2023-08-01 00:00:00', 8, 8, 'Recordatorio para revisar el ascensor.', 1, 1, '2025-03-16 21:55:07', '2025-03-16 21:55:07'),
(9, 9, 'App\\Models\\Ticket', '2023-09-01 00:00:00', 9, 9, 'Recordatorio para revisar el paisajismo.', 1, 1, '2025-03-16 21:55:07', '2025-03-16 21:55:07'),
(10, 10, 'App\\Models\\Ticket', '2023-10-01 00:00:00', 10, 10, 'Recordatorio para revisar la entrega final.', 1, 1, '2025-03-16 21:55:07', '2025-03-16 21:55:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `display_name` varchar(191) DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `guard_name` varchar(191) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `guard_name`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Admin', NULL, 'web', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'staff_member', 'Staff Member', NULL, 'web', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'client', 'Client', NULL, 'web', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales_items`
--

CREATE TABLE `sales_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` varchar(191) NOT NULL,
  `item` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `rate` double NOT NULL,
  `total` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sales_items`
--

INSERT INTO `sales_items` (`id`, `owner_id`, `owner_type`, `item`, `description`, `quantity`, `rate`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Invoice', 'Diseño de Estructura', 'Diseño de estructura para edificio residencial.', 1, 5000, 5000, '2025-03-16 21:54:23', '2025-03-16 21:54:23'),
(2, 2, 'App\\Models\\Invoice', 'Excavación', 'Excavación para cimientos.', 1, 8000, 8000, '2025-03-16 21:54:23', '2025-03-16 21:54:23'),
(3, 3, 'App\\Models\\Invoice', 'Instalación Eléctrica', 'Instalación eléctrica completa.', 1, 12000, 12000, '2025-03-16 21:54:23', '2025-03-16 21:54:23'),
(4, 4, 'App\\Models\\Invoice', 'Instalación de Fontanería', 'Instalación de fontanería completa.', 1, 9000, 9000, '2025-03-16 21:54:23', '2025-03-16 21:54:23'),
(5, 5, 'App\\Models\\Invoice', 'Pintura', 'Pintura de interiores.', 1, 3000, 3000, '2025-03-16 21:54:23', '2025-03-16 21:54:23'),
(6, 6, 'App\\Models\\Invoice', 'Instalación de Ventanas', 'Instalación de ventanas de vidrio.', 1, 7000, 7000, '2025-03-16 21:54:23', '2025-03-16 21:54:23'),
(7, 7, 'App\\Models\\Invoice', 'Colocación de Pisos', 'Colocación de pisos de cerámica.', 1, 10000, 10000, '2025-03-16 21:54:23', '2025-03-16 21:54:23'),
(8, 8, 'App\\Models\\Invoice', 'Instalación de Ascensores', 'Instalación de ascensores.', 1, 15000, 15000, '2025-03-16 21:54:23', '2025-03-16 21:54:23'),
(9, 9, 'App\\Models\\Invoice', 'Landscaping', 'Diseño y ejecución de paisajismo.', 1, 4000, 4000, '2025-03-16 21:54:23', '2025-03-16 21:54:23'),
(10, 10, 'App\\Models\\Invoice', 'Entrega Final', 'Entrega final del proyecto.', 1, 20000, 20000, '2025-03-16 21:54:23', '2025-03-16 21:54:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales_item_taxes`
--

CREATE TABLE `sales_item_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `sales_item_id` int(10) UNSIGNED NOT NULL,
  `tax_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sales_item_taxes`
--

INSERT INTO `sales_item_taxes` (`id`, `sales_item_id`, `tax_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-03-16 21:54:33', '2025-03-16 21:54:33'),
(2, 2, 2, '2025-03-16 21:54:33', '2025-03-16 21:54:33'),
(3, 3, 3, '2025-03-16 21:54:33', '2025-03-16 21:54:33'),
(4, 4, 4, '2025-03-16 21:54:33', '2025-03-16 21:54:33'),
(5, 5, 5, '2025-03-16 21:54:33', '2025-03-16 21:54:33'),
(6, 6, 6, '2025-03-16 21:54:33', '2025-03-16 21:54:33'),
(7, 7, 1, '2025-03-16 21:54:33', '2025-03-16 21:54:33'),
(8, 8, 2, '2025-03-16 21:54:33', '2025-03-16 21:54:33'),
(9, 9, 3, '2025-03-16 21:54:33', '2025-03-16 21:54:33'),
(10, 10, 4, '2025-03-16 21:54:33', '2025-03-16 21:54:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales_taxes`
--

CREATE TABLE `sales_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` varchar(191) NOT NULL,
  `tax` varchar(191) NOT NULL,
  `amount` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sales_taxes`
--

INSERT INTO `sales_taxes` (`id`, `owner_id`, `owner_type`, `tax`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Invoice', 'IVA', 500, '2025-03-16 21:54:42', '2025-03-16 21:54:42'),
(2, 2, 'App\\Models\\Invoice', 'ISV', 800, '2025-03-16 21:54:42', '2025-03-16 21:54:42'),
(3, 3, 'App\\Models\\Invoice', 'IVA', 1200, '2025-03-16 21:54:42', '2025-03-16 21:54:42'),
(4, 4, 'App\\Models\\Invoice', 'ISV', 900, '2025-03-16 21:54:42', '2025-03-16 21:54:42'),
(5, 5, 'App\\Models\\Invoice', 'IVA', 300, '2025-03-16 21:54:42', '2025-03-16 21:54:42'),
(6, 6, 'App\\Models\\Invoice', 'ISV', 700, '2025-03-16 21:54:42', '2025-03-16 21:54:42'),
(7, 7, 'App\\Models\\Invoice', 'IVA', 1000, '2025-03-16 21:54:42', '2025-03-16 21:54:42'),
(8, 8, 'App\\Models\\Invoice', 'ISV', 1500, '2025-03-16 21:54:42', '2025-03-16 21:54:42'),
(9, 9, 'App\\Models\\Invoice', 'IVA', 400, '2025-03-16 21:54:42', '2025-03-16 21:54:42'),
(10, 10, 'App\\Models\\Invoice', 'ISV', 2000, '2025-03-16 21:54:42', '2025-03-16 21:54:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `services`
--

INSERT INTO `services` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Empathy', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'Communication skills', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'Product knowledge', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'Patience', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'Positive attitude', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'Positive language', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(7, 'Personal responsibility', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(8, 'Confidence', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(9, 'Listening skills', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(10, 'Adaptability', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(11, 'Attentiveness', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(12, 'Professionalism', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(13, 'Acting ability', '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(191) NOT NULL,
  `value` text DEFAULT NULL,
  `group` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'default_country_code', 'hn', 1, '2023-01-11 22:50:13', '2025-03-17 03:18:30'),
(2, 'logo', 'http://crm.test/img/infyom-logo.png', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'favicon', 'http://crm.test/img/infyom-favicon.png', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'company_name', 'Build Martinez', 1, '2023-01-11 22:50:13', '2025-03-17 03:18:58'),
(5, 'company_domain', '127.0.0.1', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'file_type', '.png,.jpg,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(7, 'term_and_conditions', 'This Martinez project is follow all term and conditions and privacy.', 1, '2023-01-11 22:50:13', '2025-03-17 03:18:30'),
(8, 'company', 'Contructora Martinez', 2, '2023-01-11 22:50:13', '2025-03-17 03:19:49'),
(9, 'address', 'Barrio Perpetuo Socorro, Comayagüela, Francisco Morazán', 2, '2023-01-11 22:50:13', '2025-03-17 03:19:49'),
(10, 'city', 'Tegucigalpa', 2, '2023-01-11 22:50:13', '2025-03-17 03:19:49'),
(11, 'state', 'Francisco Morazán', 2, '2023-01-11 22:50:13', '2025-03-17 03:19:49'),
(12, 'country_code', '504', 2, '2023-01-11 22:50:13', '2025-03-17 03:19:49'),
(13, 'zip_code', '11101', 2, '2023-01-11 22:50:13', '2025-03-17 03:19:49'),
(14, 'phone', '+50487714518', 2, '2023-01-11 22:50:13', '2025-03-17 03:19:49'),
(15, 'vat_number', '1234567890', 2, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(16, 'current_currency', 'usd', 2, '2023-01-11 22:50:13', '2025-03-17 03:19:49'),
(17, 'website', 'http://martinez.test./', 2, '2023-01-11 22:50:13', '2025-03-17 03:19:49'),
(18, 'company_information_format', '{company_name}\n                        {address}\n                        {city} {state}\n                        {country_code} {zip_code}\n                        {vat_number_with_label}', 2, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(19, 'admin_note', 'This is the admin note of the Martinez project.', 3, '2023-01-11 22:50:13', '2025-03-17 03:17:48'),
(20, 'client_note', 'This is the client note of the Martinez project.', 3, '2023-01-11 22:50:13', '2025-03-17 03:17:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `stripe_id` varchar(191) NOT NULL,
  `stripe_status` varchar(191) NOT NULL,
  `stripe_price` varchar(191) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subscription_items`
--

CREATE TABLE `subscription_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED NOT NULL,
  `stripe_id` varchar(191) NOT NULL,
  `stripe_product` varchar(191) NOT NULL,
  `stripe_price` varchar(191) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taggables`
--

CREATE TABLE `taggables` (
  `id` int(10) UNSIGNED NOT NULL,
  `taggable_id` int(11) NOT NULL,
  `taggable_type` varchar(191) NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `taggables`
--

INSERT INTO `taggables` (`id`, `taggable_id`, `taggable_type`, `tag_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Ticket', 1, '2025-03-16 21:55:00', '2025-03-16 21:55:00'),
(2, 2, 'App\\Models\\Ticket', 2, '2025-03-16 21:55:00', '2025-03-16 21:55:00'),
(3, 3, 'App\\Models\\Ticket', 3, '2025-03-16 21:55:00', '2025-03-16 21:55:00'),
(4, 4, 'App\\Models\\Ticket', 4, '2025-03-16 21:55:00', '2025-03-16 21:55:00'),
(5, 5, 'App\\Models\\Ticket', 5, '2025-03-16 21:55:00', '2025-03-16 21:55:00'),
(6, 6, 'App\\Models\\Ticket', 6, '2025-03-16 21:55:00', '2025-03-16 21:55:00'),
(7, 7, 'App\\Models\\Ticket', 1, '2025-03-16 21:55:00', '2025-03-16 21:55:00'),
(8, 8, 'App\\Models\\Ticket', 2, '2025-03-16 21:55:00', '2025-03-16 21:55:00'),
(9, 9, 'App\\Models\\Ticket', 3, '2025-03-16 21:55:00', '2025-03-16 21:55:00'),
(10, 10, 'App\\Models\\Ticket', 4, '2025-03-16 21:55:00', '2025-03-16 21:55:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tags`
--

INSERT INTO `tags` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Bug', 'Bugs', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'Follow Up', 'Follow Up', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'Important', 'Important', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'Logo', 'Logo', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'Todo', 'Todo', '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'Tomorrow', 'Tomorrow', '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasks`
--

CREATE TABLE `tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED DEFAULT NULL,
  `public` tinyint(1) DEFAULT NULL,
  `billable` tinyint(1) DEFAULT NULL,
  `subject` varchar(191) NOT NULL,
  `status` int(11) NOT NULL,
  `hourly_rate` varchar(191) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `related_to` int(11) DEFAULT NULL,
  `owner_type` varchar(191) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tasks`
--

INSERT INTO `tasks` (`id`, `member_id`, `public`, `billable`, `subject`, `status`, `hourly_rate`, `start_date`, `due_date`, `priority`, `description`, `related_to`, `owner_type`, `owner_id`, `created_at`, `updated_at`) VALUES
(11, 1, 1, 1, 'Diseño de Estructura', 1, '50', '2023-01-01 00:00:00', '2023-01-15 00:00:00', 1, 'Diseñar la estructura del edificio.', 1, 'App\\Models\\Project', 1, '2025-03-16 21:49:34', '2025-03-16 21:49:34'),
(12, 2, 1, 1, 'Excavación', 2, '40', '2023-01-16 00:00:00', '2023-01-30 00:00:00', 2, 'Excavar el terreno para los cimientos.', 2, 'App\\Models\\Project', 2, '2025-03-16 21:49:34', '2025-03-16 21:49:34'),
(13, 3, 1, 1, 'Instalación Eléctrica', 3, '60', '2023-02-01 00:00:00', '2023-02-15 00:00:00', 3, 'Instalar el sistema eléctrico.', 3, 'App\\Models\\Project', 3, '2025-03-16 21:49:34', '2025-03-16 21:49:34'),
(14, 4, 1, 1, 'Instalación de Fontanería', 4, '55', '2023-02-16 00:00:00', '2023-03-01 00:00:00', 4, 'Instalar el sistema de fontanería.', 4, 'App\\Models\\Project', 4, '2025-03-16 21:49:34', '2025-03-16 21:49:34'),
(15, 5, 1, 1, 'Pintura', 1, '30', '2023-03-02 00:00:00', '2023-03-16 00:00:00', 1, 'Pintar las paredes interiores.', 5, 'App\\Models\\Project', 5, '2025-03-16 21:49:34', '2025-03-16 21:49:34'),
(16, 6, 1, 1, 'Instalación de Ventanas', 2, '45', '2023-03-17 00:00:00', '2023-03-31 00:00:00', 2, 'Instalar las ventanas del edificio.', 6, 'App\\Models\\Project', 6, '2025-03-16 21:49:34', '2025-03-16 21:49:34'),
(17, 7, 1, 1, 'Colocación de Pisos', 3, '50', '2023-04-01 00:00:00', '2023-04-15 00:00:00', 3, 'Colocar los pisos de cerámica.', 7, 'App\\Models\\Project', 7, '2025-03-16 21:49:34', '2025-03-16 21:49:34'),
(18, 8, 1, 1, 'Instalación de Ascensores', 4, '70', '2023-04-16 00:00:00', '2023-04-30 00:00:00', 4, 'Instalar los ascensores del edificio.', 8, 'App\\Models\\Project', 8, '2025-03-16 21:49:34', '2025-03-16 21:49:34'),
(19, 9, 1, 1, 'Landscaping', 1, '35', '2023-05-01 00:00:00', '2023-05-15 00:00:00', 1, 'Diseñar y ejecutar el paisajismo.', 9, 'App\\Models\\Project', 9, '2025-03-16 21:49:34', '2025-03-16 21:49:34'),
(20, 10, 1, 1, 'Entrega Final', 2, '0', '2023-05-16 00:00:00', '2023-05-31 00:00:00', 2, 'Realizar la entrega final del proyecto.', 10, 'App\\Models\\Project', 10, '2025-03-16 21:49:34', '2025-03-16 21:49:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tax_rates`
--

CREATE TABLE `tax_rates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `tax_rate` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tax_rates`
--

INSERT INTO `tax_rates` (`id`, `name`, `tax_rate`, `created_at`, `updated_at`) VALUES
(1, 'Madera', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'Fernado', 2, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'Agow', 5, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'Moon', 10, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'Agxm', 15, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(6, 'County', 20, '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(191) NOT NULL,
  `contact_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `department_id` int(10) UNSIGNED DEFAULT NULL,
  `cc` varchar(191) DEFAULT NULL,
  `assign_to` int(10) UNSIGNED DEFAULT NULL,
  `priority_id` int(10) UNSIGNED DEFAULT NULL,
  `service_id` int(10) UNSIGNED DEFAULT NULL,
  `predefined_reply_id` int(10) UNSIGNED DEFAULT NULL,
  `body` text DEFAULT NULL,
  `ticket_status_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`id`, `subject`, `contact_id`, `name`, `email`, `department_id`, `cc`, `assign_to`, `priority_id`, `service_id`, `predefined_reply_id`, `body`, `ticket_status_id`, `created_at`, `updated_at`) VALUES
(1, 'Problema con el diseño', 1, 'Juan Pérez', 'juan@constructoraXYZ.com', 1, NULL, 1, 1, 1, NULL, 'El diseño no cumple con los requisitos.', 1, '2025-03-16 21:53:42', '2025-03-16 21:53:42'),
(2, 'Retraso en la excavación', 2, 'María López', 'maria@ingenieriaABC.com', 2, NULL, 2, 2, 2, NULL, 'La excavación está retrasada.', 2, '2025-03-16 21:53:42', '2025-03-16 21:53:42'),
(3, 'Fallo en la instalación eléctrica', 3, 'Carlos Gómez', 'carlos@arquitecturaDEF.com', 3, NULL, 3, 3, 3, NULL, 'La instalación eléctrica tiene fallos.', 3, '2025-03-16 21:53:42', '2025-03-16 21:53:42'),
(4, 'Fuga en la fontanería', 4, 'Ana Martínez', 'ana@construccionesGHI.com', 4, NULL, 4, 4, 4, NULL, 'Hay una fuga en la fontanería.', 4, '2025-03-16 21:53:42', '2025-03-16 21:53:42'),
(5, 'Problema con la pintura', 5, 'Luis Ramírez', 'luis@proyectosJKL.com', 5, NULL, 5, 1, 5, NULL, 'La pintura no es del color correcto.', 5, '2025-03-16 21:53:42', '2025-03-16 21:53:42'),
(6, 'Ventanas mal instaladas', 6, 'Sofía Hernández', 'sofia@edificacionesMNO.com', 6, NULL, 6, 2, 6, NULL, 'Las ventanas están mal instaladas.', 1, '2025-03-16 21:53:42', '2025-03-16 21:53:42'),
(7, 'Pisos defectuosos', 7, 'Pedro Sánchez', 'pedro@construccionesPQR.com', 1, NULL, 7, 3, 7, NULL, 'Los pisos tienen defectos.', 2, '2025-03-16 21:53:42', '2025-03-16 21:53:42'),
(8, 'Ascensor no funciona', 8, 'Laura Díaz', 'laura@hotelesSTU.com', 2, NULL, 8, 4, 8, NULL, 'El ascensor no funciona.', 3, '2025-03-16 21:53:42', '2025-03-16 21:53:42'),
(9, 'Problema con el paisajismo', 9, 'Jorge Castro', 'jorge@residencialesVWX.com', 3, NULL, 9, 1, 9, NULL, 'El paisajismo no está completo.', 4, '2025-03-16 21:53:42', '2025-03-16 21:53:42'),
(10, 'Entrega retrasada', 10, 'Carmen Ruiz', 'carmen@condominiosYZ.com', 4, NULL, 10, 2, 10, NULL, 'La entrega está retrasada.', 5, '2025-03-16 21:53:42', '2025-03-16 21:53:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_priorities`
--

CREATE TABLE `ticket_priorities` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ticket_priorities`
--

INSERT INTO `ticket_priorities` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Low', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'Medium', 0, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'High', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'Urgent', 0, '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `reply` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ticket_replies`
--

INSERT INTO `ticket_replies` (`id`, `ticket_id`, `user_id`, `reply`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Estamos revisando el problema con el diseño.', '2025-03-16 21:54:49', '2025-03-16 21:54:49'),
(2, 2, 2, 'Se ha asignado un equipo para resolver el retraso en la excavación.', '2025-03-16 21:54:49', '2025-03-16 21:54:49'),
(3, 3, 3, 'Se está trabajando en la solución del fallo eléctrico.', '2025-03-16 21:54:49', '2025-03-16 21:54:49'),
(4, 4, 4, 'Un técnico revisará la fuga en la fontanería.', '2025-03-16 21:54:49', '2025-03-16 21:54:49'),
(5, 5, 5, 'Se corregirá el color de la pintura.', '2025-03-16 21:54:49', '2025-03-16 21:54:49'),
(6, 6, 6, 'Se revisará la instalación de las ventanas.', '2025-03-16 21:54:49', '2025-03-16 21:54:49'),
(7, 7, 7, 'Se están corrigiendo los defectos en los pisos.', '2025-03-16 21:54:49', '2025-03-16 21:54:49'),
(8, 8, 8, 'Un técnico revisará el ascensor.', '2025-03-16 21:54:49', '2025-03-16 21:54:49'),
(9, 9, 9, 'Se completará el paisajismo lo antes posible.', '2025-03-16 21:54:49', '2025-03-16 21:54:49'),
(10, 10, 10, 'Se está trabajando para cumplir con la fecha de entrega.', '2025-03-16 21:54:49', '2025-03-16 21:54:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_statuses`
--

CREATE TABLE `ticket_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `pick_color` varchar(191) NOT NULL,
  `is_default` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ticket_statuses`
--

INSERT INTO `ticket_statuses` (`id`, `name`, `pick_color`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Open', '#fc544b', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(2, 'In Progress', '#6777ef', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(3, 'Answered', '#3abaf4', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(4, 'On Hold', '#ffa426', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13'),
(5, 'Closed', '#47c363', 1, '2023-01-11 22:50:13', '2023-01-11 22:50:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `owner_type` varchar(191) DEFAULT NULL,
  `is_enable` tinyint(1) NOT NULL DEFAULT 1,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `image` varchar(191) DEFAULT NULL,
  `facebook` varchar(191) DEFAULT NULL,
  `linkedin` varchar(191) DEFAULT NULL,
  `skype` varchar(191) DEFAULT NULL,
  `staff_member` tinyint(1) DEFAULT NULL,
  `send_welcome_email` tinyint(1) DEFAULT NULL,
  `default_language` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stripe_id` varchar(191) DEFAULT NULL,
  `pm_type` varchar(191) DEFAULT NULL,
  `pm_last_four` varchar(4) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `owner_id`, `owner_type`, `is_enable`, `is_admin`, `image`, `facebook`, `linkedin`, `skype`, `staff_member`, `send_welcome_email`, `default_language`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`, `stripe_id`, `pm_type`, `pm_last_four`, `trial_ends_at`) VALUES
(1, 'Super', 'Admin', 'admin@infycrm.com', '+917878454512', '$2y$10$wxDbHkXgU6M.wT4SUOdfFu70Y8Rb2v9fW6GNzKkwwLFNTOadYwVNy', NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'es', '2023-01-11 22:50:13', NULL, '2023-01-11 22:50:13', '2025-03-17 03:25:26', NULL, NULL, NULL, NULL),
(2, 'Juan', 'Pérez', 'juan@constructoraXYZ.com', '+50498765432', '$2y$10$wxDbHkXgU6M.wT4SUOdfFu70Y8Rb2v9fW6GNzKkwwLFNTOadYwVNy', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, 1, 1, 'es', '2025-03-16 21:49:19', NULL, '2025-03-16 21:49:19', '2025-03-16 21:49:19', NULL, NULL, NULL, NULL),
(3, 'María', 'López', 'maria@ingenieriaABC.com', '+50498765433', '$2y$10$wxDbHkXgU6M.wT4SUOdfFu70Y8Rb2v9fW6GNzKkwwLFNTOadYwVNy', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, 1, 1, 'es', '2025-03-16 21:49:19', NULL, '2025-03-16 21:49:19', '2025-03-16 21:49:19', NULL, NULL, NULL, NULL),
(4, 'Carlos', 'Gómez', 'carlos@arquitecturaDEF.com', '+50498765434', '$2y$10$wxDbHkXgU6M.wT4SUOdfFu70Y8Rb2v9fW6GNzKkwwLFNTOadYwVNy', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, 1, 1, 'es', '2025-03-16 21:49:19', NULL, '2025-03-16 21:49:19', '2025-03-16 21:49:19', NULL, NULL, NULL, NULL),
(5, 'Ana', 'Martínez', 'ana@construccionesGHI.com', '+50498765435', '$2y$10$wxDbHkXgU6M.wT4SUOdfFu70Y8Rb2v9fW6GNzKkwwLFNTOadYwVNy', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, 1, 1, 'es', '2025-03-16 21:49:19', NULL, '2025-03-16 21:49:19', '2025-03-16 21:49:19', NULL, NULL, NULL, NULL),
(6, 'Luis', 'Ramírez', 'luis@proyectosJKL.com', '+50498765436', '$2y$10$wxDbHkXgU6M.wT4SUOdfFu70Y8Rb2v9fW6GNzKkwwLFNTOadYwVNy', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, 1, 1, 'es', '2025-03-16 21:49:19', NULL, '2025-03-16 21:49:19', '2025-03-16 21:49:19', NULL, NULL, NULL, NULL),
(7, 'Sofía', 'Hernández', 'sofia@edificacionesMNO.com', '+50498765437', '$2y$10$wxDbHkXgU6M.wT4SUOdfFu70Y8Rb2v9fW6GNzKkwwLFNTOadYwVNy', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, 1, 1, 'es', '2025-03-16 21:49:19', NULL, '2025-03-16 21:49:19', '2025-03-16 21:49:19', NULL, NULL, NULL, NULL),
(8, 'Pedro', 'Sánchez', 'pedro@construccionesPQR.com', '+50498765438', '$2y$10$wxDbHkXgU6M.wT4SUOdfFu70Y8Rb2v9fW6GNzKkwwLFNTOadYwVNy', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, 1, 1, 'es', '2025-03-16 21:49:19', NULL, '2025-03-16 21:49:19', '2025-03-16 21:49:19', NULL, NULL, NULL, NULL),
(9, 'Laura', 'Díaz', 'laura@hotelesSTU.com', '+50498765439', '$2y$10$wxDbHkXgU6M.wT4SUOdfFu70Y8Rb2v9fW6GNzKkwwLFNTOadYwVNy', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, 1, 1, 'es', '2025-03-16 21:49:19', NULL, '2025-03-16 21:49:19', '2025-03-16 21:49:19', NULL, NULL, NULL, NULL),
(10, 'Jorge', 'Castro', 'jorge@residencialesVWX.com', '+50498765430', '$2y$10$wxDbHkXgU6M.wT4SUOdfFu70Y8Rb2v9fW6GNzKkwwLFNTOadYwVNy', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, 1, 1, 'es', '2025-03-16 21:49:19', NULL, '2025-03-16 21:49:19', '2025-03-16 21:49:19', NULL, NULL, NULL, NULL),
(11, 'Carmen', 'Ruiz', 'carmen@condominiosYZ.com', '+50498765431', '$2y$10$wxDbHkXgU6M.wT4SUOdfFu70Y8Rb2v9fW6GNzKkwwLFNTOadYwVNy', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, 1, 1, 'es', '2025-03-16 21:49:19', NULL, '2025-03-16 21:49:19', '2025-03-16 21:49:19', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_departments`
--

CREATE TABLE `user_departments` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indices de la tabla `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `articles_group_id_foreign` (`group_id`);

--
-- Indices de la tabla `article_groups`
--
ALTER TABLE `article_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `article_groups_group_name_unique` (`group_name`);

--
-- Indices de la tabla `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_customer_id_foreign` (`customer_id`),
  ADD KEY `contacts_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `contact_email_notifications`
--
ALTER TABLE `contact_email_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_email_notifications_contact_id_foreign` (`contact_id`),
  ADD KEY `contact_email_notifications_email_notification_id_foreign` (`email_notification_id`);

--
-- Indices de la tabla `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contracts_subject_unique` (`subject`),
  ADD KEY `contracts_customer_id_foreign` (`customer_id`),
  ADD KEY `contracts_contract_type_id_foreign` (`contract_type_id`);

--
-- Indices de la tabla `contract_types`
--
ALTER TABLE `contract_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contract_types_name_unique` (`name`);

--
-- Indices de la tabla `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `credit_notes`
--
ALTER TABLE `credit_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_notes_customer_id_foreign` (`customer_id`);

--
-- Indices de la tabla `credit_note_addresses`
--
ALTER TABLE `credit_note_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credit_note_addresses_credit_note_id_foreign` (`credit_note_id`);

--
-- Indices de la tabla `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customer_groups`
--
ALTER TABLE `customer_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_groups_name_unique` (`name`);

--
-- Indices de la tabla `customer_to_customer_groups`
--
ALTER TABLE `customer_to_customer_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_to_customer_groups_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_to_customer_groups_customer_group_id_foreign` (`customer_group_id`);

--
-- Indices de la tabla `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`);

--
-- Indices de la tabla `email_notifications`
--
ALTER TABLE `email_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estimates`
--
ALTER TABLE `estimates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estimates_customer_id_foreign` (`customer_id`),
  ADD KEY `estimates_sales_agent_id_foreign` (`sales_agent_id`);

--
-- Indices de la tabla `estimate_addresses`
--
ALTER TABLE `estimate_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estimate_addresses_estimate_id_foreign` (`estimate_id`);

--
-- Indices de la tabla `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_expense_category_id_foreign` (`expense_category_id`),
  ADD KEY `expenses_tax_1_id_foreign` (`tax_1_id`),
  ADD KEY `expenses_tax_2_id_foreign` (`tax_2_id`),
  ADD KEY `expenses_payment_mode_id_foreign` (`payment_mode_id`),
  ADD KEY `expenses_customer_id_foreign` (`customer_id`);

--
-- Indices de la tabla `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_categories_name_unique` (`name`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goals_subject_unique` (`subject`);

--
-- Indices de la tabla `goal_members`
--
ALTER TABLE `goal_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goal_members_user_id_foreign` (`user_id`),
  ADD KEY `goal_members_goal_id_foreign` (`goal_id`);

--
-- Indices de la tabla `goal_types`
--
ALTER TABLE `goal_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goal_types_name_unique` (`name`);

--
-- Indices de la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_customer_id_foreign` (`customer_id`),
  ADD KEY `invoices_sales_agent_id_foreign` (`sales_agent_id`);

--
-- Indices de la tabla `invoice_addresses`
--
ALTER TABLE `invoice_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_addresses_invoice_id_foreign` (`invoice_id`);

--
-- Indices de la tabla `invoice_payment_modes`
--
ALTER TABLE `invoice_payment_modes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_payment_modes_payment_mode_id_foreign` (`payment_mode_id`),
  ADD KEY `invoice_payment_modes_invoice_id_foreign` (`invoice_id`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_item_group_id_foreign` (`item_group_id`);

--
-- Indices de la tabla `item_groups`
--
ALTER TABLE `item_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_groups_name_unique` (`name`);

--
-- Indices de la tabla `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_status_id_foreign` (`status_id`),
  ADD KEY `leads_source_id_foreign` (`source_id`),
  ADD KEY `leads_assign_to_foreign` (`assign_to`);

--
-- Indices de la tabla `lead_sources`
--
ALTER TABLE `lead_sources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lead_sources_name_unique` (`name`);

--
-- Indices de la tabla `lead_statuses`
--
ALTER TABLE `lead_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notes_added_by_foreign` (`added_by`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_payment_mode_foreign` (`payment_mode`);

--
-- Indices de la tabla `payment_modes`
--
ALTER TABLE `payment_modes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_modes_name_unique` (`name`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `predefined_replies`
--
ALTER TABLE `predefined_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_customer_id_foreign` (`customer_id`);

--
-- Indices de la tabla `project_contacts`
--
ALTER TABLE `project_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_contacts_contact_id_foreign` (`contact_id`),
  ADD KEY `project_contacts_project_id_foreign` (`project_id`);

--
-- Indices de la tabla `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_members_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proposals_proposal_number_unique` (`proposal_number`);

--
-- Indices de la tabla `proposal_addresses`
--
ALTER TABLE `proposal_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proposal_addresses_proposal_id_foreign` (`proposal_id`);

--
-- Indices de la tabla `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reminders_reminder_to_foreign` (`reminder_to`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sales_item_taxes`
--
ALTER TABLE `sales_item_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_item_taxes_tax_id_foreign` (`tax_id`),
  ADD KEY `sales_item_taxes_sales_item_id_foreign` (`sales_item_id`);

--
-- Indices de la tabla `sales_taxes`
--
ALTER TABLE `sales_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_name_unique` (`name`);

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscriptions_stripe_id_unique` (`stripe_id`),
  ADD KEY `subscriptions_user_id_stripe_status_index` (`user_id`,`stripe_status`);

--
-- Indices de la tabla `subscription_items`
--
ALTER TABLE `subscription_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_items_subscription_id_stripe_price_unique` (`subscription_id`,`stripe_price`),
  ADD UNIQUE KEY `subscription_items_stripe_id_unique` (`stripe_id`);

--
-- Indices de la tabla `taggables`
--
ALTER TABLE `taggables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taggables_tag_id_foreign` (`tag_id`);

--
-- Indices de la tabla `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_member_id_foreign` (`member_id`);

--
-- Indices de la tabla `tax_rates`
--
ALTER TABLE `tax_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tax_rates_name_unique` (`name`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_priority_id_foreign` (`priority_id`),
  ADD KEY `tickets_service_id_foreign` (`service_id`),
  ADD KEY `tickets_ticket_status_id_foreign` (`ticket_status_id`),
  ADD KEY `tickets_predefined_reply_id_foreign` (`predefined_reply_id`),
  ADD KEY `tickets_contact_id_foreign` (`contact_id`),
  ADD KEY `tickets_department_id_foreign` (`department_id`),
  ADD KEY `tickets_assign_to_foreign` (`assign_to`);

--
-- Indices de la tabla `ticket_priorities`
--
ALTER TABLE `ticket_priorities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_priorities_name_unique` (`name`);

--
-- Indices de la tabla `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_replies_ticket_id_foreign` (`ticket_id`),
  ADD KEY `ticket_replies_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_statuses_name_unique` (`name`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_stripe_id_index` (`stripe_id`);

--
-- Indices de la tabla `user_departments`
--
ALTER TABLE `user_departments`
  ADD KEY `user_departments_user_id_foreign` (`user_id`),
  ADD KEY `user_departments_department_id_foreign` (`department_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `article_groups`
--
ALTER TABLE `article_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `contact_email_notifications`
--
ALTER TABLE `contact_email_notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `contract_types`
--
ALTER TABLE `contract_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `credit_notes`
--
ALTER TABLE `credit_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `credit_note_addresses`
--
ALTER TABLE `credit_note_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `customer_to_customer_groups`
--
ALTER TABLE `customer_to_customer_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `email_notifications`
--
ALTER TABLE `email_notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estimates`
--
ALTER TABLE `estimates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estimate_addresses`
--
ALTER TABLE `estimate_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `goal_members`
--
ALTER TABLE `goal_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `goal_types`
--
ALTER TABLE `goal_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `invoice_addresses`
--
ALTER TABLE `invoice_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `invoice_payment_modes`
--
ALTER TABLE `invoice_payment_modes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `item_groups`
--
ALTER TABLE `item_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `lead_sources`
--
ALTER TABLE `lead_sources`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `lead_statuses`
--
ALTER TABLE `lead_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `payment_modes`
--
ALTER TABLE `payment_modes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `predefined_replies`
--
ALTER TABLE `predefined_replies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `project_contacts`
--
ALTER TABLE `project_contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proposal_addresses`
--
ALTER TABLE `proposal_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `sales_item_taxes`
--
ALTER TABLE `sales_item_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `sales_taxes`
--
ALTER TABLE `sales_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subscription_items`
--
ALTER TABLE `subscription_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `taggables`
--
ALTER TABLE `taggables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `tax_rates`
--
ALTER TABLE `tax_rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `ticket_priorities`
--
ALTER TABLE `ticket_priorities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `article_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `contact_email_notifications`
--
ALTER TABLE `contact_email_notifications`
  ADD CONSTRAINT `contact_email_notifications_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_email_notifications_email_notification_id_foreign` FOREIGN KEY (`email_notification_id`) REFERENCES `email_notifications` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_contract_type_id_foreign` FOREIGN KEY (`contract_type_id`) REFERENCES `contract_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contracts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `credit_notes`
--
ALTER TABLE `credit_notes`
  ADD CONSTRAINT `credit_notes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `credit_note_addresses`
--
ALTER TABLE `credit_note_addresses`
  ADD CONSTRAINT `credit_note_addresses_credit_note_id_foreign` FOREIGN KEY (`credit_note_id`) REFERENCES `credit_notes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `customer_to_customer_groups`
--
ALTER TABLE `customer_to_customer_groups`
  ADD CONSTRAINT `customer_to_customer_groups_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_to_customer_groups_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estimates`
--
ALTER TABLE `estimates`
  ADD CONSTRAINT `estimates_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `estimates_sales_agent_id_foreign` FOREIGN KEY (`sales_agent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `estimate_addresses`
--
ALTER TABLE `estimate_addresses`
  ADD CONSTRAINT `estimate_addresses_estimate_id_foreign` FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_payment_mode_id_foreign` FOREIGN KEY (`payment_mode_id`) REFERENCES `payment_modes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_tax_1_id_foreign` FOREIGN KEY (`tax_1_id`) REFERENCES `tax_rates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expenses_tax_2_id_foreign` FOREIGN KEY (`tax_2_id`) REFERENCES `tax_rates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `goal_members`
--
ALTER TABLE `goal_members`
  ADD CONSTRAINT `goal_members_goal_id_foreign` FOREIGN KEY (`goal_id`) REFERENCES `goals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `goal_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoices_sales_agent_id_foreign` FOREIGN KEY (`sales_agent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `invoice_addresses`
--
ALTER TABLE `invoice_addresses`
  ADD CONSTRAINT `invoice_addresses_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `invoice_payment_modes`
--
ALTER TABLE `invoice_payment_modes`
  ADD CONSTRAINT `invoice_payment_modes_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_payment_modes_payment_mode_id_foreign` FOREIGN KEY (`payment_mode_id`) REFERENCES `payment_modes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_item_group_id_foreign` FOREIGN KEY (`item_group_id`) REFERENCES `item_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_assign_to_foreign` FOREIGN KEY (`assign_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leads_source_id_foreign` FOREIGN KEY (`source_id`) REFERENCES `lead_sources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leads_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `lead_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_payment_mode_foreign` FOREIGN KEY (`payment_mode`) REFERENCES `payment_modes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `project_contacts`
--
ALTER TABLE `project_contacts`
  ADD CONSTRAINT `project_contacts_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_contacts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `project_members`
--
ALTER TABLE `project_members`
  ADD CONSTRAINT `project_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `proposal_addresses`
--
ALTER TABLE `proposal_addresses`
  ADD CONSTRAINT `proposal_addresses_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_reminder_to_foreign` FOREIGN KEY (`reminder_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sales_item_taxes`
--
ALTER TABLE `sales_item_taxes`
  ADD CONSTRAINT `sales_item_taxes_sales_item_id_foreign` FOREIGN KEY (`sales_item_id`) REFERENCES `sales_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_item_taxes_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `tax_rates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `taggables`
--
ALTER TABLE `taggables`
  ADD CONSTRAINT `taggables_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_assign_to_foreign` FOREIGN KEY (`assign_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_predefined_reply_id_foreign` FOREIGN KEY (`predefined_reply_id`) REFERENCES `predefined_replies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_priority_id_foreign` FOREIGN KEY (`priority_id`) REFERENCES `ticket_priorities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_ticket_status_id_foreign` FOREIGN KEY (`ticket_status_id`) REFERENCES `ticket_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `user_departments`
--
ALTER TABLE `user_departments`
  ADD CONSTRAINT `user_departments_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_departments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
