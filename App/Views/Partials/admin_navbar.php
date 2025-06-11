<!-- Sidebar (Solda Tam Yükseklik) -->
<div class="sidebar d-none d-lg-block p-3" id="sidebar">
  <div class="d-flex align-items-center mb-3">
    <img src="../media/images/general/icons/admin_icons/logo.png" class="nav-logo me-2" alt="Logo">
    <p class="mb-0 nav-title">Admin Paneli</p>
  </div>
  <ul class="list-unstyled">
    <li class="mb-2">
      <a href="/akademikx/public/admin" class="d-block text-decoration-none py-2 px-3 rounded <?php if($current_page == 'admin') echo 'bg-dark'; ?>">  
        <i class="fa-solid fa-sitemap me-2"></i>Ana Sayfa
      </a>
    </li>
    <li class="mb-2">
      <a class="d-block text-decoration-none py-2 px-3 rounded collapsed" 
        data-bs-toggle="collapse" 
        data-bs-target="#ModSubmenu"
        style="overflow-x: auto; overflow: hidden; -webkit-overflow-scrolling: touch;">
        <i class="fa-solid fa-book me-2"></i>Ders Yönetimi 
      </a>
      <ul id="ModSubmenu" class="list-unstyled collapse interrior">
        <a href="/akademikx/public/admin/lessons" class="text-decoration-none rounded"><li>Dersler</li></a>
        <a href="/akademikx/public/admin/lessons/add_lessons" class="text-decoration-none rounded"><li>Ders Ekle</li></a>
        <a href="/akademikx/public/admin/lessons/add_category" class="text-decoration-none rounded"><li>Kategori Ekle</li></a>
        <a href="/akademikx/public/admin/lessons/add_teacher" class="text-decoration-none rounded"><li>Öğretmen Ekle</li></a>
        <a href="/akademikx/public/admin/lessons/tests" class="text-decoration-none rounded"><li>Testler</li></a> 
      </ul>
    </li>
    <li class="mb-2">
      <a class="d-block text-decoration-none py-2 px-3 rounded collapsed" 
        data-bs-toggle="collapse" 
        data-bs-target="#userSubmenu">
        <i class="fa-solid fa-users me-2"></i>Kullanıcı
      </a>
      <ul id="userSubmenu" class="list-unstyled collapse interrior">
        <a href="/akademikx/public/admin/users/summary" class="text-decoration-none rounded"><li>Kullanıcı Özeti</li></a>
        <a href="/akademikx/public/admin/users/manage" class="text-decoration-none rounded"><li>Kullanıcılar</li></a>
      </ul>
    </li>
    <li class="mb-2">
      <a href="/akademikx/public/admin/logs" class="d-block text-decoration-none py-2 px-3 rounded <?php if($current_page == 'admin/kullanici-loglari') echo 'bg-dark'; ?>">  
        <i class="fa-solid fa-clock-rotate-left me-2"></i>Loglar
      </a>
    </li>
  </ul>
</div>

<!-- Mobilde Offcanvas Menü -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenuus" aria-labelledby="offcanvasMenuLabel">
  <div class="offcanvas-body">
    <div class="d-flex align-items-center mb-3 offcanvas-header">
      <img src="../media/images/general/icons/admin_icons/logo.png" class="nav-logo me-2" alt="Logo">
      <p class="mb-0">Admin Paneli</p>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <ul class="list-unstyled">
      <li class="mb-2">
        <a href="/akademikx/public/admin" class="d-block text-decoration-none py-2 px-3 rounded <?php if($current_page == 'admin') echo 'bg-dark'; ?>">  
          <i class="fa-solid fa-sitemap me-2"></i>Ana Sayfa
        </a>
      </li>
      <li class="mb-2">
        <a class="d-block text-decoration-none py-2 px-3 rounded collapsed" 
          data-bs-toggle="collapse" 
          data-bs-target="#ModSubmenu"
          style="overflow-x: auto; overflow: hidden; -webkit-overflow-scrolling: touch;">
          <i class="fa-solid fa-book me-2"></i>Ders Yönetimi 
        </a>
        <ul id="ModSubmenu" class="list-unstyled collapse interrior">
        <a href="/akademikx/public/admin/lessons/" class="text-decoration-none rounded"><li>Dersler</li></a>
        <a href="/akademikx/public/admin/lessons/add_lessons" class="text-decoration-none rounded"><li>Ders Ekle</li></a>
        <a href="/akademikx/public/admin/lessons/add_category" class="text-decoration-none rounded"><li>Kategori Ekle</li></a>
        <a href="/akademikx/public/admin/lessons/add_teacher" class="text-decoration-none rounded"><li>Öğretmen Ekle</li></a>
        <a href="/akademikx/public/admin/lessons/tests" class="text-decoration-none rounded"><li>Testler</li></a> 
        </ul>
      </li>
      <li class="mb-2">
        <a class="d-block text-decoration-none py-2 px-3 rounded collapsed" 
          data-bs-toggle="collapse" 
          data-bs-target="#userSubmenu">
          <i class="fa-solid fa-users me-2"></i>Kullanıcı
        </a>
        <ul id="userSubmenu" class="list-unstyled collapse interrior">
          <a href="/akademikx/public/admin/users/summary" class="text-decoration-none rounded"><li>Kullanıcı Özeti</li></a>
          <a href="/akademikx/public/admin/users/manage" class="text-decoration-none rounded"><li>Kullanıcılar</li></a>
        </ul>
      </li>
      <li class="mb-2">
        <a href="/akademikx/public/admin/logs" class="d-block text-decoration-none py-2 px-3 rounded <?php if($current_page == 'admin/kullanici-loglari') echo 'bg-dark'; ?>">  
          <i class="fa-solid fa-clock-rotate-left me-2"></i>Loglar
        </a>
      </li>
    </ul>
  </div>
</div>


<!-- Üstteki Nav -->
<div class="content" id="content">
        <!-- Navbar (Menü Tuşu Burada) -->
    <div class="" style="min-height: 100vh;">
        <nav class="ust p-1 pe-2 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <button class="btn me-2" id="toggleSidebar" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenuus" aria-controls="offcanvasMenu">
                <i class="fa-solid fa-bars"></i>
              </button>
              <a class="btn" href="/akademikx/public/admin">
                  <i class="fa-solid fa-house"></i>
              </a>
            </div>
            <div class="d-flex align-items-center">
              <form class="d-flex me-2" id="search-form">
                <input class="form-control" type="text" id="search-input" placeholder="Arama yap..." onkeyup="searchFunction()" autocomplete="off">
                <button class="ara" type="submit"><i class="fas fa-search"></i></button>
                <div id="search-results"></div>
              </form>
              <div class="dropdown">
                <button type="button" class="settings-pp rounded-circle position-relative" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                  <i class="fa-solid fa-bell position-relative">
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill <?php echo $notification_class; ?>" ">
                      <p></p> <span class="visually-hidden">Okunmamış Mesaj</span>
                    </span>
                  </i>
                </button>
               <ul class="dropdown-menu natifications">
    <strong class="natifications-title m-2">Bildirimler</strong>
</ul>
              </div>
              <a class="settings-pp d-flex align-items-center justify-content-center rounded-circle text-decoration-none" href="iletisim/yonetim-sohbet/">
                  <i class="fa-solid fa-headset"></i>
              </a>
              <div class="dropdown">
                <button class="pp-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                  <img src="" class="nav-pp" alt="PP">
                </button>
                <ul class="dropdown-menu profile">
                  <li><a class="dropdown-item" href="/akademikx/public/admin/profil/profil-düzenle"><i class="fa-regular fa-user me-2"></i>Profili Düzenle</a></li>
                  <li><a class="dropdown-item" href="/akademikx/public/admin/profil/istatistiklerim"><i class="fa-solid fa-square-poll-vertical me-2"></i></i>İstatistiklerim</a></li>
                  <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal1"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Çıkış Yap</a></li>
                </ul>
              </div>
            </div>
        </nav>
            <!-- Çıkış Yapaman-->
            <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content bg-dark">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5 text-white" id="exampleModalLabel">Çıkış Yapaman!</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div> <canvas id="c"></canvas>
                      <div class="modal-body">
                        <img class="nah" src="dur-bakam.png" alt="Çıkış yapma lan">
                        <span class="text-white">Sırf giriş yapmaya uğraşma diye. çıkış yapmanı engelledim hadi yine iyisin :)</span>
                      </div>
                      
                      <div class="modal-footer">
                        <button onclick="window.location.href='/akademikx/public/logout'" type="button" class="btn btn-danger" data-bs-dismiss="modal">Yine De Çıkmak İstersen Tıkla</button>
                      </div>
                    </div>
                  </div>
            </div>
        <div class="response-content">
        <script>
function searchFunction() {
    let query = document.getElementById("search-input").value;
    let resultsDiv = document.getElementById("search-results");

    if (query.length < 2) {
        resultsDiv.style.display = "none"; 
        return;
    }

    fetch("http://localhost/Exeteam/prepared-features/php/admin/search.php?q=" + encodeURIComponent(query))
    .then(response => response.json())
    .then(data => {
        console.log("Search Response:", data);
        resultsDiv.innerHTML = "";
        
        if (data.length > 0) {
            data.forEach(item => {
                let resultItem = document.createElement("div");
                resultItem.innerHTML = `<a href="${item.url}">${item.title}</a>`;
                resultItem.onclick = () => window.location.href = item.url; // Tıklayınca git
                resultsDiv.appendChild(resultItem);
            });
            resultsDiv.style.display = "block"; // Sonuçları göster
        } else {
            resultsDiv.innerHTML = "<div>Sonuç bulunamadı</div>";
            resultsDiv.style.display = "block"; 
        }
    })
    .catch(error => console.error("Hata oluştu:", error));
}

// Arama kutusundan ayrıldığında sonuçları gizle (UX için)
document.addEventListener("click", function(event) {
    let searchBox = document.getElementById("search-input");
    let resultsDiv = document.getElementById("search-results");

    if (!searchBox.contains(event.target) && !resultsDiv.contains(event.target)) {
        resultsDiv.style.display = "none";
    }
});
</script>