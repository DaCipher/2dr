$(document).ready(function() {
    $('img').on('click', function() {
        var src = $(this).attr("src");
        var name = $(this).attr("data-name");
        $('#modal-img').attr("src", src);
        $('#idViewModalTitle').text(name);
        $("#download-btn").attr("href", src).attr("download", name);
        $('#idViewModal').modal("show");
    });
});