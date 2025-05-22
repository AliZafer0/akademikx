<!-- Kullanıcı Ekle Modalı -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addUserModalLabel">Yeni Kullanıcı Ekle</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="username" class="form-label">Kullanıcı Adı</label>
            <input type="text" class="form-control" id="username" name="add_username" required placeholder="Ali Veli">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input type="password" class="form-control" id="password" name="add_password" required placeholder="••••••••">
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Yetki</label>
            <select class="form-select" id="role" name="add_role" required>
              <option value="" selected disabled>Seçiniz</option>
              <option value="admin">Admin</option>
              <option value="teacher">Öğretmen</option>
              <option value="student">Öğrenci</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="approved" class="form-label">Onay Durumu</label>
            <select class="form-select" id="approved" name="add_approved" required>
              <option value="1">Onaylı</option>
              <option value="0">Onaysız</option>
            </select>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
          <button type="submit" class="btn btn-success">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</div>
