docker-compose exec products php artisan migrate:fresh
docker-compose exec ratings php artisan migrate:fresh
docker-compose exec warehouse php artisan migrate:fresh
docker-compose exec orders php artisan migrate:fresh
--------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
   