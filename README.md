# mac-vendor-lookup

<p>This PHP package allows you to get the information related to an <abbr title="Institute of Electrical and Electronics Engineers">IEEE</abbr> <abbr title="Organizationally Unique Identifier">OUI</abbr> assignment from a Laravel application.</p>

<p>The <strong>mac-resolver</strong> package downloads the OUI mapping information in <abbr title="Comma Separated Values">CSV</abbr> format from the IEEE website. Then it processes the CSV files and later stores the OUI assignment details in tables in the database.</p>

## Install

You can install the package via [composer](https://getcomposer.org/):

```bash
composer require sealution/mac-resolver
```

Then publish the assets with this command:

```bash
php artisan mac:install
```

After publishing the assets (config & migrations), run `artisan migrate`:

```bash
php artisan migrate
```

## Usage

### Get MAC Address details

You can use "php artisan mac: details <mac-address>" in the console to get the vendor details as well as the OUI assignment details.

```bash
php artisan mac:details  <mac-address>
```

```bash
php artisan mac:details  00-15-5D-81-E0-B0
```

```text
 Vendor details
 ------------- ---------------------------------------------
  OUI           00155D
  MAC Address   00-15-5D-81-E0-B0
  Vendor        Microsoft Corporation
  Address       One Microsoft Way Redmond WA US 98052-8300
  Is Private    false
 ------------- ---------------------------------------------

 Block details
 ------------------- -------------------
  Registry            MA-L
  Assignment bits     2^24
  Block Size          16,777,216
  Lower MAC Address   00:15:5D:00:00:00
  Upper MAC Address   00:15:5D:FF:FF:FF
  Last Update         Unknown
 ------------------- -------------------

 MAC Address details
 --------------------- ----------------------------------------
  MAC Address           00-15-5D-81-E0-B0
  Administration byte   UAA (Universally Administered Address)
  Group byte            Individual address
  Virtual Machine       true
  Is Multicast          false
  Is Unicast            false
  Is Valid              true
 --------------------- ----------------------------------------
```

### Get vendor details

You can use "php artisan mac:vendor <mac-address>" in the console to get the vendor details associated with the OUI assignment.

```bash
php artisan mac:vendor <mac-address>
```

```bash
php artisan mac:vendor 00-15-5D-81-E0-B0
```

```text
 ------------- -----------------------
  OUI           00155D
  MAC Address   00-15-5D-81-E0-B0
  Vendor        Microsoft Corporation
  Registry      MA-L
 ------------- -----------------------
```

## Security Vulnerabilities

If you discover any security related issues, please open an issue on the GitHub repository(

## Credits

- [Angel Campos](https://github.com/angelcamposm)
- [Anthony Vancauwenberghe](https://github.com/anthonyvancauwenberghe)

## License

The package Ping is open-source package and is licensed under The MIT License (MIT). Please see [License File](LICENSE.md) for more information.