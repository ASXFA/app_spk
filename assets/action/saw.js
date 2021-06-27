$(function(){
    $('#penilaian-sidebar').addClass('menu-is-opening menu-open');
    $('#penilaian-link').addClass('active');
    $('#saw-page').addClass('active');

    $('#table-santri-bobot').DataTable();
    $('#table-bobot-saw').DataTable();
    // $('#table-hasil-saw').DataTable();

    $('#table-hasil-saw').DataTable({
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"hitungLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-hitung-sekarang').click(function(){
        Swal.fire({
            title: 'Hitung SAW Sekarang ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#ddd',
            confirmButtonText: 'Ya, Hitung '
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    url:'hitungSaw',
                    success:function(result){
                        Swal.fire({
                            title: 'Hitung Berhasil !',
                            icon : 'success',
                            showConfirmButton: false,
                            timer: 3000
                        }).then((results)=>{
                            $('#table-hasil-saw').DataTable().ajax.reload();
                        })
                    }
                })
            }
        })
    })
})