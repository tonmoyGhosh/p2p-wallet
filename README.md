## Instructions To Run App

- Clone app https://github.com/tonmoyGhosh/p2p-wallet.git
- Run this commands.
    - composer install
    - php artisan migrate
    - php artisan db:seed
    - php artisan passport:install

## API End Points

- Login Api
    - {base_url}/api/v1/login
    - Type: POST
    - Required: email
    - Required: password

- Login User Info Api
    - {base_url}/api/v1/getLoginUserInfo
    - Type: GET
    - Required: apiToken (apiToken assigned to each API account used to authenticate with the API)

- Amount Sending User Lists Api
    - {base_url}/api/v1/getUserList
    - Type: GET
    - Required: apiToken (apiToken assigned to each API account used to authenticate with the API)

- Send Amount P2P Api
    - {base_url}/api/v1/sendMoney
    - Type: POST
    - Required: apiToken (apiToken assigned to each API account used to authenticate with the API)
    - Required: receive_user_id
    - Required: amount

- {base_url}/api/v1/logout
    - Type: GET
    - Required: apiToken (apiToken assigned to each API account used to authenticate with the API)

## Test User Credentials

- Email: doe@gmail.com
- Password: 123456

- Email: henry@gmail.com
- Password: 123456

