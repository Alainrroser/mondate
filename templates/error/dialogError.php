<?php if(sizeof($errors) > 0): ?>
    <div class="center-align position-fixed" id="dialog-error">
        <div class="card w-25 pt-5 shadow-sm">
            <h1 class="text-center mb-3">
                Error
            </h1>
            <div class="p-3 card-body">
                <div class="alert alert-danger w-100">
                    <?php
                        foreach($errors as $error) {
                            echo "<span>$error</span>";
                        }
                    ?>
                </div>
                <button type="button" class="w-100 btn btn-primary" id="btn-close-error-dialog">
                    Close
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>