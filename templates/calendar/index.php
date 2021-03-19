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
        $current_date = clone $startDate;
        $current_date->add(date_interval_create_from_date_string('1 day'));
        
        $columns[$i] = COLUMNS[$i]."<br>".$current_date->format('d.m.Y');
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
            $classes .= " appointment-id-$appointmentId";
            $cellContent[$cellKey] = "<div style=\"$style\" class=\"$classes\"><span>$text</span></div>";
        }
    }
?>

<div class="container pl-0 mw-100">
    <?php
        require 'dialogCreateAppointment.php';
        require 'dialogEditAppointment.php';
        require 'dialogTags.php';
        require 'dialogShare.php';
        require 'dialogError.php';
    ?>
    <div class="row pb-4">
        <div class="col-4 px-5">
            <img src="/images/logo.png" class="card-img-top" alt="The Mondate Logo">
        </div>
        <h1 class="col-4 text-center">
            Mondate
        </h1>
        <div class="col">
            <div class="dropdown float-right w-50">
                <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Account
                </button>
                <div class="dropdown-menu dropdown-menu-right w-100"
                     aria-labelledby="dropdownMenuButton">
                    <a href="/signOut" type=submit class="dropdown-item w-100">
                        Sign Out
                    </a>
                    <a href="/user/changePassword" class="dropdown-item w-100">
                        Change Password
                    </a>
                    <a href="/user/delete" type=submit class="dropdown-item w-100">
                        Delete Account
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="container col px-5">
            <div class="ml-0 row">
                <button class="btn btn-secondary w-100 mb-2 toggleCreate">
                    Create Appointment
                </button>
            </div>
            <div class="ml-0 row">
                <button id="btn-edit-appointment" class="btn btn-secondary w-100 mb-2 toggleEdit" disabled>
                    Edit Appointment
                </button>
            </div>
            <div class="ml-0 row">
                <form action="/appointment/delete" method="post" class="w-100">
                    <input type="hidden" name="id" value="" id="delete-appointment-id">
                    <button type="submit" id="btn-delete-appointment" class="btn btn-secondary w-100 mb-2" disabled>
                        Delete Appointment
                    </button>
                </form>
            </div>
            <div class="ml-0 row">
                <button class="btn btn-secondary w-100 mb-2 refresh">
                    Refresh
                </button>
            </div>
            <div class="ml-0 row mt-5">
                <h2 class="h5">Tags</h2>
                <div class="container">
                    <?php
                        foreach($tags as $tag) {
                            $color = '#'.$tag->color;
                            echo
                            "<div class=\"row mt-2 align-items-center\">
                                <span style=\"background-color: $color\" class=\"mr-2 color-block\"></span>
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
                            $id = $startDate->getTimestamp() + ($j * SECONDS_PER_DAY) + ($i * SECONDS_PER_HOUR);
            
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
            <a href="/calendar/last" class="btn btn-secondary px-5">
                Last
            </a>
            <span id="scope-identifier">
                <?php
                    echo $startDate->format('d.m.Y').' - '.$endDate->format('d.m.Y');
                ?>
            </span>
            <a href="/calendar/next" class="btn btn-secondary px-5">
                Next
            </a>
        </div>
    </div>
</div>

<script type="text/javascript" src="/js/calendar.js"></script>