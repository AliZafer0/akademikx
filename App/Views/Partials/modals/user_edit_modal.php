<!-- Kullanıcı Düzenleme Modalı -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserLabel">Kullanıcıyı Düzenle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <!-- Gizli ID -->
          <input type="hidden" name="id" id="editUserId">

          <!-- Kullanıcı Adı -->
          <div class="mb-3">
            <label for="editUsername" class="form-label">Kullanıcı Adı</label>
            <input type="text" class="form-control" name="username" id="editUsername" required>
          </div>

          <!-- Rol Seçimi -->
          <div class="mb-3">
            <label for="editRole" class="form-label">Rol</label>
            <select class="form-select" name="role" id="editRole" required>
              <option value="admin">Admin</option>
              <option value="student">Student</option>
              <option value="teacher">Teacher</option>
            </select>
          </div>

          <!-- Onaylı mı? -->
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="approved" id="editApproved">
            <label class="form-check-label" for="editApproved">Onaylı kullanıcı mı?</label>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
          <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
  const editButtons = document.querySelectorAll('[data-bs-target="#editUserModal"]');

  editButtons.forEach(button => {
    button.addEventListener('click', () => {
      // Butondan verileri al
      const id = button.getAttribute('data-id');
      const username = button.getAttribute('data-username');
      const role = button.getAttribute('data-role');
      const approved = button.getAttribute('data-approve');

      // Modal inputlarını doldur
      document.getElementById('editUserId').value = id;
      document.getElementById('editUsername').value = username;
      document.getElementById('editRole').value = role;

      // approved 1 ise checkbox'ı işaretle
      document.getElementById('editApproved').checked = approved == '1';
    });
  });
});
</script>