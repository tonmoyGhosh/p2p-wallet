## Instructions To Run App

- Clone app https://github.com/tonmoyGhosh/p2p-wallet.git.
- Run this commands.
    - composer install
    - php artisan migrate
    - php artisan db:seed
    - php artisan passport:install

## API End Points

- {base_url}/api/v1/login
    - Type: POST
    - Required: email
    - Required: password

- {base_url}/api/v1/logout
    - Type: GET
    - Required: apiToken (apiToken assigned to each API account used to authenticate with the API)

