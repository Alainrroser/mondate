<?php
const COLUMNS = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");

function index_to_time($index)
{
    return str_pad($index, 2, '0', STR_PAD_LEFT) . ":00";
}
?>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="row">
                <button class="btn btn-primary w-100">Create Appointment</button>
            </div>
            <div class="row">
                <button class="btn btn-primary w-100">Edit Appointment</button>
            </div>
            <div class="row">
                <button class="btn btn-primary w-100">Delete Appointment</button>
            </div>
        </div>
        <div class="col-10">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col"></th>
                    <?php
                    foreach (COLUMNS as $column) {
                        echo "<th scope=\"col\">$column</th>";
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                for ($i = 0; $i < 24; $i++) {
                    echo "<tr>";
                    echo "<th scope=\"row\">" . index_to_time($i) . "</th>";

                    for ($j = 0; $j < sizeof(COLUMNS); $j++) {
                        if ($i == 3 && $j == 3) {
                            echo "<td><button class=\"btn btn-primary\"></button></td>";
                        } else {
                            echo "<td></td>";
                        }
                    }

                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>