# Struktura plików

- `Makefile` skrypty pomocniczne, jeśli projekt jest uruchamiany na windowsie to można zignorować
- `.gitlab-ci.yml` plik gitlab CI
- `docker/` pliki konfiguracyjne dockera
- `docker/compose.yml` plik docker compose do budowania kontenerów
- `app/` projekt Laravela
- `app/database/migrations/` migracje Laravela
- `app/tests` testy
- `app/storage/logs/laravel.log` plik zapisu logów (z importu pliku csv)
- `app/app` główny kod backendu aplikacji, podzielony na foldery
- `app/resources` kod frontendu - vue, ts, css i główny widok app.blade.php


# Użyte paczki

## Backend
- `tymon/jwt-auth` - uwierzytelnienie jwt
- `league/csv` - obsługa pliku csv (w aplikacji zrobiony adapter wtyczki)

## Frontend
- `vue 3` - composition api
- `typescript`
- `vuetify`
- `pinia` - przechowywanie aktualnie zalogowanego użytkownika + jwt w localStorage
- `vue-router` - spa

# Instalacja

Jeśli aplikacja jest uruchamiana na Ubuntu/Debian to powinien zadziałać automatyczny skrypt z Makefile.
W systemie musi być `docker compose` i skrypt `make`. Wtedy wystarczy wpisać (bez sudo, jak poprosi o hasło dopiero wtedy podać):

`make install`

Polecenie zbuduje kontenery i wykona inne niezbędne czynności.
Jeśli to nie zadziała lub jest uruchamiane na windowsie, wtedy trzeba wykonać:

1. Zbudowanie kontenerów dockera na podstawie pliku `docker/compose.yml`
2. Przekopiowanie `app/.env.example` do `app/.env`
3. Uruchomienie kontnerów
4. Uruchomienie wewnątrz kontenera php-fpm poleceń:
   - `composer install`
   - `php artisan key:generate`
   - `php artisan jwt:secret`
   - `php artisan migrate`
5. Uruchomienie wewnątrz kontenera node polecenia:
   - `npm install`

Aplikacja powinna być dostępna pod adresem:

`http://localhost:80` lub `http://127.0.0.1:80`

# Import pliku CSV

Plik musi się znajdować wewnątrz folderu `app/` (kontener nie ma kontekstu na cały system)
W kontenerze `php-fpm` trzeba wpisać:

`php artisan import:csv {pathToFile}`

Upewnić się że działa połączenie z bazą (plik .env)
Informacje są logowane do `app/storage/logs/laravel.log` + wyświetlane na ekran konsoli

# Gitlab CI

Po wrzuceniu projektu do repo gitlaba on sam znajdzie plik `.gitlab-ci.yml` i domyślnie pipeline będzie uruchamiany 
przy każdym mergu/commicie do mastera. Testowałem na swoim koncie i każdy job działa. Nie robiłem tylko wypychania 
kontenerów do rejestru, ale robi się to za pomocą `docker push` gdzie potem na serwerze produkcyjnym można ustawić `image`
na kontenery z tego rejestru aby były zaciągane automatycznie.
