@extends(user_env().'.layouts.main')

@section('sidebar')
    @component(user_env().'.components.sidebar')
    @endcomponent
@endsection

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body m-portlet__body--no-padding">
            <div class="m-invoice-1">
                <div class="m-invoice__wrapper">
                    <div class="m-invoice__head" style="background-image: url(/root/assets/app/media/img/bg/bg-6.jpg);">
                        <div class="d-flex justify-content-end p-4">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover">
                                    <a href="javascript:void(0);" class="m-portlet__nav-link m-dropdown__toggle
                                    dropdown-toggle btn btn-sm btn-{{ $reservation->status_class }} m-btn m-btn--pill text-white">
                                        <span>{{ $reservation->status }}</span>
                                    </a>

                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav">
                                                        @if(in_array(strtolower($reservation->status), ['reserved', 'pending']))
                                                            <li class="m-nav__item">
                                                                <a href="#" class="m-nav__link" title="Set to paid">
                                                                    <span class="m-nav__link-text">Set to
                                                                        <strong class="m--font-success">Paid</strong>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        @endif

                                                        @if(in_array(strtolower($reservation->status), ['pending']))
                                                            <li class="m-nav__item">
                                                                <a href="#" class="m-nav__link" title="Set to reserved">
                                                                    <span class="m-nav__link-text">Set to
                                                                        <strong class="m--font-info">Reserved</strong>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        @endif

                                                        @if(! in_array(strtolower($reservation->status), ['cancelled', 'void']))
                                                            <li class="m-nav__item">
                                                                <a href="#" class="m-nav__link" title="Set to cancelled">
                                                                    <span class="m-nav__link-text">Set to
                                                                        <strong class="m--font-danger">Cancelled</strong>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="m-invoice__container m-invoice__container--centered">
                            <div class="m-invoice__logo">
                                <a href="javascript:void(0);">
                                    <h1>INVOICE</h1>
                                </a>
                                <a href="javascript:void(0);">
                                    <img  src="/front/logo3.png" style="width: 200px;">
                                </a>
                            </div>
                            <span class="m-invoice__desc">
                                <span>{{ $reservation->creator->full_name }},</span>
                                <span>{{ $app['address'] }}</span>
                            </span>
                            <div class="m-invoice__items">
                                <div class="m-invoice__item">
                                    <span class="m-invoice__subtitle">CREATED</span>
                                    <span class="m-invoice__text">
                                        {{ Carbon::parse($reservation->created_at)->toFormattedDateString() }}
                                    </span>
                                </div>
                                <div class="m-invoice__item">
                                    <span class="m-invoice__subtitle">REFERENCE NO.</span>
                                    <span class="m-invoice__text">
                                        {{ $reservation->number }}
                                    </span>
                                </div>
                                <div class="m-invoice__item">
                                    <span class="m-invoice__subtitle">INVOICE TO:</span>
                                    <span class="m-invoice__text">
                                        {{ $reservation->user->full_name }}, <br>
                                        {{ $reservation->user->address }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-invoice__body m-invoice__body--centered">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>DESCRIPTION</th>
                                        <th>UNIT PRICE</th>
                                        <th>TOTAL PRICE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservation->products as $index => $product)
                                        <tr>
                                            <th scope="row" style="width: 5%;">{{ $index + 1 }}</th>
                                            <td>{{ $product->product->name }}</td>
                                            <td>{{ $product->amount_original }}</td>
                                            <td>{{ $product->amount_payable }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="m-invoice__footer">
                        <div class="m-invoice__container m-invoice__container--centered">
                            <div class="m-invoice__content">
                                <span>TOTAL</span>
                                <span class="m-invoice__price">
                                    {{ $reservation->amount_payable }}
                                </span>
                                <span>*Taxes Included</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection