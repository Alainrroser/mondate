<div class="desktop-only card-body">
    <form method=post action=<?= $formAction ?> >
        <input type="hidden" name="id" class="edit-appointment-id">
        <div class="form-group form">
            <label for="appointment-name-desktop">
                Name
            </label>
            <input type="text" name="name" class="input-appointment-name form-control" id="appointment-name-desktop" required>
        </div>
        <div class="d-flex flex-row justify-content-between">
            <div class="form-group form w-100">
                <label for="appointment-start-<?= $type ?>-desktop">
                    Start
                </label>
                <input type="datetime-local" id="appointment-start-<?= $type ?>-desktop" name="start"
                       class="input-appointment-start form-control" required>
            </div>
            <div class="form-group form w-100">
                <label for="appointment-end-desktop">
                    End
                </label>
                <input type="datetime-local" id="appointment-end-desktop" name="end"
                       class="input-appointment-end form-control" required>
            </div>
        </div>
        <div class="form-group form">
            <label for="appointment-description-desktop">
                Description
            </label>
            <textarea rows="5" id="appointment-description-desktop" name="description" class="input-appointment-description form-control"></textarea>
        </div>
        <div class="form-group form">
            <div class="form-group form">
                <label>
                    Tags
                </label>
                <div class="d-flex flex-row justify-content-between">
                    <div class="w-25">
                        <div class="dropdown w-100">
                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Select Tags...
                            </button>
                            <div class="appointment-tags dropdown-menu" aria-labelledby="dropdown-menu-button">
                                <?php
                                foreach($tags as $tag) {
                                    $id = $tag->id;
                                    $color = '#'.$tag->color;
                                    echo
                                    "<div class=\"tag-$id appointment-tag align-items-center d-flex flex-row pl-1\">
                                        <input type=\"checkbox\" name=\"tags[$id]\" class=\"align-middle mr-1\">
                                        <span style=\"background-color: $color\" class=\"mr-1 color-block\"></span>
                                        <label class=\"align-middle mb-0\">$tag->name</label>
                                    </div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-secondary toggle-tag w-25" type="button">
                        Tags...
                    </button>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div class="d-flex flex-row w-50">
                <button type="submit" class="btn btn-primary w-50">
                    <?= $submitButtonText ?>
                </button>
                <button class="btn btn-secondary refresh w-50" type="button">
                    Cancel
                </button>
            </div>
            <button class="btn btn-secondary toggle-share w-25" type="button">
                Share...
            </button>
        </div>
    </form>
</div>
<div class="mobile-only card-body">
    <form method=post action=<?= $formAction ?>>
        <input type="hidden" name="id" class="edit-appointment-id">
        <div class="form-group form">
            <label for="appointment-name-mobile">
                Name
            </label>
            <input type="text" id="appointment-name-mobile" name="name" class="input-appointment-name form-control" required>
        </div>
        <div class="form-group form w-100">
            <label for="appointment-start-mobile">
                Start Date
            </label>
            <input type="datetime-local" id="appointment-start-mobile" name="start"
                   class="input-appointment-start form-control" required>
        </div>
        <div class="form-group form w-100">
            <label for="appointment-end-mobile">
                End Date
            </label>
            <input type="datetime-local" id="appointment-end-mobile" name="end"
                   class="input-appointment-end form-control" required>
        </div>
        <div class="form-group form w-100">
            <label for="appointment-description-mobile">
                Description
            </label>
            <textarea rows="5" id="appointment-description-mobile" name="description" class="input-appointment-description form-control"></textarea>
        </div>
        <div class="form-group form w-100 mb-3">
            <label>
                Tags
            </label>
            <div class="w-100">
                <div class="w-100 mb-2">
                    <div class="dropdown w-100">
                        <button class="btn btn-secondary dropdown-toggle w-100" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Select Tags...
                        </button>
                        <div class="appointment-tags dropdown-menu w-100" aria-labelledby="dropdown-menu-button">
                            <?php
                            foreach($tags as $tag) {
                                $id = $tag->id;
                                $color = '#'.$tag->color;
                                echo
                                "<div class=\"tag-$id appointment-tag align-items-center d-flex flex-row pl-1\">
                                                    <input type=\"checkbox\" name=\"tags[$id]\" class=\"align-middle mr-1\">
                                                    <span style=\"background-color: $color\" class=\"mr-1 color-block\"></span>
                                                    <label class=\"align-middle mb-0\">$tag->name</label>
                                                </div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <button class="btn btn-secondary toggle-tag w-100" type="button">
                    Tags...
                </button>
            </div>
        </div>
        <div class="w-100 mb-5">
            <button class="btn btn-secondary toggle-share w-100" type="button">
                Share...
            </button>
        </div>
        <div>
            <button type="submit" class="btn btn-primary w-100 mb-2">
                <?= $submitButtonText ?>
            </button>
            <button class="btn btn-secondary refresh w-100" type="button">
                Cancel
            </button>
        </div>
    </form>
</div>