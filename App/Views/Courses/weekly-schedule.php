<?php
// Sayfa: weekly-schedule.php
?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AkademikX | Haftalık Program</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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
  <div class="container">
    <table id="scheduleTable" class="table table-bordered schedule-table mt-4">
      <thead class="table-primary">
        <tr>
          <th>Saat</th>
          <th>Pazartesi</th>
          <th>Salı</th>
          <th>Çarşamba</th>
          <th>Perşembe</th>
          <th>Cuma</th>
          <th>Cumartesi</th>
          <th>Pazar</th>
        </tr>
      </thead>
      <tbody id="scheduleBody"></tbody>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script>
    const userId = <?= htmlspecialchars($_SESSION['user_id']) ?>;
    const apiUrl = `/akademikx/public/get-schedules/${userId}`;
    const days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
    const timeSlots = [];
    for (let h = 8; h <= 22; h++) {
      timeSlots.push(`${h.toString().padStart(2,'0')}:00:00`);
    }

    fetch(apiUrl)
      .then(res => res.json())
      .then(data => {
        const tbody = document.getElementById("scheduleBody");
        timeSlots.forEach(time => {
          const tr = document.createElement("tr");
          const hourTd = document.createElement("td");
          hourTd.classList.add("table-secondary");
          hourTd.textContent = time.slice(0,5);
          tr.appendChild(hourTd);

          days.forEach(day => {
            const td = document.createElement("td");
            const match = data.find(entry => entry.day_of_week === day && entry.start_time === time);
            if (match) {
              const a = document.createElement("a");
              a.href = `/akademikx/public/lesson_menu/${match.course_id}`;
              a.className = "lesson-card";
              a.textContent = match.course_title;
              td.appendChild(a);
            }
            tr.appendChild(td);
          });

          tbody.appendChild(tr);
        });
      })
      .catch(error => {
        console.error("API verisi alınamadı:", error);
      });
  </script>
</body>
</html>
