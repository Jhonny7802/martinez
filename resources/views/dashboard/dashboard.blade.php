@extends('layouts.app')
@section('title')
    {{ __('messages.dashboard') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.dashboard') }}</h1>
        </div>
        @include('flash::message')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12 col-lg-4 col-sm-6">
                            <div class="col-sm-12">
                                <p class="mt-2"><b>{{ __('messages.lead.leads_overview') }}</b></p>
                                <hr>
                                <canvas id="leadChartId" width="400" height="250" class="mt-2 mb-4"></canvas>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-4 col-sm-6">
                            <div class="col-sm-12">
                                <p class="mt-2"><b>{{ __('messages.project.statistics_by_project_status') }}</b></p>
                                <hr>
                                <canvas id="projectChartId" width="400" height="250" class="mt-2 mb-4"></canvas>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-4 col-sm-6">
                            <div class="col-sm-12">
                                <p class="mt-2"><b>{{ __('messages.ticket.tickets_status') }}</b></p>
                                <hr>
                                <canvas id="ticketChartId" width="400" height="250" class="mt-2 mb-4"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 dashboard-card-css">
                <div class="card card-statistic-2 d-total-one-border">
                    <div class="card-stats">
                        <div class="card-stats-title">
                            <a href="{{route('invoices.index')}}"
                               class="font-weight-bold anchor-underline">{{ __('messages.invoices') }}</a>
                        </div>
                        <div class="card-stats-items d-stat-items-flex">
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $invoiceStatusCount['drafted'] }}</div>
                                <span class="text-warning font-weight-bold">{{ __('messages.common.drafted') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $invoiceStatusCount['unpaid'] }}</div>
                                <span class="text-primary font-weight-bold">{{ __('messages.invoice.unpaid') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $invoiceStatusCount['partially_paid'] }}</div>
                                <span class="text-info font-weight-bold">{{ __('messages.invoice.partially_paid') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $invoiceStatusCount['paid'] }}</div>
                                <span class="text-success font-weight-bold">{{ __('messages.invoice.paid') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-icon shadow-primary d-total-one-bg d-border-radius">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('messages.invoice.total_invoices') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $invoiceStatusCount['total_invoices'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 dashboard-card-css">
                <div class="card card-statistic-2 d-total-two-border">
                    <div class="card-stats">
                        <div class="card-stats-title">
                            <a href="{{route('estimates.index')}}"
                               class="font-weight-bold anchor-underline">{{ __('messages.estimates') }}</a>
                        </div>
                        <div class="card-stats-items d-stat-items-flex">
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $estimateStatusCount['drafted'] }}</div>
                                <span class="text-warning font-weight-bold">{{ __('messages.common.drafted') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $estimateStatusCount['sent'] }}</div>
                                <span class="text-primary font-weight-bold">{{ __('messages.estimate.sent') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $estimateStatusCount['declined'] }}</div>
                                <span class="text-info font-weight-bold">{{ __('messages.estimate.declined') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $estimateStatusCount['accepted'] }}</div>
                                <span class="text-success font-weight-bold">{{ __('messages.estimate.accepted') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $estimateStatusCount['expired'] }}</div>
                                <span class="text-danger font-weight-bold">{{ __('messages.estimate.expired') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-icon shadow-primary d-total-two-bg d-border-radius">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('messages.estimate.total_estimates') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $estimateStatusCount['total_estimates'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 dashboard-card-css">
                <div class="card card-statistic-2 d-total-three-border">
                    <div class="card-stats">
                        <div class="card-stats-title">
                            <a href="{{route('proposals.index')}}"
                               class="font-weight-bold anchor-underline">{{ __('messages.proposals') }}</a>
                        </div>
                        <div class="card-stats-items d-stat-items-flex">
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $proposalStatusCount['drafted'] }}</div>
                                <span class="text-warning font-weight-bold">{{ __('messages.common.drafted') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $proposalStatusCount['open'] }}</div>
                                <span class="text-danger font-weight-bold">{{ __('messages.proposal.open') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $proposalStatusCount['revised'] }}</div>
                                <span class="text-primary font-weight-bold">{{ __('messages.proposal.revised') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $proposalStatusCount['accepted'] }}</div>
                                <span class="text-success font-weight-bold">{{ __('messages.proposal.accepted') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $proposalStatusCount['declined'] }}</div>
                                <span class="text-info font-weight-bold">{{ __('messages.proposal.declined') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-icon shadow-primary d-total-three-bg d-border-radius">
                        <i class="fas fa-scroll"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('messages.proposal.total_proposal') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $proposalStatusCount['total_proposals'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 dashboard-card-css">
                <div class="card card-statistic-2 d-total-four-border">
                    <div class="card-stats">
                        <div class="card-stats-title">
                            <a href="{{route('projects.index')}}"
                               class="font-weight-bold anchor-underline">{{ __('messages.projects') }}</a>
                        </div>
                        <div class="card-stats-items d-stat-items-flex">
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $projectStatusCount['not_started'] }}</div>
                                <span class="text-danger font-weight-bold">{{ __('messages.project.not_started') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $projectStatusCount['in_progress'] }}</div>
                                <span class="text-primary font-weight-bold">{{ __('messages.project.in_progress') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $projectStatusCount['on_hold'] }}</div>
                                <span class="text-warning font-weight-bold">{{ __('messages.project.on_hold') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $projectStatusCount['cancelled'] }}</div>
                                <span class="text-info font-weight-bold">{{ __('messages.project.cancelled') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $projectStatusCount['finished'] }}</div>
                                <span class="text-success font-weight-bold">{{ __('messages.project.finished') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-icon shadow-primary d-total-four-bg d-border-radius">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('messages.project.total_projects') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $projectStatusCount['total_projects'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 dashboard-card-css">
                <div class="card card-statistic-2 d-total-five-border">
                    <div class="card-stats">
                        <div class="card-stats-title">
                            <a href="{{route('members.index')}}"
                               class="font-weight-bold anchor-underline">{{ __('messages.members') }}</a>
                        </div>
                        <div class="card-stats-items d-stat-items-flex">
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $memberCount['active_members'] }}</div>
                                <span class="text-success font-weight-bold">{{ __('messages.common.active') }}</span>
                            </div>
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $memberCount['deactive_members'] }}</div>
                                <span class="text-danger font-weight-bold">{{ __('messages.common.deactive') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-icon shadow-primary d-total-five-bg d-border-radius">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('messages.member.total_members') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $memberCount['total_members'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 dashboard-card-css">
                <div class="card card-statistic-2 d-total-six-border">
                    <div class="card-stats">
                        <div class="card-stats-title">
                            <a href="{{route('customers.index')}}"
                               class="font-weight-bold anchor-underline">{{ __('messages.customers') }}</a>
                        </div>
                        <div class="card-stats-items d-stat-items-flex">
                            <div class="card-stats-item d-stat-item-flex">
                                <div class="card-stats-item-count">{{ $customerCount['total_customers'] }}</div>
                                <span class="text-success font-weight-bold">{{ __('messages.common.active') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-icon shadow-primary d-total-six-bg d-border-radius">
                        <i class="fas fa-street-view"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ __('messages.customer.total_customers') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $customerCount['total_customers'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12 col-lg-4 col-sm-6">
                            <div class="col-sm-12">
                                <p class="text-dark mt-3">
                                    <a href="{{ route('invoices.index') }}"
                                       class="inline-block font-weight-bold anchor-underline">{{ __('messages.invoice.invoice_overview') }}</a>
                                </p>
                                <hr>
                            </div>
                            @php
                                $style = 'style';
                                $width = 'width';
                            @endphp
                            <div class="col-md-12 d-flex">
                                <span class="inline-block font-weight-bold text-warning"> {{ __('messages.common.drafted') }}</span>
                            </div>
                            <div class="col-md-12 progress-finance-status">
                                <div class="progress progress-bar-mini height-25 mt-3">
                                    <div class="progress-bar" role="progressbar"
                                         aria-valuenow="{{ $invoiceStatusCount['drafted'] * 100/totalCountForDashboard($invoiceStatusCount['total_invoices']) }}" aria-valuemin="0" aria-valuemax="100" {{$style}}="{{$width}} :{{ $invoiceStatusCount['drafted'] * 100/totalCountForDashboard($invoiceStatusCount['total_invoices']) }}%">
                                    {{ number_format($invoiceStatusCount['drafted'] * 100/totalCountForDashboard($invoiceStatusCount['total_invoices']),2) }}%
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 mt-3 d-flex">
                            <span class="inline-block font-weight-bold text-primary">{{ __('messages.invoice.unpaid_cap') }}</span>
                        </div>
                        <div class="col-md-12 progress-finance-status">
                            <div class="progress progress-bar-mini height-25 mt-3">
                                <div class="progress-bar" role="progressbar" aria-valuenow="{{ $invoiceStatusCount['unpaid'] * 100/totalCountForDashboard($invoiceStatusCount['total_invoices']) }}"
                                     aria-valuemin="0" aria-valuemax="100" {{$style}}="{{$width}} :{{ $invoiceStatusCount['unpaid'] * 100/totalCountForDashboard($invoiceStatusCount['total_invoices']) }}%">
                                {{ number_format($invoiceStatusCount['unpaid'] * 100/totalCountForDashboard($invoiceStatusCount['total_invoices']),2) }}%
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 mt-3 d-flex">
                        <span class="inline-block font-weight-bold text-success"> {{ __('messages.invoice.paid_cap') }}</span>
                            </div>
                            <div class="col-md-12 progress-finance-status">
                                <div class="progress progress-bar-mini height-25 mt-3">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $invoiceStatusCount['paid'] * 100/totalCountForDashboard($invoiceStatusCount['total_invoices']) }}"
                                         aria-valuemin="0" aria-valuemax="100" {{$style}}="{{$width}}: {{ $invoiceStatusCount['paid'] * 100/totalCountForDashboard($invoiceStatusCount['total_invoices']) }}%">
                                    {{ number_format($invoiceStatusCount['paid'] * 100/totalCountForDashboard($invoiceStatusCount['total_invoices']),2) }}%
                                </div>
                            </div>
                </div>
            </div>

            <div class="col-md-12 col-lg-4 col-sm-6">
                <div class="col-sm-12">
                    <p class="text-dark mt-3">
                        <a href="{{ route('estimates.index') }}"
                           class="inline-block font-weight-bold anchor-underline">{{ __('messages.estimate.estimate_overview') }}</a>
                    </p>
                    <hr>
                </div>
                <div class="col-sm-12 d-flex">
                    <span class="inline-block font-weight-bold ml-2 text-warning"> {{ __('messages.common.drafted') }}</span>
                </div>
                <div class="col-md-12 text-right progress-finance-status">
                    <div class="progress progress-bar-mini height-25 mt-3">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{ $estimateStatusCount['drafted'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']) }}" aria-valuemin="0" aria-valuemax="100" {{$style}}="{{$width}} :{{ $estimateStatusCount['drafted'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']) }}%">
                        {{ number_format($estimateStatusCount['drafted'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']),2) }}%
                    </div>
                </div>
            </div>

            <div class="col-sm-12 mt-3 d-flex">
                <span class="inline-block font-weight-bold ml-2 text-primary"> {{ __('messages.estimate.sent') }}</span>
            </div>
            <div class="col-md-12 text-right progress-finance-status">
                <div class="progress progress-bar-mini height-25 mt-3">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $estimateStatusCount['sent'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']) }}"
                         aria-valuemin="0" aria-valuemax="100" {{$style}}="{{$width}} :{{ $estimateStatusCount['sent'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']) }}%">
                    {{ number_format($estimateStatusCount['sent'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']),2) }}%
                </div>
            </div>
        </div>

        <div class="col-sm-12 mt-3 d-flex">
            <span class="inline-block font-weight-bold ml-2 text-success"> {{ __('messages.estimate.accepted') }}</span>
        </div>
        <div class="col-md-12 text-right progress-finance-status">
            <div class="progress progress-bar-mini height-25 mt-3">
                <div class="progress-bar" role="progressbar" aria-valuenow="{{ $estimateStatusCount['accepted'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']) }}"
                     aria-valuemin="0" aria-valuemax="100" {{$style}}="{{$width}} :{{ $estimateStatusCount['accepted'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']) }}%">
                {{ number_format($estimateStatusCount['accepted'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']),2) }} %
                </div>
            </div>
        </div>

        <div class="col-sm-12 mt-3 d-flex">
            <span class="inline-block font-weight-bold ml-2 text-danger"> {{ __('messages.estimate.expired') }}</span>
        </div>
        <div class="col-md-12 text-right progress-finance-status">
            <div class="progress progress-bar-mini height-25 mt-3">
                <div class="progress-bar" role="progressbar" aria-valuenow="{{ $estimateStatusCount['expired'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']) }}" aria-valuemin="0" aria-valuemax="100" {{$style}}="{{$width}} :{{ $estimateStatusCount['expired'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']) }}%">
                {{ number_format($estimateStatusCount['expired'] * 100/totalCountForDashboard($estimateStatusCount['total_estimates']),2) }}%
            </div>
        </div>
        </div>
        </div>

        <div class="col-md-12 col-lg-4 col-sm-6">
            <div class="col-sm-12">
                <p class="text-dark mt-3">
                    <a href="{{ route('proposals.index') }}"
                       class="inline-block font-weight-bold anchor-underline">{{ __('messages.proposal.proposal_overview') }}</a>
                </p>
                <hr>
            </div>
            <div class="col-sm-12 d-flex">
                <span class="inline-block font-weight-bold text-warning">{{ __('messages.common.drafted') }}</span>
            </div>
            <div class="col-md-12 text-right progress-finance-status">
                <div class="progress progress-bar-mini height-25 mt-3">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $proposalStatusCount['drafted'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']) }}" aria-valuemin="0" aria-valuemax="100" {{$style}}="{{$width}} :{{ $proposalStatusCount['drafted'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']) }}%">
                    {{ number_format($proposalStatusCount['drafted'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']),2) }}%
                </div>
            </div>
        </div>

        <div class="col-sm-12 mt-3 d-flex">
            <span class="inline-block font-weight-bold text-danger"> {{ __('messages.proposal.open') }}</span>
        </div>
        <div class="col-md-12 text-right progress-finance-status">
            <div class="progress progress-bar-mini height-25 mt-3">
                <div class="progress-bar" role="progressbar" aria-valuenow="{{ $proposalStatusCount['open'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']) }}"
                     aria-valuemin="0" aria-valuemax="100" {{$style}}="{{$width}} : {{ $proposalStatusCount['open'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']) }}%">
                {{ number_format($proposalStatusCount['open'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']),2) }}%
            </div>
        </div>
        </div>

        <div class="col-sm-12 mt-3 d-flex">
            <span class="inline-block font-weight-bold text-primary"> {{ __('messages.proposal.revised') }}</span>
        </div>
        <div class="col-md-12 text-right progress-finance-status">
            <div class="progress progress-bar-mini height-25 mt-3">
                <div class="progress-bar" role="progressbar" aria-valuenow="{{ $proposalStatusCount['revised'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']) }}" aria-valuemin="0" aria-valuemax="100" {{$style}}="{{$width}} : {{ $proposalStatusCount['revised'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']) }}%">
                {{ number_format($proposalStatusCount['revised'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']),2) }}%
            </div>
        </div>
        </div>

        <div class="col-sm-12 mt-3 d-flex">
            <span class="inline-block font-weight-bold text-success"> {{ __('messages.proposal.accepted') }}</span>
        </div>
        <div class="col-md-12 text-right progress-finance-status">
            <div class="progress progress-bar-mini height-25 mt-3">
                <div class="progress-bar" role="progressbar" aria-valuenow="{{ $proposalStatusCount['accepted'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']) }}" aria-valuemin="0" aria-valuemax="100" {{$style}}="{{$width}} : {{ $proposalStatusCount['accepted'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']) }}%">{{ number_format($proposalStatusCount['accepted'] * 100/totalCountForDashboard($proposalStatusCount['total_proposals']),2) }}
                %
            </div>
        </div>
        </div>
        </div>
        </div>
        <hr>
        </div>
        </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-dark">{{ __('messages.common.weekly_payment_records') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="weeklyPaymentChart" width="400" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-dark">{{ __('messages.common.incomes_vs_expenses') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeVsExpenseChart" width="400" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-dark w-100">{{ __('messages.contract.contracts_expiring_this_month') }}</h6>
                        <div>
                            {!! Form::select('month', $months, $currentMonth,['class' => 'form-control', 'id' => 'monthId']) !!}
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-responsive-sm" id="contractExpiredTable">
                            <thead class="text-white contract-table-bg-color">
                            <tr>
                                <td>{{ __('messages.contract.subject') }}</td>
                                <td>{{ __('messages.contract.customer_id') }}</td>
                                <td>{{ __('messages.contract.start_date') }}</td>
                                <td>{{ __('messages.contract.end_date') }}</td>
                            </tr>
                            </thead>
                            <tbody class="expiring-contracts">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('dashboard.templates.templates')
@endsection
@section('page_scripts')
    <script>
        let leadData = JSON.parse('@json($leadStatuses)');
        let projectStatus = JSON.parse('@json($projectStatus)');
        let projectStatusCounts = JSON.parse('@json($projectStatusCount)');
        let ticketStatusData = JSON.parse('@json($ticketStatus)');
        let currentWeekInvoices = JSON.parse('@json($currentWeekInvoices)');
        let lastWeekInvoices = JSON.parse('@json($lastWeekInvoices)');
        let incomeAndExpenseData = JSON.parse('@json($monthWiseRecords)');
        let expiringContractLists = JSON.parse('@json($contractsCurrentMonths)');
    </script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/chart/Chart.min.js') }}"></script>
    <script src="{{ mix('assets/js/dashboard/dashboard.js') }}"></script>
@endsection
