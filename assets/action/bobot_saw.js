$(function(){
    $('#main-sidebar').addClass('menu-is-opening menu-open');
    $('#main-link').addClass('active');
    $('#bobot-saw-page').addClass('active');

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })


    $('#table-bobot').DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": true,
        "processing": true,
        "serverSide": true, 
        "order":[],
        "ajax":{
            url:"bobotLists",
            type:"post",
        },
        "columnDefs":[
            {
                "targets":[-1],
                "orderable":false,
            },
        ],
    });

    $('#formBobot').submit(function(e){
        e.preventDefault();
        var nilai_bobot_saw = $('#nilai_bobot_saw').val();
        if (nilai_bobot_saw != '') {
            $.ajax({
                method:'POST',
                dataType:'JSON',
                data: new FormData(this),
                processData: false,
                contentType: false,
                url:'doBobot',
                success:function(result){
                    if (result.cond == '1') {
                        Swal.fire({
                            icon: 'success',
                            title: result.msg+' !',
                            timer:3000,
                        }).then((results) => {
                            /* Read more about handling dismissals below */
                            if (results.dismiss === Swal.DismissReason.timer) {
                                $('#table-bobot').DataTable().ajax.reload();
                                $('#btnBobot').val('');
                                $('#operation').val('');
                                $('#nilai_bobot_saw').val('');
                                $('#id_bobot_saw').val('');
                                $('#modal-bobot').modal('hide');
                            }else if(results.isConfirmed){
                                $('#table-bobot').DataTable().ajax.reload();
                                $('#btnBobot').val('');
                                $('#operation').val('');
                                $('#nilai_bobot_saw').val('');
                                $('#id_bobot_saw').val('');
                                $('#modal-bobot').modal('hide');
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
                title: 'Field Tidak boleh kosong !',
                showConfirmButton: false,
                timer: 2000
            })
        }
    })

    $(document).on('click','.editBobot',function(){
        var id = $(this).attr('id');
        $('#modal-bobot').modal({backdrop:'static',show:true});
        $('#title-bobot').html('Beri Bobot Nilai');
        $('#btnBobot').val('Submit');
        $('#operation').val('Edit');
        $('#id_bobot_saw').val(id);
        $.ajax({
            method:'POST',
            dataType:'JSON',
            data:{id_bobot_saw:id},
            url:'bobotById',
            success:function(result){
                $('#nilai_bobot_saw').val(result.nilai_bobot_saw);
                $('#keterangan_bobot_saw').val(result.keterangan_bobot_saw);
            }
        })
    })
})