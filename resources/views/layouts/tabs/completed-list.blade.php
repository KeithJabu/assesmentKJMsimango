@php use App\AssessmentIncludes\Classes\AssessmentInterface; @endphp
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Basic</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="completed-jobs-table" class="display table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Class name</th>
                            <th>Method</th>
                            <th>parameter</th>
                            <th>status</th>
                            <th>retry_count</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script type="text/javascript">
        let response_completed = $('#completed-jobs-table').DataTable({
            serverSide: true,
            processing: true,
            searching: false,
            ajax: {
                url: "{{ route('search.datatable', AssessmentInterface::COMPLETED) }}",
                data: function (data) {
                    data.id = $('input[name=id]').val();
                    data.name = $('input[name=name]').val();
                    data.surname = $('input[name=surname]').val();
                    data.email = $('input[name=email]').val();
                }
            },
            columns: [
                {data: 'class', name: 'class', orderable: true, searchable: true},
                {data: 'method', name: 'method', orderable: true, searchable: true},
                {data: 'parameters', name: 'parameters', orderable: true, searchable: true},
                {data: 'status', name: 'status', orderable: true, searchable: false},
                {data: 'retry_count', name: 'retry_count', orderable: true, searchable: false},
            ],
        });
    </script>
@endsection
