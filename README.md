# İleti Yönetim Sistemi (iys.org.tr) PHP API Client

iys.org.tr için PHP API Sınıfıdır.

### SETUP
```php
// For Pure PHP (You have to download the files first)
include('IYS.php'); 
$iys = EmreRed\IYS();

// For Candy PHP (No processing needed, you can use it directly. 😉)
$iys = Candy::plugin('EmreRed/IYS-PHP-API'); 
```
<a href="https://github.com/CandyPack/CandyPHP">🍭 Check out CandyPHP!</a>

<hr>

### ENTEGRATOR
#### AUTH
```php
$integrator = $iys->integrator($username, $password, $iyscode);
```
`$username`: IYS API Kullanıcı Adı *  
`$password`: IYS API Parolası *  
`$iyscode` : IYS Numarası  

---

#### BRAND
```php
$integrator->brand($iysCode);
```
`$iysCode`: Hizmet sağlayıcı İYS Numarası  
(Girilmezse yetkili tüm markalar listelenir)  

---

#### TEKİL İZİN EKLEME
```php
$integrator->consent($brandCode, $recipient, $type, $source, $status, $consentDate, $recipientType);
```
`$brandCode` *  
`$recipient` *  
`$type` *  
`$source` *  
`$status` *  
`$consentDate`  
`$recipientType`

---

#### ÇOKLU İZİN EKLEME (ASYNC)
```php
$integrator->consent($brandCode, $data);
```
`$brandCode` *  
`$data`:
```php
[
  [
    'recipient' => '',
    'type' => '',
    'source' => '',
    'status' => '',
    'consentDate' => ''
  ],
  .
  .
  .
]
```

---

#### ÇOKLU İZİN EKLEME DURUMU (ASYNC)
```php
$integrator->status($brandCode, $requestId);
```
`$brandCode` *  
`$requestId`: *

---

#### TEKİL İZİN SORGULAMA (ASYNC)
```php
$integrator->status($brandCode, $recipient, $recipientType, $type);
```
`$brandCode` *  
`$recipient`: *  
`$recipientType`: *  
`$type`: *  

---

#### İZİN HAREKETİ
```php
$integrator->changes($brandCode);
```
`$brandCode` *  

---

#### DİĞER PARAMETRELER
```php
$integrator->get(EmreRed\IYS::ERROR); // Alınan son hatayı getirir.
$integrator->get(EmreRed\IYS::ERROR_DESC); // Alınan son hata açıklamasını getirir.

$integrator->get(EmreRed\IYS::REQUEST); // Yapılan son isteği getirir.
$integrator->get(EmreRed\IYS::REQUEST_URL); // Yapılan son istek url adresini getirir.

$integrator->get(EmreRed\IYS::RESULT); // Yapılan son istek cevabını adresini getirir.
$integrator->get(EmreRed\IYS::RESULT_CODE); // Yapılan son istek cevabının http kodunu getirir.
```
