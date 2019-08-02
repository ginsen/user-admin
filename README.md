# sf4-ddd-user-admin
Proof web with symfony 4 under DDD architecture for users administration.

# Event Store
```bash
$ ./bin/console broadway:event-store:schema:init
```

# lexik-jwt-authentication
```bash
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

Check permissions for `private.pem`.