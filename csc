[1mdiff --git a/.gitignore b/.gitignore[m
[1mindex e1e0eee..968ac43 100644[m
[1m--- a/.gitignore[m
[1m+++ b/.gitignore[m
[36m@@ -7,12 +7,10 @@[m
 /public/fontawesome[m
 /public/build[m
 /public/build/*[m
[31m-/database/*.sqlite[m
 /storage/*.key[m
 /vendor[m
 .env[m
 .env.backup[m
[31m-/database/*[m
 .phpunit.result.cache[m
 docker-compose.override.yml[m
 Homestead.json[m
[1mdiff --git a/app/Http/Controllers/ProfileController.php b/app/Http/Controllers/ProfileController.php[m
[1mindex 1a6657c..a20b924 100644[m
[1m--- a/app/Http/Controllers/ProfileController.php[m
[1m+++ b/app/Http/Controllers/ProfileController.php[m
[36m@@ -24,13 +24,6 @@[m [mclass ProfileController extends Controller[m
         return view('profile.index')->with(['user' => $user]);[m
     }[m
 [m
[31m-    public function toggleTheme()[m
[31m-    {[m
[31m-        Auth::user()->toggleTheme();[m
[31m-[m
[31m-        return redirect()->route('profile.index');[m
[31m-    }[m
[31m-[m
     public function destroy()[m
     {[m
         $user = Auth::user();[m
[1mdiff --git a/app/Providers/AppServiceProvider.php b/app/Providers/AppServiceProvider.php[m
[1mindex 7a034e9..25656ae 100644[m
[1m--- a/app/Providers/AppServiceProvider.php[m
[1m+++ b/app/Providers/AppServiceProvider.php[m
[36m@@ -24,7 +24,6 @@[m [mclass AppServiceProvider extends ServiceProvider[m
      * @return void[m
      */[m
 [m
[31m-[m
     public function boot(UrlGenerator $url)[m
     {[m
         if (env('APP_ENV') == 'production') {[m
[1mdiff --git a/composer.lock b/composer.lock[m
[1mdeleted file mode 100644[m
[1mindex 2319443..0000000[m
[1m--- a/composer.lock[m
[1m+++ /dev/null[m
[36m@@ -1,8199 +0,0 @@[m
[31m-{[m
[31m-    "_readme": [[m
[31m-        "This file locks the dependencies of your project to a known state",[m
[31m-        "Read more about it at https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies",[m
[31m-        "This file is @generated automatically"[m
[31m-    ],[m
[31m-    "content-hash": "207774e80d75cce838de22280dcd791f",[m
[31m-    "packages": [[m
[31m-        {[m
[31m-            "name": "brick/math",[m
[31m-            "version": "0.11.0",[m
[31m-            "source": {[m
[31m-                "type": "git",[m
[31m-                "url": "https://github.com/brick/math.git",[m
[31m-                "reference": "0ad82ce168c82ba30d1c01ec86116ab52f589478"[m
[31m-            },[m
[31m-            "dist": {[m
[31m-                "type": "zip",[m
[31m-                "url": "https://api.github.com/repos/brick/math/zipball/0ad82ce168c82ba30d1c01ec86116ab52f589478",[m
[31m-                "reference": "0ad82ce168c82ba30d1c01ec86116ab52f589478",[m
[31m-                "shasum": ""[m
[31m-            },[m
[31m-            "require": {[m
[31m-                "php": "^8.0"[m
[31m-            },[m
[31m-            "require-dev": {[m
[31m-                "php-coveralls/php-coveralls": "^2.2",[m
[31m-                "phpunit/phpunit": "^9.0",[m
[31m-                "vimeo/psalm": "5.0.0"[m
[31m-            },[m
[31m-            "type": "library",[m
[31m-            "autoload": {[m
[31m-                "psr-4": {[m
[31m-                    "Brick\\Math\\": "src/"[m
[31m-                }[m
[31m-            },[m
[31m-            "notification-url": "https://packagist.org/downloads/",[m
[31m-            "license": [[m
[31m-                "MIT"[m
[31m-            ],[m
[31m-            "description": "Arbitrary-precision arithmetic library",[m
[31m-            "keywords": [[m
[31m-                "Arbitrary-precision",[m
[31m-                "BigInteger",[m
[31m-                "BigRational",[m
[31m-                "arithmetic",[m
[31m-                "bigdecimal",[m
[31m-                "bignum",[m
[31m-                "brick",[m
[31m-                "math"[m
[31m-            ],[m
[31m-            "support": {[m
[31m-                "issues": "https://github.com/brick/math/issues",[m
[31m-                "source": "https://github.com/brick/math/tree/0.11.0"[m
[31m-            },[m
[31m-            "funding": [[m
[31m-                {[m
[31m-                    "url": "https://github.com/BenMorel",[m
[31m-                    "type": "github"[m
[31m-                }[m
[31m-            ],[m
[31m-            "time": "2023-01-15T23:15:59+00:00"[m
[31m-        },[m
[31m-        {[m
[31m-            "name": "carbonphp/carbon-doctrine-types",[m
[31m-            "version": "2.1.0",[m
[31m-            "source": {[m
[31m-                "type": "git",[m
[31m-                "url": "https://github.com/CarbonPHP/carbon-doctrine-types.git",[m
[31m-                "reference": "99f76ffa36cce3b70a4a6abce41dba15ca2e84cb"[m
[31m-            },[m
[31m-            "dist": {[m
[31m-                "type": "zip",[m
[31m-                "url": "https://api.github.com/repos/CarbonPHP/carbon-doctrine-types/zipball/99f76ffa36cce3b70a4a6abce41dba15ca2e84cb",[m
[31m-                "reference": "99f76ffa36cce3b70a4a6abce41dba15ca2e84cb",[m
[31m-                "shasum": ""[m
[31m-            },[m
[31m-            "require": {[m
[31m-                "php": "^7.4 || ^8.0"[m
[31m-            },[m
[31m-            "conflict": {[m
[31m-                "doctrine/dbal": "<3.7.0 || >=4.0.0"[m
[31m-            },[m
[31m-            "require-dev": {[m
[31m-                "doctrine/dbal": "^3.7.0",[m
[31m-                "nesbot/carbon": "^2.71.0 || ^3.0.0",[m
[31m-                "phpunit/phpunit": "^10.3"[m
[31m-            },[m
[31m-            "type": "library",[m
[31m-            "autoload": {[m
[31m-                "psr-4": {[m
[31m-                    "Carbon\\Doctrine\\": "src/Carbon/Doctrine/"[m
[31m-                }[m
[31m-            },[m
[31m-            "notification-url": "https://packagist.org/downloads/",[m
[31m-            "license": [[m
[31m-                "MIT"[m
[31m-            ],[m
[31m-            "authors": [[m
[31m-                {[m
[31m-                    "name": "KyleKatarn",[m
[31m-                    "email": "kylekatarnls@gmail.com"[m
[31m-                }[m
[31m-            ],[m
[31m-            "description": "Types to use Carbon in Doctrine",[m
[31m-            "keywords": [[m
[31m-                "carbon",[m
[31m-                "date",[m
[31m-                "datetime",[m
[31m-                "doctrine",[m
[31m-                "time"[m
[31m-            ],[m
[31m-            "support": {[m
[31m-                "issues": "https://github.com/CarbonPHP/carbon-doctrine-types/issues",[m
[31m-                "source": "https://github.com/CarbonPHP/carbon-doctrine-types/tree/2.1.0"[m
[31m-            },[m
[31m-            "funding": [[m
[31m-                {[m
[31m-                    "url": "https://github.com/kylekatarnls",[m
[31m-                    "type": "github"[m
[31m-                },[m
[31m-                {[m
[31m-                    "url": "https://opencollective.com/Carbon",[m
[31m-                    "type": "open_collective"[m
[31m-                },[m
[31m-                {[m
[31m-                    "url": "https://tidelift.com/funding/github/packagist/nesbot/carbon",[m
[31m-                    "type": "tidelift"[m
[31m-                }[m
[31m-            ],[m
[31m-            "time": "2023-12-11T17:09:12+00:00"[m
[31m-        },[m
[31m-        {[m
[31m-            "name": "dflydev/dot-access-data",[m
[31m-            "version": "v3.0.2",[m
[31m-            "source": {[m
[31m-                "type": "git",[m
[31m-                "url": "https://github.com/dflydev/dflydev-dot-access-data.git",[m
[31m-                "reference": "f41715465d65213d644d3141a6a93081be5d3549"[m
[31m-            },[m
[31m-            "dist": {[m
[31m-                "type": "zip",[m
[31m-                "url": "https://api.github.com/repos/dflydev/dflydev-dot-access-data/zipball/f41715465d65213d644d3141a6a93081be5d3549",[m
[31m-                "reference": "f41715465d65213d644d3141a6a93081be5d3549",[m
[31m-                "shasum": ""[m
[31m-            },[m
[31m-            "require": {[m
[31m-                "php": "^7.1 || ^8.0"[m
[31m-            },[m
[31m-            "require-dev": {[m
[31m-                "phpstan/phpstan": "^0.12.42",[m
[31m-                "phpunit/phpunit": "^7.5 || ^8.5 || ^9.3",[m
[31m-                "scrutinizer/ocular": "1.6.0",[m
[31m-                "squizlabs/php_codesniffer": "^3.5",[m
[31m-                "vimeo/psalm": "^4.0.0"[m
[31m-            },[m
[31m-            "type": "library",[m
[31m-            "extra": {[m
[31m-                "branch-alias": {[m
[31m-                    "dev-main": "3.x-dev"[m
[31m-                }[m
[31m-            },[m
[31m-            "autoload": {[m
[31m-                "psr-4": {[m
[31m-                    "Dflydev\\DotAccessData\\": "src/"[m
[31m-                }[m
[31m-            },[m
[31m-            "notification-url": "https://packagist.org/downloads/",[m
[31m-            "license": [[m
[31m-                "MIT"[m
[31m-            ],[m
[31m-            "authors": [[m
[31m-                {[m
[31m-                    "name": "Dragonfly Development Inc.",[m
[31m-                    "email": "info@dflydev.com",[m
[31m-                    "homepage": "http://dflydev.com"[m
[31m-                },[m
[31m-                {[m
[31m-                    "name": "Beau Simensen",[m
[31m-                    "email": "beau@dflydev.com",[m
[31m-                    "homepage": "http://beausimensen.com"[m
[31m-                },[m
[31m-                {[m
[31m-                    "name": "Carlos Frutos",[m
[31m-                    "email": "carlos@kiwing.it",[m
[31m-                    "homepage": "https://github.com/cfrutos"[m
[31m-                },[m
[31m-                {[m
[31m-                    "name": "Colin O'Dell",[m
[31m-                    "email": "colinodell@gmail.com",[m
[31m-                    "homepage": "https://www.colinodell.com"[m
[31m-                }[m
[31m-            ],[m
[31m-            "description": "Given a deep data structure, access data by dot notation.",[m
[31m-            "homepage": "https://github.com/dflydev/dflydev-dot-access-data",[m
[31m-            "keywords": [[m
[31m-                "access",[m
[31m-                "data",[m
[31m-                "dot",[m
[31m-                "notation"[m
[31m-            ],[m
[31m-            "support": {[m
[31m-                "issues": "https://github.com/dflydev/dflydev-dot-access-data/issues",[m
[31m-                "source": "https://github.com/dflydev/dflydev-dot-access-data/tree/v3.0.2"[m
[31m-            },[m
[31m-            "time": "2022-10-27T11:44:00+00:00"[m
[31m-        },[m
[31m-        {[m
[31m-            "name": "doctrine/inflector",[m
[31m-            "version": "2.0.10",[m
[31m-            "source": {[m
[31m-                "type": "git",[m
[31m-                "url": "https://github.com/doctrine/inflector.git",[m
[31m-                "reference": "5817d0659c5b50c9b950feb9af7b9668e2c436bc"[m
[31m-            },[m
[31m-            "dist": {[m
[31m-                "type": "zip",[m
[31m-                "url": "https://api.github.com/repos/doctrine/inflector/zipball/5817d0659c5b50c9b950feb9af7b9668e2c436bc",[m
[31m-                "reference": "5817d0659c5b50c9b950feb9af7b9668e2c436bc",[m
[31m-                "shasum": ""[m
[31m-            },[m
[31m-            "require": {[m
[31m-                "php": "^7.2 || ^8.0"[m
[31m-            },[m
[31m-            "require-dev": {[m
[31m-                "doctrine/coding-standard": "^11.0",[m
[31m-                "phpstan/phpstan": "^1.8",[m
[31m-                "phpstan/phpstan-phpunit": "^1.1",[m
[31m-                "phpstan/phpstan-strict-rules": "^1.3",[m
[31m-                "phpunit/phpunit": "^8.5 || ^9.5",[m
[31m-                "vimeo/psalm": "^4.25 || ^5.4"[m
[31m-            },[m
[31m-            "type": "library",[m
[31m-            "autoload": {[m
[31m-                "psr-4": {[m
[31m-                    "Doctrine\\Inflector\\": "lib/Doctrine/Inflector"[m
[31m-                }[m
[31m-            },[m
[31m-            "notification-url": "https://packagist.org/downloads/",[m
[31m-            "license": [[m
[31m-                "MIT"[m
[31m-            ],[m
[31m-            "authors": [[m
[31m-                {[m
[31m-                    "name": "Guilherme Blanco",[m
[31m-                    "email": "guilhermeblanco@gmail.com"[m
[31m-                },[m
[31m-                {[m
[31m-                    "name": "Roman Borschel",[m
[31m-                    "email": "roman@code-factory.org"[m
[31m-                },[m
[31m-                {[m
[31m-                    "name": "Benjamin Eberlei",[m
[31m-                    "email": "kontakt@beberlei.de"[m
[31m-                },[m
[31m-                {[m
[31m-                    "name": "Jonathan Wage",[m
[31m-                    "email": "jonwage@gmail.com"[m
[31m-                },[m
[31m-                {[m
[31m-                    "name": "Johannes Schmitt",[m
[31m-                    "email": "schmittjoh@gmail.com"[m
[31m-                }[m
[31m-            ],[m
[31m-            "description": "PHP Doctrine Inflector is a small library that can perform string manipulations with regard to upper/lowercase and singular/plural forms of words.",[m
[31m-            "homepage": "https://www.doctrine-project.org/projects/inflector.html",[m
[31m-            "keywords": [[m
[31m-                "inflection",[m
[31m-                "inflector",[m
[31m-                "lowercase",[m
[31m-                "manipulation",[m
[31m-                "php",[m
[31m-                "plural",[m
[31m-                "singular",[m
[31m-                "strings",[m
[31m-                "uppercase",[m
[31m-                "words"[m
[31m-            ],[m
[31m-            "support": {[m
[31m-                "issues": "https://github.com/doctrine/inflector/issues",[m
[31m-                "source": "https://github.com/doctrine/inflector/tree/2.0.10"[m
[31m-            },[m
[31m-            "funding": [[m
[31m-                {[m
[31m-                    "url": "https://www.doctrine-project.org/sponsorship.html",[m
[31m-                    "type": "custom"[m
[31m-                },[m
[31m-                {[m
[31m-                    "url": "https://www.patreon.com/phpdoctrine",[m
[31m-                    "type": "patreon"[m
[31m-                },[m
[31m-                {[m
[31m-                    "url": "https://tidelift.com/funding/github/packagist/doctrine%2Finflector",[m
[31m-                    "type": "tidelift"[m
[31m-                }[m
[31m-            ],[m
[31m-            "time": "2024-02-18T20:23:39+00:00"[m
[31m-        },[m
[31m-        {[m
[31m-            "name": "doctrine/lexer",[m
[31m-            "version": "3.0.1",[m
[31m-            "source": {[m
[31m-                "type": "git",[m
[31m-                "url": "https://github.com/doctrine/lexer.git",[m
[31m-                "reference": "31ad66abc0fc9e1a1f2d9bc6a42668d2fbbcd6dd"[m
[31m-            },[m
[31m-            "dist": {[m
[31m-                "type": "zip",[m
[31m-                "url": "https://api.github.com/repos/doctrine/lexer/zipball/31ad66abc0fc9e1a1f2d9bc6a42668d2fbbcd6dd",[m
[31m-                "reference": "31ad66abc0fc9e1a1f2d9bc6a42668d2fbbcd6dd",[m
[31m-                "shasum": ""[m
[31m-            },[m
[31m-            "require": {[m
[31m-                "php": "^8.1"[m
[31m-            },[m
[31m-            "require-dev": {[m
[31m-                "doctrine/coding-standard": "^12",[m
[31m-                "phpstan/phpstan": "^1.10",[m
[31m-                "phpunit/phpunit": "^10.5",[m
[31m-                "psalm/plugin-phpunit": "^0.18.3",[m
[31m-                "vimeo/psalm": "^5.21"[m
[31m-            },[m
[31m-            "type": "library",[m
[31m-            "autoload": {[m
[31m-                "psr-4": {[m
[31m-                    "Doctrine\\Common\\Lexer\\": "src"[m
[31m-                }[m
[31m-            },[m
[31m-            "notification-url": "https://packagist.org/downloads/",[m
[31m-            "license": [[m
[31m-                "MIT"[m
[31m-            ],[m
[31m-            "authors": [[m
[31m-                {[m
[31m-                    "name": "Guilherme Blanco",[m
[31m-                    "email": "guilhermeblanco@gmail.com"[m
[31m-                },[m
[31m-                {[m
[31m-                    "name": "Roman Borschel",[m
[31m-                    "email": "roman@code-factory.org"[m
[31m-                },[m
[31m-                {[m
[31m-                    "name": "Johannes Schmitt",[m
[31m-                    "email": "schmittjoh@gmail.com"[m
[31m-                }[m
[31m-            ],[m
[31m-            "description": "PHP Doctrine Lexer parser library that can be used in Top-Down, Recursive Descent Parsers.",[m
[31m-            "homepage": "https://www.doctrine-project.org/projects/lexer.html",[m
[31m-            "keywords": [[m
[31m-                "annotations",[m
[31m-                "docblock",[m
[31m-                "lexer",[m
[31m-                "parser",[m
[31m-                "php"[m
[31m-            ],[m
[31m-            "support": {[m
[31m-                "issues": "https://github.com/doctrine/lexer/issues",[m
[31m-                "source": "https://github.com/doctrine/lexer/tree/3.0.1"[m
[31m-            },[m
[31m-            "funding": [[m
[31m-                {[m
[31m-                    "url": "https://www.doctrine-project.org/sponsorship.html",[m
[31m-                    "type": "custom"[m
[31m-                },[m
[31m-                {[m
[31m-                    "url": "https://www.patreon.com/phpdoctrine",[m
[31m-                    "type": "patreon"[m
[31m-                },[m
[31m-                {[m
[31m-                    "url": "https://tidelift.com/funding/github/packagist/doctrine%2Flexer",[m
[31m-                    "type": "tidelift"[m
[31m-                }[m
[31m-            ],[m
[31m-            "time": "2024-02-05T11:56:58+00:00"[m
[31m-        },[m
[31m-        {[m
[31m-            "name": "dragonmantank/cron-expression",[m
[31m-            "version": "v3.3.3",[m
[31m-            "source": {[m
[31m-                "type": "git",[m
[31m-                "url": "https://github.com/dragonmantank/cron-expression.git",[m
[31m-                "reference": "adfb1f505deb6384dc8b39804c5065dd3c8c8c0a"[m
[31m-            },[m
[31m-            "dist": {[m
[31m-                "type": "zip",[m
[31m-                "url": "https://api.github.com/repos/dragonmantank/cron-expression/zipball/adfb1f505deb6384dc8b39804c5065dd3c8c8c0a",[m
[31m-                "reference": "adfb1f505deb6384dc8b39804c5065dd3c8c8c0a",[m
[31m-                "shasum": ""[m
[31m-            },[m
[31m-            "require": {[m
[31m-                "php": "^7.2|^8.0",[m
[31m-                "webmozart/assert": "^1.0"[m
[31m-            },[m
[31m-            "replace": {[m
[31m-                "mtdowling/cron-expression": "^1.0"[m
[31m-            },[m
[31m-            "require-dev": {[m
[31m-                "phpstan/extension-installer": "^1.0",[m
[31m-                "phpstan/phpstan": "^1.0",[m
[31m-                "phpstan/phpstan-webmozart-assert": "^1.0",[m
[31m-                "phpunit/phpunit": "^7.0|^8.0|^9.0"[m
[31m-            },[m
[31m-            "type": "library",[m
[31m-            "autoload": {[m
[31m-                "psr-4": {[m
[31m-                    "Cron\\": "src/Cron/"[m
[31m-                }[m
[31m-            },[m
[31m-            "notification-url": "https://packagist.org/downloads/",[m
[31m-            "license": [[m
[31m-                "MIT"[m
[31m-            ],[m
[