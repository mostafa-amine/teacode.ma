
let table = undefined;
function getContributor() {
    table = $('#contributors-list').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'GET',
            url: '/admin/contributors',
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
                data: "id", name: "id", title: "Actions",
                render: function(data, type, row) {
                    return `<div class="actions d-flex justify-content-around">
                        <div class="update-contributor cursor-pointer fs-2"><i class="fa-solid fa-pen-to-square"></i></div>
                        <div class="delete-contributor cursor-pointer fs-2"><i class="fa-solid fa-trash"></i></div>
                    </div>`;
                }
            },
        ]
    });
}
function initContributorActions(){
    $('.contributor').on('click', '.delete-contributor', function (e) {
        e.preventDefault();
        if (!confirm('Are you sur ?')) {
            return;
        }
        $.ajax({
            method: 'POST',
            url: $(this).data('url'),
            data: {'deleting': true},
            success: function (response) {
                console.log(response);
                alert('Deleted');
                history.back();
            },
            error: function (jqXHR, textStatus, errorThrown){
                console.log(jqXHR, textStatus, errorThrown);
                alert('Error');
            }
        });
    });

    $('#contributors-list').on('click', '.update-contributor', function (e) {
        let row = table.row($(this).closest('tr')).data();
        $('#contributor-id').val(row.id);
        $('#fullname').val(row.fullname);
        $('#role').val(row.role);
        $('#slug').val(row.slug);
        $('#image-preview').attr('src', row.image);
    })
}

export { getContributor, initContributorActions }
