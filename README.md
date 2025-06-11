# 📘 AkademikX

**AkademikX**, eğitim içeriklerini çevrim içi bir altyapı üzerinden yönetmek ve sunmak amacıyla geliştirilmiş açık kaynaklı bir PHP-MVC uygulamasıdır. Hem eğitim kurumlarının hem de bireysel öğretmenlerin ihtiyaç duyduğu tüm temel işlevleri tek bir sistemde toplar.

## 🚀 Özellikler

- Kullanıcı kayıt ve giriş sistemi
- Rol tabanlı erişim kontrolü (Admin, Öğretmen, Öğrenci)
- Ders ve içerik yönetimi (PDF, video, görsel, test)
- Öğrenci kayıt ve takip mekanizmaları
- Haftalık ders programı oluşturma
- Test oluşturma ve otomatik puanlama
- MVC mimarisine tam uyumlu

## 🛠️ Mimari ve Teknoloji

- PHP (≥8.0) ile MVC mimarisi
- Güvenli, parametrik PDO MySQL bağlantısı
- Hafif, bağımlılıksız yönlendirme sistemi
- Composer üzerinden üçüncü parti kütüphaneler
- Bootstrap 5 ve Vanilla JS destekli ön yüz
- Statik varlıklar: `public/assets/`
- Yüklenen medya: `public/uploads/`

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

## 🔐 Kayıt & Giriş

- **AuthController:** loginIndex, login\_check, logout, register
- **Auth (Core/Auth.php):** Oturum kontrolü, parola doğrulama
- **Users Model:** Parola hash’leme, rol yönetimi

## 🔑 Yetkilendirme & Roller

| Rol     | Açıklama                                           |
| ------- | -------------------------------------------------- |
| Admin   | Sistem genelinde tam yetki                         |
| Teacher | Ders oluşturur, içerik yönetir, testleri yönetir   |
| Student | Derslere erişir, içerikleri görüntüler, test çözer |

## 📚 Ders & İçerik Yönetimi

- **Courses & CourseContents Models:** İçerik yönetimi
- **MediaController:** Ders içerikleri listeleme ve detay görüntüleme
- **UploadController:** Medya yükleme, MIME türüne göre sınıflandırma

## 📝 Test Sistemi

- **CourseContents Model:** Test CRUD işlemleri, otomatik puanlama
- **TestsController:** API tabanlı test işlemleri (başlatma, cevap gönderme, sonuçları yönetme)
- Yönetim panelleri: TeacherController ve AdminController içinde

## 📅 Haftalık Program Yönetimi

- **CourseSchedule Model:** Ders programı ekleme ve yönetim
- **WeeklyScheduleController:** Öğretmen ve öğrenci programları

## 🎨 Front-end

- Navbar ve Sidebar bileşenleri
- Modal yönetimi: kullanıcı, ders ve içerik yönetimi
- Fetch API ile dinamik içerik çekme
- Responsive tasarım (Bootstrap 5, custom CSS)

## 📈 Geliştirme ve Optimizasyon

- SQL sorgu optimizasyonları (indeksler, spesifik alan seçimi)
- JavaScript optimizasyonları (event delegation, hata yönetimi)
- Router iyileştirmeleri (regex destekli rotalar)
- Statik varlıklar için CDN ve GZIP sıkıştırma önerileri

## 🚀 Kurulum

### Gereksinimler

- PHP ≥ 8.0
- Apache/Nginx
- MySQL
- Composer

### Adımlar

```bash
git clone https://github.com/AliZafer0/akademikx.git
cd akademikx
composer install
```

### Veritabanı

- `akademikx.sql` dosyasını MySQL’e içe aktarın
- `App/Core/Database.php` içindeki bağlantıyı düzenleyin:

```php
$this->db = new PDO("mysql:host=localhost;dbname=akademikx", "kullanici", "şifre");
```
