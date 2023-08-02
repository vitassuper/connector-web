<div class="modal fade" id="SOFormModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel"
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
                    <label for="amount">Amount (quote)</label>
                    <input type="text" id="amount" class="form-control" name="amount"/>
                    @error('amount')
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
    $(document).on('click', '.addSOModalButton', function (event) {
        event.preventDefault();

        const $this = $(this);
        const $form = $('#SOFormModal form');

        $form.attr('action', $this.attr('data-attr'));

        $('#SOFormModal').modal("show");
    })
</script>
