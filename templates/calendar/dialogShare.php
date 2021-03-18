<div class="center-align invisible position-fixed"
     id="share">
    <div class="card w-25 pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Share
        </h1>
        <div class="card-body">
            <form action="/appointment/share"
                  method="post">
                <input type="hidden"
                       name="id"
                       value=""
                       id="share-id">
                <div class="contacts card list-group shadow-sm w-100 mb-3 email-list"></div>
                <label>
                    E-Mail
                </label>
                <div class="d-flex justify-content-between">
                    <input type="text" class="mb-2 mr-2 w-100"
                           id="email"
                           required>
                    <button type="button" id="add-email"
                            class="btn btn-secondary mb-2">
                        Add
                    </button>
                </div>
                <div class="mb-5 d-flex justify-content-between">
                    <button type="button" id="remove-email"
                            class="btn btn-secondary">
                        Remove
                    </button>
                    <div>
                        <button type="button" id="edit-email"
                                class="btn btn-secondary">
                            Edit
                        </button>
                        <button type="button" id="save-changes" class="btn btn-secondary">
                            Save Changes
                        </button>
                    </div>
                    
                </div>
                <div class="d-flex justify-content-between">
                    <div>
                        <button type="submit" id="save-emails"
                                class="btn btn-primary">
                            Save
                        </button>
                        <button class="btn btn-secondary toggleShare"
                                type="button">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>