
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
delete from migrations where migration='2016_04_09_180431_create_activities_table';
delete from migrations where migration='2016_04_09_132402_create_assignments_table';
delete from migrations where migration='2016_04_09_124534_create_contents_table';
drop table activities;
drop table assignments;
drop table contents;
