<div class="modal fade" id="confirmation_modal{{$modal_id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    Please Confirm
                </div>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p style="font-size: 15px;">Are you sure you want to delete this data!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger delete_btn"  id="{{$btn_id}}">Delete</button>
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
