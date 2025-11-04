document.addEventListener('DOMContentLoaded', () => {
  const selectAll = document.getElementById('select-all');
  if (selectAll) {
    selectAll.addEventListener('change', (event) => {
      document.querySelectorAll('.row-checkbox').forEach((checkbox) => {
        checkbox.checked = event.target.checked;
      });
    });
  }
});
