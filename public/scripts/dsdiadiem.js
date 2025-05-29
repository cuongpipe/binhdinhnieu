    document.querySelectorAll(".btn-delete-link").forEach(function(el) {
        el.addEventListener("click", function(e) {
            if (!confirm("Bạn có chắc chắn muốn xóa địa điểm này?")) {
                e.preventDefault();
            }
        });
    });