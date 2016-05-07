
#Laravel + bower#
https://mattstauffer.co/blog/convert-laravel-5-frontend-scaffold-to-bower

#bower packages#
## for fetch polifill (an improved XMLHttpRequest)
bower install fetch --save
bower install es6-promise --save
bower install lodash --save

#npm packages#
## for fetch polifill (an improved XMLHttpRequest)
npm install whatwg-fetch --save

## Removing from migration tables#
php artisan migrate

use test_ecolearnia;
delete from migrations where migration='2016_04_09_180431_create_activities_table';
delete from migrations where migration='2016_04_09_132402_create_assignments_table';
delete from migrations where migration='2016_04_09_124534_create_contents_table';
drop table activities;
drop table assignments;
drop table contents;

delete from activities;
delete from assignments;
delete from contents;

## Errors troubleshooting ##
ErrorException: preg_replace(): Parameter mismatch, pattern is a string while replacement is an array
==> If the field is JSON, make sure that the field name is properly listed in the $private-jsons


## PhpUnit ##
Run single test
phpunit --filter testEvaluateFieldsCorrect  DefaultEvaluatorTest tests/EcoLearnia/Modules/Assignment/Evaluation/DefaultEvaluatorTest.php
