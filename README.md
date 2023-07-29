# AdsTxt PHP Parser

## Table of Contents

- [Introduction](#introduction)
- [Installation](#installation)
- [Usage](#usage)
    - [Parsing AdsTxt from File](#parsing-adstxt-from-file)
    - [Parsing AdsTxt from String](#parsing-adstxt-from-string)
    - [Working with AdsTxt Instance](#working-with-adstxt-instance)
- [Available Line Types](#available-line-types)
    - [Vendor Line](#vendor-line)
    - [Variable Line](#variable-line)
    - [Comment Line](#comment-line)
    - [Invalid Line](#invalid-line)
    - [Blank Line](#blank-line)


## Introduction

AdsTxt PHP Parser is a library for processing and parsing content from ads.txt files and converting them into AdsTxt instances. It provides an easy-to-use interface to work with ads.txt data in PHP applications.

## Installation

You can install the library via Composer. Run the following command in your project directory:

```bash
composer require badraxas/adstxt
```

## Usage
### Parsing AdsTxt from File
```php
<?php

use Badraxas\Adstxt\AdsTxtParser;

try {
    $adsTxt = AdsTxtParser::fromFile('/path/to/ads.txt');
    // You can now work with the $adsTxt instance containing the parsed data.
} catch (\Badraxas\Adstxt\Exceptions\AdsTxtParser\FileOpenException $exception) {
    // Handle the file open exception here.
}
```

### Parsing AdsTxt from String
```php
<?php 

use Badraxas\Adstxt\AdsTxt;
use Badraxas\Adstxt\AdsTxtParser;

$adsTxtContent = <<<'EOD'
# Example ads.txt content
example.com, 123456, DIRECT, ABCD1234
domain.com, 987654, RESELLER
custom_variable=custom_value
# This is a comment
EOD;

$adsTxt = AdsTxtParser::fromString($adsTxtContent);
// Now you have an instance of AdsTxt containing the parsed data from the ads.txt string.
// You can use the $adsTxt object to perform various operations on the ads.txt data.
```

### Working with AdsTxt Instance

```php
<?php

use Badraxas\Adstxt\AdsTxt;
use Badraxas\Adstxt\Enums\AccountType;
use Badraxas\Adstxt\Lines\Vendor;
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Variable;

// Assuming $adsTxt is an instance of AdsTxt
$invalidLines = $adsTxt->getInvalidLines();
$isAdsTxtValid = $adsTxt->isValid();

// Add custom filtering using callback
$filteredAdsTxt = $adsTxt->filter(function ($line) {
    // Your custom filtering logic here
    return $line instanceof Vendor; // Return true if the line should be included, false otherwise
});

// Compare the current AdsTxt instance with another AdsTxt instance and return the lines that are missing.
     * in the other instance
$otherAdsTxt = AdsTxtParser::fromFile('/path/to/other_ads.txt');
$missingLines = $adsTxt->diff($otherAdsTxt);

// Create new AdsTxt and add lines
$newAdsTxt = new AdsTxt();
$newAdsTxt
    ->addLine(new Comment(' app-ads.txt file for vMVPD B:'))
    ->addLine(new Vendor('ssp.com', 'vwxyz', AccountType::DIRECT))
    ->addLine(new Variable('inventorypartnerdomain', 'programmerA.com'))

// display ads.txt as string
$newAdsTxt->__toString();
```

## Available Line Types
### Vendor Line

The Vendor line represents a line in the ads.txt file containing vendor information.

```php
<?php

use Badraxas\Adstxt\Lines\Vendor;
use Badraxas\Adstxt\Enums\AccountType;

// Creating a Vendor line instance
$vendorLine = new Vendor(
  domain: 'example.com',
  publisherId: '123456',
  accountType: AccountType::DIRECT,
  certificationId: 'ABCD1234',
  comment: null
);
```

### Variable Line

The Variable line represents a line in the ads.txt file containing a variable with its name and value.

```php
<?php

use Badraxas\Adstxt\Lines\Variable;

// Creating a Variable line instance
$variableLine = new Variable(
  name: 'custom_variable',
  value: 'custom_value',
  comment: null
);
```

### Comment Line

The Comment line represents a comment line in the ads.txt file.

```php
<?php

use Badraxas\Adstxt\Lines\Comment;

// Creating a Comment line instance
$commentLine = new Comment('This is a comment');
```
Note : you can associate a Comment instance to any instance of Vendor, Variable or Invalid.
```php
use Badraxas\Adstxt\Lines\Comment;
use Badraxas\Adstxt\Lines\Variable;

new Variable(
    'variable',
    'value',
    new Comment('This is a comment')
);
```

### Invalid Line

The Invalid line represents an invalid line in the ads.txt file.

```php
<?php

use Badraxas\Adstxt\Lines\Invalid;

// Creating an Invalid line instance
$invalidLine = new Invalid('Invalid content');
```

### Blank Line

The Blank line represent a blank line in the ads.txt.

```php
<?php

use Badraxas\Adstxt\Lines\Blank;

// Creating an Invalid line instance
$blankLine = new Blank();
```
