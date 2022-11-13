
let table = undefined;
function getContributor() {
    let contributorsListElement = $('#contributors-list');
    if (!contributorsListElement.length) {
        return;
    }
    table = contributorsListElement.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'GET',
            url: '/admin/contributors',
            data: {api: true},
        },
        columns: [
            {data: "id", name: "id", title: "id"},
            {data: "fullname", name: "fullname", title: "Full Name"},
            {data: "role", name: "role", title: "Role"},
            {data: "slug", name: "slug", title: "Slug"},
            {
                data: "image", name: "image", title: "Picture",
                render: function(data, type, row) {
                    return `<img src="${data}" class="square-75" />`;
                }
            },
            {
                data: "deleted_at", name: "deleted_at", title: "Active",
                render: function(data, type, row) {
                    let color = data ? 'red' : 'green';
                    return `<div><i class="fa-solid fa-circle" style="color: ${color}"></i></div>`;
                }
            },
            {
                data: "id", name: "id", title: "Actions",
                render: function(data, type, row) {
                    return `<div class="actions d-flex justify-content-around">
                        <div class="prepare-update-contributor cursor-pointer fs-2"><i class="fa-solid fa-pen-to-square"></i></div>
                        <div class="delete-contributor cursor-pointer fs-2" data-id="${data}"><i class="fa-solid fa-trash"></i></div>
                        <div class="restore-contributor cursor-pointer fs-2" data-id="${data}"><i class="fa-solid fa-rotate-left"></i></div>
                    </div>`;
                }
            },
        ]
    });
}
function initContributorActions(){
    $('#contributors-list').on('click', '.restore-contributor', function (e) {
        if (!confirm('Are you sur ?')) {
            return;
        }
        $.ajax({
            method: 'POST',
            url: '/admin/contributors',
            data: {restore: true, contributor_id: $(this).data('id')},
            success: function (response) {
                alert(response.message);
                table.ajax.reload(null, false);
            },
            error: function (jqXHR, textStatus, errorThrown){
                console.log(jqXHR, textStatus, errorThrown);
                alert('Error');
            }
        });
    });
    
    $('#contributors-list').on('click', '.delete-contributor', function (e) {
        if (!confirm('Are you sur ?')) {
            return;
        }
        $.ajax({
            method: 'POST',
            url: '/admin/contributors',
            data: {delete: true, contributor_id: $(this).data('id')},
            success: function (response) {
                alert(response.message);
                table.ajax.reload(null, false);
            },
            error: function (jqXHR, textStatus, errorThrown){
                console.log(jqXHR, textStatus, errorThrown);
                alert('Error');
            }
        });
    });

    $('#contributors-list').on('click', '.prepare-update-contributor', function (e) {
        let row = table.row($(this).closest('tr')).data();
        $('#contributor-id').val(row.id);
        $('#fullname').val(row.fullname);
        $('#role').val(row.role);
        $('#slug').val(row.slug);
        $('#image-preview').attr('src', row.image);
    })
    
    $('.btn-form-clear').on('click', function() {
        $('#contributor-id').val('');
        $('#fullname').val('');
        $('#role').val('role');
        $('#slug').val('');
        $('#image').val('');
        $('#image-preview').attr('src', '/storage/images/people/default/male.png');
    });
    
    $('#contributor-form').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this)
        $.ajax({
            method: 'POST',
            url: '/admin/contributors',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                table.ajax.reload(null, false);
                alert(response.message);
            },
            error: function (jqXHR, textStatus, errorThrown){
                console.log(jqXHR, textStatus, errorThrown);
                alert('Error');
            }
        });
    })
}

export { getContributor, initContributorActions }
