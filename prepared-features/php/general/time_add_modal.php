<!-- Ders Günü & Saati Ekle Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="scheduleModalLabel">Ders Günü & Saati Ekle</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form id="scheduleForm" method="POST">
          <div class="mb-3">
            <label for="dayOfWeek" class="form-label">Gün</label>
            <select class="form-select" id="dayOfWeek" name="day_of_week" required>
              <option value="" disabled selected>Gün Seçiniz</option>
              <option value="Monday">Pazartesi</option>
              <option value="Tuesday">Salı</option>
              <option value="Wednesday">Çarşamba</option>
              <option value="Thursday">Perşembe</option>
              <option value="Friday">Cuma</option>
              <option value="Saturday">Cumartesi</option>
              <option value="Sunday">Pazar</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="startTime" class="form-label">Başlangıç Saati</label>
            <input type="time" class="form-control" id="startTime" name="start_time" required>
          </div>
          <div class="mb-3">
            <label for="endTime" class="form-label">Bitiş Saati</label>
            <input type="time" class="form-control" id="endTime" name="end_time" required>
          </div>
          <div class="modal-footer px-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
            <button type="submit" class="btn btn-success">Ekle</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>