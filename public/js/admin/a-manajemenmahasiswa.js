document.addEventListener("DOMContentLoaded", function() {

    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const sortTahun = document.getElementById('sortTahun');
    const sortSemester = document.getElementById('sortSemester');
    const sortDosen = document.getElementById('sortDosen');

    const submitForm = () => filterForm.submit();

    // Submit on change for selects
    if (sortTahun) sortTahun.addEventListener('change', submitForm);
    if (sortSemester) sortSemester.addEventListener('change', submitForm);
    if (sortDosen) sortDosen.addEventListener('change', submitForm);

    // Debounce search input
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(submitForm, 1000); // Wait 1s after typing
        });
    }

    function filterTable() {
        const query = searchInput.value.toLowerCase();
        const tahunFilter = sortTahun.value;
        const semesterFilter = sortSemester.value;
        const dosenFilter = sortDosen ? sortDosen.value : '';

        // Skip the empty state row by selecting only rows with data-tahun
        const rows = tableBody.querySelectorAll('tr[data-tahun]');
        
        rows.forEach(row => {
            const nama = row.cells[2].textContent.toLowerCase();
            const tahun = row.getAttribute('data-tahun');
            const semester = row.getAttribute('data-semester');
            const d1 = row.dataset.d1 || '';
            const d2 = row.dataset.d2 || '';
            
            let show = true;
            
            if (query && !nama.includes(query)) show = false;
            if (tahunFilter && tahun !== tahunFilter) show = false;
            if (semesterFilter && semester !== semesterFilter) show = false;
            if (dosenFilter && d1 !== dosenFilter && d2 !== dosenFilter) show = false;
            
            row.style.display = show ? '' : 'none';
        });

        // Toggle empty state visibility
        const visibleRows = Array.from(rows).filter(r => r.style.display !== 'none');
        const emptyRow = tableBody.querySelector('.empty-row');
        if (emptyRow) {
            emptyRow.style.display = visibleRows.length === 0 ? '' : 'none';
        }
    }

    // Toggle Password Visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.password-input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });
});
