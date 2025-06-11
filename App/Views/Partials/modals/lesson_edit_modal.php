<?php
// Partial: modals/edit_lesson_modal.php
?>
<div class="modal fade" id="editLessonModal" tabindex="-1" aria-labelledby="editLessonLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="editLessonLabel">Ders Bilgilerini Düzenle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <div class="modal-body">
          <input type="hidden" name="id" id="editLessonId">

          <div class="mb-3">
            <label for="editLessonTitle" class="form-label">Ders Başlığı</label>
            <input type="text" class="form-control" name="title" id="editLessonTitle" required>
          </div>

          <div class="mb-3">
            <label for="editLessonDesc" class="form-label">Ders Açıklaması</label>
            <input type="text" class="form-control" name="desc" id="editLessonDesc" required>
          </div>

          <div class="mb-3">
            <label for="edit-teacher" class="form-label">Öğretmen</label>
            <select class="form-select" name="teacher" id="edit-teacher" required>
              <?php foreach ($teachers as $teacher): ?>
                <option value="<?= htmlspecialchars($teacher['id']) ?>">
                  <?= htmlspecialchars($teacher['username']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="edit-categories" class="form-label">Kategoriler</label>
            <select class="form-select" name="categories" id="edit-categories" required>
              <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category['id']) ?>">
                  <?= htmlspecialchars($category['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
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
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-bs-target="#editLessonModal"]').forEach(button => {
    button.addEventListener('click', () => {
      const id         = button.getAttribute('data-id');
      const title      = button.getAttribute('data-title');
      const desc       = button.getAttribute('data-desc');
      const categoryId = button.getAttribute('data-category');
      const teacherId  = button.getAttribute('data-teacher-id');

      document.getElementById('editLessonId').value     = id;
      document.getElementById('editLessonTitle').value  = title;
      document.getElementById('editLessonDesc').value   = desc;
      document.getElementById('edit-categories').value  = categoryId;
      document.getElementById('edit-teacher').value     = teacherId;
    });
  });
});
</script>
