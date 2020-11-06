# İleti Yönetim Sistemi PHP API

iys.org.tr PHP API Sınıfıdır.

#### AUTH
```php
IYS::auth($username, $password);
```
`$username`: IYS API Kullanıcı Adı
`$password`: IYS API Parolası
---

#### DİĞER PARAMETRELER
```php
IYS::get(IYS::ERROR); // Alınan son hatayı getirir.
IYS::get(IYS::ERROR_DESC); // Alınan son hata açıklamasını getirir.

IYS::get(IYS::REQUEST); // Yapılan son isteği getirir.
IYS::get(IYS::REQUEST_URL); // Yapılan son istek url adresini getirir.

IYS::get(IYS::RESULT); // Yapılan son istek cevabını adresini getirir.
IYS::get(IYS::RESULT_CODE); // Yapılan son istek cevabının http kodunu getirir.
```
