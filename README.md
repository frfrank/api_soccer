# README #

## Instalación ##

- `symfony composer install`
- `symfony composer lexik:jwt:generate-keypair` // generar claves de encriptación *1
- `yarn install`
- `yarn watch`
- `symfony console d:m:m` // migrations
- `symfony console d:f:l` // fixtures


## Acceso ##
usuario: admin@admin.com
contraseña: 1234



## Acciones de Usuario ##

#### Recuperar contraseña ####

1. Desde VUE llamada al API  
`POST /api/user/password/recover`  
`{"email": "user@user.com"}`

2. Le llega al usuario un email con un enlace.  
Al pulsar el enlace accede a una URL de VUE con un código como parámetro.
`/user/password/recover/{code}`

3. Desde VUE se hace una llamada al API con ese código para comprobar que corresponde a un usuario   
`POST /api/user/password/check`  
`{"code": "..."}`  
Recibe los datos del usuario. 

4. Una vez que se confirma que es un usuario válido se envía la contraseña junto al código como validación:  
`POST /api/user/password/update`   
`{"code": "...", "password": "..."}`



## Posibles problemas ##
### 1. Error al generar las claves de encriptación ###
- Descarga Openssl: https://www.openssl.org/source/ o https://kb.firedaemon.com/support/solutions/articles/4000121705
Help CenterHelp Center
- Descomprime en C:\openssl
- En CMD ejecuta: `set OPENSSL_CONF=C:\openssl\ssl\openssl.cnf`
- `symfony composer lexik:jwt:generate-keypair`
 