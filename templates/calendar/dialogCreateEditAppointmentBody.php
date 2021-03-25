<div class="desktop-only card-body">
    <?php echo "<form action=\"$formAction\" method=\"post\">"; ?>
        <input type="hidden" name="id" class="edit-appointment-id">
        <div class="form-group form">
            <label>
                Name
            </label>
            <input type="text" name="name" class="input-appointment-name form-control" required>
        </div>
        <div class="d-flex flex-row justify-content-between">
            <div class="form-group form w-50">
                <label>
                    Date
                </label>
                <input type="date" name="date" value="<?php echo date("Y")."-".date("m")."-".date("d"); ?>" class="input-appointment-date form-control" required>
            </div>
            <div class="d-flex flex-row w-50">
                <div class="form-group form w-100">
                    <label>
                        Start Time
                    </label>
                    <input type="time" name="start" class="input-appointment-start form-control" required>
                </div>
                <div class="form-group form w-100">
                    <label>
                        End Time
                    </label>
                    <input type="time" name="end" class="input-appointment-end form-control" required>
                </div>
            </div>
        </div>
        <div class="form-group form">
            <label>
                Description
            </label>
            <textarea rows="5" name="description" class="input-appointment-description form-control"></textarea>
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
                            <div class="appointment-tags dropdown-menu" aria-labelledby="dropdownMenuButton">
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
                    <button class="btn btn-secondary toggleTag w-25" type="button">
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
            <button class="btn btn-secondary toggleShare w-25" type="button">
                Share...
            </button>
        </div>
    </form>
</div>
<div class="mobile-only card-body">
    <?php echo "<form action=\"$formAction\" method=\"post\">"; ?>
        <input type="hidden" name="id" class="edit-appointment-id">
        <div class="form-group form">
            <label>
                Name
            </label>
            <input type="text" name="name" class="input-appointment-name form-control" required>
        </div>
        <div class="form-group form w-100">
            <label>
                Date
            </label>
            <input type="date" name="date" class="input-appointment-date form-control" required>
        </div>
        <div class="form-group form w-100">
            <label>
                Start Time
            </label>
            <input type="time" name="start" class="input-appointment-start form-control" pattern="\d{1,2}:\d{1,2}(:\d{1,2})?"
                   required>
        </div>
        <div class="form-group form w-100">
            <label>
                End Time
            </label>
            <input type="time" name="end" class="input-appointment-end form-control" pattern="\d{1,2}:\d{1,2}(:\d{1,2})?"
                   required>
        </div>
        <div class="form-group form w-100">
            <label>
                Description
            </label>
            <textarea rows="5" name="description" class="input-appointment-description form-control"></textarea>
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
                        <div class="appointment-tags dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
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
                <button class="btn btn-secondary toggleTag w-100" type="button">
                    Tags...
                </button>
            </div>
        </div>
        <div class="w-100 mb-5">
            <button class="btn btn-secondary toggleShare w-100" type="button">
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