<?php

const COLUMNS = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

const SECONDS_PER_HOUR = 60 * 60;
const SECONDS_PER_DAY = SECONDS_PER_HOUR * 24;

function index_to_time($index)
{
    return str_pad($index, 2, '0', STR_PAD_LEFT) . ":00";
}

?>

<?php
$cell_content = array();

foreach ($appointments as $appointment) {
    $date_as_string = $appointment->date . ' ' . $appointment->start;
    $id = DateTime::createFromFormat('Y-m-d H:i:s', $date_as_string)->getTimestamp();

    $start_in_seconds = DateTime::createFromFormat('H:i:s', $appointment->start)->getTimestamp();
    $end_in_seconds = DateTime::createFromFormat('H:i:s', $appointment->end)->getTimestamp();
    $duration_in_seconds = $end_in_seconds - $start_in_seconds;
    $number_of_cells = $duration_in_seconds / SECONDS_PER_HOUR;

    for ($i = 0; $i < $number_of_cells; $i++) {
        $cell_content[$id+$i * 3600] = "<button class=\"btn btn-primary m-0 w-100\">$appointment->name</button>";
    }
}
?>

<div class="container mw-100">
    <div class="row pb-4">
        <div class="col">
            <img src="/images/logo.png"
                 class="card-img-top"
                 alt="The Mondate Logo"
                 style="width: 100px">
        </div>
        <div class="col">
            <form action="/logout/" method="post">
                <button type=submit class="btn btn-secondary w-50 mb-2">Logout</button>
            </form>
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
        </div>
        <div class="col-10">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <?php
                    foreach (COLUMNS as $column) {
                        echo "<th scope=\"col\" class=\"text-center\">$column</th>";
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                for ($i = 0; $i < 24; $i++) {
                    echo "<tr>";
                    echo "<th scope=\"row\" class=\"p-0 align-middle\">" . index_to_time($i) . "</th>";

                    for ($j = 0; $j < sizeof(COLUMNS); $j++) {
                        $id = $start_date + ($j * SECONDS_PER_DAY) + ($i * SECONDS_PER_HOUR);
                        $content = isset($cell_content[$id]) ? $cell_content[$id] : "";
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
            <button id="btn-last" class="btn btn-secondary px-5">Last</button>
            <span id="scope-identifier">
                <?php
                echo date('d.m.Y', $start_date) . ' - ' . date('d.m.Y', $end_date);
                ?>
            </span>
            <button id="btn-next" class="btn btn-secondary px-5">Next</button>
        </div>
    </div>
</div>

<script type="text/javascript" src="/js/calendar.js"></script>