<?php use App\Helpers\AuthHelper; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/akademikx/public/">AkademikX</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/akademikx/public/">Anasayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/akademikx/public/about-us">Hakkımızda</a>
                </li>

                <?php if (AuthHelper::isLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="derslerimDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Derslerim
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="derslerimDropdown">
                            <?php if (AuthHelper::getRole() === 'student' || AuthHelper::getRole() === 'admin'): ?>
                                <li><a class="dropdown-item" href="/akademikx/public/my-lessons">Kayıtlı Derslerim</a></li>
                                <li><a class="dropdown-item" href="/akademikx/public/weekly-schedule">Haftalık Program</a></li>
                            <?php endif; ?>

                            <?php if (AuthHelper::getRole() === 'teacher' || AuthHelper::getRole() === 'admin'): ?>
                                <li><a class="dropdown-item" href="/akademikx/public/teacher-panel">Öğretmen Paneli</a></li>
                                <li><a class="dropdown-item" href="/akademikx/public/weekly-schedule-teacher">Haftalık Program</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (AuthHelper::isLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i> <?php echo AuthHelper::getUsername(); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="/akademikx/public/settings">
                                    <i class="bi bi-gear me-2"></i> Ayarlar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="/akademikx/public/logout">
                                    <i class="bi bi-box-arrow-right me-2"></i> Çıkış Yap
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/akademikx/public/login">Giriş Yap</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
