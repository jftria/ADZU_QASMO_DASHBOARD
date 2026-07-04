@extends('layouts.app')

@section('title', 'Water Bills - Energy Crisis Dashboard')

@section('content')
    <div class="portal-panel">
        <div class="portal-panel-header">
            <div class="title"><i class="bi bi-droplet"></i> Water Consumption</div>
            <div class="d-flex gap-2 no-print">
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('reports.index', ['module' => 'water-bills']) }}"><i class="bi bi-bar-chart me-1"></i> View Full Report</a>
                <a class="btn btn-sm btn-primary" href="{{ route('water-bills.create') }}"><i class="bi bi-plus-lg me-1"></i> Create</a>
            </div>
        </div>
        <div class="portal-panel-body">
            <form method="GET" action="{{ route('water-bills.index') }}" class="row g-2 mb-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Responder or year">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Month</label>
                    <select name="reporting_month" class="form-select form-select-sm">
                        <option value="">All</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected(request('reporting_month') == (string) $m)>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Year</label>
                    <input type="number" name="reporting_year" value="{{ request('reporting_year') }}" class="form-control form-control-sm" placeholder="Year">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search me-1"></i> Search</button>
                    <a href="{{ route('water-bills.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                </div>
            </form>

            @if ($waterBills->isEmpty())
                <p class="text-muted">No water bill records found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Responder</th>
                                <th>Month</th>
                                <th>Year</th>
                                @foreach (App\Models\WaterBill::FACILITY_FIELDS as $field => $label)
                                    <th>{{ $label }}</th>
                                @endforeach
                                <th>Total Bill</th>
                                <th>Top Facility</th>
                                <th class="no-print">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($waterBills as $bill)
                                <tr>
                                    <td>{{ $bill->responder_name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::create()->month($bill->reporting_month)->format('F') }}</td>
                                    <td>{{ $bill->reporting_year }}</td>
                                    @foreach (App\Models\WaterBill::FACILITY_FIELDS as $field => $label)
                                        <td class="text-end">₱{{ number_format((float) ($bill->{$field} ?? 0), 2) }}</td>
                                    @endforeach
                                    <td class="text-end"><strong>₱{{ number_format($bill->totalBill(), 2) }}</strong></td>
                                    <td>
                                        @if ($top = $bill->topContributor())
                                            {{ $top['facility'] }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="no-print">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('water-bills.show', $bill) }}" class="btn btn-outline-primary"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('water-bills.edit', $bill) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                            <form method="POST" action="{{ route('water-bills.destroy', $bill) }}" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $waterBills->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
