# TemporaryFilesystem

TemporaryFilesystem propose an API for temprary filesystem based on [Symfony
Filesystem Component](https://github.com/symfony/filesystem).

[![Build Status](https://travis-ci.org/romainneutron/Temporary-Filesystem.png?branch=master)](https://travis-ci.org/romainneutron/Temporary-Filesystem)

## Usage

```php
use Neutron\TemporaryFilesystem\TemporaryFilesystem;

$fs = TemporaryFilesystem::create();
```

## API Examples :

### CreateTemporaryDirectory

CreateTemporaryDirectory creates a temporary directory with an optional mode :

```php
$tempDir = $fs->createTemporaryDirectory($mode = 0755);
```

### CreateTemporaryFile

CreateTemporaryFile creates an empty files in the temporary folder:

```php
$fs->createTemporaryFile();

// return an empty temporary files with a "thumb-"
// prefix, '.dcm' as suffix and 'jpg' as extension
$fs->createTemporaryFile('thumb-', '.dcm', 'jpg');
```

### CreateTemporaryFiles

CreateTemporaryFiles creates a set of empty files in the temporary folder:

```php
// return an array of 5 path to temporary files
$fs->createTemporaryFiles(5);

// return an array of 5 path to empty temporary files with a "thumb-"
// prefix, '.dcm' as suffix and 'jpg' as extension
$fs->createTemporaryFiles(20, 'thumb-', '.dcm', 'jpg');
```

This method is useful when dealing with libraries which encode images
depending on the filename extension.

### CreateEmptyFile

CreateEmptyFile creates an empty file in the specified folder:

```php
// return a path to an empty file inside the current working directory
$fs->createEmptyFile(getcwd());

// return a path to an empty file in the "/home/romain" directory. The file
// has "original." as prefix, ".raw" as suffix and "CR2" as extension.
$fs->createEmptyFile("/home/romain", 'original.', '.raw', 'CR2');
```

This method is particularly useful when dealing with concurrent process
writing in the same directory.

# License

Released under the MIT license
