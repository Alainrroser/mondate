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
    
    for($i = 0; $i < sizeof(COLUMNS); $i++) {
        $current_date = $_SESSION['startDate'] + 60 * 60 * 24 * $i;
        
        $columns[$i] = COLUMNS[$i]."<br>".date('d.m.Y', $current_date);
    }
    
    foreach($appointments as $appointment) {
        // Convert appointment start date and time to seconds
        $date_as_string = $appointment->date.' '.$appointment->start;
        $id = DateTime::createFromFormat('Y-m-d H:i:s', $date_as_string)->getTimestamp();
        
        // Calculate the number of cells required based on the end time
        $startInSeconds = DateTime::createFromFormat('H:i:s', $appointment->start)->getTimestamp();
        $endInSeconds = DateTime::createFromFormat('H:i:s', $appointment->end)->getTimestamp();
        $durationInSeconds = $endInSeconds - $startInSeconds;
        $numberOfCells = $durationInSeconds / SECONDS_PER_HOUR;
        
        $color = '#'.$appointmentColors[$appointment->id];
        $style = "background-color: $color; border-color: $color";
        
        // Store the buttons in the array
        for($i = 0; $i < $numberOfCells; $i++) {
            $text = "-";
            if($i == 0) {
                $text = $appointment->name;
            }
            
            $cellId = $id + $i * SECONDS_PER_HOUR;
            $cellContent[$cellId] = "<div style=\"$style\" class=\"btn btn-primary m-0 w-100\">$text</div>";
        }
    }
?>

<div class="container mw-100">
    <div class="row pb-4">
        <div class="col-8">
            <img src="/images/logo.png"
                 class="card-img-top"
                 alt="The Mondate Logo"
                 style="width: 100px">
        </div>
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
                    <a href="/logout"
                       type=submit
                       class="dropdown-item w-100">
                        Logout
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
                <button class="btn btn-secondary w-100 mb-2">Create Appointment</button>
            </div>
            <div class="row">
                <button class="btn btn-secondary w-100 mb-2">Edit Appointment</button>
            </div>
            <div class="row">
                <button class="btn btn-secondary w-100 mb-2">Delete Appointment</button>
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
                            echo "<td class=\"cell-appointment py-0 px-1 align-middle\">$content</td>";
                        }
        
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row float-right pt-3">
        <form action=""
              method="get">
            <input type="week"
                   name="week">
        </form>
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