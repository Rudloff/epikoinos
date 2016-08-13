# Epíkoinos
Convert French words to their epicene form

## Usage

### Setup
```php
require_once __DIR__.'/vendor/autoload.php';
use Epíkoinos\Converter;
$converter = new Converter();
```

### Convert a single word
```php
$converter->convertWord('étudiante'); //étudiant.e
```

### Convert a text
```php
$converter->convert('Étudiants et professeurs'); //Étudiant.e.s et professeur.e.s
```

### Use a custom delimiter
```php
$converter = new Converter('-');
$converter->convertWord('étudiante'); //étudiant-e
```
