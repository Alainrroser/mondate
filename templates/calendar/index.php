<?php

use App\Util\DateTimeUtils;

const COLUMNS = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
const SECONDS_PER_HOUR = 60 * 60;
const APPOINTMENT_HEIGHT = 50;

function getAppointmentStyle($appointment, $margin, $height) {
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

    $style .= "margin-top: " . $margin . "px;";
    $style .= "height: " . $height . "px;";

    return $style;
}

function createAppointmentContainer($appointment, $margin, $height) {
    $appointmentId = $appointment->getId();
    $style = getAppointmentStyle($appointment, $margin, $height);
    $text = $appointment->getName();

    $classes = "w-100 p-0 align-middle appointment appointment-id-$appointmentId";
    return "<div style=\"$style\" class=\"$classes\"><span>$text</span></div>";
}

function getCellContent($cellDate, $cellStart, $cellEnd, $appointments, $isFirstCellOfDay) {
    $content = "";

    foreach($appointments as $appointment) {
        $appointmentStartDate = DateTimeUtils::extractDate($appointment->getStartAsDateTime());
        $appointmentEndDate = DateTimeUtils::extractDate($appointment->getEndAsDateTime());

        // Get the start in seconds relative to the current day
        // For example: An appointment starting at 08:30 would result in a value of 8.5 * 60 * 60 = 30600
        $startInSeconds = $appointment->getStartAsDateTime()->getTimestamp() - $appointmentStartDate->getTimestamp();
        $endInSeconds = $appointment->getEndAsDateTime()->getTimestamp() - $appointmentEndDate->getTimestamp();

        if($appointmentStartDate == $appointmentEndDate) {
            // The appointment only lasts for one day

            if($appointment->getStartAsDateTime() >= $cellStart && $appointment->getStartAsDateTime() < $cellEnd) {
                // The appointment starts in the current cell

                $height = APPOINTMENT_HEIGHT * ($endInSeconds - $startInSeconds) / SECONDS_PER_HOUR;
                $margin = APPOINTMENT_HEIGHT * ($startInSeconds % SECONDS_PER_HOUR) / SECONDS_PER_HOUR;
                $content .= createAppointmentContainer($appointment, $margin, $height);
            }
        } else {
            if($cellDate == $appointmentStartDate) {
                // The appointment starts on the current date

                if($appointment->getStartAsDateTime() >= $cellStart && $appointment->getStartAsDateTime() <= $cellEnd) {
                    // The appointment starts in the current cell

                    $margin = APPOINTMENT_HEIGHT * ($startInSeconds % SECONDS_PER_HOUR) / SECONDS_PER_HOUR;
                    $height = APPOINTMENT_HEIGHT * (24 - ($startInSeconds / SECONDS_PER_HOUR));
                    $content .= createAppointmentContainer($appointment, $margin, $height);
                }
            } else if($cellDate > $appointmentStartDate && $cellDate < $appointmentEndDate) {
                // The appointment starts before the current date and ends afterwards

                if($isFirstCellOfDay) {
                    $height = APPOINTMENT_HEIGHT * 24; // Make the appointment fill the entire day
                    $content .= createAppointmentContainer($appointment, 0, $height);
                }
            } else if($cellDate == $appointmentEndDate) {
                // The appointment ends on the current date

                if($isFirstCellOfDay) {
                    $height = APPOINTMENT_HEIGHT * ($endInSeconds / SECONDS_PER_HOUR);
                    $content .= createAppointmentContainer($appointment, 0, $height);
                }
            }
        }
    }

    return $content;
}

?>

<?php
require 'dialogCreateAppointment.php';
require 'dialogEditAppointment.php';
require 'dialogTags.php';
require 'dialogShare.php';
require 'dialogDeleteAccount.php';
require 'dialogSearch.php';
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
                <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdown-menu-button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Account
                </button>
                <div class="dropdown-menu dropdown-menu-right w-100"
                     aria-labelledby="dropdown-menu-button">
                    <a href="/signOut" class="dropdown-item w-100">
                        Sign Out
                    </a>
                    <a href="/user/changePassword" class="dropdown-item w-100">
                        Change Password
                    </a>
                    <a class="dropdown-item w-100 delete-account">
                        Delete Account
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="container col px-5">
            <div class="ml-0 row">
                <button class="btn btn-secondary w-100 mb-2 toggle-create">
                    Create Appointment
                </button>
            </div>
            <div class="ml-0 row">
                <button class="btn-edit-appointment btn btn-secondary w-100 mb-2" disabled>
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
                <button class="btn btn-secondary w-100 mb-2 toggle-search">
                    Search
                </button>
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
                    for($i = 0; $i < sizeof(COLUMNS); $i++) {
                        $current_date = clone $startDate;
                        $current_date->add(date_interval_create_from_date_string($i . ' day'));

                        $content = COLUMNS[$i] . "<br>" . $current_date->format('d.m.Y');
                        echo "<th scope=\"col\" class=\"text-center\">$content</th>";
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                for($i = 0; $i < 24; $i++) {
                    echo "<tr>";
                    echo "<th scope=\"row\" class=\"p-0 pt-1 align-top\">";
                    echo str_pad($i, 2, '0', STR_PAD_LEFT).":00";
                    echo "</th>";

                    for($j = 0; $j < sizeof(COLUMNS); $j++) {
                        $cellDate = clone $startDate;
                        $cellDate->add(date_interval_create_from_date_string($j . " days"));

                        $cellStart = clone $cellDate;
                        $cellStart->add(date_interval_create_from_date_string($i . " hours"));

                        $cellEnd = clone $cellStart;
                        $cellEnd->add(date_interval_create_from_date_string("59 minutes"));

                        $isFirstCellOfDay = $i == 0;
                        $content = getCellContent($cellDate, $cellStart, $cellEnd, $appointments, $isFirstCellOfDay);

                        $id = "appointment-cell-" . $cellStart->getTimestamp();
                        echo "<td class=\"appointment-cell cell-appointment p-0 align-top\" id=\"$id\">$content</td>";
                    }

                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row float-right pt-3 w-100">
        <div class="col w-100 d-flex flex-row justify-content-end align-items-center">
            <a href="/calendar/last" class="btn btn-secondary px-5 mr-3">
                Last
            </a>
            <input type="date" class="input-week-start" value="<?= $startDate->format('Y-m-d') ?>">
            <span class="ml-2 mr-2">-</span>
            <span class="week-end">
                <?= $endDate->format('d.m.Y') ?>
            </span>
            <a href="/calendar/next" class="btn btn-secondary px-5 ml-3">
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
        <button class="btn btn-secondary w-100 mb-2 toggle-create">
            Create Appointment
        </button>
    </div>
    <div class="row">
        <button class="btn-edit-appointment btn btn-secondary w-100 mb-2 toggle-edit" disabled>
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
        <button class="btn btn-secondary w-100 mb-2 toggle-search">
            Search
        </button>
    </div>
    <div class="row mb-3">
        <button class="btn btn-secondary w-100 mb-2 refresh">
            Refresh
        </button>
    </div>
    <div class="row d-flex justify-content-center mb-1">
        <input type="date" class="input-week-start" value="<?= $startDate->format('Y-m-d') ?>">
        <span class="ml-2 mr-2">-</span>
        <span class="week-end">
            <?= $endDate->format('d.m.Y') ?>
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
        // Sort the appointments by time and date
        function sortAppointmentsByTime($a, $b) {
            $timeA = strtotime($a->getStart());
            $timeB = strtotime($b->getStart());
            return $timeA < $timeB ? -1 : 1;
        }

        usort($appointments, "sortAppointmentsByTime");

        for($i = 0; $i < sizeof(COLUMNS); $i++) {
            $current_date = clone $startDate;
            $current_date->add(date_interval_create_from_date_string($i . ' day'));

            echo "<div class=\"row pb-3\">";
            echo "<h2 class=\"font-weight-bold h4\">" . COLUMNS[$i] . " " . $current_date->format('d.m.Y') . "</h2>";
            echo "<div class=\"w-100 mobile-appointment-container\">";

            foreach($appointments as $appointment) {
                if($appointment->getStartAsDateTime()->format("Y-m-d") === $current_date->format("Y-m-d")) {
                    $appointmentId = $appointment->getId();
                    $text = $appointment->getName() . " (" .$appointment->getStart() . " - " . $appointment->getEnd() . ")";

                    $style = getAppointmentStyle($appointment, 0, 49);
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
             aria-labelledby="dropdown-menu-button">
            <a href="/signOut" class="dropdown-item w-100">
                Sign Out
            </a>
            <a href="/user/changePassword" class="dropdown-item w-100">
                Change Password
            </a>
            <a class="dropdown-item w-100 delete-account">
                Delete Account
            </a>
        </div>
    </div>
</div>

<script src="/js/calendar/tags.js"></script>
<script src="/js/calendar/share.js"></script>
<script src="/js/calendar/search.js"></script>
<script src="/js/calendar/calendar.js"></script>
<script src="/js/calendar/localStorage.js"></script>
<script src="/js/error.js"></script>