<!-- Dosya Yükleme Modalı -->
<div class="modal fade" id="uploadVideoModal" tabindex="-1" aria-labelledby="uploadVideoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="uploadVideoModalLabel">Yeni Video İçeriği Yükle</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <div class="modal-body">
        <form id="uploadVideoForm" method="POST" enctype="multipart/form-data">
          
          <!-- Başlık -->
          <div class="mb-3">
            <label for="titleInput" class="form-label">Başlık</label>
            <input type="text" class="form-control" id="titleInput" name="title" placeholder="İçeriğin başlığı" required>
          </div>

          <!-- Açıklama -->
          <div class="mb-3">
            <label for="descriptionInput" class="form-label">Açıklama</label>
            <textarea class="form-control" id="descriptionInput" name="description" rows="3" placeholder="İçeriğe kısa bir açıklama ekleyin..." required></textarea>
          </div>

          <!-- Dosya Seçimi -->
          <div class="mb-3">
            <label for="fileInput" class="form-label">Dosya Seç</label>
            <input class="form-control" type="file" id="fileInput" name="uploadVideo_file" accept="video/*" required>
            <div class="form-text text-muted">Dosya yüklendikten sonra ismi sistem tarafından otomatik değiştirilecektir.</div>
          </div>

          <div class="modal-footer px-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
            <button type="submit" class="btn btn-primary">Yükle</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
