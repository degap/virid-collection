# Virid/Collection

[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.txt)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg?style=flat-square)](https://php.net/)

The collection interface and implementation based on array
 
## Installation
Add lines to composer.json
```json
...

"require": {
    "virid/collection": "~1.0"
}
...
```
or
use composer cli
```bash
composer require virid/collection
```
## Usage

Example with existed implementation

```php
use Virid\Collection\ArrayCollection;

$arrayCollection = new ArrayCollection();
$arrayCollection->addItem('message');
$arrayCollection->addItem(1);
$arrayCollection->addItem(new \StdClass());

foreach ($arrayCollection as $item)
{
    $this->processing($item);
}
```

Creating custom class implementing collection interface

```php
use Virid\Collection\CollectionInterface;

class DBCollection implements CollectionInterface
{
...
    /**
     * @return \Generator
     */
    public function getGenerator(): \Generator
    {
        while ($this->connection->valid()) {
            $current = $this->connection->current();
            $this->connection->next();
            yield $current;
        }
    }
...    
}

foreach ($dbCollection->getGenerator() as $row) {
    $this->processing($row);
}

```
