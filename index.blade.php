   <!-- lead  -->
   <div id="uploadCsvModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Lead CSV </h4>
                </div>
                <form action="{{action('\Modules\Crm\Http\Controllers\LeadController@uploadLeadCSV')}}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <label for="csv">Import CSV</label>
                    <input type="file" name="csv" class="form-control" id="csv" required>
                    <br>
                    <a href="{{ asset('files/import_lead.csv') }}" class="btn btn-success btn-sm" download><i class="fa fa-download"></i> @lang('lang_v1.download_template_file')</a>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Upload" name="submitBtn">
                </div>
                </form>
            </div>
        </div>
    </div>
