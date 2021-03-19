<div class="center-align invisible position-fixed" id="tags">
    <div class="card w-25 pt-5 shadow-sm">
        <h1 class="text-center mb-3">
            Tags
        </h1>
        <div class="card-body">
            <div class="d-flex flex-row">
                <div class="tag-list card list-group w-50 mr-3 mb-3 shadow-sm">
                    <?php
                    foreach($tags as $tag) {
                        $color = '#'.$tag->color;
                        echo
                        "<button id=\"tag-$tag->id\" class=\"tag align-items-center d-flex flex-row pl-1 list-group-item list-group-item-action\">
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
                        <input type="text" id="tag-name" name="name" class="tag-name form-control">
                    </div>
                    <div class="form-group form">
                        <label>
                            Color
                        </label>
                        <input type="color" id="tag-color" name="color" class="tag-color form-control">
                    </div>
                    <div class="form-group form">
                        <button class="btn btn-secondary" id="btn-add-tag">
                            Add
                        </button>
                    </div>
                    <div class="remove-tag form-group form align-items-end">
                        <button class="btn btn-secondary" id="btn-remove-tag">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary toggleTag">
                Close
            </button>
        </div>
    </div>
</div>