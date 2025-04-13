document.addEventListener("DOMContentLoaded", function () {
    const priceInput = document.getElementById("price");

    if (priceInput) {
        priceInput.addEventListener("input", function (e) {
            let value = e.target.value.replace(/[^0-9]/g, "");
            if (value.length > 0) {
                e.target.value = "RP. " + formatRupiah(value);
            } else {
                e.target.value = "";
            }
        });

        function formatRupiah(angka) {
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    }
});
