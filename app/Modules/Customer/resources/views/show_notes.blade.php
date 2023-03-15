<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Notes List') }} </label>
    </div>
</div>
<div class="row">
    <table class="table table-striped" id="invoices_table">
        <thead>
            <tr>
                <th width="10%">Admin</th>
                <th width="10%">Note</th>
                <th width="10%">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notes as $key => $note)
                <tr>
                    <td>{{ $note['admin_name'] }}</td>
                    <td>{{ $note['note'] }}</td>
                    <td>{{ $note['date'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>