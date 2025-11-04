document.addEventListener('DOMContentLoaded', function () {
  const selectAll = document.getElementById('select-all');
  const checkboxes = document.querySelectorAll('.row-checkbox');

  if (selectAll) {
    selectAll.addEventListener('change', function () {
      checkboxes.forEach(cb => cb.checked = selectAll.checked);
    });
  }

  checkboxes.forEach(cb => {
    cb.addEventListener('change', function () {
      if (!this.checked && selectAll) selectAll.checked = false;
    });
  });
});
