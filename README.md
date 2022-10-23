## Instructions To Run App

- Clone app https://github.com/tonmoyGhosh/p2p-wallet.git
- Run this commands.
    - composer install
        - After run composer install, if occured php version issue then run -> composer install --ignore-platform-reqs
    - Change .env file with db credentials
    - php artisan migrate
    - php artisan db:seed
    - php artisan passport:install
    - php artisan key:generate

## API End Points

- Login API
    - {base_url}/api/v1/login
    - Type: POST
    - Required: email
    - Required: password

- Login User Info API
    - {base_url}/api/v1/getLoginUserInfo
    - Type: GET
    - Required: apiToken (apiToken assigned to each API account used to authenticate with the API)

- Amount Sending User Lists API
    - {base_url}/api/v1/getUserList
    - Type: GET
    - Required: apiToken (apiToken assigned to each API account used to authenticate with the API)

- Send Amount P2P API
    - {base_url}/api/v1/sendMoney
    - Type: POST
    - Required: apiToken (apiToken assigned to each API account used to authenticate with the API)
    - Required: receive_user_id
    - Required: amount

- Stats Report API
    - {base_url}/api/v1/statsReport
    - Type: GET
    - Required: apiToken (apiToken assigned to each API account used to authenticate with the API)

- {base_url}/api/v1/logout
    - Type: GET
    - Required: apiToken (apiToken assigned to each API account used to authenticate with the API)

## Test User Credentials

- Email: doe@gmail.com
- Password: 123456

- Email: henry@gmail.com
- Password: 123456

