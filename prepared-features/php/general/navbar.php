
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">AkademikX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Anasayfa</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="about-us/">Hakkımızda</a>
                </li>
                <?php if (isUserLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="derslerimDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Derslerim
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="derslerimDropdown">
                       <?php if ($_SESSION['role'] == 'student' || $_SESSION['role'] == 'admin'): ?>
                                <li>
                                    <a class="dropdown-item" href="my-lessons/">Kayıtlı Derslerim</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="weekly-schedule/">Haftalık Program</a>
                                </li>

                        <?php endif; ?>

                        <?php if ($_SESSION['role'] == 'teacher' || $_SESSION['role'] == 'admin'): ?>
                                <li>
                                    <a class="dropdown-item" href="teacher-panel">Öğretmen Paneli</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="weekly-schedule-teacher/">Haftalık Program</a>
                                </li>
                        <?php endif; ?>
                        </ul>
                    </li>             
                <?php endif; ?>

                <?php if (isUserLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i> <?php echo $_SESSION["username"]; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="settings/">
                                    <i class="bi bi-gear me-2"></i> Ayarlar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="logout/">
                                    <i class="bi bi-box-arrow-right me-2"></i> Çıkış Yap
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                    <a class="nav-link" href="login/">Giriş Yap</a>
                    </li>
                <?php endif; ?>
            </ul>
            </div>
        </div>
    </nav>