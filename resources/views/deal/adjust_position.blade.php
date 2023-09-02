<div class="modal fade" id="PositionFormModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="demoModalLabel">Confirmation</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    @csrf
                    <label for="pos_number">Position number</label>
                    <input type="text" id="pos_number" class="form-control" name="pos_number"/>
                    @error('pos_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="module">
    $(document).on('click', '.adjustPositionButton', function (event) {
        event.preventDefault();

        const $this = $(this);
        const $form = $('#PositionFormModal form');

        $form.attr('action', $this.attr('data-attr'));

        $('#PositionFormModal').modal("show");
    })
</script>
