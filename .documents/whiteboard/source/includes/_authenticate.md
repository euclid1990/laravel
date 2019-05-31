# Authenticate

## Login

> Request API Params

```json
{
    "email": "admin@example.com",
    "password": "123456"
}
```

> Response 200

```json
{
    "data": {
        "token_type": "Bearer",
        "expires_in": 31622400,
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjNm...",
        "refresh_token": "def502002c5f165fbe17bfb2bcd4687fe65be2c2d00ed9187..."
    },
    "message": "",
    "errors": {},
    "code": 200
}
```

> Response 401

```json
{
    "code": 401,
    "data": null,
    "message": "These credentials do not match our records.",
    "errors": {}
}
```

> Response 422

```json
{
    "code": 422,
    "data": null,
    "message": "",
    "errors": {
        "email": [
            "The email must be a valid email address."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```
**HTTP Request**
`POST https://localhost:8443/api/v1/login`

Header | Example
--------- | -------
Content-Type | application/json
Accept | application/json

Params | Type | Required
--------- | ------- | -------
email | string | yes
password | string| yes

Error Code | Meaning
---------- | -------
401 | Unauthorized -- Your API key is wrong
500 | Internal Server Error -- We had a problem with our server. Try again later.

## Logout

> Response 200

```json
{
    "data": null,
    "message": "Logout success.",
    "errors": {},
    "code": 200
}
```

**HTTP Request**
`POST https://localhost:8443/api/v1/logout`

Header | Example
--------- | -------
Content-Type | application/json
Accept | application/json
Authorization | Bearer {access_token}

Params | Type | Example | Required
--------- | ------- | -------
token | string | def50200ba6953989c914568d4... | yes

Error Code | Meaning
---------- | -------
401 | Unauthorized -- Your API key is wrong
500 | Internal Server Error -- We had a problem with our server. Try again later.

## Register

> Response 200

```json
{
    "data": {
        "token_type": "Bearer",
        "expires_in": 31622400,
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJS...",
        "refresh_token": "def50200b9d221f86af81798f82bd3d..."
    },
    "message": "",
    "errors": {},
    "code": 200
}
```
> Response 422

```json
{
    "code": 422,
    "data": null,
    "message": "",
    "errors": {
        "name": [
            "The name field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

**HTTP Request**
`POST https://localhost:8443/api/v1/register`

Header | Example
--------- | -------
Content-Type | application/json
Accept | application/json

Params | Type | Example | Required
--------- | ------- | -------

Error Code | Meaning
---------- | -------
422 | Validation
500 | Internal Server Error -- We had a problem with our server. Try again later.

## Send Email Reset Password

> Response 200

```json
{
    "data": true,
    "message": "We have e-mailed your password reset link!",
    "errors": {},
    "code": 200
}
```


**HTTP Request**
`POST https://localhost:8443/api/v1/password/email`

Header | Example
--------- | -------
Content-Type | application/json
Accept | application/json

Params | Type | Example | Required
--------- | ------- | -------

Error Code | Meaning
---------- | -------
500 | Internal Server Error -- We had a problem with our server. Try again later.

## Reset Password

> Response 200

```json
{
    "data": true,
    "message": "Your password has been reset!",
    "errors": {},
    "code": 200
}
```

> Response 422

```json
{
    "code": 422,
    "data": null,
    "message": "",
    "errors": {
        "token": [
            "This password reset token is invalid."
        ]
    }
}
```

**HTTP Request**
`POST https://localhost:8443/api/v1/password/reset`

Header | Example
--------- | -------
Content-Type | application/json
Accept | application/json

Params | Type | Example | Required
--------- | ------- | -------

Error Code | Meaning
---------- | -------
500 | Internal Server Error -- We had a problem with our server. Try again later.

## Refresh Token

> Response 200

```json
{
    "data": {
        "token_type": "Bearer",
        "expires_in": 31622400,
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImIyZTBlNTZjNmMwMzg...",
        "refresh_token": "def50200dabd06deba0d3355ec62f5d2b96501cf915b2c4dce673ba45c112c2dea..."
    },
    "message": "",
    "errors": {},
    "code": 200
}
```

> Response 401

```json
{
    "code": 401,
    "data": null,
    "message": "These credentials do not match our records.",
    "errors": {}
}
```

**HTTP Request**
`POST https://localhost:8443/api/v1/token/refresh`

Header | Example
--------- | -------
Content-Type | application/json
Accept | application/json

Params | Type | Example | Required
--------- | ------- | -------

Error Code | Meaning
---------- | -------
500 | Internal Server Error -- We had a problem with our server. Try again later.
