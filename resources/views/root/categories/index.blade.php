@extends(user_env().'.layouts.main')

@section('sidebar')
    @component(user_env().'.components.sidebar')
    @endcomponent
@endsection

@section('content')
    <!-- Portlet -->
    <div class="m-portlet m-portlet--mobile">
        <!-- Portlet head -->
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        This is the list of all <span class="m--font-boldest">Categories</span>
                    </h3>
                </div>
            </div>
        </div>
        <!--/. Portlet head -->

        <!-- Portlet body -->
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="form-group m-form__group row align-items-center">
                            <!-- Availability -->
                            <div class="col-md-4">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label>Availability:</label>
                                    </div>
                                    <div class="m-form__control">
                                        <select class="form-control m-bootstrap-select" id="availability">
                                            <option value="">All</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-md-none m--margin-bottom-10"></div>
                            </div>
                            <!--/. Availability -->

                            <!-- Search -->
                            <div class="col-md-4">
                                <div class="m-input-icon m-input-icon--left">
                                    <input type="text" class="form-control m-input" placeholder="Search..." id="generalSearch">
                                    <span class="m-input-icon__icon m-input-icon__icon--left">
                                        <span><i class="la la-search"></i></span>
                                    </span>
                                </div>
                            </div>
                            <!--/. Search -->
                        </div>
                    </div>

                    <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                        <a href="{{ route(user_env().'.categories.create') }}" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                            <span>
                                <i class="la la-plus"></i><span>Create</span>
                            </span>
                        </a>
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>
            <!--end: Search Form -->

            <!-- Categories -->
            <div id="table" class="m-datatable"></div>
            <!--/. Categories -->
        </div>
        <!--/. Portlet body -->
    </div>
    <!--/. Portlet -->

    <!-- Toggle Modal -->
    @component(user_env().'.components.modal')
        @slot('name')
            confirm_toggle_category
        @endslot

        <div class="text-wrapper"></div>

        <!-- Toggle Form -->
        <form method="POST" action="" id="form_toggle_category" style="display: none;">
            @method('PATCH')
            @csrf
        </form>
    @endcomponent

    <!-- Destroy Modal -->
    @component(user_env('components.modal'))
        @slot('name')
            confirm_destroy_category
        @endslot

        <!-- Destroy Form -->
        <form method="POST" action="" id="form_destroy_category" style="display: none;">
            @method('DELETE')
            @csrf
        </form>
    @endcomponent
@endsection

@section('scripts')
    <script>
        var data = function() {
            var dataInit = function() {
                var datatable = $('#table').mDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                method: 'GET',
                                url: '{!! route(user_env().'.categories.datatables.index') !!}',
                                map: function(raw) {
                                    var dataSet = raw;
                                    if (typeof raw.data !== 'undefined') {
                                        dataSet = raw.data;
                                    }
                                    return dataSet;
                                },
                            },
                            pageSize: 10,
                            serverPaging: true,
                            serverFiltering: true,
                            serverSorting: true
                        },
                    },
                    layout: {
                        theme: 'default',
                        scroll: false,
                        footer: false
                    },
                    sortable: true,
                    pagination: true,
                    search: {
                        input: $('#generalSearch')
                    },
                    rows: {
                        autoHide: true,
                    },
                    columns: [
                        {
                            field: '#',
                            title: '#',
                            width: 30,
                            type: 'number',
                            template: function(category, index) {
                                return index + 1;
                            },
                        },
                        {
                            field: 'actions',
                            width: 120,
                            title: 'Actions',
                            sortable: false,
                            overflow: 'visible',
                            template: function (category, index, datatable) {
                                var dropup = (datatable.getPageSize() - index) <= 4 ? 'dropup' : '';
                                var toggle = category.active ? 'off' : 'on';
                                var product_create = category.active ? '' : 'd-none';

                                return '\
                                    <div class="dropdown ' + dropup + '">\
                                        <a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">\
                                            <i class="la la-ellipsis-h"></i>\
                                        </a>\
                                        <div class="dropdown-menu dropdown-menu-right">\
                                            <a href="' + category.DT_RowData.product_create_route + '" class="dropdown-item \
                                                '+ product_create +'"> \
                                                <i class="la la-shopping-cart"></i> Create product \
                                            </a>\
                                            <a href="'+ category.DT_RowData.image_route +'" class="dropdown-item">\
                                                <i class="la la-image"></i> Images\
                                            </a>\
                                            <a href="#" class="dropdown-item toggle_resource" \
                                                data-toggle="modal" \
                                                data-target="#confirm_toggle_category" \
                                                data-form="#form_toggle_category" \
                                                data-action="' + category.DT_RowData.toggle_route + '" \
                                                data-resource-name="' + category.name + '"> \
                                                <i class="la la-toggle-'+ toggle +'"></i> Toggle-'+ toggle +'\
                                            </a>\
                                        </div>\
                                    </div>\
                                    <a href="'+ category.DT_RowData.edit_route +'" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit category"><i class="la la-edit"></i>\
                                    </a>\
                                    <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill destroy_resource" title="Destroy category" \
                                        data-toggle="modal" \
                                        data-target="#confirm_destroy_category" \
                                        data-form="#form_destroy_category" \
                                        data-action="' + category.DT_RowData.destroy_route + '" \
                                        data-resource-name="' + category.name + '" \
                                    >\
                                        <i class="la la-trash"></i>\
                                    </a>\
                                ';
                            },
                        },
                        {
                            field: 'name',
                            title: 'Name',
                            width: 150,
                            template: function(category) {
                                var name = category.name;
                                var image = category.file_path;

                                if (image != null) {
                                    output = '<div class="m-card-user m-card-user--sm">\
                                        <div class="m-card-user__pic">\
                                            <img src="' + image + '" class="m--img-rounded m--marginless" alt="photo">\
                                        </div>\
                                        <div class="m-card-user__details">\
                                            <span class="m-card-user__name">' + name + '</span>\
                                        </div>\
                                    </div>';
                                } else {
                                    var stateNo = mUtil.getRandomInt(0, 7);
                                    var states = [
                                        'success', 'brand', 'danger', 'accent', 'warning', 'metal', 'primary', 'info'
                                    ];
                                    var state = states[stateNo];

                                    output = '<div class="m-card-user m-card-user--sm">\
                                        <div class="m-card-user__pic">\
                                            <div class="m-card-user__no-photo m--bg-fill-' + state + '"><span>' +
                                                name.substring(0, 1) + '</span>\
                                            </div>\
                                        </div>\
                                        <div class="m-card-user__details">\
                                            <span class="m-card-user__name">' + name + '</span>\
                                        </div>\
                                    </div>';
                                }

                                return output;
                            },
                        },                        
                        {
                            field: 'products',
                            title: 'Products',
                            width: 75,
                            template: function(category) {
                                if (category.products != null) {
                                    return category.products.length;
                                }

                                return 0;
                            }
                        },
                        {
                            field: 'active',
                            title: 'Active',
                            width: 75,
                            template: function(category) {
                                var active = category.active ? 1 : 0;
                                var statuses = {
                                    0: {'title': 'Inactive', 'class': ' m-badge--metal'},
                                    1: {'title': 'Active', 'class': ' m-badge--success'},
                                };

                                return '<span class="m-badge ' + statuses[active].class +
                                ' m-badge--wide">' + statuses[active].title + '</span>';
                            },
                        },
                        {
                            field: 'description',
                            title: 'Description',
                            width: 350
                        },
                        {
                            field: 'creator',
                            title: 'Creator',
                            width: 150,
                            template: function(category) {
                                if (category.creator != null) {
                                    var name = category.creator.name;
                                    var image = category.creator.file_path;

                                    if (image != null) {
                                        output = '<div class="m-card-user m-card-user--sm">\
                                            <div class="m-card-user__pic">\
                                                <img src="' + image + '" class="m--img-rounded m--marginless" alt="photo">\
                                            </div>\
                                            <div class="m-card-user__details">\
                                                <span class="m-card-user__name">' + name + '</span>\
                                            </div>\
                                        </div>';
                                    } else {
                                        var stateNo = mUtil.getRandomInt(0, 7);
                                        var states = [
                                            'success', 'brand', 'danger', 'accent', 'warning', 'metal', 'primary', 'info'
                                        ];
                                        var state = states[stateNo];

                                        output = '<div class="m-card-user m-card-user--sm">\
                                            <div class="m-card-user__pic">\
                                                <div class="m-card-user__no-photo m--bg-fill-' + state + '"><span>' +
                                                    name.substring(0, 1) + '</span>\
                                                </div>\
                                            </div>\
                                            <div class="m-card-user__details">\
                                                <span class="m-card-user__name">' + name + '</span>\
                                            </div>\
                                        </div>';
                                    }

                                    return output;
                                }
                            },
                        },
                        {
                            field: 'created_at',
                            title: 'Created',
                            width: 200,
                            template: function (category) {
                                if (category.created_at == null) {
                                    return '';
                                }

                                return moment(category.created_at).format('MMMM Do, YYYY @ hh:mm:ss A');
                            },
                        },
                        {
                            field: 'updater',
                            title: 'Updater',
                            width: 150,
                            template: function(category) {
                                if (category.updater != null) {
                                    var name = category.updater.name;
                                    var image = category.updater.file_path;

                                    if (image != null) {
                                        output = '<div class="m-card-user m-card-user--sm">\
                                            <div class="m-card-user__pic">\
                                                <img src="' + image + '" class="m--img-rounded m--marginless" alt="photo">\
                                            </div>\
                                            <div class="m-card-user__details">\
                                                <span class="m-card-user__name">' + name + '</span>\
                                            </div>\
                                        </div>';
                                    } else {
                                        var stateNo = mUtil.getRandomInt(0, 7);
                                        var states = [
                                            'success', 'brand', 'danger', 'accent', 'warning', 'metal', 'primary', 'info'
                                        ];
                                        var state = states[stateNo];

                                        output = '<div class="m-card-user m-card-user--sm">\
                                            <div class="m-card-user__pic">\
                                                <div class="m-card-user__no-photo m--bg-fill-' + state + '"><span>' +
                                                    name.substring(0, 1) + '</span>\
                                                </div>\
                                            </div>\
                                            <div class="m-card-user__details">\
                                                <span class="m-card-user__name">' + name + '</span>\
                                            </div>\
                                        </div>';
                                    }

                                    return output;
                                }
                            },
                        },
                        {
                            field: 'updated_at',
                            title: 'Updated',
                            width: 200,
                            template: function(category) {
                                if (category.updated_at == null) {
                                    return '';
                                }

                                return moment(category.updated_at).format('MMMM Do, YYYY @ hh:mm:ss A');
                            },
                        },
                    ],
                });

                $('select[id=availability]').on('change', function() {
                    datatable.search($(this).val(), 'active');
                }).selectpicker();
            };

            return {
                init: function() {
                    dataInit();
                },
            };
        }();

        $(document).ready(function(e) {
            data.init();
        });
    </script>
@endsection