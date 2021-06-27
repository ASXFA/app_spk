$(function(){
    $('#main-sidebar').addClass('menu-is-opening menu-open');
    $('#main-link').addClass('active');
    $('#subkriteria-page').addClass('active');

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


    $('#table-subkriteria').DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": true,
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"subkriteriaLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#btn-tambah-subkriteria').click(function(){
        $('#modalSubkriteria').modal({backdrop: 'static',show:true});
        $('#exampleModalLabel').html('Tambah Data Subkriteria')
        $('#btnSubkriteria').val('Tambah');
        $('#operation').val('Tambah');
        $('#nama_subkriteria').val('');
        $('#nilai_subkriteria').val('');
        $('#id_kriteria_sub').val(0);
        $('#id_subkriteria').val('');
    })

    $('#formSubkriteria').submit(function(e){
        e.preventDefault();
        var id_subkriteria = $('#id_subkriteria').val();
        var nama_subkriteria = $('#nama_subkriteria').val();
        var nilai_subkriteria = $('#nilai_subkriteria').val();
        var id_kriteria_sub = $('#id_kriteria_sub').val();
        var operation = $('#operation').val();
        if (nama_subkriteria != '' && id_kriteria_sub != '' && nilai_subkriteria != '') {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data:new FormData(this),
                processData: false,
                contentType: false,
                url:'doSubkriteria',
                success:function(result){
                    if (result.cond == '1') {
                        Swal.fire({
                            icon: 'success',
                            title: result.msg+' !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-subkriteria').DataTable().ajax.reload();
                                $('#btnSubkriteria').val('Tambah');
                                $('#operation').val('Tambah');
                                $('#nama_subkriteria').val('');
                                $('#nilai_subkriteria').val('');
                                $('#id_subkriteria').val('');
                                $('#id_kriteria_sub').val('');
                                $('#modalSubkriteria').modal('hide');
                            }else if(results.isConfirmed){
                                $('#table-subkriteria').DataTable().ajax.reload();
                                $('#btnSubkriteria').val('Tambah');
                                $('#operation').val('Tambah');
                                $('#nama_subkriteria').val('');
                                $('#nilai_subkriteria').val('');
                                $('#id_subkriteria').val('');
                                $('#id_kriteria_sub').val('');
                                $('#modalSubkriteria').modal('hide');
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
                icon: 'Warning',
                title: 'Peringatan !',
                text: 'Field Tidak boleh kosong !'
            })
        }
    })

    $(document).on('click','.editSubkriteria',function(){
        var id = $(this).attr('id');
        $('#modalSubkriteria').modal({backdrop:'static',show:true});
        $('#exampleModalLabel').html('Edit Data Subkriteria');
        $('#id_subkriteria').val(id);
        $('#btnSubkriteria').val('Edit');
        $('#operation').val('Edit');
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id_subkriteria:id},
            url:'subkriteriaById',
            success:function(result){
                $('#nama_subkriteria').val(result.nama_subkriteria);
                $('#nilai_subkriteria').val(result.nilai_subkriteria);
                $('#id_kriteria_sub').val(result.id_kriteria_sub);
            }
        })
    })

    $(document).on('click','.deleteSubkriteria',function(){
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
                    data:{id_subkriteria:id},
                    url:'deleteSubkriteria',
                    success:function(result){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil dihapus !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-subkriteria').DataTable().ajax.reload();
                            }else if(results.isConfirmed){
                                $('#table-subkriteria').DataTable().ajax.reload();
                            }
                        })
                    }
                })
            }
        })
    })

})