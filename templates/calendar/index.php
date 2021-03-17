<?php
    
    const COLUMNS = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
    
    const SECONDS_PER_HOUR = 60 * 60;
    const SECONDS_PER_DAY = SECONDS_PER_HOUR * 24;
    
    function index_to_time($index) {
        return str_pad($index, 2, '0', STR_PAD_LEFT).":00";
    }

?>

<?php
    $columns = [];
    $cellContent = [];
    
    // Create the column titles
    for($i = 0; $i < sizeof(COLUMNS); $i++) {
        $current_date = $_SESSION['startDate'] + 60 * 60 * 24 * $i;
        $columns[$i] = COLUMNS[$i]."<br>".date('d.m.Y', $current_date);
    }
    
    foreach($appointments as $appointment) {
        $appointmentId = $appointment->getId();
        
        // Convert appointment start date and time to seconds
        $date_as_string = $appointment->getDate().' '.$appointment->getStart();
        $id = DateTime::createFromFormat('Y-m-d H:i:s', $date_as_string)->getTimestamp();
        
        // Calculate the number of cells required based on the end time
        $startInSeconds = DateTime::createFromFormat('H:i:s', $appointment->getStart())->getTimestamp();
        $endInSeconds = DateTime::createFromFormat('H:i:s', $appointment->getEnd())->getTimestamp();
        $durationInSeconds = $endInSeconds - $startInSeconds;
        $numberOfCells = ceil($durationInSeconds / SECONDS_PER_HOUR);
        
        $style = "background-color: gray;";
        if(sizeof($appointment->getTags()) == 1) {
            $color = '#'.$appointment->getTags()[0]->getColor();
            $style = "background-color: $color;";
        } else if(sizeof($appointment->getTags()) > 1) {
            $gradient = 'linear-gradient(90deg';
            
            for($i = 0; $i < sizeof($appointment->getTags()); $i++) {
                $tag = $appointment->getTags()[$i];
                $color = $tag->getColor();
                $percent = ($i * 100) / (sizeof($appointment->getTags()) - 1);
                
                $gradient .= ", #$color $percent%";
            }
            
            $gradient .= ')';
            $style = "background: $gradient;";
        }
        
        // Store the buttons in the array
        for($i = 0; $i < $numberOfCells; $i++) {
            $text = "";
            if($i == 0) {
                $text = $appointment->getName();
            }
            
            $classes = "w-100 p-0 align-middle appointment";
            if($numberOfCells == 1) {
                $classes .= " appointment-top-bottom";
            } else {
                if($i == 0) {
                    $classes .= " appointment-top";
                } else if($i == $numberOfCells - 1) {
                    $classes .= " appointment-bottom";
                } else {
                    $classes .= " appointment-between";
                }
            }
            
            $cellKey = $id + $i * SECONDS_PER_HOUR;
            $cellId = "appointment-id-$appointmentId";
            $cellContent[$cellKey] = "<div style=\"$style\" class=\"$classes\" id=\"$cellId\"><span>$text</span></div>";
        }
    }
?>

<div class="container mw-100">
    <div class="center-align invisible position-fixed"
         id="createAppointment">
        <div class="card w-25 pt-5 shadow-sm">
            <h1 class="text-center mb-3">
                Create Appointment
            </h1>
            <div class="card-body">
                <form action="/appointment/create"
                      method="post">
                    <div class="form-group form">
                        <label>
                            Name
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control">
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="form-group form">
                            <label>
                                Date
                            </label>
                            <input type="date"
                                   name="date"
                                   class="form-control">
                        </div>
                        <div class="d-flex flex-row">
                            <div class="form-group form">
                                <label>
                                    Start Time
                                </label>
                                <input type="text"
                                       name="start"
                                       class="form-control">
                            </div>
                            <div class="form-group form">
                                <label>
                                    End Time
                                </label>
                                <input type="text"
                                       name="end"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group form">
                        <label>
                            Description
                        </label>
                        <textarea rows="5"
                                name="description"
                                class="form-control">
                            
                        </textarea>
                    </div>
                    <div class="form-group form">
                        <div class="form-group form">
                            <label>
                                Tags
                            </label>
                            <div class="d-flex flex-row justify-content-between">
                                <div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle"
                                                type="button"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                            Select Tags...
                                        </button>
                                        <div class="dropdown-menu"
                                             aria-labelledby="dropdownMenuButton">
                                            <?php
                                                foreach($tags as $tag) {
                                                    $color = '#'.$tag->color;
                                                    echo
                                                    "<div class=\"align-items-center d-flex flex-row pl-1\">
                                                        <input type=\"checkbox\" class=\"align-middle mr-1\">
                                                        <span style=\"width:1rem;height:1rem;background-color: $color\" class=\"mr-1\"></span>
                                                        <label class=\"align-middle mb-0\">$tag->name</label>
                                                    </div>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-secondary toggleTag">
                                    Manage Tags...
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit"
                                    class="btn btn-primary">
                                Create
                            </button>
                            <button class="btn btn-secondary toggleCreate"
                                    type="button">
                                Cancel
                            </button>
                        </div>
                        <button class="btn btn-secondary toggleShare"
                                type="button">
                            Share...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="center-align invisible position-fixed"
         id="editAppointment">
        <div class="card w-25 pt-5 shadow-sm">
            <h1 class="text-center mb-3">
                Edit Appointment
            </h1>
            <div class="card-body">
                <form action="/appointment/edit"
                      method="post">
                    <input type="hidden"
                           name="id"
                           value=""
                           id="edit-appointment-id">
                    <div class="form-group form">
                        <label>
                            Name
                        </label>
                        <input type="text"
                               name="name"
                               class="form-control">
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="form-group form">
                            <label>
                                Date
                            </label>
                            <input type="date"
                                   name="date"
                                   class="form-control">
                        </div>
                        <div class="d-flex flex-row">
                            <div class="form-group form">
                                <label>
                                    Start Time
                                </label>
                                <input type="text"
                                       name="start"
                                       class="form-control">
                            </div>
                            <div class="form-group form">
                                <label>
                                    End Time
                                </label>
                                <input type="text"
                                       name="end"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group form">
                        <label>
                            Description
                        </label>
                        <textarea rows="5"
                               name="description"
                               class="form-control">
                            
                        </textarea>
                    </div>
                    <div class="form-group form">
                        <div class="form-group form">
                            <label>
                                Tags
                            </label>
                            <div class="d-flex flex-row justify-content-between">
                                <div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle"
                                                type="button"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                            Select Tags...
                                        </button>
                                        <div class="dropdown-menu"
                                             aria-labelledby="dropdownMenuButton">
                                            <?php
                                                foreach($tags as $tag) {
                                                    $color = '#'.$tag->color;
                                                    echo
                                                    "<div class=\"align-items-center d-flex flex-row pl-1\">
                                                        <input type=\"checkbox\" class=\"align-middle mr-1\">
                                                        <span style=\"width:1rem;height:1rem;background-color: $color\" class=\"mr-1\"></span>
                                                        <label class=\"align-middle mb-0\">$tag->name</label>
                                                    </div>";
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <button type="button"
                                        class="btn btn-secondary toggleTag">
                                    Manage Tags...
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit"
                                    class="btn btn-primary">
                                Save
                            </button>
                            <button class="btn btn-secondary toggleEdit"
                                    type="button">
                                Cancel
                            </button>
                        </div>
                        <button class="btn btn-secondary toggleShare"
                                type="button">
                            Share...
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="center-align invisible position-fixed"
         id="tags">
        <div class="card w-25 pt-5 shadow-sm">
            <h1 class="text-center mb-3">
                Tags
            </h1>
            <div class="card-body">
                <input type="hidden"
                       name="tagId"
                       value=""
                       id="tag-id">
                <div class="d-flex flex-row">
                    <div class="card w-50 mr-3 mb-3 shadow-sm">
                        <?php
                            foreach($tags as $tag) {
                                $color = '#'.$tag->color;
                                echo
                                "<div class=\"align-items-center d-flex flex-row pl-1\">
                                    <span style=\"width:1rem;height:1rem;background-color: $color\" class=\"mr-2\"></span>
                                    <span class=\"align-middle\">$tag->name</span>
                                </div>";
                            }
                        ?>
                    </div>
                    <div class="form-group form d-flex flex-column">
                        <div class="form-group form">
                            <label>
                                Name
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control">
                        </div>
                        <div class="form-group form">
                            <label>
                                Color
                            </label>
                            <input type="color"
                                   name="color"
                                   class="form-control">
                        </div>
                        <div class="form-group form">
                            <button class="btn btn-secondary">
                                Add
                            </button>
                        </div>
                        <div class="form-group form justify-content-end">
                            <button class="btn btn-secondary">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button"
                            class="btn btn-primary">
                        Close
                    </button>
                    <button class="btn btn-secondary toggleTag">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="center-align invisible position-fixed"
         id="share">
        <div class="card w-25 pt-5 shadow-sm">
            <h1 class="text-center mb-3">
                Share
            </h1>
            <div class="card-body">
                <form action=""
                      method="post">
                    <input type="hidden"
                           name="id"
                           value=""
                           id="share-id">
                    <textarea class="form-control mb-4" rows="10" cols="">
                    
                    </textarea>
                    <div class="d-flex justify-content-between">
                        <div>
                            <label>
                                E-Mail
                            </label>
                            <input type="text" name="email">
                        </div>
                        <button type="button" class="btn btn-secondary">
                            Add
                        </button>
                    </div>
                    <button type="button" class="btn btn-secondary mb-5">
                            Remove
                        </button>
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit"
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
    <div class="row pb-4">
        <div class="col-4">
            <img src="/images/logo.png"
                 class="card-img-top"
                 alt="The Mondate Logo"
                 style="width: 100px">
        </div>
        <h1 class="col-4 text-center">
            Mondate
        </h1>
        <div class="col">
            <div class="dropdown float-right w-50">
                <button class="btn btn-secondary dropdown-toggle w-100"
                        type="button"
                        id="dropdownMenuButton"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                    Account
                </button>
                <div class="dropdown-menu dropdown-menu-right w-100"
                     aria-labelledby="dropdownMenuButton">
                    <a href="/signOut"
                       type=submit
                       class="dropdown-item w-100">
                        Sign Out
                    </a>
                    <a href="/user/changePassword"
                       class="dropdown-item w-100">
                        Change Password
                    </a>
                    <a href="/user/delete"
                       type=submit
                       class="dropdown-item w-100">
                        Delete Account
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="container col px-5">
            <div class="row">
                <button class="btn btn-secondary w-100 mb-2 toggleCreate">
                    Create Appointment
                </button>
            </div>
            <div class="row">
                <button class="btn btn-secondary w-100 mb-2 toggleEdit">
                    Edit Appointment
                </button>
            </div>
            <div class="row">
                <form action="/appointment/delete"
                      method="post"
                      class="w-100">
                    <input type="hidden"
                           name="id"
                           value=""
                           id="delete-appointment-id">
                    <button type="submit"
                            class="btn btn-secondary w-100 mb-2">
                        Delete Appointment
                    </button>
                </form>
            </div>
            <div class="row">
                <button class="btn btn-secondary w-100 mb-2"
                        id="reloadButton">
                    Refresh
                </button>
            </div>
            <div class="row mt-5">
                <h2 class="h5">Tags</h2>
                <div class="container">
                    <?php
                        foreach($tags as $tag) {
                            $color = '#'.$tag->color;
                            echo
                            "<div class=\"row mt-2 align-items-center\">
                                <span style=\"width:1rem;height:1rem;background-color: $color\" class=\"mr-2\"></span>
                                <span class=\"align-middle\">$tag->name</span>
                            </div>";
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-10">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <?php
                        foreach($columns as $column) {
                            echo "<th scope=\"col\" class=\"text-center\">$column</th>";
                        }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                    for($i = 0; $i < 24; $i++) {
                        echo "<tr>";
                        echo "<th scope=\"row\" class=\"p-0 align-middle\">".index_to_time($i)."</th>";
        
                        for($j = 0; $j < sizeof(COLUMNS); $j++) {
                            // Convert the current cell date and time to seconds
                            $id = $startDate + ($j * SECONDS_PER_DAY) + ($i * SECONDS_PER_HOUR);
            
                            // Get and display the cell content from the array
                            $content = isset($cellContent[$id]) ? $cellContent[$id] : "";
                            echo "<td class=\"cell-appointment p-0 align-middle\">$content</td>";
                        }
        
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row float-right pt-3">
        <div class="col">
            <a href="/calendar/last"
               class="btn btn-secondary px-5">Last</a>
            <span id="scope-identifier">
                <?php
                    echo date('d.m.Y', $startDate).' - '.date('d.m.Y', $endDate);
                ?>
            </span>
            <a href="/calendar/next"
               class="btn btn-secondary px-5">Next</a>
        </div>
    </div>
</div>

<script type="text/javascript"
        src="/js/calendar.js"></script>