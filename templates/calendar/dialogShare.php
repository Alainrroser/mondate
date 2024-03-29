<div class="dialog-parent invisible" id="dialog-share">
    <div class="dialog card pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Share
        </h1>
        <div class="desktop-only card-body">
            <div class="email-list card list-group shadow-sm w-100 mb-3"></div>
            <label for="email-desktop">
                E-Mail
            </label>
            <div class="d-flex justify-content-between">
                <input type="email" class="input-email mb-2 w-75" id="email-desktop">
                <button type="button" class="btn-add-email btn btn-secondary mb-2 w-25">
                    Add
                </button>
            </div>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-primary toggle-share w-25">
                    Close
                </button>
                <button type="button" class="btn-remove-email btn btn-secondary w-25">
                    Remove
                </button>
            </div>
        </div>
        <div class="mobile-only card-body">
            <div class="email-list card list-group shadow-sm w-100 mb-3"></div>
            <label for="email-mobile">
                E-Mail
            </label>
            <input type="email" class="input-email mb-2 mr-2 w-100" id="email-mobile">
            <button type="button" class="btn-add-email btn btn-secondary w-100 mb-4">
                Add
            </button>
            <button type="button" class="btn-remove-email btn btn-secondary mb-4 w-100">
                Remove
            </button>
            <button type="button" class="btn btn-primary toggle-share w-100">
                Close
            </button>
        </div>
    </div>
</div>