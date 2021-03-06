$(function(){

    $('#main-sidebar').addClass('menu-is-opening menu-open');
    $('#main-link').addClass('active');
    $('#kriteria-page').addClass('active');

    // const Toast = Swal.mixin({
    //     toast: true,
    //     position: 'top-end',
    //     showConfirmButton: false,
    //     timer: 3000,
    //     timerProgressBar: true,
    //     didOpen: (toast) => {
    //     toast.addEventListener('mouseenter', Swal.stopTimer)
    //     toast.addEventListener('mouseleave', Swal.resumeTimer)
    //     }
    // })


    $('#table-kriteria').DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": true,
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"kriteriaLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-kriteria').click(function(){
        $('#modalKriteria').modal({backdrop: 'static',show:true});
        $('#exampleModalLabel').html('Tambah Data Kriteria');
        $('#btnKriteria').val('Tambah');
        $('#operation').val('Tambah');
        $('#nama_kriteria').val('');
        $('#id_jenis').val(0);
    })

    $('#formKriteria').submit(function(e){
        e.preventDefault();
        var id_kriteria = $('#id_kriteria').val();
        var nama_kriteria = $('#nama_kriteria').val();
        var id_jenis = $('#id_jenis').val();
        var operation = $('#operation').val();
        if (nama_kriteria != '' && id_jenis != "") {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:new FormData(this),
                processData: false,
                contentType: false,
                url:'doKriteria',
                success:function(result){
                    if (result.cond == '1') {
                        Swal.fire({
                            icon: 'success',
                            title: result.msg+' !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-kriteria').DataTable().ajax.reload();
                                $('#btnKriteria').val('Tambah');
                                $('#operation').val('Tambah');
                                $('#nama_kriteria').val('');
                                $('#id_jenis').val('');
                                $('#id_kriteria').val('');
                                $('#modalKriteria').modal('hide');
                            }else if(results.isConfirmed){
                                $('#table-kriteria').DataTable().ajax.reload();
                                $('#btnKriteria').val('Tambah');
                                $('#operation').val('Tambah');
                                $('#nama_kriteria').val('');
                                $('#id_jenis').val('');
                                $('#id_kriteria').val('');
                                $('#modalKriteria').modal('hide');
                            }
                        })
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: result.msg+' !',
                            timer:3000,
                        })
                    }
                }
            })
        }else{
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan !',
                text: 'Field Tidak boleh kosong !',
                showConfirmButton: false,
                timer : 2000
            })
        }
    })

    $(document).on('click','.editKriteria',function(){
        var id = $(this).attr('id');
        $('#modalKriteria').modal({backdrop:'static',show:true});
        $('#id_kriteria').val(id);
        $('#btnKriteria').val('Edit');
        $('#operation').val('Edit');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id_kriteria:id},
            url:'kriteriaById',
            success:function(result){
                $('#nama_kriteria').val(result.nama_kriteria);
                $('#id_jenis').val(result.id_jenis);
            }
        })
    })

    $(document).on('click','.deleteKriteria',function(){
        Swal.fire({
            title: 'Yakin Di Hapus ?',
            text: 'Data akan terhapus permanen !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus !'
        }).then((result) => {
            if (result.isConfirmed) {
                var id = $(this).attr('id');
                $.ajax({
                    method:'POST',
                    dataType:'JSON',
                    data:{id_kriteria:id},
                    url:'deleteKriteria',
                    success:function(result){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil dihapus !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-kriteria').DataTable().ajax.reload();
                            }else if(results.isConfirmed){
                                $('#table-kriteria').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })

})