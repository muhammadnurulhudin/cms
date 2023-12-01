function notif(a, type) {
    var ic;
    if (type == "success") {
        ic = "fa fa-check";
    } else if (type == "danger") {
        ic = "fa fa-warning";
    } else {
        ic = "fa fa-info";
    }
    $.notify(
        {
            title: a,
            message: "",
            icon: ic,
        },
        {
            type: type,
        }
    );
}
$("#demoSelect").select2({
    placeholder: "Pilih Kategori",
});
function showalert(val) {
    swal(val);
}
function deleteAlert(url) {
    swal(
        {
            title: "Hapus Data ?",
            text: "Semua berkas terkait data ini akan terhapus.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Iya, Hapus!",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: false,
            closeOnCancel: false,
        },
        function (isConfirm) {
            if (isConfirm) {
                $.get(url, function (data, status) {
                    if (status == 'success') {
                        console.log(status);
                        notif("Data Berhasil Dihapus","success");
                    }
                });

                if ($(".datat").length) {
                    setTimeout(() => {
                    $(".datat").show();
                        $(".datat").DataTable().ajax.reload();
                    }, "1000");
                } else {
                    setTimeout(() => {
                        location.reload();
                    }, "1000");

                }
                swal.close();
                // swal("Berhasil", "Data Berhasil Dihapus", "success");
            } else {
                swal("Dibatalkan", "Penghapusan dibatalkan", "error");
            }
        }
    );
}
var data = {
    labels: ["January", "February", "March", "April", "May"],
    datasets: [
        {
            label: "My First dataset",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [65, 59, 80, 81, 56],
        },
        {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: [28, 48, 40, 19, 86],
        },
    ],
};
var pdata = [
    {
        value: 300,
        color: "#46BFBD",
        highlight: "#5AD3D1",
        label: "Complete",
    },
    {
        value: 50,
        color: "#F7464A",
        highlight: "#FF5A5E",
        label: "In-Progress",
    },
];

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
var goUrl = function () {
    document.onclick = function (e) {
        if (e.target.getAttribute("modul")) {
            location.href = e.target.getAttribute("modul");
        }
    };
};
goUrl();
