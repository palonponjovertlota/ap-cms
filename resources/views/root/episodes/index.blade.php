@extends('root.layouts.main')

@section('sidebar')
    @component(user_env('components.sidebar'))
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
                        This is the list of all <span class="m--font-boldest">Episodes</span>
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
                            <!-- Published -->
                            <div class="col-md-4">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label>Published:</label>
                                    </div>
                                    <div class="m-form__control">
                                        <select class="form-control m-bootstrap-select" id="published">
                                            <option value="">All</option>
                                            <option value="1">Published</option>
                                            <option value="0">Not</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-md-none m--margin-bottom-10"></div>
                            </div>
                            <!--/. Published -->

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
                        <a href="{{ route(user_env('episodes.create')) }}" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                            <span>
                                <i class="la la-plus"></i><span>Create</span>
                            </span>
                        </a>
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>
            <!--end: Search Form -->

            <!-- Superusers -->
            <div id="table" class="m-datatable"></div>
            <!--/. Superusers -->
        </div>
        <!--/. Portlet body -->
    </div>
    <!--/. Portlet -->

    <!-- Destroy Modal -->
    @component(user_env('components.modal'))
        @slot('name')
            confirm_destroy_episode
        @endslot

        <!-- Destroy Form -->
        <form method="POST" action="" id="form_destroy_episode" style="display: none;">
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
                                url: '{!! route(user_env('episodes.datatables.index')) !!}',
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
                            template: function(episode, index) {
                                return index + 1;
                            },
                        },
                        {
                            field: 'Actions',
                            width: 120,
                            title: 'Actions',
                            sortable: false,
                            overflow: 'visible',
                            template: function (episode, index, datatable) {
                                var dropup = (datatable.getPageSize() - index) <= 4 ? 'dropup' : '';
                                var toggle = episode.active ? 'off' : 'on';

                                return '\
                                    <a href="'+ episode.DT_RowData.edit_route +'" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit episode"><i class="la la-edit"></i>\
                                    </a>\
                                    <a href="#" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill destroy_resource" title="Destroy episode" \
                                        data-toggle="modal" \
                                        data-target="#confirm_destroy_episode" \
                                        data-form="#form_destroy_episode" \
                                        data-action="' + episode.DT_RowData.destroy_route + '" \
                                        data-resource-name="' + episode.title + '" \
                                    >\
                                        <i class="la la-trash"></i>\
                                    </a>\
                                ';
                            },
                        },
                        {
                            field: 'tutorial',
                            title: 'Tutorial',
                            width: 100,
                            template: function(episode) {
                                if (episode.tutorial != null) {
                                    return '\
                                        <a href="'+ episode.DT_RowData.tutorial_edit_route +'" class="m--font-link">'
                                            + episode.tutorial.title +
                                        '</a>\
                                    ';
                                }

                                return;
                            }
                        },
                        {
                            field: 'number',
                            title: 'Number',
                            width: 75
                        },
                        {
                            field: 'title',
                            title: 'Title',
                            width: 150
                        },
                        {
                            field: 'published',
                            title: 'Published',
                            width: 75,
                            template: function(episode) {
                                var published = episode.published ? 1 : 0;
                                var statuses = {
                                    0: {'title': 'Not', 'class': ' m-badge--metal'},
                                    1: {'title': 'Published', 'class': 'm-badge--success'},
                                };

                                return '<span class="m-badge ' + statuses[published].class +
                                ' m-badge--wide">' + statuses[published].title + '</span>';
                            },
                        },                        
                        {
                            field: 'body',
                            title: 'Body',
                            width: 350
                        },
                        {
                            field: 'creator',
                            title: 'Creator',
                            width: 150,
                            template: function(episode) {
                                if (episode.creator != null) {
                                    var name = episode.creator.name;
                                    var image = episode.creator.file_path;

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
                            template: function (episode) {
                                if (episode.created_at == null) {
                                    return '';
                                }

                                return moment(episode.created_at).format('MMMM Do, YYYY @ hh:mm:ss A');
                            },
                        },
                        {
                            field: 'updater',
                            title: 'Updater',
                            width: 150,
                            template: function(episode) {
                                if (episode.updater != null) {
                                    var name = episode.updater.name;
                                    var image = episode.updater.file_path;

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
                            template: function(episode) {
                                if (episode.updated_at == null) {
                                    return '';
                                }

                                return moment(episode.updated_at).format('MMMM Do, YYYY @ hh:mm:ss A');
                            },
                        },
                    ],
                });

                $('select[id=published]').on('change', function() {
                    datatable.search($(this).val().toLowerCase(), 'published');
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