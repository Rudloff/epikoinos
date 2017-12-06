# Epíkoinos

Convert French words to their epicene form

## Usage

### As a library

```php
require_once __DIR__.'/vendor/autoload.php';
use Epíkoinos\Converter;
$converter = new Converter();
```

#### Convert a single word

```php
$converter->convertWord('étudiant'); //['étudiant.e' => ['feminine' => 'étudiante', 'masculine' => 'étudiant', 'epicene' => 'étudiant.e']]
```

Note that it will always return an array (as there might be several possible conversions).

#### Convert a text

```php
$converter->convert('Étudiants et professeurs'); //Étudiant.e.s et professeur.e.s
```

#### Use a custom delimiter

```php
$converter = new Converter('-');
$converter->convertWord('étudiant'); //['étudiant-e' => ['feminine' => 'étudiante', 'masculine' => 'étudiant', 'epicene' => 'étudiant-e']]
```

#### Documentation

The complete library documentation is available at [epikoinos.surge.sh](https://epikoinos.surge.sh/namespaces/Ep%C3%ADkoinos.html).

### As a web app

Epíkoinos can also be used as a web app.
The official instance is hosted at [epikoinos.netlib.re](https://epikoinos.netlib.re/).

#### Setup

```bash
composer install
bower install
```
