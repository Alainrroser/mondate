<div class="desktop-only card-body">
    <form method=post action=<?=$formAction?>>
        <input type="hidden" name="id" class="edit-appointment-id">
        <div class="form-group form">
            <label for="appointment-name-<?=$type?>-desktop">
                Name
            </label>
            <input type="text" name="name" class="input-appointment-name form-control"
                   id="appointment-name-<?=$type?>-desktop" maxlength="20" required>
        </div>
        <div class="d-flex flex-row justify-content-between">
            <div class="form-group form w-100">
                <label for="appointment-start-<?=$type?>-desktop">
                    Start
                </label>
                <input type="datetime-local" id="appointment-start-<?=$type?>-desktop" name="start"
                       class="input-appointment-start form-control" required>
            </div>
            <div class="form-group form w-100">
                <label for="appointment-end-<?=$type?>-desktop">
                    End
                </label>
                <input type="datetime-local" id="appointment-end-<?=$type?>-desktop" name="end"
                       class="input-appointment-end form-control" required>
            </div>
        </div>
        <div class="form-group form">
            <label for="appointment-description-<?=$type?>-desktop">
                Description
            </label>
            <textarea rows="5" id="appointment-description-<?=$type?>-desktop" name="description"
                      class="input-appointment-description form-control"
                      maxlength="1000"></textarea>
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
                                        $id = $tag->getId();
                                        $name = $tag->getName();
                                        $color = '#'.$tag->getColor();
                                        echo
                                        "<div class=\"tag-$id appointment-tag align-items-center d-flex flex-row pl-1\">
                                            <input type=\"checkbox\" name=\"tags[$id]\" class=\"align-middle mr-1\">
                                            <span style=\"background-color: $color\" class=\"mr-1 color-block\"></span>
                                            <label class=\"align-middle mb-0\">$name</label>
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
                    <?=$submitButtonText?>
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
    <form method=post action=<?=$formAction?>>
        <input type="hidden" name="id" class="edit-appointment-id">
        <div class="form-group form">
            <label for="appointment-name-<?=$type?>-mobile">
                Name
            </label>
            <input type="text" id="appointment-name-<?=$type?>-mobile" name="name"
                   class="input-appointment-name form-control" maxlength="20" required>
        </div>
        <div class="form-group form w-100">
            <label for="appointment-start-<?=$type?>-mobile">
                Start
            </label>
            <input type="datetime-local" id="appointment-start-<?=$type?>-mobile" name="start"
                   class="input-appointment-start form-control" required>
        </div>
        <div class="form-group form w-100">
            <label for="appointment-end-<?=$type?>-mobile">
                End
            </label>
            <input type="datetime-local" id="appointment-end-<?=$type?>-mobile" name="end"
                   class="input-appointment-end form-control" required>
        </div>
        <div class="form-group form w-100">
            <label for="appointment-description-<?=$type?>-mobile">
                Description
            </label>
            <textarea rows="5" id="appointment-description-<?=$type?>-mobile" name="description"
                      class="input-appointment-description form-control"
                      maxlength="1000"></textarea>
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
                                    $id = $tag->getId();
                                    $name = $tag->getName();
                                    $color = '#'.$tag->getColor();
                                    echo
                                    "<div class=\"tag-$id appointment-tag align-items-center d-flex flex-row pl-1\">
                                        <input type=\"checkbox\" name=\"tags[$id]\" class=\"align-middle mr-1\">
                                        <span style=\"background-color: $color\" class=\"mr-1 color-block\"></span>
                                        <label class=\"align-middle mb-0\">$name</label>
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
                <?=$submitButtonText?>
            </button>
            <button class="btn btn-secondary refresh w-100" type="button">
                Cancel
            </button>
        </div>
    </form>
</div>