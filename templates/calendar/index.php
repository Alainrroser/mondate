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
        // Convert appointment start date and time to seconds
        $date_as_string = $appointment->getDate().' '.$appointment->getStart();
        $id = DateTime::createFromFormat('Y-m-d H:i:s', $date_as_string)->getTimestamp();
        
        // Calculate the number of cells required based on the end time
        $startInSeconds = DateTime::createFromFormat('H:i:s', $appointment->getStart())->getTimestamp();
        $endInSeconds = DateTime::createFromFormat('H:i:s', $appointment->getEnd())->getTimestamp();
        $durationInSeconds = $endInSeconds - $startInSeconds;
        $numberOfCells = ceil($durationInSeconds / SECONDS_PER_HOUR);
        
        $style = "background-color: gray; border-color: gray";
        if(sizeof($appointment->getTags()) == 1) {
            $color = '#'.$appointment->getTags()[0]->getColor();
            $style = "background-color: $color; border-color: $color";
        } else if(sizeof($appointment->getTags()) > 1) {
            $gradient = 'linear-gradient(90deg';
            
            for($i = 0; $i < sizeof($appointment->getTags()); $i++) {
                $tag = $appointment->getTags()[$i];
                $color = $tag->getColor();
                $percent = ($i * 100) / (sizeof($appointment->getTags()) - 1);
                
                $gradient .= ", #$color $percent%";
            }
            
            $gradient .= ')';
            $style = "background: $gradient; border: none;";
        }
        
        // Store the buttons in the array
        for($i = 0; $i < $numberOfCells; $i++) {
            $text = "";
            if($i == 0) {
                $text = $appointment->getName();
            }
            
            $classes = "btn btn-primary w-100 appointment";
            if($numberOfCells == 1) {
                $classes = $classes." appointment-top-bottom";
            } else {
                if($i == 0) {
                    $classes = $classes." appointment-top";
                } else if($i == $numberOfCells - 1) {
                    $classes = $classes." appointment-bottom";
                }
            }
            
            $cellId = $id + $i * SECONDS_PER_HOUR;
            $cellContent[$cellId] = "<div style=\"$style\" class=\"$classes\">$text</div>";
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
            <img src="/images/logo.png"
                 class="card-img-top w-25 mx-auto"
                 alt="The Mondate Logo">
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
                        <input type="text"
                               name="description"
                               class="form-control">
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
                                                id="dropdownMenuButton"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                            Select Tags...
                                        </button>
                                        <div class="dropdown-menu"
                                             aria-labelledby="dropdownMenuButton">
                                            
                                        </div>
                                    </div>
                                </div>
                                <a href=""
                                   class="btn btn-secondary">
                                    Manage Tags...
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit"
                                    class="btn btn-primary">
                                Create
                            </button>
                            <button class="btn btn-secondary"
                                    onclick="hideCreateAppointment()">
                                Cancel
                            </button>
                        </div>
                        <a href=""
                           class="btn btn-secondary">
                            Share...
                        </a>
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
            <img src="/images/logo.png"
                 class="card-img-top w-25 mx-auto"
                 alt="The Mondate Logo">
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
                        <input type="text"
                               name="description"
                               class="form-control">
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
                                                id="dropdownMenuButton"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                            Select Tags...
                                        </button>
                                        <div class="dropdown-menu"
                                             aria-labelledby="dropdownMenuButton">

                                        </div>
                                    </div>
                                </div>
                                <a href=""
                                   class="btn btn-secondary">
                                    Manage Tags...
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit"
                                    class="btn btn-primary">
                                Save
                            </button>
                            <button class="btn btn-secondary"
                                    onclick="hideEditAppointment()">
                                Cancel
                            </button>
                        </div>
                        <a href=""
                           class="btn btn-secondary">
                            Share...
                        </a>
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
                <button class="btn btn-secondary w-100 mb-2"
                        onclick="showCreateAppointment()">Create Appointment</button>
            </div>
            <div class="row">
                <button class="btn btn-secondary w-100 mb-2"
                        onclick="showEditAppointment()">Edit Appointment</button>
            </div>
            <div class="row">
                <button class="btn btn-secondary w-100 mb-2">Delete Appointment</button>
            </div>
            <div class="row"
                 onclick="reload()">
                <button class="btn btn-secondary w-100 mb-2">Reload</button>
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