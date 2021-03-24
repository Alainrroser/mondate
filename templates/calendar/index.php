<?php

const COLUMNS = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

const SECONDS_PER_HOUR = 60 * 60;
const SECONDS_PER_DAY = SECONDS_PER_HOUR * 24;

function index_to_time($index) {
    return str_pad($index, 2, '0', STR_PAD_LEFT).":00";
}

function getAppointmentStyle($appointment, $first, $last) {
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

    if($first) {
        $startInSeconds = DateTime::createFromFormat('H:i:s', $appointment->getStart())->getTimestamp();
        $height = ($startInSeconds % SECONDS_PER_HOUR / SECONDS_PER_HOUR * 49);
        if($height === 0) {
            $height = 49;
        }
        $style .= "height: " . $height . "px; margin-top: " . (49 - $height) . "px;";
    } else if($last) {
        $endInSeconds = DateTime::createFromFormat('H:i:s', $appointment->getEnd())->getTimestamp();
        $height = ($endInSeconds % SECONDS_PER_HOUR / SECONDS_PER_HOUR * 49);
        if($height == 0) {
            $height = 49;
        }
        $style .= "height: " . $height . "px;";
    } else {
        $style .= "height: 49px";
    }

    return $style;
}

?>

<?php
$columns = [];
$cellContent = [];

// Sort the appointments by time and date
// (This will make our life much easier further down)
function sortAppointmentsByTime($a, $b) {
    $timeA = strtotime($a->getStart());
    $timeB = strtotime($b->getStart());
    return $timeA < $timeB ? -1 : 1;
}

usort($appointments, "sortAppointmentsByTime");

// Create the column titles
for($i = 0; $i < sizeof(COLUMNS); $i++) {
    $current_date = clone $startDate;
    $current_date->add(date_interval_create_from_date_string($i . ' day'));

    $columns[$i] = COLUMNS[$i] . "<br>" . $current_date->format('d.m.Y');
}

foreach($appointments as $appointment) {
    $appointmentId = $appointment->getId();

    // Convert appointment start and end to seconds
    $startAsString = $appointment->getDate() . ' ' . $appointment->getStart();
    $endAsString = $appointment->getDate() . ' ' . $appointment->getEnd();

    $startInSeconds = DateTime::createFromFormat('Y-m-d H:i:s', $startAsString)->getTimestamp();
    $endInSeconds = DateTime::createFromFormat('Y-m-d H:i:s', $endAsString)->getTimestamp();

    // Calculate the number of cells required based on the end time
    $durationInSeconds = $endInSeconds - $startInSeconds;
    $numberOfCells = $durationInSeconds / SECONDS_PER_HOUR;

    // Tomorrow I won't understand why I added this line
    // Actually, i don't understand it currently either
    if(fmod($endInSeconds / SECONDS_PER_HOUR, 1) !== 0.00) {
        $numberOfCells += 1;
    }

    $numberOfCells = floor($numberOfCells);

    // Store the buttons in the array
    for($i = 0; $i < $numberOfCells; $i++) {
        $style = getAppointmentStyle($appointment, $i == 0, $i == $numberOfCells - 1);

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

        $cellKey = $startInSeconds + $i * SECONDS_PER_HOUR;
        $classes .= " appointment-id-$appointmentId";
        $cellContent[$cellKey] = "<div style=\"$style\" class=\"$classes\"><span>$text</span></div>";
    }
}
?>

<?php
require 'dialogCreateAppointment.php';
require 'dialogEditAppointment.php';
require 'dialogTags.php';
require 'dialogShare.php';
require 'dialogDeleteAccount.php';
require '../templates/error/dialogError.php';
?>

<div class="desktop-only container pl-0 mw-100">
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
                    <a type=submit class="dropdown-item w-100" id="delete-account" onclick="deleteAccount()">
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
                <button class="btn-edit-appointment btn btn-secondary w-100 mb-2 toggleEdit" disabled>
                    Edit Appointment
                </button>
            </div>
            <div class="ml-0 row">
                <form action="/appointment/delete" method="post" class="w-100">
                    <input type="hidden" name="id" value="" class="delete-appointment-id">
                    <button type="submit" class="btn-delete-appointment btn btn-secondary w-100 mb-2" disabled>
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
                        echo "
                            <div class=\"row mt-2 align-items-center\">
                                <span style=\"background-color: $color\" class=\"mr-2 color-block\"></span>
                                <span class=\"align-middle\">$tag->name</span>
                            </div>
                            ";
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
                        $dateTimeInSeconds = $startDate->getTimestamp() + ($j * SECONDS_PER_DAY) + ($i * SECONDS_PER_HOUR);

                        // Get and display the cell content from the array
                        $content = isset($cellContent[$dateTimeInSeconds]) ? $cellContent[$dateTimeInSeconds] : "";
                        echo "<td class=\"cell-appointment p-0 align-top\">$content</td>";
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
<div class="mobile-only container px-4">
    <div class="row d-flex flex-column align-items-center">
        <h1 class="text-center">
            Mondate
        </h1>
    </div>
    <div class="row d-flex flex-column align-items-center pb-5">
        <img src="/images/logo.png" class="card-img-top" alt="The Mondate Logo">
    </div>
    <div class="row">
        <button class="btn btn-secondary w-100 mb-2 toggleCreate">
            Create Appointment
        </button>
    </div>
    <div class="row">
        <button class="btn-edit-appointment btn btn-secondary w-100 mb-2 toggleEdit" disabled>
            Edit Appointment
        </button>
    </div>
    <div class="row">
        <form action="/appointment/delete" method="post" class="w-100">
            <input type="hidden" name="id" value="" class="delete-appointment-id">
            <button type="submit" class="btn-delete-appointment btn btn-secondary w-100 mb-2" disabled>
                Delete Appointment
            </button>
        </form>
    </div>
    <div class="row mb-3">
        <button class="btn btn-secondary w-100 mb-2 refresh">
            Refresh
        </button>
    </div>
    <div class="row d-flex justify-content-center mb-1">
        <span id="scope-identifier">
                <?php
                echo $startDate->format('d.m.Y').' - '.$endDate->format('d.m.Y');
                ?>
            </span>
    </div>
    <div class="row d-flex justify-content-center mb-5">
        <a href="/calendar/last" class="btn btn-secondary w-25 mr-2">
            Last
        </a>
        <a href="/calendar/next" class="btn btn-secondary w-25 ml-2">
            Next
        </a>
    </div>
    <div>
        <?php
        for($i = 0; $i < sizeof(COLUMNS); $i++) {
            $current_date = clone $startDate;
            $current_date->add(date_interval_create_from_date_string($i . ' day'));

            echo "<div class=\"row pb-3\">";
            echo "<h2 class=\"font-weight-bold h4\">" . COLUMNS[$i] . " " . $current_date->format('d.m.Y') . "</h2>";
            echo "<div class=\"w-100 mobile-appointment-container\">";

            foreach($appointments as $appointment) {
                if($appointment->getDate() === $current_date->format("Y-m-d")) {
                    $appointmentId = $appointment->getId();
                    $text = $appointment->getName() . " (" .$appointment->getStart() . " - " . $appointment->getEnd() . ")";

                    $style = getAppointmentStyle($appointment, false, false);
                    $classes = "w-100 p-0 align-middle appointment appointment-top-bottom";
                    $classes .= " appointment-id-$appointmentId";
                    echo "<div style=\"$style\" class=\"$classes\"><span>$text</span></div>";
                }
            }

            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>

    <div class="ml-0 row mt-5 mb-5">
        <h2 class="h5">Tags</h2>
        <div class="container">
            <?php
            foreach($tags as $tag) {
                $color = '#'.$tag->color;
                echo "
                <div class=\"row mt-2 align-items-center\">
                    <span style=\"background-color: $color\" class=\"mr-2 color-block\"></span>
                    <span class=\"align-middle\">$tag->name</span>
                </div>
                ";
            }
            ?>
        </div>
    </div>
    <div class="dropdown float-right w-100 mb-5">
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
            <a class="dropdown-item w-100" id="delete-account" onclick="deleteAccount()">
                Delete Account
            </a>
        </div>
    </div>
</div>

<script type="text/javascript" src="/js/calendar.js"></script>
<script type="text/javascript" src="/js/cookies.js"></script>
<script type="text/javascript" src="/js/error.js"></script>