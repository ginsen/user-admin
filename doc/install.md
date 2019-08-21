# Install

## Project download
```bash
$ git clone https://github.com/ginsen/user-admin.git
$ cd user-admin/
```

## Configure environment vars
Copy `.env` to `.env.local` and replaces with your custom params, such as **DATABASE_URL**.

```bash
$ cp .env .env.local
```
> Replace **your_secret_token** and **your_secret_passphrase** with valid tokens, use 
`php -r 'echo md5(random_bytes(12)) . "\n";'` for generate both secret tokens.

## Install vendors
```bash
$ make install
```

## Generate MySQL data base.
```bash
$ ./bin/console doctrine:database:create
$ ./bin/console doctrine:schema:create
```

## Generate Event Store
```bash
$ ./bin/console broadway:event-store:schema:init
```

## Configure lexik-jwt-authentication

```bash
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

Change permissions for `private.pem` **only in develop environment**.
```bash
$ chmod 644 config/jwt/private.pem
```
