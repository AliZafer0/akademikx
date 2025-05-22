
# 📘 AkademikX

**AkademikX**, çevrim içi ders içerik yönetim sistemi olarak tasarlanmış, PHP ile yazılmış bir MVC tabanlı web uygulamasıdır. Bu sistem sayesinde yöneticiler ders, öğretmen ve kullanıcı yönetimi yapabilirken; öğrenciler içeriklere erişebilir, derslere kayıt olabilirler.

## 🚀 Özellikler

- Kullanıcı kayıt ve giriş sistemi (Auth)
- Yönetici paneli üzerinden:
  - Ders oluşturma ve yönetimi
  - Öğretmen ekleme ve düzenleme
  - Haftalık ders programı oluşturma
  - Kullanıcı yönetimi
- Ders içeriklerini PDF, video vb. medya olarak yükleyebilme
- Öğrenci-ders kayıt sistemi
- Rol tabanlı erişim kontrolü (Yönetici, Öğretmen, Öğrenci)
- MVC (Model-View-Controller) mimarisi

## 📂 Proje Yapısı

```
akademikx/
│
├── index.php                  -> Ana giriş noktası
├── composer.json             -> Composer bağımlılık tanımı
│
├── App/
│   ├── Controllers/          -> Tüm sayfa ve işlem kontrol dosyaları
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   ├── AdminController.php
│   │   ├── LessonController.php
│   │   ├── UploadController.php
│   │   └── ...
│   │
│   ├── Core/                 -> Temel altyapı bileşenleri
│   │   ├── Auth.php          -> Oturum kontrol ve yetkilendirme
│   │   ├── Database.php      -> PDO tabanlı veri tabanı bağlantısı
│   │   └── Router.php        -> Basit yönlendirme sistemi
│   │
│   ├── Helpers/              -> Yardımcı fonksiyonlar
│   │   └── AuthHelper.php
│   │
│   ├── Models/               -> Veri tabanı işlemleri
│   │   ├── Users.php
│   │   ├── Teachers.php
│   │   ├── Courses.php
│   │   ├── Enrollments.php
│   │   └── ...
│   │
│   └── Views/                -> HTML arayüz dosyaları
│       ├── auth/             -> Giriş/kayıt sayfaları
│       ├── Admin/            -> Yönetici panelleri
│       ├── Courses/          -> Ders detayları, oynatma ekranları
│       ├── home/             -> Ana sayfa
│       ├── Partials/         -> Navbar, modal, bileşen parçaları
│       └── Error/            -> 401 / 404 sayfaları
│
├── public/                   -> Web root klasörü
│   ├── index.php             -> Sunucunun gördüğü ana dosya
│   ├── .htaccess             -> URL yönlendirmeleri
│   ├── assets/               -> CSS, JS, görseller
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── uploads/              -> Yüklenen video, döküman, resimler
│       ├── documents/
│       ├── videos/
│       └── images/
│
└── vendor/                   -> Composer tarafından oluşturulan bağımlılıklar

```

## 🛠️ Kurulum

### Gereksinimler

- PHP >= 8.0
- Apache/Nginx
- MySQL
- Composer

### Kurulum Adımları

```bash
git clone https://github.com/AliZafer0/akademikx.git
cd akademikx
composer install
```

### Veritabanı

1. `akademikx.sql` dosyasını veritabanınıza içe aktarın.
2. `App/Core/Database.php` dosyasından veritabanı bağlantınızı güncelleyin:
```php
$this->db = new PDO("mysql:host=localhost;dbname=akademikx", "kullanici", "şifre");
```

## 🔐 Kullanıcı Rolleri

| Rol       | Açıklama |
|-----------|----------|
| admin     | Tüm sistemi yönetir (ders, öğretmen, kullanıcı ekleyebilir) |
| teacher   | Yalnızca ders içeriklerini yükleyebilir ve ders yönetimi yapabilir |
| student   | Derslere katılabilir ve içeriklere erişebilir |

> Roller `users` tablosundaki `role` alanı üzerinden yönetilir.

## 🔧 Geliştirici Notları

- Giriş kontrolü `App/Core/Auth.php` üzerinden yapılır.
- Erişim kontrolü (örneğin `hasRole`) `App/Helpers/AuthHelper.php` içinde tanımlanmıştır.
- Tüm yönlendirmeler `App/Core/Router.php` üzerinden yapılır.
- Proje MVC yapısına uygun olarak düzenlenmiştir.
