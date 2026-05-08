document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const sortSemester = document.getElementById("sortSemester");
    const sortTahun = document.getElementById("sortTahun");
    const sortPeran = document.getElementById("sortPeran");
    const tableRows = document.querySelectorAll("#tabelMahasiswa tbody tr");

    if (searchInput) searchInput.addEventListener("keyup", filterTable);
    if (sortSemester) sortSemester.addEventListener("change", filterTable);
    if (sortTahun) sortTahun.addEventListener("change", filterTable);
    if (sortPeran) sortPeran.addEventListener("change", filterTable);

    // Cek query param
    const urlParams = new URLSearchParams(window.location.search);
    const initialPembimbing = urlParams.get('pembimbing');
    if (initialPembimbing && sortPeran) {
        sortPeran.value = 'P' + initialPembimbing;
        filterTable();
    }

    function filterTable() {
        let search = searchInput ? searchInput.value.toLowerCase() : "";
        let semester = sortSemester ? sortSemester.value : "";
        let tahun = sortTahun ? sortTahun.value : "";
        let peran = sortPeran ? sortPeran.value : "";

        tableRows.forEach(row => {
            let nama = row.cells[3].innerText.toLowerCase();
            let rowSemester = row.cells[4].innerText.trim();
            let rowTahun = row.cells[1].innerText.trim();

            // ambil dari badge
            let badge = row.querySelector(".role-badge");
            let rowPeran = badge ? badge.innerText.trim() : "";

            let show = true;

            if (search && !nama.includes(search)) show = false;
            if (semester && rowSemester !== semester) show = false;
            if (tahun && rowTahun !== tahun) show = false;
            if (peran && rowPeran !== peran) show = false;

            row.style.display = show ? "" : "none";
        });
    }
});
