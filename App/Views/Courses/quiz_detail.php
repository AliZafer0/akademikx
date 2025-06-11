<?php
// Sayfa: test_detail.php
?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AkademikX | Test Detay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { padding-bottom: 100px; }
    #submit-footer {
      position: fixed; bottom: 0; left: 0; width: 100%;
      background: #fff; box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
      padding: 10px 0; z-index: 1000;
    }
    .question-card { margin-bottom: 1.5rem; }
  </style>
</head>
<body>
  <div class="container my-5">
    <h2 id="test-title" class="mb-3 text-center">Test Yükleniyor…</h2>
    <p id="test-description" class="text-muted text-center mb-4"></p>

    <div id="questions-container"></div>

    <div id="progress-wrapper" class="mb-4">
      <div class="progress">
        <div id="progress-bar" class="progress-bar" role="progressbar" style="width:0%"></div>
      </div>
      <small id="progress-text" class="form-text text-muted mt-1">0 / 0 cevaplandı</small>
    </div>
  </div>

  <div id="submit-footer" class="text-center">
    <button id="submit-btn" class="btn btn-primary btn-lg" disabled>Cevapları Gönder</button>
  </div>

  <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="resultModalLabel">Test Sonucu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
        </div>
        <div class="modal-body text-center">
          <h3 id="resultScoreText">Puanınız: 0</h3>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tamam</button>
        </div>
      </div>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const parts = window.location.pathname.split('/');
    const testId = parts[parts.length - 1];
    const container = document.getElementById('questions-container');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const submitBtn = document.getElementById('submit-btn');
    let total = 0, answered = 0;

    fetch(`/akademikx/public/api/tests/${testId}`)
      .then(res => { if (!res.ok) throw new Error(res.status); return res.json(); })
      .then(data => {
        document.getElementById('test-title').textContent = data.title;
        document.getElementById('test-description').textContent = data.description || '';

        total = data.questions.length;
        if (!total) {
          container.innerHTML = '<div class="alert alert-info">Bu teste ait soru bulunmuyor.</div>';
          return;
        }

        container.innerHTML = data.questions.map((q, idx) => `
          <div class="card question-card" data-qid="${q.question_id}">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span><strong>Soru ${idx+1}:</strong> ${q.question_text}</span>
              <span id="badge-${q.question_id}" class="badge bg-secondary">Bekliyor</span>
            </div>
            <div class="card-body">
              ${q.options.map(opt => `
                <div class="form-check mb-2">
                  <input class="form-check-input answer-input" type="radio"
                         name="q${q.question_id}" id="opt${opt.option_id}" value="${opt.option_id}">
                  <label class="form-check-label" for="opt${opt.option_id}">
                    ${opt.option_text}
                  </label>
                </div>
              `).join('')}
              ${q.type === 'open' ? `
                <textarea class="form-control answer-input" name="q${q.question_id}" rows="2" placeholder="Cevabınızı yazın..."></textarea>
              ` : ''}
            </div>
          </div>
        `).join('');

        document.querySelectorAll('.answer-input').forEach(input => {
          input.addEventListener('change', e => {
            const qid = e.target.name.replace('q','');
            const badge = document.getElementById(`badge-${qid}`);
            if (!badge.classList.contains('bg-success')) {
              answered++;
              badge.textContent = 'Cevaplandı';
              badge.classList.replace('bg-secondary','bg-success');
            }
            updateProgress();
          });
        });

        updateProgress();
      })
      .catch(err => {
        console.error(err);
        container.innerHTML = '<div class="alert alert-danger">Test yüklenirken hata oluştu.</div>';
      });

    function updateProgress() {
      const percent = total ? Math.round((answered/total)*100) : 0;
      progressBar.style.width = `${percent}%`;
      progressBar.textContent = `${percent}%`;
      progressText.textContent = `${answered} / ${total} cevaplandı`;
      submitBtn.disabled = answered < total;
    }

    submitBtn.addEventListener('click', () => {
      const answers = {};
      document.querySelectorAll('.question-card').forEach(card => {
        const qid = card.dataset.qid;
        const input = card.querySelector('input:checked, textarea');
        answers[qid] = input ? input.value : null;
      });

      fetch(`/akademikx/public/api/tests/${testId}/submit`, {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ testId, answers })
      })
      .then(res => { if (!res.ok) throw new Error(res.status); return res.json(); })
      .then(result => {
        document.getElementById('resultScoreText').textContent = `Puanınız: ${result.score}`;
        new bootstrap.Modal(document.getElementById('resultModal')).show();
      })
      .catch(err => {
        console.error(err);
        alert('Gönderme hatası');
      });
    });
  });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
