<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="demoModalLabel">Confirmation</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure?
            </div>
            <div class="modal-footer">
                <form method="POST" action="">
                    @csrf
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                    {!! $modalBtn !!}
                </form>
            </div>
        </div>
    </div>
</div>
<script type="module">
    $(document).on('click', '.formModalButton', function(event) {
        event.preventDefault();

        const $this = $(this);
        const $form = $('#formModal form');

        $form.attr('action', $this.attr('data-attr'));

        $('#formModal').modal("show");
    })
</script>
