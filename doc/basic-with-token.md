# Делегирование авторизации на бэкенд если basic в nginx не пропустил

Иногда сайт закрыт basic авторизацией на уровне nginx и это мешает использовать авторизацию бэкенда,
потому что в nginx и бэкенд ожидают в заголовке Authorization разные значения.

Решить проблему можно используя комбинацию из satisfy any и auth_request.
Директива auth_request позволяет авторизовать текущий запрос, сделав ещё один запрос на бэкенд и получив от него код 200 или 401/403.

Для начала закроем авторизацией основной локейшен
```
location / {
    satisfy any;
    auth_basic "Authentication required";
    auth_basic_user_file /etc/nginx/conf.d/htpassword;
    auth_request /auth_request;

    try_files $uri $uri/ /basic-with-token.php?$query_string;
}
```
Далее определим локейшен `/auth_request`, который проксирует текущий запрос на бэкенд.
```
location /auth_request {
    internal;
    proxy_pass http://backend/auth;
    proxy_pass_request_body off;
    proxy_set_header Content-Length "";
}
```

> Почему-то с fastcgi_pass на свой же бэкенд эта схема не работает, и нужно делать proxy_pass на самого себя

В итоге nginx будет пускать и по basic и по токену.
Причём если в запросе есть basic, то запрос на бэкенд уже не делается.
```shell
curl http://basic-with-token.127.0.0.1.nip.io/ -I -u user:password
>> HTTP/1.1 200 OK
```

```shell
curl http://basic-with-token.127.0.0.1.nip.io/ -I -H 'Authorization: Bearer 123'
>> HTTP/1.1 200 OK
```