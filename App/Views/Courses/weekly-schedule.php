<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Anasayfa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .schedule-table {
            text-align: center;
            font-size: 14px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .schedule-table th,
        .schedule-table td {
            vertical-align: middle !important;
        }

        .lesson-card {
            background: linear-gradient(135deg, #90caf9, #42a5f5);
            color: white;
            padding: 10px 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .lesson-card:hover {
            background: linear-gradient(135deg, #42a5f5, #1e88e5);
            text-decoration: none;
            transform: scale(1.05);
        }

        .table-primary {
            background-color: #e3f2fd !important;
        }

        .table-secondary {
            background-color: #f1f3f4 !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>
    <div class="conteiner">
        <table id="scheduleTable" class="table table-bordered schedule-table mt-4">
        <thead class="table-primary">
            <tr>
            <th>Saat</th>
            <th>Monday</th>
            <th>Tuesday</th>
            <th>Wednesday</th>
            <th>Thursday</th>
            <th>Friday</th>
            <th>Saturday</th>
            <th>Sunday</th>
            </tr>
        </thead>
        <tbody id="scheduleBody">
            <!-- JS buraya satırları ekleyecek -->
        </tbody>
        </table>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
<script>
    const userId = <?=htmlspecialchars($_SESSION['user_id'])?>;
    const apiUrl = `/akademikx/public/get-schedules/${userId}`;
    console.log("📡 API URL:", apiUrl);

    // Günleri sıraya sokmak için
    const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    console.log("📅 Günler:", days);

    // Saatleri oluştur (08:00 - 22:00 arası örnek)
    const timeSlots = [];
    for (let h = 8; h <= 22; h++) {
        let hour = h.toString().padStart(2, '0') + ":00:00";
        timeSlots.push(hour);
    }
    console.log("⏰ Saat Aralıkları:", timeSlots);

    // Veriyi çek
    fetch(apiUrl)
    .then(res => {
        console.log("🔄 API isteği yapıldı.");
        return res.json();
    })
    .then(data => {
        console.log("✅ API'den gelen veri:", data);

        const tbody = document.getElementById("scheduleBody");
        if (!tbody) {
        console.error("❌ scheduleBody bulunamadı.");
        return;
        }

        // Her saat dilimi için
        timeSlots.forEach(time => {
        console.log(`⏱️ İşleniyor: ${time}`);
        const tr = document.createElement("tr");

        // Saat hücresi
        const hourTd = document.createElement("td");
        hourTd.classList.add("table-secondary");
        hourTd.textContent = time.slice(0, 5); // HH:MM
        tr.appendChild(hourTd);

        // Günlere göre kontrol
        days.forEach(day => {
            const td = document.createElement("td");

            const match = data.find(entry =>
            entry.day_of_week === day && entry.start_time === time
            );

            if (match) {
            console.log(`🎯 Eşleşme bulundu -> Gün: ${day}, Saat: ${time}, Ders: ${match.course_title}`);
            const a = document.createElement("a");
            a.href = `/akademikx/public/lesson_menu/${match.course_id}`;
            a.className = "lesson-card";
            a.textContent = match.course_title;
            td.appendChild(a);
            } else {
            console.log(`➖ Boş hücre -> Gün: ${day}, Saat: ${time}`);
            }

            tr.appendChild(td);
        });

        tbody.appendChild(tr);
        });

        console.log("📋 Tablo başarıyla oluşturuldu.");
    })
    .catch(error => {
        console.error("🚨 API verisi alınamadı:", error);
    });
</script>

</body>
</html>