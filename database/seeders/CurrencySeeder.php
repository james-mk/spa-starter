<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            // A
            ['code' => 'AED', 'name' => 'United Arab Emirates Dirham', 'symbol' => 'د.إ', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'AFN', 'name' => 'Afghan Afghani', 'symbol' => '؋', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'ALL', 'name' => 'Albanian Lek', 'symbol' => 'L', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'AMD', 'name' => 'Armenian Dram', 'symbol' => '֏', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'ANG', 'name' => 'Netherlands Antillean Guilder', 'symbol' => 'ƒ', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'AOA', 'name' => 'Angolan Kwanza', 'symbol' => 'Kz', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'ARS', 'name' => 'Argentine Peso', 'symbol' => '$', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'AUD', 'name' => 'Australian Dollar', 'symbol' => 'A$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'AWG', 'name' => 'Aruban Florin', 'symbol' => 'ƒ', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'AZN', 'name' => 'Azerbaijani Manat', 'symbol' => '₼', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // B
            ['code' => 'BAM', 'name' => 'Bosnia-Herzegovina Convertible Mark', 'symbol' => 'KM', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'BBD', 'name' => 'Barbadian Dollar', 'symbol' => 'Bds$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'BDT', 'name' => 'Bangladeshi Taka', 'symbol' => '৳', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'BGN', 'name' => 'Bulgarian Lev', 'symbol' => 'лв', 'decimal_mark' => ',', 'thousands_separator' => ' ', 'decimal_places' => 2],
            ['code' => 'BHD', 'name' => 'Bahraini Dinar', 'symbol' => 'BD', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 3],
            ['code' => 'BIF', 'name' => 'Burundian Franc', 'symbol' => 'FBu', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'BMD', 'name' => 'Bermudan Dollar', 'symbol' => 'BD$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'BND', 'name' => 'Brunei Dollar', 'symbol' => 'B$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'BOB', 'name' => 'Bolivian Boliviano', 'symbol' => 'Bs.', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'BSD', 'name' => 'Bahamian Dollar', 'symbol' => 'B$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'BTN', 'name' => 'Bhutanese Ngultrum', 'symbol' => 'Nu.', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'BWP', 'name' => 'Botswanan Pula', 'symbol' => 'P', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'BYN', 'name' => 'Belarusian Ruble', 'symbol' => 'Br', 'decimal_mark' => ',', 'thousands_separator' => ' ', 'decimal_places' => 2],
            ['code' => 'BZD', 'name' => 'Belize Dollar', 'symbol' => 'BZ$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // C
            ['code' => 'CAD', 'name' => 'Canadian Dollar', 'symbol' => 'C$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'CDF', 'name' => 'Congolese Franc', 'symbol' => 'FC', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'CHF', 'name' => 'Swiss Franc', 'symbol' => 'CHF', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'CLP', 'name' => 'Chilean Peso', 'symbol' => '$', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 0],
            ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'COP', 'name' => 'Colombian Peso', 'symbol' => '$', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'CRC', 'name' => 'Costa Rican Colón', 'symbol' => '₡', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'CUP', 'name' => 'Cuban Peso', 'symbol' => '₱', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'CVE', 'name' => 'Cape Verdean Escudo', 'symbol' => '$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'CZK', 'name' => 'Czech Koruna', 'symbol' => 'Kč', 'decimal_mark' => ',', 'thousands_separator' => ' ', 'decimal_places' => 2],

            // D
            ['code' => 'DJF', 'name' => 'Djiboutian Franc', 'symbol' => 'Fdj', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'DKK', 'name' => 'Danish Krone', 'symbol' => 'kr', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'DOP', 'name' => 'Dominican Peso', 'symbol' => 'RD$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'DZD', 'name' => 'Algerian Dinar', 'symbol' => 'د.ج', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // E
            ['code' => 'EGP', 'name' => 'Egyptian Pound', 'symbol' => 'E£', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'ERN', 'name' => 'Eritrean Nakfa', 'symbol' => 'Nfk', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'ETB', 'name' => 'Ethiopian Birr', 'symbol' => 'Br', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],

            // F
            ['code' => 'FJD', 'name' => 'Fijian Dollar', 'symbol' => 'FJ$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'FKP', 'name' => 'Falkland Islands Pound', 'symbol' => '£', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // G
            ['code' => 'GBP', 'name' => 'British Pound Sterling', 'symbol' => '£', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'GEL', 'name' => 'Georgian Lari', 'symbol' => '₾', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'GHS', 'name' => 'Ghanaian Cedi', 'symbol' => 'GH₵', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'GIP', 'name' => 'Gibraltar Pound', 'symbol' => '£', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'GMD', 'name' => 'Gambian Dalasi', 'symbol' => 'D', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'GNF', 'name' => 'Guinean Franc', 'symbol' => 'FG', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'GTQ', 'name' => 'Guatemalan Quetzal', 'symbol' => 'Q', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'GYD', 'name' => 'Guyanaese Dollar', 'symbol' => 'G$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // H
            ['code' => 'HKD', 'name' => 'Hong Kong Dollar', 'symbol' => 'HK$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'HNL', 'name' => 'Honduran Lempira', 'symbol' => 'L', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'HRK', 'name' => 'Croatian Kuna', 'symbol' => 'kn', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'HTG', 'name' => 'Haitian Gourde', 'symbol' => 'G', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'HUF', 'name' => 'Hungarian Forint', 'symbol' => 'Ft', 'decimal_mark' => ',', 'thousands_separator' => ' ', 'decimal_places' => 0],

            // I
            ['code' => 'IDR', 'name' => 'Indonesian Rupiah', 'symbol' => 'Rp', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 0],
            ['code' => 'ILS', 'name' => 'Israeli New Shekel', 'symbol' => '₪', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'INR', 'name' => 'Indian Rupee', 'symbol' => '₹', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'IQD', 'name' => 'Iraqi Dinar', 'symbol' => 'ع.د', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 3],
            ['code' => 'IRR', 'name' => 'Iranian Rial', 'symbol' => '﷼', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'ISK', 'name' => 'Icelandic Króna', 'symbol' => 'kr', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 0],

            // J
            ['code' => 'JMD', 'name' => 'Jamaican Dollar', 'symbol' => 'J$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'JOD', 'name' => 'Jordanian Dinar', 'symbol' => 'JD', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 3],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],

            // K
            ['code' => 'KES', 'name' => 'Kenyan Shilling', 'symbol' => 'KSh', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'KGS', 'name' => 'Kyrgystani Som', 'symbol' => 'с', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'KHR', 'name' => 'Cambodian Riel', 'symbol' => '៛', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'KMF', 'name' => 'Comorian Franc', 'symbol' => 'CF', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'KPW', 'name' => 'North Korean Won', 'symbol' => '₩', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'KRW', 'name' => 'South Korean Won', 'symbol' => '₩', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'KWD', 'name' => 'Kuwaiti Dinar', 'symbol' => 'KD', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 3],
            ['code' => 'KYD', 'name' => 'Cayman Islands Dollar', 'symbol' => 'CI$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'KZT', 'name' => 'Kazakhstani Tenge', 'symbol' => '₸', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // L
            ['code' => 'LAK', 'name' => 'Laotian Kip', 'symbol' => '₭', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'LBP', 'name' => 'Lebanese Pound', 'symbol' => 'ل.ل', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'LKR', 'name' => 'Sri Lankan Rupee', 'symbol' => 'Rs', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'LRD', 'name' => 'Liberian Dollar', 'symbol' => 'L$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'LSL', 'name' => 'Lesotho Loti', 'symbol' => 'L', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'LYD', 'name' => 'Libyan Dinar', 'symbol' => 'ل.د', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 3],

            // M
            ['code' => 'MAD', 'name' => 'Moroccan Dirham', 'symbol' => 'د.م.', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'MDL', 'name' => 'Moldovan Leu', 'symbol' => 'L', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'MGA', 'name' => 'Malagasy Ariary', 'symbol' => 'Ar', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'MKD', 'name' => 'Macedonian Denar', 'symbol' => 'ден', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'MMK', 'name' => 'Myanma Kyat', 'symbol' => 'K', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'MNT', 'name' => 'Mongolian Tugrik', 'symbol' => '₮', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'MOP', 'name' => 'Macanese Pataca', 'symbol' => 'MOP$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'MRU', 'name' => 'Mauritanian Ouguiya', 'symbol' => 'UM', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'MUR', 'name' => 'Mauritian Rupee', 'symbol' => '₨', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'MVR', 'name' => 'Maldivian Rufiyaa', 'symbol' => 'Rf', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'MWK', 'name' => 'Malawian Kwacha', 'symbol' => 'MK', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'MXN', 'name' => 'Mexican Peso', 'symbol' => '$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'MYR', 'name' => 'Malaysian Ringgit', 'symbol' => 'RM', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'MZN', 'name' => 'Mozambican Metical', 'symbol' => 'MT', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // N
            ['code' => 'NAD', 'name' => 'Namibian Dollar', 'symbol' => 'N$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'NGN', 'name' => 'Nigerian Naira', 'symbol' => '₦', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'NIO', 'name' => 'Nicaraguan Córdoba', 'symbol' => 'C$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'NOK', 'name' => 'Norwegian Krone', 'symbol' => 'kr', 'decimal_mark' => ',', 'thousands_separator' => ' ', 'decimal_places' => 2],
            ['code' => 'NPR', 'name' => 'Nepalese Rupee', 'symbol' => 'Rs', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'NZD', 'name' => 'New Zealand Dollar', 'symbol' => 'NZ$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // O
            ['code' => 'OMR', 'name' => 'Omani Rial', 'symbol' => 'ر.ع.', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 3],

            // P
            ['code' => 'PAB', 'name' => 'Panamanian Balboa', 'symbol' => 'B/.', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'PEN', 'name' => 'Peruvian Nuevo Sol', 'symbol' => 'S/', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'PGK', 'name' => 'Papua New Guinean Kina', 'symbol' => 'K', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'PHP', 'name' => 'Philippine Peso', 'symbol' => '₱', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'PKR', 'name' => 'Pakistani Rupee', 'symbol' => '₨', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'PLN', 'name' => 'Polish Zloty', 'symbol' => 'zł', 'decimal_mark' => ',', 'thousands_separator' => ' ', 'decimal_places' => 2],
            ['code' => 'PYG', 'name' => 'Paraguayan Guarani', 'symbol' => '₲', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 0],

            // Q
            ['code' => 'QAR', 'name' => 'Qatari Rial', 'symbol' => 'ر.ق', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // R
            ['code' => 'RON', 'name' => 'Romanian Leu', 'symbol' => 'lei', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'RSD', 'name' => 'Serbian Dinar', 'symbol' => 'дин.', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'RUB', 'name' => 'Russian Ruble', 'symbol' => '₽', 'decimal_mark' => ',', 'thousands_separator' => ' ', 'decimal_places' => 2],
            ['code' => 'RWF', 'name' => 'Rwandan Franc', 'symbol' => 'FRw', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],

            // S
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => 'ر.س', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'SBD', 'name' => 'Solomon Islands Dollar', 'symbol' => 'SI$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'SCR', 'name' => 'Seychellois Rupee', 'symbol' => 'SR', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'SDG', 'name' => 'Sudanese Pound', 'symbol' => 'ج.س.', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'SEK', 'name' => 'Swedish Krona', 'symbol' => 'kr', 'decimal_mark' => ',', 'thousands_separator' => ' ', 'decimal_places' => 2],
            ['code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'SHP', 'name' => 'Saint Helena Pound', 'symbol' => '£', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'SLL', 'name' => 'Sierra Leonean Leone', 'symbol' => 'Le', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'SOS', 'name' => 'Somali Shilling', 'symbol' => 'Sh', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'SRD', 'name' => 'Surinamese Dollar', 'symbol' => '$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'SSP', 'name' => 'South Sudanese Pound', 'symbol' => '£', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'STN', 'name' => 'São Tomé and Príncipe Dobra', 'symbol' => 'Db', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'SYP', 'name' => 'Syrian Pound', 'symbol' => '£S', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'SZL', 'name' => 'Swazi Lilangeni', 'symbol' => 'L', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            // T
            ['code' => 'THB', 'name' => 'Thai Baht', 'symbol' => '฿', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'TJS', 'name' => 'Tajikistani Somoni', 'symbol' => 'ЅМ', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'TMT', 'name' => 'Turkmenistani Manat', 'symbol' => 'm', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'TND', 'name' => 'Tunisian Dinar', 'symbol' => 'د.ت', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 3],
            ['code' => 'TOP', 'name' => 'Tongan Paʻanga', 'symbol' => 'T$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'TRY', 'name' => 'Turkish Lira', 'symbol' => '₺', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'TTD', 'name' => 'Trinidad and Tobago Dollar', 'symbol' => 'TT$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'TWD', 'name' => 'New Taiwan Dollar', 'symbol' => 'NT$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'TZS', 'name' => 'Tanzanian Shilling', 'symbol' => 'TSh', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],

            // U
            ['code' => 'UAH', 'name' => 'Ukrainian Hryvnia', 'symbol' => '₴', 'decimal_mark' => ',', 'thousands_separator' => ' ', 'decimal_places' => 2],
            ['code' => 'UGX', 'name' => 'Ugandan Shilling', 'symbol' => 'USh', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'USD', 'name' => 'United States Dollar', 'symbol' => '$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'UYU', 'name' => 'Uruguayan Peso', 'symbol' => '$U', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'UZS', 'name' => 'Uzbekistan Som', 'symbol' => 'so\'m', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],

            // V
            ['code' => 'VES', 'name' => 'Venezuelan Bolívar', 'symbol' => 'Bs.', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 2],
            ['code' => 'VND', 'name' => 'Vietnamese Dong', 'symbol' => '₫', 'decimal_mark' => ',', 'thousands_separator' => '.', 'decimal_places' => 0],
            ['code' => 'VUV', 'name' => 'Vanuatu Vatu', 'symbol' => 'VT', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],

            // W
            ['code' => 'WST', 'name' => 'Samoan Tala', 'symbol' => 'WS$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // X (Special currencies)
            ['code' => 'XAF', 'name' => 'Central African CFA Franc', 'symbol' => 'FCFA', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'XCD', 'name' => 'East Caribbean Dollar', 'symbol' => 'EC$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'XOF', 'name' => 'West African CFA Franc', 'symbol' => 'CFA', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],
            ['code' => 'XPF', 'name' => 'CFP Franc', 'symbol' => 'F', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],

            // Y
            ['code' => 'YER', 'name' => 'Yemeni Rial', 'symbol' => '﷼', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 0],

            // Z
            ['code' => 'ZAR', 'name' => 'South African Rand', 'symbol' => 'R', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'ZMW', 'name' => 'Zambian Kwacha', 'symbol' => 'ZK', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'ZWL', 'name' => 'Zimbabwean Dollar', 'symbol' => 'Z$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // Additional/Alternative Currencies
            ['code' => 'GGP', 'name' => 'Guernsey Pound', 'symbol' => '£', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'IMP', 'name' => 'Isle of Man Pound', 'symbol' => '£', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'JEP', 'name' => 'Jersey Pound', 'symbol' => '£', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
            ['code' => 'TVD', 'name' => 'Tuvaluan Dollar', 'symbol' => '$', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],

            // Cryptocurrencies (Optional - for modern fleet management)
            // ['code' => 'BTC', 'name' => 'Bitcoin', 'symbol' => '₿', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 8],
            // ['code' => 'ETH', 'name' => 'Ethereum', 'symbol' => 'Ξ', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 8],
            // ['code' => 'USDT', 'name' => 'Tether', 'symbol' => '₮', 'decimal_mark' => '.', 'thousands_separator' => ',', 'decimal_places' => 2],
        ];


        foreach ($currencies as $currency) {
            Currency::create($currency);
        }

        $this->command->info('Currencies seeded successfully!');
    }
}
