<div class="dialog-parent invisible" id="dialog-manage-tags">
    <div class="dialog card pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Tags
        </h1>
        <div class="desktop-only card-body">
            <div class="d-flex flex-row">
                <div class="tag-list card list-group w-50 mr-3 mb-3 shadow-sm">
                    <?php
                    foreach($tags as $tag) {
                        $color = '#'.$tag->color;
                        echo
                        "<button class=\"tag-$tag->id tag align-items-center d-flex flex-row pl-1 list-group-item list-group-item-action\">
                            <span style=\"background-color: $color\" class=\"mr-2 color-block\"></span>
                            <span class=\"align-middle\">$tag->name</span>
                        </button>";
                    }
                    ?>
                </div>
                <div class="form-group form d-flex flex-column">
                    <div class="form-group form">
                        <label>
                            Name
                        </label>
                        <input type="text" name="name" class="tag-name form-control">
                    </div>
                    <div class="form-group form">
                        <label>
                            Color
                        </label>
                        <input type="color" name="color" class="tag-color form-control">
                    </div>
                    <div class="form-group form">
                        <button class="btn btn-secondary btn-add-tag">
                            Add
                        </button>
                    </div>
                    <div class="remove-tag form-group form align-items-end">
                        <button class="btn btn-secondary btn-remove-tag">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary toggle-tag">
                Close
            </button>
        </div>
        <div class="mobile-only card-body">
            <div class="tag-list card list-group w-100 mr-3 mb-3 shadow-sm">
                <?php
                    foreach($tags as $tag) {
                        $color = '#'.$tag->color;
                        echo
                        "<button class=\"tag-$tag->id tag align-items-center d-flex flex-row pl-1 list-group-item list-group-item-action\">
                        <span style=\"background-color: $color\" class=\"mr-2 color-block\"></span>
                        <span class=\"align-middle\">$tag->name</span>
                        </button>";
                    }
                ?>
            </div>
            <label>
                Name
            </label>
            <input type="text" name="name" class="tag-name form-control">
            <label>
                Color
            </label>
            <input type="color" name="color" class="tag-color form-control mb-2">
            <button class="btn btn-secondary w-100 mb-4 btn-add-tag">
                Add
            </button>
            <button class="btn btn-secondary mb-4 w-100 btn-remove-tag">
                Remove
            </button>
            <button type="button" class="btn btn-primary toggle-tag w-100">
                Close
            </button>
        </div>
    </div>
</div>