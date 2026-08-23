window.shouldExport = false;
$('#filterForm').on('submit', function () {
    if (shouldExport) {
        let filter = {};

        $('#filterForm input[type=text], #filterForm input[type=number], #filterForm input[type=date], #filterForm select').each(function (i, el) {
            let input = $(el);
            let propertyName = input.prop('name');

            if (input.val()) {
                filter[propertyName] = input.val();
            }
        });

        window.location = route(route().current(), {...route().params, filter: filter, "export": 'csv'});
    }

    dataTable.draw();

    return false;
});

// DataTable default config
$.extend(true, $.fn.dataTable.defaults, {
    searching: false,
    ordering: false,
    orderCellsTop: true,
    fixedHeader: true,
    processing: true,
    serverSide: true,
    ajax: {
        data: function (data) {
            delete data.columns;
            delete data.search;
            data['filter'] = {};

            $('#filterForm input[type=text], #filterForm input[type=number], #filterForm input[type=date], #filterForm select').each(function (i, el) {
                let input = $(el);
                let propertyName = input.prop('name');

                if (input.val()) {
                    data['filter'][propertyName] = input.val();
                }
            });
        },
    },
    drawCallback: function (settings) {
        let pageInfo = dataTable.page.info();
        let totalPages = pageInfo.pages;

        let html = `
            <div class="dataTables_length">
                <label>
                    Go to Page
                    <select name="datatable_page_list" id="dataTablePageList" class="custom-select custom-select-sm form-control form-control-sm">`;

        for (let count = 1; count <= totalPages; count++) {
            let pageNumber = count - 1;
            let selected = (parseInt(pageInfo.page) === parseInt(pageNumber)) ? 'selected' : '';

            html += `<option value="${pageNumber}" ${selected}>${count}</option>`;
        }

        html += `
                    </select>
                    of ${totalPages}
                </label>
            </div>`;

        $('.dataTablesPageSelect').html(html);
    },
    dom: `
            <'row'
                <'col-sm-12 col-md-6 col-6'l>
                <'col-sm-12 col-md-6 col-6 text-right dataTablesPageSelect'>
            >
            <'row'
                <'col-sm-12 col-12'tr>
            >
            <'row'
                <'col-sm-12 col-md-5 col-12'i>
                <'col-sm-12 col-md-7 col-12 dataTables_pager'p>
            >
        `,
    pageLength: 10,
    searchDelay: 1000,
    lengthMenu: [
        10, 25, 50, 100, 250
    ]
});

$('body').on('change', '#dataTablePageList', function () {
    dataTable.page(
        parseInt(this.value)
    ).draw('page');
});
